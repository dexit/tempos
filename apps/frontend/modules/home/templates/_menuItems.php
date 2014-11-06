<?php
$items = array(
	'items' => array(
		'home/index' => array(
			'title' => __('Start page'),
			'icon' => 'mi-home.png',
		),
	)
);
if (!is_null($user))
{
	$items['items'] = array_merge($items['items'], array(
		'home/profile' => array(
			'title' => __('My profile'),
			'icon' => 'mi-profile.png',
		),
		'message/index' => array(
			'title' => __('Messages'),
			'icon' => 'mi-message.png',
			'count' => $newMessagesCount,
		),
	));
}
?>
<?php include_partial('home/drawMenuItems', $items); ?>
