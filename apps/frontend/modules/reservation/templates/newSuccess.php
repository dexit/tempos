<h1><?php echo __('New reservation for %activity_name% in %room_name%', array('%activity_name%' => $activity->getName(), '%room_name%' => $room->getName())) ?></h1>

<p><?php echo __('Go back to %room_page%.', array('%room_page%' => link_to(__('the room planning page'), 'reservation/index?roomId='.$room->getId()))) ?></p>

<?php if (isset($colliding_reservation) && (!is_null($colliding_reservation))): ?>
	<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Multiple reservation'), 'msg' => __('You already have a reservation at this date: %reservation%', array('%reservation%' => link_to($colliding_reservation->getActivity()->getName(), sprintf('reservation/index?roomId=%d&date=%s', $colliding_reservation->getRoomProfile()->getRoomId(), $colliding_reservation->getDate('Y-m-d'))))), 'showImg' => true)); ?>
<?php endif; ?>

<?php include_partial('form', array('form' => $form, 'room' => $room, 'view' => false)) ?>
