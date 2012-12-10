<div style="float: left;margin-top: 10px;" id="gear_box">
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
                        echo '<li><a href="javascript://;" onclick="showHideColumns(' . $key . ');" id="' . $key . '_column"><i class="icon-ok" ' . $display . '></i>' . str_replace("_", ' ', $row['title']) . '</a></li>';
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
            $('#listing_table').dataTable().fnSetColumnVis(column,true);
            $('#'+column+'_column i').toggle();
            if ($('#'+column+'_column i').css('display') == "none") {
                $('#listing_table').dataTable().fnSetColumnVis(column,false);
            }
            else{
                $('#listing_table').dataTable().fnSetColumnVis(column,true);
            }
            updateDatabase();
        }
        else{
            alert('Frozen Column will not take any affect');
        }
    }                                        
    function getColumnOrder(){
        $('table th').each(function(index){
            if(index==0 || orderString=='')
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
        $('#freeze_col_'+frozen).toggle(); 
        facet_search('0');
        updateDatabase();
                                                                                                                                                                                            
    }
    function updateDataTable(){
        oTable = $('#listing_table').dataTable({
            //                "sDom": 'RC<"clear">lfrtip',
            "sDom": 'RlfrtSip',
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
            "bScrollCollapse": true,
            "bScrollInfinite": true,
            "bDeferRender": true,
            "bAutoWidth": false

           
            
                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                
        });
        if(frozen>0){
            new FixedColumns( oTable, {
                "iLeftColumns": frozen
            } );
        }
        $('#freeze_col_'+frozen).show();                                                                                                                                                                                                                                           
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
            data:{settings:userSettings,frozen_column:frozen,table_type:'<?php echo $table_type ?>'},
            success: function (result)
            { 
                                       
                                                                                    
            }
        });
    }
</script>