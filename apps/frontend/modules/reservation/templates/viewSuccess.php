<h1><?php echo __('Reservation for %activity_name% in %room_name%', array('%activity_name%' => $activity->getName(), '%room_name%' => $room->getName())) ?></h1>

<p><?php echo __('Go back to %room_page%.', array('%room_page%' => link_to(__('the room planning page'), 'reservation/index?roomId='.$room->getId()))) ?></p>

<?php include_partial('form', array('form' => $form, 'room' => $room, 'reservation' => $reservation, 'view' => true)) ?>