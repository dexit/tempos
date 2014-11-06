<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
* User filter form base class.
*
* @package    tempos
* @subpackage filter
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseUserFormFilter extends BaseFormFilterPropel
{
	public function setup()
	{
		$c = new Criteria();
		$c->add(UsergroupPeer::IS_TEMPORAY, false);
		
		$this->setWidgets(array(
		'login'                    => new sfWidgetFormFilterInput(),
		'password_hash'            => new sfWidgetFormFilterInput(),
		'family_name'              => new sfWidgetFormFilterInput(),
		'surname'                  => new sfWidgetFormFilterInput(),
		'is_active'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
		'card_number'              => new sfWidgetFormFilterInput(),
		'birthdate'                => new sfWidgetFormFilterDate(),
		'is_member'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
		'email_address'            => new sfWidgetFormFilterInput(),
		'address'                  => new sfWidgetFormFilterInput(),
		'phone_number'             => new sfWidgetFormFilterInput(),
		'created_at'               => new sfWidgetFormFilterDate(array(
			'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
			'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => true)),
		'photograph'               => new sfWidgetFormFilterInput(),
		'user_has_role_list'       => new sfWidgetFormPropelChoice(array('model' => 'Role', 'add_empty' => true)),
		'usergroup_has_chief_list' => new sfWidgetFormPropelChoice(array('model' => 'Usergroup', 'add_empty' => true, 'criteria' => $c)),
		'usergroup_has_user_list'  => new sfWidgetFormPropelChoice(array('model' => 'Usergroup', 'add_empty' => true, 'criteria' => $c)),
		));

		$this->setValidators(array(
		'login'                    => new sfValidatorPass(array('required' => false)),
		'password_hash'            => new sfValidatorPass(array('required' => false)),
		'family_name'              => new sfValidatorPass(array('required' => false)),
		'surname'                  => new sfValidatorPass(array('required' => false)),
		'is_active'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
		'card_number'              => new sfValidatorPass(array('required' => false)),
		'is_member'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
		'email_address'            => new sfValidatorPass(array('required' => false)),
		'address'                  => new sfValidatorPass(array('required' => false)),
		'phone_number'             => new sfValidatorPass(array('required' => false)),
		'created_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'photograph'               => new sfValidatorPass(array('required' => false)),
		'user_has_role_list'       => new sfValidatorPropelChoice(array('model' => 'Role', 'required' => false)),
		'usergroup_has_chief_list' => new sfValidatorPropelChoice(array('model' => 'Usergroup', 'required' => false)),
		'usergroup_has_user_list'  => new sfValidatorPropelChoice(array('model' => 'Usergroup', 'required' => false)),
		));

		$this->widgetSchema->setNameFormat('user_filters[%s]');

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

		$criteria->addJoin(UserHasRolePeer::USER_ID, UserPeer::ID);

		$value = array_pop($values);
		$criterion = $criteria->getNewCriterion(UserHasRolePeer::ROLE_ID, $value);

		foreach ($values as $value)
		{
			$criterion->addOr($criteria->getNewCriterion(UserHasRolePeer::ROLE_ID, $value));
		}

		$criteria->add($criterion);
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

		$criteria->addJoin(UsergroupHasChiefPeer::USER_ID, UserPeer::ID);

		$value = array_pop($values);
		$criterion = $criteria->getNewCriterion(UsergroupHasChiefPeer::USERGROUP_ID, $value);

		foreach ($values as $value)
		{
			$criterion->addOr($criteria->getNewCriterion(UsergroupHasChiefPeer::USERGROUP_ID, $value));
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

		$criteria->addJoin(UsergroupHasUserPeer::USER_ID, UserPeer::ID);

		$value = array_pop($values);
		$criterion = $criteria->getNewCriterion(UsergroupHasUserPeer::USERGROUP_ID, $value);

		foreach ($values as $value)
		{
			$criterion->addOr($criteria->getNewCriterion(UsergroupHasUserPeer::USERGROUP_ID, $value));
		}

		$criteria->add($criterion);
	}

	public function getModelName()
	{
		return 'User';
	}

	public function getFields()
	{
		return array(
		'id'                       => 'Number',
		'login'                    => 'Text',
		'password_hash'            => 'Text',
		'family_name'              => 'Text',
		'surname'                  => 'Text',
		'is_active'                => 'Boolean',
		'card_number'              => 'Text',
		'birthdate'                => 'Date',
		'is_member'                => 'Boolean',
		'email_address'            => 'Text',
		'address'                  => 'Text',
		'phone_number'             => 'Text',
		'created_at'               => 'Date',
		'photograph'               => 'Text',
		'user_has_role_list'       => 'ManyKey',
		'usergroup_has_chief_list' => 'ManyKey',
		'usergroup_has_user_list'  => 'ManyKey',
		);
	}
}
