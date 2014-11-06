<h1><?php echo __('Reply to message') ?></h1>

<p><?php echo __('Go back to %message_link%.', array('%message_link%' => link_to(__('the original message'), 'message/view?id='.$message->getId()))) ?></p>

<?php if (isset($succeeded)): ?>
	<?php if (!$succeeded): ?>
		<?php include_partial('tools/messageBox', array('class' => 'error', 'title' => __('Send failed'), 'msg' => array(__('The server failed to send the message. This might be a temporary error.'), __('Please contact your administrator is the problem occurs again.')), 'showImg' => true)); ?>
	<?php endif; ?>
<?php endif; ?>

<?php include_partial('message/form', array(
	'form' => $form, 
	'cancel_url' => 'message/view?id='.$message->getId(),
	'submit_url' => 'message/processReply?messageId='.$message->getId(),
)) ?>
