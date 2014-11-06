<?php

class VirtualPhysicalAccessController extends BasePhysicalAccessController
{
	public function __construct($configuration = null)
	{
		$this->name = 'Virtual';
		$this->publicName = 'Virtual';
		
		if (is_null($configuration))
		{
			$this->defaultValues = array(
				'controller_name'	=> $this->name,
				'status'			=> Reservation::BLOCKED,
				'delay'				=> '5',
			);
		} else
		{
			$this->defaultValues = $configuration;
		}
	}

	protected function sendCommand($person, $room_profile, $begin_date, $end_date)
	{
		// Virtual physical access controller does nothing.
	}

	protected function sendReservationSuccess($reservation)
	{
		// We set the reservation status to what is defined in the configuration.

		$reservation->setStatus($this->getParameter('status'));
		$reservation->save();
	}
}
