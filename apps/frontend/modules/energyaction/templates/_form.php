<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<form action="<?php echo url_for('energyaction/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
					<?php echo link_to(__('Cancel'), 'energyaction/index') ?>

          <?php if (!$form->getObject()->isNew()): ?>
						<?php if_javascript(); ?>
  	          <?php echo link_to(__('Delete'), 'energyaction/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure ?'))) ?>
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
	  <?php if (isset($form['home_automation_controller']) && !is_null($form['home_automation_controller'])): ?>
		  <tr>
			<th><?php echo $form['home_automation_controller']->renderLabel() ?></th>
			<td>
			  <?php echo $form['home_automation_controller']->renderError() ?>
			  <?php echo $form['home_automation_controller'] ?>
			</td>
		  </tr>
	  <?php endif; ?>
      <tr>
        <th><?php echo $form['delayUp']->renderLabel() ?></th>
        <td>
          <?php echo $form['delayUp']->renderError() ?>
          <?php echo $form['delayUp'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['delayDown']->renderLabel() ?></th>
        <td>
          <?php echo $form['delayDown']->renderError() ?>
          <?php echo $form['delayDown'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['identifier']->renderLabel() ?></th>
        <td>
          <?php echo $form['identifier']->renderError() ?>
          <?php echo $form['identifier'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['processIdUp']->renderLabel() ?></th>
        <td>
          <?php echo $form['processIdUp']->renderError() ?>
          <?php echo $form['processIdUp'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['processIdDown']->renderLabel() ?></th>
        <td>
          <?php echo $form['processIdDown']->renderError() ?>
          <?php echo $form['processIdDown'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['start']->renderLabel() ?></th>
        <td>
          <?php echo $form['start']->renderError() ?>
          <?php echo $form['start'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['stop']->renderLabel() ?></th>
        <td>
          <?php echo $form['stop']->renderError() ?>
          <?php echo $form['stop'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['room_has_energyaction_list']->renderLabel() ?></th>
        <td>
          <?php echo $form['room_has_energyaction_list']->renderError() ?>
          <?php echo $form['room_has_energyaction_list'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
