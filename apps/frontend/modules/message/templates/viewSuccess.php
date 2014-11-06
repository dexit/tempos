<h1><?php echo __('View message') ?></h1>

<p><?php echo __('Go back to %message_link%.', array('%message_link%' => link_to(__('the messages list'), 'message/index'))) ?></p>

<div class="message">
	<dl>
		<dt><?php echo __('From') ?></dt>
		<dd><?php echo $message->getSender() ?></dd>
		<dt><?php echo __('To') ?></dt>
		<?php if (!is_null($message->getRecipientUser())): ?>
			<dd><?php echo $message->getRecipientUser()->getFullName() ?></dd>
		<?php else: ?>
			<dd class="empty">No recipient</dd>
		<?php endif; ?>
		<dt><?php echo __('At') ?></dt>
		<dd><?php echo $message->getCreatedAt('%c') ?></dd>
		<dt class="subject"><?php echo __('Subject') ?></dt>
		<dd class="subject"><?php echo $message->getSubject() ?></dd>
	</dl>
	<pre><?php echo $message->getText() ?></pre>

	<ul class="buttons">
		<?php if (($message->getSenderId() !== $message->getOwnerId()) && (!is_null($message->getSenderUser()))): ?>
			<li><?php echo button_link_to(Reply, 'message/reply?messageId='.$message->getId()); ?></li>
		<?php endif; ?>

		<li><?php echo button_link_to('Delete', 'message/delete?id='.$message->getId()); ?></li>
	</ul>
</div>
