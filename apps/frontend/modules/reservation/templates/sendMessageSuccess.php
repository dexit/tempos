<h1><?php echo __('Send message') ?></h1>

<p><?php echo __('Go back to %room_link%.', array('%room_link%' => link_to(__('the room planning page'), 'reservation/index?roomId='.$room->getId()))) ?></p>

<?php if (isset($succeeded)): ?>
	<?php if (!$succeeded): ?>
		<?php include_partial('tools/messageBox', array('class' => 'error', 'title' => __('Send failed'), 'msg' => array(__('The server failed to send the message. This might be a temporary error.'), __('Please contact your administrator is the problem occurs again.')), 'showImg' => true)); ?>
	<?php endif; ?>
<?php endif; ?>

<?php include_partial('message/form', array(
	'form' => $form, 
	'cancel_url' => 'reservation/index?roomId='.$room->getId(),
	'submit_url' => 'reservation/sendMessageProcess?id='.$reservation->getId(),
)) ?>
