<?php

/**
 * RoomHasEnergyaction form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseRoomHasEnergyactionForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'Room_id'         => new sfWidgetFormInputHidden(),
      'EnergyAction_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'Room_id'         => new sfValidatorPropelChoice(array('model' => 'Room', 'column' => 'id', 'required' => false)),
      'EnergyAction_id' => new sfValidatorPropelChoice(array('model' => 'Energyaction', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('room_has_energyaction[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'RoomHasEnergyaction';
  }


}
