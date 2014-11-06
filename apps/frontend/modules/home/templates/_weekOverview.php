<?php $uri = 'home/overallIndex'.'?displayPeriod=week&activityId='.$activity->getId(); ?>

<h2 class="weekNo">
	<?php echo link_to('< '.__('Previous week'), $uri.'&date='.date('Y-m-d', strtotime(date('Y-m-d', $date).'-1 week'))) ?>
	<?php echo link_to(__('Next week').' >', $uri.'&date='.date('Y-m-d', strtotime(date('Y-m-d', $date).'+1 week'))) ?>

	<?php echo __('Week #%week_number% - %year%', array('%week_number%' => strftime('%V', $date), '%year%' => date('Y', $date))) ?>
	<?php echo display_planning_calendar($uri); ?>
	<span class="print_only">
		<span class="complete no_border"> <?php echo __('Unvailable').' (X), ' ?> </span>
		<span class="occupied no_border"> <?php echo __('Available').' (C), ' ?> </span>
		<span class="free no_border"> <?php echo __('Free').' (O), ' ?> </span>
		<span class="past no_border"> <?php echo __('Past').' (~), ' ?> </span>
		<span class="toofar no_border"> <?php echo __('Currently unvailable'). ' (~)' ?> </span>
	</span>
</h2>

<table class="color planning">
	<thead>
		<tr>
			<th class="empty"></th>
			<th class="hide"></th>
			<?php for ($day = 0; $day < 7; ++$day): ?>
				<th><?php echo __('%week_day% - %date%', array('%week_day%' => Dayperiod::dayOfWeekToShortName($day), '%date%' => strftime('%d/%m', strtotime(date('Y-m-d', $date)." +".($day - Dayperiod::toWeekDay($date) + 1)." day")))) ?></th>
			<?php endfor; ?>
		</tr>
	</thead>
	<tbody>
		<?php $startIndex = $availability['startIndex']; ?>
		<?php $stopIndex = $availability['stopIndex']; ?>
		<?php $day_marker = array(0 => null, 1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => null); ?>

		<?php for($i = $startIndex; $i < $stopIndex; ++$i): ?>

				<tr>
					<?php $tst = mktime($i / 2, ($i % 2) * 30); ?>

					<?php if ($i % 2 == 0): ?>
						
						<?php if ($i != ($stopIndex - 1)): ?>
							<th rowspan="1" class="hour"><?php echo strftime('%H:%M', $tst) ?></th>
						<?php else: ?>
							<th rowspan="1" class="half"><?php echo strftime('%H:%M', $tst) ?></th>
						<?php endif; ?>

					<?php else: ?>

						<?php if ($i == $startIndex): ?>
							<th rowspan="1" class="half"><?php echo strftime('%H:%M', $tst) ?></th>
						<?php else: ?>
							<th rowspan="1"></th>
						<?php endif; ?>

					<?php endif; ?>

					<td class="hide"></td>

					<?php for($day = 0; $day < 7; ++$day): ?>
						<?php echo availability_week_cell($availability[$day][$i], $activity->getId(),
							array(
								RoomPeer::COMPLETE => __('Complete'),
								RoomPeer::OCCUPIED => __('Other reservation'),
								RoomPeer::FREE => __('Add a reservation'),
								RoomPeer::PAST => __('Past'),
								RoomPeer::TOOFAR => __('Too far in the future'),
							)
						);
						?>
					<?php endfor; ?>
				</tr>

			<?php endfor; ?>
	
	</tbody>
</table>

<div class="legend">
	<h3> <?php echo __('Legend'); ?> </h3>
	<dl>
		<?php echo availability_legend_item(RoomPeer::COMPLETE, __('Complete')); ?>
		<?php echo availability_legend_item(RoomPeer::OCCUPIED, __('Partially occupied')); ?>
		<?php echo availability_legend_item(RoomPeer::FREE, __('Empty')); ?>
		<?php echo availability_legend_item(RoomPeer::PAST, __('In the past')); ?>
		<?php echo availability_legend_item(RoomPeer::TOOFAR, __('Too far in the future')); ?>
	</dl>
</div>
