<?php

/**
 * General Configuration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class RenameConfigurationForm extends sfForm
{
  public function configure()
  {
	$this->widgetSchema['activity_module'] = new sfWidgetFormInput();
	$this->validatorSchema['activity_module'] = new sfValidatorString(array('required' => false));
	$this->widgetSchema->setLabel('activity_module', 'Activities module');

	$this->widgetSchema['activity_label'] = new sfWidgetFormInput();
	$this->validatorSchema['activity_label'] = new sfValidatorString(array('required' => false));
	$this->widgetSchema->setLabel('activity_label', 'Activities item');
	
	$this->widgetSchema['reservation_custom_field_1'] = new sfWidgetFormInput();
	$this->validatorSchema['reservation_custom_field_1'] = new sfValidatorString(array('required' => false));
	$this->widgetSchema->setLabel('reservation_custom_field_1', 'Free field 1');
	
	$this->widgetSchema['reservation_custom_field_2'] = new sfWidgetFormInput();
	$this->validatorSchema['reservation_custom_field_2'] = new sfValidatorString(array('required' => false));
	$this->widgetSchema->setLabel('reservation_custom_field_2', 'Free field 2');
	
	$this->widgetSchema['reservation_custom_field_3'] = new sfWidgetFormInput();
	$this->validatorSchema['reservation_custom_field_3'] = new sfValidatorString(array('required' => false));
	$this->widgetSchema->setLabel('reservation_custom_field_3', 'Free field 3');

	$this->setDefaults(ConfigurationHelper::getNamespace('Rename'));

	$this->widgetSchema->setNameFormat('renameConfiguration[%s]');

	$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
