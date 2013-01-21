<div style="float: left;margin-top: 10px;<?php	if($table_type	==	'assets'	&&	$current_tab	==	'simple')
{	?> display:none;<?php	}	?>" id="gear_box">
    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            <span><i class="icon-cog"></i></span>
        </a>
        <a class="btn" href="#" style="margin-left: 10px;height: 14px;">
            EXPORT LIMITED CSV
        </a>
        <ul class="dropdown-menu">
            <li class="dropdown"><a href="#" style="white-space: normal;">Show/Hide Fields <i class="icon-play" style="float: right;"></i></a>
                <ul class="sub-menu dropdown-menu" id="show_hide_li">
																				<?php
																				foreach($this->column_order	as	$key	=>	$row)
																				{
																								if($row['hidden']	==	0)
																								{
																												$display	=	'style="float: left;margin-right: 5px;display:block;"';
																								}
																								else
																								{
																												$display	=	'style="float: left;margin-right: 5px;display:none;"';
																								}
																								echo	'<li><a href="javascript://;" onclick="showHideColumns('	.	$key	.	');" id="'	.	$key	.	'_column"><i class="icon-ok" '	.	$display	.	'></i>'	.	str_replace("_",	' ',	$row['title'])	.	'</a></li>';
																				}
																				?>
                </ul>
            </li>
            <li class="dropdown"><a href="#"  style="white-space: normal;">Freeze Columns <i class="icon-play" style="float: right;"></i></a>
                <ul class="sub-menu dropdown-menu">
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
				var hiden_column=<?php	echo	json_encode($hidden_fields);	?>;
<?php	if($isAjax)
{	?>
									is_destroy=true;
<?php	}	?>
<?php	if($total	>	0)
{	?>
								$(function()
								{
												updateDataTable();
				    });
<?php	}	?>
</script>



