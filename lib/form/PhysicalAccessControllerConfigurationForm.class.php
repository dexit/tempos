<?php

/**
 * PhysicalAccessControllerConfiguration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class PhysicalAccessControllerConfigurationForm extends sfForm
{
	protected $name = null;
	protected $physical_access_controller = null;

	public function __construct($name, $configuration = null)
	{
		if (empty($name))
		{
			throw new InvalidArgumentException('`$name`cannot be empty');
		}

		$this->name = $name;
		$this->physical_access_controller = BasePhysicalAccessController::create($this->name, $configuration);

		parent::__construct();
	}
	
	public function configure()
	{
		$pac = $this->getPhysicalAccessController();
		
		$defaultValues = $pac->getDefaultValues();
		
		$widgets = array();
		$validators = array();
		
		foreach ($defaultValues as $key => $value)
		{
			$widgets[$key] = new sfWidgetFormInput();
			$validators[$key] = new sfValidatorString(array('required' => true));
		}
		
		$this->setWidgets($widgets);
		$this->setValidators($validators);
		
		$this->setDefaults($pac->getConfiguration());
		
		$this->widgetSchema->setNameFormat($pac->getName().'PhysicalAccessConfiguration[%s]');
		
		/* Default values for all physical access controller */
		$this->widgetSchema->setLabels(array(
			'delay' 			=> 'Start reservation X minutes before the reservation date (minutes)',
			'controller_name'	=> 'Identifier name',
		));
	}

	public static function create($name, $configuration)
	{		
		$formname = $name.'PhysicalAccessControllerConfigurationForm';
		
		if (!class_exists($formname))
		{
			throw new InvalidArgumentException(sprintf('Class `%s` doesn\'t exist.', $formname));
		}
		
		return new $formname($name, $configuration);
	}

	public function getPhysicalAccessController()
	{
		return $this->physical_access_controller;
	}
}
