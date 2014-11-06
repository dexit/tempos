<?php

/**
* Usergroup form.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class UsergroupForm extends BaseUsergroupForm
{
	public function configure()
	{
		// Sort
		$activitySortCriteria = new Criteria();
		$activitySortCriteria->addAscendingOrderByColumn(ActivityPeer::NAME);

		$userSortCriteria = new Criteria();
		$userSortCriteria->addAscendingOrderByColumn(UserPeer::FAMILY_NAME);

		// Remove some fields
		//unset($this['usergroup_has_chief_list']);

		// Labels
		$activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');
		if (is_null($activityItem) || empty($activityItem))
		{
			$activityItem = 'Activities';
		}
		
		$this->widgetSchema->setLabel('usergroup_has_chief_list', 'Leader(s)');
		$this->widgetSchema->setLabel('usergroup_has_user_list', 'Member(s)');
		$this->widgetSchema->setLabel('usergroup_has_activity_list', $activityItem);

		// Validators
		$this->validatorSchema['name'] = new sfXSSValidatorString(array('max_length' => 64));

		// Options
		$this->widgetSchema['usergroup_has_chief_list']->setOption('expanded', true);
		$this->widgetSchema['usergroup_has_chief_list']->setOption('criteria', $userSortCriteria);
		$this->validatorSchema['usergroup_has_chief_list']->setOption('required', true);
		$this->widgetSchema['usergroup_has_user_list']->setOption('expanded', true);
		$this->widgetSchema['usergroup_has_user_list']->setOption('criteria', $userSortCriteria);
		$this->widgetSchema['usergroup_has_activity_list']->setOption('expanded', true);
		$this->widgetSchema['usergroup_has_activity_list']->setOption('criteria', $activitySortCriteria);
		$this->validatorSchema['usergroup_has_activity_list']->setOption('required', true);
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
