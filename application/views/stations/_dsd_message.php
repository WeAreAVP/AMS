
<a href="#myDSDStationModal" data-toggle="modal" id="showDSDPopUp"></a>
<div class="modal hide" id="myDSDStationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Compose</h3>
    <p id="DSDLabel" style="font-size: 10px;"></p>
  </div>
  <div class="modal-body">

    
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
  function sendMessage(){
      var stations=new Array();
      $('input[name="station[]"]:checked').each(function(index,a){
        stations[index]=$(this).val();
      });
      if(stations.length>0){
        $.ajax({
          type: 'POST', 
          url: site_url+'stations/get_dsd_stations',
          data:{id:stations},
          dataType: 'json',
          cache: false,
          success: function (result) {
            if(result.success==true){
              var station_name='';
              var compare_start_date=0;
                        
                        
                        
              var start_date=false;
              for(cnt in result.records){
                if(cnt==0){
                  start_date=result.records[cnt].start_date;
                }
                if(cnt>=result.records.length-1){
                  if(start_date==result.records[cnt].start_date && compare_start_date==0){
                    compare_start_date=0;
                  }
                  else{
                    compare_start_date=1; 
                  }
                          
                         
                          
                }
                                                                                                                                                
                if(cnt==result.records.length-1)
                  station_name+=result.records[cnt].station_name;
                else
                  station_name+=result.records[cnt].station_name+',';
              }
              if(compare_start_date==0 && start_date!=0){
                $('#start_date').val(start_date);
                console.log(start_date);
              }
              else if(compare_start_date==0 && start_date==0){
                $('#start_date').val('');
                console.log('empty date');
              }
              else{
                console.log('conflicting dates');
              }
              $('#DSDLabel').html(station_name);
              $('#showDSDPopUp').trigger('click');
            }
            else{
              console.log(result);
            }
                                                                                                                                        
          }
        });
      }
                
    }
    
</script>

