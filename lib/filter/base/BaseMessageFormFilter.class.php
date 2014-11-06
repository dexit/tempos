<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
* Message filter form base class.
*
* @package    tempos
* @subpackage filter
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseMessageFormFilter extends BaseFormFilterPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'subject'      => new sfWidgetFormFilterInput(),
		'text'         => new sfWidgetFormFilterInput(),
		'created_at'   => new sfWidgetFormFilterDate(array(
				'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
				'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => true)),
		'recipient_id' => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
		'sender'       => new sfWidgetFormFilterInput(),
		'sender_id'    => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
		'was_read'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
		'owner_id'     => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
		));

		$this->setValidators(array(
		'subject'      => new sfValidatorPass(array('required' => false)),
		'text'         => new sfValidatorPass(array('required' => false)),
		'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'recipient_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'User', 'column' => 'id')),
		'sender'       => new sfValidatorPass(array('required' => false)),
		'sender_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'User', 'column' => 'id')),
		'was_read'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
		'owner_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'User', 'column' => 'id')),
		));

		$this->widgetSchema->setNameFormat('message_filters[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'Message';
	}

	public function getFields()
	{
		return array(
		'id'           => 'Number',
		'subject'      => 'Text',
		'text'         => 'Text',
		'created_at'   => 'Date',
		'recipient_id' => 'ForeignKey',
		'sender'       => 'Text',
		'sender_id'    => 'ForeignKey',
		'was_read'     => 'Boolean',
		'owner_id'     => 'ForeignKey',
		);
	}
}
