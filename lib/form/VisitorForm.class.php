<?php

/**
* Visitor form.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class VisitorForm extends BaseUserForm
{
	public function configure()
	{
		// Remove some fields
		unset($this['password_hash']);
		unset($this['user_has_subscription_list']);
		unset($this['usergroup_has_user_list']);
		unset($this['usergroup_has_chief_list']);
		unset($this['is_member']);
		unset($this['photograph']);

		// Try to render things a bit more beautiful
		$this->widgetSchema['user_has_role_list']->setOption('expanded', true);
		$this->widgetSchema['address'] = new sfWidgetFormInputGeoComplete();

		// Labels
		$this->widgetSchema->setLabel('login', 'Username');
		$this->widgetSchema->setLabel('user_has_role_list', 'Roles');

		// Set validators
		$this->validatorSchema['login'] =  new sfValidatorString(array('max_length' => 64));
		$this->validatorSchema['email_address'] = new sfValidatorAnd(array(
		$this->validatorSchema['email_address'],
		new sfValidatorEmail(),
		));
		$this->validatorSchema['email_address']->setOption('required', false);

		// Set a propel unique validator (it doesn't seem to work in this case (varchar primary key) but who knows...)
		$this->validatorSchema->setPostValidator(
		new sfValidatorAnd(array(
		new sfValidatorPropelUnique(array('model' => 'User', 'column' => array('login'))),
		new sfValidatorPropelUnique(array('model' => 'User', 'column' => array('card_number'))),
		new sfValidatorPropelUnique(array('model' => 'Card', 'column' => array('card_number'), 'field' => 'card_number')),
		))
		);
	}

	public function disableRoles()
	{
		unset($this['user_has_role_list']);
	}

	protected function doSave($con = null)
	{
		$this->object->setIsMember(true);
		
		return parent::doSave($con);
	}
}
