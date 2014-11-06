<?php

/**
 * occupancy actions.
 *
 * @package    tempos
 * @subpackage occupancy
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class occupancyActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
		$this->form = new OccupancyForm(array(), array(), false);
		$formName = $this->form->getName();

		$this->getUser()->syncParameters($this, 'occupancy', 'index', array($formName), $request);

		$this->occupancy_list = array();
		$this->filtered = false;

		if (!is_null($this->$formName))
		{
			$this->filtered = true;
			$this->form->bind($this->$formName, $request->getFiles($formName));

			if ($this->form->isValid())
			{
				$this->occupancy_list = RoomPeer::getOccupancy(
					$this->form->getValue('zone'),
					$this->form->getValue('activities'),
					strtotime($this->form->getValue('begin_date')),
					strtotime($this->form->getValue('end_date'))
				);
			}
		}
  }
}
