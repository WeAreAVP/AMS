<?php
$email = array(
    'name' => 'email',
    'id' => 'email',
    'value' => set_value('email'),
);
$password = array(
    'name' => 'password',
    'id' => 'password',
    'value' => set_value('password'),
);
$first_name = array(
    'name' => 'first_name',
    'id' => 'first_name',
    'value' => set_value('first_name'),
);
$last_name = array(
    'name' => 'last_name',
    'id' => 'last_name',
    'value' => set_value('last_name'),
);
$phone_no = array(
    'name' => 'phone_no',
    'id' => 'phone_no',
    'value' => set_value('phone_no'),
);
$role = array(
    'name' => 'role',
    'id' => 'role',
    'value' => set_value('role'),
);
$station = array(
    'name' => 'station',
    'id' => 'station',
    'value' => set_value('station'),
);

$attributes = array('onsubmit' => 'return false;', 'id' => 'new_user');
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
            <td><?php echo form_password($password); ?><span style="color: red;"><?php echo form_error($password['name']); ?></span></td>
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

        <tr>
            <td width="150"><?php echo form_label('Role:', $role['id']); ?></td>
            <td><?php echo form_dropdown($role['id'], $roles,array(),'id="role" onchange="checkRole();"'); ?><span style="color: red;"><?php echo form_error($role['name']); ?></span></td>
        </tr>
        <tr style="display: none;" id="station_row">
            <td width="150"><?php echo form_label('Station:', $station['id']); ?></td>
            <td><?php echo form_dropdown($station['id'], $stations_list); ?><span style="color: red;"><?php echo form_error($station['name']); ?></span></td>
        </tr>


        <tr>

            <td colspan="2" style="text-align: right;">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>&nbsp;<?php echo form_submit('save', 'Save', 'class="btn btn-primary" onclick="manageUser(\'post\',\'add_user\');" '); ?>

            </td>
        </tr>


    </table>

    <?php echo form_close(); ?>
</center>