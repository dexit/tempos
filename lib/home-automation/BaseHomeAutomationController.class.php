<?php

/**
 * This is the base class for all home automation controller.
 *
 * If you intend to create your own home automation controller class, you only need to override the following functions :
 *
 * - __construct (at least to set the name of your controller)
 * - recvActionStatus
 * - sendActionStatus
 *
 * The new controller class name *MUST* end with HomeAutomationController !
 * 
 * You must also create a configuration form. See lib/form/HomeAutomationConfigurationForm.class.php for more info about that.
 *
 * Have fun ;)
 */
abstract class BaseHomeAutomationController
{
	/**
	 * Instantiate a home automation controller.
	 *
	 * \param $name The home automation controller name.
	 * \param $configuration The configuration.
	 * \return The new instance.
	 */
	public static function create($name = null, $configuration = null)
	{
		if (is_null($name))
		{
			$name = ConfigurationHelper::getParameter(null, 'home_automation_controller1');
		}

		if (empty($name))
		{
			throw new Exception('No home automation controller defined.');
		}

		$restricted = sfConfig::get('app_restricted_home_automation_controllers');

		if (is_null($restricted))
		{
			throw new Exception(sprintf('Usage of "%s" is not allowed !', $name));
		}

		if (!empty($restricted))
		{
			if (!in_array($name, $restricted))
			{
				throw new Exception(sprintf('Usage of "%s" is not allowed !', $name));
			}
		}

		$name .= 'HomeAutomationController';

		if (!class_exists($name))
		{
			throw new InvalidArgumentException(sprintf('Class `%s` doesn\'t exist.', $name));
		}

		return new $name($configuration);
	}
	
	/**
	 * Find the home_automation_controller name with an identifier name.
	 *
	 * \param $name The identifier name.
	 * \return The home_automation_controller name.
	 */
	public static function findHacFromNameIdentifier($cname = null)
	{
		$nb_controller = ConfigurationHelper::getParameter(null, 'number_of_home_automation');
		$controller_name = null;
		$controllers = self::getControllers();
		$results = array();
		
		if (count($controllers) > 0)
		{
			$results['name'] = 'home_automation_controller1';
			$results['id'] = 1;
			
			$exit = false;
			$i = 1;
			
			if (!is_null($cname))
			{
				while ($i <= $nb_controller && !$exit)
				{
					foreach ($controllers as $key => $controller)
					{
						$namespace = ConfigurationHelper::getNamespace($key.$i);
						// print($key.$i);
						// var_dump($namespace);
						// print('CONTROLLER : '.$namespace['controller_name'].'<br />');
						
						if (!empty($namespace))
						{
							if ($namespace['controller_name'] == $cname)
							{
								$controller_name = 'home_automation_controller'.$i;
								$results['name'] = $controller_name;
								$results['id'] = $i;
								// print 'SORTIE : '.$controller_name.'<br />';
								$exit = true;
								break;
							}
						}
					}
					$i++;
				}
			}
		} else
		{
			return null;
		}
		
		return $results;
	}

	/**
	 * Get the list of all controllers.
	 *
	 * \return An array of the controllers name.
	 */
	public static function getControllers()
	{
		$dir = dirname(__FILE__);

		$files = scandir($dir);

		$files = preg_grep('/^[a-zA-Z_][a-zA-Z0-9_]*HomeAutomationController\.class\.php/', $files);

		$names = preg_replace('/^([a-zA-Z_][a-zA-Z0-9_]*)HomeAutomationController\.class\.php/', '$1', $files);

		$results = array();

		foreach ($names as $name)
		{
			if ($name == 'Base')
			{
				continue;
			}

			try
			{
				if ($pa = self::create($name))
				{
					$results[$name] = $pa->getPublicName();
				}
			}
			catch (Exception $ex)
			{
			}
		}

		return $results;
	}

	protected $name = null;
	protected $publicName = null;
	protected $verbose = false;
	protected $updateStatus = true;
	protected $defaultValues = array();

	/**
	 * Get the name of this controller.
	 * \return The name of this controller.
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the public name of this controller.
	 * \return The public name of this controller.
	 */
	public function getPublicName()
	{
		return $this->publicName;
	}

	/**
	 * Get the verbose status of this controller.
	 * \return The verbose status of this controller.
	 */
	public function getVerbose()
	{
		return $this->verbose;
	}

	/**
	 * Set the verbose status of this controller.
	 * \param $value The verbose status of this controller.
	 */
	public function setVerbose($value)
	{
		$this->verbose = $value;
	}

	/**
	 * Get the update status of this controller.
	 * \return The update status of this controller.
	 */
	public function getUpdateStatus()
	{
		return $this->updateStatus;
	}

	/**
	 * Set the update status of this controller.
	 * \param $value The update status of this controller.
	 */
	public function setUpdateStatus($value)
	{
		$this->updateStatus = $value;
	}

	/**
	 * Get the configuration value.
	 *
	 * \param $key The key of the configuration value to get value from.
	 * \param $default The default value.
	 * \return The value if a configuration with the specified name exists, or $default otherwise.
	 */
	public function getParameter($key, $default = null)
	{
		if (is_null($default))
		{
			if (isset($this->defaultValues[$key]))
			{
				$default = $this->defaultValues[$key];
			}
		}

		return ConfigurationHelper::getParameter($this->getName(), $key, $default);
	}

	/**
	 * Get the configuration.
	 *
	 * \return The configuration array.
	 */
	public function getConfiguration()
	{
		$configuration = ConfigurationHelper::getNamespace($this->getName());

		return array_merge($this->defaultValues, $configuration);
	}

	/**
	 * Get the default values.
	 *
	 * \return The default values array.
	 */
	public function getDefaultValues()
	{
		return $this->defaultValues;
	}

	/**
	 * Refreshes the status of an energy action.
	 *
	 * \param $action The energy action.
	 * \return true if the status was refreshed, false otherwise.
	 */
	public function refreshAction($action)
	{
		if (!($action instanceof Energyaction))
		{
			throw new InvalidArgumentException('`$action` must be a type of Energyaction');
		}

		$status = $this->recvActionStatus($action);

		if (!is_null($status))
		{
			if ($status != $action->getStatus())
			{
				$action->setStatus($status);
				$action->save();
				return true;
			}
		}

		return false;
	}

	/**
	 * Changes the status of an energy action.
	 * 
	 * \param $action The energy action.
	 * \param $status The new status.
	 * \param $force Wether to force the status update. Default: false
	 * \return true if the status was changed, false if the status was already set.
	 */
	public function updateAction($action, $status, $force = false)
	{
		if (!($action instanceof Energyaction))
		{
			throw new InvalidArgumentException('`$action` must be a type of Energyaction');
		}

		if ($force || ($action->getStatus() != $status))
		{
			$this->sendActionStatus($action, $status);

			if ($this->getUpdateStatus())
			{
				$action->setStatus($status);
				$action->save();
			}

			return true;
		}

		return false;
	}

	/**
	 * Receives the action status.
	 *
	 * \param $action The energy action.
	 * \return The current action status.
	 */
	protected abstract function recvActionStatus($action);

	/**
	 * Sends the action status.
	 *
	 * \param $action The energy action.
	 * \param $status The new action status.
	 */
	protected abstract function sendActionStatus($action, $status);
}
