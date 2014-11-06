<h1><?php echo __('Reservation reasons for "%activity_name%"', array('%activity_name%' => $activity)) ?></h1>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<p>
	<?php echo __('You can %activity_link%.', array('%activity_link%' => link_to(__('go back to the entries page'), 'activity/index'))) ?>
</p>

<table class="list">
  <thead>
    <tr>
      <th>
				<a class="action" href="<?php echo url_for('reservationreason/new?activityId='.$activity->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/add.png', array('alt' => __('add a new reason'))) ?></a>
				<?php echo __('Name') ?>
			</th>
    </tr>
  </thead>
  <tbody>
		<?php $is_even = false; ?>

    <?php foreach ($reservationreason_list as $reservationreason): ?>

		<?php $is_even ^= true; ?>

    <tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
      <td>
				<div class="actions">
					<?php if_javascript(); ?>
						<?php echo link_to(image_tag('/sf/sf_admin/images/cancel.png', array('alt' => __('Delete reason'))), 'reservationreason/delete?id='.$reservationreason->getId(), array('method' => 'delete', 'confirm' => __('Are you sure ?'))) ?>
					<?php end_if_javascript(); ?>
				</div>
				<a href="<?php echo url_for('reservationreason/edit?id='.$reservationreason->getId()) ?>"><?php echo $reservationreason->getName() ?></a>
			</td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p>
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new reason'), 'reservationreason/new?activityId='.$activity->getId()))) ?>
</p>
