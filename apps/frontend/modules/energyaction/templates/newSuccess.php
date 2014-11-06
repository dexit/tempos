<h1><?php echo __('New action') ?></h1>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the action list page'), 'energyaction/index'))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
