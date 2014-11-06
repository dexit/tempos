<h1><?php echo __('Opening time for "%room_name%"', array('%room_name%' => $room)) ?></h1>

<p>
	<?php echo __('You can %room_link%.', array('%room_link%' => link_to(__('go back to the rooms page'), 'room/index'))) ?>
</p>

<p>
<?php echo __('Copy periods from another room: ') ?>
</p>

<form action="<?php echo url_for('room/copyDayperiods?id='.$room->getId()) ?>" method="post" <?php $copyForm->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
	<?php echo $copyForm->renderHiddenFields(); ?>
	<?php if ($copyForm->hasGlobalErrors()): ?>
		<p class="error">
			<?php echo $copyForm->renderGlobalErrors() ?>
		</p>
	<?php endif; ?>
	<p>
		<?php echo $copyForm['copyRoom_id']->renderLabel() ?>
		<?php echo $copyForm['copyRoom_id'] ?>
		<input type="submit" value="<?php echo __('Copy') ?>" />
	</p>

</form>

<ul class="buttons">
	<li><?php echo button_link_to('Clear the periods', 'room/clearDayperiods?id='.$room->getId()); ?></li>
</ul>

<?php include_partial('dayperiod/openingTime', array('dayperiod_list' => $dayperiod_list, 'room' => $room)); ?>

<p>
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new period'), 'dayperiod/new?roomId='.$room->getId()))) ?>
</p>
