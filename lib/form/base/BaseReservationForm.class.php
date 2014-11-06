<?php

/**
* Reservation form base class.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
*/
class BaseReservationForm extends BaseFormPropel
{
	public function setup()
	{
		$this->setWidgets(array(
		'id'                   => new sfWidgetFormInputHidden(),
		'RoomProfile_id'       => new sfWidgetFormPropelChoice(array('model' => 'Roomprofile', 'add_empty' => false)),
		'Activity_id'          => new sfWidgetFormPropelChoice(array('model' => 'Activity', 'add_empty' => false)),
		'date'                 => new sfWidgetFormI18nDateTime(array('culture' => 'fr')),
		'duration'             => new sfWidgetFormInput(),
		'is_activated'         => new sfWidgetFormInputCheckbox(),
		'ReservationReason_id' => new sfWidgetFormPropelChoice(array('model' => 'Reservationreason', 'add_empty' => true)),
		'comment'              => new sfWidgetFormInput(),
		'UserGroup_id'         => new sfWidgetFormPropelChoice(array('model' => 'Usergroup', 'add_empty' => true)),
		'Card_id'              => new sfWidgetFormPropelChoice(array('model' => 'Card', 'add_empty' => true)),
		'User_id'              => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
		'members_count'        => new sfWidgetFormInput(),
		'guests_count'         => new sfWidgetFormInput(),
		'created_at'           => new sfWidgetFormI18nDateTime(array('culture' => 'fr')),
		'updated_at'           => new sfWidgetFormI18nDateTime(array('culture' => 'fr')),
		'status'               => new sfWidgetFormInput(),
		'price'                => new sfWidgetFormInput(),
		'custom_1'             => new sfWidgetFormInput(),
		'custom_2'             => new sfWidgetFormInput(),
		'custom_3'             => new sfWidgetFormInput(),
		'reservation_has_user_list'     => new sfWidgetFormPropelChoiceMany(array('model' => 'User', 'add_empty' => false, 'expanded' => true)),
		));

		$this->setValidators(array(
		'id'                   => new sfValidatorPropelChoice(array('model' => 'Reservation', 'column' => 'id', 'required' => false)),
		'RoomProfile_id'       => new sfValidatorPropelChoice(array('model' => 'Roomprofile', 'column' => 'id')),
		'Activity_id'          => new sfValidatorPropelChoice(array('model' => 'Activity', 'column' => 'id')),
		'date'                 => new sfValidatorDateTime(),
		'duration'             => new sfValidatorInteger(),
		'is_activated'         => new sfValidatorBoolean(),
		'ReservationReason_id' => new sfValidatorPropelChoice(array('model' => 'Reservationreason', 'column' => 'id', 'required' => false)),
		'comment'              => new sfValidatorString(array('max_length' => 256, 'required' => false)),
		'UserGroup_id'         => new sfValidatorPropelChoice(array('model' => 'Usergroup', 'column' => 'id', 'required' => false)),
		'Card_id'              => new sfValidatorPropelChoice(array('model' => 'Card', 'column' => 'id', 'required' => false)),
		'User_id'              => new sfValidatorPropelChoice(array('model' => 'User', 'column' => 'id', 'required' => false)),
		'members_count'        => new sfValidatorInteger(),
		'guests_count'         => new sfValidatorInteger(),
		'created_at'           => new sfValidatorDateTime(array('required' => false)),
		'updated_at'           => new sfValidatorDateTime(array('required' => false)),
		'status'               => new sfValidatorInteger(),
		'price'                => new sfValidatorInteger(),
		'custom_1'             => new sfValidatorString(array('required' => false)),
		'custom_2'             => new sfValidatorString(array('required' => false)),
		'custom_3'             => new sfValidatorString(array('required' => false)),
		'reservation_has_user_list'     => new sfValidatorPropelChoiceMany(array('model' => 'User', 'column' => 'id', 'required' => false)),
		));

		$this->widgetSchema->setNameFormat('reservation[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		parent::setup();
	}

	public function getModelName()
	{
		return 'Reservation';
	}
	
	protected function doSave($con = null)
	{
		parent::doSave($con);
		
		$this->deleteOldReservationOtherMembers();
		$this->saveReservationOtherMembers($con);
	}

	public function deleteOldReservationOtherMembers()
	{
		$r_id = $this->getObject()->getId();
		if (!is_null($r_id) && !empty($r_id))
		{
			$c = new Criteria();
			$c->add(ReservationOtherMembersPeer::RESERVATION_ID, $r_id);

			$o_members = ReservationOtherMembersPeer::doSelect($c);
			if (!is_null($o_members))
			{
				foreach($o_members as $member)
				{
					$member->delete();
				}
			}
		}
	}

	public function saveReservationOtherMembers($con = null)
	{
		$oneshot_values = $this->getValue('reservation_has_user_list');
		//print('Oneshot : '.$oneshot_values);  

		if (!$this->isValid())
		{
				throw $this->getErrorSchema();
		}
		
		if (!isset($this->widgetSchema['reservation_has_user_list']))
		{
				return false;
		}

		if (!is_null($oneshot_values))
		{
			// For each user checked...
			foreach ($oneshot_values as $value)
			{
				$new_other_members_group = new ReservationOtherMembers();
				// Get the current Reservation ID
				$new_other_members_group->setReservationId($this->getObject()->getId());
				$new_other_members_group->setUserId($value);
				$new_other_members_group->save();
				//print('User id: '.$value.' Reservation id: '.$this->getObject()->getId());
			}
		}
		
		return true;
	}
}
