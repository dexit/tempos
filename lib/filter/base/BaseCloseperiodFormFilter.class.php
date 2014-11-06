<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
* Closeperiod filter form base class.
*
* @package    tempos
* @subpackage filter
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseCloseperiodFormFilter extends BaseFormFilterPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'start'   => new sfWidgetFormFilterDate(array(
				'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
				'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => false)),
		'stop'    => new sfWidgetFormFilterDate(array(
				'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
				'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => false)),
		'Room_id' => new sfWidgetFormPropelChoice(array('model' => 'Room', 'add_empty' => true)),
		'reason'  => new sfWidgetFormFilterInput(),
		));

		$this->setValidators(array(
		'start'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'stop'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'Room_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Room', 'column' => 'id')),
		'reason'  => new sfValidatorPass(array('required' => false)),
		));

		$this->widgetSchema->setNameFormat('closeperiod_filters[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'Closeperiod';
	}

	public function getFields()
	{
		return array(
		'id'      => 'Number',
		'start'   => 'Date',
		'stop'    => 'Date',
		'Room_id' => 'ForeignKey',
		'reason'  => 'Text',
		);
	}
}
