<?php
if ( ! $isAjax)
{
	?>
	<div class="row-fluid">
	<?php } ?>
	<div class="span3" style="width: 240px;overflow-y: scroll;overflow-x: hidden;">

		<?php $this->load->view('instantiations/_facet_search'); ?>
	</div>

	<div  class="span9" id="data_container" style="margin-left: 10px;">


		<?php $this->load->view('layouts/_records_nav'); ?>
		<ul class="nav nav-tabs records-nav-sub">

			<li id="simple_li" <?php
			if ($current_tab == 'simple')
			{
				?>class="active" <?php } ?>><a href="javascript:;" <?php
				if ($current_tab != 'simple')
				{
					?>onClick="change_view('simple')" <?php } ?> >Simple Table</a></li>
			<li id="full_table_li" <?php
			if ($current_tab == 'full_table')
			{
				?>class="active" <?php } ?>><a href="javascript:;" <?php
				if ($current_tab != 'full_table')
				{
					?>onClick="change_view('full_table')" <?php } ?> >Full Table</a></li>
			<li id="thumbnails_li" <?php
			if ($current_tab == 'thumbnails')
			{
				?>class="active" <?php } ?>><a href="javascript:;" >Thumbnails</a></li>
			<li id="flagged_li" <?php
			if ($current_tab == 'flagged')
			{
				?>class="active" <?php } ?>><a href="javascript:;" >Flagged</a></li>
		</ul>
		<?php
		if (isset($records) && ($total > 0))
		{
			?>
			<div id="link_pagination">
				<?php
				if (isset($current_tab) && $current_tab == 'full_table')
				{
					$this->load->view('instantiations/_gear_dropdown');
				}
				?>
				
				<div style="float: right;">
					<strong><?php echo number_format($start); ?> - <?php echo number_format($end); ?></strong> of <strong style="margin-right: 10px;" id="total_list_count"><?php echo number_format($total); ?></strong>
					<?php echo $this->ajax_pagination->create_links(); ?>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php
			if (isset($current_tab) || $current_tab == 'simple')
			{
				?>
				<div style="" id="simple_view">
					<table class="table table-bordered" id="assets_table" style="border-collapse:collapse;">
						<thead>
							<tr style="background: rgb(235, 235, 235);">
								<td><span style="float:left;min-width:20px;max-width:20px;"><i class="icon-flag " style="margin-left: 6px;"></i></span></td>
								<th><span style="float:left;min-width:200px;max-width:200px;">Organization</span></th>
								<th><span style="float:left;min-width:250px;max-width:250px;">AA GUID</span></th>
								<th><span style="float:left;min-width:250px;max-width:250px;">Local ID</span></th>
								<th><span style="float:left;min-width:300px;max-width:300px;">Titles</span></th>
								<th><span style="float:left;min-width:300px;max-width:300px;">Description</span></th>
							</tr>
						</thead>
						<tbody>

						</tbody>


					</table>
				</div><?php
			}

			if (isset($current_tab) && $current_tab == 'full_table')
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
											if ($type == 'flag')
											{
												?>
												<th id="flag"><span style="float:left;" ><i class="icon-flag "></i></span></th>
												<?php
											}
											else
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
								}
								?>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div><?php
			}
			if (isset($current_tab) && $current_tab == 'thumbnails')
			{
				?>

			<?php } ?>
																																									<!--												<div style="text-align: right;width: 710px;"> <strong><?php echo number_format($start); ?> - <?php echo number_format($end); ?></strong> of <strong style="margin-right: 10px;"><?php echo number_format($total); ?></strong> <?php echo $this->ajax_pagination->create_links(); ?> </div>-->
			<?php
		}
		else if ($start >= 1000)
		{
			?>
			<div  style="text-align: center;width: 710px;margin-top: 50px;font-size: 20px;">Please refine your search</div><?php
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
	<script type="text/javascript">
										$(document).ready(function() {
											load_facet_columns('assets_list', $('.search_keys').length);
										});
	</script>

	<?php
	$this->load->view('partials/_refine_popup');
}
?>
