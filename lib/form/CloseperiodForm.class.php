<?php

/**
* Closeperiod form.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class CloseperiodForm extends BaseCloseperiodForm
{
	public function configure()
	{
		// Layout
		$this->widgetSchema['Room_id'] = new sfWidgetFormInputHidden();
		$this->widgetSchema['reason'] = new sfWidgetFormTextarea();
		$this->widgetSchema['start']->setOption('time', array('minutes' => array(0 => '00', 30 => '30')));
		$this->widgetSchema['stop']->setOption('time', array('minutes' => array(0 => '00', 30 => '30')));
		
		$this->setDefault('start', date('Y-m-d 00:00', time()));
		$this->setDefault('stop', date('Y-m-d 00:00', strtotime(date('Y-m-d', time()).'+ 1 week')));

		$this->validatorSchema->setPostValidator(
		new sfValidatorAnd(array(
		new sfDayperiodStartStopValidator('start', 'stop', array(), array('invalid' => 'Start date must be before stop date.')),
		new sfCloseperiodOverlapValidator(array(), array('invalid' => 'An overlapping closing period exists.')),
		))
		);
	}

	public function setDefaultRoom($room)
	{
		$this->getObject()->setRoom($room);
		$this->setDefault('Room_id', $room->getId());
	}
}
