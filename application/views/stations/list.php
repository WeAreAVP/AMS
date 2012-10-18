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
$state_options = array(
    "AK" => "AK",
    "AL" => "AL",
    "AR" => "AR",
    "AZ" => "AZ",
    "CA" => "CA",
    "CO" => "CO",
    "CT" => "CT",
    "DC" => "DC",
    "DE" => "DE",
    "FL" => "FL",
    "GA" => "GA",
    "HI" => "HI",
    "IA" => "IA",
    "ID" => "ID",
    "IL" => "IL",
    "IN" => "IN",
    "KS" => "KS",
    "KY" => "KY",
    "LA" => "LA",
    "MA" => "MA",
    "MD" => "MD",
    "ME" => "ME",
    "MI" => "MI",
    "MN" => "MN",
    "MO" => "MO",
    "MS" => "MS",
    "MT" => "MT",
    "NC" => "NC",
    "ND" => "ND",
    "NE" => "NE",
    "NH" => "NH",
    "NJ" => "NJ",
    "NM" => "NM",
    "NV" => "NV",
    "NY" => "NY",
    "OH" => "OH",
    "OK" => "OK",
    "OR" => "OR",
    "PA" => "PA",
    "RI" => "RI",
    "SC" => "SC",
    "SD" => "SD",
    "TN" => "TN",
    "TX" => "TX",
    "UT" => "UT",
    "VA" => "VA",
    "VT" => "VT",
    "WA" => "WA",
    "WI" => "WI",
    "WV" => "WV",
    "WY" => "WY",
    "ON" => "ON",
    "QC" => "QC",
    "AB" => "AB",
    "NS" => "NS",
    "NB" => "NB",
    "MB" => "MB",
    "BC" => "BC",
    "PE" => "PE",
    "SK" => "SK",
    "NL" => "NL");
$attributes = array('id' => 'search_form', 'onkeypress' => "return event.keyCode != 13;");

echo form_open_multipart($this->uri->uri_string(), $attributes);
?>
<div class="row-fluid">
    <div class="span3">
        <div id="search_bar">
            <b><h4>Filter Stations</h4></b>
            <div id="tokens" style="display: none;"></div>
            <input type="hidden" name="search_words" id="search_words"/>
            <div>
                <?php echo form_label('Keyword(s):', $search['id']); ?></b>
            </div>
            <div>
                <?php echo form_input($search); ?>
            </div>
            <div style="float: left;">Type:</div>
            <div style="margin-left:35px;">
                <div><input type="checkbox" value="0" name="type[]" id="radio" class="cls_radio"/>Radio</div>
                <div><input type="checkbox" value="1" name="type[]" id="tv" class="cls_radio"/>TV</div>
                <div><input type="checkbox" value="2" name="type[]" id="joint" class="cls_radio"/>Joint</div>
            </div>
            <div>State:</div>
            <div>
                <select name="state" >
                    <option value="0">Select</option>
                    <?php foreach ($state_options as $options) { ?>
                        <option value="<?php echo $options; ?>" <?php if ($options == $sel_state_id) { ?> selected <?php } ?>  ><?php echo $options; ?></option>
                    <?php } ?>


                </select>
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
                                        No DSD
                                        <?php
                                    } else {
                                        ?>
                                        <?php echo $data->start_date; ?>
                                        <?php
                                    }
                                    ?>


                                </td>
                                <td>
                                    <?php if (empty($data->end_date)) {
                                        ?>
                                        No DED
                                        <?php
                                    } else {
                                        ?>
                                        <?php echo $data->end_date; ?>
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

            <a href="javascript://" class="btn btn-large" onclick="editStations();">Edit</a>
        </div>
    </div>


</div>
<a href="#myStationModal" data-toggle="modal" id="showPopUp"></a>
<div class="modal hide" id="myStationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Edit Station Record(s)</h3>
        <p id="subLabel" style="font-size: 10px;"></p>
    </div>
    <div class="modal-body">

        <input type="hidden" id="station_id" name="station_id"/>
        <div><div style="float: left;width: 150px;">Digitization Start Date:</div><input type="text" id="start_date" name="start_date"/></div>
        <div><div style="float: left;width: 150px;">Digitization End Date:</div><input type="text" id="end_date" name="end_date"/></div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true" id="">Cancel</button>
        <button class="btn btn-primary"   data-dismiss="modal">Save changes</button>
    </div>
</div>
<script type="text/javascript">
    var stationName=null;
    
    
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
    var search_words=null;
    function makeToken(event){
    
        if (event.keyCode == 13 && $('#search_keyword').val()!='') {
            
            
            $('#tokens').append('<div class="token">'+$('#search_keyword').val()+'</div>');
            $('#search_keyword').val('');
            $('.token').last().html();
            
            if(token==0)
                search_words=$('.token').last().html();
            else
                search_words+=','+$('.token').last().html();
              
            $('#search_words').val(search_words);
            
            token=token+1;
            
        }
        if(token>0){
            $('#tokens').show();
        }
        else{
            $('#tokens').hide();
        }
        
    }
    
    function editStations(){
        var stations=new Array();
        $('input[name="station[]"]:checked').each(function(index,a){
            stations[index]=$(this).val();
        });
        if(stations.length>0){
            $.ajax({
                type: 'POST', 
                url: site_url+'stations/get_stations',
                data:{id:stations},
                dataType: 'json',
                cache: false,
                success: function (result) {
                    if(result.success==true){
                        var station_name='';
                        var compare_start_date=0;
                        var compare_end_date=0;
                        var start_date=false;
                        var end_date=false;
                        for(cnt in result.records){
                            if(cnt==0){
                                start_date=result.records[cnt].start_date;
                                end_date=result.records[cnt].end_date;
                            
                            }
                        
                            if(cnt>=result.records.length-1){
                                if(start_date==result.records[cnt].start_date){
                                    compare_start_date=0;
                                }
                                else{
                                    compare_start_date=1; 
                                }
                                if(end_date==result.records[cnt].end_date){
                                    compare_end_date=0;
                                }
                                else{
                                    compare_end_date=1; 
                                }
                            }
                        
                            if(cnt==result.records.length-1)
                                station_name+=result.records[cnt].station_name;
                            else
                                station_name+=result.records[cnt].station_name+',';
                        }
                        if(compare_start_date==0 && start_date!=null)
                            $('#start_date').val(start_date);
                        else if(compare_start_date==0 && start_date==null)
                            $('#start_date').val('');
                        else
                            $('#start_date').val('---');
                        if(compare_end_date==0 && end_date!=null)
                            $('#end_date').val(end_date);
                        else if(compare_end_date==0 && end_date==null)
                            $('#end_date').val('');
                        else
                            $('#end_date').val('---');
                        $('#subLabel').html('Record(s) being edited: '+station_name);
                        $('#station_id').val(stations);
                        $('#showPopUp').trigger('click');
                    }
                    else{
                        console.log(result);
                    }
                
                }
            });
        }
        
    }
    
</script>