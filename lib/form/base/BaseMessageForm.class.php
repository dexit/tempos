<?php

/**
 * Message form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseMessageForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'subject'      => new sfWidgetFormInput(),
      'text'         => new sfWidgetFormTextarea(),
      'created_at'   => new sfWidgetFormI18nDateTime(array('culture' => 'fr')),
      'recipient_id' => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => false)),
      'sender'       => new sfWidgetFormInput(),
      'sender_id'    => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
      'was_read'     => new sfWidgetFormInputCheckbox(),
      'owner_id'     => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorPropelChoice(array('model' => 'Message', 'column' => 'id', 'required' => false)),
      'subject'      => new sfValidatorString(array('max_length' => 256)),
      'text'         => new sfValidatorString(),
      'created_at'   => new sfValidatorDateTime(array('required' => false)),
      'recipient_id' => new sfValidatorPropelChoice(array('model' => 'User', 'column' => 'id')),
      'sender'       => new sfValidatorString(array('max_length' => 256)),
      'sender_id'    => new sfValidatorPropelChoice(array('model' => 'User', 'column' => 'id', 'required' => false)),
      'was_read'     => new sfValidatorBoolean(),
      'owner_id'     => new sfValidatorPropelChoice(array('model' => 'User', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('message[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Message';
  }
}
