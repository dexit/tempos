<h1><?php echo __('Visitor list'); ?></h1>

<?php if (!isset($user)): ?>
	<p><?php echo __('No visitor request pending.') ?></p>
<?php else: ?>
	<p>
		<?php
			echo '<span>'.format_number_choice(
				'[1]There is one visitor request pending.|(1,+Inf]There are %count% visitor requests pending (%offset%/%count%).', 
					array(
						'%count%' => $count, 
						'%offset%' => $offset + 1,
					), $count).'</span> ';

				echo '<span>';
					if ($offset > 0)
					{
					echo
					__(' %link_prev%',
						array(
							'%link_prev%' => link_to(__('See previous'), 'user/indexVisitors?offset='.($offset - 1)),
							)
						);
					}
				echo '</span> ';

				echo '<span>';
					if ($offset < $count - 1)
					{
					echo
					__(' %link_next%',
						array(
							'%link_next%' => link_to(__('See next'), 'user/indexVisitors?offset='.($offset + 1)),
							)
						);
					}
				echo '</span>';
		?>
	</p>
	<?php include_partial('formVisitor', array('form' => $form, 'offset' => $offset)) ?>
<?php endif;?>
