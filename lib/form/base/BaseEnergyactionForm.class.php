<?php

/**
 * Energyaction form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseEnergyactionForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                         => new sfWidgetFormInputHidden(),
      'name'                       => new sfWidgetFormInput(),
      'delayUp'                    => new sfWidgetFormInput(),
      'delayDown'                  => new sfWidgetFormInput(),
      'identifier'                 => new sfWidgetFormInput(),
      'processIdUp'                => new sfWidgetFormInput(),
      'processIdDown'              => new sfWidgetFormInput(),
      'start'                      => new sfWidgetFormTime(),
      'stop'                       => new sfWidgetFormTime(),
      'status'                     => new sfWidgetFormInputCheckbox(),
      'room_has_energyaction_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'Room')),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorPropelChoice(array('model' => 'Energyaction', 'column' => 'id', 'required' => false)),
      'name'                       => new sfValidatorString(array('max_length' => 64)),
      'delayUp'                    => new sfValidatorInteger(),
      'delayDown'                  => new sfValidatorInteger(),
      'identifier'                 => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'processIdUp'                => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'processIdDown'              => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'start'                      => new sfValidatorTime(),
      'stop'                       => new sfValidatorTime(),
      'status'                     => new sfValidatorBoolean(),
      'room_has_energyaction_list' => new sfValidatorPropelChoiceMany(array('model' => 'Room', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('energyaction[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Energyaction';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['room_has_energyaction_list']))
    {
      $values = array();
      foreach ($this->object->getRoomHasEnergyactions() as $obj)
      {
        $values[] = $obj->getRoomId();
      }

      $this->setDefault('room_has_energyaction_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveRoomHasEnergyactionList($con);
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
    $c->add(RoomHasEnergyactionPeer::ENERGYACTION_ID, $this->object->getPrimaryKey());
    RoomHasEnergyactionPeer::doDelete($c, $con);

    $values = $this->getValue('room_has_energyaction_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new RoomHasEnergyaction();
        $obj->setEnergyactionId($this->object->getPrimaryKey());
        $obj->setRoomId($value);
        $obj->save();
      }
    }
  }

}
