<div class="tab-content">
    <div id="users"  class="tab-pane">
        <table class="tablesorter table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Phone #</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($users) > 0) {
                    foreach ($users as $row) {
                        ?>

                        <tr>
                            <td><?php echo $row->email; ?></td>
                            <td><?php echo $row->first_name . $row->last_name; ?></td>
                            <td><?php echo $row->phone_no; ?></td>
                            <td>
                                <a href="<?php echo site_url('settings/edit_user/' . $row->id); ?>"><i class="icon-pencil" style="margin-right: 5px; margin-top: 2px;" > </i>Edit</a>&nbsp;|&nbsp;
                                <a href="<?php echo site_url('settings/delete_user/' . $row->id); ?>" ><i class="icon-trash" style="margin-right: 5px; margin-top: 2px;" > </i>Delete</a>
                            </td>
                        </tr>

                        <?php
                    }
                    ?>


                <?php } ?>
            </tbody>
        </table>
        <div style="text-align: center;"><a href="#myModal" data-toggle="modal" onclick="addUser('get');" ><i class="icon-plus-sign" style="margin-right: 5px; margin-top: 2px;" > </i>Add New</a></div>
    </div>

</div>

<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="stationLabel">Add User</h3>
    </div>
    <div class="modal-body"  id="add_user">

    </div>


</div>
<script type="text/javascript">
    function addUser(type){
        data=null;
        method='GET';
        if(type=='post'){
            data=$('#new_user').serialize();
            method='POST';
        }
        $.ajax({
            type: method, 
            url: site_url+'/settings/add_user',
            data:data,
            dataType: 'html',
            success: function (result) { 
                if(result=='done'){
                    window.location.reload();
                }
                else{
                    $('#add_user').html(result);  
                }
                
            }
        });
    }
</script>