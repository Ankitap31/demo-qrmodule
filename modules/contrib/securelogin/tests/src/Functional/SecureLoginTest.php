<?php

namespace Drupal\Tests\securelogin\Functional;

/**
 * Basic tests for Secure login module.
 *
 * @group Secure login
 */
class SecureLoginTest extends SecureLoginTestBase {

  /**
   * Ensure a request over HTTP gets 301 redirected to HTTPS.
   */
  public function testHttpSecureLogin() {
    global $base_url;
    // Disable redirect following.
    $maximumMetaRefreshCount = $this->maximumMetaRefreshCount;
    $this->maximumMetaRefreshCount = 0;
    if (method_exists($this->getSession()->getDriver(), 'getClient')) {
      $this->getSession()->getDriver()->getClient()->followRedirects(FALSE);
    }
    $this->drupalGet($this->httpUrl('user/login'));
    $this->assertSession()->statusCodeEquals(301);
    $this->assertSame(0, strpos($this->getSession()->getResponseHeader('Location'), str_replace('http://', 'https://', $base_url)), 'Location header uses the secure base URL.');
    if (method_exists($this->getSession()->getDriver(), 'getClient')) {
      $this->getSession()->getDriver()->getClient()->followRedirects(TRUE);
    }
    $this->maximumMetaRefreshCount = $maximumMetaRefreshCount;
  }

  /**
   * Ensure HTTPS requests do not get redirected.
   */
  public function testHttpsSecureLogin() {
    $this->drupalGet($this->httpsUrl('user/login'));
    $this->assertSession()->statusCodeEquals(200);

    $xpath = $this->xpath('//form[@id="user-login-form"]');
    $this->assertSame(1, count($xpath), 'The user is on the login form.');
  }

}
