<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
* Reservation filter form base class.
*
* @package    tempos
* @subpackage filter
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseReservationFormFilter extends BaseFormFilterPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'RoomProfile_id'       => new sfWidgetFormPropelChoice(array('model' => 'Roomprofile', 'add_empty' => true)),
		'Activity_id'          => new sfWidgetFormPropelChoice(array('model' => 'Activity', 'add_empty' => true)),
		'date'                 => new sfWidgetFormFilterDate(array(
			'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
			'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => false)),
		'duration'             => new sfWidgetFormFilterInput(),
		'is_activated'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
		'ReservationReason_id' => new sfWidgetFormPropelChoice(array('model' => 'Reservationreason', 'add_empty' => true)),
		'comment'              => new sfWidgetFormFilterInput(),
		'UserGroup_id'         => new sfWidgetFormPropelChoice(array('model' => 'Usergroup', 'add_empty' => true)),
		'Card_id'              => new sfWidgetFormPropelChoice(array('model' => 'Card', 'add_empty' => true)),
		'User_id'              => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
		'members_count'        => new sfWidgetFormFilterInput(),
		'guests_count'         => new sfWidgetFormFilterInput(),
		'created_at'           => new sfWidgetFormFilterDate(array(
			'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
			'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => true)),
		'updated_at'           => new sfWidgetFormFilterDate(array(
			'from_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')),
			'to_date' => new sfWidgetFormI18nDate(array('culture' => 'fr', 'month_format' => 'number')), 'with_empty' => true)),
		'status'               => new sfWidgetFormFilterInput(),
		'price'                => new sfWidgetFormFilterInput(),
		'custom_1'             => new sfWidgetFormFilterInput(),
		'custom_2'             => new sfWidgetFormFilterInput(),
		'custom_3'             => new sfWidgetFormFilterInput(),
		'reservation_has_user_list'     => new sfWidgetFormPropelChoiceMany(array('model' => 'User', 'add_empty' => false, 'expanded' => true)),
		));

		$this->setValidators(array(
		'RoomProfile_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Roomprofile', 'column' => 'id')),
		'Activity_id'          => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Activity', 'column' => 'id')),
		'date'                 => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'duration'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'is_activated'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
		'ReservationReason_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Reservationreason', 'column' => 'id')),
		'comment'              => new sfValidatorPass(array('required' => false)),
		'UserGroup_id'         => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Usergroup', 'column' => 'id')),
		'Card_id'              => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Card', 'column' => 'id')),
		'User_id'              => new sfValidatorPropelChoice(array('required' => false, 'model' => 'User', 'column' => 'id')),
		'members_count'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'guests_count'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
		'status'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'price'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
		'status'               => new sfValidatorPass(array('required' => false)),
		'custom_1'             => new sfValidatorPass(array('required' => false)),
		'custom_2'             => new sfValidatorPass(array('required' => false)),
		'custom_3'             => new sfValidatorPass(array('required' => false)),
		'reservation_has_user_list'    => new sfValidatorPropelChoiceMany(array('model' => 'User', 'column' => 'id', 'required' => false)),
		));

		$this->widgetSchema->setNameFormat('reservation_filters[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'Reservation';
	}

	public function getFields()
	{
		return array(
		'id'                   => 'Number',
		'RoomProfile_id'       => 'ForeignKey',
		'Activity_id'          => 'ForeignKey',
		'date'                 => 'Date',
		'duration'             => 'Number',
		'is_activated'         => 'Boolean',
		'ReservationReason_id' => 'ForeignKey',
		'comment'              => 'Text',
		'UserGroup_id'         => 'ForeignKey',
		'Card_id'              => 'ForeignKey',
		'User_id'              => 'ForeignKey',
		'members_count'        => 'Number',
		'guests_count'         => 'Number',
		'created_at'           => 'Date',
		'updated_at'           => 'Date',
		'status'               => 'Number',
		'price'				   => 'Number',
		'custom_1'             => 'Text',
		'custom_2'             => 'Text',
		'custom_3'             => 'Text',
		'reservation_has_user_list'     => 'ManyKey',
		);
	}
}
