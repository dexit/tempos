<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<h1><?php echo __('User search') ?></h1>

<p><?php echo __('You can also %user_index_link%.', array('%user_index_link%' => link_to(__('go back to the user page'), 'user/index?clear='))) ?></p>

<?php include_partial('searchForm', array('form' => $form)); ?>
