<?php 

$activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');

if (is_null($activityItem) || empty($activityItem))
{
	$activityItem = 'Activities';
}

include_partial('home/drawMenuItems', array(
	'items' => array(
		'activity/index' => array(
			'title' => __($activityItem),
			'icon' => 'mi-activities.png',
		),
		'feature/index' => array(
			'title' => __('Features'),
			'icon' => 'mi-features.png',
		),
	),
)); ?>
