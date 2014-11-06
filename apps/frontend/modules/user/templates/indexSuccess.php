<?php if ($filtered): ?>
	<h1><?php echo __('Search member') ?></h1>
<?php else: ?>
	<h1><?php echo __('Members list'); ?></h1>
<?php endif; ?>

<?php if ($filtered): ?>
	<p>
		<?php echo format_number_choice('[0]No users match.|[1]There is one matching user.|(1,+Inf]There is actually %count% matching users.', array('%count%' => $count), $count) ?>
	</p>

	<div class="filter" title="<?php echo __('Show/Hide filters') ?>">
		<?php include_partial('searchForm', array('form' => $form)); ?>
	</div>

	<p>
		<?php echo __('You can also %home_link%.', array('%home_link%' => link_to(__('go back to the user page'), 'user/index?clear='))) ?>
	</p>

	<ul class="buttons">
		<li><?php echo button_link_to('Create a group with these users', 'usergroup/new?users='.id_list($user_list)); ?></li>
		<li><?php echo button_link_to('Add to an existing group', 'usergroup/addUsers?users='.id_list($user_list)); ?></li>
	</ul>
<?php else: ?>
	<p>
		<?php echo format_number_choice('[0]No users in the database.|[1]There is actually one user in the database.|(1,+Inf]There is actually %count% users in the database.', array('%count%' => $count), $count) ?>
	</p>

	<ul class="buttons">
		<li><?php echo button_link_to('Filter user list', 'user/search'); ?></li>
		<?php if ($is_admin): ?>
			<li><?php echo button_link_to('Import from CSV file', 'user/import'); ?></li>
		<?php endif; ?>
	</ul>
<?php endif; ?>

<?php if (count($user_list) > 0): ?>

<table class="list">
  <thead>
    <tr>
			<th><?php echo sort_link('user', 'index', 'name', __('Name'), $sort_direction, $sort_column) ?></th>
			<th><?php echo sort_link('user', 'index', 'login', __('Username'), $sort_direction, $sort_column) ?></th>
			<th><?php echo sort_link('user', 'index', 'card_number', __('Card number'), $sort_direction, $sort_column) ?></th>
      <th><?php echo __('Subscriptions') ?></th>
      <th><?php echo __('Groups') ?></th>
    </tr>
  </thead>
  <tbody>

		<?php $is_even = false; ?>

    <?php foreach ($user_list as $user): ?>

		<?php $is_even ^= true; ?>

    <tr class="<?php if (!$user->getIsActive()) { echo 'unactive';} ?> <?php echo $is_even ? 'even' : 'odd'; ?>">
      <td>
				<div class="actions">
					<?php echo link_to(image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit user'))), 'user/edit?id='.$user->getId()) ?>
				</div>
				<span class="familyName"><?php echo $user->getFamilyName() ?></span>
				<span class="surname"><?php echo $user->getSurname() ?></span>
				<?php if (!$user->getIsActive()): ?>
					(<?php echo link_to(__('activate'), 'user/activate?id='.$user->getId()) ?>)
				<?php endif; ?>
			</td>
      <td class="code"><?php echo $user->getLogin() ?></td>
      <td class="code"><?php echo $user->getCardNumber() ?></td>
      <td>
				<div class="actions">
					<?php echo link_to(image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit subscriptions'))), 'subscription/index?userId='.$user->getId()) ?>
				</div>
				<?php $activities = $user->getActiveSubscriptionsActivities(); ?>
				<?php if (count($activities) > 0): ?>
					<ul class="inline">
						<?php foreach($activities as $activity): ?>
							<li><?php echo color_dot($activity->getColor()).$activity->getName() ?></li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<span class="warning"><?php echo __('No active subscription') ?></span>
				<?php endif; ?>
			</td>
			<td>
				<?php $groups = $user->getGroups(); ?>
				<?php if (count($groups) > 0): ?>
					<ul class="inline">
						<?php foreach($groups as $group): ?>
							<li>
								<?php $is_leader = $group->hasLeader($user->getId()); ?>
								<span class="<?php echo $is_leader ? 'leader' : '' ?>" title="<?php echo $is_leader ? __('Group leader') : '' ?>"><?php echo $group->getName() ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<span class="empty"><?php echo __('No groups') ?></span>
				<?php endif; ?>
			</td>
    </tr>

    <?php endforeach; ?>

  </tbody>
</table>
<?php include_partial('tools/navigator', array(
	'offset'	=> $offset,
	'limit'		=> $limit,
	'step'		=> $step,
	'count'		=> $count,
	'url'			=> 'user/index',
)); ?>

<?php endif; ?>

<?php if (!$filtered): ?>
	<div>
		<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new user'), 'user/new'))) ?>
	</div>
<?php endif; ?>
