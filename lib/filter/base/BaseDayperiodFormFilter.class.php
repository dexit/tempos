<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
* Dayperiod filter form base class.
*
* @package    tempos
* @subpackage filter
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseDayperiodFormFilter extends BaseFormFilterPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'start'       => new sfWidgetFormFilterDate(array(
				'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
				'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => false)),
		'stop'        => new sfWidgetFormFilterDate(array(
				'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
				'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => false)),
		'day_of_week' => new sfWidgetFormFilterInput(),
		'Room_id'     => new sfWidgetFormPropelChoice(array('model' => 'Room', 'add_empty' => true)),
		));

		$this->setValidators(array(
		'start'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'stop'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'day_of_week' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'Room_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Room', 'column' => 'id')),
		));

		$this->widgetSchema->setNameFormat('dayperiod_filters[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'Dayperiod';
	}

	public function getFields()
	{
		return array(
		'id'          => 'Number',
		'start'       => 'Date',
		'stop'        => 'Date',
		'day_of_week' => 'Number',
		'Room_id'     => 'ForeignKey',
		);
	}
}
