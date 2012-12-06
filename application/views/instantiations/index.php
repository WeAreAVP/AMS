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
            <div style="overflow:scroll;width:865px;height: 600px;" id="instantiation-main">

                <table class="table table-bordered" id="instantiation_table" style="margin-top:0px;margin-left: 1px;">
                    <thead>
                        <tr>
    <!--                        <th><span style="float:left;min-width: 80px;">Asset ID</span></th>-->
                            <th><span style="float:left;min-width: 100px;">Nomination </span></th>
                            <th><span style="float:left;min-width: 100px;">Organization</span></th>
                            <th style="min-width: 250px;">Asset Title</th>
                            <th><span style="float:left;min-width: 100px;">Instantiation ID</span></th>
                            <th><span style="float:left;min-width: 130px;">Instantiation ID Source</span></th>
                            <th><span style="float:left;min-width: 90px;">Format Type</span></th>
                            <th><span style="float:left;min-width: 70px;">Duration</span></th>
                            <th><span style="float:left;min-width: 90px;">Date</span></th>
                            <th><span style="float:left;min-width: 90px;">Date Type</span></th>
                            <th><span style="float:left;min-width: 90px;">File size</span></th>
                            <th><span style="float:left;min-width: 100px;">Unit of measure</span></th>
                            <th><span style="float:left;min-width: 70px;">Colors</span></th>
                            <th><span style="float:left;min-width: 70px;">Language</span></th>
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
        $(function() {
            oTable = $('#instantiation_table').dataTable({
                "sDom": "<'row'<'span9'l><'span9'f>r>t<'row'<'span9'i><'span9'p>>",
                'bPaginate':false,
                'bInfo':false,
                'bFilter': false
            });
            $.extend( $.fn.dataTableExt.oStdClasses, {
                "sWrapper": "dataTables_wrapper form-inline"
            } );
        });
                
                                
    </script>
<?php } ?>