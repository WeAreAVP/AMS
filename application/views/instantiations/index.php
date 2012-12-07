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
                            <th id="Asset_Title">Asset Title</th>
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
        $(function() {
            oTable = $('#instantiation_table').dataTable({
                "sDom": 'RC<"clear">lfrtip',
                "aoColumnDefs": [
                    { "bVisible": false, "aTargets": [ 1 ] }
                ],
                "oColReorder": {
                    "aiOrder": [ 0, 1, 2, 3, 4,5,6,7,8,9,10,11,12],
                    "iFixedColumns": 1,
                    "fnReorderCallback": function () {
                                
                        $('table th').each(function(index){
                            
                            columnsOrder.push(this.id);
                            
                                
                            
                        }); 
                        console.log(columnsOrder);   
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
            //            new FixedHeader( oTable,{
            //                "offsetTop": 80
            //            } );
            $.extend( $.fn.dataTableExt.oStdClasses, {
                "sWrapper": "dataTables_wrapper form-inline"
            } );
        });
                                        
                                                        
    </script>
<?php } ?>