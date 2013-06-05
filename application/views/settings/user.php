<?php
if ( ! $is_ajax)
{
	?>
	<div class="row-fluid">
		<?php
		$class = '';

		if ($current_role == 1 || $current_role == 2 || $current_role == 3 || $current_role == 20)
		{
			$class = 'span9';
			?>
			<div class="span3">
				<div id="search_bar">
					<h6 class="filter_title" id="filter_criteria" style="font-weight: bold;">FILTER</h6>
					<div style="background: #ebebeb;">


						<div class="sidebar-fields" style="border-bottom: 1px solid #DDD;">
							<div><label for="role_id">Role</label></div>
							<div>
								<div>
									<?php echo form_dropdown('role_id', $roles, array(), 'id="role_id" onchange="filterUser();"'); ?>
								</div>
							</div>
						</div>
						<div class="sidebar-fields" style="border-bottom: 1px solid #DDD;">
							<div><label for="station_id">Station</label></div>
							<div>
								<div>
									<?php echo form_dropdown('station_id', $stations, array(), 'id="station_id" onchange="filterUser();"'); ?>
								</div>
							</div>
						</div>


						<div class="sidebar-fields">
							<div class="filter-fileds"><a class="btn" onclick="resetFilter();">Reset</a></div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<div id="users"  class="<?php echo $class; ?>">

			<?php
			if (isset($this->session->userdata['saved']))
			{
				?><div class="alert alert-success notification" style="margin-bottom: 0px; margin-top: 0px;"><?php echo $this->session->userdata['saved']; ?></div><br/><?php } $this->session->unset_userdata('saved'); ?>
			<?php
			if (isset($this->session->userdata['updated']))
			{
				?><div class="alert alert-success notification" style="margin-bottom: 0px; margin-top: 0px;"><?php echo $this->session->userdata['updated']; ?></div><br/><?php } $this->session->unset_userdata('updated'); ?>
			<?php
			if (isset($this->session->userdata['deleted']))
			{
				?><div class="alert alert-error notification" style="margin-bottom: 0px; margin-top: 0px;"><?php echo $this->session->userdata['deleted']; ?></div><br/><?php } $this->session->unset_userdata('deleted'); ?>
			<?php
			if ($current_role == 1 || $current_role == 2 || $current_role == 3)
			{
				?>
				<div><a href="#myModal" data-toggle="modal" onclick="manageUser('get', 'add_user');" class="btn">ADD USER</a></div>
			<?php } ?>
			<table class="tablesorter table table-bordered" id="user_table_list">
				<thead>
					<tr style="background: #ebebeb;">
						<th style="width:160px;">Email</th>
						<th style="width:100px">Name</th>
						<th style="width: 110px;">Title</th>
						<th style="width: 200px;">Station</th>
						<th style="width:80px;">Role</th>
						<?php
						if ($current_role == 1 || $current_role == 2 || $current_role == 3)
						{
							?>
							<td style="width: 33px;"></td>
						<?php } ?>
					</tr>
				</thead>
				<tbody id="user_list">
				<?php } ?>
				<?php
				if (count($users) > 0)
				{
					foreach ($users as $row)
					{
						?>

						<tr>
							<td><?php echo $row->email; ?></td>
							<td><?php echo $row->first_name . ' ' . $row->last_name; ?></td>
							<td><?php echo $row->title; ?></td>
							<td><?php echo $row->st_name; ?></td>
							<td><?php echo $row->role_name; ?></td>
							<?php
							if ($current_role == 1 || $current_role == 2 || $current_role == 3)
							{
								?>
								<td>


									<a title="Edit User" href="#myModal" data-toggle="modal" onclick="manageUser('get', 'edit_user/<?php echo $row->id; ?>');"><i class="icon-cog"></i></a>
									<a title="Delete User" href="#deleteModel" data-toggle="modal" onclick="deleteUser('<?php echo $row->id; ?>', '<?php echo $row->first_name . ' ' . $row->last_name; ?>')" ><i class="icon-remove-sign"></i></a>

								</td>
							<?php } ?>
						</tr>

						<?php
					}
					?>


					<?php
				}
				else
				{
					?>
						<tr><td colspan="6" style="text-align: center;">No User Found.</td></tr>
					<?php
				} if ( ! $is_ajax)
				{
					?>
				</tbody>
			</table>

		</div>


	</div>



	<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 680px;" >
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="userLabel">Add User</h3>
		</div>
		<div class="modal-body"  id="manage_user" style="max-height: 460px !important">

		</div>


	</div>
	<div class="modal hide" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="userDelete">Are you sure you want to delete?</h3>
		</div>

		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
			<a id="delete_user_btn" class="btn btn-danger"  href="">Yes</a>

		</div>
	</div>
	<script type="text/javascript">
								setTimeout(function() {
									$('.notification').hide();
								}, 5000);


								function manageUser(type, uType) {
									data = null;
									method = 'GET';
									if (uType == 'add_user')
										$('#userLabel').html('Add User');
									else
										$('#userLabel').html('Edit User');
									if (type == 'post') {
										if (uType == 'add_user')
											data = $('#new_user').serialize();
										else
											data = $('#edit_from').serialize();
										method = 'POST';
									}
									$.ajax({
										type: method,
										url: site_url + 'settings/' + uType,
										data: data,
										dataType: 'html',
										success: function(result) {
											if (result == 'done') {
												window.location.reload();
											}
											else {
												$('#manage_user').html(result);
												checkRole();
											}

										}
									});
								}
								function deleteUser(userID, name) {
									$('#userDelete').html('Are you sure you want to delete "' + name + '"?');
									$('#delete_user_btn').attr('href', site_url + '/settings/delete_user/' + userID);
								}
								function filterUser() {

									role = $('#role_id').val();
									station = $('#station_id').val();
									$.ajax({
										type: 'POST',
										url: site_url + 'settings/users',
										data: {role_id: role, station_id: station},
										//                dataType: 'html',
										success: function(result) {
											$('#user_list').html(result);
											$("#user_table_list").trigger("update");

										}
									});
								}
								function resetFilter() {
									$('#station_id').prop('selectedIndex', 0);
									$('#role_id').prop('selectedIndex', 0);
									filterUser();
								}
								function checkRole() {
									role = $('#role').val();

									if (role == 3 || role == 4) {
										$('#station_row').show();
									}
									else
										$('#station_row').hide();
								}
	</script>
<?php }
?>