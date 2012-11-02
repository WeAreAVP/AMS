<?php if (count($station_detail) > 0) { ?>
    <div style="margin: 0px -20px;">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('stations/index'); ?>">Stations</a> <span class="divider">/</span></li>
            <li class="active"><?php echo $station_detail->station_name; ?></li>
        </ul> 
        <h2>Station Information</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>CPB ID</th>
                    <th>Station Name</th>
                    <th>Allocated Hours</th>
                    <th>Allocated Buffer</th>
                    <th>Total Allocated Hours</th>
                    <th>Certified</th>
                    <th>Agreed</th>
                    <th>DSD</th>
                    <th>DED</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $station_detail->cpb_id; ?></td>
                    <td><?php echo $station_detail->station_name; ?></td>
                    <td><?php echo $station_detail->allocated_hours; ?></td>
                    <td><?php echo $station_detail->allocated_buffer; ?></td>
                    <td><?php echo $station_detail->total_allocated; ?></td>
                    <td id="certified_"><?php echo ($station_detail->is_certified) ? 'Yes' : 'No'; ?></td>
                    <td id="agreed_"><?php echo ($station_detail->is_agreed) ? 'Yes' : 'No'; ?></td>
                    <td id="dsd_"><?php echo ($station_detail->start_date) ? $station_detail->start_date : 'NO DSD'; ?></td>
                    <td id="ded_"><?php echo ($station_detail->end_date) ? $station_detail->end_date : 'NO DED'; ?></td>
                    <td><a href="#myStationModal" data-toggle="modal" onclick="editSingleStation('<?php echo $station_detail->start_date; ?>','<?php echo $station_detail->end_date; ?>','<?php echo $station_detail->is_certified; ?>','<?php echo $station_detail->is_agreed; ?>');"><i class="icon-cog"></i></a></td>
                </tr>
            </tbody> 
        </table>
        <h2>Station Address</h2>
        <div class="row">
            <div class="span4">
                <!--          <h2>Heading</h2>-->
                <p>
                    <?php echo $station_detail->address_primary; ?><br/><?php echo $station_detail->city; ?>, <?php echo $station_detail->state; ?> <?php echo $station_detail->zip; ?>
                    <br/>Phone: <?php echo $station_detail->contact_phone; ?>
                    <br/>Fax: <?php echo $station_detail->contact_fax; ?>
                    <br/>Email: <?php echo $station_detail->contact_email; ?>
                </p>
            </div>

        </div>
        <h2>Station Contacts</h2>
        <div class="row">
            <?php if (count($station_contacts) > 0) { ?>

                <?php
                foreach ($station_contacts as $key => $value) {
                    ?>

                    <div class="span3">
                        <h4><?php echo $value->first_name . ' ' . $value->last_name; ?></h4>
                        <h4><?php echo ($value->role_id == 3) ? 'Station Admin' : 'Station User'; ?></h4>
                        <h4><?php if ($station_detail->type == 0) echo 'Radio'; else if ($station_detail->type == 1) echo 'TV'; else echo 'Joint'; ?></h4>
                        <p>
                            <?php echo $value->address; ?>
                            <br/>Phone: <?php echo $value->phone_no; ?>
                            <br/>Fax: <?php echo $value->fax; ?>
                            <br/>Email: <?php echo $value->email; ?>
                        </p>
                    </div>

                <?php }
                ?>
            <?php } else { ?>
                <div style="text-align: center;">No Station Contact Available.</div>
            <?php } ?>
        </div>
        <h2>Tracking Information</h2>
        <?php if (count($station_tracking) > 0) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ship Date</th>
                        <th>To</th>
                        <th>Shipped Via</th>
                        <th>Tracking Number</th>
                        <th># Boxes Shipped</th>
                        <th style="width: 35px;"></th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($station_tracking as $value) { ?>
                        <tr>
                            <td><?php echo $value->ship_date; ?></td>
                            <td><?php echo $value->ship_to; ?></td>
                            <td><?php echo $value->ship_via; ?></td>
                            <td><?php echo $value->tracking_no; ?></td>
                            <td><?php echo $value->no_box_shipped; ?></td>
                            <td><a href="#trackingModel" data-toggle="modal" onclick="manageTracking('get','edit','<?php echo $value->id; ?>');"><i class="icon-cog"></i></a>
                                <a  href="#deleteTracingModel" data-toggle="modal" onclick="deleteTracking('<?php echo $value->id; ?>','<?php echo $station_detail->id; ?>');"><i class="icon-remove-sign"></i></a>
                            </td>
                        </tr>


                        </div>
                    <?php } ?>
                </tbody>
            </table>


        <?php } else { ?>
            <div style="text-align: center;">No Tracking Information available for station <?php echo $station_detail->station_name; ?>.</div>
        <?php } ?>
        <div><a href="#trackingModel" class="btn btn-large" data-toggle="modal" onclick="manageTracking('get','add','<?php echo $station_detail->id; ?>');">Add Shipment</a></div>
        <?php $this->load->view('stations/_edit_station'); ?>






    <?php } else { ?>
        <h3>The requested sation not found</h3>
        <a href="<?php echo site_url('stations/index'); ?>">Back to Stations</a>

    <?php } ?>


    <div class="modal hide" id="trackingModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 680px;" >
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="TrackingLabel">Add User</h3>
        </div>
        <div class="modal-body"  id="manage_tracking" style="max-height: 460px !important">

        </div>


    </div>

    <div class="modal hide" id="deleteTracingModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="trackingDelete">Are you sure you want to delete?</h3>
        </div>

        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
            <a id="delete_tracking_btn" class="btn btn-danger"  href="">Yes</a>

        </div>
    </div>
    <script type="text/javascript">
        function manageTracking(type,action,id){
            data=null;
            method='GET';
        
            if(action=='add'){
                $('#TrackingLabel').html('Add Shipment');
            }
            else{
                $('#TrackingLabel').html('Edit Shipment');
            }
            if(type=='post'){
                if(action=='add')
                    data=$('#tracking_new_form').serialize();
                else
                    data=$('#tracking_edit_form').serialize();
                method='POST';
            }
            $.ajax({
                type: method, 
                url: site_url+'tracking/'+action+'/'+id,
                data:data,
                dataType: 'html',
                success: function (result) { 
                    if(result=='done'){
                        window.location.reload();
                    }
                    else{
                        $('#manage_tracking').html(result); 
                 
                    }
                                        
                }
            });
        }
        function deleteTracking(trackingID,stationID){
            $('#delete_tracking_btn').attr('href',site_url+'/tracking/delete/'+trackingID+'/'+stationID);
        }
    </script>