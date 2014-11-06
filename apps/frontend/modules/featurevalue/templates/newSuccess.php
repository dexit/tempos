<h1><?php echo __('New value') ?></h1>

<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the value list page'), 'featurevalue/index?featureId='.$form->getFeature()->getId()))) ?></p>

<?php include_partial('form', array('form' => $form)) ?>
