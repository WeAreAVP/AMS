<?php
$ship_date = array(
    'name' => 'tracking_ship_date',
    'id' => 'tracking_ship_date',
    'value' => $tracking_info->ship_date,
);
$ship_to = array(
    'name' => 'ship_to',
    'id' => 'ship_to',
    'value' => $tracking_info->ship_to,
);
$ship_via = array(
    'name' => 'ship_via',
    'id' => 'ship_via',
    'value' => $tracking_info->ship_via,
);
$tracking_no = array(
    'name' => 'tracking_no',
    'id' => 'tracking_no',
    'value' => $tracking_info->tracking_no,
);

$no_tracks=		$tracking_info->tracking_no;
$no_box_shipped = array(
    'name' => 'no_box_shipped',
    'id' => 'no_box_shipped',
    'value' => $tracking_info->no_box_shipped,
);
$media_received_date = array(
    'name' => 'media_received_date',
    'id' => 'media_received_date',
    'value' => $tracking_info->media_received_date,
);

$attributes = array('onsubmit' => 'return false;', 'id' => 'tracking_edit_form', 'class' => 'form-custom');
?>


<?php echo form_open_multipart($this->uri->uri_string(), $attributes); ?>
<table class="table no_border">
    <tr>
        <td class="tracking_label"><?php echo form_label('Ship Date:', $ship_date['id']); ?></td>
        <td><?php echo form_input($ship_date); ?><span style="color: red;"><?php echo form_error($ship_date['name']); ?></span></td>
    </tr>


    <tr>
        <td class="tracking_label"<?php echo form_label('Ship To:', $ship_to['id']); ?></td>
        <td><?php echo form_input($ship_to); ?><span style="color: red;"><?php echo form_error($ship_to['name']); ?></span></td>
    </tr>



    <tr>
        <td class="tracking_label"><?php echo form_label('Ship Via:', $ship_via['id']); ?></td>
        <td><?php echo form_dropdown($ship_via['id'], $this->config->item('ship_types'), array(array_search($tracking_info->ship_via, $this->config->item('ship_types')))); ?><span style="color: red;"><?php echo form_error($ship_via['name']); ?></span></td>
    </tr>

    <tr>
        <td class="tracking_label"><?php echo form_label('Tracking #:', $tracking_no['id']); ?></td>
        <td><?php echo form_textarea($tracking_no,$no_tracks,	'style="height:60px;"'); ?><span style="color: red;"><?php echo form_error($tracking_no['name']); ?></span></td>
    </tr>


    <tr>
        <td class="tracking_label"><?php echo form_label('# of Box Shipped :', $no_box_shipped['id']); ?></td>
        <td><?php echo form_input($no_box_shipped); ?><span style="color: red;"><?php echo form_error($no_box_shipped['name']); ?></span></td>
    </tr>
    <tr>
        <td class="tracking_label"><?php echo form_label('Media Received Date :', $media_received_date['id']); ?></td>
        <td><?php echo form_input($media_received_date); ?><span style="color: red;"><?php echo form_error($media_received_date['name']); ?></span></td>
    </tr>
    <tr>

        <td colspan="2" style="text-align: right;">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>&nbsp;<?php echo form_submit('save', 'Save', 'class="btn btn-primary btn-custom" onclick="manageTracking(\'post\',\'edit\',\'' . $tracking_info->id . '\');" '); ?>

        </td>
    </tr>


</table>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(function() {
        $("#tracking_ship_date").datepicker({dateFormat: 'yy-mm-dd'});
        $("#media_received_date").datepicker({dateFormat: 'yy-mm-dd'});
        
    });
</script>