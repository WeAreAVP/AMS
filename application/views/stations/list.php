<?php
$search = array(
    'name' => 'search_keyword',
    'id' => 'search_keyword',
    'value' => set_value('search_keyword'),
);
$certified = array(
    'name' => 'certified',
    'id' => 'certified',
    'value' => set_value('certified'),
);
$agreed = array(
    'name' => 'agreed',
    'id' => 'agreed',
    'value' => set_value('agreed'),
);
$attributes = array('id' => 'search_form');

echo form_open_multipart($this->uri->uri_string(), $attributes);
?>

<table class="table no_border">
    <tr>
        <td>
            <?php echo form_label('Search Keyword', $search['id']); ?>
        </td>
        <td>
            <?php echo form_input($search); ?>
        </td>
        <td><?php echo form_label('Certified', $certified['id']); ?></td>
        <td>
            <?php echo form_dropdown($certified['id'], array('' => 'Select', '1' => 'Yes', '0' => 'No')); ?>

        </td>
        <td><?php echo form_label('Agreed', $certified['id']); ?></td>
        <td>
            <?php echo form_dropdown($agreed['id'], array('' => 'Select', '1' => 'Yes', '0' => 'No')); ?>
        </td>
        <td><?php echo form_submit('search', 'Search', 'class="btn primary" '); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>
<div  style="overflow: scroll;height: 600px;">
    <table class="tablesorter table table-bordered" id="station_table">
        <thead>
            <tr>
                <td width="20"><input type='checkbox' name='all' value='' id='check_all'  class="check-all" onclick='javascript:checkAll();' /></td>
                <th width="20">CPB ID</th>
                <th>Station Name</th>
                <th>Contact Name</th>
                <th>Contact Title</th>
                <th>Type</th>
                <th>Primary Address </th>
                <th>Secondary Address</th>
                <th>City</th>
                <th>State</th>
                <th>Zip</th>
                <th>Contact Phone</th>
                <th>Contact Fax</th>
                <th>Contact Email</th>
                <th>Allocated Hours</th>
                <th>Allocated Buffer</th>
                <th>Total Allocated Hours</th>
                <th>Certified</th>
                <th>Agreed</th>
                <th>Start Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($stations) > 0) {
                foreach ($stations as $data) {
                    ?>
                    <tr>
                        <td><input style='margin-left:15px;' type='checkbox' name='station[]' value='<?php echo $data->id; ?>'  class='checkboxes'/></td>
                        <td><a href="<?php echo site_url('stations/detail/' . $data->id); ?>"><?php echo $data->cpb_id; ?></a></td>
                        <td><?php echo $data->station_name; ?></td>
                        <td><?php echo $data->contact_name; ?></td>
                        <td><?php echo $data->contact_title; ?></td>

                        <td>
                            <?php
                            if ($data->type == 0)
                                echo 'Radio';
                            else if ($data->type == 1)
                                echo 'TV';
                            else if ($data->type == 2)
                                echo 'Joint';
                            else
                                echo 'Unknown';
                            ?>
                        </td>
                        <td><?php echo $data->address_primary; ?></td>
                        <td><?php echo $data->address_secondary; ?></td>
                        <td><?php echo $data->city; ?></td>
                        <td><?php echo $data->state; ?></td>
                        <td><?php echo $data->zip; ?></td>
                        <td><?php echo $data->contact_phone; ?></td>
                        <td><?php echo $data->contact_fax; ?></td>
                        <td><?php echo $data->contact_email; ?></td>
                        <td><?php echo $data->allocated_hours; ?></td>
                        <td><?php echo $data->allocated_buffer; ?></td>
                        <td><?php echo $data->total_allocated; ?></td>
                        <td><?php echo ($data->is_certified) ? 'Yes' : 'No'; ?>
                        <td><?php echo ($data->is_agreed) ? 'Yes' : 'No'; ?>
                        <td>
                            <?php if (empty($data->start_date)) {
                                ?>
                                <a id="<?php echo $data->id; ?>_date" href="#myModal"  data-toggle="modal" onclick="setStartDate('','<?php echo $data->station_name; ?>','<?php echo $data->id; ?>');">Set start date</a>
                                <?php
                            } else {
                                ?>
                                <a id="<?php echo $data->id; ?>_date" href="#myModal"  data-toggle="modal" onclick="setStartDate('<?php echo $data->start_date; ?>','<?php echo $data->station_name; ?>','<?php echo $data->id; ?>');"><?php echo $data->start_date; ?></a>
                                <?php
                            }
                            ?>


                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr><td colspan="11" style="text-align: center;"><b>No Station Found.</b></td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div style="text-align: center;"><a href="<?php echo site_url('stations/add/'); ?>" >Add New</a></div>


<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="stationLabel">Set Start Date</h3>
    </div>
    <div class="modal-body">
        <input type="hidden" id="station_id" name="station_id"/>
        <input type="text" id="start_date" name="start_date"/>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary" onclick="updateStartDate();"  data-dismiss="modal">Save changes</button>
    </div>
</div>
<script type="text/javascript">
    var stationName=null;
    function setStartDate(date,station_name,id){
        stationName=station_name;
        $('#stationLabel').html(station_name+' Start Date');
        $('#start_date').val(date);
        $('#station_id').val(id);
        
    }
    function updateStartDate(){
        station=$('#station_id').val();
        start_date=$('#start_date').val();
        $.ajax({
            type: 'POST', 
            url: '/index.php/stations/update_station_date',
            data:{id:station,start_date:start_date},
            dataType: 'json',
            cache: false,
            success: function (result) { 
                if(result.success==true && result.station==true){
                    $('#'+station+'_date').attr('onclick','setStartDate("'+start_date+'","'+stationName+'","'+station+'")');
                    $('#'+station+'_date').html(start_date);
                }
                else{
                    console.log('some error occur');
                }
            }
        });
    }
    function checkAll() {
        var boxes = document.getElementsByTagName('input');
        for (var index = 0; index < boxes.length; index++) {
            box = boxes[index];
            if (box.type == 'checkbox' && box.className == 'checkboxes' && box.disabled == false)
                box.checked = document.getElementById('check_all').checked;
        }
        return true;
    }
    
</script>