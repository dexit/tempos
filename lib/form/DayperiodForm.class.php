<?php

/**
 * Dayperiod form.
 *
 * @package    tempos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class DayperiodForm extends BaseDayperiodForm
{
	protected $defaultWeekDay = null;

  public function configure()
  {
		// Layout
		$this->widgetSchema['day_of_week'] = new sfWidgetFormWeekDayChoice();
		$this->widgetSchema['Room_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['start']->setOption('minutes', array(0 => '00', 30 => '30'));
    $this->widgetSchema['stop']->setOption('minutes', array(0 => '00', 30 => '30'));
		
		// Validators
		$this->validatorSchema['day_of_week']->setOption('min', 0);
		$this->validatorSchema['day_of_week']->setOption('max', 6);

		$this->validatorSchema->setPostValidator(
			new sfValidatorAnd(array(
				new sfDayperiodStartStopValidator('start', 'stop', array(), array('invalid' => 'Start time must be before stop time.')),
				new sfDayperiodOverlapValidator(array(), array('invalid' => 'An overlapping period exists.')),
			))
		);
  }

	public function setDefaultRoom($room)
	{
		$this->getObject()->setRoom($room);
		$this->setDefault('Room_id', $room->getId());
	}

	public function setDefaultWeekDay($day)
	{
		$this->getObject()->setDayOfWeek($day);
		$this->setDefault('day_of_week', $day);
		$this->widgetSchema['day_of_week'] = new sfWidgetFormInputHidden();
		$this->defaultWeekDay = $day;
	}

	public function getDefaultWeekDay()
	{
		if (!$this->isDefaultWeekDaySet())
		{
			return null;
		}

		return $this->defaultWeekDay;
	}

	public function isDefaultWeekDaySet()
	{
		return !is_null($this->defaultWeekDay);
	}

	public function setDefaultStartTime($start)
	{
		$this->getObject()->setStart($start);
		$this->setDefault('start', $start);
	}

	public function setDefaultStopTime($stop)
	{
		$this->getObject()->setStop($stop);
		$this->setDefault('stop', $stop);
	}

  protected function doSave($con = null)
  {
    parent::doSave($con);

		$this->saveRoundStartStop();
	}

	protected function saveRoundStartStop()
	{
		$this->getObject()->roundStartStop(30);
		$this->getObject()->save();
	}
}
