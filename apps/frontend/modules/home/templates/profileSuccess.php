<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<h1><?php echo __('My profile') ?></h1>

<?php if (!is_null($form)): ?>

	<?php if (isset($saved)): ?>
		<?php if ($saved): ?>
			<?php include_partial('tools/messageBox', array('class' => 'success', 'title' => __('Profile updated'), 'msg' => __('Profile updated successfully'), 'showImg' => true)); ?>
		<?php endif; ?>
	<?php endif; ?>

	<form action="<?php echo url_for('home/profile') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
		<table class="form">
			<tfoot>
				<tr>
					<td colspan="2">
						<?php echo $form->renderHiddenFields() ?>

						<input type="submit" value="<?php echo __('Save') ?>" />
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php echo $form->renderGlobalErrors() ?>
				<tr>
					<th><label><?php echo __('Family name') ?></label></th>
					<td><?php echo $form->getObject()->getFamilyName() ?></td>
				</tr>
				<tr>
					<th><label><?php echo __('Surname') ?></label></th>
					<td><?php echo $form->getObject()->getSurname() ?></td>
				</tr>
				<tr>
					<th><label><?php echo __('Username') ?></label></th>
					<td><?php echo $form->getObject()->getLogin() ?></td>
				</tr>
				<tr>
					<th><?php echo $form['current_password']->renderLabel() ?></th>
					<td>
						<?php echo $form['current_password']->renderError() ?>
						<?php echo $form['current_password'] ?>
					</td>
				</tr>
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
					<th><label><?php echo __('Card number') ?></label></th>
					<td><?php echo $form->getObject()->getCardNumber() ?></td>
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
			</tbody>
		</table>
	</form>
<?php endif; ?>
