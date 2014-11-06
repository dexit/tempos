<h1><?php echo __('Edit Room') ?></h1>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the room list page'), 'room/index'))) ?></p>

<?php include_partial('form', array('form' => $form, 'referer' => $referer)) ?>
