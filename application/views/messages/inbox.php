<?php
if (!$is_ajax) {
    $stations = array(
        'name' => 'stations',
        'id' => 'stations',
        'function' => 'onchange="filter_inbox();"',
    );
    $message_type = array(
        'name' => 'message_type',
        'id' => 'message_type',
        'function' => 'onchange="filter_inbox();"',
    );
    $select[] = 'Select';
    foreach ($this->config->item('messages_type') as $msg_type) {
        $select[] = $msg_type;
    }
    ?>
    <br/>
    <div class="row-fluid">
        <div class="span3">
            <a href="#compose_to_type" class="btn btn-large" data-toggle="modal" id="compose_anchor">Compose Message</a>
            <a href="#compose_confirm"  data-toggle="modal" id="confirm_anchor" style="display: none;"></a>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span3">
            <div id="search_bar">
                <b><h4>Folders</h4></b>
                <div style="padding: 8px;color: white;background: none repeat scroll 0% 0% rgb(0, 152, 214);" ><a href="<?php echo site_url('messages/inbox') ?>" >Inbox</a></div>
                <div style="padding: 8px;" >	<a href="<?php echo site_url('messages/sent') ?>" >Sent</a></div>

                <div>
                    <?php echo form_label('Stations', $stations['id']); ?>
                </div>
                <div>
                    <?php echo form_dropdown($stations['id'], array('' => 'Select'), array(), $stations['function'] . 'id="' . $stations['id'] . '"'); ?>
                </div>
                <div>
                    <?php echo form_label('Message Type', $message_type['id']); ?>
                </div>
                <div>
                    <?php echo form_dropdown($message_type['id'], $select, array(), $message_type['function'] . 'id="' . $message_type['id'] . '"'); ?>
                </div>
            </div>
        </div>

        <div  class="span9">
            <div class="alert" style="margin-bottom: 0px; margin-top: 0px;display: none;" id="success_message"></div>
            <div style="overflow: scroll;height: 600px;" >
                <table class="tablesorter table table-bordered" id="station_table">
                    <thead>
                        <tr>
                            <td><span style="float:left;min-width:50px;">From</span></td>
                            <td><span style="float:left;min-width:80px;">Subject</span></td>
                            <th><span style="float:left;min-width:90px;">Date</span></td>
                        </tr>
                    </thead>
                    <tbody id="append_record"><?php
            }
            if (count($results) > 0) {
                foreach ($results as $row) {
                        ?>
                            <tr <?php if($row->msg_status=='unread'){?> style="font-weight:bold"<?php }?>>
                                <td><?php echo $row->sender_id; ?></td>
                                <td><?php echo $row->subject; ?></td>
                                <td><?php echo date("Y-m-d", strtotime($row->created_at)); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr><td colspan="11" style="text-align: center;"><b>No Message Found.</b></td></tr>
                    <?php } ?>
                    <?php if (!$is_ajax) { ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function filter_inbox(){
            var stations=$('#stations').val();
            var message_type=$('#message_type').val();
            $.ajax({
                type: 'POST', 
                url: site_url+'messages/inbox',
                data:{stations:stations,message_type:message_type},
                dataType: 'json',
                cache: false,
                success: function (result) {
                    $('#append_record').html(result);
                }
            });
    }
    
    </script>
   
    <?php
} else {
    exit();
    ?>
    <script type="text/javascript"> $("#station_table").tablesorter();</script>
<?php } ?>


