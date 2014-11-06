<h1><?php echo __('Edit card'); ?></h1>

<?php if (isset($created) && ($created == true)): ?>
	<?php include_partial('tools/messageBox', array('class' => 'success', 'title' => __('Card created'), 'msg' => array(__('Card was created successfully.'), link_to(__('Create a new one.'), 'card/new')), 'showImg' => true)); ?>
<?php endif; ?>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the card list page'), 'card/index'))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
