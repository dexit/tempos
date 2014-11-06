<h1><?php echo __('New user'); ?></h1>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the user list page'), 'user/index'))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
