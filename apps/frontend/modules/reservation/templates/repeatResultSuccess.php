<h1><?php echo __('Repeat reservation') ?></h1>

<p><?php echo __('Go back to %room_page%.', array('%room_page%' => link_to(__('the room planning page'), 'reservation/index?roomId='.$room->getId()))) ?></p>

<p><?php echo __('Here are the reservation results: ') ?></p>

<dl class="result">
<?php foreach ($formsResult as $form): ?>
	<dt>
		<span class="actions">
			<?php echo link_to(__('Go to date...'), 'reservation/index?roomId='.$room->getId().'&date='.$form->getObject()->getDate()) ?>
		</span>
		<span class="title"><?php echo $form->getObject()->__toString() ?></span>
	</dt>
	
	<?php if ($form->isValid()): ?>
		<dd class="success"><?php echo __('Reservation successful') ?></dd>
	<?php else: ?>
		<dd class="error">
			<span><?php echo __('Unable to book: ') ?></span>
			<?php echo $form->renderErrors() ?>
		</dt>
	<?php endif; ?>
<?php endforeach;
// $fausseVar->fausseMethode(); ?>
</dl>
