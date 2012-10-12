
<table class="table">
    <tr>
        <td class="no_td_border">
            <b>Search Keyword</b>
        </td>
        <td class="no_td_border">
            <input type="input" id="search" name="search" placeholder="Enter Keyword"/>
        </td>
        <td>Certified</td>
        <td><select id="certified" name="certified">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select></td>
    </tr>
</table>

<table class="table table-bordered zebra-striped text-align">
    <thead>
        <tr>
            <th>CPB ID</th>
            <th>Station Name</th>
            <th>Contact Name</th>
            <th>Type</th>
            <th>Total Allocated Hours</th>
            <th>Start Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($stations) > 0) {
            foreach ($stations as $data) {
                ?>
                <tr>
                    <td><a href="<?php echo site_url('stations/detail/' . $data->id); ?>"><?php echo $data->cpb_id; ?></a></td>
                    <td><?php echo $data->station_name; ?></td>
                    <td><?php echo $data->contact_name; ?></td>
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
                    <td>
                        <?php echo $data->total_allocated; ?>
                    </td>
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
</script>