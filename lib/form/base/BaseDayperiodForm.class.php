<?php

/**
 * Dayperiod form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseDayperiodForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'start'       => new sfWidgetFormTime(),
      'stop'        => new sfWidgetFormTime(),
      'day_of_week' => new sfWidgetFormInput(),
      'Room_id'     => new sfWidgetFormPropelChoice(array('model' => 'Room', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'Dayperiod', 'column' => 'id', 'required' => false)),
      'start'       => new sfValidatorTime(),
      'stop'        => new sfValidatorTime(),
      'day_of_week' => new sfValidatorInteger(),
      'Room_id'     => new sfValidatorPropelChoice(array('model' => 'Room', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('dayperiod[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Dayperiod';
  }


}
