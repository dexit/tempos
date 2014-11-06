<h1><?php echo __('Close periods for %room_name%', array('%room_name%' => $room->getName())) ?></h1>

<p>
<?php echo format_number_choice('[0]No close periods in the database.|[1]There is actually one close period in the database.|(1,+Inf]There is actually %count% close periods in the database.', array('%count%' => $count), $count) ?>
</p>

<p>
	<?php echo __('You may also %room_link%.', array('%room_link%' => link_to(__('go back to the room page'), 'room/index'))) ?>
</p>

<?php if ($count > 0): ?>

<table class="list">
  <thead>
    <tr>
			<th class="small"></th>
			<th><?php echo sort_link('closeperiod', 'index', 'start', __('Start'), $sort_direction, $sort_column, array('roomId' => $room->getId())) ?></th>
			<th><?php echo sort_link('closeperiod', 'index', 'stop', __('Stop'), $sort_direction, $sort_column, array('roomId' => $room->getId())) ?></th>
			<th><?php echo sort_link('closeperiod', 'index', 'reason', __('Reason'), $sort_direction, $sort_column, array('roomId' => $room->getId())) ?></th>
    </tr>
  </thead>
  <tbody>
		<?php $is_even = false; ?>

    <?php foreach ($closeperiod_list as $closeperiod): ?>

		<?php $is_even ^= true; ?>

    <tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
      <td>
				<a href="<?php echo url_for('closeperiod/edit?id='.$closeperiod->getId()) ?>">
					<?php echo image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit'), 'width' => '16px', 'height' => '16px')) ?>
				</a>
			</td>
      <td><?php echo $closeperiod->getStart() ?></td>
      <td><?php echo $closeperiod->getStop() ?></td>
      <td><?php echo $closeperiod->getReason() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include_partial('tools/navigator', array(
	'offset'	=> $offset,
	'limit'		=> $limit,
	'step'		=> $step,
	'count'		=> $count,
	'url'			=> 'closeperiod/index?roomId='.$room->getId(),
)); ?>

<?php endif; ?>

<p>
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new close period'), 'closeperiod/new?roomId='.$room->getId()))) ?>
</p>
