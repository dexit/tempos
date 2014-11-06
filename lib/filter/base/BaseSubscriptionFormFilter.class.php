<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
* Subscription filter form base class.
*
* @package    tempos
* @subpackage filter
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseSubscriptionFormFilter extends BaseFormFilterPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'Activity_id'      => new sfWidgetFormPropelChoice(array('model' => 'Activity', 'add_empty' => true)),
		'Zone_id'          => new sfWidgetFormPropelChoice(array('model' => 'Zone', 'add_empty' => true)),
		'start'            => new sfWidgetFormFilterDate(array(
			'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
			'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => true)),
		'stop'             => new sfWidgetFormFilterDate(array(
			'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
			'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => true)),
		'credit'           => new sfWidgetFormFilterInput(),
		'is_active'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
		'Card_id'          => new sfWidgetFormPropelChoice(array('model' => 'Card', 'add_empty' => true)),
		'User_id'          => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
		'minimum_delay'    => new sfWidgetFormFilterInput(),
		'maximum_delay'    => new sfWidgetFormFilterInput(),
		'maximum_duration' => new sfWidgetFormFilterInput(),
		'hours_per_week'   => new sfWidgetFormFilterInput(),
		'UserGroup_id'     => new sfWidgetFormPropelChoice(array('model' => 'Usergroup', 'add_empty' => true)),
		'minimum_duration' => new sfWidgetFormFilterInput(),
		));

		$this->setValidators(array(
		'Activity_id'      => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Activity', 'column' => 'id')),
		'Zone_id'          => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Zone', 'column' => 'id')),
		'start'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'stop'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'credit'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'is_active'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
		'Card_id'          => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Card', 'column' => 'id')),
		'User_id'          => new sfValidatorPropelChoice(array('required' => false, 'model' => 'User', 'column' => 'id')),
		'minimum_delay'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'maximum_delay'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'maximum_duration' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'hours_per_week'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'UserGroup_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Usergroup', 'column' => 'id')),
		'minimum_duration' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		));

		$this->widgetSchema->setNameFormat('subscription_filters[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'Subscription';
	}

	public function getFields()
	{
		return array(
		'id'               => 'Number',
		'Activity_id'      => 'ForeignKey',
		'Zone_id'          => 'ForeignKey',
		'start'            => 'Date',
		'stop'             => 'Date',
		'credit'           => 'Number',
		'is_active'        => 'Boolean',
		'Card_id'          => 'ForeignKey',
		'User_id'          => 'ForeignKey',
		'minimum_delay'    => 'Number',
		'maximum_delay'    => 'Number',
		'maximum_duration' => 'Number',
		'hours_per_week'   => 'Number',
		'UserGroup_id'     => 'ForeignKey',
		'minimum_duration' => 'Number',
		);
	}
}
