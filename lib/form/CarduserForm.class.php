<?php

/**
* Carduser form.
*
* @package    tempos
* @subpackage form
* @author     Your name here
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class CarduserForm extends BaseCarduserForm
{
	public function configure()
	{
		unset($this['id']);
		// Set validators
		$this->validatorSchema['family_name'] =  new sfXSSValidatorString(array('max_length' => 64));
		$this->validatorSchema['surname'] =  new sfXSSValidatorString(array('max_length' => 64));
		$this->validatorSchema['birthdate'] =  new sfValidatorDate();

		// Options
		$this->validatorSchema['family_name']->setOption('required', false);
		$this->validatorSchema['surname']->setOption('required', false);
		$this->validatorSchema['birthdate']->setOption('required', false);
	}
}
