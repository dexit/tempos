<h1><?php echo __('Edit group')?></h1>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the group list page'), 'usergroup/index'))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
