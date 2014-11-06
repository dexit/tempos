<?php

/**
* User Search form.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class UserSearchForm extends sfForm
{
	public function configure()
	{
		$c = new Criteria();

		// Choices
		$active_choices = array(
		null => sfContext::getInstance()->getI18N()->__('Any'),
		true => sfContext::getInstance()->getI18N()->__('Yes'),
		false => sfContext::getInstance()->getI18N()->__('No'),
		);

		$this->setWidgets(array(
		'login'	=> new sfWidgetFormInput(),
		'family_name'	=> new sfWidgetFormInput(),
		'surname'	=> new sfWidgetFormInput(),
		'usergroupsAsLeader' => new sfWidgetFormPropelChoiceMany(array('model' => 'Usergroup', 'add_empty' => false, 'expanded' => true, 'criteria' => $c)),
		'usergroupsAsMember' => new sfWidgetFormPropelChoiceMany(array('model' => 'Usergroup', 'add_empty' => false, 'expanded' => true, 'criteria' => $c)),
		'activities' => new sfWidgetFormPropelChoiceMany(array('model' => 'Activity', 'add_empty' => false, 'expanded' => true)),
		'is_active'	=> new sfWidgetFormChoice(array('choices' => $active_choices)),
		'card_number'	=> new sfWidgetFormInput(),
		'begin_date'	=> new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
		'end_date'		=> new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
		'email_address'	=> new sfWidgetFormInput(),
		'address'	=> new sfWidgetFormInput(),
		'phone_number'	=> new sfWidgetFormInput(),
		));

		$years = range(date('Y') - 128, date('Y'));
		$this->widgetSchema['begin_date']->setOption('years', array_combine($years, $years));
		$this->widgetSchema['end_date']->setOption('years', array_combine($years, $years));

		$minimum_birth_date = '01/01/1910';

		$this->setDefault('is_active', null);
		$this->setDefault('begin_date', $minimum_birth_date);
		$this->setDefault('end_date', date('m/d/Y'));

		$this->setValidators(array(
		'login'                    => new sfValidatorString(array('max_length' => 64, 'required' => false)),
		'family_name'              => new sfValidatorString(array('max_length' => 64, 'required' => false)),
		'surname'                  => new sfValidatorString(array('max_length' => 64, 'required' => false)),
		'usergroupsAsLeader'			 => new sfValidatorPropelChoiceMany(array('model' => 'Usergroup', 'column' => 'id', 'required' => false)),
		'usergroupsAsMember'			 => new sfValidatorPropelChoiceMany(array('model' => 'Usergroup', 'column' => 'id', 'required' => false)),
		'activities'							 => new sfValidatorPropelChoiceMany(array('model' => 'Activity', 'column' => 'id', 'required' => false)),
		'is_active'                => new sfValidatorChoice(array('choices' => array_keys($active_choices), 'required' => false)),
		'card_number'              => new sfValidatorString(array('max_length' => 32, 'required' => false)),
		'begin_date'							 => new sfValidatorDate(array('required' => false)),
		'end_date'								 => new sfValidatorDate(array('required' => false)),
		'email_address'            => new sfValidatorString(array('max_length' => 128, 'required' => false)),
		'address'                  => new sfValidatorString(array('max_length' => 256, 'required' => false)),
		'phone_number'             => new sfValidatorString(array('max_length' => 64, 'required' => false)),
		));

		$activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');
		if (is_null($activityItem) || empty($activityItem))
		{
			$activityItem = 'Activities';
		}
		$this->widgetSchema->setLabels(array(
		'login'	=> 'Username',
		'family_name'	=> 'Family name',
		'surname'	=> 'Surname',
		'usergroupsAsLeader'	=> 'Groups as leader',
		'usergroupsAsMember'	=> 'Groups as member',
		'activities'	=> $activityItem,
		'is_active'	=> 'Is active',
		'card_number'	=> 'Card number',
		'begin_date'	=> 'Minimum birth date',
		'end_date'	=> 'Maximum birth date',
		'email_address'	=> 'Email address',
		'address'	=> 'Address',
		'phone_number'	=> 'Phone number',
		));

		$this->widgetSchema->setNameFormat('userSearch[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		// Post validators
		$this->validatorSchema->setPostValidator(
		new sfValidatorSchemaCompare('end_date', sfValidatorSchemaCompare::GREATER_THAN_EQUAL, 'begin_date', array(),
		array(
		'invalid' => 'The stop date must be after the start date.',
		)
		)
		);
	}

	public function setIsActive($is_active)
	{
		$this->setDefault('is_active', $is_active);

		$this->widgetSchema['is_active'] = new sfWidgetFormInputHidden();
	}

	public function setCardNumber($card_number)
	{
		$this->setDefault('card_number', $card_number);

		$this->widgetSchema['card_number'] = new sfWidgetFormInputHidden();
	}

	public function setUsergroupsAsLeader($usergroups)
	{
		$this->setDefault('usergroupsAsLeader', $usergroups);

		$this->widgetSchema['usergroupsAsLeader'] = new sfWidgetFormInputHidden();
	}

	public function setUsergroupsAsMember($usergroups)
	{
		$this->setDefault('usergroupsAsMember', $usergroups);

		$this->widgetSchema['usergroupsAsMember'] = new sfWidgetFormInputHidden();
	}

	public function setActivities($activities)
	{
		$this->setDefault('activities', $activities);

		$this->widgetSchema['activities'] = new sfWidgetFormInputHidden();
	}
}
