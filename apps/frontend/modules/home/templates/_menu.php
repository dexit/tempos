<?php if (isset($modules)): ?>
	<!-- <h1><?php // echo __('Navigation') ?></h1> -->
	<?php if (empty($modules) || (count($modules) <= 0)): ?>
		<p><?php echo __('Nothing to display'); ?>
	<?php else: ?>
		<ul class="menu_ul">
		<?php foreach($modules as $module => $title): ?>
			<li>
				<div class="title">
					<?php echo link_to(__($title), $module.'/index'); ?>
				</div>
				<?php include_component($module, 'menuItems') ?>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
<?php endif; ?>
