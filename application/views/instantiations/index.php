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
                 <?php if(!$isAjax){$this->load->view('instantiations/_gear_dropdown');} ?>
                <div style="float: right;">
                    <strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong>
                    <?php echo $this->ajax_pagination->create_links(); ?>
                </div>
            </div>
            <div style="width: 865px;overflow: hidden;" id="instantiation-main">

                <table class="table-bordered" id="listing_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;">
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
    
<?php } ?>