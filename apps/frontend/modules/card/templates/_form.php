<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<form action="<?php echo url_for('card/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="form">
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="<?php echo __('Save') ?>" />
			<?php echo $form->renderHiddenFields() ?>
			<div class="link_bot_form">
				<?php echo link_to(__('Cancel'), 'card/index') ?>
				
				<?php if (!$form->getObject()->isNew()): ?>
					<?php if_javascript(); ?>
						<?php echo link_to(__('Delete'), 'card/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure ?')) ?>
					<?php end_if_javascript(); ?>
				<?php endif; ?>
			</div>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['card_number']->renderLabel() ?></th>
        <td>
          <?php echo $form['card_number']->renderError() ?>
          <?php echo $form['card_number'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['pin_code']->renderLabel() ?></th>
        <td>
          <?php echo $form['pin_code']->renderError() ?>
          <?php echo $form['pin_code'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['is_active']->renderLabel() ?></th>
        <td>
          <?php echo $form['is_active']->renderError() ?>
          <?php echo $form['is_active'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['family_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['family_name']->renderError() ?>
          <?php echo $form['family_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['surname']->renderLabel() ?></th>
        <td>
          <?php echo $form['surname']->renderError() ?>
          <?php echo $form['surname'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['birthdate']->renderLabel() ?></th>
        <td>
          <?php echo $form['birthdate']->renderError() ?>
          <?php echo $form['birthdate'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
