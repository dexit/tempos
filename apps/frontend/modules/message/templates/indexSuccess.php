<h1><?php echo __('My messages') ?></h1>

<p>
	<?php echo format_number_choice('[0]No unread messages.|[1]You have one unread message in your mailbox.|(1,+Inf]You have %unread_count% unread messages in your mailbox.', array('%count%' => $unread_count), $unread_count) ?>
</p>

<?php if (count($message_list) > 0): ?>

<table class="list">
  <thead>
    <tr>
		<th><?php echo sort_link('message', 'index', 'subject', __('Subject'), $sort_direction, $sort_column) ?></th>
		<th><?php echo sort_link('message', 'index', 'sender', __('From'), $sort_direction, $sort_column) ?></th>
		<th><?php echo sort_link('message', 'index', 'recipient', __('To'), $sort_direction, $sort_column) ?></th>
		<th><?php echo sort_link('message', 'index', 'created_at', __('Received/Send at'), $sort_direction, $sort_column) ?></th>
    </tr>
  </thead>
  <tbody>
		<?php $is_even = false; ?>

		<?php foreach ($message_list as $message): ?>

			<?php $is_even ^= true; ?>

			<tr class="<?php if ($message->getWasRead()) { echo 'unactive';} ?> <?php echo $is_even ? 'even' : 'odd'; ?>">
			  <td><a href="<?php echo url_for('message/view?id='.$message->getId()) ?>"><?php echo $message->getSubject() ?></a></td>
			  <td><?php echo $message->getSender() ?></td>
			  <td>
					<?php if (!is_null($message->getRecipientUser())): ?>
						<?php echo $message->getRecipientUser()->getFullName() ?>
					<?php else: ?>
						<span class="empty">No recipient</span>
					<?php endif; ?>
				</td>
			  <td><?php echo $message->getCreatedAt('%c') ?></td>
			</tr>
			<?php endforeach; ?>
  </tbody>
</table>
<?php include_partial('tools/navigator', array(
	'offset'	=> $offset,
	'limit'		=> $limit,
	'step'		=> $step,
	'count'		=> $count,
	'url'			=> 'message/index',
)); ?>


<?php endif; ?>
