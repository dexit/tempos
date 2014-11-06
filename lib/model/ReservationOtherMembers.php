<?php

class ReservationOtherMembers extends BaseReservationOtherMembers
{
	protected $activities = null;

	public function __wakeup() 
	{
		$this->reload(true);
	}

	public function reload($deep = false, PropelPDO $con = null)
	{
			parent::reload($deep, $con);

			$this->clearCache();
	}

	public function clearCache()
	{
			$this->activities = null;
	}

	public function __toString()
	{
			return $this->getReservationId().' -> '.$this->getUserId();
	}
}
