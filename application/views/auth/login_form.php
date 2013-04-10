<?php
$username = array(
	'name' => 'username',
	'id' => 'username',
	'size' => 30,
	'value' => set_value('username')
);

$password = array(
	'name' => 'password',
	'id' => 'password',
	'size' => 30
);

$remember = array(
	'name' => 'remember',
	'id' => 'remember',
	'value' => 1,
	'checked' => set_value('remember'),
	'style' => 'float:left;margin-right:5px;'
);

$confirmation_code = array(
	'name' => 'captcha',
	'id' => 'captcha',
	'maxlength' => 8
);
$attributes = 'class="form-horizontal"';
?>

<div class="span12" style="margin-top: 50px;">
	<legend>Sign in</legend>
	<?php echo form_open($this->uri->uri_string(), $attributes) ?>
	<?php if ($this->dx_auth->get_auth_error())
	{
		?>
		<div class="alert alert-error"><?php echo $this->dx_auth->get_auth_error(); ?></div>
<?php } ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $username['id']; ?>"><?php echo form_label('Email:', $username['id']); ?></label>
		<div class="controls">
<?php echo form_input($username) ?>
			<div class="help-block" style="color: #B94A48;"> <?php echo form_error($username['name']); ?></div>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="<?php echo $password['id']; ?>"><?php echo form_label('Password:', $password['id']); ?></label>
		<div class="controls">
<?php echo form_password($password) ?>
			<div class="help-block" style="color: #B94A48;"> <?php echo form_error($password['name']); ?></div>
		</div>
	</div>

<?php if ($show_captcha): ?>

		<dt>Enter the code exactly as it appears. There is no zero.</dt>
		<dd><?php echo $this->dx_auth->get_captcha_image(); ?></dd>

		<dt><?php echo form_label('Confirmation Code', $confirmation_code['id']); ?></dt>
		<dd>
			<?php echo form_input($confirmation_code); ?>
	<?php echo form_error($confirmation_code['name']); ?>
		</dd>

<?php endif; ?>



	<!--        <div class="controls">
<?php echo form_checkbox($remember); ?><?php echo form_label('Remember me', $remember['id']); ?>
			</div>-->
	<div class="controls">
<?php echo anchor($this->dx_auth->forgot_password_uri, 'Forgot password'); ?> 



		<?php
		if ($this->dx_auth->allow_registration)
		{
			echo ' |  ' . anchor($this->dx_auth->register_uri, 'Register');
		};
		?>
	</div>


	<div class="controls"><?php echo form_submit('login', 'Sign in', 'class="btn btn-inverse"'); ?></div>


<?php echo form_close() ?>

</div>

