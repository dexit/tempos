<?php

/**
* Room form.
*
* @package    tempos
* @subpackage form
* @author     Your name here
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class RoomForm extends BaseRoomForm
{
	public function configure()
	{
		unset($this['room_has_dayperiod_list']);
		unset($this['room_has_featurevalue_list']);

		// Sort
		$activitySortCriteria = new Criteria();
		$activitySortCriteria->addAscendingOrderByColumn(ActivityPeer::NAME);
		$energyactionSortCriteria = new Criteria();
		$energyactionSortCriteria->addAscendingOrderByColumn(EnergyactionPeer::NAME);

		// Layout
		$this->widgetSchema['address'] = new sfWidgetFormInputGeoComplete();
		$this->widgetSchema['description'] = new sfWidgetFormTextarea();
		$this->widgetSchema['room_has_activity_list']->setOption('expanded', true);
		$this->widgetSchema['room_has_activity_list']->setOption('criteria', $activitySortCriteria);
		$this->widgetSchema['room_has_energyaction_list']->setOption('criteria', $energyactionSortCriteria);
		$this->widgetSchema['zone_has_room_list'] = new sfWidgetFormZoneChoice(array('multiple' => true, 'add_empty' => false));
		$this->widgetSchema['room_has_energyaction_list']->setOption('expanded', true);

		// Validators
		$this->validatorSchema['capacity']->setOption('min', 1);
		$this->validatorSchema['zone_has_room_list']->setOption('required', true);
		$this->validatorSchema['name'] =  new sfXSSValidatorString(array('max_length' => 64));

		// Labels
		$activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');
		if (is_null($activityItem) || empty($activityItem))
		{
			$activityItem = 'Activities';
		}
		
		$this->widgetSchema->setLabel('room_has_activity_list', $activityItem);
		$this->widgetSchema->setLabel('zone_has_room_list', 'Zones');
		$this->widgetSchema->setLabel('room_has_energyaction_list', 'Energy actions');

		// Post-validators
		$this->validatorSchema->setPostValidator(
		new sfValidatorAnd(array(
		new sfValidatorPropelUnique(array('model' => 'Room', 'column' => array('name'))),
		//new sfValidatorPropelUnique(array('model' => 'Room', 'column' => array('physical_access_id'))),
		))
		);
	}

	public function addParentZone($parentZone)
	{
		$this->object->addParentZone($parentZone->getId());
		$this->setDefault('zone_has_room_list', $parentZone->getId());
	}
}
