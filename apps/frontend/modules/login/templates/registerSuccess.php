<h1><?php echo __('Registration') ?></h1>

<p><?php echo __('The following informations are required to register an new account.') ?></p>

<?php include_partial('tools/messageBox', array('class' => 'info', 'title' => __('Registration information'), 'msg' => array(__("Your account will only be usable when validated by an administrator."), __("Don't forget to fill in your email address to receive information about your registration.")), 'showImg' => true)); ?>

<?php include_partial('formRegister', array('form' => $form)) ?>
