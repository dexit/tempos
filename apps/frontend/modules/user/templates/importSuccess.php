<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<h1><?php echo __('User import') ?></h1>

<?php if (isset($import_count)): ?>
	<?php include_partial('tools/messageBox', array('class' => ($import_count <= 0) ? 'error' : 'success', 'title' => __('Importation result'), 'msg' => __('%count% user(s) imported.', array('%count%' => $import_count)), 'showImg' => true)); ?>
<?php endif; ?>

<p><?php echo __('You can also %user_index_link%.', array('%user_index_link%' => link_to(__('go back to the user page'), 'user/index'))) ?></p>

<?php include_partial('importForm', array('form' => $form)); ?>
