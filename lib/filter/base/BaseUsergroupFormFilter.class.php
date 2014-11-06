<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
* Usergroup filter form base class.
*
* @package    tempos
* @subpackage filter
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseUsergroupFormFilter extends BaseFormFilterPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'name'                        => new sfWidgetFormFilterInput(),
		'usergroup_has_chief_list'    => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
		'usergroup_has_user_list'     => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
		'usergroup_has_activity_list' => new sfWidgetFormPropelChoice(array('model' => 'Activity', 'add_empty' => true)),
		));

		$this->setValidators(array(
		'name'                        => new sfValidatorPass(array('required' => false)),
		'usergroup_has_chief_list'    => new sfValidatorPropelChoice(array('model' => 'User', 'required' => false)),
		'usergroup_has_user_list'     => new sfValidatorPropelChoice(array('model' => 'User', 'required' => false)),
		'usergroup_has_activity_list' => new sfValidatorPropelChoice(array('model' => 'Activity', 'required' => false)),
		));

		$this->widgetSchema->setNameFormat('usergroup_filters[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function addUsergroupHasChiefListColumnCriteria(Criteria $criteria, $field, $values)
	{
		if (!is_array($values))
		{
			$values = array($values);
		}

		if (!count($values))
		{
			return;
		}

		$criteria->addJoin(UsergroupHasChiefPeer::USERGROUP_ID, UsergroupPeer::ID);

		$value = array_pop($values);
		$criterion = $criteria->getNewCriterion(UsergroupHasChiefPeer::USER_ID, $value);

		foreach ($values as $value)
		{
			$criterion->addOr($criteria->getNewCriterion(UsergroupHasChiefPeer::USER_ID, $value));
		}

		$criteria->add($criterion);
	}

	public function addUsergroupHasUserListColumnCriteria(Criteria $criteria, $field, $values)
	{
		if (!is_array($values))
		{
			$values = array($values);
		}

		if (!count($values))
		{
			return;
		}

		$criteria->addJoin(UsergroupHasUserPeer::USERGROUP_ID, UsergroupPeer::ID);

		$value = array_pop($values);
		$criterion = $criteria->getNewCriterion(UsergroupHasUserPeer::USER_ID, $value);

		foreach ($values as $value)
		{
			$criterion->addOr($criteria->getNewCriterion(UsergroupHasUserPeer::USER_ID, $value));
		}

		$criteria->add($criterion);
	}

	public function addUsergroupHasActivityListColumnCriteria(Criteria $criteria, $field, $values)
	{
		if (!is_array($values))
		{
			$values = array($values);
		}

		if (!count($values))
		{
			return;
		}

		$criteria->addJoin(UsergroupHasActivityPeer::USERGROUP_ID, UsergroupPeer::ID);

		$value = array_pop($values);
		$criterion = $criteria->getNewCriterion(UsergroupHasActivityPeer::ACTIVITY_ID, $value);

		foreach ($values as $value)
		{
			$criterion->addOr($criteria->getNewCriterion(UsergroupHasActivityPeer::ACTIVITY_ID, $value));
		}

		$criteria->add($criterion);
	}

	public function getModelName()
	{
		return 'Usergroup';
	}

	public function getFields()
	{
		return array(
		'id'                          => 'Number',
		'name'                        => 'Text',
		'usergroup_has_chief_list'    => 'ManyKey',
		'usergroup_has_user_list'     => 'ManyKey',
		'usergroup_has_activity_list' => 'ManyKey',
		);
	}
}
