<?php

/**
 * Role form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseRoleForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'name'               => new sfWidgetFormInput(),
      'user_has_role_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'User')),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorPropelChoice(array('model' => 'Role', 'column' => 'id', 'required' => false)),
      'name'               => new sfValidatorString(array('max_length' => 64)),
      'user_has_role_list' => new sfValidatorPropelChoiceMany(array('model' => 'User', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('role[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Role';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['user_has_role_list']))
    {
      $values = array();
      foreach ($this->object->getUserHasRoles() as $obj)
      {
        $values[] = $obj->getUserId();
      }

      $this->setDefault('user_has_role_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveUserHasRoleList($con);
  }

  public function saveUserHasRoleList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['user_has_role_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(UserHasRolePeer::ROLE_ID, $this->object->getPrimaryKey());
    UserHasRolePeer::doDelete($c, $con);

    $values = $this->getValue('user_has_role_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new UserHasRole();
        $obj->setRoleId($this->object->getPrimaryKey());
        $obj->setUserId($value);
        $obj->save();
      }
    }
  }

}
