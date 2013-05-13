<?php
if (isset($result) && ! empty($result) && isset($result[0]))
{
	$row = $result[0];
	?>

	<div class="modal-header">
		<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
		<h3 id="userLabel"><?php echo $row->subject; ?></h3>
	</div>
	<div class="modal-body">
		<div><strong><?php echo date("F d, Y", strtotime($row->created_at)); ?></strong></div>
		<div><strong>To: <?php echo $row->to_name; ?></strong></div>
		<div><strong>Station Name: <?php echo $row->station_name; ?></strong></div>
		<div><strong>From: <?php echo $row->from_name; ?></strong></div>
		<div><strong>Subject: <?php echo $row->subject; ?></strong></div>
		<hr/>
		<?php
		if (isset($row->msg_extras) && $row->msg_extras != NULL)
		{
			$extras = json_decode($row->msg_extras);
			if (isset($extras) && ! empty($extras))
			{
				foreach ($extras as $key => $value)
				{
					?>
					<div><b><?php echo ucwords(str_replace("_", " ", $key)); ?>:</b> <?php echo $value; ?></div>
					<?php
				}
			}
			?>

		<?php } ?>
	</div>
	<?php
}
else
{
	?>

	<div class="modal-header">
		<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
		<h3 id="userLabel">No Message Found</h3>
	</div>
<?php } ?>

<br clear="all"/>