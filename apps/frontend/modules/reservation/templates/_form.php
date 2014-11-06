<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<form action="<?php
if (!$view):
	echo url_for('reservation/'.($form->getObject()->isNew() ? 'create' : 'update').'?roomId='.$room->getId().(!is_null($form->getObject()->getDate()) ? '&date='.$form->getObject()->getDate() : '').(!$form->getObject()->isNew() ? '&id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ';
else:
	echo '"';
endif;
?>
>

<?php if (!$form->getObject()->isNew() && !$view): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="form">
    <tfoot>
      <tr>
        <td colspan="2">
			<?php if (!$view) { ?>
				<input type="submit" value="<?php echo __('Save') ?>" />
				<?php echo $form->renderHiddenFields() ?>
				<div class="link_bot_form">
					<?php echo link_to(__('Cancel'), 'reservation/index?roomId='.$form->getRoom()->getId()) ?>
					
					<?php if (!$form->getObject()->isNew()): ?>
						<?php if_javascript(); ?>
							<?php echo link_to(__('Delete'), 'reservation/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure ?'))) ?>
						<?php end_if_javascript(); ?>
					<?php endif; ?>
				</div>
			<?php } else {
				echo link_to(__('Back'), 'reservation/index?roomId='.$room->getId());
			} ?>
        </td>
      </tr>
    </tfoot>
    <tbody>
		<?php if (!$view):
			echo $form->renderGlobalErrors(); 
		endif;?>
		<tr>
			<?php if (!$view): ?>
				<th><?php echo $form['RoomProfile_id']->renderLabel() ?></th>
				<td>
					<?php if (!$form['RoomProfile_id']->isHidden()): ?>
						<?php echo $form['RoomProfile_id']->renderError() ?>
						<?php echo $form['RoomProfile_id'] ?>
					<?php else: ?>
						<?php echo $form->getObject()->getRoomprofile()->getRoom()->getName() ?>
					<?php endif; ?>
				</td>
			<?php else: ?>
				<th><?php echo $form['RoomProfile_id']->renderLabel() ?></th>
				<td>
					<?php echo $reservation->getRoomprofile()->getRoom()->getName() ?>
				</td>
			<?php endif; ?>
		</tr>
		<tr>
			<?php if (!$view): ?>
				<th><?php echo $form['Activity_id']->renderLabel() ?></th>
				<td>
					<?php if (!$form['Activity_id']->isHidden()): ?>
						<?php echo $form['Activity_id']->renderError() ?>
						<?php echo $form['Activity_id'] ?>
					<?php else: ?>
						<?php echo $form->getObject()->getActivity()->getName() ?>
					<?php endif; ?>
				</td>
			<?php else: ?>
				<th><?php echo $form['Activity_id']->renderLabel() ?></th>
				<td>
					<?php echo $reservation->getActivity()->getName() ?>
				</td>
			<?php endif; ?>
		</tr>
		<?php if (!$view): ?>
			<?php if (!is_null($form->getObject()->getUserId())): ?>
			<tr>
				<th><?php echo $form['User_id']->renderLabel() ?></th>
				<td>
					<?php echo $form->getObject()->getUser()->getFullName() ?>
				</td>
			</tr>
			<?php endif; ?>
		<?php else: ?>
			<?php if ($reservation->getUserId() != null): ?>
			<tr>
				<th><?php echo $form['User_id']->renderLabel() ?></th>
				<td>
					<?php echo $reservation->getUser()->getFullName() ?>
				</td>
			</tr>
			<?php endif; ?> 
		<?php endif; ?>
		<?php if (!$view): ?>
			<?php if (!is_null($form->getObject()->getCardId())): ?>
			<tr>
				<th><?php echo $form['Card_id']->renderLabel() ?></th>
				<td>
					<?php echo $form->getObject()->getCard()->getOwnerName() ?>
				</td>
			</tr>
			<?php endif; ?>
		<?php else: ?>
			<?php if ($reservation->getCard() != null): ?>
			<tr>
				<th><?php echo $form['Card_id']->renderLabel() ?></th>
				<td>
					<?php echo $reservation->getCard()->getOwnerName() ?>
				</td>
			</tr>
			<?php endif; ?>
		<?php endif; ?>
		
		<?php if (isset($form['ReservationReason_id']) && !$form['ReservationReason_id']->isHidden()): ?>
		<tr>
			<?php if (!$view): ?>
				<th><?php echo $form['ReservationReason_id']->renderLabel() ?></th>
				<td>
					<?php echo $form['ReservationReason_id']->renderError() ?>
					<?php echo $form['ReservationReason_id'] ?>
				</td>
			<?php else: ?>
				<th><?php echo $form['ReservationReason_id']->renderLabel() ?></th>
				<td>
					<?php echo $reservation->getReservationreason()->getName() ?>
				</td>
			<?php endif; ?>
		</tr>
		<?php endif; ?>
		<tr>
			<?php if (!$view): ?>
				<th><?php echo $form['date']->renderLabel() ?></th>
				<td>
					<?php if (!$form['date']->isHidden()): ?>
						<?php echo $form['date']->renderError() ?>
						<?php echo $form['date'] ?>
					<?php else: ?>
						<?php echo $form->getObject()->getDate('Y-m-d H:i:s'); ?>
					<?php endif; ?>
				</td>
			<?php else: ?>
				<th><?php echo $form['date']->renderLabel() ?></th>
				<td>
					<?php echo $reservation->getDate(); ?>
				</td>
			<?php endif; ?>
		</tr>
		  <tr>
			<?php if (!$view): ?>
				<th><?php echo $form['duration']->renderLabel() ?></th>
				<td>
				  <?php echo $form['duration']->renderError() ?>
				  <?php echo $form['duration'] ?>
				</td>
			<?php else: ?>
				<th><?php echo $form['duration']->renderLabel() ?></th>
				<td>
				  <?php echo $reservation->getDuration().' minutes' ?>
				</td>
			<?php endif; ?>
		  </tr>
		  <tr>
			<?php if (!$view): ?>
				<th><?php echo $form['members_count']->renderLabel() ?></th>
				<td>
				  <?php echo $form['members_count']->renderError() ?>
				  <?php echo $form['members_count'] ?>
				</td>
			<?php else: ?>
				<th><?php echo $form['members_count']->renderLabel() ?></th>
				<td>
					<?php echo $reservation->getMembersCount() ?>
				</td>
			<?php endif; ?>
		  </tr>
		  <tr>
			<?php if (!$view): ?>
				<th><?php echo $form['guests_count']->renderLabel() ?></th>
				<td>
				  <?php echo $form['guests_count']->renderError() ?>
				  <?php echo $form['guests_count'] ?>
				</td>
			<?php else: ?>
				<th><?php echo $form['guests_count']->renderLabel() ?></th>
				<td>
					<?php echo $reservation->getGuestsCount() ?>
				</td>
			<?php endif; ?>
		  </tr>
		  <tr>
			<?php if (!$view): ?>
				<th><?php echo $form['comment']->renderLabel() ?></th>
				<td>
				  <?php echo $form['comment']->renderError() ?>
				  <?php echo $form['comment'] ?>
				</td>
			<?php else: ?>
				<?php if ($reservation->getComment() != null): ?>
					<th><?php echo $form['comment']->renderLabel() ?></th>
					<td>
						<?php echo $reservation->getComment() ?>
					</td>
				<?php endif; ?>
			<?php endif; ?>
		  </tr>
		  <tr>
			<?php if (!$view): ?>
				<th><?php echo $form['price']->renderLabel() ?></th>
				<td>
				  <?php echo $form['price']->renderError() ?>
				  <?php echo $form['price'] ?>
				</td>
			<?php else: ?>
				<th><?php echo $form['price']->renderLabel() ?></th>
				<td>
				  <?php echo $reservation->getPrice() ?>
				</td>
			<?php endif; ?>
		  </tr>
		  <tr <?php echo ($form['custom_1']->renderLabelName() != 'hide') ? '' : 'style="display: none"'; ?>">
			<?php if (!$view): ?>
				<th><?php echo $form['custom_1']->renderLabel() ?></th>
				<td>
				  <?php echo $form['custom_1']->renderError() ?>
				  <?php echo $form['custom_1'] ?>
				</td>
			<?php else: ?>
				<?php if ($reservation->getCustom1() != null): ?>
					<th><?php echo $form['custom_1']->renderLabel() ?></th>
					<td>
					  <?php echo $reservation->getCustom1() ?>
					</td>
				<?php endif; ?>
			<?php endif; ?>
		  </tr>
		  <tr <?php echo ($form['custom_2']->renderLabelName() != 'hide') ? '' : 'style="display: none"'; ?>">
			<?php if (!$view): ?>
				<th><?php echo $form['custom_2']->renderLabel() ?></th>
				<td>
				  <?php echo $form['custom_2']->renderError() ?>
				  <?php echo $form['custom_2'] ?>
				</td>
			<?php else: ?>
				<?php if ($reservation->getCustom2() != null): ?>
					<th><?php echo $form['custom_2']->renderLabel() ?></th>
					<td>
					  <?php echo $reservation->getCustom2() ?>
					</td>
				<?php endif; ?>
			<?php endif; ?>
		</tr>
		<tr <?php echo ($form['custom_3']->renderLabelName() != 'hide') ? '' : 'style="display: none"'; ?>">
			<?php if (!$view): ?>
				<th><?php echo $form['custom_3']->renderLabel() ?></th>
				<td>
				  <?php echo $form['custom_3']->renderError() ?>
				  <?php echo $form['custom_3'] ?>
				</td>
			<?php else: ?>
				<?php if ($reservation->getCustom3() != null): ?>
					<th><?php echo $form['custom_3']->renderLabel() ?></th>
					<td>
					  <?php echo $reservation->getCustom3() ?>
					</td>
				<?php endif; ?>
			<?php endif; ?>
		</tr>
			<?php if (!$view): ?>
				<?php if (isset($form['UserGroup_id'])): ?>
					<tr>
						<th><?php echo $form['UserGroup_id']->renderLabel() ?></th>
						<td>
							<?php echo $form['UserGroup_id']->renderError() ?>
							<?php echo $form['UserGroup_id'] ?>
						</td>
					</tr>
				<?php endif; ?>
			<?php else: ?>
				<?php if (!is_null($reservation->getUsergroup())): ?>
				<tr>
					<th><?php echo $form['UserGroup_id']->renderLabel() ?></th>
					<td>
						<?php echo $reservation->getUsergroup()->getName() ?>
					</td>
				</tr>
				<?php endif; ?>
			<?php endif; ?>
	  <tr>
		<?php if (!$view): ?>
			<?php if (isset($form['reservation_has_user_list'])): ?>
				<th><?php echo $form['reservation_has_user_list']->renderLabel() ?></th>
				<td>
				  <?php echo $form['reservation_has_user_list']->renderError() ?>
				  <?php echo $form['reservation_has_user_list'] ?>
				</td>
			<?php endif; ?>
		<?php else: ?>
			<?php if ($reservation->countReservationOtherMemberss() > 0): ?>
				<tr>
					<th><?php echo $form['reservation_has_user_list']->renderLabel() ?></th>
					<td>
						<?php
						foreach ($reservation->getReservationOtherMemberss() as $occ)
						{
							echo $occ->getUser()->getFullName();
							?>
							</br>
							<?php
						} 
						?>
					</td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
      </tr>
    </tbody>
  </table>
</form>
