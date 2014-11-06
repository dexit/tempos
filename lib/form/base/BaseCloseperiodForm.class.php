<?php

/**
* Closeperiod form base class.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseCloseperiodForm extends BaseFormPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'id'      => new sfWidgetFormInputHidden(),
		'start'   => new sfWidgetFormI18nDateTime(array('culture' => 'fr')),
		'stop'    => new sfWidgetFormI18nDateTime(array('culture' => 'fr')),
		'Room_id' => new sfWidgetFormPropelChoice(array('model' => 'Room', 'add_empty' => false)),
		'reason'  => new sfWidgetFormInput(),
		));

		$this->setValidators(array(
		'id'      => new sfValidatorPropelChoice(array('model' => 'Closeperiod', 'column' => 'id', 'required' => false)),
		'start'   => new sfValidatorDateTime(),
		'stop'    => new sfValidatorDateTime(),
		'Room_id' => new sfValidatorPropelChoice(array('model' => 'Room', 'column' => 'id')),
		'reason'  => new sfValidatorString(array('max_length' => 128)),
		));

		$this->widgetSchema->setNameFormat('closeperiod[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'Closeperiod';
	}


}
