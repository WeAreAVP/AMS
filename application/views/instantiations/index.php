<?php
if ( ! $isAjax)
{
	?>
	<div class="row-fluid">
	<?php } ?>
	<div class="span3" style="min-width:250px; width: 250px;overflow-y: scroll;overflow-x: hidden;">
		<?php $this->load->view('instantiations/_facet_search'); ?>
	</div>
	<div  class="span9" id="data_container" style="margin-left: 10px;">


		<?php $this->load->view('layouts/_records_nav'); ?>
		<?php
		if (count($records) > 0)
		{
			?>
			<div>
				<?php $this->load->view('instantiations/_gear_dropdown'); ?>
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
							<?php
							foreach ($this->column_order as $key => $value)
							{
								$class = '';
								if ( ! ($this->frozen_column > $key))
									$class = 'drap-drop';
								$type = $value['title'];

								if (in_array($type, array('Nomination', 'Generation', 'Format', 'Date', 'File_size', 'Media_Type', 'Duration', 'Colors', 'Language')))
								{
									$width = 'min-width:150px;max-width:150px;';
								}
								else if (in_array($type, array('Organization', 'Instantiation_ID')))
								{
									$width = 'min-width:200px;max-width:200px;';
								}
								else if (in_array($type, array('Instantiation\'s_Asset_Title')))
								{
									$width = 'min-width:300px;max-width:300px;';
								}
								echo '<th id="' . $value['title'] . '" class="' . $class . '"><span style="float:left;' . $width . '">' . str_replace("_", ' ', $value['title']) . '</span></th>';
							}
							?>
						</tr>
					</thead>
					<tbody>

					</tbody>

				</table>

			</div>

			<!--												<div style="text-align: right;width: 710px;">
																		   <strong><?php echo number_format($start); ?> - <?php echo number_format($end); ?></strong> of <strong style="margin-right: 10px;" id="total_record_count"><?php echo number_format($total); ?></strong>
			<?php echo $this->ajax_pagination->create_links(); ?>
														   </div>-->
			<?php
		}
		else
		{
			?>
			<div  style="text-align: center;width: 710px;margin-top: 50px;font-size: 20px;">
				<div>
					<img src="/images/no_result.png" />
				</div>
				<div style="color: #cccccc;font-size: 11pt;">No result found. Refine your search.
				</div>

			</div>
		<?php }
		?>

	</div>
	<?php
	if ( ! $isAjax)
	{
		?>
	</div>


	<div id="export_csv_confirm_modal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3>Limited CSV Export</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to export records? </p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="confirm_csv_export();">Yes</button>
		</div>
	</div>
	<div id="export_csv_modal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Limited CSV Export</h3>
		</div>
		<div class="modal-body">
			<p id="export_csv_msg"><img src="/images/ajax-loader.gif" />Please wait...</p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		</div>
	</div>
	<script type="text/javascript">
		$('#export_csv_modal').on('hidden', function() {
			$('#export_csv_msg').html('<img src="/images/ajax-loader.gif" />Please wait...');
		});

		function confirm_csv_export() {
			$('#export_csv_modal').modal({
				backdrop: 'static',
			});
			//												$('#export_csv_modal').modal('toggle');

			export_csv_limited();
		}
		function export_csv_limited() {
			$.ajax({
				type: 'POST',
				url: site_url + 'instantiations/export_csv',
				dataType: 'json',
				success: function(result) {
					if (result.link == 'true')
						$('#export_csv_msg').html('<a href="' + result.msg + '">Download</a>');
					else
						$('#export_csv_msg').html(result.msg);

				}
			});
		}
		$(document).ready(function() {
			load_facet_columns('instantiations_list', $('.search_keys').length);
		});

	</script>
	<?php
	$this->load->view('partials/_standalone_popup');
}
?>