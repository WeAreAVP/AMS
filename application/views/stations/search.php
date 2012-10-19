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
    <input type="hidden" name="search_words" id="search_words"/>
    <div> <?php echo form_label('FILTER STATIONS', $search['id']); ?></b> </div>
    <div id="tokens" style="display: none;"></div>
    <div> <?php echo form_input($search); ?><span class="input-search-img" onclick="add_remove_search();"></span> </div>
    <?php /*?> <div><input type="button" name="Search" value="Search" class="btn primary" onclick="search_station();" /></div><?php */?>
  </div>
</div>
<?php echo form_close(); ?>
<div class="span9">
  <div style="overflow: scroll;height: 600px;" >
    <table class="tablesorter table table-bordered" id="station_table">
      <?php
			}
			 if (isset($results['records']) && count($results['records']) > 0) {
      ?>
      <thead>
        <tr>
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
        </tr>
      </thead>
      <tbody><?php
      	foreach ($results['records'] as $data)
				{?>
          <tr>
            <td><?php echo $data->cpb_id; ?></td>
            <td><?php echo $data->station_name; ?></td>
            <td><?php echo $data->contact_name; ?></td>
            <td><?php echo $data->contact_title; ?></td>
            <td><?php echo $data->my_type; ?></td>
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
          </tr><?php
        }
			}
			else
			{?>
        <tr>
          <td colspan="11" style="text-align: center;"><b>No Station Found.</b></td>
        </tr><?php 
			} ?>
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
		var search_words='';
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
            url: '<?php echo site_url('stations/update_station_date')?>',
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
			search_words=$('#search_words').val();
    	   $.ajax({
            type: 'POST', 
            url: '<?php echo site_url('stations/search')?>',
            data:{"search_words":search_words},
           	success: function (result) { 
           	 $('#station_table').html(result);
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
				var search_id=$('#search_keyword').val()+random_id;
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
</script>
<?php }else{ exit();?>
<script type="text/javascript"> $("#station_table").tablesorter();</script>
 <?php } ?>
