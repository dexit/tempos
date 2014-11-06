<?php $uri = 'home/ganttIndex'.'?activityId='.$activity->getId(); ?>

<h2 class="dayNo">
	<?php echo link_to('< '.__('Previous day'), $uri.'&date='.date('Y-m-d', strtotime(date('Y-m-d', $date).'-1 day'))) ?>
	<?php echo link_to(__('Next day').' >', $uri.'&date='.date('Y-m-d', strtotime(date('Y-m-d', $date).'+1 day'))) ?>
	
	<?php echo __('%day_name%', array('%day_name%' => strftime('%A - %Y-%m-%d', $date))) ?>
	<?php echo display_planning_calendar($uri); ?>
	<span class="print_only">
		<span class="complete no_border"> <?php echo __('Unvailable').' (X), ' ?> </span>
		<span class="occupied no_border"> <?php echo __('Available').' (C), ' ?> </span>
		<span class="free no_border"> <?php echo __('Free').' (O), ' ?> </span>
		<span class="past no_border"> <?php echo __('Past').' (~), ' ?> </span>
		<span class="toofar no_border"> <?php echo __('Currently unvailable'). ' (~)' ?> </span>
	</span>
</h2>

<table class="gantt color planning">
	<?php $startIndex = $availability['startIndex']; ?>
	<?php $stopIndex = $availability['stopIndex']; ?>
	<thead>
		<tr>
			<th class="first"><?php echo __('Rooms') ?></th>
			<?php for ($i = $startIndex; $i < $stopIndex; ++$i): ?>
				<?php $tst = $availability['timestamps'][$i]; ?>
				<?php if ($i % 2 == 0): ?>
					<th><?php echo date('H', $tst) ?></th>
				<?php else: ?>
					<th class="half"><?php echo date('i', $tst) ?></th>
				<?php endif; ?>
			<?php endfor; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($availability as $room_id => $avail): ?>
			<?php if (!is_numeric($room_id)) continue; ?>
			<tr>
				<th><?php echo link_to($avail['room']->getName(), 'reservation/index?roomId='.$room_id) ?></th>
				<?php for ($i = $startIndex; $i < $stopIndex; ++$i): ?>
					<?php echo availability_day_cell("book-$room_id-$i", $avail[$i], $activity->getId(),
						array(
							RoomPeer::COMPLETE => __('Complete'),
							RoomPeer::OCCUPIED => __('Other reservation'),
							RoomPeer::FREE => __('Add a reservation'),
							RoomPeer::PAST => __('Past'),
							RoomPeer::TOOFAR => __('Too far in the future'),
						), false
					);
					?>
	                                <?php echo availability_day_cell(null, $avail[$i], $activity->getId(),
                                                array(
                                                        RoomPeer::COMPLETE => __('Complete'),
                                                        RoomPeer::OCCUPIED => __('Other reservation'),
                                                        RoomPeer::FREE => __('Add a reservation'),
                                                        RoomPeer::PAST => __('Past'),
                                                        RoomPeer::TOOFAR => __('Too far in the future'),
                                                ), true
                                        );
                                        ?>
				<?php endfor; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<div class="legend">
	<h3><?php echo __('Legend') ?></h3>
        <dl>
		<?php
		echo '<div class="no_print">'. availability_legend_item(RoomPeer::COMPLETE, __('Complete')).'</div>';
		echo '<div class="no_print">'.availability_legend_item(RoomPeer::FREE, __('Empty')).'</div>';
               	echo '<div class="no_print">'.availability_legend_item(RoomPeer::PAST, __('In the past')).'</div>';
               	echo '<div class="no_print">'.availability_legend_item(RoomPeer::TOOFAR, __('Too far in the future')).'</div>'; ?>
	</dl>
</div>
