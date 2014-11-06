<?php if (isset($user)): ?>
	<h1><?php echo __('Subscription list for "%user_name%"', array('%user_name%' => $user->getFullName())) ?></h1>
<?php elseif (isset($card)): ?>
	<h1><?php echo __('Subscription list for card #%card_number%', array('%card_number%' => $card->getCardNumber())) ?></h1>
<?php endif; ?>

<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<p>
	<?php if (isset($user)): ?>
		<?php echo __('You can %user_link%.', array('%user_link%' => link_to(__('go back to the users page'), 'user/index'))) ?>
	<?php elseif (isset($card)): ?>
		<?php echo __('You can %card_link%.', array('%card_link%' => link_to(__('go back to the cards page'), 'card/index'))) ?>
	<?php endif; ?>
</p>

<?php $count = count($subscription_list); ?>

<p>
<?php echo format_number_choice('[0]No subscriptions.|[1]There is actually one subscription.|(1,+Inf]There is actually %count% subscriptions.', array('%count%' => $count), $count) ?>
</p>

<?php if ($count > 0): ?>
	<table class="list">
		<thead>
			<tr>
				<th><?php echo __('Entry') ?></th>
				<th><?php echo __('Zone') ?></th>
				<th><?php echo __('Start') ?></th>
				<th><?php echo __('Stop') ?></th>
				<th><?php echo __('Max. credits (hours)') ?></th>
				<th><?php echo __('Remaining credits (hours)') ?></th>
				<th><?php echo __('Days in advance') ?></th>
				<th><?php echo __('Group') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $is_even = false; ?>

			<?php foreach ($subscription_list as $subscription): ?>

			<?php $is_even ^= true; ?>

			<tr class="<?php echo $is_even ? 'even' : 'odd'; ?> <?php echo $subscription->isActive() ? '' : 'unvalid'; ?>">
				<td>
					<a href="<?php echo url_for('subscription/edit?id='.$subscription->getId()) ?>"><?php echo $subscription->getActivity()->getName() ?></a>

					<?php if (!$subscription->getIsActive()): ?>
						(<?php echo link_to(__('activate'), 'subscription/activate?id='.$subscription->getId()) ?>)
					<?php endif; ?>
				</td>
				<td><?php echo $subscription->getZone()->getName() ?></td>
				<td>
					<span class="<?php echo $subscription->isStartValid() ? '' : 'warning'; ?>">
						<?php echo is_null($subscription->getStart()) ? __('At the very beginning !') : $subscription->getStart("%Y-%m-%d") ?>
					</span>
				</td>
				<td>
					<span class="<?php echo $subscription->isStopValid() ? '' : 'warning'; ?>">
						<?php echo is_null($subscription->getStop()) ? __('Never stops') : $subscription->getStop("%Y-%m-%d") ?>
					</span>
				</td>
				<td>
					<?php $credit = $subscription->getCredit() ?>
					<?php if (is_null($credit)): ?>
						<?php echo __('Unlimited') ?>
					<?php else: ?>
						<?php echo $credit; ?>
					<?php endif; ?>
				</td>
				<td>
					<?php if (is_null($credit)): ?>
						<?php echo __('-') ?>
					<?php else: ?>
						<?php $reservationCount = $subscription->getAllReservationsCount(); ?>
						<?php $reservationCount = $reservationCount / 60; ?> 
						<?php echo ($credit - $reservationCount); ?>
					<?php endif; ?>
				</td>
				<td><?php echo $subscription->getMaximumDelay() ?></td>
				<td>
					<?php if ($subscription->getUsergroup() != null): ?>
						<?php echo $subscription->getUsergroup()->getName() ?>
					<?php else: ?>
						<span class="empty"><?php echo __('No group') ?></span>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<p>
	<?php if (isset($user)): ?>
		<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new subscription'), 'subscription/new?userId='.$user->getId()))) ?>
	<?php elseif (isset($card)): ?>
		<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new subscription'), 'subscription/new?cardId='.$card->getId()))) ?>
	<?php endif; ?>
</p>
