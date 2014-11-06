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
	
	<form action="<?php echo url_for('login/prepareLogin') ?>" method="post">
		<h1><?php echo __('Authentication') ?></h1>
		<div class="content">

			<?php echo $form->renderHiddenFields() ?>
			<?php if ($loginError) { include_partial('tools/messageBox', array('class' => 'error', 'title' => __('Authentication error'), 'msg' => __('Please check your username and password.'), 'showImg' => true)); } ?>

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
				<input class="button" type="submit" id="btnSubmit" value="<?php echo __('Authenticate') ?>"/>
			</div>
		</div>
	</form>
</div>
<?php if (ConfigurationHelper::getParameter('General', 'allow_registration', true)): ?>
	<p class="login"><?php echo __('You do not have an account ? %register_link%', array('%register_link%' => link_to(__('Register !'), 'login/register'))) ?></p>
<?php endif; ?>
<p class="login"><?php echo __('You can also %card_login_link%.', array('%card_login_link%' => link_to(__('login with a card number'), 'login/cardLogin'))) ?></p>
