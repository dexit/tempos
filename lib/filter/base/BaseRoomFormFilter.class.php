<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Room filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseRoomFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                       => new sfWidgetFormFilterInput(),
      'capacity'                   => new sfWidgetFormFilterInput(),
      'address'                    => new sfWidgetFormFilterInput(),
      'description'                => new sfWidgetFormFilterInput(),
      'is_active'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'room_has_featurevalue_list' => new sfWidgetFormPropelChoice(array('model' => 'Featurevalue', 'add_empty' => true)),
      'room_has_energyaction_list' => new sfWidgetFormPropelChoice(array('model' => 'Energyaction', 'add_empty' => true)),
      'room_has_activity_list'     => new sfWidgetFormPropelChoice(array('model' => 'Activity', 'add_empty' => true)),
      'zone_has_room_list'         => new sfWidgetFormPropelChoice(array('model' => 'Zone', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'                       => new sfValidatorPass(array('required' => false)),
      'capacity'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'address'                    => new sfValidatorPass(array('required' => false)),
      'description'                => new sfValidatorPass(array('required' => false)),
      'is_active'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'room_has_featurevalue_list' => new sfValidatorPropelChoice(array('model' => 'Featurevalue', 'required' => false)),
      'room_has_energyaction_list' => new sfValidatorPropelChoice(array('model' => 'Energyaction', 'required' => false)),
      'room_has_activity_list'     => new sfValidatorPropelChoice(array('model' => 'Activity', 'required' => false)),
      'zone_has_room_list'         => new sfValidatorPropelChoice(array('model' => 'Zone', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('room_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addRoomHasFeaturevalueListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(RoomHasFeaturevaluePeer::ROOM_ID, RoomPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(RoomHasFeaturevaluePeer::FEATUREVALUE_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(RoomHasFeaturevaluePeer::FEATUREVALUE_ID, $value));
    }

    $criteria->add($criterion);
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

    $criteria->addJoin(RoomHasEnergyactionPeer::ROOM_ID, RoomPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(RoomHasEnergyactionPeer::ENERGYACTION_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(RoomHasEnergyactionPeer::ENERGYACTION_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function addRoomHasActivityListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(RoomHasActivityPeer::ROOM_ID, RoomPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(RoomHasActivityPeer::ACTIVITY_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(RoomHasActivityPeer::ACTIVITY_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function addZoneHasRoomListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(ZoneHasRoomPeer::ROOM_ID, RoomPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(ZoneHasRoomPeer::ZONE_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(ZoneHasRoomPeer::ZONE_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Room';
  }

  public function getFields()
  {
    return array(
      'id'                         => 'Number',
      'name'                       => 'Text',
      'capacity'                   => 'Number',
      'address'                    => 'Text',
      'description'                => 'Text',
      'is_active'                  => 'Boolean',
      'room_has_featurevalue_list' => 'ManyKey',
      'room_has_energyaction_list' => 'ManyKey',
      'room_has_activity_list'     => 'ManyKey',
      'zone_has_room_list'         => 'ManyKey',
    );
  }
}
