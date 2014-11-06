<?php

class VirtualHAHomeAutomationController extends BaseHomeAutomationController
{
	public function __construct($configuration = null)
	{
		$this->name = 'VirtualHA';
		$this->publicName = 'VirtualHA';
		
		if (is_null($configuration))
		{
			$this->defaultValues = array(
				'controller_name' => $this->name,
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

	protected function sendActionStatus($action, $status)
	{
		// The virtual home automation does nothing
	}
}
