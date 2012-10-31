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
        <h2>Station Contacts</h2>
        <h2>Tracking Information</h2>
<!--        <table class="table table-bordered">
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
                <tr>
                    <td>1/3/12</td>
                    <td>Station</td>
                    <td>FedEx</td>
                    <td>67GHTY88965</td>
                    <td>5</td>
                    <td><i class="icon-cog"></i><i class="icon-remove-sign"></i></td>
                </tr>
            </tbody>
        </table>-->
        
    </div>
    <?php $this->load->view('stations/_edit_station'); ?>






    <?php
} else {
    echo '<h3>The requested sationed not found</h3>';
}
?>