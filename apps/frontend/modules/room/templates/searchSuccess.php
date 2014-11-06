<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<h1><?php echo __('Room search') ?></h1>

<p><?php echo __('You can also %room_index_link%.', array('%room_index_link%' => link_to(__('go back to the room page'), 'room/index?clear='))) ?></p>

<?php include_partial('searchForm', array('form' => $form)); ?>
