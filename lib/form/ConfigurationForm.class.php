<?php

/**
* Configuration form.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
*/
class ConfigurationForm extends sfForm
{
	protected $subforms = array();
	private $max_controllers = 10;
	private $max_home_automation = 10;

	public function configure()
	{
		$physical_access_controllers = BasePhysicalAccessController::getControllers();
		$home_automation_controllers = BaseHomeAutomationController::getControllers();
		
		$this->widgetSchema['number_of_physical_access'] = new sfWidgetFormInput();
		$this->validatorSchema['number_of_physical_access'] = new sfValidatorInteger(array('min' => 0, 'max' => $this->max_controllers, 'required' => true));
		$this->widgetSchema->setLabel('number_of_physical_access', 'Number of physical access controllers');
		
		if (!is_null($this->getOption('nb_pac')))
		{
			$nb_pac = $this->getOption('nb_pac');
		} else
		{
			$nb_pac = ConfigurationHelper::getParameter(null, 'number_of_physical_access');
		}
		
		if ($nb_pac > $this->max_controllers)
		{
			$nb_pac = $this->max_controllers;
		}
		
		if (count($physical_access_controllers) > 0)
		{
			for ($i = 1; $i <= $nb_pac; $i++)
			{
				$this->widgetSchema['physical_access_controller'.$i] = new sfWidgetFormChoice(array('choices' => $physical_access_controllers));
				$this->validatorSchema['physical_access_controller'.$i] = new sfValidatorChoice(array('choices' => array_keys($physical_access_controllers), 'required' => false));
				$this->widgetSchema->setLabel('physical_access_controller'.$i, 'Physical Access Controller');
				
				foreach ($physical_access_controllers as $physical_access_controller => $name)
				{
					$form = PhysicalAccessControllerConfigurationForm::create($physical_access_controller, null);
					$labelName = $form->getPhysicalAccessController()->getName().$i;
					
					$this->embedForm($labelName, $form);
					$this->widgetSchema->setLabel($labelName, $name);
				}
			}
		}
		
		$this->widgetSchema['number_of_home_automation'] = new sfWidgetFormInput();
		$this->validatorSchema['number_of_home_automation'] = new sfValidatorInteger(array('min' => 0, 'max' => $this->max_home_automation, 'required' => true));
		$this->widgetSchema->setLabel('number_of_home_automation', 'Number of home automation controllers');
		
		if (!is_null($this->getOption('nb_hac')))
		{
			$nb_hac = $this->getOption('nb_hac');
		} else
		{
			$nb_hac = ConfigurationHelper::getParameter(null, 'number_of_home_automation');
		}
		
		if ($nb_hac > $this->max_home_automation)
		{
			$nb_hac = $this->max_home_automation;
		}
		
		if (count($home_automation_controllers) > 0)
		{
			for ($i = 1; $i <= $nb_hac; $i++)
			{
				$this->widgetSchema['home_automation_controller'.$i]	= new sfWidgetFormChoice(array('choices' => $home_automation_controllers));
				$this->validatorSchema['home_automation_controller'.$i]	= new sfValidatorChoice(array('choices' => array_keys($home_automation_controllers), 'required' => false));
				$this->widgetSchema->setLabel('home_automation_controller'.$i, 'Home automation controller');
				
				foreach ($home_automation_controllers as $home_automation_controller => $name)
				{
					$form = HomeAutomationControllerConfigurationForm::create($home_automation_controller, null);
					$labelName = $form->getHomeAutomationController()->getName().$i;
					
					$this->embedForm($labelName, $form);
					$this->widgetSchema->setLabel($labelName, $name);
				}
			}
		}

		$this->addSubConfigurationForm('Network', 'Network');
		$this->addSubConfigurationForm('Email', 'Email');
		$this->addSubConfigurationForm('General', 'General');
		$this->addSubConfigurationForm('Rename', 'Rename');
		$this->addSubConfigurationForm('Backup', 'Backup');
		$this->addSubConfigurationForm('Print', 'Print');
		
		$this->setDefault('number_of_physical_access', 1);
		$this->setDefault('number_of_home_automation', 1);
		$this->setDefaults(ConfigurationHelper::get());
		
		$this->widgetSchema->setNameFormat('configuration[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
	}

	protected function addSubConfigurationForm($name, $label, $i = 0)
	{
		$formclass = $name.'ConfigurationForm';
		if ($i > 0)
		{
			$this->subforms[$name] = new $formclass($i);
		} else
		{
			$this->subforms[$name] = new $formclass();
		}
		$this->embedForm($name, $this->subforms[$name]);
		$this->widgetSchema->setLabel($name, $label);
	}

	public function setDefaults($defaults)
	{
		if (is_array($this->defaults) && is_array($defaults))
		{
			$defaults = array_merge($this->defaults, $defaults);
		}

		parent::setDefaults($defaults);
	}

	public function save($filename = null)
	{
		foreach ($this->subforms as $name => $subform)
		{
			if (method_exists($subform, 'filter'))
			{
				$this->values[$name] = $subform->filter($this->values[$name]);
			}
		}

		ConfigurationHelper::set($this->values);
		ConfigurationHelper::save($filename);
	}
	
	public function getMaxControllers()
	{
		return $this->max_controllers;
	}
	
	public function getMaxHomeAutomation()
	{
		return $this->max_home_automation;
	}
}
