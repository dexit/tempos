<?php

/**
 * UserHasRole form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseUserHasRoleForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'User_id' => new sfWidgetFormInputHidden(),
      'Role_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'User_id' => new sfValidatorPropelChoice(array('model' => 'User', 'column' => 'id', 'required' => false)),
      'Role_id' => new sfValidatorPropelChoice(array('model' => 'Role', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user_has_role[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserHasRole';
  }


}
