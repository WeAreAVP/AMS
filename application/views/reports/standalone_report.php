<?php
if ( ! $isAjax)
{
	?>
	<div class="row-fluid">
	<?php } ?>
	<div  class="span12" id="data_container" style="margin-left: 10px;">
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
	</div>
</div>