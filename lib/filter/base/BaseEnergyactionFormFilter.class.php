<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
* Energyaction filter form base class.
*
* @package    tempos
* @subpackage filter
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseEnergyactionFormFilter extends BaseFormFilterPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'name'                       => new sfWidgetFormFilterInput(),
		'delayUp'                    => new sfWidgetFormFilterInput(),
		'delayDown'                  => new sfWidgetFormFilterInput(),
		'identifier'                 => new sfWidgetFormFilterInput(),
		'processIdUp'                => new sfWidgetFormFilterInput(),
		'processIdDown'              => new sfWidgetFormFilterInput(),
		'start'                      => new sfWidgetFormFilterDate(array(
				'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
				'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => false)),
		'stop'                       => new sfWidgetFormFilterDate(array(
				'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
				'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => false)),
		'status'                     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
		'room_has_energyaction_list' => new sfWidgetFormPropelChoice(array('model' => 'Room', 'add_empty' => true)),
		));

		$this->setValidators(array(
		'name'                       => new sfValidatorPass(array('required' => false)),
		'delayUp'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'delayDown'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'identifier'                 => new sfValidatorPass(array('required' => false)),
		'processIdUp'                => new sfValidatorPass(array('required' => false)),
		'processIdDown'              => new sfValidatorPass(array('required' => false)),
		'start'                      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'stop'                       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'status'                     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
		'room_has_energyaction_list' => new sfValidatorPropelChoice(array('model' => 'Room', 'required' => false)),
		));

		$this->widgetSchema->setNameFormat('energyaction_filters[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function addRoomHasEnergyactionListColumnCriteria(Criteria $criteria, $field, $values)
	{
		if (!is_array($values))
		{
			$values = array($values);
		}

		if (!count($values))
		{
			return;
		}

		$criteria->addJoin(RoomHasEnergyactionPeer::ENERGYACTION_ID, EnergyactionPeer::ID);

		$value = array_pop($values);
		$criterion = $criteria->getNewCriterion(RoomHasEnergyactionPeer::ROOM_ID, $value);

		foreach ($values as $value)
		{
			$criterion->addOr($criteria->getNewCriterion(RoomHasEnergyactionPeer::ROOM_ID, $value));
		}

		$criteria->add($criterion);
	}

	public function getModelName()
	{
		return 'Energyaction';
	}

	public function getFields()
	{
		return array(
		'id'                         => 'Number',
		'name'                       => 'Text',
		'delayUp'                    => 'Number',
		'delayDown'                  => 'Number',
		'identifier'                 => 'Text',
		'processIdUp'                => 'Text',
		'processIdDown'              => 'Text',
		'start'                      => 'Date',
		'stop'                       => 'Date',
		'status'                     => 'Boolean',
		'room_has_energyaction_list' => 'ManyKey',
		);
	}
}
