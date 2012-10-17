<?php
$search = array(
    'name' => 'search_keyword',
    'id' => 'search_keyword',
    'value' => set_value('search_keyword'),
    'onkeyup' => 'makeToken(event);',
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
$attributes = array('id' => 'search_form','onkeypress'=>"return event.keyCode != 13;");

echo form_open_multipart($this->uri->uri_string(), $attributes);
?>
<div class="row-fluid">
    <div class="span3">
        <div id="search_bar">
            <div>
                <?php echo form_label('FILTER STATIONS', $search['id']); ?></b>
            </div>
            <div>
                <?php echo form_input($search); ?>
            </div>
            <div>
                <?php echo form_label('Certified', $certified['id']); ?>
            </div>
            <div>
                <?php echo form_dropdown($certified['id'], array('' => 'Select', '1' => 'Yes', '0' => 'No')); ?>
            </div>
            <div>
                <?php echo form_label('Agreed', $agreed['id']); ?>
            </div>
            <div>
                <?php echo form_dropdown($agreed['id'], array('' => 'Select', '1' => 'Yes', '0' => 'No')); ?>
            </div>
            <div><?php echo form_submit('search', 'Search', 'class="btn primary" '); ?></div>
        </div>


    </div>
    <?php echo form_close(); ?>
    <div  class="span9">
        <div style="overflow: scroll;height: 600px;" >
        <table class="tablesorter table table-bordered" id="station_table">
            <thead>
                <tr>
                    <td><span style="float:left;min-width:25px;"><input type='checkbox' name='all' value='' id='check_all'  class="check-all" onclick='javascript:checkAll();' /></span></td>
                    <th><span style="float:left;min-width:50px;">CPB ID</span></th>
                    <th><span style="float:left;min-width:80px;">Station Name</span></th>
                    <th><span style="float:left;min-width:90px;">Contact Name</span></th>
                    <th><span style="float:left;min-width:80px;">Contact Title</span></th>
                    <th><span style="float:left;min-width:30px;">Type</span></th>
                    <th><span style="float:left;min-width:100px;">Primary Address</span> </th>
                    <th><span style="float:left;min-width:110px;">Secondary Address</span></th>
                    <th><span style="float:left;min-width:50px;">City</span></th>
                    <th><span style="float:left;min-width:50px;">State</span></th>
                    <th><span style="float:left;min-width:50px;">Zip</span></th>
                    <th><span style="float:left;min-width:90px;">Contact Phone</span></th>
                    <th><span style="float:left;min-width:90px;">Contact Fax</span></th>
                    <th><span style="float:left;min-width:80px;">Contact Email</span></th>
                    <th><span style="float:left;min-width:90px;">Allocated Hours</span></th>
                    <th><span style="float:left;min-width:100px;">Allocated Buffer</span></th>
                    <th><span style="float:left;min-width:130px;">Total Allocated Hours</span></th>
                    <th><span style="float:left;min-width:50px;">Certified</span></th>
                    <th><span style="float:left;min-width:50px;">Agreed</span></th>
                    <th><span style="float:left;min-width:80px;">DSD</span></th>
                    <th><span style="float:left;min-width:80px;">DED</span></th>
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
                            <td>
                                <?php if (empty($data->end_date)) {
                                    ?>
                                    <a id="<?php echo $data->id; ?>_date" href="#myModal"  data-toggle="modal" onclick="setStartDate('','<?php echo $data->station_name; ?>','<?php echo $data->id; ?>');">Set end date</a>
                                    <?php
                                } else {
                                    ?>
                                    <a id="<?php echo $data->id; ?>_date" href="#myModal"  data-toggle="modal" onclick="setStartDate('<?php echo $data->end_date; ?>','<?php echo $data->station_name; ?>','<?php echo $data->id; ?>');"><?php echo $data->end_date; ?></a>
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
        <div class="row" style="margin:5px 0px;">
            
            <a href="javascript://" class="btn btn-large">Edit</a>
        </div>
    </div>


</div>

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
    var token=0;
    var removeToken=0;
    function makeToken(event){
    
        if (event.keyCode == 13 && $('#mainsearch').val()!='') {
            token=token+1;
            
            $('#token_string').append('<div class="token" id="div_'+token+'"><span id="search_string_'+token+'">'+$('#mainsearch').val()+'</span><span> <a href="javascript:void(0);" onclick="removeTokenDiv('+token+');">X</a></span></div>');
//            getRecords();
            $('#mainsearch').val('');
            $('.dropdown-container').css('width',$('.search-input').width()+26);
            
        }
        else if (event.keyCode == 8) {
            if($('#mainsearch').val()=='' && token!=0){
                if(removeToken==1){
                    $('.token').last().remove();
                    
                    $('.dropdown-container').css('width',$('.search-input').width()+26);
                    token=token-1;
                    removeToken=0;
//                    getRecords();
                }
                else{
                    removeToken=1;
                }
                
            }
            
        }
        if(token>0){
            $('.token-count').html(token);
            $('.search-close').show();
            $('.token-count').show();
            
        }
        else{
            $('.token-count').html(token);
            $('.search-close').hide();
            $('.token-count').hide();
        }
        //        console.log(token);
    }
</script>