<?php

/**
 * Card login form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class CardLoginForm extends sfForm
{
  public function configure()
  {
		$this->setWidgets(array(
			'card_number'	=> new sfWidgetFormInput(),
			'pincode'	=> new sfWidgetFormInputPassword(),
		));

		$this->setValidators(array(
			'card_number'	=> new sfValidatorString(array('max_length' => 128)),
			'pincode'	=> new sfValidatorString(array('max_length' => 64)),
		));

		$this->widgetSchema->setLabels(array(
			'card_number'	=> 'Card number',
			'pincode'	=> 'Pin code',
		));
  }
}
