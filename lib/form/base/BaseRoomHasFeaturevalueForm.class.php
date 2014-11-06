<?php

/**
 * RoomHasFeaturevalue form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseRoomHasFeaturevalueForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'Room_id'         => new sfWidgetFormInputHidden(),
      'FeatureValue_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'Room_id'         => new sfValidatorPropelChoice(array('model' => 'Room', 'column' => 'id', 'required' => false)),
      'FeatureValue_id' => new sfValidatorPropelChoice(array('model' => 'Featurevalue', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('room_has_featurevalue[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'RoomHasFeaturevalue';
  }


}
