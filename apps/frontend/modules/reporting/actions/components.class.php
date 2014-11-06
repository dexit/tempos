<?php
class reportingComponents extends sfComponents
{
	public function executeMenuItems()
	{
		$this->items = array();
		
		if ($this->getUser()->hasCredential(array('admin', 'reportingManager'), false))
		{
			$this->items = array(
			'reporting/index' => array(
				'title' => __('Reporting'),
				'icon' => 'mi-reporting.png',
			),
			'occupancy/index' => array(
				'title' => __('Occupancy'),
				'icon' => 'mi-stats.png',
			));
		}

		if ($this->getUser()->hasCredential(array('admin'), false))
		{
			$this->items['reservationdelete/index'] = array(
				'title' => __('Delete reservations'),
				'icon' => 'mi-trash.png'
			);
		}
	}
}
?>
