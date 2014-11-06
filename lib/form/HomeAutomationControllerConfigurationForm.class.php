<?php

/**
 * HomeAutomationControllerConfiguration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class HomeAutomationControllerConfigurationForm extends sfForm
{
	protected $name = null;
	protected $home_automation_controller = null;

	public function __construct($name, $configuration = null)
	{
		if (empty($name))
		{
			throw new InvalidArgumentException('`$name`cannot be empty');
		}

		$this->name = $name;
		$this->home_automation_controller = BaseHomeAutomationController::create($this->name, $configuration);

		parent::__construct();
	}

	public function configure()
	{
		$pac = $this->getHomeAutomationController();

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

		$this->widgetSchema->setNameFormat($pac->getName().'HomeAutomationConfiguration[%s]');
		
		/* Default values for all physical access controller */
		$this->widgetSchema->setLabels(array(
			'controller_name'	=> 'Identifier name',
		));
	}

	public static function create($name, $configuration)
	{
		$formname = $name.'HomeAutomationControllerConfigurationForm';

		if (!class_exists($formname))
		{
			throw new InvalidArgumentException(sprintf('Class `%s` doesn\'t exist.', $formname));
		}

		return new $formname($name, $configuration);
	}

	public function getHomeAutomationController()
	{
		return $this->home_automation_controller;
	}
}
