<h1><?php echo __('Edit user'); ?></h1>

<?php if (isset($created) && ($created == true)): ?>
	<?php include_partial('tools/messageBox', array('class' => 'success', 'title' => __('User created'), 'msg' => array(__('User was created successfully.'), link_to(__('Create a new one.'), 'user/new')), 'showImg' => true)); ?>
<?php endif; ?>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the user list page'), 'user/index'))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
