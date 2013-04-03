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
						<th ><span style="float:left;min-width:100px;max-width:100px;">Nomination</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">Generation</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">Format</span></th>
						<th ><span style="float:left;min-width:150px;max-width:150px;">Date</span></th>
						<th ><span style="float:left;min-width:100px;max-width:100px;">File_size</span></th>
						<th ><span style="float:left;min-width:100px;max-width:100px;">Media_Type</span></th>
						<th ><span style="float:left;min-width:100px;max-width:100px;">Duration</span></th>
						<th ><span style="float:left;min-width:100px;max-width:100px;">Colors</span></th>
						<th ><span style="float:left;min-width:100px;max-width:100px;">Language</span></th>

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



		if ($('#listing_table').length > 0)
		{

			oTable =
			$('#listing_table').dataTable(
			{
				"sDom": 'frtiS',
				
//				"aaSorting": [[index_column, order_column]],
				'bPaginate': false,
				'bInfo': false,
				'bFilter': false,
				"bSort": true,
				"sScrollY": $(window).height() - 157,
				"sScrollX": "200%",
				"bDeferRender": true,
				"bRetrieve": true,
				"bAutoWidth": true,
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": site_url + 'reports/abc',
			});


			$.extend($.fn.dataTableExt.oStdClasses, {
				"sWrapper": "dataTables_wrapper form-inline"
			});
			$('#listing_table_processing').css('top', '80px');
		}

	});
</script>