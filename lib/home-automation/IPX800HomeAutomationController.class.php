<?php

/* http://www.gce-electronics.com/fr/tcp-ip/31-serveur-ip-8-relais-ethernet.html */
/* Sample : http://192.168.0.1/preset.htm?led1=1 */
class IPX800HomeAutomationController extends BaseHomeAutomationController
{
	public function __construct($configuration = null)
	{
		$this->name = 'IPX800';
		$this->publicName = 'IPX800';
		
		if (is_null($configuration))
		{
			$this->defaultValues = array(
				'controller_name' 	=> $this->name,
				'host'				=> '0.0.0.0',
				'service'			=> '80',
				'preset'			=> 'preset.htm',
			);
		} else
		{
			$this->defaultValues = $configuration;
		}
	}

	protected function recvActionStatus($action)
	{
		return null;
	}

	/* $action = led1 | led2 | ... led8 */
	/* $pid = 0 | 1 */
	protected function sendActionStatus($action, $status)
	{
		$pid = $action->getActivePID($status);

		if (!is_null($pid))
		{
			$this->sendWebCommand($action, $pid);
		}
		else
		{
			throw new Exception('PID is empty ! Doing nothing...');
		}
	}

	/* Class specific methods */

	protected function getURL($action, $pid)
	{
		return sprintf(
			'http://%s:%s/%s?%s=%s',
			$this->getParameter('host'),
			$this->getParameter('service'),
			$this->getParameter('preset'),
			$action->getIdentifier(),
			$pid
		);
	}
	
	protected function sendWebCommand($action, $pid)
	{
		//printf(sprintf("Sending URL for action (%s) - pid (%s)\n", $action, $pid));

		$url = $this->getURL($action, $pid);

		//printf(sprintf("URL is (%s)\n", $url));

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$output = curl_exec($ch);

		if ($output === FALSE)
		{
			throw new Exception(sprintf('cURL error: %s', curl_error($ch)));
		}

		$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if (!in_array($return_code, array(200)))
		{
			if ($this->getVerbose())
			{
				throw new Exception(sprintf("HTTP return value: %d\n\n%s", $return_code, $output));
			} else
			{
				throw new Exception(sprintf('HTTP return value: %d', $return_code));
			}
		}
	}
}
