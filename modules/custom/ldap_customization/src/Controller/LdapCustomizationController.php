<?php

namespace Drupal\ldap_customization\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\ldap_query\Controller;
use Drupal\ldap_query\Controller\QueryController;
/*use Drupal\domain\DomainInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Link;
use Drupal\Core\Url;*/

/**
 * Controller for domain customization.
 */
class LdapCustomizationController extends ControllerBase {
  
  /**
  * Construct
  */  
  public function __construct() {
  }
  
  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content() {
	  $query_id = "test_query";
	  $controller = new QueryController($query_id);
	  $filter = "uid=emil.aliev@hpe.com";
      $controller->execute($filter);
      $data = $controller->getRawResults();
	  
	  if ($data['count'] >= 1) {
		foreach ($data[0] as $key => $value) {
		  if ($key == "employeetype" || $key == "hprole" || $key == "hpbusinessgroup" || $key == "hpjobfunction") {
			//echo "Key is ----- " . $key . "<br/>"; 
		    //echo "Value is ----- ";
		    //print_r($value);
		    echo "<hr/>";
		    $employee[$key] = $value[0];
		  }
		}
	  }
	  echo "Post processed data is as follow <br>";
	  //echo "<pre>"; print_r($data);
	  print_r($employee);
	  echo "<hr/>";
	  
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Testing ldap query!'),
    ];
	
  }
  
}
