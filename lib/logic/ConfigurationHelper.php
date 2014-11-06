<?php

class ConfigurationHelper
{
	protected static $configuration = null;
	protected static $filename = null;

	public static function getDefaultConfigurationFileName()
	{
		return basename(sfConfig::get('app_default_tempos_configuration_file'));
	}

	public static function getDefaultConfigurationFilePath()
	{
		$filename = sfConfig::get('app_default_tempos_configuration_file');

		return dirname(__FILE__).'/../../'.$filename; 
	}

	public static function load($filename = null)
	{
		if (is_null($filename))
		{
			$filename = sfConfig::get('app_default_tempos_configuration_file');
		}

		chdir(dirname(__FILE__).'/../../');

		$configuration = @file_get_contents($filename);

		if ($configuration === false)
		{
			throw new Exception(sprintf('Unable to open configuration file ("%s")', $filename));
		} else
		{
			$configuration = json_decode($configuration, true);

			if ($configuration === false)
			{
				throw new Exception(sprintf('Unable to parse configuration file ("%s")', $filename));
			}

			if (is_array($configuration))
			{
				self::$configuration = $configuration;
			} else
			{
				self::$configuration = array();
			}
		}

		self::$filename = $filename;
	}
	
	public static function getIdPartOf($name)
	{
		$size = strlen($name);
		$result = null;
		// print 'name: ';
		// var_dump($name);
		// print 'size: ';
		// var_dump($size);
		
		for ($i = ($size - 1); $i >= 0; $i--)
		{
			// print 'part'.$i.': ';	
			$part = substr($name, $i);
			// var_dump($part);
			if (is_numeric($part))
			{
				// print 'part is int<br />';
				$result = intval($part);
			} else
			{
				// print 'break<br />';
				break;
			}
		}
		
		// print 'result: ';
		// var_dump($result);
		return $result;
	}
	
	public static function checkControllersIdentifierName($controller, $name, $nb_controller)
	{
		$controllers = BasePhysicalAccessController::getControllers();
		$results = array();
		
		if (!empty($controllers))
		{
			for ($i = 1; $i <= $nb_controller; $i++)
			{
				foreach ($controllers as $key => $value)
				{
					$c_name = $key.$i;
					// print '<br /> ====> '.$c_name.' : ';
					$i_name = self::getParameter($c_name, 'controller_name');
					// print $i_name.'<br />';
					// print '<br /> SI '.$name.' == '.$i_name.' ET SI '.$c_name.' != '.$controller.'<br /><br />';
					if (($name == $i_name) && ($c_name != $controller))
					{
						// print '<br /> ERREUR : NOM RETROUVE <br/>';
						$results['valid'] = false;
						$results['cname1'] = $c_name;
						$results['cname2'] = $controller;
						return $results;
					}
				}
			}
		}
		
		// print '<br/> OK : NOM DISPO <br/>';
		$results['valid'] = true;
		return $results;
	}
	
	public static function checkAutomationsIdentifierName($controller, $name, $nb_controller)
	{
		$controllers = BaseHomeAutomationController::getControllers();
		$results = array();
		
		if (!empty($controllers))
		{
			for ($i = 1; $i <= $nb_controller; $i++)
			{
				foreach ($controllers as $key => $value)
				{
					$c_name = $key.$i;
					// print '<br /> ====> '.$c_name.' : ';
					$i_name = self::getParameter($c_name, 'controller_name');
					// print $i_name.'<br />';
					// print '<br /> SI '.$name.' == '.$i_name.' ET SI '.$c_name.' != '.$controller.'<br /><br />';
					if (($name == $i_name) && ($c_name != $controller))
					{
						// print '<br /> ERREUR : NOM RETROUVE <br/>';
						$results['valid'] = false;
						$results['cname1'] = $c_name;
						$results['cname2'] = $controller;
						return $results;
					}
				}
			}
		}
		
		// print '<br/> OK : NOM DISPO <br/>';
		$results['valid'] = true;
		return $results;
	}

	public static function save($filename = null)
	{
		if (is_null(self::$configuration))
		{
			throw new Exception('No configuration loaded or set');
		}

		if (is_null($filename))
		{
			if (is_null(self::$filename))
			{
				$filename = sfConfig::get('app_default_tempos_configuration_file');
			} else
			{
				$filename = self::$filename;
			}
		}

		$configuration = json_encode(self::$configuration);

		chdir(dirname(__FILE__).'/../../');

		if (!@file_put_contents($filename, $configuration))
		{
			throw new Exception(sprintf('Unable to write configuration file ("%s")', $filename));
		}
	}

	public static function reload()
	{
		if (is_null(self::$filename))
		{
			throw new Exception('No configuration loaded yet');
		}

		self::load(self::$filename);
	}

	public static function get()
	{
		if (is_null(self::$configuration))
		{
			self::load();
		}

		return self::$configuration;
	}

	public static function set($configuration)
	{
		if (!is_array($configuration))
		{
			throw new InvalidArgumentException('`$configuration` must be an array');
		}

		self::$configuration = $configuration;
	}

	public static function getNamespace($namespace)
	{
		if (!is_string($namespace) && !is_null($namespace))
		{
			throw new InvalidArgumentException('`$namespace` must be a string');
		}

		$configuration = self::get();

		if (is_null($namespace))
		{
			return $configuration;
		}

		if (array_key_exists($namespace, $configuration))
		{
			return $configuration[$namespace];
		} else
		{
			return array();
		}
	}

	public static function setNamespace($namespace, $configuration)
	{
		if (!is_string($namespace))
		{
			throw new InvalidArgumentException('`$namespace` must be a string');
		}

		if (!is_array($configuration))
		{
			throw new InvalidArgumentException('`$configuration` must be an array');
		}

		if (is_null(self::$configuration))
		{
			self::$configuration = array();
		}

		self::$configuration[$namespace] = $configuration;
	}

	public static function hasParameter($namespace, $key)
	{
		if (!is_string($key))
		{
			throw new InvalidArgumentException('`$key` must be a string');
		}

		$configuration = self::getNamespace($namespace);

		return (array_key_exists($key, $configuration));
	}

	public static function getParameter($namespace, $key, $default = null)
	{
		if (!is_string($key))
		{
			throw new InvalidArgumentException('`$key` must be a string');
		}

		$configuration = self::getNamespace($namespace);

		if (array_key_exists($key, $configuration))
		{
			return $configuration[$key];
		} else
		{
			return $default;
		}
	}

	public static function setParameter($namespace, $key, $value)
	{
		if (!is_string($namespace) && !is_null($namespace))
		{
			throw new InvalidArgumentException('`$namespace` must be a string');
		}

		if (!is_string($key))
		{
			throw new InvalidArgumentException('`$key` must be a string');
		}

		if (is_null($namespace))
		{
			if (is_null(self::$configuration))
			{
				self::$configuration = array();
			}

			self::$configuration[$key] = $value;
		} else
		{
			if (is_null(self::$configuration))
			{
				self::$configuration = array();
				self::$configuration[$namespace] = array();
			} else
			{
				if (!array_key_exists($namespace, self::$configuration))
				{
					self::$configuration[$namespace] = array();
				}
			}

			self::$configuration[$namespace][$key] = $value;
		}
	}
	
	public static function getVersion()
	{
		return "v4.0";
	}
}
