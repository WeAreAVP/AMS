<?php
if (!$is_ajax) {
    $search = array(
        'name' => 'search_keyword',
        'id' => 'search_keyword',
        'value' => set_value('search_keyword'),
        'onkeyup' => 'makeToken(event);',
        'class' => 'span10'
    );
    $certified = array(
        'name' => 'certified',
        'id' => 'certified',
        'value' => set_value('certified'),
        'function' => 'onchange="search_station();"',
    );
    $agreed = array(
        'name' => 'agreed',
        'id' => 'agreed',
        'value' => set_value('agreed'),
        'function' => 'onchange="search_station();"',
    );

    $attributes = array('id' => 'search_form', 'onsubmit' => "return false;", 'onkeypress' => "return event.keyCode != 13;");

    echo form_open_multipart($this->uri->uri_string(), $attributes);
    ?>
    <div class="row-fluid">
        <div class="span3">
            <div id="search_bar">
                <b><h4>Filter Stations</h4></b>
                <input type="hidden" name="search_words" id="search_words"/>



                <div>
                    <?php echo form_label('Keyword(s):', $search['id']); ?></b>
                </div>
                <div id="tokens" style="display: none;"></div>
                <div class="input-append">
                    <?php echo form_input($search); ?><span class="add-on" onclick="add_remove_search();"><i class="icon-search"></i></span>
                </div>


                <div>
                    <?php echo form_label('Certified', $certified['id']); ?>
                </div>
                <div>
                    <?php echo form_dropdown($certified['id'], array('' => 'Select', '1' => 'Yes', '0' => 'No'), array(), $certified['function'] . 'id="' . $certified['id'] . '"'); ?>
                </div>
                <div>
                    <?php echo form_label('Agreed', $agreed['id']); ?>
                </div>
                <div>
                    <?php echo form_dropdown($agreed['id'], array('' => 'Select', '1' => 'Yes', '0' => 'No'), array(), $agreed['function'] . 'id="' . $agreed['id'] . '"'); ?>
                </div>
                <div class="filter-fileds"><a class="btn" onclick="resetStationFilter();">Reset</a></div>

            </div>


        </div>
        <?php echo form_close(); ?>
        <div  class="span9">
            <div class="alert" style="margin-bottom: 0px; margin-top: 0px;display: none;" id="success_message"></div>
            <div class="row" style="margin:5px 0px;">
                <a href="javascript://" class="btn btn-large" onclick="editStations();">Batch Edit</a>
            </div>



            <table class="tablesorter table table-bordered" id="station_table">
                <thead>
                    <tr>
                        <td><input type='checkbox' name='all' value='' id='check_all'  class="check-all" onclick='javascript:checkAll();' /></td>
                        <th>CPB ID</th>
                        <th>Station Name</th>
                        <th>Total Allocated Hours</th>
                        <th>Certified</th>
                        <th>Agreed</th>
                        <th>DSD</th>

                    </tr>
                </thead>
                <tbody id="append_record">
                <?php }
                ?>
                <?php
                if (count($stations) > 0) {
                    foreach ($stations as $data) {
                        ?>
                        <tr>
                            <td><input style='margin-left:18px;margin-right: 4px;' type='checkbox' name='station[]' value='<?php echo $data->id; ?>'  class='checkboxes'/></td>
                            <td><?php echo $data->cpb_id; ?></td>
                            <td><?php echo $data->station_name; ?></td>
                            <td><?php echo $data->total_allocated; ?></td>
                            <td><?php echo ($data->is_certified) ? 'Yes' : 'No'; ?>
                            <td><?php echo ($data->is_agreed) ? 'Yes' : 'No'; ?>
                            <td id="start_date_<?php echo $data->id; ?>">
                                <?php echo ($data->start_date == 0) ? 'No DSD' : date('Y-m-d', $data->start_date); ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr><td colspan="8" style="text-align: center;"><b>No Station Found.</b></td></tr>
                <?php } ?>
                <?php if (!$is_ajax) { ?>
                </tbody>
            </table>



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
            <div><div style="float: left;width: 130px;text-align: right;margin-right: 10px;">Digitization Start Date:</div><input type="text" id="start_date" name="start_date" value=""/><span id="start_date_message" style="display: none;color: #C09853;margin-left: 10px;">Please select date.</span></div>
            <div><div style="float: left;width: 130px;text-align: right;margin-right: 10px;">Digitization End Date:</div><input type="text" id="end_date" name="end_date" value=""/><span id="end_date_message" style="display: none;color: #C09853;margin-left: 10px;">Please select date.</span></div>
            <div><div style="float: left;width: 130px;text-align: right;margin-right: 10px;">Certified:</div><input type="checkbox" id="station_certified" name="station_certified" value="1"/></div>
            <div><div style="float: left;width: 130px;text-align: right;margin-right: 10px;">Agreed:</div><input type="checkbox" id="station_agreed" name="station_agreed" value="1"/></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true" id="">Cancel</button>
            <button class="btn btn-primary" onclick="validateFields();">Save</button>
        </div>
    </div>
    <a href="#confirmModel" data-toggle="modal" id="showConfirmPopUp"></a>
    <div class="modal hide" id="confirmModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>Are you sure you want to save?</h3>

        </div>

        <div class="modal-footer" style="text-align: left;">
            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="$('#showPopUp').trigger('click');">Go Back</button>
            <button class="btn" data-dismiss="modal" onclick="UpdateStations();">Save</button>
        </div>
    </div>
    <script type="text/javascript">
        var stationName=null;
    function validateFields(){
        if($('#start_date').val()=='' || $('#start_date').val()=='--' ||  $('#start_date').val()=='0000-00-00'){
            $('#start_date_message').show();
        }
        else{
            $('#start_date_message').hide();
        }
        if($('#end_date').val()=='' || $('#end_date').val()=='--'  ||  $('#end_date').val()=='0000-00-00'){
            $('#end_date_message').show();
                                                                                                                
        }
        else{
            $('#end_date_message').hide();
        }
        if($('#start_date').val()!='' && $('#start_date').val()!='--' && $('#start_date').val()!='0000-00-00' && $('#end_date').val()!='' && $('#end_date').val()!='--' && $('#end_date').val()!='0000-00-00'){
            $('#showPopUp').trigger('click');
            $('#showConfirmPopUp').trigger('click');
        }
                                                                                                                     
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
                                                                                                                 
    var search_words='';
    function makeToken(event)
    {
        if (event.keyCode == 13 )
        {
            add_remove_search();
        }
    }
    function remove_keword(id)
    {
        $("#"+id).remove();
        add_remove_search();
    }
    function add_remove_search()
    {
        var token=0;
        $('#search_words').val('');
        var my_search_words='';
        if($('#search_keyword').val()!='')
        {
            var random_id=rand(0,1000365);
            name=make_slug_name($('#search_keyword').val());
            var search_id=name+random_id;
            $('#tokens').append('<div class="btn-img" id="'+search_id+'" ><span class="search_keys">'+$('#search_keyword').val()+'</span><span class="btn-close-img" onclick="remove_keword(\''+search_id+'\')"></span></div>');
        }
        $('#search_keyword').val('');
                                                                                                            			
        $(".search_keys").each(function() {
            if(token==0)
                my_search_words=$(this).text();
            else
                my_search_words+=','+$(this).text();
            token=token+1;
        });
        if(my_search_words!='' && typeof(my_search_words)!=undefined)
        {
            $('#search_words').val(my_search_words);
        }
        if(token>0){
            $('#tokens').show();
        }
        else
        {
            $('#tokens').hide();
        }	
        search_station();
    }
    function make_slug_name(string){
        string = string.split('/').join('-');
        string = string.split('??').join('q');
        string = string.split(' ').join('');
        string = string.toLowerCase();
        return string;
    }
    function search_station(){
        search_words=$('#search_words').val();
        certified=$('#certified').val();
        agreed=$('#agreed').val();
        $.ajax({
            type: 'POST', 
            url: '<?php echo site_url('stations/index') ?>',
            data:{"search_words":search_words,certified:certified,agreed:agreed},
            success: function (result) { 
                $('#append_record').html(result);
                $("#station_table").trigger("update");  
                            
            }
        });
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
                        console.log(result.records);
                        var station_name='';
                        var compare_start_date=0;
                        var compare_end_date=0;
                        var compare_is_agreed=0;
                        var compare_is_certified=0;
                        var start_date=false;
                        var end_date=false;
                        var is_agreed=false;
                        var is_certified=false;
                        for(cnt in result.records){
                            if(cnt==0){
                                start_date=result.records[cnt].start_date;
                                end_date=result.records[cnt].end_date;
                                is_agreed=result.records[cnt].is_agreed;
                                is_certified=result.records[cnt].is_certified;
                                            
                            }
                            if(cnt>=result.records.length-1){
                                if(start_date==result.records[cnt].start_date && compare_start_date==0){
                                    compare_start_date=0;
                                }
                                else{
                                    compare_start_date=1; 
                                }
                                if(end_date==result.records[cnt].end_date && compare_end_date==0){
                                    compare_end_date=0;
                                }
                                else{
                                    compare_end_date=1; 
                                }
                                if(is_agreed==result.records[cnt].is_agreed && compare_is_agreed==0){
                                    compare_is_agreed=0;
                                }
                                else{
                                    compare_is_agreed=1; 
                                }
                                if(is_certified==result.records[cnt].is_certified && compare_is_certified==0){
                                    compare_is_certified=0;
                                }
                                else{
                                    compare_is_certified=1; 
                                }
                            }
                                                                                                                                    
                            if(cnt==result.records.length-1)
                                station_name+=result.records[cnt].station_name;
                            else
                                station_name+=result.records[cnt].station_name+',';
                        }
                        if(compare_start_date==0 && start_date!=0)
                            $('#start_date').val(start_date);
                        else if(compare_start_date==0 && start_date==0)
                            $('#start_date').val('');
                        else
                            $('#start_date').val('--');
                        if(compare_end_date==0 && end_date!=0)
                            $('#end_date').val(end_date);
                        else if(compare_end_date==0 && end_date==0)
                            $('#end_date').val('');
                        else
                            $('#end_date').val('--');
                        if(compare_is_certified==0){
                            if(is_certified==1)
                                $('#station_certified').attr('checked',true); 
                            else
                                $('#station_certified').attr('checked',false);
                        }
                        else{
                            $('#station_certified').attr('checked',false);
                        }
                        if(compare_is_agreed==0){
                            if(is_agreed==1)
                                $('#station_agreed').attr('checked',true); 
                            else
                                $('#station_agreed').attr('checked',false);
                        }
                        else{
                            $('#station_certified').attr('checked',false);
                        }
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
    function UpdateStations(){
        ids=$('#station_id').val();
        start_date=$('#start_date').val();
        end_date=$('#end_date').val();
        if($('#station_agreed').attr('checked')==undefined)
            agreed=0;
        else
            agreed=1;
        if($('#station_certified').attr('checked')==undefined)
            certified=0;
        else
            certified=1;
        $.ajax({
            type: 'POST', 
            url: site_url+'stations/update_stations',
            data:{id:ids,start_date:start_date,end_date:end_date,is_agreed:agreed,is_certified:certified},
            dataType: 'json',
            cache: false,
            success: function (result) {
                if(result.success==true){
                    $('#success_message').html('<strong>'+result.total+' Record(s) Changed.</strong> <a style="color:#C09853;text-decoration: underline;" href="'+site_url+'stations/undostations">Undo</a>');
                    $('#success_message').show();
                    ids=ids.split(',');
                    for(cnt in ids){
                        $('#start_date_'+ids[cnt]).html(start_date);
                                   
                    }
                }
                                                                                                                            
            }
        });
    }
    function frozenColumn(count){
        freezeColumn=count;
        search_station();
                                                        
    }
    function resetStationFilter(){
        $('#search_words').val('');
        $('#search_keyword').val('');
        $('#certified').prop('selectedIndex', 0);
        $('#agreed').prop('selectedIndex', 0);
        $('#tokens').html('');
        search_station();
    }
    </script>

    <?php
} else {
    exit();
    ?>
    <script type="text/javascript"> $("#station_table").tablesorter();</script>
<?php } ?>