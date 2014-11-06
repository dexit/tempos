<?php

class Reservationreason extends BaseReservationreason
{
	public function __toString()
	{
		return $this->getName();
	}
}
