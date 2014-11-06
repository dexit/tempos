<h1><?php echo __('Edit Feature') ?></h1>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the feature list page'), 'feature/index'))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
