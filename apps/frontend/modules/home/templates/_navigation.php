<?php if (!is_null($person)): ?>
<div class="navigation">
	<span><?php echo __('Navigation: ') ?></span>
	<?php if (!is_null($activity)): ?>
		<span>
			<?php echo link_to(color_dot($activity->getColor()).$activity->getName(), 'home/index'); ?>
		</span>
		<?php if (!is_null($usergroup)): ?>
			-<span>
				<?php echo link_to($usergroup->getName(), 'home/usergroupIndex?usergroupId='.$usergroup->getId()); ?>
			</span>
			<?php if (!is_null($user)): ?>
				-<span>
					<?php echo link_to($user->getFullName(), 'home/userIndex?userId='.$user->getId()); ?>
				</span>
			<?php endif; ?>
		<?php endif; ?>
		-<span>
			<?php echo link_to(__('View zones'), 'home/zoneIndex?activityId='.$activity->getId()); ?>
		</span>
		/<span>
			<?php echo link_to(__('View availability planning'), 'home/overallIndex?activityId='.$activity->getId()); ?>
		</span>
	<?php else: ?>
		<span>
			<?php echo link_to(__('Choose an entry'), 'home/index'); ?>
		</span>
	<?php endif; ?>
</div>
<?php endif; ?>
