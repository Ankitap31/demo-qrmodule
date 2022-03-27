<?php

namespace Drupal\securelogin;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\Core\Url;
use Drupal\user\Plugin\Block\UserLoginBlock;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Defines the secure login service.
 */
class SecureLoginManager implements TrustedCallbackInterface {

  /**
   * Form action placeholder for the form builder.
   */
  const FORM_PLACEHOLDER = 'form_action_p_pvdeGsVG5zNF_XLGPTvYSKCf43t8qZYSwcfZl2uzM';

  /**
   * Form action placeholder for the user login block.
   */
  const USER_PLACEHOLDER = 'form_action_p_4r8ITd22yaUvXM6SzwrSe9rnQWe48hz9k1Sxto3pBvE';

  /**
   * Configured secure login settings.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * The event dispatcher service.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Constructs the secure login service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EventDispatcherInterface $event_dispatcher, RequestStack $request_stack, FormBuilderInterface $form_builder = NULL) {
    $this->config = $config_factory->get('securelogin.settings');
    $this->eventDispatcher = $event_dispatcher;
    $this->requestStack = $request_stack;
    $this->request = $this->requestStack->getCurrentRequest();
    $this->formBuilder = $form_builder;
  }

  /**
   * Rewrites the form action to use the secure base URL.
   */
  public function secureForm(&$form) {
    // Rebuild this form on based on the actual URL.
    $form['#cache']['contexts'][] = 'securelogin';
    // Flag form as secure for theming purposes.
    $form['#https'] = TRUE;
    if ($this->request->isSecure()) {
      return;
    }
    // Redirect to secure page, if enabled.
    if ($this->config->get('secure_forms')) {
      // Disable caching, as this form must be rebuilt to set the redirect.
      $form['#cache']['max-age'] = 0;
      $this->secureRedirect();
    }
    $form['#action'] = $this->secureUrl($form['#action']);
    if ($form['#action'] === self::FORM_PLACEHOLDER) {
      $form['#attached']['placeholders'][self::FORM_PLACEHOLDER] = [
        '#lazy_builder' => [
          'securelogin.manager:renderPlaceholderFormAction',
          [$form['#attached']['placeholders'][self::FORM_PLACEHOLDER]['#lazy_builder'][0]],
        ],
      ];
    }
    $form['#after_build'][] = 'Drupal\securelogin\SecureLoginManager::afterBuild';
  }

  /**
   * Reimplement core form securing with placeholders.
   */
  public static function afterBuild(array $form, FormStateInterface $form_state) {
    global $base_root;
    if (!empty($form['#https']) && isset($form['#action']) && $form['#action'] === str_replace('http://', 'https://', $base_root) . self::FORM_PLACEHOLDER) {
      $form['#action'] = self::FORM_PLACEHOLDER;
    }
    return $form;
  }

  /**
   * Redirects a request to the same path on the secure base URL.
   */
  public function secureRedirect() {
    // Do not redirect from HTTPS requests.
    if ($this->request->isSecure()) {
      return;
    }
    $status = $this->getRedirectStatus();
    // Build the redirect URL from the master request.
    $method = method_exists($this->requestStack, 'getMainRequest') ? 'getMainRequest' : 'getMasterRequest';
    $request = $this->requestStack->$method();
    // Request may be a 404 so handle as unrouted URI.
    $url = Url::fromUri("internal:{$request->getPathInfo()}");
    $url->setOption('absolute', TRUE)
      ->setOption('external', FALSE)
      ->setOption('https', TRUE)
      ->setOption('query', $request->query->all());
    // Create listener to set the redirect response.
    $listener = function ($event) use ($url, $status) {
      $response = new TrustedRedirectResponse($url->toString(), $status);
      // Page cache has a fatal error if cached response has no Expires header.
      $response->setExpires(\DateTime::createFromFormat('j-M-Y H:i:s T', '19-Nov-1978 05:00:00 UTC'));
      // Add cache context for this redirect.
      $response->addCacheableDependency(new SecureLoginCacheableDependency());
      $event->setResponse($response);
      // Redirect URL has destination so consider this the final destination.
      $event->getRequest()->query->set('destination', '');
    };
    // Add listener to response event at high priority.
    $this->eventDispatcher->addListener(KernelEvents::RESPONSE, $listener, 222);
  }

  /**
   * Rewrites a URL to use the secure base URL.
   */
  public function secureUrl($url) {
    global $base_path, $base_secure_url;
    // Set the form action to use secure base URL in place of base path.
    if (strpos($url, $base_path) === 0) {
      $base_url = $this->config->get('base_url') ?: $base_secure_url;
      return substr_replace($url, $base_url, 0, strlen($base_path) - 1);
    }
    // Or if a different domain is being used, forcibly rewrite to HTTPS.
    return str_replace('http://', 'https://', $url);
  }

  /**
   * Lazy builder callback; renders a form action URL including destination.
   *
   * @return array
   *   A renderable array representing the form action.
   *
   * @see \Drupal\Core\Form\FormBuilder::renderPlaceholderFormAction()
   */
  public function renderPlaceholderFormAction($callback = NULL) {
    switch ($callback) {

      case 'form_builder:renderPlaceholderFormAction':
        // This method is not defined in the interface, so check that it exists.
        $action = method_exists($this->formBuilder, 'renderPlaceholderFormAction') ? $this->formBuilder->renderPlaceholderFormAction() : [];
        break;

      default:
        $action = UserLoginBlock::renderPlaceholderFormAction();

    }
    $action['#markup'] = $this->secureUrl($action['#markup']);
    return $action;
  }

  /**
   * Determines proper redirect status based on request method.
   */
  public function getRedirectStatus() {
    // If necessary, use a 308 redirect to avoid losing POST data.
    return $this->request->isMethodCacheable() ? RedirectResponse::HTTP_MOVED_PERMANENTLY : RedirectResponse::HTTP_PERMANENTLY_REDIRECT;
  }

  /**
   * Returns list of trusted callbacks.
   */
  public static function trustedCallbacks() {
    return ['renderPlaceholderFormAction', 'userLoginBlockPreRender'];
  }

  /**
   * Pre-render callback to re-secure the user login block.
   */
  public function userLoginBlockPreRender($build) {
    if (!empty($build['content']['user_login_form']['#https'])) {
      $this->secureForm($build['content']['user_login_form']);
      // Handle Drupal 8.4 style action placeholder.
      if (isset($build['content']['user_login_form']['#attached']['placeholders'][self::USER_PLACEHOLDER])) {
        $build['content']['user_login_form']['#attached']['placeholders'][self::USER_PLACEHOLDER] = [
          '#lazy_builder' => [
            'securelogin.manager:renderPlaceholderFormAction',
            [],
          ],
        ];
      }
    }
    return $build;
  }

}
