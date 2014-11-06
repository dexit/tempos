<?php

/**
* Usergroup form.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class UsergroupUsersForm extends sfForm
{
	public function configure()
	{
		// Sort
		$groupSortCriteria = new Criteria();
		$groupSortCriteria->addAscendingOrderByColumn(UsergroupPeer::NAME);

		$userSortCriteria = new Criteria();
		$userSortCriteria->addAscendingOrderByColumn(UserPeer::FAMILY_NAME);

		$this->setWidgets(array(
		'Usergroup_id'				=> new sfWidgetFormPropelChoice(array('model' => 'Usergroup', 'criteria' => $groupSortCriteria)),
		'usergroup_has_user_list'	=> new sfWidgetFormPropelChoiceMany(array('model' => 'User', 'expanded' => true, 'criteria' => $userSortCriteria)),
		));

		$this->setValidators(array(
		'Usergroup_id'				=> new sfValidatorPropelChoice(array('model' => 'Usergroup', 'column' => 'id', 'required' => true)),
		'usergroup_has_user_list'	=> new sfValidatorPropelChoiceMany(array('model' => 'User', 'required' => true)),
		));

		$this->widgetSchema->setLabels(array(
		'Usergroup_id'				=> 'Group',
		'usergroup_has_user_list'	=> 'Users',
		));

		$this->widgetSchema->setNameFormat('usergroupUsers[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
	}

	public function addUsers($users_id)
	{
		$users = $this->getDefault('usergroup_has_user_list');

		if (is_array($users))
		{
			$users_id = array_merge($users_id, $users);
		}

		$this->setDefault('usergroup_has_user_list', $users_id);
	}
}
