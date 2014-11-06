<?php

/**
* Reservation delete confirm form.
*
* @package    tempos
* @subpackage form
* @author     Your name here
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2012-18-09 17:08:00Z dwhittle $
*/
class ReservationDeleteConfirmForm extends sfForm
{
	public function configure()
	{
		$choices = array(
			'this'     => sfContext::getInstance()->getI18N()->__('This reservation only'),
			'next'     => sfContext::getInstance()->getI18N()->__('This and following reservations').'<sup>*</sup>',
			'previous' => sfContext::getInstance()->getI18N()->__('This and previous reservations').'<sup>*</sup>',
			'all'      => sfContext::getInstance()->getI18N()->__('All reservations of the same repetition').'<sup>*</sup>');

		$this->widgetSchema['deletion_choices'] = new sfWidgetFormChoice(array(
			'expanded' => true, 
			'multiple' => false, 
			'choices'  => $choices));

		$this->validatorSchema['deletion_choices'] = new sfValidatorChoice(array('choices' => array_keys($choices)));

		$this->widgetSchema->setLabel('deletion_choices', 'Deletion choices');
	
		$this->setDefault('deletion_choices', 'this');

		$this->widgetSchema->setNameFormat('reservationDeleteConfirm[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
	}
}
