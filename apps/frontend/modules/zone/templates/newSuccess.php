<h1><?php echo __('New zone'); ?></h1>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the zone list page'), 'zone/index'))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
