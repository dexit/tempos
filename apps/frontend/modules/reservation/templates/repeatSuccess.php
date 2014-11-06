<h1><?php echo __('Repeat reservation for %activity_name% in %room_name%', array('%activity_name%' => $activity->getName(), '%room_name%' => $room->getName())) ?></h1>

<p><?php echo __('Go back to %room_page%.', array('%room_page%' => link_to(__('the room planning page'), 'reservation/index?roomId='.$room->getId()))) ?></p>

<?php 
$formValid = false;

if (isset($forms))
{
	if (count($forms) > 0)
	{
		$formValid = true;
	}
}
?>

<?php if ($formValid): ?>

<form action="<?php echo url_for('reservation/repeatResult?id='.$reservation_id) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
			foreach ($forms as $form):
				$i++;
				$reservation = $form->getObject();
				$reservation->setId($i);
				$is_even ^= true;
				$valid = $form->isValid();
				?>
				<tr class="<?php echo $is_even ? 'even' : 'odd'; echo !$valid ? ' error' : '' ; ?>">
					<td class="ckb">
						<?php if ($valid): ?>
							<label class="ckb" for="ckb<?php echo $i ?>">
								<input type="checkbox" id="ckb<?php echo $i ?>" name="ckb<?php echo $i ?>" value="<?php echo $reservation->getId() ?>" <?php echo !$valid ? ' disabled' : ' checked' ; ?>/>
							</label>
						<?php endif; ?>
					</td>
					
					<td class="ckb">
						<label class="ckb" for="ckb<?php echo $i ?>">
							<?php echo $reservation->getDate() ?>
						</label>
					</td>
					
					<td class="ckb">
						<label class="ckb" for="ckb<?php echo $i ?>">
							<?php echo color_dot($reservation->getActivity()->getColor()).$reservation->getActivity()->getName() ?>
							<span class="error_form"><?php echo __($form->getErrorsToString()); ?></span>
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
					<input type="submit" value="<?php echo __('Create selected reservations') ?>">
					
					<?php
					if_javascript();
					?>
						<input type="button" value="Inverser la sélection" onClick="reverseAllCkb(<?php echo $i ?>);">
						<input type="button" value="<?php echo __('Uncheck all') ?> " onClick="this.value=checkAllCkb(<?php echo $i ?>);" style = "width: 150px;">
					<?php
					end_if_javascript();
					?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>

<?php else: ?>

<?php include_partial('repeatForm', array('form' => $form, 'room' => $room)) ?>

<?php endif; ?>