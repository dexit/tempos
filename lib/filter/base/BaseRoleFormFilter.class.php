<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Role filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseRoleFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'               => new sfWidgetFormFilterInput(),
      'user_has_role_list' => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'               => new sfValidatorPass(array('required' => false)),
      'user_has_role_list' => new sfValidatorPropelChoice(array('model' => 'User', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('role_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addUserHasRoleListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(UserHasRolePeer::ROLE_ID, RolePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(UserHasRolePeer::USER_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(UserHasRolePeer::USER_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Role';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Text',
      'name'               => 'Text',
      'user_has_role_list' => 'ManyKey',
    );
  }
}
