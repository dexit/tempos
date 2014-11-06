<h1><?php echo __('Room planning for "%activity_name%"', array('%activity_name%' => $activity->getName())) ?></h1>

<?php if (count($room_list) > 0): ?>

<?php include_partial('home/dayOverview', array(
	'availability' => $availability,
	'date' => $date,
	'activity' => $activity,
)); ?>

<p class="floatspan">
	<?php echo __('%count% room(s) displayed: ', array('%count%' => count($room_list)));
	foreach ($room_list as $room):
		?>
		<span><?php echo link_to($links[$room->getId()], 'reservation/index?roomId='.$room->getId()) ?></span>
		<?php
		if (isset($divs[$room->getId()])):
			if (!empty($divs[$room->getId()])): ?>
				<span class="float"><?php echo $divs[$room->getId()] ?></span>
				<?php
			endif;
		endif;
		echo ($room->getId() != $room_list[count($room_list) - 1]->getId()) ? ',' : '';
	endforeach; ?>
</p>


<?php else: ?>

<p><?php echo __('No rooms displayed.') ?></p>

<?php endif; ?>
