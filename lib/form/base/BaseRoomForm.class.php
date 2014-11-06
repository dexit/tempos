<?php

/**
 * Room form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseRoomForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                         => new sfWidgetFormInputHidden(),
      'name'                       => new sfWidgetFormInput(),
      'capacity'                   => new sfWidgetFormInput(),
      'address'                    => new sfWidgetFormInput(),
      'description'                => new sfWidgetFormInput(),
      'is_active'                  => new sfWidgetFormInputCheckbox(),
      'room_has_featurevalue_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'Featurevalue')),
      'room_has_energyaction_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'Energyaction')),
      'room_has_activity_list'     => new sfWidgetFormPropelChoiceMany(array('model' => 'Activity')),
      'zone_has_room_list'         => new sfWidgetFormPropelChoiceMany(array('model' => 'Zone')),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorPropelChoice(array('model' => 'Room', 'column' => 'id', 'required' => false)),
      'name'                       => new sfValidatorString(array('max_length' => 64)),
      'capacity'                   => new sfValidatorInteger(array('required' => false)),
      'address'                    => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'description'                => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'is_active'                  => new sfValidatorBoolean(),
      'room_has_featurevalue_list' => new sfValidatorPropelChoiceMany(array('model' => 'Featurevalue', 'required' => false)),
      'room_has_energyaction_list' => new sfValidatorPropelChoiceMany(array('model' => 'Energyaction', 'required' => false)),
      'room_has_activity_list'     => new sfValidatorPropelChoiceMany(array('model' => 'Activity', 'required' => false)),
      'zone_has_room_list'         => new sfValidatorPropelChoiceMany(array('model' => 'Zone', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('room[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Room';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['room_has_featurevalue_list']))
    {
      $values = array();
      foreach ($this->object->getRoomHasFeaturevalues() as $obj)
      {
        $values[] = $obj->getFeaturevalueId();
      }

      $this->setDefault('room_has_featurevalue_list', $values);
    }

    if (isset($this->widgetSchema['room_has_energyaction_list']))
    {
      $values = array();
      foreach ($this->object->getRoomHasEnergyactions() as $obj)
      {
        $values[] = $obj->getEnergyactionId();
      }

      $this->setDefault('room_has_energyaction_list', $values);
    }

    if (isset($this->widgetSchema['room_has_activity_list']))
    {
      $values = array();
      foreach ($this->object->getRoomHasActivitys() as $obj)
      {
        $values[] = $obj->getActivityId();
      }

      $this->setDefault('room_has_activity_list', $values);
    }

    if (isset($this->widgetSchema['zone_has_room_list']))
    {
      $values = array();
      foreach ($this->object->getZoneHasRooms() as $obj)
      {
        $values[] = $obj->getZoneId();
      }

      $this->setDefault('zone_has_room_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveRoomHasFeaturevalueList($con);
    $this->saveRoomHasEnergyactionList($con);
    $this->saveRoomHasActivityList($con);
    $this->saveZoneHasRoomList($con);
  }

  public function saveRoomHasFeaturevalueList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['room_has_featurevalue_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(RoomHasFeaturevaluePeer::ROOM_ID, $this->object->getPrimaryKey());
    RoomHasFeaturevaluePeer::doDelete($c, $con);

    $values = $this->getValue('room_has_featurevalue_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new RoomHasFeaturevalue();
        $obj->setRoomId($this->object->getPrimaryKey());
        $obj->setFeaturevalueId($value);
        $obj->save();
      }
    }
  }

  public function saveRoomHasEnergyactionList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['room_has_energyaction_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(RoomHasEnergyactionPeer::ROOM_ID, $this->object->getPrimaryKey());
    RoomHasEnergyactionPeer::doDelete($c, $con);

    $values = $this->getValue('room_has_energyaction_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new RoomHasEnergyaction();
        $obj->setRoomId($this->object->getPrimaryKey());
        $obj->setEnergyactionId($value);
        $obj->save();
      }
    }
  }

  public function saveRoomHasActivityList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['room_has_activity_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(RoomHasActivityPeer::ROOM_ID, $this->object->getPrimaryKey());
    RoomHasActivityPeer::doDelete($c, $con);

    $values = $this->getValue('room_has_activity_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new RoomHasActivity();
        $obj->setRoomId($this->object->getPrimaryKey());
        $obj->setActivityId($value);
        $obj->save();
      }
    }
  }

  public function saveZoneHasRoomList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['zone_has_room_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(ZoneHasRoomPeer::ROOM_ID, $this->object->getPrimaryKey());
    ZoneHasRoomPeer::doDelete($c, $con);

    $values = $this->getValue('zone_has_room_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new ZoneHasRoom();
        $obj->setRoomId($this->object->getPrimaryKey());
        $obj->setZoneId($value);
        $obj->save();
      }
    }
  }

}
