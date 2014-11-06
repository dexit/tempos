<?php

/**
 * UsergroupHasUser form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseUsergroupHasUserForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'UserGroup_id' => new sfWidgetFormInputHidden(),
      'User_id'      => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'UserGroup_id' => new sfValidatorPropelChoice(array('model' => 'Usergroup', 'column' => 'id', 'required' => false)),
      'User_id'      => new sfValidatorPropelChoice(array('model' => 'User', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('usergroup_has_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsergroupHasUser';
  }


}
