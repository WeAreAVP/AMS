<?php
if (count($station_detail) > 0) {
    ?>
    <center>
        <table class="table table-bordered zebra-striped" style="width: 50%;">
            <tr>
                <td>
                    CPB ID
                </td>
                <td> <?php echo $station_detail->cpb_id; ?></td>
            </tr>
            <tr>
                <td>
                    Station Name
                </td>
                <td><?php echo $station_detail->station_name; ?>

                </td>
            </tr>
            <tr>
                <td>
                    Contact Name
                </td>
                <td><?php echo $station_detail->contact_name; ?>
            </tr>
            <tr>
                <td>
                    Contact Title
                </td>
                <td><?php echo $station_detail->contact_title; ?>
            </tr>
            <tr>
                <td>
                    Type
                </td>
                <td>
                    <?php
                    if ($station_detail->type == 0)
                        echo 'Radio';
                    else if ($station_detail->type == 1)
                        echo 'TV';
                    else
                        echo 'Joint';
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Primary Address 
                </td>
                <td><?php echo $station_detail->address_primary; ?>
            </tr>
            <tr>
                <td>
                    Secondary Address
                </td>
                <td><?php echo $station_detail->address_secondary; ?>
            </tr>
            <tr>
                <td>
                    City
                </td>
                <td><?php echo $station_detail->city; ?>
            </tr>
            <tr>
                <td>
                    State
                </td>
                <td><?php echo $station_detail->state; ?>
            </tr>
            <tr>
                <td>
                    Zip
                </td>
                <td><?php echo $station_detail->zip; ?>
            </tr>
            <tr>
                <td>
                    Contact Phone
                </td>
                <td><?php echo $station_detail->contact_phone; ?>
            </tr>
            <tr>
                <td>
                    Contact Fax
                </td>
                <td><?php echo $station_detail->contact_fax; ?>
            </tr>
            <tr>
                <td>
                    Contact Email
                </td>
                <td><?php echo $station_detail->contact_email; ?>
            </tr>
            <tr>
                <td>
                    Allocated Hours
                </td>
                <td><?php echo $station_detail->allocated_hours; ?>
            </tr>
            <tr>
                <td>
                    Allocated Buffer
                </td>
                <td><?php echo $station_detail->allocated_buffer; ?>
            </tr>
            <tr>
                <td>
                    Total Allocated Hours
                </td>
                <td><?php echo $station_detail->total_allocated; ?>
            </tr>
            <tr>
                <td>
                    Certified
                </td>
                <td><?php echo ($station_detail->is_certified) ? 'Yes' : 'No'; ?>
            </tr>
            <tr>
                <td>
                    Agreed
                </td>
                <td><?php echo ($station_detail->is_agreed) ? 'Yes' : 'No'; ?>
            </tr>
            <tr>
                <td>
                    Start Date
                </td>
                <td><?php echo $station_detail->start_date; ?>
            </tr>
        </table></center>
    <?php ?>
    <?php
} else {
    echo '<h1>The requested sationed not found</h1>';
}
?>