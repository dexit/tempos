<h1> <?php echo (__('Confirm deletion')); ?> </h1>

<p><?php echo __('The reservation is a part of a repetition of reservations. You can also delete reservations before and after it and part of the same repetition.') ?></p>

<form action="<?php echo url_for('reservationdelete/index?id='.$reservation->getId()) ?>" method="post">
<table class="form">
  <tbody>
    <?php echo $form ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan = "2">
		<div class="left_form"> <sup>*</sup><?php echo ' '.__('A summary of relevant reservations will be displayed before deleting'); ?> </div>
        <input type="submit" value="<?php echo __('Delete') ?>">
      </td>
    </tr>
  </tfoot>
</table>
</form>
