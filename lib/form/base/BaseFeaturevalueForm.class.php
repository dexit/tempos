<?php

/**
 * Featurevalue form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseFeaturevalueForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                         => new sfWidgetFormInputHidden(),
      'value'                      => new sfWidgetFormInput(),
      'Feature_id'                 => new sfWidgetFormPropelChoice(array('model' => 'Feature', 'add_empty' => false)),
      'room_has_featurevalue_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'Room')),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorPropelChoice(array('model' => 'Featurevalue', 'column' => 'id', 'required' => false)),
      'value'                      => new sfValidatorString(array('max_length' => 128)),
      'Feature_id'                 => new sfValidatorPropelChoice(array('model' => 'Feature', 'column' => 'id')),
      'room_has_featurevalue_list' => new sfValidatorPropelChoiceMany(array('model' => 'Room', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('featurevalue[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Featurevalue';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['room_has_featurevalue_list']))
    {
      $values = array();
      foreach ($this->object->getRoomHasFeaturevalues() as $obj)
      {
        $values[] = $obj->getRoomId();
      }

      $this->setDefault('room_has_featurevalue_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveRoomHasFeaturevalueList($con);
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
    $c->add(RoomHasFeaturevaluePeer::FEATUREVALUE_ID, $this->object->getPrimaryKey());
    RoomHasFeaturevaluePeer::doDelete($c, $con);

    $values = $this->getValue('room_has_featurevalue_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new RoomHasFeaturevalue();
        $obj->setFeaturevalueId($this->object->getPrimaryKey());
        $obj->setRoomId($value);
        $obj->save();
      }
    }
  }

}
