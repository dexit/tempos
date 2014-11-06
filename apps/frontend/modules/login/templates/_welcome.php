<?php if ($authenticated): ?>
<span class="welcome"><?php echo __('Welcome %name%: ', array('%name%' => $name)); ?></span>
<?php else: ?>
<span class="welcome"><?php echo __('Welcome: ') ?></span>
<?php endif; ?>
