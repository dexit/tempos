<h1><?php echo __('Energy actions list') ?></h1>

<?php if ($count > 0): ?>

<p><?php echo __('Here is the list of all energy actions:') ?></p>

<table class="list">
  <thead>
    <tr>
      <th>
				<?php echo sort_link('energyaction', 'index', 'status', __('Status'), $sort_direction, $sort_column) ?>
			</th>
      <th>
				<a class="action" href="<?php echo url_for('energyaction/new') ?>"><?php echo image_tag('/sf/sf_admin/images/add.png', array('alt' => __('add a new action'), 'width' => '16px', 'height' => '16px')) ?></a>
				<?php echo sort_link('energyaction', 'index', 'name', __('Name'), $sort_direction, $sort_column) ?>
			</th>
      <th><?php echo __('Up delay (minutes)') ?></th>
      <th><?php echo __('Down delay (minutes)') ?></th>
      <th><?php echo __('Identifier') ?></th>
      <th><?php echo __('Up PID') ?></th>
      <th><?php echo __('Down PID') ?></th>
      <th>
				<?php echo sort_link('energyaction', 'index', 'start', __('Start'), $sort_direction, $sort_column) ?>
			</th>
      <th>
				<?php echo sort_link('energyaction', 'index', 'stop', __('Stop'), $sort_direction, $sort_column) ?>
			</th>
    </tr>
  </thead>
  <tbody>
		<?php $is_even = false; ?>

    <?php foreach ($energyaction_list as $energyaction): ?>

		<?php $is_even ^= true; ?>

    <tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
			<td class="status">
				<?php if ($energyaction->getStatus()): ?>
					<?php echo image_tag('light-bulb-on', array('alt' => __('On'))) ?>
				<?php else: ?>
					<?php echo image_tag('light-bulb-off', array('alt' => __('Off'))) ?>
				<?php endif; ?>
			</td>
      <td><a href="<?php echo url_for('energyaction/edit?id='.$energyaction->getId()) ?>"><?php echo $energyaction->getName() ?></a></td>
      <td><?php echo __('%count% minute(s)', array('%count%' => $energyaction->getDelayup())) ?></td>
      <td><?php echo __('%count% minute(s)', array('%count%' => $energyaction->getDelaydown())) ?></td>
      <td>
				<?php if (is_null($energyaction->getIdentifier())): ?>
					<span class="empty"><?php echo __('No identifier') ?></span>
				<?php else: ?>
					<?php echo $energyaction->getIdentifier() ?>
				<?php endif; ?>
			</td>
      <td>
				<?php if (is_null($energyaction->getProcessidup())): ?>
					<span class="empty"><?php echo __('No PID') ?></span>
				<?php else: ?>
					<?php echo $energyaction->getProcessidup() ?>
				<?php endif; ?>
			</td>
      <td>
				<?php if (is_null($energyaction->getProcessiddown())): ?>
					<span class="empty"><?php echo __('No PID') ?></span>
				<?php else: ?>
					<?php echo $energyaction->getProcessiddown() ?>
				<?php endif; ?>
			</td>
			<td><?php echo $energyaction->getStart('H:i'); ?></td>
			<td><?php echo $energyaction->getStop('H:i'); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include_partial('tools/navigator', array(
	'offset'	=> $offset,
	'limit'		=> $limit,
	'step'		=> $step,
	'count'		=> $count,
	'url'			=> 'energyaction/index',
)); ?>

<?php endif; ?>

<p>
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new action'), 'energyaction/new'))) ?>
</p>
