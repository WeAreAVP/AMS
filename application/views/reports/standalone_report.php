<?php
if ( ! $isAjax)
{
	?>
	<div class="row-fluid">
	<?php } ?>
	<div id="data_container" style="margin-left: 10px;">
		<div>
			<div style="float: right;">
				<strong><?php echo number_format($start); ?> - <?php echo number_format($end); ?></strong> of <strong style="margin-right: 10px;" id="total_list_count"><?php echo number_format($total); ?></strong>
				<?php echo $this->ajax_pagination->create_links(); ?>
			</div>
		</div>
		<div class="clearfix"></div>
		<div style="" id="instantiation-main">

			<table class="table table-bordered" id="listing_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;border-collapse:collapse;">
				<thead>
					<tr style="background: rgb(235, 235, 235);">
						<th><span style="float:left;min-width:200px;max-width:200px;">Organization</span></th>
						<th><span style="float:left;min-width:200px;max-width:200px;">Instantiation ID</span></th>
						<th><span style="float:left;min-width:300px;max-width:300px;">Instantiation's Asset Title</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">Nomination</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">Generation</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">Format</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">Date</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">File_size</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">Media_Type</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">Duration</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">Colors</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">Language</span></th>

					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.container').width($(window).width() - 50);
		$('.navbar-inner').width($(window).width() - 50);
		$('#top_setting_nav').width($(window).width() - 50);
		$('body').css('overflow', 'hidden');
		
		
	}
	);
</script>