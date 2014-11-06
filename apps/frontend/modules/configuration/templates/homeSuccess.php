<h1><?php echo __('General configuration') ?></h1>

<?php if (isset($saved)): ?>
	<?php if ($saved): ?>
		<?php include_partial('tools/messageBox', array('class' => 'success', 'title' => __('Configuration updated !'), 'msg' => __('The configuration was saved and applied.'), 'showImg' => true)); ?>
	<?php else: ?>
		<?php include_partial('tools/messageBox', array('class' => 'error', 'title' => __('Configuration error !'), 'msg' => array(__('There is one or more error(s) in the form.'), __('Unable to save the configuration.')), 'showImg' => true)); ?>
	<?php endif; ?>
<?php else: ?>
	<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Be careful !'), 'msg' => __('Be sure you know what you\'re doing before changing a value in this section ! Misconfiguration could lead to a definitive loss of access.'), 'showImg' => true)); ?>
<?php endif; ?>

<?php 
if (isset($checkError)):
	if (isset($checkError['valid'])):
		if (!$checkError['valid']):
			include_partial('tools/messageBox', array('class' => 'error', 'title' => __('Configuration error !'), 'msg' => array(__('You can\'t give the same identifier name to two different controllers : %controller_name1% and %controller_name2%.', array('%controller_name1%' => $checkError['cname1'], '%controller_name2%' => $checkError['cname2'])), __('')), 'showImg' => true));
		endif;
	endif;
endif;
?>

<p><?php echo __('Here is the general configuration for your Tempo\'s system.') ?></p>

<form action="<?php echo url_for('configuration/update') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table class="form">
    <tfoot>
      <tr>
        <td colspan="2">
            <input type="submit" value="<?php echo __('Apply &amp; Save') ?>" />
			<?php echo $form->renderHiddenFields() ?>
			<div class="link_bot_form">
				<?php echo link_to(__('Cancel'), 'home/index') ?>
			</div>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
		<?php foreach($form as $widget): ?>
			<?php if (!$widget->isHidden()): ?>
			<tr 
			<?php
				if ($widget->getName() === 'number_of_physical_access' || $widget->getName() === 'number_of_home_automation')
					echo 'class="bordertop borderlr"';
				
				if (strstr($widget->getName(), 'physical_access_controller') || strstr($widget->getName(), 'home_automation_controller'))
					echo 'class="borderlr"';
				
				foreach ($physical_access_controllers as $key => $value)
				{
					if (strstr($widget->getName(), $key))
						echo 'class="borderlr"';
				}
				
				foreach ($home_automation_controllers as $key => $value)
				{
					if (strstr($widget->getName(), $key))
						echo 'class="borderlr"';
				}
				
				if ($widget->getName() === 'Network')
					echo 'class="bordertop"';
			?>
			>
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
