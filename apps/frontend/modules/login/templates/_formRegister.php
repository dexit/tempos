<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form action="<?php echo url_for('login/prepareRegister?id='.$form->getObject()->getId()) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table class="form">
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
          <input type="submit" value="<?php echo __('Register') ?>" />
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
      <tr>
        <th><?php echo $form['captcha']->renderLabel() ?></th>
        <td>
					<p>
						<?php echo captcha_image() ?>
						<?php echo captcha_reload_button() ?>
					</p>
          <?php echo $form['captcha']->renderError() ?>
          <?php echo $form['captcha'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
