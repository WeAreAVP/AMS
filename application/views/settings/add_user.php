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

$attributes = array('onsubmit' => 'return false;', 'id' => 'new_user');
?>

<center>
    <?php echo form_open_multipart($this->uri->uri_string(), $attributes); ?>
    <table class="table">



        <tr>
            <td width="150"><?php echo form_label('Email', $email['id']); ?></td>
            <td><?php echo form_input($email); ?></td>
        </tr>

        <?php if (isset($errors[$email['name']])) { ?>
            <tr><td></td>	
                <td style="color: red;"><?php echo form_error($email['name']); ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td width="150"><?php echo form_label('Password', $password['id']); ?></td>
            <td><?php echo form_input($password); ?></td>
        </tr>

        <?php if (isset($errors[$password['name']])) { ?>
            <tr><td></td>	
                <td style="color: red;"><?php echo form_error($password['name']); ?></td>
            </tr>
        <?php } ?>

        <tr>
            <td width="150"><?php echo form_label('First Name', $first_name['id']); ?></td>
            <td><?php echo form_input($first_name); ?></td>
        </tr>

        <?php if (isset($errors[$first_name['name']])) { ?>
            <tr><td></td>	
                <td style="color: red;"><?php echo form_error($first_name['name']); ?></td>
            </tr>
        <?php } ?>


        <tr>
            <td width="150"><?php echo form_label('Last Name', $last_name['id']); ?></td>
            <td><?php echo form_input($last_name); ?></td>
        </tr>

        <?php if (isset($errors[$last_name['name']])) { ?>
            <tr><td></td>	
                <td style="color: red;"><?php echo form_error($last_name['name']); ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td width="150"><?php echo form_label('Phone #', $phone_no['id']); ?></td>
            <td><?php echo form_input($phone_no); ?></td>
        </tr>

        <?php if (isset($errors[$phone_no['name']])) { ?>
            <tr><td></td>	
                <td style="color: red;"><?php echo form_error($phone_no['name']); ?></td>
            </tr>
        <?php } ?>
            
            <tr>
            <td width="150"><?php echo form_label('Country', $role['id']); ?></td>
            <td><?php echo form_dropdown($role['id'], $roles); ?></td>
        </tr>

        <?php if (isset($errors[$country['name']])) { ?>
            <tr><td></td>	
                <td style="color: red;"><?php echo form_error($country['name']); ?></td>
            </tr>
        <?php } ?>














        <tr>

            <td colspan="2" style="text-align: right;">
                
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>&nbsp;<?php echo form_submit('save', 'Save', 'class="btn primary" onclick="addUser(\'post\');" '); ?>
                
            </td>
        </tr>


    </table>

    <?php echo form_close(); ?>
</center>