<?php

/**
 * Energyaction form.
 *
 * @package    tempos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class EnergyactionForm extends BaseEnergyactionForm
{
  public function configure()
  {
		$hac_list = array();
		$choices = array();
		$nb_hac = ConfigurationHelper::getParameter(null, 'number_of_home_automation');
		
		// Recherche les différents contrôleurs d'accès physiques
		if ($nb_hac > 0)
		{
			for ($i = 1; $i <= $nb_hac; $i++)
			{
				$hac_list[] = ConfigurationHelper::getParameter(null, 'home_automation_controller'.$i);
			}
		}
		
		$i = 1;
		
		// Recherche les noms données aux différents contrôleurs
		foreach ($hac_list as $hac)
		{
			$choices[] = ConfigurationHelper::getParameter($hac.$i, 'controller_name');
			$i++;
		}
		
		// Widgets
		if (count($choices) > 0)
		{
			$this->widgetSchema['home_automation_controller'] = new sfWidgetFormChoice(array('choices' => $choices, 'expanded' => false));
		}
		
		// Layout
		unset($this['status']);
		$this->widgetSchema['room_has_energyaction_list']->setOption('expanded', true);

		// Validators
		$this->validatorSchema['home_automation_controller'] = new sfValidatorChoice(array('choices' => array_keys($choices), 'required' => false));
		$this->validatorSchema['name'] =  new sfXSSValidatorString(array('max_length' => 64));

		// Labels
		if (count($choices) > 0)
		{
			$this->widgetSchema->setLabel('home_automation_controller', 'Configured Home Automation Controller');
		}
		
		$this->widgetSchema->setLabel('delayUp', 'Up delay (minutes)');
		$this->widgetSchema->setLabel('delayDown', 'Down delay (minutes)');
		$this->widgetSchema->setLabel('identifier', 'Identifier');
		$this->widgetSchema->setLabel('processIdUp', 'Up PID');
		$this->widgetSchema->setLabel('processIdDown', 'Down PID');
		$this->widgetSchema->setLabel('start', 'Start');
		$this->widgetSchema->setLabel('stop', 'Stop');
		$this->widgetSchema->setLabel('room_has_energyaction_list', 'Rooms');

		// Options
		$minutes = array();

		for ($i = 0; $i < 60; $i += 5)
		{
			$minutes[$i] = str_pad($i, 2, '0', STR_PAD_LEFT);
		}

		$this->widgetSchema['start']->setOption('minutes', $minutes);
		$this->widgetSchema['stop']->setOption('minutes', $minutes);

		// Defaults
		$this->setDefault('start', '20:00');
		$this->setDefault('stop', '08:00');

		// Validators
		$this->validatorSchema['delayUp']->setOption('min', 0);
		$this->validatorSchema['delayDown']->setOption('min', 0);

		// Post-validators
		$this->validatorSchema->setPostValidator(
			new sfValidatorAnd(array(
				new sfValidatorPropelUnique(array('model' => 'Energyaction', 'column' => array('name'))),
			))
		);
  }
}
