<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion and filter functions disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<form action="<?php echo url_for('usergroup/addUsersProcess') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table class="form">
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
					<?php echo link_to(__('Cancel'), 'user/index') ?>

          <input type="submit" value="Add users" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['Usergroup_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['Usergroup_id']->renderError() ?>
          <?php echo $form['Usergroup_id'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['usergroup_has_user_list']->renderLabel() ?></th>
        <td>
          <?php echo $form['usergroup_has_user_list']->renderError() ?>
          <?php echo $form['usergroup_has_user_list'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
