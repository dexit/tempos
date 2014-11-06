<?php

/**
 * room actions.
 *
 * @package    tempos
 * @subpackage room
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class roomActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
		$this->form = new RoomSearchForm();
		$formName = $this->form->getName();

		$this->step = sfConfig::get('app_max_rooms_on_roomlist');

		$this->getUser()->syncParameters($this, 'room', 'index', array('offset', 'limit', $this->form->getName(), 'sort_column', 'sort_direction'), $request);

		if (is_null($this->sort_column))
		{
			$this->sort_column = 'name';
			$this->sort_direction = 'up';
		}

		if (is_null($this->offset))
		{
			$this->offset = 0;
		}

		if (is_null($this->limit) || ($this->limit <= 0))
		{
			$this->limit = $this->step;
		}

		$c = new Criteria();

		SortCriteria::addSortCriteria($c, $this->sort_column, RoomPeer::getSortAliases(), $this->sort_direction);

		if (!is_null($this->$formName))
		{
			$this->filtered = true;
			$this->form->bind($this->$formName, $request->getFiles($formName));

			if ($this->form->isValid())
			{
				$this->room_list = RoomPeer::searchRooms(
					$this->form->getValue('Activity_id'),
					$this->form->getValue('is_active'),
					$this->form->getValue('namePattern'),
					$this->form->getValue('capacity'),
					$this->form->getValue('addressPattern'),
					$this->form->getValue('descriptionPattern'),
					$this->form->getFeaturesFieldsValues(),
					$c
				);

				$this->count = count($this->room_list);
				$this->room_list = array_slice($this->room_list, $this->offset, $this->limit);
			} else
			{
				$this->setTemplate('search');
			}
		} else
		{
			$c->setOffset($this->offset);

			if ($this->limit >= 0)
			{
				$c->setLimit($this->limit);
			}

			$this->filtered = false;
			$this->room_list = RoomPeer::doSelect($c);
			$this->count = RoomPeer::doCount(new Criteria());
		}

		if (($this->offset < 0) || (($this->offset >= $this->count) && ($this->count > 0)))
		{
			$this->forward404('Invalid offset/count values.');
		}
  }

	public function executeSearch(sfWebRequest $request)
	{
		$this->form = new RoomSearchForm();
	}

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new RoomForm();

		$this->checkReferer($request);

		if ($request->hasParameter('parentZoneId'))
		{
    	$this->forward404Unless($parentZone = ZonePeer::retrieveByPk($request->getParameter('parentZoneId')), sprintf('Object zone does not exist (%s).', $request->getParameter('parentZoneId')));

			$this->form->addParentZone($parentZone);
		}
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

		$this->checkReferer($request);

    $this->form = new RoomForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

	public function executeActivate(sfWebRequest $request)
	{
    $this->forward404Unless($room = RoomPeer::retrieveByPk($request->getParameter('id')), sprintf('Object room does not exist (%s).', $request->getParameter('id')));

		$room->setIsActive(true);
		$room->save();

		$this->redirect('room/index');
	}

  public function executeEdit(sfWebRequest $request)
  {
		$this->checkReferer($request);

    $this->forward404Unless($room = RoomPeer::retrieveByPk($request->getParameter('id')), sprintf('Object room does not exist (%s).', $request->getParameter('id')));
    $this->form = new RoomForm($room);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($room = RoomPeer::retrieveByPk($request->getParameter('id')), sprintf('Object room does not exist (%s).', $request->getParameter('id')));
    $this->form = new RoomForm($room);

		$this->checkReferer($request);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

	public function checkReferer(sfWebRequest $request)
	{
		if ($request->hasParameter('referer'))
		{
			$this->referer = $request->getParameter('referer');

			$this->forward404Unless(empty($this->referer) || ($this->referer == 'zone'), '"zone" is the only valid referer.');
		} else
		{
			$this->referer = '';
		}
	}

  public function executeFeaturesEdit(sfWebRequest $request)
  {
    $this->forward404Unless($room = RoomPeer::retrieveByPk($request->getParameter('id')), sprintf('Object room does not exist (%s).', $request->getParameter('id')));
    $this->form = new RoomFeaturesValuesForm($room);
  }

  public function executeFeaturesUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($room = RoomPeer::retrieveByPk($request->getParameter('id')), sprintf('Object room does not exist (%s).', $request->getParameter('id')));
    $this->form = new RoomFeaturesValuesForm($room);

    $this->processForm($request, $this->form);

    $this->setTemplate('featuresEdit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($room = RoomPeer::retrieveByPk($request->getParameter('id')), sprintf('Object room does not exist (%s).', $request->getParameter('id')));
    $room->delete();

    $this->redirect('room/index');
  }

	public function executeCopyDayperiods(sfWebRequest $request)
	{
    $this->forward404Unless($request->isMethod('post'));
    $this->forward404Unless($room = RoomPeer::retrieveByPk($request->getParameter('id')), sprintf('Object room does not exist (%s).', $request->getParameter('id')));

    $this->form = new DayperiodCopyForm();

    $this->processCopyForm($request, $this->form, $room);

		$this->redirect('dayperiod/index?roomId='.$room->getId());
	}

	public function executeClearDayperiods(sfWebRequest $request)
	{
    $request->checkCSRFProtection();

    $this->forward404Unless($room = RoomPeer::retrieveByPk($request->getParameter('id')), sprintf('Object room does not exist (%s).', $request->getParameter('id')));

		$room->clearDayperiods();

		$this->redirect('dayperiod/index?roomId='.$room->getId());
	}

	public function executeUnforce(sfWebRequest $request)
	{
    $request->checkCSRFProtection();

    $this->forward404Unless($room = RoomPeer::retrieveByPk($request->getParameter('id')), sprintf('Object room does not exist (%s).', $request->getParameter('id')));

		$room->unforce();

		$this->redirect('room/index');
	}

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $room = $form->save();

			if (!empty($this->referer))
			{
				if ($this->referer == 'zone')
				{
					$this->redirect('zone/index');
				}
			} else
			{
      	$this->redirect('room/index');
			}
    }
  }

  protected function processCopyForm(sfWebRequest $request, sfForm $form, Room $room)
	{
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

		if ($form->isValid())
		{
			$this->forward404Unless($copyRoom = RoomPeer::retrieveByPk($form->getValue('copyRoom_id')), sprintf('Object room does not exist (%s).', $form->getValue('copyRoom_id')));

			if ($copyRoom->getId() != $room->getId())
			{
				$room->copyDayperiodsFromRoom($copyRoom);
				$room->save();
			}

			$this->redirect('dayperiod/index?roomId='.$room->getId());
		}
	}
}
