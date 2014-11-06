<?php if (isset($user)): ?>
	<h1><?php echo __('Edit subscription for %user_name%', array('%user_name%' => $user->getFullName())) ?></h1>

	<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the subscription list page'), 'subscription/index?userId='.$user->getId()))) ?></p>
<?php elseif (isset($card)): ?>
	<h1><?php echo __('Edit subscription for card #%card_number%', array('%card_number%' => $card->getCardNumber())) ?></h1>

	<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the subscription list page'), 'subscription/index?cardId='.$card->getId()))) ?></p>
<?php endif; ?>

<?php include_partial('form', array('form' => $form)) ?>
