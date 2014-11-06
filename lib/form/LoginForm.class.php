<?php

/**
 * Login form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class LoginForm extends sfForm
{
  public function configure()
  {
		$this->setWidgets(array(
			'username'	=> new sfWidgetFormInput(),
			'password'	=> new sfWidgetFormInputPassword(),
		));

		$this->setValidators(array(
			'username'	=> new sfValidatorString(array('max_length' => 64)),
			'password'	=> new sfValidatorString(array('max_length' => 64)),
		));

		$this->widgetSchema->setLabels(array(
			'username'	=> 'Username',
			'password'	=> 'Password',
		));
  }
}
