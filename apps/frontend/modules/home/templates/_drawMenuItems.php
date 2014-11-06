<?php if (empty($items) || (count($items) <= 0)): ?>
	<p><?php echo __('No sub menu.') ?></p>
<?php else: ?>
	<ul>
	<?php foreach($items as $link => $item): ?>
		<li>
		<?php if (isset($item['icon']) && !empty($item['icon'])): ?>
			<?php echo link_to(image_tag($item['icon'], array('alt' => '', 'width' => '32px', 'height' => '32px')).'<span>'.$item['title'].'</span>'.(isset($item['count']) && ($item['count'] > 0) ? '<span class="count"> ('.$item['count'].')</span>' : ''), $link) ?>
		<?php else: ?>
			<?php echo link_to('<span>'.$item['title'].'</span>', $link) ?>
		<?php endif; ?>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
