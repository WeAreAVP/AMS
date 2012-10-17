<div class="row-fluid">
     <div  class="span9">
        <div style="overflow: scroll;height: 600px;" >
            <table class="tablesorter table table-bordered" id="station_table">
                <thead>
                    <tr>
                        <th><span style="float:left;min-width:50px;">Name</span></th>
                        <th><span style="float:left;min-width:80px;">Subject</span></th>
                        <th><span style="float:left;min-width:90px;">Reply To</span></th>
                        <th><span style="float:left;min-width:80px;">From</span></th>
                        <th><span style="float:left;min-width:30px;">Template Type</span></th>
                        <th><span style="float:left;min-width:80px;">Replaceables</span></th>
                        <th><span style="float:left;min-width:80px;">Action</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($templates) > 0)
										{
                        foreach ($templates as $data)
												{?>
                            <tr>
                                <td><a href="<?php echo site_url('templatemanager/details/' . $data->id); ?>"><?php echo $data->system_id; ?></a></td>
                                <td><?php echo $data->subject; ?></td>
                                <td><?php echo $data->reply_to; ?></td>
                                <td><?php echo $data->contact_title; ?></td>
                                <td><?php echo $data->email_from; ?></td>
                                <td><?php echo $data->email_type; ?></td>
                                <td><?php echo $data->replaceables; ?></td>
                                <td>
                                	<a href="<?php echo site_url('templatemanager/delete/' . $data->id)?>" >Delete</a> | 
                                	<a href="<?php echo site_url('templatemanager/edit/' . $data->id)?>" >Edit</a>
                               	</td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr><td colspan="11" style="text-align: center;"><b>No Template Found.</b></td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>