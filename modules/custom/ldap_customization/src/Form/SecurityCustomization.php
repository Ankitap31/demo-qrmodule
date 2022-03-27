<?php

namespace Drupal\ldap_customization\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\user\Entity\Role;


/**
 * Displays the domain_customization settings form.
 */
class SecurityCustomization extends FormBase {

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormID().
   */
  public function getFormId() {
    return 'security_customization';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
     $roles = \Drupal::entityTypeManager()->getStorage('user_role')->loadMultiple();
	 
	 foreach ($roles as $role_object) {
	   if (in_array ($role_object->Id(), array("manager"))) {
		 continue;
	   }
	   $form['group_' . $role_object->Id()] = [
         '#type' => 'details',
         '#title' => $this->t("CWAM grants for role '". $role_object->Label() ."'"),
	     '#description' => $this->t("Please set CWAM grants for specified role."),
	     '#expandable' => TRUE,
	     '#expanded' => FALSE,
       ]; 
	   $options = array(
	     "view" => $this->t("View"),
		 "update" => $this->t("Update"),
		 "delete" => $this->t("Delete"),
	   );
	   $form['group_' . $role_object->Id()]['CWAM_grants_'.$role_object->Id()] = [
         '#type' => 'checkboxes',
	     '#options' => $options,
       ]; 
	 }
	 
	 $form["op"] = [
       '#type' => 'submit',
	   '#value' => $this->t("Submit"),
     ]; 

	return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
