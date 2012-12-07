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
            <div style="text-align: right;width: 860px;">
                <div style="text-align: left;">
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <span><i class="icon-cog"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown"><a href="#" style="white-space: normal;">Show/Hide Fields <i class="icon-play" style="float: right;"></i></a>
                                <ul class="sub-menu dropdown-menu">
                                    <li><a href="javascript://;" onclick="showHideColumns(0);" id="0_column"><i class="icon-ok"></i>Nomination</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(1);" id="1_column"><i class="icon-ok"></i>Organization</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(2);" id="2_column"><i class="icon-ok"></i>Asset Title</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(3);" id="3_column"><i class="icon-ok"></i>Instantiation ID</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(4);" id="4_column"><i class="icon-ok"></i>Instantiation ID Source</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(5);" id="5_column"><i class="icon-ok"></i>Format Type</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(6);" id="6_column"><i class="icon-ok"></i>Duration</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(7);" id="7_column"><i class="icon-ok"></i>Date</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(8);" id="8_column"><i class="icon-ok"></i>Date Type</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(9);" id="9_column"><i class="icon-ok"></i>File Size</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(10);" id="10_column"><i class="icon-ok"></i>Unit of measure</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(11);" id="11_column"><i class="icon-ok"></i>Color</a></li>
                                    <li><a href="javascript://;" onclick="showHideColumns(12);" id="12_column"><i class="icon-ok"></i>Language</a></li>


                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"  style="white-space: normal;">Freeze Columns <i class="icon-play" style="float: right;"></i></a>
                                <ul class="sub-menu dropdown-menu">
                                    <li><a href="javascript://;">None</a></li>
                                    <li><a href="javascript://;">Freeze 1 Column</a></li>
                                    <li><a href="javascript://;">Freeze 2 Columns</a></li>
                                    <li><a href="javascript://;">Freeze 3 Columns</a></li>
                                    <li><a href="javascript://;">Freeze 4 Columns</a></li>




                                </ul>
                            </li>
                        </ul>
                    </div>

                </div>
                <strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong>
                <?php echo $this->ajax_pagination->create_links(); ?>
            </div>
            <div style="width: 865px;overflow: hidden;" id="instantiation-main">

                <table class="table table-bordered" id="instantiation_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;">
                    <thead>
                        <tr>
    <!--                        <th><span style="float:left;min-width: 80px;">Asset ID</span></th>-->
                            <th id="Nomination"><span style="float:left;min-width: 100px;">Nomination </span></th>
                            <th id="Organization"><span style="float:left;min-width: 100px;">Organization</span></th>
                            <th id="Asset_Title"><span style="float:left;min-width: 300px;">Asset Title</span></th>
                            <th id="Instantiation_ID"><span style="float:left;min-width: 100px;">Instantiation ID</span></th>
                            <th id="Instantiation_ID_Source"><span style="float:left;min-width: 130px;">Instantiation ID Source</span></th>
                            <th id="Format_Type"><span style="float:left;min-width: 90px;">Format Type</span></th>
                            <th id="Duration"><span style="float:left;min-width: 70px;">Duration</span></th>
                            <th id="Date"><span style="float:left;min-width: 90px;">Date</span></th>
                            <th id="Date_Type"><span style="float:left;min-width: 90px;">Date Type</span></th>
                            <th id="File_size"><span style="float:left;min-width: 90px;">File size</span></th>
                            <th id="Unit_of_measure"><span style="float:left;min-width: 100px;">Unit of measure</span></th>
                            <th id="Colors"><span style="float:left;min-width: 70px;">Colors</span></th>
                            <th id="Language"><span style="float:left;min-width: 70px;">Language</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($records as $key => $value)
                        {
                            ?>
                            <tr>
                                <td><?php echo ($value->status) ? $value->status : 'N/A'; ?></td>
                                <td><?php echo $value->organization; ?></td>
                                <td><a href="<?php echo site_url('instantiations/detail/' . $value->id); ?>"><?php echo $value->asset_title; ?></a></td>
                                <td><?php echo $value->instantiation_identifier; ?></td>
                                <td><?php echo $value->instantiation_source; ?></td>
                                <td><?php echo $value->format_type; ?></td>
                                <td><?php echo ($value->actual_duration) ? $value->actual_duration : 'N/A'; ?></td>
                                <td><?php echo ($value->instantiation_date == 0) ? 'No Source Date' : date('Y-m-d', $value->instantiation_date); ?></td>
                                <td><?php echo $value->date_type; ?></td>
                                <td><?php echo ($value->file_size == 0) ? 'N/A' : $value->file_size; ?></td>
                                <td><?php echo ($value->file_size_unit_of_measure) ? $value->file_size_unit_of_measure : 'N/A'; ?></td>
                                <td><?php echo ($value->color) ? $value->color : 'N/A'; ?></td>
                                <td><?php echo ($value->language) ? $value->language : 'N/A'; ?></td>

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
        frozen=1;
        $(function() {
            oTable = $('#instantiation_table').dataTable({
                //                "sDom": 'RC<"clear">lfrtip',
                "sDom": 'Rlfrtip',
                "aoColumnDefs": [
                    //                    { "bVisible": false, "aTargets": [ 1 ] }
                ],
                "oColReorder": {
                    "aiOrder": [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                    "iFixedColumns": frozen,
                    "fnReorderCallback": function () {
                        $('table th').each(function(index){
                            if(index==0)
                                orderString=this.id;
                            else{
                                if(orderString.indexOf(this.id)<0){
                                    orderString+=','+this.id;
                                }
                            }
                                                       
                                                        
                                                            
                                                        
                        }); 
                        columnsOrder=orderString.split(',');
                                                    
                    }
                },
                                                        
                'bPaginate':false,
                'bInfo':false,
                'bFilter': false,
                "bSort": false,
                "sScrollY": 400,
                "sScrollX": "100%"
                                                        
            });
            new FixedColumns( oTable );
                                
            $.extend( $.fn.dataTableExt.oStdClasses, {
                "sWrapper": "dataTables_wrapper form-inline"
            } );
        });
        function showHideColumns(column){
            if(frozen>column+1){
                $('#instantiation_table').dataTable().fnSetColumnVis(column,true);
                $('#'+column+'_column i').toggle();
                if ($('#'+column+'_column i').css('display') == "none") {
                    $('#instantiation_table').dataTable().fnSetColumnVis(column,false);
                }
                else{
                    $('#instantiation_table').dataTable().fnSetColumnVis(column,true);
                }
                    
            }
            else{
                alert('Frozen Column will not take any affect');
            }
        }                                        
                                                                                    
    </script>
<?php } ?>