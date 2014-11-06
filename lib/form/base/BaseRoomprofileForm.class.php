<?php

/**
 * Roomprofile form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseRoomprofileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'name'               => new sfWidgetFormInput(),
      'physical_access_id' => new sfWidgetFormInput(),
      'Room_id'            => new sfWidgetFormPropelChoice(array('model' => 'Room', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorPropelChoice(array('model' => 'Roomprofile', 'column' => 'id', 'required' => false)),
      'name'               => new sfValidatorString(array('max_length' => 256)),
      'physical_access_id' => new sfValidatorString(array('max_length' => 256)),
      'Room_id'            => new sfValidatorPropelChoice(array('model' => 'Room', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('roomprofile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Roomprofile';
  }


}
