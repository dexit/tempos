<?php

/**
 * Card form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseCardForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'card_number' => new sfWidgetFormInput(),
      'pin_code'    => new sfWidgetFormInput(),
      'is_active'   => new sfWidgetFormInputCheckbox(),
      'owner'       => new sfWidgetFormPropelChoice(array('model' => 'Carduser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'Card', 'column' => 'id', 'required' => false)),
      'card_number' => new sfValidatorString(array('max_length' => 32)),
      'pin_code'    => new sfValidatorString(array('max_length' => 16)),
      'is_active'   => new sfValidatorBoolean(),
      'owner'       => new sfValidatorPropelChoice(array('model' => 'Carduser', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('card[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Card';
  }


}
