<h1><?php echo __('Room selection for booking "%activity_name%"', array('%activity_name%' => $activity->getName())) ?></h1>

<?php if (isset($usergroup)): ?>
	<h2><?php echo __('Available rooms for "%user_name%"', array('%user_name%' => $user->getFullName())) ?></h2>
<?php else: ?>
	<h2><?php echo __('Your rooms') ?></h2>
<?php endif; ?>

<p><?php echo __('You can also %zone_index_link%.', array('%zone_index_link%' => link_to(__('go back to the zone page'), 'home/zoneIndex?activityId='.$activity->getId()))) ?></p>

<?php if (count($rooms) == 0): ?>
	<p><?php echo __('No rooms found that match the specified criterias.') ?></p>
<?php else: ?>
	<ul class="blocklist">
		<?php foreach($rooms as $room): ?>
			<?php $features_array = $room->getValuedFeaturesArray(); ?>

			<?php echo block_item($room->getName(), array(
				color_square($activity->getColor(), $activity->getName()),
				definition_list(array(
					__('Capacity') => $room->getCapacity(),
					__('Address') => $room->getAddress(),
					)
				),
				definition_list($features_array),
				link_to(__('Select'), 'reservation/index?roomId='.$room->getId()),
			)); ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
