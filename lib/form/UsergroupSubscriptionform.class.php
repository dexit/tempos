<?php

/**
* Subscription form.
*
* @package    tempos
* @subpackage form
* @author     Your name here
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class UsergroupSubscriptionForm extends BaseSubscriptionForm
{
	const EVERYBODY = 0;
	const LEADERS = 1;
	const MEMBERS = 2;
	const NONMEMBERS = 3;

	const ADD = 0;
	const REPLACE = 1;
	const FILL = 2;
	const DELETE = 3;

	public function __construct($subscription = null)
	{
		parent::__construct($subscription);

		if (!is_null($subscription))
		{
			if (!is_null($subscription->getUser()) || !is_null($subscription->getCard()))
			{
				$this->hideOwnerFields();
			}

			if ($subscription->getCredit() != null)
			{
				$this->setDefault('unlimitedCredit', false);
			}
		}
	}

	public function configure()
	{
		// Sort
		$activitySortCriteria = new Criteria();
		$activitySortCriteria->addAscendingOrderByColumn(ActivityPeer::NAME);

		// Choices
		$userTypeChoices = array(
		self::EVERYBODY => sfContext::getInstance()->getI18N()->__('Everybody'),
		self::LEADERS => sfContext::getInstance()->getI18N()->__('Leaders'),
		self::MEMBERS => sfContext::getInstance()->getI18N()->__('Members'),
		self::NONMEMBERS => sfContext::getInstance()->getI18N()->__('Non-members'),
		);

		$createRuleChoices = array(
		self::ADD => sfContext::getInstance()->getI18N()->__('Add'),
		self::REPLACE => sfContext::getInstance()->getI18N()->__('Replace'),
		self::FILL=> sfContext::getInstance()->getI18N()->__('Fill'),
		self::DELETE=> sfContext::getInstance()->getI18N()->__('Delete'),
		);

		// Layout
		$this->widgetSchema['User_type'] = new sfWidgetFormChoice(array('choices' => $userTypeChoices));
		$this->widgetSchema['Create_rule'] = new sfWidgetFormChoice(array('choices' => $createRuleChoices));
		$this->widgetSchema['Activity_id']->setOption('expanded', true);
		$this->widgetSchema['Activity_id']->setOption('multiple', true);
		$this->widgetSchema['Activity_id']->setOption('criteria', $activitySortCriteria);
		$this->widgetSchema['Zone_id'] = new sfWidgetFormZoneChoice(array('multiple' => true, 'add_empty' => false));
		$this->widgetSchema['unlimitedCredit'] = new sfWidgetFormInputCheckbox();
		$this->widgetSchema['UserGroup_id'] = new sfWidgetFormInputHidden();
		$this->widgetSchema['User_id'] = new sfWidgetFormInputHidden();
		$this->widgetSchema['Card_id'] = new sfWidgetFormInputHidden();

		$years = range(date('Y') - 128, date('Y') + 128);
		$this->widgetSchema['start']->setOption('years', array_combine($years, $years));
		$this->widgetSchema['stop']->setOption('years', array_combine($years, $years));

		// Labels
		$activityLabel = ConfigurationHelper::getParameter('Rename', 'activity_label');
		if (is_null($activityLabel) || empty($activityLabel))
		{
			$activityLabel = 'Activity';
		}
		
		$this->widgetSchema->setLabel('User_type', 'Edit for');
		$this->widgetSchema->setLabel('Create_rule', 'Edition rule');
		$this->widgetSchema->setLabel('Activity_id', $activityLabel);
		$this->widgetSchema->setLabel('Zone_id', 'Zone');
		$this->widgetSchema->setLabel('UserGroup_id', 'Group');
		$this->widgetSchema->setLabel('unlimitedCredit', 'Unlimited access');
		$this->widgetSchema->setLabel('minimum_delay', 'Minimum number of hours in advance');
		$this->widgetSchema->setLabel('maximum_delay', 'Maximum number of days in advance');
		$this->widgetSchema->setLabel('minimum_duration', 'Minimum duration of reservations (minutes)');
		$this->widgetSchema->setLabel('maximum_duration', 'Maximum duration of reservations (minutes)');
		$this->widgetSchema->setLabel('hours_per_week', 'How many hours allowed per week (hours)');

		// Validators
		$step = sfConfig::get('app_booking_step');
		$this->validatorSchema['User_type'] = new sfValidatorChoice(array('choices' => array_keys($userTypeChoices), 'required' => true));
		$this->validatorSchema['Create_rule'] = new sfValidatorChoice(array('choices' => array_keys($createRuleChoices), 'required' => true));
		$this->validatorSchema['credit']->setOption('min', 1);
		$this->validatorSchema['Activity_id']->setOption('multiple', true);
		$this->validatorSchema['Zone_id']->setOption('multiple', true);
		$this->validatorSchema['unlimitedCredit'] = new sfValidatorBoolean(array('required' => false));
		$this->validatorSchema['minimum_delay']->setOption('min', 0);
		$this->validatorSchema['maximum_delay']->setOption('min', 0);
		$this->validatorSchema['minimum_duration']->setOption('min', $step);
		$this->validatorSchema['maximum_duration']->setOption('min', $step);
		$this->validatorSchema['hours_per_week']->setOption('min', 1);
		//$this->validatorSchema['hours_per_week']->setOption('max', 170);

		// Defaults
		$this->setDefault('Create_rule', self::REPLACE);
		$this->setDefault('start', strftime("%Y-%m-%d", time()));
		$this->setDefault('stop', strftime("%Y-%m-%d", strtotime('+1 year')));
		$this->setDefault('unlimitedCredit', true);

		// Post validators
		$this->validatorSchema->setPostValidator(
		new sfValidatorAnd(array(
		new sfValidatorSchemaCompare('stop', sfValidatorSchemaCompare::GREATER_THAN, 'start', array(),
		array(
		'invalid' => 'The stop date must be after the start date.',
		)
		),
		new sfValidatorSchemaCompare('maximum_duration', sfValidatorSchemaCompare::GREATER_THAN, 'minimum_duration', array(),
		array(
		'invalid' => 'The maximum duration must be greater than the minimum duration.',
		)
		),
		))
		);
	}

	public function setDefaultUsergroup(Usergroup $usergroup)
	{
		$this->getObject()->setUsergroup($usergroup);
		$this->setDefault('UserGroup_id', $usergroup->getId());

		$activities = $usergroup->getActivities();

		if (count($activities) == 1)
		{
			$this->setDefault('Activity_id', $activities[0]->getId());
		}
	}

	protected function doSave($con = null)
	{
		parent::doSave($con);

		if ($this->getValue('unlimitedCredit') == true)
		{
			$this->getObject()->setCredit(null);
			$this->getObject()->save();
		}

		$usergroup = $this->getObject()->getUsergroup();

		$zonesId = $this->getValue('Zone_id');
		$activitiesId = $this->getValue('Activity_id');

		foreach ($zonesId as $zoneId)
		{
			$this->getObject()->setZoneId($zoneId);

			foreach ($activitiesId as $activityId)
			{
				$this->getObject()->setActivityId($activityId);

				if (!is_null($usergroup))
				{
					$userType = $this->getValue('User_type');
					$createRule = $this->getValue('Create_rule');

					switch ($userType)
					{
					case self::EVERYBODY:
						{
							$users = $usergroup->getUsers();
							break;
						}
					case self::LEADERS:
						{
							$users = $usergroup->getLeaders();
							break;
						}
					case self::MEMBERS:
						{
							$users = $usergroup->getMembers();
							break;
						}
					case self::NONMEMBERS:
						{
							$users = $usergroup->getNonMembers();
							break;
						}
					}

					switch ($createRule)
					{
					case self::ADD:
						{
							foreach ($users as $user)
							{
								$subscription = $this->getObject()->copy();
								$subscription->setUser($user);
								$subscription->save();
							}

							break;
						}
					case self::REPLACE:
						{
							foreach ($users as $user)
							{
								$user->removeSubscriptionForUsergroup($usergroup->getId(), $zoneId, $activityId);
								$subscription = $this->getObject()->copy();
								$subscription->setUser($user);
								$subscription->save();
							}

							break;
						}
					case self::FILL:
						{
							foreach ($users as $user)
							{
								if ($user->countSubscriptionForUsergroup($usergroup->getId(), $zoneId, $activityId) == 0)
								{
									$subscription = $this->getObject()->copy();
									$subscription->setUser($user);
									$subscription->save();
								}
							}
							break;
						}
					case self::DELETE:
						{
							foreach ($users as $user)
							{
								$user->removeSubscriptionForUsergroup($usergroup->getId(), $zoneId, $activityId);
							}

							break;
						}
					}
				}
			}
		}
		$this->getObject()->delete();
	}
}
