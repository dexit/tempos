<?php

/**
 * Reservationreason form.
 *
 * @package    tempos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class ReservationreasonForm extends BaseReservationreasonForm
{
  public function configure()
  {
		// Validators
    $this->validatorSchema['name'] =  new sfXSSValidatorString(array('max_length' => 64));
  }

	public function setDefaultActivity($activity)
	{
		$this->getObject()->setActivity($activity);
		$this->setDefault('Activity_id', $activity->getId());
		$this->widgetSchema['Activity_id'] = new sfWidgetFormInputHidden();
	}
}
