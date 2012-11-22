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
        <div style="overflow: auto;width:800px;" id="instantiation-main">
            <table class="tablesorter table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 100px;">Asset ID</th>
                        <th style="width: 100px;">Organization</th>
                        <th style="width: 250px;">Asset Title</th>
                        <th style="width: 120px;">Instantiation ID</th>
                        <th style="width: 100px;">Source Date</th>
                        <th style="width: 100px;">Date Type</th>
                        <th style="width: 100px;">Format Type</th>
                        <th style="width: 100px;">File size</th>
                        <th style="width: 100px;">Unit of measure</th>
                        <th style="width: 100px;">Duration</th>
                        <th style="width: 100px;">Colors</th>
                        <th style="width: 100px;">Language</th>
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
                                <td><?php echo $value->asset_id; ?></td>
                                <td><?php echo $value->organization; ?></td>
                                <td><?php echo $value->asset_title; ?></td>
                                <td><?php echo $value->id; ?></td>
                                <td><?php echo $value->instantiation_date; ?></td>
                                <td><?php echo $value->date_type; ?></td>
                                <td><?php echo $value->format_type; ?></td>
                                <td><?php echo $value->file_size; ?></td>
                                <td><?php echo $value->file_size_unit_of_measure; ?></td>
                                <td><?php echo $value->actual_duration; ?></td>
                                <td><?php echo $value->color; ?></td>
                                <td><?php echo $value->language; ?></td>

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
