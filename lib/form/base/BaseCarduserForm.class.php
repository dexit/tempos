<?php

/**
* Carduser form base class.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseCarduserForm extends BaseFormPropel
{
	public function setup()
	{
		$years = range(date('Y') - 100, date('Y'));
		
		$this->setWidgets(array(
		'id'          => new sfWidgetFormInputHidden(),
		'family_name' => new sfWidgetFormInput(),
		'surname'     => new sfWidgetFormInput(),
		'birthdate'   => new sfWidgetFormJQueryDate(array(
			'image'  => '/images/calendar.gif',
			'culture'=> 'fr',
			'config' => '{firstDay: 1, changeMonth: true, changeYear: true, yearRange: \'-100:+0\'}',
			'date_widget' => new sfWidgetFormI18nDate(array(
				'format' => '%day%/%month%/%year%',
				'culture' => 'fr',
				'month_format' => 'number',
				'years' => array_combine($years, $years))))),
		));

		$this->setValidators(array(
		'id'          => new sfValidatorPropelChoice(array('model' => 'Carduser', 'column' => 'id', 'required' => false)),
		'family_name' => new sfValidatorString(array('max_length' => 64)),
		'surname'     => new sfValidatorString(array('max_length' => 64)),
		));

		$this->widgetSchema->setNameFormat('carduser[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'Carduser';
	}


}
