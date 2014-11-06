<?php

class X10HomeAutomationController extends BaseHomeAutomationController
{
	public function __construct($configuration = null)
	{
		$this->name = 'X10';
		$this->publicName = 'X10';
		
		if (is_null($configuration))
		{
			$this->defaultValues = array(
				'controller_name' => $this->name,
				'command' => 'heyu',
				'force-status' => '0',
			);
		} else
		{
			$this->defaultValues = $configuration;
		}
	}

	protected function recvActionStatus($action)
	{
		return ($this->getParameter('force-status') != 0) ? false : null;
	}

	protected function sendActionStatus($action, $status)
	{
		$retval = null;
		$pid = $action->getActivePID($status);
		$command = sprintf('%s %s %s', $this->getParameter('command'), $pid, $action->getIdentifier());
		$lastline = system($command, $retval);

		if ($retval != 0)
		{
			throw new Exception(sprintf('Unable to send the command (%s)', $lastline));
		}
	}
}
