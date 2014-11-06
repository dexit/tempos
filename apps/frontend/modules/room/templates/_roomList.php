<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Status change functions disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<table class="list">
    <thead>
    <tr>
        <th>
        <?php if (isset($add_links) && $add_links): ?>
            <a class="action" href="<?php echo url_for('room/new') ?>"><?php echo image_tag('/sf/sf_admin/images/add.png', array('alt' => __('add a new room'))) ?></a>
        <?php endif; ?>
        <?php echo sort_link('room', 'index', 'name', __('Name'), $sort_direction, $sort_column) ?>
        </th>
        <th>
        <?php
            $activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');
            if (is_null($activityItem) || empty($activityItem))
            {
                $activityItem = 'Activities';
            }
            echo __($activityItem);
        ?>
        </th>
        <th><?php echo sort_link('room', 'index', 'capacity', __('Capacity'), $sort_direction, $sort_column) ?></th>
        <th><?php echo __('Features') ?></th>
        <th><?php echo __('Physical access') ?></th>
        <th><?php echo __('Opening time') ?></th>
        <th><?php echo __('Closing time') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php $is_even = false; ?>
    <?php foreach ($room_list as $room): ?>

		<?php $is_even ^= true; ?>

        <tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
            <td>
				<a class="<?php echo ($room->getIsActive()) ? '' : 'warning' ?>" href="<?php echo url_for('room/edit?id='.$room->getId()) ?>"><?php echo $room->getName() ?></a>
				<?php if (!$room->getIsActive()): ?>
					(<?php echo link_to(__('activate'), 'room/activate?id='.$room->getId()) ?>)
				<?php endif; ?>
			</td>
			<td>
				<?php $activities = $room->getActivities(); ?>
				<?php if (count($activities) > 0): ?>
					<ul class="inline">
						<?php foreach($activities as $activity): ?>
							<li>
								<?php echo color_dot($activity->getColor()) ?>
								<span class="<?php echo $activity->isCapacitySuitable($room->getCapacity()) ? '' : 'warning' ?>">
									<?php echo $activity->getNameAndOccupation($room->getCapacity()) ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<span class="empty"><?php echo __('No entries') ?></span>
				<?php endif; ?>
			</td>
            <td>
				<?php if (!is_null($room->getCapacity())): ?>
					<span class="<?php echo $room->hasCapacityIssue() ? 'warning' : '' ?>"><?php echo $room->getCapacity() ?></span>
				<?php else: ?>
					<span class="empty"><?php echo __('No capacity specified') ?></span>
				<?php endif; ?>
			</td>
            <td>
				<a class="actions" href="<?php echo url_for('room/featuresEdit?id='.$room->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit features'))) ?></a>
				<?php $valuedFeaturesArray = $room->getValuedFeaturesArray(); ?>

				<?php if (count($valuedFeaturesArray) > 0): ?>
					<dl>
						<?php foreach($valuedFeaturesArray as $featureName => $values): ?>
							<dt><?php echo $featureName ?></dt>
							<dd><?php echo $values ?></dd>
						<?php endforeach; ?>
					</dl>
				<?php else: ?>
					<span class="empty"><?php echo __('No feature values') ?></span>
				<?php endif; ?>
			</td>
			<td>
				<a class="actions" href="<?php echo url_for('roomprofile/index?roomId='.$room->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit profiles'))) ?></a>
				<?php $roomprofiles = $room->getRoomprofiles(); ?>

				<?php if (count($roomprofiles) > 0): ?>
					<ul class="inline">
						<?php foreach($roomprofiles as $roomprofile): ?>
							<li><?php echo $roomprofile->getName() ?></li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<span class="warning"><?php echo __('No profiles defined') ?></span>
				<?php endif; ?>
			</td>
            <td>
				<a class="actions" href="<?php echo url_for('dayperiod/index?roomId='.$room->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit opening time'))) ?></a>
				<?php if ($room->hasDayperiods()): ?>
					<span class=""><?php echo __('%hours% hour(s) per week.', array('%hours%' => $room->getOpeningDurationString())) ?></span>
				<?php else: ?>
					<span class="warning info"><?php echo __('No opening time !') ?></span>
				<?php endif; ?>
			</td>
            <td>
				<a class="actions" href="<?php echo url_for('closeperiod/index?roomId='.$room->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit closing time'))) ?></a>
				<?php if ($room->hasCloseperiods()): ?>
					<span class="info"><?php echo __('%count% closing time(s).', array('%count%' => $room->countCloseperiods())) ?></span>
				<?php else: ?>
					<span class="info"><?php echo __('No closing time.') ?></span>
				<?php endif; ?>
			</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
