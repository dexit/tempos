<h1><?php echo __('New profile for %room_name%', array('%room_name%' => $room->getName())) ?></h1>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the profile list page'), 'roomprofile/index?roomId='.$room->getId()))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
