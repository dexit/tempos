<?php

/**
 * This is the base class for all physical access controller.
 *
 * If you intend to create your own physical access controller class, you only need to override the following functions :
 *
 * - __construct (at least to set the name of your controller)
 * - sendCommand
 * - sendReservationSuccess (change the reservation status accordingly)
 *
 * The new controller class name *MUST* end with PhysicalAccessController !
 *
 * You must also create a configuration form. See lib/form/PhysicalAccessConfigurationForm.class.php for more info about that.
 *
 * Have fun ;)
 */
abstract class BasePhysicalAccessController
{
	/**
	 * Instantiate a physical access controller.
	 *
	 * \param $name The physical access controller name.
	 * \param $configuration The configuration.
	 * \return The new instance.
	 */
	public static function create($name = null, $configuration = null)
	{
		if (is_null($name))
		{
			$name = ConfigurationHelper::getParameter(null, 'physical_access_controller1');
		}

		if (empty($name))
		{
			throw new Exception('No physical access controller defined.');
		}

		$restricted = sfConfig::get('app_restricted_physical_access_controllers');
		
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

		$name .= 'PhysicalAccessController';
		
		if (!class_exists($name))
		{
			throw new InvalidArgumentException(sprintf('Class `%s` doesn\'t exist.', $name));
		}

		return new $name($configuration);
	}

	/**
	 * Find the physical_access_controller name with an identifier name.
	 *
	 * \param $name The identifier name.
	 * \return The physical_access_controller name.
	 */
	public static function findPacFromNameIdentifier($cname = null)
	{
		$nb_controller = ConfigurationHelper::getParameter(null, 'number_of_physical_access');
		$controller_name = null;
		$controllers = self::getControllers();
		$results = array();

		if (count($controllers) > 0)
		{
			$results['name'] = 'physical_access_controller1';
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
								$controller_name = 'physical_access_controller'.$i;
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
		}
		else
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

		$files = preg_grep('/^[a-zA-Z_][a-zA-Z0-9_]*PhysicalAccessController\.class\.php/', $files);

		$names = preg_replace('/^([a-zA-Z_][a-zA-Z0-9_]*)PhysicalAccessController\.class\.php/', '$1', $files);
		
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
				//var_dump($ex);
			}
		}

		return $results;
	}

	protected $name = null;
	protected $publicName = null;
	protected $verbose = false;
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
	 * Get the configuration value.
	 *
	 * \param $key The key of the configuration value to get value from.
	 * \param $default The default value.
	 * \return The value if a configuration with the specified name exists, or $default otherwise.
	 */
	public function getParameter($key, $default = null, $controller_id = null)
	{
		$name = $this->getName();

		if (is_null($default))
		{
			if (isset($this->defaultValues[$key]))
			{
				$default = $this->defaultValues[$key];
			}
		}

		if (!is_null($controller_id))
		{
			$name .= $controller_id;
		}

		return ConfigurationHelper::getParameter($name, $key, $default);
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
	 * Send a reservation.
	 * \param $reservation The reservation to send.
	 * \param $update_status Force reservation status to success even if there was some errors.
	 * \param $persons All persons of the reservation.
	 * \return An array of persons that failed (if any) and the associated exception.
	 */
	public function sendReservation($reservation, $update_status = true, $persons = null)
	{
		if (!($reservation instanceof Reservation))
		{
			throw new InvalidArgumentException('`$reservation` must be a type of Reservation');
		}

		if (is_null($persons))
		{
			$persons = $reservation->getAllPersons();
		}

		$room_profile = $reservation->getRoomprofile();

		$begin_date = strtotime($reservation->getDate());
		$end_date = strtotime($reservation->getStopDate());

		$results = $this->sendMultipleCommands($persons, $room_profile, $begin_date, $end_date);

		if (empty($results))
		{
			if ($update_status)
			{
				$this->sendReservationSuccess($reservation);
			}
		}

		return $results;
	}

	/**
	 * Sends the reservations commands for a list of persons in a given room.
	 *
	 * \param $persons An array of persons.
	 * \param $room_profile The room profile.
	 * \param $begin_date The begin date, timestamp.
	 * \param $end_date The end date, timestamp.
	 * \return An array of persons that failed (if any) and the associated exception.
	 */
	protected function sendMultipleCommands($persons, $room_profile, $begin_date, $end_date)
	{
		$result = array();

		foreach ($persons as $person)
		{
			try
			{
				$this->sendCommand($person, $room_profile, $begin_date, $end_date);
			}
			catch (Exception $ex)
			{
				$result[$person->getId()]['person'] = $person;
				$result[$person->getId()]['exception'] = $ex;
			}
		}

		return $result;
	}

	/**
	 * Sends the reservation command for a person in a given room.
	 *
	 * \param $person An person.
	 * \param $room_profile The room profile.
	 * \param $begin_date The begin date, timestamp.
	 * \param $end_date The end date, timestamp.
	 */
	protected abstract function sendCommand($person, $room_profile, $begin_date, $end_date);

	/**
	 * Updates the reservation status on success.
	 *
	 * \param $reservation The reservation whose status must be updated.
	 */
	protected abstract function sendReservationSuccess($reservation);
}
