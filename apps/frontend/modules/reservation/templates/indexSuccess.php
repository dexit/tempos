<h1><?php echo __('Planning for "%room_name%"', array('%room_name%' => $room)) ?></h1>

<?php if (isset($deleteError) && $deleteError): ?>
	<?php include_partial('tools/messageBox', array('class' => 'error', 'title' => __('Deletion error'), 'msg' => __('Unable to delete the reservation. Operation denied.'), 'showImg' => true)); ?>
<?php endif; ?>

<?php include_partial('reservation/weekPlanning', array(
	'reservation_list' => $reservation_list,
	'room' => $room,
	'activity' => isset($activity) ? $activity : null,
	'user' => isset($user) ? $user : null,
	'usergroup' => isset($usergroup) ? $usergroup : null,
	'date' => $date,
	'person' => $person,
	'realPerson' => $realPerson,
	'is_admin' => $is_admin,
	'weekStart' => $weekStart,
)); ?>

<p>
	<div class="no_print">
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new reservation'), 'reservation/new?roomId='.$room->getId()))) ?>
	</div>
</p>
