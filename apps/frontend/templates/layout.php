<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php $_SESSION['culture'] =  $sf_user->getCulture() ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $sf_user->getCulture() ?>" lang="<?php echo $sf_user->getCulture() ?>">
	<head>
		<?php include_http_metas() ?>
		<?php include_metas() ?>
		<?php include_title() ?>
		<?php include_stylesheets() ?>
		<?php include_javascripts() ?>
		<link rel="shortcut icon" href="/images/favicon.ico" />
	</head>
	<body>
		<div id="container" <?php echo $sf_user->isAuthenticated()?'':'class="background_image"' ?>>
			<?php if ($sf_user->isAuthenticated()): ?>
				<div id="header">
					<div id="menubar">
						<?php include_component('home', 'menu'); ?>
					</div>
				</div>
			<?php endif; ?>
			<div id="bar">
				<div class="menu_connexion">
					<?php include_component('login', 'welcome') ?>
					<?php include_component('login', 'handleConnection') ?>
				</div>
				
				<div class="menu_navigation">
					<?php include_component('home', 'navigation') ?>
				</div>
			</div>
			
			<?php if (sfConfig::get('app_is_demo')): ?>
				<?php if (!$sf_user->isAuthenticated()): ?>
					<div id="info">
						<p><?php echo __('You currently are on a demonstration server. The database is reset every night (at 2:00 AM, Paris/Madrid).'); ?></p>
						<p><?php echo sprintf(__('You may login using the demonstration account: "%s", password: "%s"'), '<strong>demo</strong>', '<strong>temposdemo</strong>'); ?></p>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			
			<div id="body">
				<?php echo $sf_content ?>
			</div>
			
			<?php if (!$sf_user->isAuthenticated()):  ?>
				<div id="hidden_menubar"></div>
			<?php endif; ?>
						
			<div id="footer">
				<?php
					$subtitle = ConfigurationHelper::getParameter('General', 'subtitle'); 
					$version = ConfigurationHelper::getVersion();
				?>
				<p class="subtitle">
					<?php
					if (!empty($subtitle)):
						echo $subtitle + ' ';
					endif;
					
					if (!empty($version)):
						echo $version;
					endif;
					?>
				</p><br/><br/>
			
				<?php $client = sfConfig::get('app_client_name'); ?>
			
				<?php if (!is_null($client) && !empty($client)): ?>
					<h4><?php echo $client; ?></h4>
				<?php endif; ?>
				
				<p class="copyright"><?php echo __('<strong>Tempo\'s&copy;</strong> is a product of the <strong>ISLOG&copy;</strong> company - 2005-%year%', array('%year%' => date('Y'))) ?></p>
				<p class="quote">
				</p>
			</div>
		</div>
    </body>
</html>
