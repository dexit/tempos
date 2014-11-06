<h1><?php echo __('Physical access for "%room_name%"', array('%room_name%' => $room)) ?></h1>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<p>
	<?php echo __('You can %room_link%.', array('%room_link%' => link_to(__('go back to the rooms page'), 'room/index'))) ?>
</p>

<table class="list">
  <thead>
    <tr>
		<th>
			<a class="action" href="<?php echo url_for('roomprofile/new?roomId='.$room->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/add.png', array('alt' => __('add a new reason'))) ?></a>
			<?php echo __('Name') ?>
		</th>
		<th>
			<?php echo __('Physical access identifier') ?>
		</th>
    </tr>
  </thead>
  <tbody>
		<?php $is_even = false; ?>

    <?php foreach ($roomprofile_list as $roomprofile): ?>

		<?php $is_even ^= true; ?>

    <tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
      <td>
				<div class="actions">
					<?php if_javascript(); ?>
						<?php echo link_to(image_tag('/sf/sf_admin/images/cancel.png', array('alt' => __('Delete reason'))), 'roomprofile/delete?id='.$roomprofile->getId(), array('method' => 'delete', 'confirm' => __('Are you sure ?'))) ?>
					<?php end_if_javascript(); ?>
				</div>
				<a href="<?php echo url_for('roomprofile/edit?id='.$roomprofile->getId()) ?>"><?php echo $roomprofile->getName() ?></a>
			</td>
			<td>
				<?php echo $roomprofile->getPhysicalAccessId() ?>
			</td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p>
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new profile'), 'roomprofile/new?roomId='.$room->getId()))) ?>
</p>
