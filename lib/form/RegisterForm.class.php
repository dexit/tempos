<?php

/**
* Register form.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class RegisterForm extends BaseUserForm
{
	public function configure()
	{
		// Remove some fields
		unset($this['id']);
		unset($this['login']);
		unset($this['card_number']);
		unset($this['password_hash']);
		unset($this['user_has_subscription_list']);
		unset($this['user_has_role_list']);
		unset($this['usergroup_has_user_list']);
		unset($this['usergroup_has_chief_list']);
		unset($this['is_member']);
		unset($this['is_active']);
		unset($this['photograph']);

		$this->widgetSchema['captcha'] = new sfWidgetFormInput();
		$this->widgetSchema['address'] = new sfWidgetFormInputGeoComplete();

		// Labels
		$this->widgetSchema->setLabel('captcha', 'Please copy the security code');

		// Set validators
		$this->validatorSchema['captcha'] =  new sfValidatorSfCryptoCaptcha(array('required' => true, 'trim' => true));
		$this->validatorSchema['email_address'] = new sfValidatorAnd(array(
		$this->validatorSchema['email_address'],
		new sfValidatorEmail(),
		));
		$this->validatorSchema['email_address']->setOption('required', true);

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		// Set a propel unique validator (it doesn't seem to work in this case (varchar primary key) but who knows...)
		$this->validatorSchema->setPostValidator(
		new sfValidatorAnd(array(
		new sfValidatorPropelUnique(array('model' => 'User', 'column' => array('login'))),
		))
		);
	}

	protected function doSave($con = null)
	{
		$this->object->setIsMember(false);
		
		$result = parent::doSave($con);

		$this->object->autoSetLogin();
		$this->object->autoCorrectNames();
		$this->object->save();

		return $result;
	}
}
