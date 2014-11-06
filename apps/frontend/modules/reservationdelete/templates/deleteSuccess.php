<h1><?php echo __('Reservation deletion') ?></h1>

<p>
<?php 
if ($linkRoom)
{
	echo __('Go back to %room_page%.', array('%room_page%' => link_to(__('the room planning page'), 'reservation/index?roomId='.$room_id)));
}
?>
</p>
<p><?php echo __('Go back to %room_page%.', array('%room_page%' => link_to(__('the multiple reservations deletion page'), 'reservationdelete/index?clear='))) ?></p>

<?php if ($count_delete > 0): ?>
<p><span class="title"><?php echo __('Here are the deleted reservations: ') ?></span></p>
<dl class="result">
<?php
	foreach ($reservation_delete_list as $reservation)
	{
	?>
	<dt>
		<span class="title"> <?php echo $reservation->__toString(); ?></span>
	</dt>
	<dd class="success"><?php echo __('Deletion successful') ?></dd>
	<?php 
	}
?>
</dl>
<?php else: ?>
	<div class="error"><?php echo __('No reservation has been deleted') ?></div>
<?php endif; ?>

<?php if ($count_fail > 0): ?>
<p><span class="title"><?php echo __('Here are the reservations that have not been deleted: ') ?></span></p>
<dl class="result">
<?php
	foreach ($reservation_delete_fail as $reservation)
	{
	?>
	<dt>
		<span class="title"> <?php echo $reservation->__toString(); ?></span>
		<dd class="error">
			<span><?php echo __('Unable to delete this reservation') ?></span>
		</dd>
	</dt>
	<?php 
	}
?>
</dl>
<?php else: ?>
	<div class="recap_success success"><?php echo __('All reservations have been deleted') ?></div>
<?php endif; ?>
