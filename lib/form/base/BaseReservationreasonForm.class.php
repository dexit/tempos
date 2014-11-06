<?php

/**
* Reservationreason form base class.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseReservationreasonForm extends BaseFormPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'id'          => new sfWidgetFormInputHidden(),
		'Activity_id' => new sfWidgetFormPropelChoice(array('model' => 'Activity', 'add_empty' => false)),
		'name'        => new sfWidgetFormInput(),
		));

		$this->setValidators(array(
		'id'          => new sfValidatorPropelChoice(array('model' => 'Reservationreason', 'column' => 'id', 'required' => false)),
		'Activity_id' => new sfValidatorPropelChoice(array('model' => 'Activity', 'column' => 'id')),
		'name'        => new sfValidatorString(array('max_length' => 64)),
		));

		$this->widgetSchema->setNameFormat('reservationreason[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'Reservationreason';
	}
}
