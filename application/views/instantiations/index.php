<?php
if (!$isAjax)
{
    ?>
    <div class="row-fluid">
        <div class="span3">
            <?php $this->load->view('instantiations/_facet_search'); ?>
        </div>
        <div  class="span9" id="data_container">
        <?php } ?>
        <?php
        if (count($records) > 0)
        {
            ?>
            <div style="width: 860px;">
                <div style="float: left;margin-top: 10px;">
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <span><i class="icon-cog"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown"><a href="#" style="white-space: normal;">Show/Hide Fields <i class="icon-play" style="float: right;"></i></a>
                                <ul class="sub-menu dropdown-menu" id="show_hide_li">
                                    <?php
                                    foreach ($this->column_order as $key => $row)
                                    {
                                        if ($row['hidden'] == 0)
                                        {
                                            $display = 'style="float: left;margin-right: 5px;display:block;"';
                                        } else
                                        {
                                            $display = 'style="float: left;margin-right: 5px;display:none;"';
                                        }
                                        echo '<li"><a href="javascript://;" onclick="showHideColumns(' . $key . ');" id="' . $key . '_column"><i class="icon-ok" ' . $display . '></i>' . str_replace("_", ' ', $row['title']) . '</a></li>';
                                    }
                                    ?>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"  style="white-space: normal;">Freeze Columns <i class="icon-play" style="float: right;"></i></a>
                                <ul class="sub-menu dropdown-menu">
                                    <li><a href="javascript://;" onclick="freezeColumns(0);"><i id="freeze_col_0" class="icon-ok" style="display: none;"></i>None</a></li>
                                    <li><a href="javascript://;" onclick="freezeColumns(1);"><i id="freeze_col_1" class="icon-ok" style="display: none;"></i>Freeze 1 Column</a></li>
                                    <li><a href="javascript://;" onclick="freezeColumns(2);"><i id="freeze_col_2" class="icon-ok" style="display: none;"></i>Freeze 2 Columns</a></li>
                                    <li><a href="javascript://;" onclick="freezeColumns(3);"><i id="freeze_col_3" class="icon-ok" style="display: none;"></i>Freeze 3 Columns</a></li>
                                    <li><a href="javascript://;" onclick="freezeColumns(4);"><i id="freeze_col_4" class="icon-ok" style="display: none;"></i>Freeze 4 Columns</a></li>




                                </ul>
                            </li>
                        </ul>
                    </div>

                </div>
                <div style="float: right;">
                    <strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong>
                    <?php echo $this->ajax_pagination->create_links(); ?>
                </div>
            </div>
            <div style="width: 865px;overflow: hidden;" id="instantiation-main">

                <table class="table table-bordered" id="instantiation_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;">
                    <thead>
                        <tr>
                            <?php
                            foreach ($this->column_order as $key => $value)
                            {
                                $type = $value['title'];
                                if ($type == 'Nomination' || $type == 'Organization' || $type == 'Instantiation_ID' || $type == 'Format_Type' || $type == 'Date' || $type == 'Date_Type' || $type == 'File_size' || $type == 'Unit_of_measure')
                                {
                                    $width = 'min-width:100px;';
                                } else if ($type == 'Duration' || $type == 'Colors' || $type == 'Language')
                                {
                                    $width = 'min-width:70px;';
                                } else if ($type == 'Asset_Title')
                                {
                                    $width = 'min-width:300px;';
                                } else if ($type == 'Instantiation_ID_Source')
                                {
                                    $width = 'min-width:145px;';
                                }
                                echo '<th id="' . $value['title'] . '"><span style="float:left;' . $width . '">' . str_replace("_", ' ', $value['title']) . '</span></th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($records as $key => $value)
                        {
                            ?>
                            <tr>
                                <?php
                                foreach ($this->column_order as $key => $row)
                                {
                                    $type = $row['title'];
                                    if ($type == 'Nomination')
                                    {
                                        $column = ($value->status) ? $value->status : 'N/A';
                                    } else if ($type == 'Organization')
                                    {
                                        $column = $value->organization;
                                    } else if ($type == 'Asset_Title')
                                    {
                                        $column = '<a href="' . site_url('instantiations/detail/' . $value->id) . '">' . $value->asset_title . '</a>';
                                    } else if ($type == 'Instantiation_ID')
                                    {
                                        $column = $value->instantiation_identifier;
                                    } else if ($type == 'Instantiation_ID_Source')
                                    {
                                        $column = $value->instantiation_source;
                                    } else if ($type == 'Format_Type')
                                    {
                                        $column = $value->format_type;
                                    } else if ($type == 'Duration')
                                    {
                                        $column = ($value->actual_duration) ? $value->actual_duration : 'N/A';
                                    } else if ($type == 'Date')
                                    {
                                        $column = ($value->instantiation_date == 0) ? 'No Source Date' : date('Y-m-d', $value->instantiation_date);
                                    } else if ($type == 'Date_Type')
                                    {
                                        $column = $value->date_type;
                                    } else if ($type == 'File_size')
                                    {
                                        $column = ($value->file_size == 0) ? 'N/A' : $value->file_size;
                                    } else if ($type == 'Unit_of_measure')
                                    {
                                        $column = ($value->file_size_unit_of_measure) ? $value->file_size_unit_of_measure : 'N/A';
                                    } else if ($type == 'Colors')
                                    {
                                        $column = ($value->color) ? $value->color : 'N/A';
                                    } else if ($type == 'Language')
                                    {
                                        $column = ($value->language) ? $value->language : 'N/A';
                                    }
                                    echo '<td>' . $column . '</td>';
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>

                </table>

            </div>

            <div style="text-align: right;width: 860px;">
                <strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong>
                <?php echo $this->ajax_pagination->create_links(); ?>
            </div>
            <?php
        } else
        {
            ?>
            <div  style="text-align: center;width: 860px;margin-top: 50px;font-size: 20px;">No instantiation record found.</div>
        <?php }
        ?>
        <?php
        if (!$isAjax)
        {
            ?>
        </div>
    </div>
    <script type="text/javascript">
        columnsOrder=new Array();
        orderString='';
        frozen='<?php echo $this->frozen_column; ?>';
        updateInstantiationsTable=1;
        oTable=null;
        $(function() {
            updateDataTable();
        });
        function showHideColumns(column){
            if(frozen<column+1){
                $('#instantiation_table').dataTable().fnSetColumnVis(column,true);
                $('#'+column+'_column i').toggle();
                if ($('#'+column+'_column i').css('display') == "none") {
                    $('#instantiation_table').dataTable().fnSetColumnVis(column,false);
                }
                else{
                    $('#instantiation_table').dataTable().fnSetColumnVis(column,true);
                }
                updateDatabase();
            }
            else{
                alert('Frozen Column will not take any affect');
            }
        }                                        
        function getColumnOrder(){
            $('table th').each(function(index){
                if(index==0)
                    orderString=this.id;
                else{
                    if(orderString.indexOf(this.id)<0){
                        orderString+=','+this.id;
                    }
                }
            }); 
            return columnsOrder=orderString.split(',');
        } 
        function reOrderDropDown(columnArray){
            $('#show_hide_li').html('');
            for(cnt in columnArray){
                name=columnArray[cnt].split('_').join(' ');
                $('#show_hide_li').append('<li><a href="javascript://;" onclick="showHideColumns('+cnt+');" id="'+cnt+'_column"><i class="icon-ok"></i>'+name+'</a></li>');
            }
        }
        function freezeColumns(count){
            frozen=count;
            $('freeze_col_'+frozen).toggle(); 
            facet_search('0');
            updateDatabase();
                                                                                                                                                                                        
        }
        function updateDataTable(){
            oTable = $('#instantiation_table').dataTable({
                //                "sDom": 'RC<"clear">lfrtip',
                "sDom": 'Rlfrtip',
                "aoColumnDefs": [
                    { "bVisible": false, "aTargets": <?php echo json_encode($hidden_fields); ?> }
                ],
                "oColReorder": {
//                    "aiOrder": [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                    "iFixedColumns": frozen,
                    "fnReorderCallback": function () {
                        columnArray= getColumnOrder();
                        reOrderDropDown(columnArray);
                        updateDatabase();
                    }
                },
                                                                                                                                                                                                                                                                                            
                'bPaginate':false,
                'bInfo':false,
                'bFilter': false,
                "bSort": false,
                "sScrollY": 400,
                "sScrollX": "100%",
                "bScrollInfinite": true
                                                                                                                                                                                
                                                                                                                                                                                                                                                                                            
            });
            if(frozen>0){
                new FixedColumns( oTable, {
                    "iLeftColumns": frozen
                } );
            }
            $('freeze_col_'+frozen).show();                                                                                                                                                                                                                                           
            $.extend( $.fn.dataTableExt.oStdClasses, {
                "sWrapper": "dataTables_wrapper form-inline"
            } );
        }
        function updateDatabase(){
            userSettings=new Array();
            $('#show_hide_li a').each(function(index,id){
                columnAnchorID=this.id;
                if ($('#'+columnAnchorID+' i').css('display') == "none") {
                    userSettings[index]= {
                        title: str_replace(' ','_',$(this).text()),
                        hidden: 1
                    };
                }
                else{
                    userSettings[index]= {
                        title:  str_replace(' ','_',$(this).text()),
                        hidden: 0
                    };
                }
            }); 
                                
            $.ajax({
                type: 'POST', 
                url: site_url+'instantiations/update_user_settings',
                data:{settings:userSettings,frozen_column:frozen},
                success: function (result)
                { 
                                   
                                                                                
                }
            });
        }
    </script>
<?php } ?>