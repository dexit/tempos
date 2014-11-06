<?php

/**
* Usergroup form base class.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseUsergroupForm extends BaseFormPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'id'                          => new sfWidgetFormInputHidden(),
		'name'                        => new sfWidgetFormInput(),
		'usergroup_has_chief_list'    => new sfWidgetFormPropelChoiceMany(array('model' => 'User')),
		'usergroup_has_user_list'     => new sfWidgetFormPropelChoiceMany(array('model' => 'User')),
		'usergroup_has_activity_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'Activity')),
		));

		$this->setValidators(array(
		'id'                          => new sfValidatorPropelChoice(array('model' => 'Usergroup', 'column' => 'id', 'required' => false)),
		'name'                        => new sfValidatorString(array('max_length' => 64)),
		'usergroup_has_chief_list'    => new sfValidatorPropelChoiceMany(array('model' => 'User', 'required' => false)),
		'usergroup_has_user_list'     => new sfValidatorPropelChoiceMany(array('model' => 'User', 'required' => false)),
		'usergroup_has_activity_list' => new sfValidatorPropelChoiceMany(array('model' => 'Activity', 'required' => false)),
		));

		$this->widgetSchema->setNameFormat('usergroup[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'Usergroup';
	}

	public function updateDefaultsFromObject()
	{
		parent::updateDefaultsFromObject();

		if (isset($this->widgetSchema['usergroup_has_chief_list']))
		{
			$values = array();
			foreach ($this->object->getUsergroupHasChiefs() as $obj)
			{
				$values[] = $obj->getUserId();
			}

			$this->setDefault('usergroup_has_chief_list', $values);
		}

		if (isset($this->widgetSchema['usergroup_has_user_list']))
		{
			$values = array();
			foreach ($this->object->getUsergroupHasUsers() as $obj)
			{
				$values[] = $obj->getUserId();
			}

			$this->setDefault('usergroup_has_user_list', $values);
		}

		if (isset($this->widgetSchema['usergroup_has_activity_list']))
		{
			$values = array();
			foreach ($this->object->getUsergroupHasActivitys() as $obj)
			{
				$values[] = $obj->getActivityId();
			}

			$this->setDefault('usergroup_has_activity_list', $values);
		}
	}

	protected function doSave($con = null)
	{
		parent::doSave($con);

		$this->saveUsergroupHasChiefList($con);
		$this->saveUsergroupHasUserList($con);
		$this->saveUsergroupHasActivityList($con);
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
		$c->add(UsergroupHasChiefPeer::USERGROUP_ID, $this->object->getPrimaryKey());
		UsergroupHasChiefPeer::doDelete($c, $con);

		$values = $this->getValue('usergroup_has_chief_list');
		if (is_array($values))
		{
			foreach ($values as $value)
			{
				$obj = new UsergroupHasChief();
				$obj->setUsergroupId($this->object->getPrimaryKey());
				$obj->setUserId($value);
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
		$c->add(UsergroupHasUserPeer::USERGROUP_ID, $this->object->getPrimaryKey());
		UsergroupHasUserPeer::doDelete($c, $con);

		$values = $this->getValue('usergroup_has_user_list');
		if (is_array($values))
		{
			foreach ($values as $value)
			{
				$obj = new UsergroupHasUser();
				$obj->setUsergroupId($this->object->getPrimaryKey());
				$obj->setUserId($value);
				$obj->save();
			}
		}
	}

	public function saveUsergroupHasActivityList($con = null)
	{
		if (!$this->isValid())
		{
			throw $this->getErrorSchema();
		}

		if (!isset($this->widgetSchema['usergroup_has_activity_list']))
		{
			// somebody has unset this widget
			return;
		}

		if (is_null($con))
		{
			$con = $this->getConnection();
		}

		$c = new Criteria();
		$c->add(UsergroupHasActivityPeer::USERGROUP_ID, $this->object->getPrimaryKey());
		UsergroupHasActivityPeer::doDelete($c, $con);

		$values = $this->getValue('usergroup_has_activity_list');
		if (is_array($values))
		{
			foreach ($values as $value)
			{
				$obj = new UsergroupHasActivity();
				$obj->setUsergroupId($this->object->getPrimaryKey());
				$obj->setActivityId($value);
				$obj->save();
			}
		}
	}
}
