<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<form action="<?php echo url_for('room/'.($form->getObject()->isNew() ? 'create' : 'update').'?'.(!$form->getObject()->isNew() ? '&id='.$form->getObject()->getId() : '').(isset($referer) ? '&referer='.$referer : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
					<?php echo link_to(__('Cancel'), 'room/index') ?>

          <?php if (!$form->getObject()->isNew()): ?>
						<?php if_javascript(); ?>
  	          <?php echo link_to(__('Delete'), 'room/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure ?'))) ?>
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
        <th><?php echo $form['capacity']->renderLabel() ?></th>
        <td>
          <?php echo $form['capacity']->renderError() ?>
          <?php echo $form['capacity'] ?>
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
        <th><?php echo $form['address']->renderLabel() ?></th>
        <td>
          <?php echo $form['address']->renderError() ?>
          <?php echo $form['address'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['room_has_energyaction_list']->renderLabel() ?></th>
        <td>
          <?php echo $form['room_has_energyaction_list']->renderError() ?>
          <?php echo $form['room_has_energyaction_list'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['description']->renderLabel() ?></th>
        <td>
          <?php echo $form['description']->renderError() ?>
          <?php echo $form['description'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['room_has_activity_list']->renderLabel() ?></th>
        <td>
          <?php echo $form['room_has_activity_list']->renderError() ?>
          <?php echo $form['room_has_activity_list'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['zone_has_room_list']->renderLabel() ?></th>
        <td>
          <?php echo $form['zone_has_room_list']->renderError() ?>
          <?php echo $form['zone_has_room_list'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
