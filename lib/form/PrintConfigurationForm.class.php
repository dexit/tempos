<?php

/**
 * General Configuration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class PrintConfigurationForm extends sfForm
{
  public function configure()
  {
	$this->widgetSchema['print_reserved_by'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['print_reserved_by'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('print_reserved_by', 'Display "Reserved by"');

	if (!ConfigurationHelper::hasParameter('Print', 'print_reserved_by'))
	{
		ConfigurationHelper::setParameter('Print', 'print_reserved_by', true);
	}


	$this->widgetSchema['print_reserved_for'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['print_reserved_for'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('print_reserved_for', 'Display "Reserved for"');

	if (!ConfigurationHelper::hasParameter('Print', 'print_reserved_for'))
	{
			ConfigurationHelper::setParameter('Print', 'print_reserved_for', true);
	}

	$this->widgetSchema['print_reason'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['print_reason'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('print_reason', 'Display "Reason"');

	$this->widgetSchema['print_time'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['print_time'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('print_time', 'Display "Time"');

	$this->widgetSchema['print_duration'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['print_duration'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('print_duration', 'Display "Duration"');

	$this->widgetSchema['print_custom_field1'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['print_custom_field1'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('print_custom_field1', 'Display "Custom field 1"');

	$this->widgetSchema['print_custom_field2'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['print_custom_field2'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('print_custom_field2', 'Display "Custom field 2"');

	$this->widgetSchema['print_custom_field3'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['print_custom_field3'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('print_custom_field3', 'Display "Custom field 3"');

	$this->widgetSchema['print_status'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['print_status'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('print_status', 'Display "Status"');
	
	$this->widgetSchema['print_profile'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['print_profile'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('print_profile', 'Display "Physical access"');

	$this->widgetSchema['print_title'] = new sfWidgetFormInputCheckbox();
	$this->validatorSchema['print_title'] = new sfValidatorBoolean(array('required' => false));
	$this->widgetSchema->setLabel('print_title', 'Display titles');

	$this->setDefaults(ConfigurationHelper::getNamespace('Print'));

	$this->widgetSchema->setNameFormat('printConfiguration[%s]');

	$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
