<?php

/**
* Occupancy form.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
*/
class OccupancyForm extends sfForm
{
	public function configure()
	{
		$this->setWidgets(array(
		'zone'			=> new sfWidgetFormZoneChoice(array('current_zone' => null)),
		'activities'	=> new sfWidgetFormPropelChoiceMany(array('model' => 'Activity', 'add_empty' => false, 'expanded' => true)),
		'begin_date'	=> new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
		'end_date'		=> new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
		));

		$years = range(date('Y') - 128, date('Y'));
		$this->widgetSchema['begin_date']->setOption('years', array_combine($years, $years));
		$this->widgetSchema['end_date']->setOption('years', array_combine($years, $years));

		$this->setDefault('begin_date', date('Y/m/d', strtotime('- 1 month')));
		$this->setDefault('end_date', date('Y/m/d'));

		$this->setValidators(array(
		'zone'			=> new sfValidatorZoneChoice(array('current_zone' => null, 'required' => false)),
		'activities'	=> new sfValidatorPropelChoiceMany(array('model' => 'Activity', 'column' => 'id', 'required' => false)),
		'begin_date'	=> new sfValidatorDate(array('required' => true)),
		'end_date'		=> new sfValidatorDate(array('required' => true)),
		));

		$activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');
		if (is_null($activityItem) || empty($activityItem))
		{
			$activityItem = 'Activities';
		}
		
		$this->widgetSchema->setLabels(array(
		'zone'				=> 'Zone',
		'activities'	=> $activityItem,
		'begin_date'	=> 'Start date',
		'end_date'		=> 'Stop date',
		));

		$this->widgetSchema->setNameFormat('occupancy[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		// Post validators
		$this->validatorSchema->setPostValidator(
		new sfValidatorSchemaCompare('end_date', sfValidatorSchemaCompare::GREATER_THAN_EQUAL, 'begin_date', array(),
		array(
		'invalid' => 'The stop date must be after the start date.',
		)
		)
		);
	}

}
