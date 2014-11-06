<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form action="<?php echo url_for('subscription/usergroupCreate?usergroupId='.$form->getObject()->getUsergroupId()) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="form">
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
					<?php echo link_to(__('Cancel'), 'usergroup/index') ?>

          <input type="submit" value="<?php echo __('Save') ?>" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['User_type']->renderLabel() ?></th>
        <td>
          <?php echo $form['User_type']->renderError() ?>
          <?php echo $form['User_type'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['Create_rule']->renderLabel() ?></th>
        <td>
          <?php echo $form['Create_rule']->renderError() ?>
          <?php echo $form['Create_rule'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['Activity_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['Activity_id']->renderError() ?>
          <?php echo $form['Activity_id'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['Zone_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['Zone_id']->renderError() ?>
          <?php echo $form['Zone_id'] ?>
        </td>
      </tr>
	  <tr>
        <th><?php echo $form['UserGroup_id']->renderLabel() ?></th>
        <td>
					<?php if (!$form['UserGroup_id']->isHidden()) : ?>
						<?php echo $form['UserGroup_id']->renderError() ?>
						<?php echo $form['UserGroup_id'] ?>
					<?php else: ?>
						<?php if (!is_null($form->getObject()->getUsergroup())): ?>
							<?php echo $form->getObject()->getUsergroup()->getName(); ?>
						<?php else: ?>
							<span class="empty"><?php echo __('No group') ?></span>
						<?php endif; ?>
					<?php endif; ?>
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
        <th><?php echo $form['unlimitedCredit']->renderLabel() ?></th>
        <td>
          <?php echo $form['unlimitedCredit']->renderError() ?>
          <?php echo $form['unlimitedCredit'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['credit']->renderLabel() ?></th>
        <td>
          <?php echo $form['credit']->renderError() ?>
          <?php echo $form['credit'] ?>
        </td>
      </tr>
			<?php if (!$form['minimum_delay']->isHidden()) : ?>
      <tr>
        <th><?php echo $form['minimum_delay']->renderLabel() ?></th>
        <td>
          <?php echo $form['minimum_delay']->renderError() ?>
          <?php echo $form['minimum_delay'] ?>
        </td>
      </tr>
			<?php endif; ?>
			<?php if (!$form['maximum_delay']->isHidden()) : ?>
      <tr>
        <th><?php echo $form['maximum_delay']->renderLabel() ?></th>
        <td>
          <?php echo $form['maximum_delay']->renderError() ?>
          <?php echo $form['maximum_delay'] ?>
        </td>
      </tr>
			<?php endif; ?>
			<?php if (!$form['minimum_duration']->isHidden()) : ?>
      <tr>
        <th><?php echo $form['minimum_duration']->renderLabel() ?></th>
        <td>
          <?php echo $form['minimum_duration']->renderError() ?>
          <?php echo $form['minimum_duration'] ?>
        </td>
      </tr>
			<?php endif; ?>
			<?php if (!$form['maximum_duration']->isHidden()) : ?>
      <tr>
        <th><?php echo $form['maximum_duration']->renderLabel() ?></th>
        <td>
          <?php echo $form['maximum_duration']->renderError() ?>
          <?php echo $form['maximum_duration'] ?>
        </td>
      </tr>
			<?php endif; ?>
			<?php if (!$form['hours_per_week']->isHidden()) : ?>
      <tr>
        <th><?php echo $form['hours_per_week']->renderLabel() ?></th>
        <td>
          <?php echo $form['hours_per_week']->renderError() ?>
          <?php echo $form['hours_per_week'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['is_active']->renderLabel() ?></th>
        <td>
          <?php echo $form['is_active']->renderError() ?>
          <?php echo $form['is_active'] ?>
        </td>
      </tr>
			<?php endif; ?>
    </tbody>
  </table>
</form>
