<?php

/**
* User form.
*
* @package    tempos
* @subpackage form
* @author     Your name here
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class UserForm extends BaseUserForm
{
	public function configure()
	{
		// Remove some fields
		unset($this['password_hash']);
		unset($this['usergroup_has_user_list']);
		unset($this['usergroup_has_chief_list']);
		unset($this['is_member']);
		unset($this['photograph']);

		// Reset the login field and add a password field
		$this->widgetSchema['password'] = new sfWidgetFormInputPassword();
		$this->widgetSchema['password2'] = new sfWidgetFormInputPassword();

		// Try to render things a bit more beautiful
		$this->widgetSchema['user_has_role_list']->setOption('expanded', true);

		$this->widgetSchema['address'] = new sfWidgetFormInputGeoComplete();

		if ($this->getObject()->isNew())
		{
			$this->widgetSchema['login'] = new sfWidgetFormInputHidden();
		}

		$userHasRoleCriteria = new Criteria();
		$userHasRoleCriteria->addAscendingOrderByColumn(RolePeer::NAME);
		$this->widgetSchema['user_has_role_list']->setOption('criteria', $userHasRoleCriteria);

		// Labels
		$this->widgetSchema->setLabel('login', 'Username');
		$this->widgetSchema->setLabel('password2', 'Please repeat password');
		$this->widgetSchema->setLabel('user_has_role_list', 'Roles');

		// Set validators
		$this->validatorSchema['login'] =  new sfXSSValidatorString(array('max_length' => 64));
		$this->validatorSchema['family_name'] =  new sfXSSValidatorString(array('max_length' => 64));
		$this->validatorSchema['surname'] =  new sfXSSValidatorString(array('max_length' => 64));
		$this->validatorSchema['password'] =  new sfValidatorString(array('max_length' => 64, 'min_length' => 3, 'required' => false));
		$this->validatorSchema['password2'] =  new sfValidatorString(array('max_length' => 64, 'min_length' => 3, 'required' => false));
		$this->validatorSchema['email_address'] = new sfValidatorAnd(array(
		$this->validatorSchema['email_address'],
		new sfValidatorEmail(),
		));
		$this->validatorSchema['email_address']->setOption('required', false);

		if ($this->getObject()->isNew())
		{
			$this->validatorSchema['login']->setOption('required', false);
		}

		// Set a propel unique validator
		$this->validatorSchema->setPostValidator(
		new sfValidatorAnd(array(
		new sfValidatorPropelUnique(array('model' => 'User', 'column' => array('login'))),
		new sfValidatorPropelUnique(array('model' => 'User', 'column' => array('card_number'))),
		new sfValidatorPropelUnique(array('model' => 'Card', 'column' => array('card_number'), 'field' => 'card_number')),
		new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password2', array(), array('invalid' => 'The two passwords must match.'))
		))
		);
	}

	public function disableRoles()
	{
		unset($this['user_has_role_list']);
	}

	protected function doSave($con = null)
	{
		$password = $this->getValue('password');

		if (!empty($password))
		{
			$this->getObject()->setPassword($password);
		}

		$login = $this->getValue('login');
		$is_new = $this->getObject()->isNew();
		$auto_login = (empty($login) && $is_new);

		$result = parent::doSave($con);

		if ($auto_login)
		{
			$this->getObject()->autoSetLogin();
		}

		if ($is_new)
		{
			$this->getObject()->autoCorrectNames();
			$this->getObject()->save();
		}

		return $result;
	}
}
