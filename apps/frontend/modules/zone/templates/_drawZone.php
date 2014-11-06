<?php $czones = $zone->getChildrenZoneObjects(); ?>
<?php $cszones = $zone->getAllChildrenZoneObjectsCount(); ?>
<tr class="<?php echo $recursion & 1 ? 'odd' : 'even' ?>">
		<?php if (count($czones) > 0): ?>
			<td rowspan="<?php echo $cszones + 1 ?>">
				<?php echo $zone->getName() ?>
			</td>
			<?php if ($recursion >= 1) : ?>
				<th colspan="<?php echo $recursion ?>">
						<?php include_partial('actions', array('zone' => $zone)) ?>
						<?php echo __('Sub-zones') ?>
				</th>
			<?php endif; ?>
		<?php else: ?>
			<td colspan="<?php echo $recursion + 1 ?>">
				<?php include_partial('actions', array('zone' => $zone)) ?>
				<?php echo $zone->getName() ?>
			</td>
		<?php endif; ?>

		<td>
				<span class="actions">
					<?php echo link_to(image_tag('/sf/sf_admin/images/add.png', array('alt' => __('Add a room'))), 'room/new?parentZoneId='.$zone->getId()) ?>
				</span>

				<?php $rooms = $zone->getDirectRooms(); ?>
				<?php if (count($rooms) > 0): ?>
					<ul class="inline">
						<?php foreach($rooms as $room): ?>
							<li>
								<?php echo link_to($room->getName(), 'room/edit?referer=zone&id='.$room->getId()) ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<span class="empty"><?php echo __('No rooms in this zone.') ?></span>
				<?php endif; ?>
		</td>
</tr>
<?php if (count($czones) > 0): ?>
	<?php foreach($czones as $czone): ?>
				<?php include_partial('drawZone', array('zone' => $czone, 'recursion' => $recursion - 1)) ?>
	<?php endforeach; ?>
<?php endif; ?>
