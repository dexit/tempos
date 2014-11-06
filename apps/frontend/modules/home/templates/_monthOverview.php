<?php $uri = 'home/overallIndex'.'?displayPeriod=month&activityId='.$activity->getId(); ?>

<h2 class="monthNo">
	<?php echo link_to('< '.__('Previous month'), $uri.'&date='.date('Y-m-d', strtotime(date('Y-m-1', $date).'-1 month'))) ?>
	<?php echo link_to(__('Next month').' >', $uri.'&date='.date('Y-m-d', strtotime(date('Y-m-1', $date).'+1 month'))) ?>
	
	<?php echo __('%month_name% - %year%', array('%month_name%' => strftime('%B', $date), '%year%' => date('Y', $date))) ?>
	<?php echo display_planning_calendar($uri); ?>
	<span class="print_only">
		<span class="complete no_border"> <?php echo __('Unvailable').', ' ?> </span>
		<span class="occupied no_border"> <?php echo __('Available').', ' ?> </span>
		<span class="free no_border"> <?php echo __('Free').', ' ?> </span>
		<!-- Concerne les dates dans le passé
		<span class="past no_border"> <?php // echo __('Unvailable').', ' ?> </span>
		-->
		<span class="toofar no_border"> <?php echo __('Currently unvailable') ?> </span>
	</span>
</h2>

<table class="color planning month">
<thead>
<tr>
<?php for ($day = 0; $day < 7; ++$day): ?>
	<th><?php echo __('%week_day%', array('%week_day%' => Dayperiod::dayOfWeekToShortName($day))) ?></th>
<?php endfor; ?>
</tr>
</thead>
<tbody>
<?php $monthStart = ReservationPeer::getMonthStart($date); ?>
<?php $monthStop = ReservationPeer::getMonthStop($date); ?>
<?php $startDayIndex = date('N', $monthStart) - 1; ?>
<?php $tst = $monthStart; ?>

<?php for ($weekNo = 0; $tst < $monthStop; ++$weekNo): ?>
<tr>
	<?php for ($day = 0; $day < 7; ++$day): ?>
		<?php $tst = strtotime(date('Y-m-d', $monthStart).' + '.($day + $weekNo * 7).' day - '.$startDayIndex.' day'); ?>
		<?php if (($tst >= $monthStart) && ($tst < $monthStop)): ?>
		<?php echo availability_month_cell($availability[$day - $startDayIndex + 1 + $weekNo * 7], $activity->getId(),
			array(
					RoomPeer::COMPLETE => __('Complete'),
					RoomPeer::OCCUPIED => __('Other reservation'),
					RoomPeer::FREE => __('Add a reservation'),
					RoomPeer::PAST => __('Past'),
					RoomPeer::TOOFAR => __('Too far in the future'),
				)
			);
			?>
		<?php else: ?>
			<?php echo month_cell(array('empty'), strftime('%#d', $tst), null) ?>
		<?php endif; ?>
	<?php endfor; ?>
</tr>
<?php endfor; ?>
</tbody>
</table>

<div class="legend">
	<h3><?php echo __('Legend') ?></h3>
	<dl>
		<?php echo availability_legend_item(RoomPeer::COMPLETE, __('Unvailable')); ?>
		<?php echo availability_legend_item(RoomPeer::OCCUPIED, __('Available')); ?>
		<?php echo availability_legend_item(RoomPeer::FREE, __('Free')); ?>
		<?php echo availability_legend_item(RoomPeer::PAST, __('Unvailable')); ?>
		<?php echo availability_legend_item(RoomPeer::TOOFAR, __('Currently unvailable')); ?>
	</dl>	
</div>
