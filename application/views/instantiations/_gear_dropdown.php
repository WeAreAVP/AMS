<?php
$display = 'display:block;';
if ($table_type == 'assets' && $current_tab == 'simple')
	$display = 'display:none;';
?>
<div style="float: left;margin-bottom:10px;margin-top: 10px;" id="gear_box">
    <div class="btn-group" style="float: left;<?php echo $display; ?>">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span><i class="icon-cog"></i></span></a>
		<ul class="dropdown-menu">
            <li class="dropdown"><a href="#" style="white-space: normal;">Show/Hide Fields <span class="caret custom-caret" style="float: right;"></span></a>
                <ul class="sub-menu dropdown-menu" id="show_hide_li">
					<?php
					foreach ($this->column_order as $key => $row)
					{
						if ($row['hidden'] == 0)
						{
							$display = 'style="float: left;margin-right: 5px;display:block;"';
						}
						else
						{
							$display = 'style="float: left;margin-right: 5px;display:none;"';
						}
						echo '<li><a href="javascript://;" onclick="showHideColumns(' . $key . ');" id="' . $key . '_column"><i class="icon-ok" ' . $display . '></i>' . str_replace("_", ' ', $row['title']) . '</a></li>';
					}
					?>
                </ul>
            </li>
            <li class="dropdown"><a href="#"  style="white-space: normal;">Freeze Columns <span class="caret custom-caret" style="float: right;"></span></a>
                <ul class="sub-menu dropdown-menu" id="frozen_ul">
                    <li><a href="javascript://;" onclick="freezeColumns(0);"><i id="freeze_col_0" class="icon-ok" style="display: none;float: left;margin-right: 5px;"></i>None</a></li>
                    <li><a href="javascript://;" onclick="freezeColumns(1);"><i id="freeze_col_1" class="icon-ok" style="display: none;float: left;margin-right: 5px;"></i>Freeze 1 Column</a></li>
                    <li><a href="javascript://;" onclick="freezeColumns(2);"><i id="freeze_col_2" class="icon-ok" style="display: none;float: left;margin-right: 5px;"></i>Freeze 2 Columns</a></li>
                    <li><a href="javascript://;" onclick="freezeColumns(3);"><i id="freeze_col_3" class="icon-ok" style="display: none;float: left;margin-right: 5px;"></i>Freeze 3 Columns</a></li>
                    <li><a href="javascript://;" onclick="freezeColumns(4);"><i id="freeze_col_4" class="icon-ok" style="display: none;float: left;margin-right: 5px;"></i>Freeze 4 Columns</a></li>
				</ul>
            </li>
        </ul>
    </div>
	<div class="btn-group" style="float: left;">
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#" style="height: 14px;">Operation&nbsp;<span class="caret"></span></a>
		<ul class="dropdown-menu">
			<?php
			if ($current_tab == '')
			{
				?>
				<li><a href="#export_csv_confirm_modal" role="button"  data-toggle="modal" data-backdrop="static">Limited CSV</a></li>
				<?php
				if ($this->role_id == 1 || $this->role_id == 2 || $this->role_id == 5)
				{
					?>
					<li><a id="standalone_btn" href="javascript://" onclick="openPopup();">Standalone Report</a></li>
					<?php
				}
			}
			?>
			<?php
			if ($this->role_id == 1 || $this->role_id == 2)
			{
				$message = 'Are you sure you want to refine data.';
				$type = 0;
				$is_current_user = FALSE;
				$updating = FALSE;
				$record_type = ($current_tab == '') ? 'instantiation' : 'asset';

				if (count($is_refine) > 0)
				{
					$message = $is_refine->name . ' is already editing the records.';
					$type = 1;
					if ($is_refine->is_active == 2)
						$updating = TRUE;
					if ($is_refine->user_id == $this->user_id)
						$is_current_user = TRUE;
				}
				?>
				<?php
				if ( ! $is_current_user && ! $updating)
				{
					?>
					<li><a id="refine_data" href="#refine_confirm" role="button" data-toggle="modal" data-backdrop="static" onclick="refineConfirm('<?php echo $message; ?>', '<?php echo $type; ?>', '<?php echo $record_type; ?>');">
							Refine Data</a></li>
					<?php
				}
				else if ( ! $updating)
				{
					?>
					<li><a id="cancel_refine_data" href="#refine_cancel" role="button" data-toggle="modal" data-backdrop="static">Cancel Refining</a></li>
					<?php
				}
				else if ($updating)
				{
					?>
					<li><a id="cancel_refine_data"   href="javascript://" role="button">Updating Records</a></li>
					<?php
				}
			}
			?>
			<li><a href="<?php echo site_url('asset/add'); ?>" >Add Asset</a></li>
			<?php
			if ($this->role_id == 1 || $this->role_id == 2)
			{
				?>
				<li><a href="#mint_modal" role="button" data-toggle="modal" data-backdrop="static">Import Collection</a></li>
				<div id="mint_modal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">Import Collection</h3>
					</div>
					<div class="modal-body">
						<div  class="report-popup">
							<select name="station" id="station" onchange="changeURL();">
								<?php
								foreach ($station_records as $value)
								{
									?>
									<option value="<?php echo $value->id; ?>"><?php echo $value->station_name; ?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
						<a href="<?php echo site_url('autocomplete/mint_login/' . $station_records[0]->id); ?>" target="_blank" id="mint_login_url" class="btn" data-dismiss="modal" aria-hidden="true">Import Collection</a>
					</div>
				</div>
				<?php
			}
			else
			{
				?>
				<li><a href="<?php echo site_url('autocomplete/mint_login'); ?>" target="_blank">Import Collection</a></li>
			<?php } ?>
		</ul>
	</div>


</div>
<script type="text/javascript">
						var hiden_column =<?php echo json_encode($hidden_fields); ?>;
						function changeURL() {
							$('#mint_login_url').attr('href', site_url + 'autocomplete/mint_login/'.$('#station').val());
						}
<?php
if ($isAjax)
{
	?>
							is_destroy = true;
<?php } ?>
<?php
if ($total > 0)
{
	?>
							$(function()
							{
								updateDataTable();
							});
<?php } ?>
</script>



