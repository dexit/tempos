<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<table class="planning">
	<thead>
		<tr>
			<th class="empty"></th>
			<th class="hide"></th>
			<?php for ($day = 0; $day < 7; ++$day): ?>
				<th><?php echo Dayperiod::dayOfWeekToName($day) ?></th>
			<?php endfor; ?>
		</tr>
	</thead>
	<tbody>
			<?php $day_marker = array(0 => null, 1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => null); ?>
			<?php $day_periods_per_day = array(0 => array(), 1 => array(), 2 => array(), 3 => array(), 4 => array(), 5 => array(), 6 => array()); ?>

			<?php foreach ($dayperiod_list as $dayperiod): ?>
				<?php $day_periods_per_day[$dayperiod->getDayOfWeek()][] = $dayperiod; ?>
			<?php endforeach; ?>
			
			<?php for($i = 0; $i < 48; ++$i): ?>

				<tr>
					<?php if ($i % 2 == 0): ?>

						<?php $tst = mktime($i / 2, ($i % 2) * 30); ?>

						<th rowspan="1" class="hour"><?php echo strftime('%H:%M', $tst) ?></th>

					<?php else: ?>

						<th rowspan="1"></th>

					<?php endif; ?>

					<td class="hide"></td>

					<?php for($day = 0; $day < 7; ++$day): ?>
						<?php $tst = mktime($i / 2, ($i % 2) * 30, 0, 6, 8 + $day, 2009); ?>

						<?php $writeDefault = true; ?>

							<?php foreach($day_periods_per_day[$day] as $dayperiod): ?>

								<?php if ($dayperiod->matchTimestamp($tst)): ?>

									<?php $writeDefault = false; ?>

									<?php if ($day_marker[$day] != $dayperiod): ?>
										<?php $day_marker[$day] = $dayperiod; ?>
										<td class="period" rowspan="<?php echo $dayperiod->getDuration() / 30 ?>">
											<div>
												<?php if_javascript(); ?>
													<?php echo link_to(image_tag('/sf/sf_admin/images/cancel.png', array('alt' => __('Delete period'), 'width' => '16px', 'height' => '16px')), 'dayperiod/delete?id='.$dayperiod->getId(), array('class' => 'action', 'method' => 'delete', 'confirm' => __('Are you sure ?'))) ?>
												<?php end_if_javascript(); ?>
												<?php echo $dayperiod->getStart('%H:%M').' - '.$dayperiod->getStop('%H:%M'); ?>
											</div>
											<div>
												<?php echo link_to(image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit period'), 'width' => '16px', 'height' => '16px')), 'dayperiod/edit?id='.$dayperiod->getId(), array('class' => 'action')) ?>
												<span><?php echo __('Edit period') ?></span>
											</div>
											<div>
												<?php echo link_to(image_tag('/sf/sf_admin/images/reset.png', array('alt' => __('Copy every available day'))), 'dayperiod/repeatWeek?id='.$dayperiod->getId(), array('class' => 'action')) ?>
												<span><?php echo __('Repeat every day') ?></span>
											</div>
										</td>
									<?php endif; ?>

								<?php endif; ?>

							<?php endforeach; ?>

						<?php if ($writeDefault): ?>
							<?php $day_marker[$day] = null; ?>
							<td id="<?php echo "book-$day-$i" ?>" class="<?php if ($i % 2 == 0) echo 'hour'; ?> tselectable">
								<?php echo link_to(__('Add a new period'), 'dayperiod/new?roomId='.$room->getId().'&start='.strftime('%H:%M', $tst).'&day='.$day) ?>
							</td>
						<?php endif; ?>

					<?php endfor; ?>
				</tr>

			<?php endfor; ?>
	</tbody>
</table>
