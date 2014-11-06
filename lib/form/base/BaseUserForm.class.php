<?php

/**
* User form base class.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseUserForm extends BaseFormPropel
{
	public function setup()
	{
		$years = range(date('Y') - 100, date('Y'));
		
		$c = new Criteria();

		$this->setWidgets(array(
		'id'                       => new sfWidgetFormInputHidden(),
		'login'                    => new sfWidgetFormInput(),
		'password_hash'            => new sfWidgetFormInput(),
		'family_name'              => new sfWidgetFormInput(),
		'surname'                  => new sfWidgetFormInput(),
		'is_active'                => new sfWidgetFormInputCheckbox(),
		'card_number'              => new sfWidgetFormInput(),
		'birthdate'                => new sfWidgetFormJQueryDate(array(
			'image'  => '/images/calendar.gif',
			'culture'=> 'fr',
			'config' => '{firstDay: 1, changeMonth: true, changeYear: true, yearRange: \'-100:+0\'}',
        	'date_widget' => new sfWidgetFormI18nDate(array(
					'format' => '%day%/%month%/%year%',
					'culture' => 'fr',
					'month_format' => 'number',
					'years' => array_combine($years, $years))))),
		'is_member'                => new sfWidgetFormInputCheckbox(),
		'email_address'            => new sfWidgetFormInput(),
		'address'                  => new sfWidgetFormInput(),
		'phone_number'             => new sfWidgetFormInput(),
		'created_at'               => new sfWidgetFormI18nDateTime(array('culture' => 'fr')),
		'photograph'               => new sfWidgetFormInput(),
		'user_has_role_list'       => new sfWidgetFormPropelChoiceMany(array('model' => 'Role')),
		'usergroup_has_chief_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'Usergroup', 'criteria' => $c)),
		'usergroup_has_user_list'  => new sfWidgetFormPropelChoiceMany(array('model' => 'Usergroup', 'criteria' => $c)),
		));

		$this->setValidators(array(
		'id'                       => new sfValidatorPropelChoice(array('model' => 'User', 'column' => 'id', 'required' => false)),
		'login'                    => new sfValidatorString(array('max_length' => 64)),
		'password_hash'            => new sfValidatorString(array('max_length' => 64)),
		'family_name'              => new sfValidatorString(array('max_length' => 64)),
		'surname'                  => new sfValidatorString(array('max_length' => 64)),
		'is_active'                => new sfValidatorBoolean(),
		'card_number'              => new sfValidatorString(array('max_length' => 32)),
		'birthdate'                => new sfValidatorDate(array('required' => false)),
		'is_member'                => new sfValidatorBoolean(),
		'email_address'            => new sfValidatorString(array('max_length' => 128, 'required' => false)),
		'address'                  => new sfValidatorString(array('max_length' => 256, 'required' => false)),
		'phone_number'             => new sfValidatorString(array('max_length' => 64, 'required' => false)),
		'created_at'               => new sfValidatorDateTime(array('required' => false)),
		'photograph'               => new sfValidatorPass(array('required' => false)),
		'user_has_role_list'       => new sfValidatorPropelChoiceMany(array('model' => 'Role', 'required' => false)),
		'usergroup_has_chief_list' => new sfValidatorPropelChoiceMany(array('model' => 'Usergroup', 'required' => false)),
		'usergroup_has_user_list'  => new sfValidatorPropelChoiceMany(array('model' => 'Usergroup', 'required' => false)),
		));

		$this->widgetSchema->setNameFormat('user[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'User';
	}

	public function updateDefaultsFromObject()
	{
		$c = new Criteria();
		parent::updateDefaultsFromObject();

		if (isset($this->widgetSchema['user_has_role_list']))
		{
			$values = array();
			foreach ($this->object->getUserHasRoles() as $obj)
			{
				$values[] = $obj->getRoleId();
			}

			$this->setDefault('user_has_role_list', $values);
		}

		if (isset($this->widgetSchema['usergroup_has_chief_list']))
		{
			$values = array();
			foreach ($this->object->getUsergroupHasChiefs($c) as $obj)
			{
				$values[] = $obj->getUsergroupId();
			}

			$this->setDefault('usergroup_has_chief_list', $values);
		}

		if (isset($this->widgetSchema['usergroup_has_user_list']))
		{
			$values = array();
			foreach ($this->object->getUsergroupHasUsers($c) as $obj)
			{
				$values[] = $obj->getUsergroupId();
			}

			$this->setDefault('usergroup_has_user_list', $values);
		}
	}

	protected function doSave($con = null)
	{
		parent::doSave($con);

		$this->saveUserHasRoleList($con);
		$this->saveUsergroupHasChiefList($con);
		$this->saveUsergroupHasUserList($con);
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
		$c->add(UserHasRolePeer::USER_ID, $this->object->getPrimaryKey());
		UserHasRolePeer::doDelete($c, $con);

		$values = $this->getValue('user_has_role_list');
		if (is_array($values))
		{
			foreach ($values as $value)
			{
				$obj = new UserHasRole();
				$obj->setUserId($this->object->getPrimaryKey());
				$obj->setRoleId($value);
				$obj->save();
			}
		}
	}

	public function saveUsergroupHasChiefList($con = null)
	{
		if (!$this->isValid())
		{
			throw $this->getErrorSchema();
		}

		if (!isset($this->widgetSchema['usergroup_has_chief_list']))
		{
			// somebody has unset this widget
			return;
		}

		if (is_null($con))
		{
			$con = $this->getConnection();
		}

		$c = new Criteria();
		$c->add(UsergroupHasChiefPeer::USER_ID, $this->object->getPrimaryKey());
		UsergroupHasChiefPeer::doDelete($c, $con);

		$values = $this->getValue('usergroup_has_chief_list');
		if (is_array($values))
		{
			foreach ($values as $value)
			{
				$obj = new UsergroupHasChief();
				$obj->setUserId($this->object->getPrimaryKey());
				$obj->setUsergroupId($value);
				$obj->save();
			}
		}
	}

	public function saveUsergroupHasUserList($con = null)
	{
		if (!$this->isValid())
		{
			throw $this->getErrorSchema();
		}

		if (!isset($this->widgetSchema['usergroup_has_user_list']))
		{
			// somebody has unset this widget
			return;
		}

		if (is_null($con))
		{
			$con = $this->getConnection();
		}

		$c = new Criteria();
		$c->add(UsergroupHasUserPeer::USER_ID, $this->object->getPrimaryKey());
		UsergroupHasUserPeer::doDelete($c, $con);

		$values = $this->getValue('usergroup_has_user_list');
		if (is_array($values))
		{
			foreach ($values as $value)
			{
				$obj = new UsergroupHasUser();
				$obj->setUserId($this->object->getPrimaryKey());
				$obj->setUsergroupId($value);
				$obj->save();
			}
		}
	}
}
