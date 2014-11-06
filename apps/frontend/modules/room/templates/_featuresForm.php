<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form action="<?php echo url_for('room/featuresUpdate?id='.$form->getObject()->getId()) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="form">
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
					<?php echo link_to(__('Cancel'), 'room/index') ?>

          <input type="submit" value="<?php echo __('Save') ?>" />
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
