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
            <div style="overflow:hidden;width:865px;" id="instantiation-main">
    <!--                <table class="tablesorter table-freeze-custom table-bordered freeze-my-column" id="instantiation_table" style="margin-top:0px;margin-left: 1px;">-->
                <table class="tablesorter table table-bordered" id="instantiation_table" style="margin-top:0px;margin-left: 1px;">
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
                <pre class="code example" data-lllanguage="js" style="display: none;">$pt = $('#instantiation_table').powertable({
	fixedColumns: ['nomination'],
	fixedRows: [0],
	moveDisabled: ['nomination'],
	showHideDisabled: ['nomination']
});</pre>
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