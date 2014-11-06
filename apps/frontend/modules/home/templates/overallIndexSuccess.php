<h1><?php echo __('Availability planning for "%activity_name%"', array('%activity_name%' => $activity->getName())) ?></h1>

<div class="filter" title="<?php echo __('Show/Hide filters') ?>">
	<?php include_partial('room/searchForm', array('form' => $form, 'target' => 'home/overallIndex?activityId='.$activity->getId())); ?>
</div>

<?php if ($filtered): ?>
	<p>
		<?php echo __('You can also %clear_filter_link%.', array('%clear_filter_link%' => link_to(__('clear the filter'), 'home/overallIndex?activityId='.$activity->getId().'&clear='.$form->getName()))) ?>
	</p>
<?php endif; ?>

<?php if (count($room_list) > 0): ?>

<?php if ($displayPeriod == 'month'): ?>
	<?php
	//echo __('You can also %display_week_link%.', array('%display_week_link%' => link_to(__('display the week overview'), 'home/overallIndex?activityId='.$activity->getId().'&displayPeriod=week'))) 
	echo link_to(image_tag('cal_week.gif', array('alt' => __('display the week overview'))).'<span> '.__('Display the week overview').'</span>' , 'home/overallIndex?activityId='.$activity->getId().'&displayPeriod=week', array('class' => 'options'));
	?>

	<?php include_partial('home/monthOverview', array(
		'availability' => $availability,
		'date' => $date,
		'activity' => $activity,
	)); ?>
<?php endif; ?>

<?php if ($displayPeriod == 'week'): ?>
	<?php 
	//echo __('You can also %display_week_link%.', array('%display_week_link%' => link_to(__('display the week overview'), 'home/overallIndex?activityId='.$activity->getId().'&displayPeriod=week'))) 
	echo link_to(image_tag('cal_month.gif', array('alt' => __('display the month overview'))).'<span> '.__('Display the month overview').'</span>' , 'home/overallIndex?activityId='.$activity->getId().'&displayPeriod=month', array('class' => 'options'));
	?>

	<?php include_partial('home/weekOverview', array(
		'availability' => $availability,
		'date' => $date,
		'activity' => $activity,
	)); ?>
<?php endif; ?>

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

<p><?php echo __('No room matches the specified criterias.') ?></p>

<?php endif; ?>
