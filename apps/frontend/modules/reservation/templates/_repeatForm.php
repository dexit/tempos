<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form action="<?php echo url_for('reservation/processRepeat?id='.$form->getObject()->getId()) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table class="form">
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
					<?php echo link_to(__('Cancel'), 'reservation/index?roomId='.$form->getObject()->getRoomprofile()->getRoomId()) ?>

          <input type="submit" value="<?php echo __('Save') ?>" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>

			<?php if (!$form['repeat_type']->isHidden()): ?>
				<tr>
					<th><?php echo $form['repeat_type']->renderLabel() ?></th>
					<td>
						<?php echo $form['repeat_type']->renderError() ?>
						<?php echo $form['repeat_type'] ?>
					</td>
				</tr>
			<?php endif; ?>
			<?php if (!$form['stop_on_error']->isHidden()): ?>
				<tr>
					<th><?php echo $form['stop_on_error']->renderLabel() ?></th>
					<td>
						<?php echo $form['stop_on_error']->renderError() ?>
						<?php echo $form['stop_on_error'] ?>
					</td>
				</tr>
			<?php endif; ?>
			<?php if (!$form['count']->isHidden()): ?>
				<tr>
					<th><?php echo $form['count']->renderLabel() ?></th>
					<td>
						<?php echo $form['count']->renderError() ?>
						<?php echo $form['count'] ?>
					</td>
				</tr>
			<?php endif; ?>
			<?php if (!$form['until_date']->isHidden()): ?>
				<tr>
					<th><?php echo $form['until_date']->renderLabel() ?></th>
					<td>
						<?php echo $form['until_date']->renderError() ?>
						<?php echo $form['until_date'] ?>
					</td>
				</tr>
			<?php endif; ?>
			<?php if (!$form['day_period']->isHidden()): ?>
				<tr>
					<th><?php echo $form['day_period']->renderLabel() ?></th>
					<td>
						<?php echo $form['day_period']->renderError() ?>
						<?php echo $form['day_period'] ?>
					</td>
				</tr>
			<?php endif; ?>
			<?php if (!$form['week_period']->isHidden()): ?>
				<tr>
					<th><?php echo $form['week_period']->renderLabel() ?></th>
					<td>
						<?php echo $form['week_period']->renderError() ?>
						<?php echo $form['week_period'] ?>
					</td>
				</tr>
			<?php endif; ?>
			<?php if (!$form['month_period']->isHidden()): ?>
				<tr>
					<th><?php echo $form['month_period']->renderLabel() ?></th>
					<td>
						<?php echo $form['month_period']->renderError() ?>
						<?php echo $form['month_period'] ?>
						<div class="div_form"><?php echo __('1 month = 4 weeks') ?></div>
					</td>
				</tr>
			<?php endif; ?>
			<?php if (!$form['year_period']->isHidden()): ?>
				<tr>
					<th><?php echo $form['year_period']->renderLabel() ?></th>
					<td>
						<?php echo $form['year_period']->renderError() ?>
						<?php echo $form['year_period'] ?>
						<div class="div_form"><?php echo __('1 year = 52 weeks') ?></div>
					</td>
				</tr>
			<?php endif; ?>
    </tbody>
  </table>
</form>
