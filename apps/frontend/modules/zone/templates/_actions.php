<span class="actions">
	<?php if($zone->isOwned()): ?>
		<?php if_javascript(); ?>
			<?php echo link_to(image_tag('/sf/sf_admin/images/previous.png', array('alt' => __('Move to the parent zone'))), 'zone/moveUp?id='.$zone->getId(), array('method' => 'put', 'confirm' => __('Are you sure ?'))) ?>
		<?php end_if_javascript(); ?>
	<?php endif; ?>

	<?php echo link_to(image_tag('/sf/sf_admin/images/add.png', array('alt' => __('Add a sub-zone'))), 'zone/new?parentId='.$zone->getId()) ?>
	<?php echo link_to(image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit zone'))), 'zone/edit?id='.$zone->getId()) ?>
	
	<?php if_javascript(); ?>
		<?php echo link_to(image_tag('/sf/sf_admin/images/cancel.png', array('alt' => __('Delete zone'))), 'zone/delete?id='.$zone->getId(), array('method' => 'delete', 'confirm' => __('Are you sure ?'))) ?>
	<?php end_if_javascript(); ?>
</span>
