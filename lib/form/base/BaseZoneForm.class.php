<?php

/**
 * Zone form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseZoneForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'name'               => new sfWidgetFormInput(),
      'parent_zone'        => new sfWidgetFormPropelChoice(array('model' => 'Zone', 'add_empty' => true)),
      'zone_has_room_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'Room')),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorPropelChoice(array('model' => 'Zone', 'column' => 'id', 'required' => false)),
      'name'               => new sfValidatorString(array('max_length' => 64)),
      'parent_zone'        => new sfValidatorPropelChoice(array('model' => 'Zone', 'column' => 'id', 'required' => false)),
      'zone_has_room_list' => new sfValidatorPropelChoiceMany(array('model' => 'Room', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('zone[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Zone';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['zone_has_room_list']))
    {
      $values = array();
      foreach ($this->object->getZoneHasRooms() as $obj)
      {
        $values[] = $obj->getRoomId();
      }

      $this->setDefault('zone_has_room_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveZoneHasRoomList($con);
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
    $c->add(ZoneHasRoomPeer::ZONE_ID, $this->object->getPrimaryKey());
    ZoneHasRoomPeer::doDelete($c, $con);

    $values = $this->getValue('zone_has_room_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new ZoneHasRoom();
        $obj->setZoneId($this->object->getPrimaryKey());
        $obj->setRoomId($value);
        $obj->save();
      }
    }
  }

}
