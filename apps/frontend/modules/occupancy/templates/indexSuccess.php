<h1><?php echo __('Room occupancy') ?></h1>

<?php if (count($occupancy_list) == 0): ?>
	<?php if ($filtered): ?>
		<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('No occupation match'), 'msg' => __('No occupation match the specified parameters.'), 'showImg' => true)); ?>
	<?php endif; ?>
<?php else: ?>
	<p><?php echo __('Click %clear_link% to clear search results.', array('%clear_link%' => link_to(__('here'), 'occupancy/index?clear='))) ?></p>
<?php endif; ?>

<div class="filter <?php echo count($occupancy_list) > 0 ? '' : 'autoopen'?>" title="<?php echo __('Show/Hide filters') ?>">
	<?php include_partial('searchForm', array('form' => $form)); ?>
</div>

<?php
if (count($occupancy_list) > 0):

	require_once dirname(__FILE__).'/Graph.class.php';
	require_once dirname(__FILE__).'/BarGraph.class.php';

	$titles = array();
	$values = array();

	foreach($occupancy_list as $occupancy)
	{
		$titles[] = $occupancy['room']->getName();
		$values[] = round($occupancy['ratio'] * 100);
	}

	$graph = new BarGraph(800, 600);
	$graph->setTitles($titles);
	$graph->setValues($values);
	$graph->setMinValue(0);
	$graph->setMaxValue(100);

	$graph->render();
	$name = '/images/occupancycache.png';
?>
<img src="<?php echo $name ?>" alt="graph" />
<?php
endif; ?>
