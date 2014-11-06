<h1><?php echo __('Values for "%feature_name%"', array('%feature_name%' => $feature->getName())) ?></h1>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<p>
	<?php echo link_to(__('Go back to the features list.'), 'feature/index') ?>
</p>

<table class="list">
  <thead>
    <tr>
      <th>
					<a class="action" href="<?php echo url_for('featurevalue/new?featureId='.$feature->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/add.png', array('alt' => __('add a new value'), 'width' => '16px', 'height' => '16px')) ?></a>
					<?php echo __('Value') ?>
			</th>
    </tr>
  </thead>
  <tbody>

		<?php $is_even = false; ?>

    <?php foreach ($featurevalue_list as $featurevalue): ?>

		<?php $is_even ^= true; ?>

    <tr class="<?php echo $is_even ? 'even' : 'odd'; ?>">
      <td>
				<div class="actions">
					<a href="<?php echo url_for('featurevalue/edit?id='.$featurevalue->getId()) ?>"><?php echo image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit'), 'width' => '16px', 'height' => '16px')) ?></a>
					<?php if_javascript(); ?>
						<?php echo link_to(image_tag('/sf/sf_admin/images/cancel.png', array('alt' => __('Delete zone'))), 'featurevalue/delete?id='.$featurevalue->getId(), array('method' => 'delete', 'confirm' => __('Are you sure ?'))) ?>
					<?php end_if_javascript(); ?>
				</div>
				<span><?php echo $featurevalue->getValue() ?></span>
			</td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p>
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new value'), 'featurevalue/new?featureId='.$feature->getId()))) ?>
</p>
