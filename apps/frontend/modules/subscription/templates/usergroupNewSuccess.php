<?php if (isset($usergroup)): ?>
	<h1><?php echo __('Edit subscription for users of %usergroup_name%', array('%usergroup_name%' => $usergroup->getName())) ?></h1>

	<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the user groups list page'), 'usergroup/index'))) ?></p>
<?php endif; ?>

<?php include_partial('usergroupForm', array('form' => $form)) ?>
