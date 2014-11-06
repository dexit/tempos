<?php

/**
 * RoomHasActivity form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseRoomHasActivityForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'Room_id'     => new sfWidgetFormInputHidden(),
      'Activity_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'Room_id'     => new sfValidatorPropelChoice(array('model' => 'Room', 'column' => 'id', 'required' => false)),
      'Activity_id' => new sfValidatorPropelChoice(array('model' => 'Activity', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('room_has_activity[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'RoomHasActivity';
  }


}
