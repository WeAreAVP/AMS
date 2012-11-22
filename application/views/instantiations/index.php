<div class="row-fluid">
    <div class="span3">
        <div id="search_bar"> 
            <b>
                <h4>Filter Instantiations</h4>
            </b>
            <div class="filter-fileds">
                <div> Search </div>
                <div>
                    <input type="text"/>
                </div>
            </div>
            <div class="filter-fileds">
                <div><input type="button" name="reset" value="Reset" class="btn"/></div>
            </div>
        </div>
    </div>
    <div  class="span9">
        <div style="text-align: right;width: 860px;"><strong><?php echo $total; ?></strong><a class="btn"><i class="icon-chevron-left"></i></a><a class="btn"><i class="icon-chevron-right"></i></a></div>
        <div style="overflow: auto;width:865px;" id="instantiation-main">
            <table class="tablesorter table table-bordered">
                <thead>
                    <tr>
                        <th><span style="float:left;min-width: 80px;">Asset ID</span></th>
                        <th><span style="float:left;min-width: 100px;">Organization</span></th>
                        <th><span style="float:left;min-width: 250px;">Asset Title</span></th>
                        <th><span style="float:left;min-width: 100px;">Instantiation ID</span></th>
                        <th><span style="float:left;min-width: 90px;">Source Date</span></th>
                        <th><span style="float:left;min-width: 90px;">Date Type</span></th>
                        <th><span style="float:left;min-width: 90px;">Format Type</span></th>
                        <th><span style="float:left;min-width: 90px;">File size</span></th>
                        <th><span style="float:left;min-width: 100px;">Unit of measure</span></th>
                        <th><span style="float:left;min-width: 70px;">Duration</span></th>
                        <th><span style="float:left;min-width: 70px;">Colors</span></th>
                        <th><span style="float:left;min-width: 70px;">Language</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($records) > 0)
                    {
                        foreach ($records as $key => $value)
                        {
                            ?>
                            <tr>
                                <td><?php echo $value->assets_id; ?></td>
                                <td><?php echo $value->organization; ?></td>
                                <td><?php echo $value->multi_assets; ?></td>
                                <td><?php echo $value->id; ?></td>
                                <td><?php echo ($value->instantiation_date == 0) ? 'No Source Date' : date('Y-m-d', $value->instantiation_date); ?></td>
                                <td><?php echo $value->date_type; ?></td>
                                <td><?php echo $value->format_type; ?></td>
                                <td><?php echo ($value->file_size == 0) ? 'N/A' : $value->file_size; ?></td>
                                <td><?php echo ($value->file_size_unit_of_measure) ? $value->file_size_unit_of_measure : 'N/A'; ?></td>
                                <td><?php echo ($value->actual_duration) ? $value->actual_duration : 'N/A'; ?></td>
                                <td><?php echo ($value->color) ? $value->color : 'N/A'; ?></td>
                                <td><?php echo ($value->language) ? $value->language : 'N/A'; ?></td>

                            </tr>
                            <?php
                        }
                    } else
                    {
                        ?>
                        <tr>
                            <td colspan="12">No instantiation record found.</td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>


    </div>
</div>
