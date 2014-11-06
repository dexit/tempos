<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<h1><?php echo __('Room search') ?></h1>

<p><?php echo __('You can also %zone_index_link%.', array('%zone_index_link%' => link_to(__('go back to the zone page'), 'home/zoneIndex?activityId='.$activity->getId()))) ?></p>

<form action="<?php echo url_for('home/searchRoomProcess?activityId='.$activity->getId()) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table class="form">
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
					<?php echo link_to(__('Cancel'), 'home/zoneIndex?activityId='.$activity->getId()) ?>

          <input type="submit" value="<?php echo __('Show results') ?>" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>

			<?php foreach($form as $widget): ?>
				<?php if (!$widget->isHidden()): ?>
					<tr>
						<th><?php echo $widget->renderLabel() ?></th>
						<td>
							<?php echo $widget->renderError() ?>
							<?php echo $widget ?>
						</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>

    </tbody>
  </table>
</form>
