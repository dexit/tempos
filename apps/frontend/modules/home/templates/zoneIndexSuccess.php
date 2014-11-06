<h1><?php echo __('Room selection for booking "%activity_name%"', array('%activity_name%' => $activity->getName())) ?></h1>

<?php if (isset($usergroup)): ?>
	<h2><?php echo __('Available zones and rooms for "%user_name%"', array('%user_name%' => $user->getFullName())) ?></h2>
<?php else: ?>
	<h2><?php echo __('Your zones and rooms') ?></h2>
<?php endif; ?>

<p><?php echo __('You can also %room_search_link%.', array('%room_search_link%' => link_to(__('search a room'), 'home/searchRoom?activityId='.$activity->getId()))) ?></p>

<?php if (count($zones) == 0): ?>
	<?php if (isset($usergroup)): ?>
		<p><?php echo __('There is no valid subscription for any zone. Please contact the person responsible for the system.') ?></p>
	<?php else: ?>
		<p><?php echo __('You have no valid subscription for any zone. Please contact the person responsible for the system.') ?></p>
	<?php endif; ?>
<?php else: ?>
	<?php foreach($zones as $zone): ?>
		<?php if ($zone->getDirectRoomsCount($activity->getId(), RoomPeer::getActiveCriteria()) > 0): ?>
			<h3><?php echo $zone->getName() ?></h3>
			<ul class="blocklist">
				<?php foreach($zone->getDirectRooms($activity->getId(), RoomPeer::getActiveCriteria()) as $room): ?>
					<?php $features_array = $room->getValuedFeaturesArray(); ?>

					<?php echo block_item($room->getName(), array(
						color_square($activity->getColor()),
						definition_list(array(
							__('Capacity') => $room->getCapacity(),
							__('Address') => $room->getAddress(),
							)
						),
						definition_list($features_array),
						link_to(__('Select'), 'reservation/index?roomId='.$room->getId())
					)); ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
