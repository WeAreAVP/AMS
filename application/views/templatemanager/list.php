<div class="row-fluid">
     <div  class="span12">
     		<?php if (isset($message) && !empty($message)) { ?><div class="alert alert-success notification" style="margin-bottom: 0px; margin-top: 0px;">Template <?php echo ucfirst($message)?> Successfully</div><br/><?php } ?>

            <table class="tablesorter table table-bordered">
              <?php
                    if (isset($templates) && $templates && count($templates) > 0)
										{
                      ?>
                <thead>
                    <tr>
                        <td><span style="float:left;min-width:50px;">Name</span></td>
                        <td><span style="float:left;min-width:80px;">Subject</span></td>
                        <td><span style="float:left;min-width:90px;">Reply To</span></td>
                        <td><span style="float:left;min-width:80px;">From</span></td>
                        <td><span style="float:left;min-width:30px;">Template Type</span></td>
                        <td><span style="float:left;min-width:80px;">Replaceables</span></td>
                        <td><span style="float:left;min-width:80px;">Action</span></td>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    foreach ($templates as $data)
												{?>
                            <tr>
                                <td><a href="<?php echo site_url('templatemanager/details/' . $data->id); ?>"><?php echo $data->system_id; ?></a></td>
                                <td><?php echo $data->subject; ?></td>
                                <td><?php echo $data->reply_to; ?></td>
                                <td><?php echo $data->email_from; ?></td>
                                <td><?php echo $data->email_type; ?></td>
                                <td><?php echo $data->replaceables; ?></td>
                                <td>                   
                                	<a href="<?php echo site_url('templatemanager/edit/' . $data->id)?>" ><i class="icon-pencil" style="margin-right: 5px; margin-top: 2px;" > </i>Edit</a>&nbsp;|&nbsp;
                                	<a href="<?php echo site_url('templatemanager/delete/' . $data->id)?>" ><i class="icon-trash" style="margin-right: 5px; margin-top: 2px;" > </i>Delete</a>
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
              <div style="text-align: center;"><a href="<?php echo site_url('templatemanager/add');?>"  ><i class="icon-plus-sign" style="margin-right: 5px; margin-top: 2px;" > </i>Add New</a></div>
    </div>
</div>