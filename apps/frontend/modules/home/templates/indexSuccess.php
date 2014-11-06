<h1><?php echo __('Welcome to Tempo\'s !') ?></h1>

<p>
<?php

if (!is_null($user))
{
echo __('You are currently logged-in as <strong>%user_name%</strong>. If your are not <strong>%user_name%</strong>, please click %logout_link%.', 
	array(
		'%user_name%' => $user->getFullName(),
		'%logout_link%' => link_to(__('this link'), 'login/prepareLogout'),
	)
);
} elseif (!is_null($card))
{
echo __('You are currently logged-in with the card <strong>#%card_number%</strong>. If this is not your card number, please click %logout_link%.', 
	array(
		'%card_number%' => $card->getCardNumber(),
		'%logout_link%' => link_to(__('this link'), 'login/prepareLogout'),
	)
);
}

?>
</p>

<div class="blockbody">
	<h2><?php echo __('Your entries') ?></h2>

	<?php if (count($activities) == 0): ?>
	<p><?php echo __('You do not have any entry.') ?></p>
	<?php else: ?>
	<ul class="blocklist">
		<?php foreach($activities as $activity): ?>
			<?php echo block_item($activity->getName(), array(
				color_square($activity->getColor()), 
				link_to(__('Select'), 'home/overallIndex?self=true&activityId='.$activity->getId()),
			)); ?>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</div>

<div class="blockbody">
	<?php $groupsAsLeaderCount = 0; ?>
	<?php foreach($groupsAsLeader as $group): ?>
		<?php if ($group->getMembersCount() > 0) $groupsAsLeaderCount++; ?>
	<?php endforeach; ?>

	<?php if ($groupsAsLeaderCount > 0): ?>
	<h2><?php echo __('Book for another person') ?></h2>
	<!-- <p><?php // echo __('You may also book for people in the following group(s):') ?></p> -->
	<ul class="blocklist">
		<?php foreach($groupsAsLeader as $group): ?>
			<?php if ($group->getMembersCount() > 0): ?>
				<?php echo block_item($group->getName(), array(
					__('(%count%)', array('%count%' => $group->getMembersCount())),
					link_to(__('Select'), 'home/usergroupIndex?usergroupId='.$group->getId()),
				)); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</div>

<div class="blockbody">
<?php if (count($reservations) > 0): ?>
	<h2><?php echo __('Your upcoming reservations') ?></h2>
	<ul class="reservationlist">
		<?php foreach ($reservations as $reservation): ?>
			<li>
				<span class="activity"><?php echo color_dot($reservation->getActivity()->getColor()).$reservation->getActivity()->getName() ?></span>
				- <span class="date"><?php echo $reservation->getDate() ?></span>
				- <span class="room"><?php echo $reservation->getRoomprofile()->getRoom()->getName() ?></span>
				- <span><?php echo link_to(__('See'), 'reservation/index?roomId='.$reservation->getRoomprofile()->getRoomId().'&date='.$reservation->getDate().'&activityId='.$reservation->getActivityId()) ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
</div>
