<h1><?php echo __('Reporting') ?></h1>

<?php if (count($reservation_list) == 0): ?>
	<?php if ($filtered): ?>
		<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('No reservation match'), 'msg' => __('No reservation match the specified parameters.'), 'showImg' => true)); ?>
	<?php endif; ?>
<?php else: ?>
	<p><?php echo __('%count% reservation(s) match the specified parameters. Click %clear_link% to clear search results.', array('%count%' => count($reservation_list), '%clear_link%' => link_to(__('here'), 'reporting/index?clear='))) ?></p>
<?php endif; ?>

<div class="filter <?php echo count($reservation_list) > 0 ? '' : 'autoopen'?>" title="<?php echo __('Show/Hide filters') ?>">
	<?php include_partial('searchForm', array('form' => $form)); ?>
</div>

<?php if (count($reservation_list) > 0): ?>
	<ul class="buttons">
		<li><?php echo button_link_to('Download this report as a CSV file', 'reporting/index?export=csv'); ?></li>
		<li><?php echo button_link_to('Download this report as a PDF file', 'reporting/index?export=pdf'); ?></li>
	</ul>

	<table class="list">
		<thead>
			<tr>
				<?php if (in_array(0, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'date', __('Date'), $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php if (in_array(1, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'user', __('User'), $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php
					$activityTitle = ConfigurationHelper::getParameter('Rename', 'activity_label');
					if (empty($activityTitle))
					{
						$activityTitle = sfContext::getInstance()->getI18N()->__('Activity');
					}
					if (in_array(2, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'activity', $activityTitle, $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php if (in_array(3, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'duration', __('Duration'), $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php if (in_array(4, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'room', __('Room'), $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php if (in_array(5, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'reservationreason', __('Reason'), $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php if (in_array(6, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'comment', __('Comment'), $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php if (in_array(7, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'usergroup', __('Group'), $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php if (in_array(8, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'members_count', __('Members count'), $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php if (in_array(9, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'guests_count', __('Guests count'), $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php if (in_array(10, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'status', __('Status'), $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php if (in_array(11, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'price', __('Price'), $sort_direction, $sort_column) ?></th><?php endif; ?>
                <?php if (in_array(12, $form->getValue('fields'))): ?><th><?php echo sort_link('reporting', 'index', 'feature', __('Features'), $sort_direction, $sort_column) ?></th><?php endif; ?>
				<?php if (in_array(90, $form->getValue('fields'))): ?>
                    <th>
                    <?php
                        $free_field_1_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_1');
                        echo sort_link('reporting', 'index', 'reservation_custom_field_1', $free_field_1_name, $sort_direction, $sort_column);
                    ?>
                    </th>
                <?php endif; ?>
                <?php if (in_array(91, $form->getValue('fields'))): ?>
                    <th>
                    <?php
                        $free_field_2_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_2');
                        echo sort_link('reporting', 'index', 'reservation_custom_field_2', $free_field_2_name, $sort_direction, $sort_column);
                    ?>
                    </th>
                <?php endif; ?>
                <?php if (in_array(92, $form->getValue('fields'))): ?>
                    <th>
                    <?php
                        $free_field_3_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_3');
                        echo sort_link('reporting', 'index', 'reservation_custom_field_3', $free_field_3_name, $sort_direction, $sort_column);
                    ?>
                    </th>
                <?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<?php $is_even = false; ?>

			<?php foreach ($reservation_list as $reservation): ?>

			<?php $is_even ^= true; ?>

			<tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
				<?php if (in_array(0, $form->getValue('fields'))): ?>
					<td>
						<span class="<?php echo !$reservation->isOld() ? 'warning' : '' ?>"><?php echo $reservation->getDate() ?></span>
					</td>
				<?php endif; ?>
				<?php if (in_array(1, $form->getValue('fields'))): ?>
					<td><?php echo $reservation->getUser()->getFullName() ?></td>
				<?php endif; ?>
				<?php if (in_array(2, $form->getValue('fields'))): ?>
					<td><?php echo color_dot($reservation->getActivity()->getColor()).$reservation->getActivity()->getName() ?></td>
				<?php endif; ?>
				<?php if (in_array(3, $form->getValue('fields'))): ?>
					<td><?php echo __('%duration% minute(s)', array('%duration%' => $reservation->getDuration())) ?></td>
				<?php endif; ?>
				<?php if (in_array(4, $form->getValue('fields'))): ?>
					<td><?php echo $reservation->getRoomprofile()->getRoom()->getName() ?></td>
				<?php endif; ?>
				<?php if (in_array(5, $form->getValue('fields'))): ?>
					<td>
						<?php if (!is_null($reservation->getReservationreason())): ?>
							<?php echo $reservation->getReservationreason()->getName() ?>
						<?php else: ?>
							<span class="empty"><?php echo __('No reason') ?></span>
						<?php endif; ?>
					</td>
				<?php endif; ?>
				<?php if (in_array(6, $form->getValue('fields'))): ?>
					<td><?php echo $reservation->getComment() ?></td>
				<?php endif; ?>
				<?php if (in_array(7, $form->getValue('fields'))): ?>
					<td>
						<?php if (!is_null($reservation->getUsergroup())): ?>
							<?php
								//If there's is a temporary group, we modify the name to display "Perso. (first user, ...)"
								$resa_group_name = null;
								$ug = $reservation->getUsergroup();
								$resa_group_name = $ug->getName();
								echo $resa_group_name ?>
						<?php else: ?>
							<span class="empty"><?php echo __('No group') ?></span>
						<?php endif; ?>
					</td>
				<?php endif; ?>
				<?php if (in_array(8, $form->getValue('fields'))): ?>
					<td><?php echo $reservation->getMembersCount() ?></td>
				<?php endif; ?>
				<?php if (in_array(9, $form->getValue('fields'))): ?>
					<td><?php echo $reservation->getGuestsCount() ?></td>
				<?php endif; ?>
				<?php if (in_array(10, $form->getValue('fields'))): ?>
					<td>
						<?php if ($reservation->isInError()): ?>
							<span class="warning"><?php echo __('Physical Access Controller reports error !') ?></span>
						<?php elseif ($reservation->isForgotten()): ?>
							<span class="warning"><?php echo __('Forgotten !') ?></span>
						<?php elseif ($reservation->isOld()): ?>
							<span class="success"><?php echo __('Ok') ?></span>
						<?php else: ?>
							<span class="empty"><?php echo __('Reservation not ended yet !') ?></span>
						<?php endif; ?>
					</td>
				<?php endif; ?>
				<?php if (in_array(11, $form->getValue('fields'))): ?>
					<td><?php echo $reservation->getPrice() ?></td>
				<?php endif; ?>
                <?php if (in_array(12, $form->getValue('fields'))):
                    $valuedFeaturesArray = $reservation->getRoomprofile()->getRoom()->getValuedFeaturesArray(); ?>
                    <td>
                    <?php 
                        $concat = '';
                        foreach($valuedFeaturesArray as $featureName => $values) {
                            $concat .= $featureName.'='.$values.' | ';
                        }
                        $concat = rtrim($concat, ' | ');
                        echo $concat;
                    ?>
                    </td>
                <?php endif; ?>
				<?php if (in_array(90, $form->getValue('fields'))): ?>
					<td><?php echo $reservation->getCustom1() ?></td>
				<?php endif; ?>
				<?php if (in_array(91, $form->getValue('fields'))): ?>
					<td><?php echo $reservation->getCustom2() ?></td>
				<?php endif; ?>
				<?php if (in_array(92, $form->getValue('fields'))): ?>
					<td><?php echo $reservation->getCustom3() ?></td>
				<?php endif; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php include_partial('tools/navigator', array(
		'offset'	=> $offset,
		'limit'		=> $limit,
		'step'		=> $step,
		'count'		=> $count,
		'url'			=> 'reporting/index',
	)); ?>
<?php endif; ?>
