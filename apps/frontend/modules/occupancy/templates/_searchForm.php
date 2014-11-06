<?php if (!isset($target)): ?>
	<?php $target = 'occupancy/index?offset=0'; ?>
<?php endif; ?>

<form action="<?php echo url_for($target) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table class="form">
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>

          <input type="submit" value="<?php echo __('Show reporting') ?>" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>

			<?php foreach($form as $widget): ?>
				<?php if (!$widget->isHidden()): ?>
					<tr>
						<th><?php echo $widget->renderLabel() ?></th>
						<td>
							<?php echo $widget->renderError() ?>
							<?php echo $widget ?>
						</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>

    </tbody>
  </table>
</form>
