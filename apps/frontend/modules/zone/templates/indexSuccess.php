<h1><?php echo __('Zone list'); ?></h1>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion and move functions disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<p>
<?php echo format_number_choice('[0]No zones in the database.|[1]There is actually one zone in the database.|(1,+Inf]There is actually %count% zones in the database.', array('%count%' => $zone_total_count), $zone_total_count) ?>
</p>

<?php if (count($zone_list) > 0): ?>
<table class="tree list">
	<thead>
		<tr>
			<th colspan="<?php echo $recursion + 1 ?>"><?php echo __('Zones') ?></th>
			<th><?php echo __('Rooms') ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($zone_list as $zone): ?>
		<?php include_partial('drawZone', array('zone' => $zone, 'recursion' => $recursion)) ?>
	<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>

<div>
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new root zone'), 'zone/new'))) ?>
</div>
