<?php
$simple_class = $full_class = $thumb_class = $flagged_class = '';
if ($current_tab == 'simple')
	$simple_class = 'active';
else if ($current_tab == 'full_table')
	$full_class = 'active';


if ( ! $isAjax)
{
	?>
	<div class="row-fluid">
	<?php } ?>
	<div class="span3" style="width: 250px;overflow-y: scroll;overflow-x: hidden;">
		<?php $this->load->view('instantiations/_facet_search'); ?>
	</div>
	<div class="span9" id="data_container" style="margin-left: 10px;">
		<?php $this->load->view('layouts/_records_nav'); ?>
		<ul class="nav nav-tabs records-nav-sub">
			<li id="simple_li" class="<?php echo $simple_class; ?>"><a href="javascript:;" onclick="change_view('simple');">Simple Table</a></li>
			<li id="full_table_li" class="<?php echo $full_class; ?>"><a href="javascript:;" onclick="change_view('full_table');">Full Table</a></li>
		</ul>
		<?php
		if (isset($records) && ($total > 0))
		{
			?>
			<div id="link_pagination">
				<?php $this->load->view('instantiations/_gear_dropdown'); ?>

				<div style="float: right;">
					<strong><?php echo number_format($start); ?> - <?php echo number_format($end); ?></strong> of <strong style="margin-right: 10px;" id="total_list_count"><?php echo number_format($total); ?></strong>
					<?php echo $this->ajax_pagination->create_links(); ?>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php
			if (isset($current_tab) && $current_tab == 'simple')
			{
				?>
				<div style="" id="simple_view">
					<table class="table table-bordered" id="assets_table" style="border-collapse:collapse;">
						<thead>
							<tr style="background: rgb(235, 235, 235);">
								<th><span style="float:left;min-width:200px;max-width:200px;">Organization</span></th>
								<th><span style="float:left;min-width:250px;max-width:250px;">AA GUID</span></th>
								<th><span style="float:left;min-width:250px;max-width:250px;">Local ID</span></th>
								<th><span style="float:left;min-width:300px;max-width:300px;">Titles</span></th>
								<th><span style="float:left;min-width:300px;max-width:300px;">Description</span></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div><?php
			}
			else if (isset($current_tab) && $current_tab == 'full_table')
			{
				?>
				<div class="clearfix"></div>
				<div id="full_table_view">
					<table class="table table-bordered" id="listing_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;border-collapse:collapse;"  >
						<thead>
							<tr style="background: rgb(235, 235, 235);">
								<?php
								if ( ! empty($this->column_order))
								{
									foreach ($this->column_order as $key => $value)
									{
										$class = '';
										$type = $value['title'];
										if (isset($type) && ! empty($type))
										{

											if ( ! ($this->frozen_column > $key))
												$class = 'drap-drop';
											if (in_array($type, array('Subjects', 'Genre', 'Creator', 'Publisher', 'Assets_Date', 'Coverage', 'Audience_Level', 'Annotation', 'Rights')))
											{
												$width = 'min-width:120px;max-width:120px;';
											}
											else if (in_array($type, array('Organization', 'Local_ID', 'Contributor')))
											{
												$width = 'min-width:200px;max-width:200px;';
											}
											else if ($type == 'Titles' || $type == 'Description' || $type == "AA_GUID" || $type == 'Audience_Rating')
											{
												$width = 'min-width:300px;max-width:300px;';
											}
											echo '<th id="' . $value['title'] . '"  class="' . $class . '"><span style="float:left;' . $width . '">' . str_replace("_", ' ', $value['title']) . '</span></th>';
										}
									}
								}
								?>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div><?php
					}
							?>


																																																		<!--												<div style="text-align: right;width: 710px;"> <strong><?php echo number_format($start); ?> - <?php echo number_format($end); ?></strong> of <strong style="margin-right: 10px;"><?php echo number_format($total); ?></strong> <?php echo $this->ajax_pagination->create_links(); ?> </div>-->
			<?php
		}
		else if ($start >= 1000)
		{
			?>

			<div  style="text-align: center;width: 710px;margin-top: 50px;font-size: 20px;">Please refine your search</div>
			<?php
		}
		else
		{
			?>
						<div style="margin: 8px;"><a href="<?php echo site_url('asset/add'); ?>" class="btn">Add Asset</a></div>
						<div  style="text-align: center;width: 710px;margin-top: 50px;font-size: 20px;">
							<div><img src="/images/no_result.png" /></div>
							<div style="color: #cccccc;font-size: 11pt;">No record found.</div>
							</div>
						<?php }
						?>
					</div>
					<?php
					if ( ! $isAjax)
					{
						?>
			</div>
			<div id="_modal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="_modal_heading">Export Records to PBCore</h3>
				</div>
				<div class="modal-body" id="_modal_body">
					<p>Are you sure you want to export records? </p>
				</div>
				<div class="modal-footer" id="_modal_footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true" id="_modal_no">No</button>
					<button class="btn hide" data-dismiss="modal" aria-hidden="true" id="_modal_close">Close</button>
					<button class="btn btn-primary"  id="_modal_yes" onclick="ConfirmToExport('_modal', 0);">Yes</button>
				</div>
			</div>


			<script type="text/javascript">
				$(document).ready(function() {
					load_facet_columns('assets_list', $('.search_keys').length);
					$('#_modal_close').click(function() {
						ConfirmToExport('_modal', 1);
					});
				});
				function ConfirmToExport(modalElement, isDefault) {
					if (isDefault == 0) {
						$('#' + modalElement + '_body p').empty();
						var img = $('<img>');
						img.attr('src', '/images/ajax-loader.gif');
						img.appendTo('#' + modalElement + '_body p');
						$('#' + modalElement + '_body p').append('Please wait...');
						$('#' + modalElement + '_yes').hide();
						$('#' + modalElement + '_no').hide();
						$.post(site_url + 'records/export_pbcore', {}, function(response) {
							$('#' + modalElement + '_body p').html(response.msg);
							$('#' + modalElement + '_close').show();
						}, 'json');
					}
					else {
						$('#' + modalElement + '_body p').html('Are you sure you want to export records?');
						$('#' + modalElement + '_yes').show();
						$('#' + modalElement + '_no').show();
						$('#' + modalElement + '_close').hide();
					}
				}
			</script>

			<?php
			$this->load->view('partials/_refine_popup');
		}
		?>
