<style type="text/css">
    .tablewrapper{ width: 860; height: 500px; overflow: auto; border: solid 2px #999; }

    table{ width: 900px; font-size: 11px; }
    table th, 
    table td{ padding: 5px 5px 5px 10px; min-width: 80px;}
    table th{ background-color: white; text-align: left; }
    table tr.bg0 td{ background-color: #fff; }
    table tr.bg1 td{ background-color: #f4f4f4; }
    /* pt specific styles: ptfixed, ptdragover, ptdraghandle, ptshowhide */
    table tr th.ptfixed{ background-color: #F1F8FA; border-bottom: solid 1px #aaa; opacity: 0.95; filter: alpha(95); }
    table tr.bg0 td.ptfixed{ background-color: #eee; border-right: solid 1px #aaa;  opacity: 0.95; filter: alpha(95); }
    table tr.bg1 td.ptfixed{ background-color: #e4e4e4; border-right: solid 1px #aaa;  opacity: 0.95; filter: alpha(95); }
    table .ptdragover{ border-left: dashed 2px #A04334; padding-left: 8px; }
    table th .ptdraghandle{ text-decoration: none; font-weight: bold; font-size: 14px; padding: 0 4px; cursor: move; }
    table th .ptshowhide{ text-decoration: none; font-weight: bold; font-size: 14px; padding: 0 4px; }

    ul#tableController{ float: right; padding: 0; width: 175px; }
    ul#tableController li{ list-style: none; margin: 5px 0; padding: 5px 5px; color: #777; border-bottom: solid 2px #eee; }
    ul#tableController li[data-ptcolumnvisible='true']{ color: #000; border-bottom: solid 2px #aaa; }
    ul#tableController li.ptdragover{ border: dashed 2px #A04334; padding: 3px 3px 5px 3px; }
    ul#tableController li .ptdraghandle{ text-decoration: none; font-weight: bold; padding: 0 4px; font-size: 14px; cursor: move; float: right;}
    ul#tableController li .ptshowhide{ text-decoration: none; font-weight: bold; padding: 0 4px; font-size: 14px;  }
</style>
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
            <div id="instantiation-main" class="tablewrapper">
    <!--                <table class="tablesorter table-freeze-custom table-bordered freeze-my-column" id="instantiation_table" style="margin-top:0px;margin-left: 1px;">-->
                <table class="" id="instantiation_table" style="margin-top:0px;margin-left: 1px;">
                    <thead>
                        <tr>
                            <th data-ptcolumn="nomination"><span style="float:left;min-width: 100px;">Nomination </span></th>
                            <th data-ptcolumn="organization"><span style="float:left;min-width: 100px;">Organization</span></th>
                            <th data-ptcolumn="asset_title"><span style="float:left;min-width: 300px;">Asset Title</span></th>
                            <th data-ptcolumn="instantiation_id"><span style="float:left;min-width: 100px;">Instantiation ID</span></th>
                            <th data-ptcolumn="instantiation_id_source"><span style="float:left;min-width: 130px;">Instantiation ID Source</span></th>
                            <th data-ptcolumn="format_type"><span style="float:left;min-width: 90px;">Format Type</span></th>
                            <th data-ptcolumn="duration"><span style="float:left;min-width: 70px;">Duration</span></th>
                            <th data-ptcolumn="date"><span style="float:left;min-width: 90px;">Date</span></th>
                            <th data-ptcolumn="date_type"><span style="float:left;min-width: 90px;">Date Type</span></th>
                            <th data-ptcolumn="file_size"><span style="float:left;min-width: 90px;">File size</span></th>
                            <th data-ptcolumn="unit_of_measure"><span style="float:left;min-width: 100px;">Unit of measure</span></th>
                            <th data-ptcolumn="color"><span style="float:left;min-width: 70px;">Colors</span></th>
                            <th data-ptcolumn="language"><span style="float:left;min-width: 70px;">Language</span></th>
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
                <div style="display: none;">
                    <pre class="code example" data-lllanguage="js">$pt = $('#instantiation_table').powertable({
    	fixedColumns: ['nomination'],
    	fixedRows: [0],
    	moveDisabled: ['nomination'],
    	showHideDisabled: ['nomination']
    });</pre>
                </div>
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
        $(document).ready(function(){
            $('pre.example').each(function(){
                eval($(this).text());
            });

            $('pre').litelighter({  });
        });                                   
                                                        
        //        $(function() {
        //                        			 
        //            $('.freeze-my-column').freezeTableColumns({
        //                width:       860,   // required
        //                height:      600,   // required
        //                numFrozen:   0,     // optional
        //                //            frozenWidth: 150,   // optional
        //                clearWidths: false  // optional
        //            });//freezeTableColumns
        //        });
                                                
    </script>
<?php } ?>