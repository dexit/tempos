<h1><?php
$activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');

if (is_null($activityItem) || empty($activityItem))
{
	$activityItem = 'Activities';
}

echo __($activityItem); ?>
</h1>

<p>
<?php echo format_number_choice('[0]No entries in the database.|[1]There is actually one entry in the database.|(1,+Inf]There is actually %count% entries in the database.', array('%count%' => $count), $count) ?>
</p>

<?php if ($count > 0): ?>

<table class="list">
  <thead>
    <tr>
      <th>
				<a class="action" href="<?php echo url_for('activity/new') ?>"><?php echo image_tag('/sf/sf_admin/images/add.png', array('alt' => __('add a new entry'), 'width' => '16px', 'height' => '16px')) ?></a>
				<?php echo sort_link('activity', 'index', 'name', __('Name'), $sort_direction, $sort_column) ?>
			</th>
      <th class="small"><?php echo __('Color') ?></th>
			<th><?php echo sort_link('activity', 'index', 'minimum_occupation', __('Minimum occupation'), $sort_direction, $sort_column) ?></th>
			<th><?php echo sort_link('activity', 'index', 'maximum_occupation', __('Maximum occupation'), $sort_direction, $sort_column) ?></th>
			<th><?php echo sort_link('activity', 'index', 'minimum_delay', __('Minimum delay'), $sort_direction, $sort_column) ?></th>
      <th><?php echo __('Reservation reasons') ?></th>
    </tr>
  </thead>
  <tbody>

		<?php $is_even = false; ?>

    <?php foreach ($activity_list as $activity): ?>

		<?php $is_even ^= true; ?>

    <tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
      <td><a href="<?php echo url_for('activity/edit?id='.$activity->getId()) ?>"><?php echo $activity->getName() ?></a></td>
      <td>
				<?php echo color_square($activity->getColor()) ?>
			</td>
      <td><?php echo $activity->getMinimumOccupation() ?></td>
      <td><?php echo $activity->getMaximumOccupation() ?></td>
      <td><?php echo format_number_choice('[0]No delay|[1]1 minute|(1,+Inf]%delay% minutes', array('%delay%' => $activity->getMinimumDelay()), $activity->getMinimumDelay()) ?></td>
			<td>
				<?php $reasons = $activity->getReservationreasons(); ?>
				<div class="actions">
					<?php if (count($reasons) > 0): ?>
						<a href="<?php echo url_for('reservationreason/index?activityId='.$activity->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit reasons'), 'width' => '16px', 'height' => '16px')) ?></a>
					<?php endif; ?>
					<a href="<?php echo url_for('reservationreason/new?activityId='.$activity->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/add.png', array('alt' => __('Add reason'), 'width' => '16px', 'height' => '16px')) ?></a>
				</div>
				<?php if (count($reasons) > 0): ?>
					<ul class="inline">
						<?php foreach($reasons as $reason): ?>
							<li><?php echo $reason->getName() ?></li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<span class="empty"><?php echo __('No reasons.') ?></span>
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
	'url'			=> 'activity/index',
)); ?>

<?php endif; ?>

<div>
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new entry'), 'activity/new'))) ?>
</div>
