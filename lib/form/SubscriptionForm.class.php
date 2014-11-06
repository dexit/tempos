<?php

/**
* Subscription form.
*
* @package    tempos
* @subpackage form
* @author     Your name here
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class SubscriptionForm extends BaseSubscriptionForm
{
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

		// Layout
		$this->widgetSchema['Activity_id']->setOption('expanded', true);
		$this->widgetSchema['Activity_id']->setOption('criteria', $activitySortCriteria);
		$this->widgetSchema['Zone_id'] = new sfWidgetFormZoneChoice(array('multiple' => false, 'add_empty' => false));
		$this->widgetSchema['unlimitedCredit'] = new sfWidgetFormInputCheckbox();
		$this->widgetSchema['UserGroup_id'] = new sfWidgetFormInputHidden();

		$years = range(date('Y') - 128, date('Y') + 128);
		$this->widgetSchema['start']->setOption('years', array_combine($years, $years));
		$this->widgetSchema['stop']->setOption('years', array_combine($years, $years));

		// Labels
		$activityLabel = ConfigurationHelper::getParameter('Rename', 'activity_label');
		if (is_null($activityLabel) || empty($activityLabel))
		{
			$activityLabel = 'Activity';
		}
		
		$this->widgetSchema->setLabel('Activity_id', $activityLabel);
		$this->widgetSchema->setLabel('Zone_id', 'Zone');
		$this->widgetSchema->setLabel('UserGroup_id', 'Group');
		$this->widgetSchema->setLabel('unlimitedCredit', 'Unlimited access');
		$this->widgetSchema->setLabel('minimum_delay', 'Minimum number of hours in advance');
		$this->widgetSchema->setLabel('maximum_delay', 'Maximum number of days in advance');
		$this->widgetSchema->setLabel('minimum_duration', 'Minimum duration of reservations (minutes)');
		$this->widgetSchema->setLabel('maximum_duration', 'Maximum duration of reservations (minutes)');
		$this->widgetSchema->setLabel('hours_per_week', 'How many hours allowed per week (hours)');
		$this->widgetSchema->setLabel('credit', 'Max. credits (hours)');

		// Validators
		$step = sfConfig::get('app_booking_step');
		$this->validatorSchema['credit']->setOption('min', 1);
		$this->validatorSchema['unlimitedCredit'] = new sfValidatorBoolean(array('required' => false));
		$this->validatorSchema['minimum_delay']->setOption('min', 0);
		$this->validatorSchema['maximum_delay']->setOption('min', 0);
		$this->validatorSchema['minimum_duration']->setOption('min', $step);
		$this->validatorSchema['maximum_duration']->setOption('min', $step);
		$this->validatorSchema['hours_per_week']->setOption('min', 1);
		//$this->validatorSchema['hours_per_week']->setOption('max', 170);

		// Defaults
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

	public function setDefaultUser(User $user)
	{
		$this->getObject()->setUser($user);
		$this->getObject()->setCard(null);
		$this->setDefault('User_id', $user->getId());
		$this->hideOwnerFields();
	}

	public function setDefaultCard(Card $card)
	{
		$this->getObject()->setUser(null);
		$this->getObject()->setCard($card);
		$this->setDefault('Card_id', $card->getId());
		$this->setDefault('unlimitedCredit', false);
		$this->hideOwnerFields();
	}

	public function setDefaultUsergroup(Usergroup $usergroup)
	{
		$this->getObject()->setUsergroup(null);
		$this->setDefault('UserGroup_id', $usergroup->getId());
	}

	protected function hideOwnerFields()
	{
		$this->widgetSchema['User_id'] = new sfWidgetFormInputHidden();
		$this->widgetSchema['Card_id'] = new sfWidgetFormInputHidden();
	}

	protected function doSave($con = null)
	{
		parent::doSave($con);

		if ($this->getValue('unlimitedCredit') == true)
		{
			$this->getObject()->setCredit(null);
			$this->getObject()->save();
		}
	}
}
