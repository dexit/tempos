<h1><?php echo __('Groups') ?></h1>

<p>
<?php echo format_number_choice('[0]No groups in the database.|[1]There is actually one group in the database.|(1,+Inf]There is actually %count% groups in the database.', array('%count%' => $count), $count) ?>
</p>

<?php if (count($usergroup_list) > 0): ?>

<table class="list">
  <thead>
    <tr>
			<th><?php echo sort_link('usergroup', 'index', 'name', __('Name'), $sort_direction, $sort_column) ?></th>
      <th><?php 
		$activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');
		if (is_null($activityItem) || empty($activityItem))
		{
			$activityItem = 'Activities';
		}
		echo __($activityItem) ?></th>
      <th><?php echo __('Leader(s)') ?></th>
      <th><?php echo __('Members count') ?></th>
      <th><?php echo __('Actions') ?></th>
    </tr>
  </thead>
  <tbody>

		<?php $is_even = false; ?>

    <?php foreach ($usergroup_list as $usergroup): ?>

		<?php $is_even ^= true; ?>

    <tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
      <td><a href="<?php echo url_for('usergroup/edit?id='.$usergroup->getId()) ?>"><?php echo $usergroup->getName() ?></a></td>
			<td>
				<?php $activities = $usergroup->getActivities(); ?>
				<?php if (count($activities) > 0): ?>
					<ul class="inline">
						<?php foreach($activities as $activity): ?>
							<li>
								<?php echo color_dot($activity->getColor()).$activity->getName() ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<span class="empty"><?php echo __('No activities') ?></span>
				<?php endif; ?>
			</td>
			<td>
				<?php $leaders = $usergroup->getLeaders(); ?>
				<?php if (count($leaders) > 0): ?>
					<ul class="inline">
						<?php foreach($leaders as $leader): ?>
							<li>
								<span class="familyName"><?php echo $leader->getFamilyName() ?></span>
								<span class="surname"><?php echo $leader->getSurname() ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<span class="empty"><?php echo __('No leader(s)') ?></span>
				<?php endif; ?>
			</td>
			<td>
				<span><?php echo $usergroup->getMembersCount() ?></span>
			</td>
			<td>
				<?php echo link_to(__('Edit subscriptions'), 'subscription/usergroupNew?usergroupId='.$usergroup->getId()) ?>
			</td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include_partial('tools/navigator', array(
	'offset'	=> $offset,
	'limit'		=> $limit,
	'step'		=> $step,
	'count'		=> empty($namePattern) ? $count : $searchCount,
	'url'			=> ('usergroup/index'.(!empty($namePattern) ? '?namePattern='.$namePattern : '')),
)); ?>

<?php endif; ?>

<div>
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new group'), 'usergroup/new'))) ?>
</div>
