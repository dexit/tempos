<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<form action="<?php echo url_for('user/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
	      <?php echo link_to(__('Cancel'), 'user/index') ?>
          <?php if (!$form->getObject()->isNew() && !$form->getObject()->hasRole('admin') && ($form->getObject()->getId() != $sf_user->getTemposUser()->getId())): ?>
						<?php if_javascript(); ?>
  	          <?php echo link_to(__('Delete'), 'user/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure ?'))) ?>
						<?php end_if_javascript(); ?>
          <?php endif; ?>
		</div>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
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
			<?php if (!$form['login']->isHidden()): ?>
				<tr>
					<th><?php echo $form['login']->renderLabel() ?></th>
					<td>
						<?php echo $form['login']->renderError() ?>
						<?php echo $form['login'] ?>
					</td>
				</tr>
			<?php endif; ?>
      <tr>
        <th><?php echo $form['password']->renderLabel() ?></th>
        <td>
          <?php echo $form['password']->renderError() ?>
          <?php echo $form['password'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['password2']->renderLabel() ?></th>
        <td>
          <?php echo $form['password2']->renderError() ?>
          <?php echo $form['password2'] ?>
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
        <th><?php echo $form['card_number']->renderLabel() ?></th>
        <td>
          <?php echo $form['card_number']->renderError() ?>
          <?php echo $form['card_number'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['birthdate']->renderLabel() ?></th>
        <td>
          <?php echo $form['birthdate']->renderError() ?>
          <?php echo $form['birthdate'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['email_address']->renderLabel() ?></th>
        <td>
          <?php echo $form['email_address']->renderError() ?>
          <?php echo $form['email_address'] ?>
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
        <th><?php echo $form['phone_number']->renderLabel() ?></th>
        <td>
          <?php echo $form['phone_number']->renderError() ?>
          <?php echo $form['phone_number'] ?>
        </td>
      </tr>
			<?php if (isset($form['user_has_role_list'])): ?>
				<tr>
					<th><?php echo $form['user_has_role_list']->renderLabel() ?></th>
					<td>
						<?php echo $form['user_has_role_list']->renderError() ?>
						<?php echo $form['user_has_role_list'] ?>
					</td>
				</tr>
			<?php endif; ?>
    </tbody>
  </table>
</form>
