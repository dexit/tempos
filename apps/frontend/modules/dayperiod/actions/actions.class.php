<?php

/**
 * dayperiod actions.
 *
 * @package    tempos
 * @subpackage dayperiod
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class dayperiodActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));
    $this->dayperiod_list = DayperiodPeer::doSelectFromRoom($this->room->getId());
		$this->copyForm = new DayperiodCopyForm();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));
    $this->form = new DayperiodForm();
		$this->form->setDefaultRoom($this->room);

		$day = $request->getParameter('day');

		if (!is_null($day))
		{
			$this->form->setDefaultWeekDay($day);
		}

		$start = $request->getParameter('start');

		if (!empty($start))
		{
			$this->form->setDefaultStartTime($start);
		}

		$stop = $request->getParameter('stop');

		if (!empty($stop))
		{
			$this->form->setDefaultStopTime($stop);
		}

		$duration = $request->getParameter('duration');

		if (!empty($duration) && (!empty($start)))
		{
			$stop = date('H:i:s', strtotime($start." + $duration minute"));
			$this->form->setDefaultStopTime($stop);
		}
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));

    $this->form = new DayperiodForm();

		$day = $request->getParameter('day');

		if (!is_null($day))
		{
			$this->form->setDefaultWeekDay($day);
		}

    $this->processForm($request, $this->form);

		$this->form->setDefaultRoom($this->room);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($dayperiod = DayperiodPeer::retrieveByPk($request->getParameter('id')), sprintf('Object dayperiod does not exist (%s).', $request->getParameter('id')));
		$this->room = $dayperiod->getRoom();
    $this->form = new DayperiodForm($dayperiod);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($dayperiod = DayperiodPeer::retrieveByPk($request->getParameter('id')), sprintf('Object dayperiod does not exist (%s).', $request->getParameter('id')));
    $this->form = new DayperiodForm($dayperiod);
		$this->room = $dayperiod->getRoom();

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($dayperiod = DayperiodPeer::retrieveByPk($request->getParameter('id')), sprintf('Object dayperiod does not exist (%s).', $request->getParameter('id')));
		$roomId = $dayperiod->getRoomId();
    $dayperiod->delete();

    $this->redirect('dayperiod/index?roomId='.$roomId);
  }

	public function executeRepeatWeek(sfWebRequest $request)
	{
    $this->forward404Unless($dayperiod = DayperiodPeer::retrieveByPk($request->getParameter('id')), sprintf('Object dayperiod does not exist (%s).', $request->getParameter('id')));

		$dayperiod->repeatWeek();

    $this->redirect('dayperiod/index?roomId='.$dayperiod->getRoomId());
	}

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $dayperiod = $form->save();

      $this->redirect('dayperiod/index?roomId='.$dayperiod->getRoomId());
    }
  }
}
