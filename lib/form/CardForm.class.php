<?php

/**
* Card form.
*
* @package    tempos
* @subpackage form
* @author     Your name here
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class CardForm extends BaseCardForm
{
	public function configure()
	{
		// Remove some fields
		unset($this['owner']);

		// Layout
		$years = range(date('Y') - 100, date('Y'));
		
		$this->widgetSchema['family_name'] = new sfWidgetFormInput();
		$this->widgetSchema['surname'] = new sfWidgetFormInput();
		$this->widgetSchema['birthdate'] = new sfWidgetFormJQueryDate(array(
			'image'  => '/images/calendar.gif',
			'culture'=> 'fr',
			'config' => '{firstDay: 1, changeMonth: true, changeYear: true, yearRange: \'-100:+0\'}',
        	'date_widget' => new sfWidgetFormI18nDate(array(
					'format' => '%day%/%month%/%year%',
					'culture' => 'fr',
					'month_format' => 'number',
					'years' => array_combine($years, $years)))));

		// Set validators
		$this->validatorSchema['pincode'] =  new sfValidatorString(array('max_length' => 64, 'min_length' => 4, 'required' => false));
		$this->validatorSchema['card_number'] =  new sfXSSValidatorString(array('max_length' => 32));
		$this->validatorSchema['family_name'] =  new sfXSSValidatorString(array('max_length' => 64, 'required' => false));
		$this->validatorSchema['surname'] =  new sfXSSValidatorString(array('max_length' => 64, 'required' => false));
		$this->validatorSchema['birthdate'] =  new sfValidatorDate(array('required' => false));

		$carduser = $this->getCarduser();

		$this->setDefault('family_name', $carduser->getFamilyName());
		$this->setDefault('surname', $carduser->getSurname());
		$this->setDefault('birthdate', $carduser->getBirthdate());

		// Post-validators
		$this->validatorSchema->setPostValidator(
		new sfValidatorAnd(array(
		new sfValidatorPropelUnique(array('model' => 'Card', 'column' => array('card_number'))),
		new sfValidatorPropelUnique(array('model' => 'User', 'column' => array('card_number'), 'field' => 'card_number')),
		))
		);
	}

	protected function doSave($con = null)
	{
		$carduser = $this->getCarduser();

		$carduser->setFamilyName($this->getValue('family_name'));
		$carduser->setSurname($this->getValue('surname'));
		$carduser->setBirthdate($this->getValue('birthdate'));

		parent::doSave($con);
	}

	protected function getCarduser()
	{
		$carduser = $this->getObject()->getCarduser();

		if (is_null($carduser))
		{
			$carduser = new Carduser();

			$this->getObject()->setCarduser($carduser);
		}

		return $carduser;
	}
}
