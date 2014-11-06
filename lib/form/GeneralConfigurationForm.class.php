<?php

/**
 * General Configuration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class GeneralConfigurationForm extends sfForm
{
  public function configure()
  {
	$this->widgetSchema['allow_registration'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['allow_registration'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('allow_registration', 'Allow registration');

	$this->widgetSchema['registration_granted_message'] = new sfWidgetFormTextarea();
	$this->validatorSchema['registration_granted_message'] = new sfValidatorString(array('required' => false));
	$this->widgetSchema->setLabel('registration_granted_message', 'Registration granted message');

	$this->widgetSchema['subtitle'] = new sfWidgetFormInput();
	$this->validatorSchema['subtitle'] = new sfValidatorString(array('required' => false));
	$this->widgetSchema->setLabel('subtitle', 'Subtitle');

    $this->widgetSchema['auth_ldap_choice'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['auth_ldap_choice'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('auth_ldap_choice', 'Use LDAP authentication');
    
    $this->widgetSchema['auth_ldap_host'] = new sfWidgetFormInput();
	$this->validatorSchema['auth_ldap_host'] = new sfValidatorString(array('required' => false));
	$this->widgetSchema->setLabel('auth_ldap_host', 'LDAP Host');
    
    $this->widgetSchema['auth_ldap_domain'] = new sfWidgetFormInput();
	$this->validatorSchema['auth_ldap_domain'] = new sfValidatorString(array('required' => false));
	$this->widgetSchema->setLabel('auth_ldap_domain', 'LDAP Domain name');
    
    // Enabled by default
	if (!ConfigurationHelper::hasParameter('General', 'allow_registration'))
	{
		ConfigurationHelper::setParameter('General', 'allow_registration', true);
	}
    
    // Disabled by default
    if (!ConfigurationHelper::hasParameter('General', 'auth_ldap_choice'))
	{
		ConfigurationHelper::setParameter('General', 'auth_ldap_choice', false);
	}

	$this->setDefaults(ConfigurationHelper::getNamespace('General'));

    $this->widgetSchema->setNameFormat('generalConfiguration[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
