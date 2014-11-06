<h1><?php echo __('Book for another person in "%group_name%"', array('%group_name%' => $usergroup->getName())) ?></h1>

<h2><?php echo __('Group members') ?></h2>

<div class="filter" title="<?php echo __('Show/Hide filters') ?>">
	<?php include_partial('user/searchForm', array('form' => $form, 'target' => 'home/usergroupIndex?usergroupId='.$usergroup->getId())); ?>
</div>

<?php if ($filtered): ?>
	<p>
		<?php echo format_number_choice('[0]No users match.|[1]There is one matching user.|(1,+Inf]There is actually %count% matching users.', array('%count%' => $count), $count) ?>
	</p>

	<p>
		<?php echo __('You can also %clear_filter_link%.', array('%clear_filter_link%' => link_to('clear the filter', 'home/usergroupIndex?usergroupId='.$usergroup->getId().'&clear='))) ?>
	</p>

<?php else: ?>
	<p>
		<?php echo format_number_choice('[0]No users in the group.|[1]There is actually one user in the group.|(1,+Inf]There is actually %count% users in the group.', array('%count%' => $count), $count) ?>
	</p>

<?php endif; ?>

<?php if (count($user_list) > 0): ?>

	<p><?php echo __('Please select the person for which you want to book a room:') ?></p>

	<table class="list">
		<thead>
			<tr>
				<th><?php echo sort_link('home', 'usergroupIndex', 'name', __('Name'), $sort_direction, $sort_column, array('usergroupId' => $usergroup->getId())) ?></th>
				<th><?php
					$activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');
					if (is_null($activityItem) || empty($activityItem))
					{
						$activityItem = 'Activities';
					}
				
					echo __($activityItem) ?></th>
			</tr>
		</thead>
		<tbody>

			<?php $is_even = false; ?>

			<?php foreach ($user_list as $user): ?>
			<?php $activities = $user->getActiveSubscriptionsActivities(); ?>
			<?php $activities = $usergroup->filterActivities($activities); ?>

			<?php $is_even ^= true; ?>

			<tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
				<td>
					<span class="<?php echo (!$user->getIsActive()) ? 'warning' : '' ?>">
						<?php if (count($activities) > 0): ?>
							<a class="actions" href="<?php echo url_for('home/userIndex?userId='.$user->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/date.png', array('alt' => __('Add booking'))) ?></a>
							<?php if (!$user->getIsActive()): ?>
								<span class="info"><?php echo __('User account deactivated') ?></span>
							<?php endif; ?>
							<?php echo link_to($user->getFullName(), 'home/userIndex?userId='.$user->getId()); ?>
						<?php else: ?>
							<?php if (!$user->getIsActive()): ?>
								<span class="info"><?php echo __('User account deactivated') ?></span>
							<?php endif; ?>
							<?php echo $user->getFullName(); ?>
						<?php endif; ?>
					</span>
				</td>
				<td>
					<?php if (count($activities) > 0): ?>
						<ul class="inline">
							<?php foreach($activities as $activity): ?>
								<li><?php echo color_dot($activity->getColor()).$activity->getName() ?></li>
							<?php endforeach; ?>
						</ul>
					<?php else: ?>
						<span class="empty warning"><?php echo __('No entries.') ?></span>
					<?php endif; ?>
				</td>
			</tr>
			
			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif; ?>
