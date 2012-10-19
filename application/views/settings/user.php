<div class="row-fluid">
    <div id="users"  class="span12">
        
        <?php if (isset($this->session->userdata['saved'])) { ?><div class="alert alert-success notification" style="margin-bottom: 0px; margin-top: 0px;"><?php echo $this->session->userdata['saved']; ?></div><br/><?php } $this->session->unset_userdata('saved'); ?>
        <?php if (isset($this->session->userdata['updated'])) { ?><div class="alert alert-success notification" style="margin-bottom: 0px; margin-top: 0px;"><?php echo $this->session->userdata['updated']; ?></div><br/><?php } $this->session->unset_userdata('updated'); ?>
        <?php if (isset($this->session->userdata['deleted'])) { ?><div class="alert alert-error notification" style="margin-bottom: 0px; margin-top: 0px;"><?php echo $this->session->userdata['deleted']; ?></div><br/><?php } $this->session->unset_userdata('deleted'); ?>

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
                            <td><?php echo $row->first_name .' '. $row->last_name; ?></td>
                            <td><?php echo $row->phone_no; ?></td>
                            <td>
                                <a href="#myModal" data-toggle="modal" onclick="manageUser('get','edit_user/<?php echo $row->id; ?>');"><i class="icon-pencil" style="margin-right: 5px; margin-top: 2px;" > </i>Edit</a>&nbsp;|&nbsp;
                                <a href="#deleteModel" data-toggle="modal" onclick="deleteUser('<?php echo $row->id; ?>')" ><i class="icon-trash" style="margin-right: 5px; margin-top: 2px;" > </i>Delete</a>
                            </td>
                        </tr>

                        <?php
                    }
                    ?>


                <?php } ?>
            </tbody>
        </table>
        <div style="text-align: center;"><a href="#myModal" data-toggle="modal" onclick="manageUser('get','add_user');" ><i class="icon-plus-sign" style="margin-right: 5px; margin-top: 2px;" > </i>Add New</a></div>
    </div>

</div>

<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="userLabel">Add User</h3>
    </div>
    <div class="modal-body"  id="manage_user">

    </div>


</div>
<div class="modal hide" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="stationLabel">Are you sure you want to delete?</h3>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
        <a id="delete_user_btn" class="btn btn-primary"  href="">Yes</a>

    </div>
</div>
<script type="text/javascript">
    setTimeout(function(){
        $('.notification').hide();
    },5000);
    function manageUser(type,uType){
        data=null;
        method='GET';
        if(uType=='add_user')
            $('#userLabel').html('Add User');
        else
            $('#userLabel').html('Edit User');
        if(type=='post'){
            if(uType=='add_user')
                data=$('#new_user').serialize();
            else
                data=$('#edit_from').serialize();
            method='POST';
        }
        $.ajax({
            type: method, 
            url: site_url+'settings/'+uType,
            data:data,
            dataType: 'html',
            success: function (result) { 
                if(result=='done'){
                    window.location.reload();
                }
                else{
                    $('#manage_user').html(result);  
                }
                
            }
        });
    }
    function deleteUser(userID){
        $('#delete_user_btn').attr('href',site_url+'/settings/delete_user/'+userID);
    }
</script>