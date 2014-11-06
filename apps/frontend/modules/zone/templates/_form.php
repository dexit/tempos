<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<form action="<?php echo url_for('zone/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
					<?php echo link_to(__('Cancel'), 'zone/index') ?>

          <?php if (!$form->getObject()->isNew()): ?>
						<?php if_javascript(); ?>
  	          <?php echo link_to(__('Delete'), 'zone/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure ?'))) ?>
						<?php end_if_javascript(); ?>
          <?php endif; ?>
		  </div>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $form['name']->renderError() ?>
          <?php echo $form['name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['parent_zone']->renderLabel() ?></th>
        <td>
					<?php if (!$form['parent_zone']->isHidden()): ?>
          	<?php echo $form['parent_zone']->renderError() ?>
          	<?php echo $form['parent_zone'] ?>
					<?php else: ?>
						<?php if (is_null($form->getObject()->getParentZoneObject())): ?>
							<span class="empty"><?php echo __('Root'); ?></span>
						<?php else: ?>
							<?php echo $form->getObject()->getParentZoneObject()->getName() ?>
						<?php endif; ?>
					<?php endif; ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
