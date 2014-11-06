<?php

class KNXHomeAutomationController extends BaseHomeAutomationController
{
	protected $logfile = '/tmp/eibnetmux.log';

	protected $eibnetmux = null;

	public function __construct($configuration = null)
	{
		$this->name = 'KNX';
		$this->publicName = 'KNX';
		
		if (is_null($configuration))
		{
			$this->defaultValues = array(
				'controller_name'	=> $this->name,
				'host'				=> '10.33.96.250',
				'service'			=> '3671',
			);
		} else
		{
			$this->defaultValues = $configuration;
		}
	}

	protected function recvActionStatus($action)
	{
		$eibnetmux = $this->getEIBNetMux();
		$knxgroup = $this->getKNXGroup($action);
		$value = $knxgroup->read($eibnetmux);

		return ($value == $action->getActivePID(true));
	}

	protected function sendActionStatus($action, $status)
	{
		$eibnetmux = $this->getEIBNetMux();
		$knxgroup = $this->getKNXGroup($action);
		$value = $action->getActivePID($status);

		if (!is_null($value) && $value != "")
		{
			$knxgroup->write($eibnetmux, $value);
		}
		else
		{
			throw new Exception('Value is empty ! Doing nothing...');
		}
	}

	/* Class specific methods */

	protected function getEIBNetMux()
	{
		if (is_null($this->eibnetmux))
		{
			// FIXME: This is dirty :/ It works but it really sucks. Should i modify eibnetmux to provide a better communication system ? (yes)
			// An elegant way for doing this would be to add a new signal SIGHUP and configuration files.
			system('killall eibnetmux >/dev/null 2>&1');

			system(
				sprintf(
					'/usr/local/bin/eibnetmux %s:%d -d -t -l 4195 -Lfile:%s',
					escapeshellarg($this->getParameter('host')),
					$this->getParameter('service'),
					escapeshellarg($this->logfile)
				)
			);

			sleep(1);

			$this->eibnetmux = new eibnetmux("Tempo's KNX home automation controller", "localhost");
		}

		return $this->eibnetmux;
	}

	protected function getKNXGroup($action)
	{
		$identifiers = split(';', $action->getIdentifier());

		if (count($identifiers) != 2)
		{
			throw new Exception('Invalid identifier');
		} else
		{
			$group = $identifiers[0];
			$eis_type = $identifiers[1];
		}

		$array = preg_grep("|^(\d+)/(\d+)/(\d+)$|", array($group));

		if (count($array) != 1)
		{
			throw new Exception('Unable to parse the KNX group');
		}

		$array = preg_grep("|^(\d+)$|", array($eis_type));

		if (count($array) != 1)
		{
			throw new Exception('Unable to parse the KNX eis type');
		}

		$knxgroup = new KNXgroup($group, $eis_type);

		return $knxgroup;
	}
}
