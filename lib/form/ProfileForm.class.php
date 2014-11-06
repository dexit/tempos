<?php

/**
* Profile form.
*
* @package    tempos
* @subpackage form
* @author     Your name here
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class ProfileForm extends BaseUserForm
{
	public function configure()
	{
		// Remove some fields
		unset($this['login']);
		unset($this['family_name']);
		unset($this['surname']);
		unset($this['card_number']);
		unset($this['password_hash']);
		unset($this['user_has_role_list']);
		unset($this['usergroup_has_user_list']);
		unset($this['usergroup_has_chief_list']);
		unset($this['is_member']);
		unset($this['is_active']);
		unset($this['photograph']);

		// Add password fields
		$this->widgetSchema['current_password'] = new sfWidgetFormInputPassword();
		$this->widgetSchema['password'] = new sfWidgetFormInputPassword();
		$this->widgetSchema['password2'] = new sfWidgetFormInputPassword();

		$this->widgetSchema['address'] = new sfWidgetFormInputGeoComplete();

		// Labels
		$this->widgetSchema->setLabel('password2', 'Please repeat password');

		// Set validators
		$this->validatorSchema['current_password'] =  new sfValidatorString(array('max_length' => 64, 'required' => true));
		$this->validatorSchema['password'] =  new sfValidatorString(array('max_length' => 64, 'min_length' => 3, 'required' => false));
		$this->validatorSchema['password2'] =  new sfValidatorString(array('max_length' => 64, 'min_length' => 3, 'required' => false));
		$this->validatorSchema['email_address'] = new sfValidatorAnd(array(
		$this->validatorSchema['email_address'],
		new sfValidatorEmail(),
		));
		$this->validatorSchema['email_address']->setOption('required', false);

		// Set a propel unique validator
		$this->validatorSchema->setPostValidator(
		new sfValidatorAnd(array(
		new sfValidatorPasswordCheck(array('password_field' => 'current_password'), array()),
		new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password2', array(), array('invalid' => 'The two passwords must match.')),
		))
		);
	}

	protected function doSave($con = null)
	{
		$password = $this->getValue('password');

		if (!empty($password))
		{
			$this->getObject()->setPassword($password);
		}

		$result = parent::doSave($con);

		return $result;
	}
}
