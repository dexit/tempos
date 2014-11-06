<h1><?php echo __('Book for %user_name%', array('%user_name%' => $user->getFullName())) ?></h1>

<p><?php echo __('Go back to %group_page%.', array('%group_page%' => link_to(__('the "%group_name%" group page', array('%group_name%' => $usergroup->getName())), 'home/usergroupIndex?usergroupId='.$usergroup->getId()))) ?></p>

<h2><?php echo __('Available entries') ?></h2>

<?php if (count($activities) == 0): ?>
<p><?php echo __('This user does not have any entry.') ?></p>
<?php else: ?>
<ul class="blocklist">
	<?php foreach($activities as $activity): ?>
		<?php echo block_item($activity->getName(), array(
			color_square($activity->getColor()),
			link_to(__('Select'), 'home/overallIndex?activityId='.$activity->getId()),
		)); ?>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
