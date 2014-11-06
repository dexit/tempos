<?php

/**
 * Roomprofile form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class RoomprofileForm extends BaseRoomprofileForm
{
	public function configure()
	{
		$pac_list = array();
		$choices = array();
		
		// Recherche les différents contrôleurs d'accès physiques
		for ($i = 1; $i <= ConfigurationHelper::getParameter(null, 'number_of_physical_access'); $i++)
		{
			$pac_list[] = ConfigurationHelper::getParameter(null, 'physical_access_controller'.$i);
		}
		
		$i = 1;
		
		// Recherche les noms données aux différents contrôleurs
		foreach ($pac_list as $pac)
		{
			$choices[] = ConfigurationHelper::getParameter($pac.$i, 'controller_name');
			$i++;
		}
		
		// Widgets
		if (count($choices) > 0)
		{
			$this->widgetSchema['physical_access_controller'] = new sfWidgetFormChoice(array('choices' => $choices, 'expanded' => false));
		}
		
		// Validators
		$this->validatorSchema['physical_access_controller'] = new sfValidatorChoice(array('choices' => array_keys($choices), 'required' => false));
		$this->validatorSchema['name'] =  new sfXSSValidatorString(array('max_length' => 256));
		$this->validatorSchema['physical_access_id'] =  new sfXSSValidatorString(array('max_length' => 256));

		// Labels
		$this->widgetSchema->setLabel('physical_access_id', 'Physical access identifier');
		
		if (count($choices) > 0)
		{
			$this->widgetSchema->setLabel('physical_access_controller', 'Configured Physical Access Controller');
		}
	}

	public function setDefaultRoom($room)
	{
		$this->getObject()->setRoom($room);
		$this->setDefault('Room_id', $room->getId());
		$this->widgetSchema['Room_id'] = new sfWidgetFormInputHidden();
	}
}
