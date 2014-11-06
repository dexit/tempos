<?php if ($filtered): ?>
	<h1><?php echo __('Search room') ?></h1>
<?php else: ?>
	<h1><?php echo __('Room list') ?></h1>
<?php endif; ?>

<?php if ($filtered): ?>
	<p>
		<?php echo format_number_choice('[0]No rooms match.|[1]There is one matching room.|(1,+Inf]There is actually %count% matching rooms.', array('%count%' => $count), $count) ?>
	</p>

	<div class="filter" title="<?php echo __('Show/Hide filters') ?>">
		<?php include_partial('searchForm', array('form' => $form)); ?>
	</div>

	<p>
		<?php echo __('You can also %clear_filter_link%.', array('%clear_filter_link%' => link_to(__('clear the filter'), 'room/index?clear='))) ?>
	</p>
<?php else: ?>
	<p>
		<?php echo format_number_choice('[0]No rooms in the database.|[1]There is actually one room in the database.|(1,+Inf]There is actually %count% rooms in the database.', array('%count%' => $count), $count) ?>
	</p>

	<p>
		<?php echo __('If you want a better view on room organization, you can go to %zone_link%.', array('%zone_link%' => link_to(__('the zone view'), 'zone/index'))) ?>
	</p>

	<p>
		<?php echo __('You can also %search_link%.', array('%search_link%' => link_to(__('search for a specific room'), 'room/search'))) ?>
	</p>
<?php endif; ?>

<?php if (count($room_list) > 0): ?>

<?php include_partial('roomList', array('room_list' => $room_list, 'add_links' => true, 'sort_column' => $sort_column, 'sort_direction' => $sort_direction)); ?>

<?php include_partial('tools/navigator', array(
	'offset'	=> $offset,
	'limit'		=> $limit,
	'step'		=> $step,
	'count'		=> $count,
	'url'			=> 'room/index',
)); ?>

<?php endif; ?>

<?php if (!$filtered): ?>
	<div>
		<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new room'), 'room/new'))) ?>
	</div>
<?php endif; ?>
