<h1><?php echo __('Features'); ?></h1>

<p>
<?php echo format_number_choice('[0]No features in the database.|[1]There is actually one feature in the database.|(1,+Inf]There is actually %count% features in the database.', array('%count%' => $count), $count) ?>
</p>

<?php if ($count > 0): ?>

<table class="list">
  <thead>
    <tr>
      <th>
				<a class="action" href="<?php echo url_for('feature/new') ?>"><?php echo image_tag('/sf/sf_admin/images/add.png', array('alt' => __('add a new feature'), 'width' => '16px', 'height' => '16px')) ?></a>
				<?php echo sort_link('feature', 'index', 'name', __('Name'), $sort_direction, $sort_column) ?>
			</th>
      <th><?php
			$activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');
			if (is_null($activityItem) || empty($activityItem))
			{
				$activityItem = 'Activities';
			}
			echo __($activityItem) ?></th>
			<th><?php echo sort_link('feature', 'index', 'is_exclusive', __('Exclusive'), $sort_direction, $sort_column) ?></th>
      <th>
				<?php echo __('Values') ?>
			</th>
    </tr>
  </thead>
  <tbody>

		<?php $is_even = false; ?>

    <?php foreach ($feature_list as $feature): ?>

		<?php $is_even ^= true; ?>

    <tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
      <td><a href="<?php echo url_for('feature/edit?id='.$feature->getId()) ?>"><?php echo $feature->getName() ?></a></td>
			<td>
				<?php $activities = $feature->getActivities(); ?>
				<?php if (count($activities) > 0): ?>
					<ul class="inline">
						<?php foreach($activities as $activity): ?>
							<li><?php echo color_dot($activity->getColor()).$activity->getName() ?></li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<span class="empty"><?php echo __('No entries') ?></span>
				<?php endif; ?>
			</td>
			<td><?php echo $feature->getIsExclusive() ? __('Yes') : __('No') ?></td>
			<td>
				<?php $values = $feature->getFeaturevalues(); ?>
				<div class="actions">
					<?php if (count($values) > 0): ?>
						<a href="<?php echo url_for('featurevalue/index?featureId='.$feature->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit values'), 'width' => '16px', 'height' => '16px')) ?></a>
					<?php endif; ?>
					<a href="<?php echo url_for('featurevalue/new?featureId='.$feature->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/add.png', array('alt' => __('Add value'), 'width' => '16px', 'height' => '16px')) ?></a>
				</div>
				<?php if (count($values) > 0): ?>
					<ul class="inline">
						<?php foreach($values as $value): ?>
							<li><?php echo $value->getValue() ?></li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<span class="empty"><?php echo __('No values') ?></span>
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
	'url'			=> 'feature/index',
)); ?>

<?php endif; ?>

<p>
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new feature'), 'feature/new'))) ?>
</p>
