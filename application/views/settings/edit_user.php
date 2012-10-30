<?php
$email = array(
    'name' => 'email',
    'id' => 'email',
    'value' => $user_info->email,
);
$password = array(
    'name' => 'password',
    'id' => 'password',
    'value' => set_value('password'),
);
$first_name = array(
    'name' => 'first_name',
    'id' => 'first_name',
    'value' => $user_info->first_name,
);
$last_name = array(
    'name' => 'last_name',
    'id' => 'last_name',
    'value' => $user_info->last_name,
);
$phone_no = array(
    'name' => 'phone_no',
    'id' => 'phone_no',
    'value' => $user_info->phone_no,
);
$role = array(
    'name' => 'role',
    'id' => 'role',
    'value' => $user_info->role_id,
);
$station = array(
    'name' => 'station',
    'id' => 'station',
    'value' => $user_info->station_id,
);
$attributes=null;
if(!isset($profile_edit))
$attributes = array('onsubmit' => 'return false;', 'id' => 'edit_from');
?>

<center>
    <?php echo form_open_multipart($this->uri->uri_string(), $attributes); ?>
    <table class="table no_border">



        <tr>
            <td width="150"><?php echo form_label('Email:', $email['id']); ?></td>
            <td><?php echo form_input($email); ?><span style="color: red;"><?php echo form_error($email['name']); ?></span></td>
        </tr>


        <tr>
            <td width="150"><?php echo form_label('Password:', $password['id']); ?></td>
            <td><?php echo form_password($password); ?><br/>Leave blank if you not want to change<span style="color: red;"><?php echo form_error($password['name']); ?></span></td>

        </tr>


        <tr>
            <td width="150"><?php echo form_label('First Name:', $first_name['id']); ?></td>
            <td><?php echo form_input($first_name); ?><span style="color: red;"><?php echo form_error($first_name['name']); ?></span></td>
        </tr>




        <tr>
            <td width="150"><?php echo form_label('Last Name:', $last_name['id']); ?></td>
            <td><?php echo form_input($last_name); ?><span style="color: red;"><?php echo form_error($last_name['name']); ?></span></td>
        </tr>


        <tr>
            <td width="150"><?php echo form_label('Phone #:', $phone_no['id']); ?></td>
            <td><?php echo form_input($phone_no); ?><span style="color: red;"><?php echo form_error($phone_no['name']); ?></span></td>
        </tr>


        <?php if(!isset($profile_edit)){ ?>
        <tr>
            <td width="150"><?php echo form_label('Role:', $role['id']); ?></td>
            <td><?php echo form_dropdown($role['id'], $roles, array($user_info->role_id)); ?><span style="color: red;"><?php echo form_error($role['name']); ?></span></td>
        </tr>
        <?php } ?>
        <tr>
            <td width="150"><?php echo form_label('Station:', $station['id']); ?></td>
            <td><?php echo form_dropdown($station['id'], $stations_list, array($user_info->station_id)); ?><span style="color: red;"><?php echo form_error($station['name']); ?></span></td>
        </tr>
        <tr style="background-color: whitesmoke;">
            <?php if (isset($profile_edit)) { ?>
            <td colspan="2">
                <?php echo form_submit('save', 'Save', 'class="btn btn-primary"'); ?>
            </td>
                
            <?php } else { ?>
                <td colspan="2" style="text-align: right;">

                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>&nbsp;<?php echo form_submit('save', 'Update', 'class="btn btn-primary" onclick="manageUser(\'post\',\'edit_user/' . $user_info->id . '\');" '); ?>

                </td>
            <?php } ?>
        </tr>


    </table>

    <?php echo form_close(); ?>
</center>