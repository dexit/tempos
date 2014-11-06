<?php

/**
* Subscription form base class.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseSubscriptionForm extends BaseFormPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'id'               => new sfWidgetFormInputHidden(),
		'Activity_id'      => new sfWidgetFormPropelChoice(array('model' => 'Activity', 'add_empty' => false)),
		'Zone_id'          => new sfWidgetFormPropelChoice(array('model' => 'Zone', 'add_empty' => false)),
		'start'            => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
		'stop'             => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
		'credit'           => new sfWidgetFormInput(),
		'is_active'        => new sfWidgetFormInputCheckbox(),
		'Card_id'          => new sfWidgetFormPropelChoice(array('model' => 'Card', 'add_empty' => true)),
		'User_id'          => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
		'minimum_delay'    => new sfWidgetFormInput(),
		'maximum_delay'    => new sfWidgetFormInput(),
		'maximum_duration' => new sfWidgetFormInput(),
		'hours_per_week'   => new sfWidgetFormInput(),
		'UserGroup_id'     => new sfWidgetFormPropelChoice(array('model' => 'Usergroup', 'add_empty' => true)),
		'minimum_duration' => new sfWidgetFormInput(),
		));

		$this->setValidators(array(
		'id'               => new sfValidatorPropelChoice(array('model' => 'Subscription', 'column' => 'id', 'required' => false)),
		'Activity_id'      => new sfValidatorPropelChoice(array('model' => 'Activity', 'column' => 'id')),
		'Zone_id'          => new sfValidatorPropelChoice(array('model' => 'Zone', 'column' => 'id')),
		'start'            => new sfValidatorDate(array('required' => false)),
		'stop'             => new sfValidatorDate(array('required' => false)),
		'credit'           => new sfValidatorInteger(array('required' => false)),
		'is_active'        => new sfValidatorBoolean(),
		'Card_id'          => new sfValidatorPropelChoice(array('model' => 'Card', 'column' => 'id', 'required' => false)),
		'User_id'          => new sfValidatorPropelChoice(array('model' => 'User', 'column' => 'id', 'required' => false)),
		'minimum_delay'    => new sfValidatorInteger(),
		'maximum_delay'    => new sfValidatorInteger(),
		'maximum_duration' => new sfValidatorInteger(),
		'hours_per_week'   => new sfValidatorInteger(),
		'UserGroup_id'     => new sfValidatorPropelChoice(array('model' => 'Usergroup', 'column' => 'id', 'required' => false)),
		'minimum_duration' => new sfValidatorInteger(),
		));

		$this->widgetSchema->setNameFormat('subscription[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'Subscription';
	}
}
