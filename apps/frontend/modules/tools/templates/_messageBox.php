<div class="<?php echo $class ?> box">
	<?php if (isset($title)): ?>
	<p class="title"><?php echo $title ?></p>
	<?php endif; ?>
	<?php if ($showImg): ?>
		<div class="icon"></div>
	<?php endif; ?>
	<?php if (!is_string($msg)): ?>
		<div class="text">
			<?php foreach($msg as $m): ?>
				<p>
					<?php echo html_entity_decode($m) ?>
				</p>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<p class="text">
			<?php echo html_entity_decode($msg) ?>
		</p>
	<?php endif; ?>
</div>
