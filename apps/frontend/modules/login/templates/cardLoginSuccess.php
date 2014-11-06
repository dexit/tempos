<?php use_helper('Form'); ?>
<div id="login">
	<?php $logo = 'logos/'.sfConfig::get('app_has_logo'); ?>
	<?php $logofile = sfConfig::get('sf_web_dir').'/images/'.$logo; ?>
	<?php if (file_exists($logofile) && is_readable($logofile) && is_file($logofile) && !$sf_user->isAuthenticated()): ?>
		<?php echo image_tag($logo, array('id' => 'logo', 'alt' => 'Logo')); ?>
	<?php endif; ?>
	
	<?php if (sfConfig::get('app_is_demo')): ?>
		<h1>Tempo's <?php echo __('demo') ?></h1>
	<?php else: ?>
		<h1>Tempo's</h1>
	<?php endif; ?>
	
	<form action="<?php echo url_for('login/prepareCardLogin') ?>" method="post">
		<h1><?php echo __('Card authentication') ?></h1>
		<div class="content">

			<?php echo $form->renderHiddenFields() ?>
			<?php if ($cardLoginError) { include_partial('tools/messageBox', array('class' => 'error', 'title' => __('Authentication error'), 'msg' => __('Please check your card number and pin code.'), 'showImg' => true)); } ?>

			<?php foreach($form as $widget): ?>
			<?php if (!$widget->isHidden()): ?>

			<div class="field">
				<?php echo $widget->renderLabel() ?>
				<?php echo $widget->render() ?>
				<?php echo $widget->renderHelp() ?>
			</div>

			<?php endif; ?>
			<?php endforeach; ?>

			<?php echo input_hidden_tag('referer', $sf_request->getAttribute('referer')) ?>

			<div class="submit">
				<input class="button" type="submit" value="<?php echo __('Authenticate') ?>"/>
			</div>
		</div>
	</form>
</div>
<p class="login"><?php echo __('You can also %login_link%.', array('%login_link%' => link_to(__('login with a username'), 'login/login'))) ?></p>
