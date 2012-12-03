<div class="span3">
    <div class="sidebar">
        <div class="my-navbar span12">
            <div><a style="color:white" href="<?php echo site_url('records/details/' . $asset_id) ?>">Asset Information</a></div>
        </div>
        <?php
        if (isset($asset_instantiations['records']) && !empty($asset_instantiations['records']))
        {
            ?>
            <div class="container-sidebar"><?php
        foreach ($asset_instantiations['records'] as $asset_instantiation)
        {
            ?>
                    <h4><a href="<?php echo site_url('instantiations/detail/' . $asset_instantiation->id) ?>"><?php echo $asset_details->guid_identifier ?></a></h4>
                    <?php
                    echo (isset($asset_instantiation->asset_title) && ($asset_instantiation->asset_title != NULL)) ? "Title: " . $asset_instantiation->asset_title . '<br/>' : '';
                    echo (isset($asset_instantiation->instantiation_identifier) && ($asset_instantiation->instantiation_identifier != NULL)) ? "Instantiation ID: " . $asset_instantiation->instantiation_identifier . '<br/>' : '';
                    echo (isset($asset_instantiation->format_name) && ($asset_instantiation->format_name != NULL) ) ? "Format: " . $asset_instantiation->format_name . '<br/>' : '';
                    echo (isset($asset_instantiation->generation) && ($asset_instantiation->generation != NULL) ) ? "Generation: " . $asset_instantiation->generation . '<br/>' : '';
                    echo ($asset_instantiation->actual_duration > 0) ? "Actual Duration: " . duration($asset_instantiation->actual_duration) . '<br/>' : '';
                    echo ($asset_instantiation->projected_duration > 0) ? "Projected Duration: " . duration($asset_instantiation->projected_duration) . '<br/>' : '';
                    ?><?php }
                ?>
            </div>
<?php } ?>
    </div><!--end of sidebar-->
</div>