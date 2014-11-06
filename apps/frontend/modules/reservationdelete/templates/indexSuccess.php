<h1><?php echo __('Multiple reservations deletion') ?></h1>

<?php if (count($reservation_list) == 0): ?>

<?php 
if ($filtered)
{
	include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('No reservation match'), 'msg' => __('No reservation match the specified parameters.'), 'showImg' => true));
}
?>

<?php include_partial('form', array('form' => $form, 'reservationId' => $reservationId)) ?>

<?php else:
	$beginDate	= date('d/m/Y', strtotime($form->getValue('start_date')));
	$endDate = date('d/m/Y', strtotime($form->getValue('end_date')));

	$beginHour = date('H:i', strtotime($form->getValue('start_hour')));
	$endHour = date('H:i', strtotime($form->getValue('end_hour')));

	if ($endHour == '00:00')
	{
		$endHour = '23:59';
	}
?>
	<?php if ($displayInfo): ?>
		<p class="recap">
			<?php echo __('Reservations on the slot %beginHour% - %endHour%', array('%beginHour%' => $beginHour, '%endHour%' => $endHour)) ?>
			<br/>
			<?php echo __('From %beginDate% to %endDate%', array('%beginDate%' => $beginDate,'%endDate%' => $endDate)) ?>
		</p>
	<?php endif; ?>

	<p><?php echo __('%count% reservation(s) match the specified parameters. Please select reservations to delete: ', array('%count%' => count($reservation_list))) ?></p>

	<form action="<?php echo url_for('reservationdelete/delete') ?>" method="post">
		<table class="list">
			<thead>
				<tr>
					<th></th>
					<th><?php echo __('Date'); ?></th>
					<?php
					$activityTitle = ConfigurationHelper::getParameter('Rename', 'activity_label');
					if (is_null($activityTitle) || empty($activityTitle))
					{
						$activityTitle = sfContext::getInstance()->getI18N()->__('Activity');
					}?>
					<th><?php echo $activityTitle; ?></th>
					<th><?php echo __('Duration') ?></th>
					<th><?php echo __('Room') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$is_even = false;
				$i = 0;
				foreach ($reservation_list as $reservation):
					$is_even ^= true;
					$i++;
					?>
					<tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
						<td class="ckb">
							<label class="ckb" for="ckb<?php echo $i ?>">
								<input type="checkbox" id="ckb<?php echo $i ?>" name="ckb<?php echo $i ?>" value="<?php echo $reservation->getId() ?>" checked />
							</label>
						</td>
						
						<td class="ckb">
							<label class="ckb" for="ckb<?php echo $i ?>">
								<?php echo $reservation->getDate() ?>
							</label>
						</td>
						
						<td class="ckb">
							<label class="ckb" for="ckb<?php echo $i ?>">
								<?php echo color_dot($reservation->getActivity()->getColor()).$reservation->getActivity()->getName() ?>
							</label>
						</td>
						
						<td class="ckb">
							<label class="ckb" for="ckb<?php echo $i ?>">
								<?php echo __('%duration% minute(s)', array('%duration%' => $reservation->getDuration())) ?>
							</label>
						</td>
						
						<td class="ckb">
							<label class="ckb" for="ckb<?php echo $i ?>">
								<?php echo $reservation->getRoomprofile()->getRoom()->getName() ?>
							</label>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan = "5">						
						<input type="submit" value="<?php echo __('Delete selected reservations') ?>">
						
						<?php
						if_javascript();
						?>
							<input type="button" value="Inverser la sélection" onClick="reverseAllCkb(<?php echo $i ?>);">
							<input type="button" value="<?php echo __('Uncheck all') ?> " onClick="this.value=checkAllCkb(<?php echo $i ?>);" style = "width: 150px;">
						<?php
						end_if_javascript();
						?>
						
						<div class="link_bot_form">
							<?php echo link_to(__('Clear'), 'reservationdelete/index?clear='); ?>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>

	<p>
		<?php echo __('Click %clear_link% to clear search results.', array('%clear_link%' => link_to(__('here'), 'reservationdelete/index?clear='))) ?>
	</p>

<?php endif; ?>
