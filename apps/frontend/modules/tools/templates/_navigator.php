<div class="navigator">
<?php $fullUrl = $url.((strpos($url, '?') === false) ? '?' : '&') ?>
<?php if ($offset > 0): ?>
	<p class="prev">
		<?php if ($offset - $step > 0): ?>
			<?php echo link_to(__('See previous %count%', array('%count%' => $step)), $fullUrl.'offset='.($offset - $step)) ?>
		<?php else: ?>
			<?php echo link_to(__('See first %count%', array('%count%' => $offset)), $fullUrl.'offset=0') ?>
		<?php endif; ?>
	</p>
<?php endif; ?>
<?php if ($offset + $limit < $count): ?>
	<p class="next">
		<?php if ($offset + $limit + $step < $count): ?>
			<?php echo link_to(__('See next %count%', array('%count%' => $step)), $fullUrl.'offset='.($offset + $limit)) ?>
		<?php else: ?>
			<?php echo link_to(__('See last %count%', array('%count%' => $count - $offset - $limit)), $fullUrl.'offset='.($offset + $limit)) ?>
		<?php endif; ?>
	</p>
<?php endif; ?>
<?php if ($count > $step): ?>
	<span><?php echo __('Select page: ') ?></span>
	<?php for ($i = 0; $i * $step < $count; ++$i): ?>
		<?php if ($i == floor($offset / $step)): ?>
			<?php echo $i + 1 ?>
		<?php else: ?>
			<?php echo link_to($i + 1, $fullUrl.'offset='.($i * $step)) ?>
		<?php endif; ?>
	<?php endfor; ?>
<?php endif; ?>
</div>
