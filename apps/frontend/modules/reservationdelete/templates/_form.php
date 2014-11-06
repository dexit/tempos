<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Filter functions disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>
<?php if (is_null($reservationId)):
	$target = 'reservationdelete/index';
else:
	$target = 'reservationdelete/index?repeat='.$reservationId;
endif;
?>
<form action="<?php echo url_for($target) ?>" method="post">
<table class="form">
	<tbody>
		<?php $form->renderGlobalErrors(); ?>
		<tr>
			<th><?php echo $form['start_date']->renderLabel()?></th>
			<td>
			  <?php echo $form['start_date']->renderError() ?>
			  <?php echo $form['start_date'] ?>
			</td>
		</tr>

		<tr>
			<th><?php echo $form['end_date']->renderLabel() ?></th>
			<td>
			  <?php echo $form['end_date']->renderError() ?>
			  <?php echo $form['end_date'] ?>
			</td>
		</tr>

		<tr>
			<th><?php echo $form['start_hour']->renderLabel() ?></th>
			<td>
				<?php echo $form['start_hour']->renderError() ?>
				<?php echo $form['start_hour'] ?>
			</td>
		</tr>

		<tr>
			<th><?php echo $form['end_hour']->renderLabel() ?></th>
			<td>
				<?php echo $form['end_hour']->renderError() ?>
				<?php echo $form['end_hour'] ?>
			</td>
		</tr>

		<tr>
			<th><?php echo $form['activity']->renderLabel() ?></th>
			<td>
				<?php echo $form['activity']->renderError() ?>
				<?php echo $form['activity'] ?>
				<div class="bot_form"><?php echo __('No checkbox checked selects all activities') ?></div>
			</td>
		</tr>

		<tr>
			<th><?php echo $form['rooms']->renderLabel() ?></th>
			<td>
				<?php echo $form['rooms']->renderError() ?>
				<?php echo $form['rooms'] ?>
				<div class="bot_form"><?php echo __('No checkbox checked selects all rooms') ?></div>
			</td>
		</tr>

		<tr>
			<th><?php echo $form['periodicity']->renderLabel() ?></th>
			<td>
				<?php echo $form['periodicity']->renderError() ?>
				<?php echo $form['periodicity'] ?>
				<div class="div_form"><?php echo __('1 month = 4 weeks') ?></div>
				<div class="div_form"><?php echo __('1 year = 52 weeks') ?></div>
			</td>
		</tr>

		<tr>
			<th><?php echo $form['number']->renderLabel() ?></th>
			<td>
				<?php echo $form['number']->renderError() ?>
				<?php echo $form['number'] ?>
			</td>
		</tr>

		<tr>
			<th><?php echo $form['stop_on_error']->renderLabel() ?></th>
			<td>
				<?php echo $form['stop_on_error']->renderError() ?>
				<?php echo $form['stop_on_error'] ?>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan = "2">
				<div class = "left_form">
					<?php echo __('A summary of relevant reservations will be displayed before deleting') ?>
				</div>
				<input type="submit" value="<?php echo __('Delete') ?>">
				<div class="link_bot_form">
					<?php echo link_to(__('Clear'), 'reservationdelete/index?clear='); ?>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
</form>
