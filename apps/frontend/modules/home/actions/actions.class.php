<?php

/**
 * home actions.
 *
 * @package    tempos
 * @subpackage home
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class homeActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
		$this->user = $this->getUser()->getTemposUser();
		$this->card = $this->getUser()->getTemposCard();
		$this->activities = $this->getUser()->getActivities();
		$this->groupsAsLeader = $this->getUser()->getGroupsAsLeader();
		$this->reservations = $this->getUser()->getUpcomingReservations(sfConfig::get('app_upcoming_reservations_count'));
  }

	public function executeProfile(sfWebRequest $request)
	{
		$this->user = $this->getUser()->getTemposUser();
		$this->card = $this->getUser()->getTemposCard();

		if (!is_null($this->user))
		{
			$this->form = new ProfileForm($this->user);
			$formName = $this->form->getName();

			if (!is_null($this->$formName = $request->getParameter($formName)))
			{
				$this->form->bind($this->$formName, $request->getFiles($formName));

				if ($this->form->isValid())
				{
					$this->form->save();
					$this->saved = true;
				}
			}
		}
	}

 /**
  * Executes group index action
  *
  * @param sfRequest $request A request object
  */
  public function executeUsergroupIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->usergroup = UsergroupPeer::retrieveByPk($request->getParameter('usergroupId')), sprintf('Object usergroup does not exist (%s).', $request->getParameter('usergroupId')));

		$this->forward404Unless($this->user = $this->getUser()->getTemposUser(), 'No user logged-in.');
		$this->forward404Unless($this->getUser()->getTemposUser()->isLeader($this->usergroup), 'The logged user is not leader of the specified usergroup.');

		$this->form = new UserSearchForm();
		$formName = $this->form->getName();
		$this->form->setIsActive(null);
		$this->form->setCardNumber(null);
		$this->form->setUsergroupsAsLeader(null);
		$this->form->setUsergroupsAsMember(null);
		
		$this->getUser()->syncParameters($this, 'home', 'usergroupIndex', array($formName, 'sort_column', 'sort_direction'), $request);

		if (is_null($this->sort_column))
		{
			$this->sort_column = 'name';
			$this->sort_direction = 'up';
		}

		$c = new Criteria();

		SortCriteria::addSortCriteria($c, $this->sort_column, UserPeer::getSortAliases(), $this->sort_direction);

		if (!is_null($this->$formName))
		{
			$this->filtered = true;
			$this->form->bind($this->$formName, $request->getFiles($formName));

			$c = UserPeer::getUsergroupMemberCriteria($this->usergroup->getId(), $c);

			if ($this->form->isValid())
			{
				$this->user_list = UserPeer::searchUsers(
					$this->form->getValue('login'),
					$this->form->getValue('family_name'),
					$this->form->getValue('surname'),
					$this->form->getValue('usergroupsAsLeader'),
					$this->form->getValue('usergroupsAsMember'),
					$this->form->getValue('activities'),
					$this->form->getValue('is_active'),
					$this->form->getValue('card_number'),
					$this->form->getValue('year_min'),
					$this->form->getValue('year_max'),
					$this->form->getValue('email_address'),
					$this->form->getValue('address'),
					$this->form->getValue('phone_number'),
					$c
				);
			}
		}
		
		if (!isset($this->user_list))
		{
			$this->filtered = false;
			$this->user_list = $this->usergroup->getMembers();
		}

		$this->count = count($this->user_list);

		$this->getUser()->setAttribute('usergroupId', $this->usergroup->getId());
  }

 /**
  * Executes user index action
  *
  * @param sfRequest $request A request object
  */
  public function executeUserIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->user = UserPeer::retrieveByPk($request->getParameter('userId')), sprintf('Object user does not exist (%s).', $request->getParameter('userId')));

		if ($this->getUser()->checkTemposUser($this->user))
		{
			$this->forward('home', 'index');
			return;
		}

    $this->forward404Unless($this->usergroup = UsergroupPeer::retrieveByPk($this->getUser()->getAttribute('usergroupId')), sprintf('Object usergroup does not exist (%s).', $this->getUser()->getAttribute('usergroupId')));

		$this->forward404Unless($this->user->isMember($this->usergroup), 'The user is not member of the specified usergroup.');
		$this->forward404Unless($this->getUser()->getTemposUser()->isLeader($this->usergroup), 'The logged user is not leader of the specified usergroup.');

		$this->activities = $this->user->getActiveSubscriptionsActivities();
		$this->activities = $this->usergroup->filterActivities($this->activities);

		$this->getUser()->setAttribute('userId', $this->user->getId());

		if (count($this->activities) == 1)
		{
			$this->redirect('home/overallIndex?activityId='.$this->activities[0]->getId());
		}
	}

 /**
  * Executes zone index action
  *
  * @param sfRequest $request A request object
  */
  public function executeZoneIndex(sfWebRequest $request)
	{
		$this->handleZoneParameters($request);

		$this->zones = Zone::explode_zones($this->person->getActiveSubscriptionsZones($this->activity->getId()));
	}

	public function executeOverallIndex(sfWebRequest $request)
	{
		$this->handleZoneParameters($request);

		$this->form = new RoomSearchForm($this->activity);
		$this->form->setIsActive(true);
		$formName = $this->form->getName();

		$this->getUser()->syncParameters($this, 'home', 'overallIndex', array($formName, 'displayPeriod'), $request);
		$this->getUser()->syncParameters($this, 'general', 'index', array('date'), $request);

		if (is_null($this->date))
		{
			$this->date = time();
		} else
		{
			$this->date = strtotime($this->date);
		}

		if (is_null($this->displayPeriod))
		{
			$this->displayPeriod = 'month';
		} else
		{
			$this->forward404Unless(in_array($this->displayPeriod, array('week', 'month')), sprintf('"%s" is not a valid value for displayPeriod', $this->displayPeriod));
		}

		$this->today = false;
		
		if ($this->displayPeriod == 'month')
		{
			if (date('m', $this->date) == date('m'))
			{
				$this->today = true;
			}
		} else
		{
			if (date('W', $this->date) == date('W'))
			{
				$this->today = true;
			}
		}
		
		$c = new Criteria();

		SortCriteria::addSortCriteria($c, $this->sort_column, RoomPeer::getSortAliases(), $this->sort_direction);

		$this->filtered = false;

		if (!is_null($this->$formName))
		{
			$this->form->bind($this->$formName, $request->getFiles($formName));

			if ($this->form->isValid())
			{
				$this->filtered = true;
				$this->room_list = RoomPeer::searchRooms(
					$this->activity->getId(),
					true,
					$this->form->getValue('namePattern'),
					$this->form->getValue('capacity'),
					$this->form->getValue('addressPattern'),
					$this->form->getValue('descriptionPattern'),
					$this->form->getFeaturesFieldsValues(),
					$c
				);

			}
		}

		if (!$this->filtered)
		{
			$this->room_list = RoomPeer::searchRooms(
				$this->activity->getId(),
				true,
				null,
				null,
				null,
				null,
				null,
				$c
			);

			$this->count = 0;
		}

		$this->room_list = $this->person->filterAccessibleRooms($this->room_list);
		$this->count = count($this->room_list);

		$this->availability = RoomPeer::getAvailability($this->room_list, $this->activity->getId(), $this->person, $this->displayPeriod, $this->date);
		
		$this->checkFeatures();
	}

	public function executeGanttIndex(sfWebRequest $request)
	{
		$this->handleZoneParameters($request);
		
		$this->getUser()->syncParameters($this, 'home', 'ganttIndex', array('rooms'), $request);

		if (is_null($this->rooms))
		{
			$this->redirect('home/overallIndex?activityId='.$this->activity->getId());
		}

		$room_id_list = explode(',', $this->rooms);
		$this->room_list = RoomPeer::doSelectFromIdList($room_id_list);
		$this->count = count($this->room_list);

		$this->forward404Unless($this->count > 0, 'No rooms specified');

		$this->getUser()->syncParameters($this, 'general', 'index', array('date'), $request);

		if (is_null($this->date))
		{
			$this->date = time();
		} else
		{
			$this->date = strtotime($this->date);
		}

		if ($this->count == 1)
		{
			if ($request->hasParameter('autobook'))
			{
				$this->redirect('reservation/new?roomId='.$this->room_list[0]->getId().'&date='.date('Y-m-d H:i:s', $this->date));
			} else
			{
				$this->redirect('reservation/index?roomId='.$this->room_list[0]->getId());
			}
		}

		$this->availability = RoomPeer::getGantt($this->room_list, $this->activity->getId(), $this->person, $this->date);
		$this->checkFeatures();
	}

	public function executeSearchRoom(sfWebRequest $request)
	{
		$this->handleZoneParameters($request);

		$this->form = new RoomSearchForm($this->activity);
		$this->form->setIsActive(true);
	}

	public function executeSearchRoomProcess(sfWebRequest $request)
	{
    $this->forward404Unless($request->isMethod('post'));

		$this->handleZoneParameters($request);

		$this->form = new RoomSearchForm($this->activity);
		$this->form->setIsActive(true);

    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

		if ($this->form->isValid())
		{
			$this->rooms = RoomPeer::searchRooms(
				$this->activity->getId(),
				$this->form->getValue('is_active'),
				$this->form->getValue('namePattern'),
				$this->form->getValue('capacity'),
				$this->form->getValue('addressPattern'),
				$this->form->getValue('descriptionPattern'),
				$this->form->getFeaturesFieldsValues()
			);

			$this->rooms = $this->person->filterAccessibleRooms($this->rooms);
			
			$this->setTemplate('roomIndex');
		} else
		{
			$this->setTemplate('searchRoom');
		}
	}

	protected function handleZoneParameters(sfWebRequest $request)
	{
		if ($request->hasParameter('self'))
		{
			$this->getUser()->getAttributeHolder()->remove('userId');
			$this->getUser()->getAttributeHolder()->remove('usergroupId');
		}

    $this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($request->getParameter('activityId')), sprintf('Object activity does not exist (%s).', $request->getParameter('activityId')));

		if ($this->getUser()->hasAttribute('userId'))
		{
    	$this->forward404Unless($this->usergroup = UsergroupPeer::retrieveByPk($this->getUser()->getAttribute('usergroupId')), sprintf('Object usergroup does not exist (%s).', $this->getUser()->getAttribute('usergroupId')));
			$this->forward404Unless($this->usergroup->hasActivity($this->activity->getId()), sprintf('User group does not have this entry (\'%s\')', $this->activity->getName()));
    	$this->forward404Unless($this->user = UserPeer::retrieveByPk($this->getUser()->getAttribute('userId')), sprintf('Object user does not exist (%s).', $this->getUser()->getAttribute('userId')));
			$this->forward404Unless($this->user->isMember($this->usergroup), 'The user is not member of the specified usergroup.');
			$this->forward404Unless($this->getUser()->getTemposUser()->isLeader($this->usergroup), 'The logged user is not leader of the specified usergroup.');

			$this->person = $this->user;
		} else
		{
			$this->forward404Unless($this->person = $this->getUser()->getPerson(), 'No user or card logged-in.');
		}

		$this->forward404Unless($this->person->hasActivity($this->activity->getId()), sprintf('Cannot access entry ("%s").', $this->activity));

		$this->getUser()->setAttribute('activityId', $this->activity->getId());
	}
	
	public function checkFeatures()
	{
		$c = new Criteria();
		$roomHasFeatures = RoomHasFeaturevaluePeer::doSelect($c);
		
		$this->links = array();
		$this->divs = array();
		
		foreach ($this->room_list as $room)
		{
			$one = false;
			$nbMax = 2;
			$occ = 0;
			$i = 0;
			$roomId = $room->getId();
			
			$this->links[$roomId] = $room->getName();
			$this->divs[$roomId] = '';
			
			foreach ($roomHasFeatures as $f)
			{
				if ($f->getRoomId() == $roomId)
				{
					if ($occ < $nbMax)
					{
						if (!$one)
						{
							$this->links[$roomId] .= ' (';
							$one = true;
						} else
						{
							$this->links[$roomId] .= ', ';
							$this->divs[$roomId] .= ', ';
						}
						$this->divs[$roomId] .= $f->getFeaturevalue();
						$this->links[$roomId] .= $f->getFeaturevalue();
					} elseif ($occ == $nbMax)
					{
						$this->links[$roomId] .= ', ...)';
						$this->divs[$roomId] .= ', '.$f->getFeaturevalue();
					} else
					{
						$this->divs[$roomId] .= ', '.$f->getFeaturevalue();
					}
					$occ++;
				}
				
				$i++;
				
				if ($i >= count($roomHasFeatures) && $one && $occ <= $nbMax)
				{
					$this->links[$roomId] .= ')';
				}
			}
		}
	}
}
