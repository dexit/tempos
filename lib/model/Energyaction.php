<?php

class Energyaction extends BaseEnergyaction
{
	public function getConfiguredControllerName()
	{
		$names = explode(':', $this->getName());
		
		return $names[0];
	}
	
	public function __toString()
	{
		return $this->getName();
	}

	public function getUpDate($tst)
	{
		$delay = $this->getDelayup();

		if ($this->getStatus() == true)
		{
			$delay += $this->getDelaydown();
		}

		$result = strtotime(date('Y-m-d H:i:s', $tst).' - '.$delay.' minute');

		if (!$this->hasDayPeriod())
		{
			$startDate = $this->getStartDate($tst);

			if ($startDate > $result)
			{
				$result = $startDate;
			}
		}

		return $result;
	}

	public function getDownDate($tst)
	{
		$result = $tst;

		if (!$this->hasDayPeriod())
		{
			$stopDate = $this->getStopDate($tst);

			if ($stopDate < $result)
			{
				$result = $stopDate;
			}
		}

		return $result;
	}

	public function getStartDate($tst)
	{
		return strtotime(date(sprintf('Y-m-d %s', $this->getStart('H:i:s')), $tst));
	}

	public function getStopDate($tst)
	{
		return strtotime(date(sprintf('Y-m-d %s', $this->getStop('H:i:s')), $tst).($this->passMidnight() ? ' + 1 day' : ''));
	}

	public function hasDayPeriod()
	{
		return ($this->getStop() == $this->getStart());
	}

	public function passMidnight()
	{
		return (strtotime($this->getStart()) >= strtotime($this->getStop()));
	}

	public function getActivePID($status = null)
	{
		if (is_null($status))
		{
			$status = $this->getStatus();
		}

		if ($status)
		{
			return $this->getProcessidup();
		} else
		{
			return $this->getProcessiddown();
		}
	}
}
