<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form action="<?php echo url_for(html_entity_decode($submit_url)) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="form">
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="<?php echo __('Send message') ?>" />
			<?php echo $form->renderHiddenFields() ?>
			<div class="link_bot_form">
				<?php echo link_to(__('Cancel'), html_entity_decode($cancel_url)) ?>
			</div>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
		<th><?php echo __('Send to') ?></th>
		<td>
			<?php echo $form->getObject()->getOwnerUser(); ?>
		</td>
	  </tr>
	  <tr>
        <th><?php echo $form['subject']->renderLabel() ?></th>
        <td>
          <?php echo $form['subject']->renderError() ?>
          <?php echo $form['subject'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['text']->renderLabel() ?></th>
        <td>
          <?php echo $form['text']->renderError() ?>
          <?php echo $form['text'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
