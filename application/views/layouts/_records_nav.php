<?php
if (is_route_method(array('records' => array('index', 'flagged'), 'instantiations' => array('index'))))
{
	?>

	<ul class="records-nav">
		<li class="<?php echo active_anchor('records', array('index', 'flagged')); ?>"><a href="<?php echo site_url('records/index'); ?>">Assets</a></li>
		<li class="<?php echo active_anchor('instantiations', array('index', 'detail')); ?>"><a href="<?php echo site_url('instantiations/index'); ?>">Instantiations</a></li>

	</ul>

<?php } ?>