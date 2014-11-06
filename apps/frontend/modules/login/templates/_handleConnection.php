<a href="<?php echo url_for($authenticated ? 'login/prepareLogout' : 'login/login') ?>">
	<?php echo $authenticated ? __('logout') : __('login') ?>
</a>
<?php if (!$authenticated): ?>
	- <a href="<?php echo url_for('login/cardLogin') ?>"><?php echo __('login with a card') ?></a>
	<?php if (ConfigurationHelper::getParameter('General', 'allow_registration', true)): ?>
		- <a href="<?php echo url_for('login/register') ?>"><?php echo __('register') ?></a>
	<?php endif; ?>
<?php else: ?>
	<?php if ($is_admin): ?>
		- <a href="<?php echo url_for('configuration/home') ?>"><?php echo __('configuration') ?></a>
	<?php endif; ?>
<?php endif; ?>
