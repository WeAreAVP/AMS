<?php
if ( ! $is_ajax)
{
	$search = array(
		'name' => 'search_keyword',
		'id' => 'search_keyword',
		'value' => set_value('search_keyword'),
		'onkeyup' => 'makeToken(event);',
		'class' => 'span10',
		'style' => 'width:182px;'
	);
	$certified = array(
		'name' => 'certified',
		'id' => 'certified',
		'value' => set_value('certified'),
		'function' => 'onchange="search_station();"',
	);
	$agreed = array(
		'name' => 'agreed',
		'id' => 'agreed',
		'value' => set_value('agreed'),
		'function' => 'onchange="search_station();"',
	);
	$start_date_range = array(
		'name' => 'start_date_range',
		'id' => 'start_date_range',
		'value' => set_value('start_date_range'),
		'function' => 'onchange="search_station();"',
	);
	$end_date_range = array(
		'name' => 'end_date_range',
		'id' => 'end_date_range',
		'value' => set_value('end_date_range'),
		'function' => 'onchange="search_station();"',
	);

	$attributes = array('id' => 'search_form', 'onsubmit' => "return false;", 'onkeypress' => "return event.keyCode != 13;");

	echo form_open_multipart($this->uri->uri_string(), $attributes);
	?>
	<div class="row-fluid">
		<?php
		$class = 'span12';
		if ( ! $this->is_station_user)
		{
			$class = 'span9'
			?>
			<div class="span3">
				<div id="search_bar">
					<h6 class="filter_title" id="filter_criteria" style="font-weight: bold;">FILTER</h6>
					<div style="background: #ebebeb;">
						<div id="tokens" style="display: none;padding: 5px;"></div>
						<input type="hidden" name="search_words" id="search_words"/>
						<div class="sidebar-fields" style="border-bottom: 1px solid #DDD;">
							<div><?php echo form_label('Keyword(s):', $search['id']); ?></div>
							<div>
								<div class="input-append">
									<?php echo form_input($search); ?><span class="add-on" onclick="add_remove_search();"><i class="icon-search"></i></span>
								</div>
							</div>
						</div>

						<div class="sidebar-fields" style="border-bottom: 1px solid #DDD;">
							<div><?php echo form_label('Certified', $certified['id']); ?></div>
							<div><?php echo form_dropdown($certified['id'], array('' => 'Select', '1' => 'Yes', '0' => 'No'), array(), $certified['function'] . 'id="' . $certified['id'] . '"'); ?></div>
						</div>
						<div class="sidebar-fields" style="border-bottom: 1px solid #DDD;">
							<div><?php echo form_label('Agreed', $agreed['id']); ?></div>
							<div><?php echo form_dropdown($agreed['id'], array('' => 'Select', '1' => 'Yes', '0' => 'No'), array(), $agreed['function'] . 'id="' . $agreed['id'] . '"'); ?></div>
						</div>
						<!--						<div class="sidebar-fields">
													<div><?php echo form_label('Digitization Start Date Range', $start_date_range['id']); ?></div>
													<div><?php echo form_input($start_date_range); ?></div>
												</div>
												<div class="sidebar-fields">
													<div><?php echo form_label('Digitization End Date Range', $end_date_range['id']); ?></div>
													<div><?php echo form_input($end_date_range); ?></div>
												</div>-->
						<div class="sidebar-fields">
							<div class="filter-fileds"><a class="btn" onclick="resetStationFilter();">Reset</a></div>
						</div>
					</div>
				</div>
			</div>
		<?php }
		?>
		<?php echo form_close(); ?>
		<div  class="<?php echo $class; ?>">
			<div class="alert" style="margin-bottom: 0px; margin-top: 0px;display: none;" id="success_message"></div>
			<?php
			if (isset($this->session->userdata['sent']))
			{
				?><div class="alert" style="margin-bottom: 0px; margin-top: 0px;"><?php echo $this->session->userdata['sent']; ?></div><br/><?php } $this->session->unset_userdata('sent'); ?>
			<?php
			if ( ! $this->is_station_user && $this->role_id != '20')
			{
				?>
				<div class="row" style="margin:5px 0px;">
					<a href="javascript://" class="btn" onclick="editStations();">Batch Edit</a>
					<a href="#updateStations" role="button" class="btn" data-toggle="modal" data-backdrop="static">Update Station Records</a>
					<a href="javascript://" class="btn" onclick="checkStations();">Send Message</a>
				</div>

			<?php } ?>

			<table class="tablesorter table table-bordered" id="station_table">
				<thead>
					<tr style="background: #ebebeb;">
						<?php
						if ( ! $this->is_station_user && $this->role_id != '20')
						{
							?>
						<td style="width: 40px;"><input type='checkbox' name='all' value='' id='check_all'  class="check-all" onclick='javascript:checkAll();' /></td>
						<?php } ?>
						<th style="width:65px;">CPB ID</th>
						<th style="width: 227px;">Station Name</th>
						<th style="width:155px;">Total Allocated Hours</th>
						<th style="width:73px;">Certified</th>
						<th style="width:68px;">Agreed</th>
						<th style="width:80px;">DSD</th>

					</tr>
				</thead>
				<tbody id="append_record">
				<?php }
				?>
				<?php
				if (count($stations) > 0)
				{
					foreach ($stations as $data)
					{
						?>
						<tr>
							<?php
							if ( ! $this->is_station_user && $this->role_id != '20')
							{
								?>
								<td><input style='margin-left:18px;margin-right: 4px;' type='checkbox' name='station[]' value='<?php echo $data->id; ?>'  class='checkboxes'/></td>
							<?php } ?>
							<td><?php echo $data->cpb_id; ?></td>
							<td><a href="<?php echo site_url('stations/detail/' . $data->id); ?>" id="station_name_<?php echo $data->id; ?>"><?php echo $data->station_name; ?></a></td>
							<td><?php echo $data->total_allocated; ?></td>
							<td id="certified_<?php echo $data->id; ?>"><?php echo ($data->is_certified) ? 'Yes' : 'No'; ?>
							<td id="agreed_<?php echo $data->id; ?>"><?php echo ($data->is_agreed) ? 'Yes' : 'No'; ?>
							<td id="start_date_<?php echo $data->id; ?>">
								<?php echo ($data->start_date == 0) ? 'No DSD' : date('Y-m-d', $data->start_date); ?>
							</td>
						</tr>
						<?php
					}
				}
				else
				{
					?>
					<tr><td colspan="8" style="text-align: center;"><b>No Station Found.</b></td></tr>
				<?php } ?>
				<?php
				if ( ! $is_ajax)
				{
					?>
				</tbody>
			</table>
		</div>
	</div>

	<?php $this->load->view('stations/_edit_station'); ?>
	<?php $this->load->view('stations/_messages'); ?>
	<?php $this->load->view('stations/_update_stations'); ?>


	<script type="text/javascript">
										var stationName = null;
										var search_words = '';
										function makeToken(event)
										{
											if (event.keyCode == 13)
											{
												add_remove_search();
											}
										}
										function remove_keword(id)
										{
											$("#" + id).remove();
											add_remove_search();
										}
										function add_remove_search()
										{
											var token = 0;
											$('#search_words').val('');
											var my_search_words = '';
											if ($('#search_keyword').val() != '')
											{
												var random_id = rand(0, 1000365);
	//												name = make_slug_name($('#search_keyword').val());
												var search_id = 'search_' + random_id;
												$('#tokens').append('<div class="btn-img" id="' + search_id + '" ><span class="search_keys">' + $('#search_keyword').val() + '</span><span class="btn-close-img" onclick="remove_keword(\'' + search_id + '\')"></span></div>');
											}
											$('#search_keyword').val('');

											$(".search_keys").each(function() {
												if (token == 0)
													my_search_words = $(this).text();
												else
													my_search_words += ',' + $(this).text();
												token = token + 1;
											});
											if (my_search_words != '' && typeof(my_search_words) != undefined)
											{
												$('#search_words').val(my_search_words);
											}
											if (token > 0) {
												$('#tokens').show();
											}
											else
											{
												$('#tokens').hide();
											}
											search_station();
										}
										function make_slug_name(string) {
											string = string.split('/').join('-');
											string = string.split('??').join('q');
											string = string.split(' ').join('');
											string = string.toLowerCase();
											return string;
										}
										function search_station() {
											search_words = $('#search_words').val();
											certified = $('#certified').val();
											agreed = $('#agreed').val();

											$.ajax({
												type: 'POST',
												url: '<?php echo site_url('stations/index') ?>',
												data: {"search_words": search_words, certified: certified, agreed: agreed},
												success: function(result) {
													$('#append_record').html(result);
													$("#station_table").trigger("update");

												}
											});
										}

										function resetStationFilter() {
											$('#search_words').val('');
											$('#search_keyword').val('');
											$('#certified').prop('selectedIndex', 0);
											$('#agreed').prop('selectedIndex', 0);
											$('#tokens').html('');
											search_station();
										}


	</script>

<?php }
?>