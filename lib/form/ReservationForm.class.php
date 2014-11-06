<?php

/**
* Reservation form.
*
* @package    tempos
* @subpackage form
* @author     Your name here
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class ReservationForm extends BaseReservationForm
{
	var $room;

	public function configure()
	{
		// Layout
		unset($this['ReservationReason_id']);
		unset($this['status']);
		unset($this['created_at']);
		unset($this['updated_at']);
		unset($this['is_activated']);

		$this->widgetSchema['comment'] = new sfWidgetFormTextarea();
		$this->widgetSchema['UserGroup_id'] = new sfWidgetFormInputHidden();
		$this->widgetSchema['User_id'] = new sfWidgetFormInputHidden();
		$this->widgetSchema['Card_id'] = new sfWidgetFormInputHidden();

		// Options
		$this->widgetSchema['date']->setOption('time', array('minutes' => array(0 => '00', 30 => '30')));

		// Labels
		$this->widgetSchema->setLabel('RoomProfile_id', 'Physical access');

		$activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');
		if (is_null($activityItem) || empty($activityItem))
		{
			$activityItem = 'Activity';
		}

		$this->widgetSchema->setLabel('Activity_id', $activityItem);
		$this->widgetSchema->setLabel('Card_id', 'Card');
		$this->widgetSchema->setLabel('User_id', 'User');
		$this->widgetSchema->setLabel('duration', 'Duration (minutes)');
		$this->widgetSchema->setLabel('date', 'Date and time');
		$this->widgetSchema->setLabel('members_count', 'Members count');
		$this->widgetSchema->setLabel('guests_count', 'Guests count');
		$this->widgetSchema->setLabel('price', 'Price');
        $this->widgetSchema->setLabel('reservation_has_user_list', 'Other member(s)');

		// Checking specific fields name
		$custom1 = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_1');	
		$custom2 = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_2');
		$custom3 = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_3');

        $this->widgetSchema->setLabel('custom_1', empty($custom1) ? 'hide' : $custom1);
        $this->widgetSchema->setLabel('custom_2', empty($custom2) ? 'hide' : $custom2);
        $this->widgetSchema->setLabel('custom_3', empty($custom3) ? 'hide' : $custom3);

		$userSortCriteria = new Criteria();
		$userSortCriteria->addAscendingOrderByColumn(UserPeer::FAMILY_NAME);
		$this->widgetSchema['reservation_has_user_list']->setOption('expanded', true);
		$this->widgetSchema['reservation_has_user_list']->setOption('criteria', $userSortCriteria);

		// Validators
		$step = sfConfig::get('app_booking_step');
		$this->validatorSchema['RoomProfile_id']->setOption('required', true);
		$this->validatorSchema['duration']->setOption('min', $step);
		$this->validatorSchema['members_count']->setOption('min', 0);
		$this->validatorSchema['guests_count']->setOption('min', 0);

		// Defaults
		$this->setDefault('members_count', 0);
		$this->setDefault('guests_count', 0);

		$this->validatorSchema->setPostValidator(
            new sfValidatorAnd(self::getReservationValidators())
		);
	}

	public static function getReservationValidators()
	{
		$validators = array(
            new sfReservationCollideValidator(array(), array()),
            new sfReservationOverlapValidator(array(), array()),
            new sfReservationPeopleCountValidator(array(), array()),
            new sfReservationPastValidator(array(), array()),
            new sfReservationDurationValidator(array(), array()),
            new sfReservationHoursPerWeekValidator(array(), array()),
            new sfReservationDelayValidator(array(), array()),
            new sfReservationCreditValidator(array(), array()),
		);

		return $validators;
	}

	// Virtually binds using an object
	public function bindObject(Reservation $reservation)
	{
		$taintedValues = $reservation->toArray(BasePeer::TYPE_FIELDNAME);
		$taintedFiles = array();

		foreach ($taintedValues as $key => $value)
		{
			if (!isset($this->widgetSchema[$key]))
			{
				unset($taintedValues[$key]);
			}
		}

		$taintedValues[self::$CSRFFieldName] = $this->getCSRFToken(self::$CSRFSecret);

		$this->bind($taintedValues, $taintedFiles);
	}

	public function renderErrors()
	{
		$output = $this->renderGlobalErrors();

		foreach ($this->getWidgetSchema()->getPositions() as $widgetName)
		{
			$output .= $this[$widgetName]->renderError();
		}

		return $output;
	}
	
	public function getErrorsToString()
	{
		$output = $this->renderGlobalErrors();

		foreach ($this->getWidgetSchema()->getPositions() as $widgetName)
		{
			$output .= $this[$widgetName]->getError();
		}

		return $output;
	}

	public function setDefaultRoom($room)
	{
		$this->room = $room;
		$this->widgetSchema['RoomProfile_id']->setOption('criteria', RoomprofilePeer::getFromRoomCriteria($room->getId()));
	}

	public function getRoom()
	{
		return $this->room;
	}

	public function setDefaultCustomUsers()
	{
		$r_id = $this->getObject()->getId();
		
		//print ('Current reservation ID: '.$r_id.'<br/>');
	
		if (!is_null($r_id) && !empty($r_id))
		{
			$c = new Criteria();
			$c->add(ReservationOtherMembersPeer::RESERVATION_ID, $r_id);
		
			$o_members = ReservationOtherMembersPeer::doSelect($c);

			//print ('All other members: <br/>');
			//print_r($o_members);

			$values = array();

			if (!is_null($o_members))
			{
				foreach($o_members as $member)
				{
					$values[] = $member->getUserId();
				}
			}

			$this->setDefault('reservation_has_user_list', $values);
		}
	}
	
	public function setDefaultActivity($activity)
	{
		$this->getObject()->setActivity($activity);
		$this->setDefault('Activity_id', $activity->getId());
		$this->widgetSchema['Activity_id'] = new sfWidgetFormInputHidden();
		$this->widgetSchema->setLabel('Activity_id', 'Activity');

		if ($activity->countReservationreasons() > 0)
		{
			$this->widgetSchema['ReservationReason_id'] = new sfWidgetFormPropelChoice(array('model' => 'Reservationreason', 'add_empty' => false, 'criteria' => ReservationreasonPeer::getFromActivityCriteria($activity->getId())));
			$this->widgetSchema->setLabel('ReservationReason_id', 'Reason');
			// FIXME: On dirait que le critère n'est pas pris en compte : bug de symfony ?
			$this->validatorSchema['ReservationReason_id'] = new sfValidatorPropelChoice(array('model' => 'Reservationreason', 'column' => 'id', 'required' => true, 'criteria' => ReservationreasonPeer::getFromActivityCriteria($activity->getId())));
		}

		if ($this->getObject()->isNew())
		{
			$this->setDefault('guests_count', $activity->getMinimumOccupation() - 1);
		}
	}

	public function setDefaultCard($card)
	{
		$this->getObject()->setCard($card);
		$this->getObject()->setUser(null);
		$this->setDefault('Card_id', $card->getId());
		$this->getObject()->setUsergroup(null);
		unset($this['UserGroup_id']);
		unset($this['User_Id']);
	}

	public function setDefaultUser($user)
	{
		$this->getObject()->setCard(null);
		$this->getObject()->setUser($user);
		$this->setDefault('User_id', $user->getId());

		if ($user->isLeader())
		{
			$activityId = $this->getObject()->getActivity();

			if (!is_null($activityId))
			{
				$activityId = $activityId->getId();
			}
			
			$usergroupId = $this->getObject()->getUsergroup();

			if (!is_null($usergroupId))
			{
				$usergroupId = $usergroupId->getId();
			}
			
			$this->widgetSchema['UserGroup_id'] = new sfWidgetFormPropelChoice(array('model' => 'Usergroup', 'add_empty' => true, 'criteria' => $user->getUsergroupAsLeaderCriteria($activityId)));
			$this->widgetSchema->setLabel('UserGroup_id', 'Book for an entire group');
			$this->validatorSchema['UserGroup_id'] = new sfValidatorPropelChoice(array('model' => 'Usergroup', 'column' => 'id', 'required' => false, 'criteria' => $user->getUsergroupAsLeaderCriteria($activityId)));
		}
		else
		{
			unset($this['UserGroup_id']);
		}

		unset($this['Card_Id']);
	}

	public function setDefaultUsergroup($usergroup)
	{
		$this->getObject()->setUsergroup($usergroup);
		$this->setDefault('UserGroup_id', $usergroup->getId());
		$this->widgetSchema['UserGroup_id'] = new sfWidgetFormInputHidden();
	}

	public function setDefaultDate($date)
	{
		$this->getObject()->setDate($date);
		$this->setDefault('date', $date);
		$this->widgetSchema['date'] = new sfWidgetFormInputHidden();
	}

	public function setDefaultDuration($duration)
	{
		$this->getObject()->setDuration($duration);
		$this->setDefault('duration', $duration);
	}

	protected function doSave($con = null)
	{
		parent::doSave($con);

		$this->getObject()->setIsActivated(true);

		$this->saveRoundDate();
	}

	protected function saveRoundDate()
	{
		$this->getObject()->roundDate(30);
	}
}
