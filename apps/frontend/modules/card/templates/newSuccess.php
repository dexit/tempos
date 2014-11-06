<h1><?php echo __('New card'); ?></h1>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the card list page'), 'card/index'))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
