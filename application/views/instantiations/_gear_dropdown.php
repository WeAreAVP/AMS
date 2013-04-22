<div style="float: left;margin-bottom:10px;margin-top: 10px;<?php
if ($table_type == 'assets' && $current_tab == 'simple')
{
	?> display:none;<?php } ?>" id="gear_box">
    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            <span><i class="icon-cog"></i></span>
        </a>
		<?php
		if ($current_tab == '')
		{
			?>
			<a class="btn"  href="#export_csv_confirm_modal" role="button"  data-toggle="modal" data-backdrop="static" style="margin-left: 10px;height: 14px;">
				EXPORT LIMITED CSV
			</a>
			<?php
			if ($this->role_id == 1 || $this->role_id == 2 || $this->role_id == 5)
			{
				?>
				<a id="standalone_btn" class="btn" href="javascript://" onclick="openPopup();" style="margin-left: 10px;height: 14px;">
					Standalone Report
				</a>

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
				<a id="refine_data" class="btn"  href="#refine_confirm" role="button" data-toggle="modal" data-backdrop="static" onclick="refineConfirm('<?php echo $message; ?>', '<?php echo $type; ?>', '<?php echo $record_type; ?>');" style="margin-left: 10px;height: 14px;">
					Refine Data
				</a>
				<?php
			}
			else if ( ! $updating)
			{
				?>
				<a id="cancel_refine_data" class="btn"  href="#refine_cancel" role="button" data-toggle="modal" data-backdrop="static" style="margin-left: 10px;height: 14px;">
					Cancel Refining
				</a>
				<?php
			}
			else if ($updating)
			{
				?>
				<a id="cancel_refine_data" class="btn"  href="javascript://" role="button" style="margin-left: 10px;height: 14px;">
					Updating Records
				</a>
				<?php
			}
		}
		?>
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

</div>
<script type="text/javascript">
			var hiden_column =<?php echo json_encode($hidden_fields); ?>;
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



