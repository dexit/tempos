<?php

/**
* Reservation delete form.
*
* @package    tempos
* @subpackage form
* @author     Your name here
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class ReservationDeleteForm extends sfForm
{

	public function configure()
	{
		$c = new Criteria();
		
		$years = range(date('Y'), date('Y') + 10);
		$minutes = array('00', 30);

		$periodicityChoices = array(
		'dayly' => sfContext::getInstance()->getI18N()->__('Dayly'),
		'weekly' => sfContext::getInstance()->getI18N()->__('Weekly'),
		'monthly' => sfContext::getInstance()->getI18N()->__('Monthly'),
		'annual' => sfContext::getInstance()->getI18N()->__('Annual'),
		);

		$this->setWidgets(array(
		'start_date'     => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number', 'can_be_empty' => false, 'can_be_empty' => false)),
		'end_date'       => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number', 'can_be_empty' => false, 'can_be_empty' => false)),
		'start_hour'     => new sfWidgetFormTime(array('can_be_empty' => false, 'minutes' =>array_combine($minutes, $minutes))),
		'end_hour'       => new sfWidgetFormTime(array('can_be_empty' => false, 'minutes' =>array_combine($minutes, $minutes))),
		'activity' 		 => new sfWidgetFormPropelChoiceMany(array('model' => 'Activity', 'add_empty' => false, 'expanded' => true)),
		'rooms'          => new sfWidgetFormPropelChoiceMany(array('model' => 'Room', 'add_empty' => false, 'expanded' => true)),
		'periodicity'    => new sfWidgetFormChoice(array('choices' => $periodicityChoices)),
		'number'         => new sfWidgetFormInput(),
		'stop_on_error'  => new sfWidgetFormInputCheckbox(),
		));
		
		// print '</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>';
		// var_dump($this->getWidget('activity')->getDefault());
		// var_dump($this->getWidget('rooms'));

		$this->setValidators(array(
		'start_date'	=> new sfValidatorDate(array('required' => true)),
 		'end_date'      => new sfValidatorDate(array('required' => true)),
		'start_hour'    => new sfValidatorTime(),
		'end_hour'      => new sfValidatorTime(),
		'activity'		=> new sfValidatorPropelChoiceMany(array('model' => 'Activity', 'column' => 'id', 'required' => false)),
		'rooms'         => new sfValidatorPropelChoiceMany(array('model' => 'Room', 'column' => 'id', 'required' => false)),
		'periodicity'   => new sfValidatorChoice(array('choices' => array_keys($periodicityChoices))),
		'number'        => new sfValidatorNumber(array('min' => 1, 'required' => true)),
		'stop_on_error' => new sfValidatorBoolean(array('required' => false)),
		));

		$this->setDefault('start_date', date('Y/m/d'));
		$this->setDefault('end_date', date('Y/m/d', strtotime('+ 1 week')));
		$this->setDefault('number', 1);
		$this->setDefault('activity', 0);

		$this->widgetSchema->setLabel('end_date', 'Stop date');

		$this->widgetSchema['start_date']->setOption('years', array_combine($years, $years));
		$this->widgetSchema['end_date']->setOption('years', array_combine($years, $years));
		$this->widgetSchema->setNameFormat('reservationDelete[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		$this->validatorSchema->setPostValidator(
			new sfValidatorSchemaCompare('end_date', sfValidatorSchemaCompare::GREATER_THAN_EQUAL, 'start_date', array(), array('invalid' => 'The stop date must be after the start date.')
                	)
		);
	}

	public function setDefaultDeletion($beginDate = null, $endDate = null, $beginHour = null, $endHour = null, $reservation = null, $numberPeriodicity = null)
	{
		if (is_null($beginDate))
		{
			$beginDate = date('Y/m/d');
		}

		if (is_null($endDate))
		{
			$endDate = date('Y/m/d', strtotime('+ 1 week'));
		}

		$this->setDefault('start_date', strtotime($beginDate));
		$this->setDefault('end_date', strtotime($endDate));

		if (!is_null($beginHour))
		{
			$this->setDefault('start_hour', strtotime($beginHour));	
		}

		if (!is_null($endHour))
		{
			$this->setDefault('end_hour', strtotime($endHour));
		}

		if (!is_null($reservation))
		{
			$this->setDefault('activity', $reservation->getActivity()->getId());
			$this->setDefault('rooms', $reservation->getRoomProfile()->getRoom()->getId());
		}

		if (!is_null($numberPeriodicity))
		{
			$this->setDefault('number', $numberPeriodicity);
		}
	}
}
