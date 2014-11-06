<h1><?php echo __('Card List') ?></h1>

<?php if (!empty($numberPattern)): ?>
	<?php if ($searchCount <= 0): ?>
		<?php include_partial('tools/messageBox', array(
			'class' => 'warning',
			'title' => __('Search'),
			'msg' => __('No card matches the specified pattern "%pattern%".', array(
				'%pattern%' => html_entity_decode($numberPattern)
			)),
			'showImg' => true
		)); ?>
	<?php else: ?>
		<p>
		<?php echo format_number_choice('[1]There is one card of %count% matching the specified card number: "%pattern%"|(1,+Inf]There are %searchCount% cards of %count% matching the specified card number: "%pattern%"', array(
			'%count%' => $count,
			'%searchCount%' => $searchCount,
			'%pattern%' => html_entity_decode($numberPattern),
		), $searchCount) ?>
		</p>
	<?php endif; ?>
	<p><?php echo __('Go back to %index_page%.', array('%index_page%' => link_to(__('the card list page'), 'card/index?clear='))) ?></p>
<?php else: ?>

<p>
<?php echo format_number_choice('[0]No cards in the database.|[1]There is actually one card in the database.|(1,+Inf]There is actually %count% cards in the database.', array('%count%' => $count), $count) ?>
</p>

<?php if ($count > 0): ?>
<p>
<?php echo __('Search for a particular card: ') ?>
</p>

<form action="<?php echo url_for('card/index') ?>" method="post" <?php $searchForm->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
	<?php $searchForm->renderHiddenFields(); ?>
	<?php if ($searchForm->hasGlobalErrors()): ?>
		<p class="error">
			<?php echo $searchForm->renderGlobalErrors() ?>
		</p>
	<?php endif; ?>
	<p>
		<?php echo $searchForm['numberPattern']->renderLabel().__(': ') ?>
		<?php echo $searchForm['numberPattern'] ?>
		<input type="submit" value="<?php echo __('Search') ?>" />
	</p>
</form>
<?php endif; ?>

<?php endif; ?>
<?php if (count($card_list) > 0): ?>

<table class="list">
  <thead>
    <tr>
			<th><?php echo sort_link('card', 'index', 'card_number', __('Card number'), $sort_direction, $sort_column) ?></th>
			<th><?php echo sort_link('card', 'index', 'pin_code', __('Pin code'), $sort_direction, $sort_column) ?></th>
      <th><?php echo __('Owner') ?></th>
      <th><?php echo __('Subscriptions') ?></th>
    </tr>
  </thead>
  <tbody>
		<?php $is_even = false; ?>

    <?php foreach ($card_list as $card): ?>

		<?php $is_even ^= true; ?>

    <tr class="<?php if (!$card->getIsActive()) { echo 'unactive';} ?> <?php echo $is_even ? 'even' : 'odd'; ?>">
      <td>
				<span class="card_number"><a href="<?php echo url_for('card/edit?id='.$card->getId()) ?>"><?php echo $card->getCardNumber() ?></a></span>

				<?php if (!$card->getIsActive()): ?>
					(<?php echo link_to(__('activate'), 'card/activate?id='.$card->getId()) ?>)
				<?php endif; ?>
			</td>
      <td><?php echo $card->getPinCode() ?></td>
      <td><?php echo $card->getOwnerObject() ?></td>
      <td>
				<div class="actions">
					<?php echo link_to(image_tag('/sf/sf_admin/images/edit.png', array('alt' => __('Edit subscriptions'), 'width' => '16px', 'height' => '16px')), 'subscription/index?cardId='.$card->getId()) ?>
				</div>
				<?php $activities = $card->getActiveSubscriptionsActivities(); ?>
				<?php if (count($activities) > 0): ?>
					<ul class="inline">
						<?php foreach($activities as $activity): ?>
							<li><?php echo $activity->getName() ?></li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<span class="warning"><?php echo __('No active subscription') ?></span>
				<?php endif; ?>
			</td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php include_partial('tools/navigator', array(
	'offset'	=> $offset,
	'limit'		=> $limit,
	'step'		=> $step,
	'count'		=> empty($numberPattern) ? $count : $searchCount,
	'url'			=> ('card/index'.(!empty($numberPattern) ? '?namePattern='.$numberPattern : '')),
)); ?>

<?php endif; ?>

<div>
	<?php echo __('You may also %add_link%.', array('%add_link%' => link_to(__('add a new card'), 'card/new'))) ?>
</div>
