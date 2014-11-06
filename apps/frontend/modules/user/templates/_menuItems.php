<?php include_partial('home/drawMenuItems', array(
	'items' => array(
		'user/index' => array(
			'title' => __('Members'),
			'icon' => 'mi-user.png',
		),
		'usergroup/index' => array(
			'title' => __('Groups'),
			'icon' => 'mi-groups.png',
		),
		'user/indexVisitors' => array(
			'title' => __('Visitors'),
			'icon' => 'mi-users.png',
			'count' => $visitorsCount,
		),
	),
)); ?>
