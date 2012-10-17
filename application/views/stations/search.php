<?php
if(!$is_ajax){
$search = array(
    'name' => 'search_keyword',
    'id' => 'search_keyword',
    'value' => set_value('search_keyword'),
		'onkeyup' => 'makeToken(event);',
);
$attributes = array('id' => 'search_form','onsubmit' => "return false;",'onkeypress' => "return event.keyCode != 13;");
echo form_open_multipart($this->uri->uri_string(), $attributes);
?>
<div class="row-fluid">
    <div class="span3">
        <div id="search_bar">
          <div id="tokens" style="display: none;"></div>
           <input  type="hidden" name="search_words" id="search_words"/>
            <div>
                <?php echo form_label('FILTER STATIONS', $search['id']); ?></b>
            </div>
            <div>
                <?php echo form_input($search); ?>
            </div>
            <div><input type="button" name="Search" value="Search" class="btn primary" onclick="search_station();" /></div>
        </div>


    </div>
    <?php echo form_close(); ?>
    <div class="span9">
    	<table class="tablesorter table table-bordered" id="station_table">
      <?php
      }?>
            <thead>
                <tr>
                    <td style"float:left"><input type='checkbox' name='all' value='' id='check_all'  class="check-all" onclick='javascript:checkAll();' /></td>
                    <th>Station Name</th>
                    <td>Contact Name</td>
                    <td>Contact Title</td>
                    <td>Type</td>
                    <td>Start Date</td>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($results['records']) && count($results['records']) > 0) {
                    foreach ($results['records'] as $data) {
                        ?>
                        <tr>
                            <td><input style='margin-left:15px;' type='checkbox' name='station[]' value='<?php echo $data->id; ?>'  class='checkboxes'/></td>
                            <td><?php echo $data->station_name; ?></td>
                            <td><?php echo $data->contact_name; ?></td>
                            <td><?php echo $data->contact_title; ?></td>
	                          <td><?php echo $data->my_type; ?></td>
                            <td><?php if (($data->start_date)==0) {?>
                                    <a id="<?php echo $data->id; ?>_date" href="#myModal"  data-toggle="modal" onclick="setStartDate('','<?php echo $data->station_name; ?>','<?php echo $data->id; ?>');">Set start date</a>
                                    <?php
                                } else {
                                    ?>
                                    <a id="<?php echo $data->id; ?>_date" href="#myModal"  data-toggle="modal" onclick="setStartDate('<?php echo date("Y-m-d",$data->start_date); ?>','<?php echo $data->station_name; ?>','<?php echo $data->id; ?>');"><?php echo date("Y-m-d",$data->start_date); ?></a>
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
            <?php
if(!$is_ajax){?>
        </table>
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
		function search_station(){
      var search_words=$('#search_words').val();
       $.ajax({
            type: 'POST', 
            url: '/index.php/stations/search',
            data:{"search_words":search_words},
            dataType: 'json',
            cache: false,
            success: function (result) { 
                if(result.success==true )
								{
                	 $('#station_table').html(result.html);
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
</script>
<?php }else{ exit();} ?>