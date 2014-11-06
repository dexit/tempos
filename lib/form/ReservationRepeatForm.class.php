<?php

/**
* Reservation repeat form.
*
* @package    tempos
* @subpackage form
* @author     Your name here
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class ReservationRepeatForm extends sfForm
{
	const COUNT = 0;
	const DATE = 1;
	const COUNTDATE = 2;

	protected $object = null;
	protected $forms = array();

	public function __construct($reservation)
	{
		if (is_null($reservation) || !is_object($reservation))
		{
			throw new ArgumentNullException('`reservation` cannot be null.');
		}

		$this->object = $reservation;

		parent::__construct();
	}

	public function getObject()
	{
		return $this->object;
	}

	public function getReservationForms()
	{
		return $this->forms;
	}
	
	public function setReservationForms($reservationForms)
	{
		$this->forms = $reservationForms;
	}

	public function configure()
	{
		$repeatTypeChoices = array(
		self::COUNT => sfContext::getInstance()->getI18N()->__('Count'),
		self::DATE => sfContext::getInstance()->getI18N()->__('Date'),
		self::COUNTDATE => sfContext::getInstance()->getI18N()->__('Count and date'),
		);

		$this->setWidgets(array(
		'repeat_type'	=> new sfWidgetFormChoice(array('choices' => $repeatTypeChoices)),
		'stop_on_error'	=> new sfWidgetFormInputCheckbox(),
		'count'			=> new sfWidgetFormInput(),
		'until_date'	=> new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
		'day_period'	=> new sfWidgetFormInput(),
		'week_period'	=> new sfWidgetFormInput(),
		'month_period'	=> new sfWidgetFormInput(),
		'year_period'	=> new sfWidgetFormInput(),
		));

		$this->setValidators(array(
		'repeat_type'	=> new sfValidatorChoice(array('choices' => array_keys($repeatTypeChoices), 'required' => true)),
		'stop_on_error'	=> new sfValidatorBoolean(array('required' => false)),
		'count'			=> new sfValidatorInteger(array('min' => 1, 'required' => false)),
		'until_date'	=> new sfValidatorDate(array('required' => false)),
		'day_period'	=> new sfValidatorInteger(array('min' => 0, 'required' => true)),
		'week_period'	=> new sfValidatorInteger(array('min' => 0, 'required' => true)),
		'month_period'	=> new sfValidatorInteger(array('min' => 0, 'required' => true)),
		'year_period'	=> new sfValidatorInteger(array('min' => 0, 'required' => true)),
		));

		$this->widgetSchema->setLabel('repeat_type', 'Repetition type');
		$this->widgetSchema->setLabel('stop_on_error', 'Stop on error');
		$this->widgetSchema->setLabel('count', 'Count');
		$this->widgetSchema->setLabel('until_date', 'Book until');
		$this->widgetSchema->setLabel('day_period', 'Day period');
		$this->widgetSchema->setLabel('week_period', 'Week period');
		$this->widgetSchema->setLabel('month_period', 'Month period');
		$this->widgetSchema->setLabel('year_period', 'Year period');

		$this->setDefault('repeat_type', self::DATE);
		$this->setDefault('stop_on_error', false);
		$this->setDefault('count', 10);
		$this->setDefault('until_date', strtotime(date('Y-m-d').' + 1 month'));
		$this->setDefault('day_period', 0);
		$this->setDefault('week_period', 1);
		$this->setDefault('month_period', 0);
		$this->setDefault('year_period', 0);

		$this->widgetSchema->setNameFormat('reservationRepeat[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		$this->validatorSchema->setPostValidator(
		new sfValidatorAnd(array(
		new sfReservationRepeatTypeValidator(array(), array()),
		new sfReservationRepeatPeriodValidator(array(), array()),
		))
		);
	}
	
	public function process()
	{
		$this->forms = array();

		$repeat_type = $this->getValue('repeat_type');
		$stop_on_error = $this->getValue('stop_on_error');
		$count = $this->getValue('count');
		$until_date = strtotime($this->getValue('until_date'));
		$day_period = $this->getValue('day_period');
		$week_period = $this->getValue('week_period');
		$month_period = $this->getValue('month_period');
		$year_period = $this->getValue('year_period');

		$base_date = $this->object->getDate();

		$result = true;
		$i = 0;
		
		do
		{
			++$i;

			$day = $day_period * $i;
			$week = $week_period * $i;
			$month = $month_period * $i * 4; // 4 semaines = 1 mois 
			$year = $year_period * $i * 52; // 52 semaines = 1 an
			
			$date = strtotime("$base_date + $day day + $week week + $month week + $year week");

			if ($repeat_type == self::COUNT)
			{
				if ($i > $count)
				{
					break;
				}
			} elseif ($repeat_type == self::DATE)
			{
				if ($date > $until_date)
				{
					break;
				}
			} else
			{
				if (($i > $count) || ($date > $until_date))
				{
					break;
				}
			}
			
			$reservation = $this->object->copy();
			$reservation->setDate($date);
			
			$form = new ReservationForm($reservation);
			$form->bindObject($reservation);

			if (!$form->isValid())
			{
				$result = false;
				
				$this->forms[] = $form;
				
				if ($stop_on_error)
				{
					break;
				}
			} else
			{
				$this->forms[] = $form;
			}
		} while (true);

		return $result;
	}
}
