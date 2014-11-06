<?php

/**
 * Configuration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class EmailConfigurationForm extends sfForm
{
  public function configure()
  {
		$encryption_methods = array(
			'none' => sfContext::getInstance()->getI18N()->__('No encryption'),
			'ssl' => sfContext::getInstance()->getI18N()->__('SSL'),
			'tls' => sfContext::getInstance()->getI18N()->__('TLS'),
		);

		$this->widgetSchema['use_mail'] = new sfWidgetFormInputCheckbox();
		$this->validatorSchema['use_mail'] = new sfValidatorBoolean(array('required' => false));
		$this->widgetSchema->setLabel('use_mail', 'Use mail');

		$this->widgetSchema['smtp_host'] = new sfWidgetFormInput();
		$this->validatorSchema['smtp_host'] = new sfValidatorString(array('required' => false));
		$this->widgetSchema->setLabel('smtp_host', 'SMTP host');

		$this->widgetSchema['smtp_service'] = new sfWidgetFormInput();
		$this->validatorSchema['smtp_service'] = new sfValidatorInteger(array('min' => 1, 'max' => 65535, 'required' => false));
		$this->widgetSchema->setLabel('smtp_service', 'SMTP port');

		$this->widgetSchema['from'] = new sfWidgetFormInput();
		$this->validatorSchema['from'] = new sfValidatorString(array('required' => false));
		$this->widgetSchema->setLabel('from', 'From');

		$this->widgetSchema['override_from'] = new sfWidgetFormInputCheckbox();
		$this->validatorSchema['override_from'] = new sfValidatorBoolean(array('required' => false));
		$this->widgetSchema->setLabel('override_from', 'Always override from ?');

		$this->widgetSchema['smtp_encryption_method'] = new sfWidgetFormChoice(array('choices' => $encryption_methods));
		$this->validatorSchema['smtp_encryption_method'] = new sfValidatorChoice(array('choices' => array_keys($encryption_methods), 'required' => true));
		$this->widgetSchema->setLabel('smtp_encryption_method', 'Encryption method');

		$this->widgetSchema['smtp_use_authentication'] = new sfWidgetFormInputCheckbox();
		$this->validatorSchema['smtp_use_authentication'] = new sfValidatorBoolean(array('required' => false));
		$this->widgetSchema->setLabel('smtp_use_authentication', 'Use authentication');

		$this->widgetSchema['smtp_username'] = new sfWidgetFormInput();
		$this->validatorSchema['smtp_username'] = new sfValidatorString(array('required' => false));
		$this->widgetSchema->setLabel('smtp_username', 'Username');

		$this->widgetSchema['smtp_password'] = new sfWidgetFormInputPassword();
		$this->validatorSchema['smtp_password'] = new sfValidatorString(array('required' => false));
		$this->widgetSchema->setLabel('smtp_password', 'Password');

		$this->widgetSchema['signature'] = new sfWidgetFormTextarea();
		$this->validatorSchema['signature'] = new sfValidatorString(array('required' => false));
		$this->widgetSchema->setLabel('signature', 'Signature');

		$this->validatorSchema['from'] = new sfValidatorAnd(array(
			$this->validatorSchema['from'],
			new sfValidatorEmail(),
		));

		if (!ConfigurationHelper::hasParameter('Email', 'use_mail'))
		{
			ConfigurationHelper::setParameter('Email', 'use_mail', false);
		}

		if (!ConfigurationHelper::hasParameter('Email', 'smtp_host'))
		{
			ConfigurationHelper::setParameter('Email', 'smtp_host', 'smtp');
		}

		if (!ConfigurationHelper::hasParameter('Email', 'smtp_service'))
		{
			ConfigurationHelper::setParameter('Email', 'smtp_service', '25');
		}

		if (!ConfigurationHelper::hasParameter('Email', 'from'))
		{
			ConfigurationHelper::setParameter('Email', 'from', 'admin@tempos.com');
		}

		if (!ConfigurationHelper::hasParameter('Email', 'signature'))
		{
			ConfigurationHelper::setParameter('Email', 'signature', "\n\n--------------------\n\nThis message was sent to you from the Tempo's system.\n\nGo to http://tempos.islog-services.eu to get more information.");
		}

		$this->setDefaults(ConfigurationHelper::getNamespace('Email'));

    $this->widgetSchema->setNameFormat('emailConfiguration[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

	public function filter($values)
	{
		if (empty($values['smtp_password']) && ConfigurationHelper::hasParameter('Email', 'smtp_password'))
		{
			$values['smtp_password'] = ConfigurationHelper::getParameter('Email', 'smtp_password');
		}

		return $values;
	}
}
