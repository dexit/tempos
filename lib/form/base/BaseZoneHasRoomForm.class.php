<?php

/**
 * ZoneHasRoom form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseZoneHasRoomForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'Zone_id' => new sfWidgetFormInputHidden(),
      'Room_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'Zone_id' => new sfValidatorPropelChoice(array('model' => 'Zone', 'column' => 'id', 'required' => false)),
      'Room_id' => new sfValidatorPropelChoice(array('model' => 'Room', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('zone_has_room[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZoneHasRoom';
  }


}
