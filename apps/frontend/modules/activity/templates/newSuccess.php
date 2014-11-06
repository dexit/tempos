<h1><?php echo __('New entry') ?></h1>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the entries list page'), 'activity/index'))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
