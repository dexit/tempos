<?php

/**
* Reporting form.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
*/
class ReportingForm extends sfForm
{
	public function configure()
	{
        $i18n = sfContext::getInstance()->getI18N();
		$active_choices = array(
            null => $i18n->__('Any'),
            true => $i18n->__('Yes'),
            false => $i18n->__('No'),
		);
		
		$activity_name = ConfigurationHelper::getParameter('Rename', 'activity_label');	
		$free_field_1_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_1');	
		$free_field_2_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_2');
		$free_field_3_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_3');

		if (empty($activity_name))
		{
			$activity_name = $i18n->__('Activity');
		}
		
		$fields_choices = array(
            $i18n->__('Date'),          // 0
            $i18n->__('User'),          // 1
            $activity_name,             // 2
            $i18n->__('Duration'),      // 3
            $i18n->__('Room'),          // 4
            $i18n->__('Reason'),        // 5
            $i18n->__('Comment'),       // 6
            $i18n->__('Group'),         // 7
            $i18n->__('Members count'), // 8
            $i18n->__('Guests count'),  // 9
            $i18n->__('Status'),        // 10
            $i18n->__('Price'),         // 11
            $i18n->__('Features'),      // 12
		);
        
        if (!empty($free_field_1_name)) {
            $fields_choices[90] = $free_field_1_name;   // 91
        }
        if (!empty($free_field_2_name)) {
            $fields_choices[91] = $free_field_2_name;   // 92
        }
        if (!empty($free_field_3_name)) {
            $fields_choices[92] = $free_field_3_name;   // 93
        }
		
		$this->setWidgets(array(
            'users'			=> new sfWidgetFormPropelChoiceMany(array('model' => 'User', 'add_empty' => false, 'expanded' => true)),
            'usergroups'	=> new sfWidgetFormPropelChoiceMany(array('model' => 'Usergroup', 'add_empty' => false, 'expanded' => true)),
            'activities'	=> new sfWidgetFormPropelChoiceMany(array('model' => 'Activity', 'add_empty' => false, 'expanded' => true)),
            'zones'			=> new sfWidgetFormPropelChoiceMany(array('model' => 'Zone', 'add_empty' => false, 'expanded' => true)),
            'rooms'			=> new sfWidgetFormPropelChoiceMany(array('model' => 'Room', 'add_empty' => false, 'expanded' => true)),
            'begin_date'	=> new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
            'end_date'		=> new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
            'fields'		=> new sfWidgetFormChoiceMany(array('choices' => $fields_choices, 'expanded' => true)),
		));

		$begin_years = range(date('Y') - 128, date('Y') + 20);
		$end_years = range(date('Y') - 128, date('Y') + 20);
		$this->widgetSchema['begin_date']->setOption('years', array_combine($begin_years, $begin_years));
		$this->widgetSchema['end_date']->setOption('years', array_combine($end_years, $end_years));

		$this->setDefault('begin_date', date('Y/m/d', strtotime('- 1 month')));
		$this->setDefault('end_date', date('Y/m/d'));
        // Select default selected filters
		$this->setDefault('fields', array(0, 1, 2, 3, 4, 7));   // array_keys($fields_choices));

		$this->setValidators(array(
            'users'			=> new sfValidatorPropelChoiceMany(array('model' => 'User', 'column' => 'id', 'required' => false)),
            'usergroups'	=> new sfValidatorPropelChoiceMany(array('model' => 'Usergroup', 'column' => 'id', 'required' => false)),
            'activities'	=> new sfValidatorPropelChoiceMany(array('model' => 'Activity', 'column' => 'id', 'required' => false)),
            'zones'	=> new sfValidatorPropelChoiceMany(array('model' => 'Zone', 'column' => 'id', 'required' => false)),
            'rooms'	=> new sfValidatorPropelChoiceMany(array('model' => 'Room', 'column' => 'id', 'required' => false)),
            'begin_date'	=> new sfValidatorDate(array('required' => true)),
            'end_date'		=> new sfValidatorDate(array('required' => true)),
            'fields'		=> new sfValidatorChoiceMany(array('choices' => array_keys($fields_choices), 'required' => true)),
		));
	
		$this->widgetSchema->setLabels(array(
            'users'			=> 'Users',
            'usergroups'	=> 'Groups',
            'activities'	=> $activity_name,
            'zones'			=> 'Zones',
            'rooms'			=> 'Rooms',
            'begin_date'	=> 'Start date',
            'end_date'		=> 'Stop date',
            'fields'		=> 'Fields',
		));

		$this->widgetSchema->setNameFormat('reporting[%s]');

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
