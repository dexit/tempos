<h1><?php echo __('New reason for %activity_name%', array('%activity_name%' => $activity->getName())) ?></h1>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the reason list page'), 'reservationreason/index?activityId='.$activity->getId()))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
