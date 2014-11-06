<?php 
/*include_partial('home/drawMenuItems', array(
	'items' => array(
		'reporting/index' => array(
			'title' => __('Reporting'),
			'icon' => 'mi-reporting.png',
		),
		'occupancy/index' => array(
			'title' => __('Occupancy'),
			'icon' => 'mi-stats.png',
		),
                'reservationdelete/index' => array(
                        'title' => __('Delete reservations'),
                        'icon' => 'mi-trash.png',
                ),
	),
));*/

include_partial('home/drawMenuItems', array('items' => $items));

?>
