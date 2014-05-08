
<form name="form_search" id="form_search" method="post" onsubmit="return false;" style="margin: 0;padding: 0;">
    <div id="search_bar_val" class="facet-search"> 
        <h6 class="filter_title" id="filter_criteria" style="display: none;font-weight: bold;">FILTER CRITERIA&nbsp;<span id="filter_record_count"></span></h6>
        <div id="tokens">
            <!-- Checked Token Start  -->
            <div id="checked_token">
				<?php
				if (isset($this->session->userdata['digitized']) && $this->session->userdata['digitized'] === '1')
				{
					?>
					<div class="btn-img" id="digitized_token" ><span class="search_keys">Reformatted</span><i class="icon-remove-sign" style="float: right;" onclick="remove_checked_token('digitized')"></i></div>
					<?php
				}
				if (isset($this->session->userdata['migration_failed']) && $this->session->userdata['migration_failed'] === '1')
				{
					?>
					<div class="btn-img" id="migration_failed_token" ><span class="search_keys">Migration Failed</span><i class="icon-remove-sign" style="float: right;" onclick="remove_checked_token('migration_failed')"></i></div>
				<?php }
				?>
            </div>

            <!-- Checked Token End  -->

            <!-- Custom  Search Display End  -->

			<?php
			if (isset($this->session->userdata['custom_search']) && $this->session->userdata['custom_search'] != '')
			{
				?>
				<div id="keyword_field_main">
					<input type="hidden" id="keyword_field_main_search" name="keyword_field_main_search" value="<?php echo htmlentities(json_encode($this->session->userdata['custom_search'])); ?>" />
					<?php
					$custom_search = $this->session->userdata['custom_search'];

					foreach ($custom_search as $index => $token)
					{

						$column_name = $get_column_name[trim($index)];
						?>	
						<div class="filter-fileds"><b id="keyword_field_name">Keyword: <?php echo $column_name; ?></b></div>
						<?php
						foreach ($token as $key => $token_val)
						{
							$search_id = $token_val->id;
							?>
							<div class="btn-img" id="<?php echo $search_id; ?>" >
								<span class="search_keys"><?php echo $token_val->value; ?></span>
								<i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($token_val->value); ?>', '<?php echo $search_id; ?>', 'keyword_field_main', '<?php echo $index; ?>');"></i>
							</div>
							<?php
						}
					}
					?>
					<div class="clearfix"></div>

				</div>
				<div class="clearfix"></div>
				<?php
			}
			else
			{
				?>
				<div id="keyword_field_main" style="display: none;">
					<div class="filter-fileds"><b id="keyword_field_name">Keyword</b></div>
					<input type="hidden" id="keyword_field_main_search" name="keyword_field_main_search" value="" />
				</div>
			<?php } ?>
            <div class="clearfix"></div>
            <!-- Custom  Search Display End  -->

            <!-- Date Search Display Start  -->
			<?php
			if (isset($this->session->userdata['date_range']) && $this->session->userdata['date_range'] != '')
			{
				?>
				<div id = "date_field_main">
					<input id="date_type" name="date_type" value="" type="hidden"/>
					<input type="hidden" id="date_field_main_search" name="date_field_main_search" value="<?php echo htmlentities(json_encode($this->session->userdata['date_range'])); ?>" />
					<?php
					$date_search = $this->session->userdata['date_range'];

					foreach ($date_search as $index => $token)
					{

						$column_name = trim($index);
						?>	
						<div class="filter-fileds"><b id="date_field_name">Date Keyword: <?php echo $column_name; ?></b></div>
						<?php
						foreach ($token as $key => $token_val)
						{
							$search_id = $token_val->id;
							?>
							<div class="btn-img" id="<?php echo $search_id; ?>" >
								<span class="search_keys"><?php echo $token_val->value; ?></span>
								<i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($token_val->value); ?>', '<?php echo $search_id; ?>', 'date_field_main', '<?php echo $index; ?>');"></i>
							</div>
							<?php
						}
					}
					?>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>	
				<?php
			}
			else
			{
				?>
				<div id="date_field_main" style="display: none;">
					<input id="date_type" name="date_type" value="" type="hidden"/>
					<div class="filter-fileds"><b id="date_field_name">Keyword</b></div>
					<input type="hidden" id="date_field_main_search" name="date_field_main_search" value="" />

				</div>
				<div class="clearfix"></div>
			<?php }
			?>

            <!-- Date Search Display End  -->
            <div class="clearfix"></div>
            <!-- Organization Search Display Start  -->

			<?php
			if (isset($this->session->userdata['organization']) && $this->session->userdata['organization'] != '')
			{

				$organization = $this->session->userdata['organization'];
				$organization_array = explode('|||', $organization);
				?>
				<div id="organization_main">
					<div class="filter-fileds"><b>Organization</b></div>
					<input type="hidden" id="organization_main_search" name="organization_main_search" value="<?php echo $organization; ?>" />
					<?php
					foreach ($organization_array as $value)
					{
						$search_id = name_slug($value);
						?>
						<div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>', '<?php echo $search_id ?>', 'organization_main')"></i></div>
					<?php } ?>
				</div>
				<?php
			}
			else
			{
				?>

				<div id="organization_main" style="display: none;">
					<div class="filter-fileds"><b>Organization</b></div>
					<input type="hidden" id="organization_main_search" name="organization_main_search"/>
				</div>
			<?php } ?>

            <!-- Organization Search Display End  -->
            <div class="clearfix"></div>
            <!-- State Search Display Start  -->

			<?php
			if (isset($this->session->userdata['states']) && $this->session->userdata['states'] != '')
			{

				$state = $this->session->userdata['states'];
				$state_array = explode('|||', $state);
				?>
				<div id="states_main">
					<div class="filter-fileds"><b>State</b></div>
					<input type="hidden" id="states_main_search" name="states_main_search" value="<?php echo $state; ?>" />
					<?php
					foreach ($state_array as $value)
					{
						$search_id = name_slug($value);
						?>
						<div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>', '<?php echo $search_id ?>', 'states_main')"></i></div>
					<?php } ?>
				</div>
				<?php
			}
			else
			{
				?>

				<div id="states_main" style="display: none;">
					<div class="filter-fileds"><b>State</b></div>
					<input type="hidden" id="states_main_search" name="states_main_search"/>
				</div>
			<?php } ?>

            <!-- State Search Display End  -->
            <div class="clearfix"></div>
            <!-- Nomination Status Search Display Start  -->
			<?php
			if (isset($this->session->userdata['nomination']) && $this->session->userdata['nomination'] != '')
			{

				$nomination = $this->session->userdata['nomination'];
				$nomination_array = explode('|||', $nomination);
				?>
				<div id="nomination_status_main">
					<div class="filter-fileds"><b>Nomination Status</b></div>
					<input type="hidden" id="nomination_status_main_search" name="nomination_status_main_search" value="<?php echo $nomination; ?>" />
					<?php
					foreach ($nomination_array as $value)
					{
						$search_id = name_slug($value);
						?>
						<div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>', '<?php echo $search_id ?>', 'nomination_status_main')"></i></div>
					<?php } ?>
				</div>
				<?php
			}
			else
			{
				?>

				<div id="nomination_status_main" style="display: none;">
					<div class="filter-fileds"><b>Nomination Status</b></div>
					<input type="hidden" id="nomination_status_main_search" name="nomination_status_main_search"/>
				</div>
			<?php } ?>
            <!-- Nomination Status Search Display End  -->
            <div class="clearfix"></div>
            <!-- Media Type Search Display Start  -->
			<?php
			if (isset($this->session->userdata['media_type']) && $this->session->userdata['media_type'] != '')
			{

				$media_type = $this->session->userdata['media_type'];
				$media_type_array = explode('|||', $media_type);
				?>
				<div id="media_type_main">
					<div class="filter-fileds"><b>Media Type</b></div>
					<input type="hidden" id="media_type_main_search" name="media_type_main_search" value="<?php echo $media_type; ?>" />
					<?php
					foreach ($media_type_array as $value)
					{
						$search_id = name_slug($value);
						?>
						<div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>', '<?php echo $search_id ?>', 'media_type_main')"></i></div>
					<?php } ?>
				</div>
				<?php
			}
			else
			{
				?>

				<div id="media_type_main" style="display: none;">
					<div class="filter-fileds"><b>Media Type</b></div>
					<input type="hidden" id="media_type_main_search" name="media_type_main_search"/>
				</div>
			<?php } ?>
            <!-- Media Type Search Display End  -->
            <div class="clearfix"></div>
            <!-- Physical Format Search Display Start  -->
			<?php
			if (isset($this->session->userdata['physical_format']) && $this->session->userdata['physical_format'] != '')
			{

				$physical_format = $this->session->userdata['physical_format'];
				$physical_format_array = explode('|||', $physical_format);
				?>
				<div id="physical_format_main">
					<div class="filter-fileds"><b>Physical Format</b></div>
					<input type="hidden" id="physical_format_main_search" name="physical_format_main_search" value="<?php echo $physical_format; ?>" />
					<?php
					foreach ($physical_format_array as $value)
					{
						$search_id = name_slug($value);
						?>
						<div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>', '<?php echo $search_id ?>', 'physical_format_main')"></i></div>
					<?php } ?>
				</div>
				<?php
			}
			else
			{
				?>

				<div id="physical_format_main" style="display: none;">
					<div class="filter-fileds"><b>Physical Format</b></div>
					<input type="hidden" id="physical_format_main_search" name="physical_format_main_search"/>
				</div>
			<?php } ?>
            <!-- Physical Format Search Display End  -->
            <div class="clearfix"></div>
            <!-- Digital Format Search Display Start  -->
			<?php
			if (isset($this->session->userdata['digital_format']) && $this->session->userdata['digital_format'] != '')
			{

				$digital_format = $this->session->userdata['digital_format'];
				$digital_format_array = explode('|||', $digital_format);
				?>
				<div id="digital_format_main">
					<div class="filter-fileds"><b>Digital Format</b></div>
					<input type="hidden" id="digital_format_main_search" name="digital_format_main_search" value="<?php echo $digital_format; ?>" />
					<?php
					foreach ($digital_format_array as $value)
					{
						$search_id = name_slug($value);
						?>
						<div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>', '<?php echo $search_id ?>', 'digital_format_main')"></i></div>
					<?php } ?>
				</div>
				<?php
			}
			else
			{
				?>

				<div id="digital_format_main" style="display: none;">
					<div class="filter-fileds"><b>Digital Format</b></div>
					<input type="hidden" id="digital_format_main_search" name="digital_format_main_search"/>
				</div>
			<?php } ?>
            <!-- Digital Format Search Display End  -->
            <div class="clearfix"></div>
            <!-- Generation Search Display Start  -->
			<?php
			if (isset($this->session->userdata['generation']) && $this->session->userdata['generation'] != '')
			{

				$generation = $this->session->userdata['generation'];
				$generation_array = explode('|||', $generation);
				?>
				<div id="generation_main">
					<div class="filter-fileds"><b>Generation</b></div>
					<input type="hidden" id="generation_main_search" name="generation_main_search" value="<?php echo $generation; ?>" />
					<?php
					foreach ($generation_array as $value)
					{
						$search_id = name_slug($value);
						?>
						<div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>', '<?php echo $search_id ?>', 'generation_main')"></i></div>
					<?php } ?>
				</div>
				<?php
			}
			else
			{
				?>

				<div id="generation_main" style="display: none;">
					<div class="filter-fileds"><b>Generation</b></div>
					<input type="hidden" id="generation_main_search" name="generation_main_search"/>
				</div>
			<?php } ?>
            <!-- Generation Search Display End  -->
            <div class="clearfix"></div>

        </div>
        <div style="margin: 4px 6px;">

            <input type="button" value="Reset" id="reset_all" name="reset_all" style="display: none;" class="btn" onclick="resetAll();"/>
        </div>
    </div>
    <div class="clearfix"></div>
    <div id="search_bar" class="facet-search"> 
        <input type="hidden" name="current_tab" id="current_tab" value="<?php echo isset($this->session->userdata['current_tab']) ? $this->session->userdata['current_tab'] : '' ?>"  />
        <b>
            <h6 class="filter_title" style="font-weight: bold;">FILTER</h6>
        </b>



        <div class="field-filters" id="limit_field_div">
            <div class="filter-fileds" >
                <div onclick="showHideSearch('sk_div', this);" style="cursor: pointer;"><b>Keyword Search</b>	<span class="caret custom-caret" style="margin-top: 8px;margin-left: 3px;"></span></div>

                <!--																</div>-->
                <div id="sk_div" style="display: none;">
                    <div>
                        <input type="text" name="search" id="search" value="" style="width: 190px;" onkeyup="addTokenOnEnter('search', 'keyword_field_main');"/>
                    </div>

                    <div class="btn-group" id="limit_field_dropdown">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <span id="limit_field_text">Limit Search to Field</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" style="min-width: 98px;">
                            <li class="dropdown"><a href="javascript://;" style="white-space: normal;">Asset<span class="caret custom-caret" style="margin-left:32px;"></span></a>
                                <ul class="sub-menu dropdown-menu" style="min-width: 116px;width: 116px;">
                                    <li href="javascript://;" onclick="add_custom_token('AA GUID', 'guid_identifier');"><a>AA GUID</a></li>
                                    <li href="javascript://;" onclick="add_custom_token('Title', 'asset_title');"><a>Title</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Subject', 'asset_subject');">Subject</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Coverage', 'asset_coverage');">Coverage</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Genre', 'asset_genre');">Genre</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Publisher', 'asset_publisher_name');">Publisher</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Description', 'asset_description');">Description</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Creator Name', 'asset_creator_name');">Creator Name</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Creator Affiliation', 'asset_creator_affiliation');">Creator Affiliation</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Contributor Name', 'asset_contributor_name');">Contributor Name</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Contributor Affiliation', 'asset_contributor_affiliation');">Contributor Affiliation</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Rights Summaries', 'asset_rights');">Rights Summaries</a></li>

                                </ul>
                            </li>
                            <li class="dropdown"><a href="javascript://;"  style="white-space: normal;">Instantiation<span class="caret custom-caret"></span></a>
                                <ul class="sub-menu dropdown-menu" style="min-width: 116px;width: 116px;max-height: 250px;overflow-y: scroll;">
                                    <li><a href="javascript://;" onclick="add_custom_token('ID', 'instantiation_identifier');">ID</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('ID Source', 'instantiation_source');">Identifier Source</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Dimensions', 'instantiation_dimension');">Dimensions</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Unit of Measure', 'unit_of_measure');">Unit of Measure</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Standard', 'standard');">Standard</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Location', 'location');">Location</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('File Size', 'file_size');">File Size</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Duration', 'actual_duration track_duration');">Duration</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Data Rate', 'track_data_rate data_rate');">Data Rate</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Tracks', 'tracks');">Tracks</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Channel Configuration', 'channel_configuration');">Channel Configuration</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Language', 'language track_language');">Language</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Alternative Modes', 'alternative_modes');">Alternative Modes</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Annotation', 'ins_annotation track_annotation');">Annotation</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Annotation Type', 'ins_annotation_type');">Annotation Type</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Track Type', 'track_essence_track_type');">Track Type</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Encoding', 'track_encoding');">Encoding</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Track Standard', 'track_standard');">Track Standard</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Frame Rate', 'track_frame_rate');">Frame Rate</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Playback Speed', 'track_playback_speed');">Playback Speed</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Sampling Rate', 'track_sampling_rate');">Sampling Rate</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Bit Depth', 'track_bit_depth');">Bit Depth</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Frame Size', 'track_width track_height');">Frame Size</a></li>
                                    <li><a href="javascript://;" onclick="add_custom_token('Aspect Ratio', 'track_aspect_ratio');">Aspect Ratio</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div id="limit_btn">
                        <input type="button"  style="margin-top: 10px;" id="add_keyword" name="add_keyword" value="Add Keyword" class="btn btn-primary" onclick="add_token('', 'keyword_field_main');" />
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="field-filters" id="date_range_filter_div">
            <div class="filter-fileds" onclick="showHideSearch('date_range_filter_div_1', this);" style="cursor: pointer;"><b>Date</b><span class="caret custom-caret" style="margin-top: 8px;margin-left: 3px;"></span></div>
            <div id="date_range_filter_div_1" style="display: none;">
                <div id="widget">
                    <div id="date_range_filter">

                        <div class="controls filter-fileds">
                            <div class="input-append">
                                <input type="text" name="date_range" id="date_range" value="" style="width: 180px;" onkeyup="addTokenOnEnter('date_range', 'date_field_main');"/>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="filter-fileds">
					<?php
					if (count($date_types) > 0)
					{
						?>


						<div class="btn-group" id="limit_field_dropdowns">
							<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
								<span id="date_field_text">Date Type</span>

								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu custom-dropdown-menu">
								<?php
								foreach ($date_types as $value)
								{
									?>
									<li><a href="javascript://;" onclick="add_date_token('<?php echo $value->date_type; ?>');"><?php echo $value->date_type; ?></a></li>
								<?php }
								?>
							</ul>
						</div>




						<?php
					}
					?>
                    <div id="add_date_btn">
                        <input type="button" id="add_date_keyword" name="add_date_keyword" value="Add Date" class="btn btn-primary" onclick="add_token($('#date_range').val(), 'date_field_main');"/>
                    </div>

                </div>
            </div>
        </div>
        <div id="append_facet_columns">
            <div style="text-align: center;padding: 10px;">
                <img src="/images/ajax-loader.gif" />
            </div>

        </div>





    </div>
</form>

<script type="text/javascript">
	var is_destroy = false;
	var columnsOrder = new Array();
	var orderString = '';
	var frozen = '<?php echo $this->frozen_column; ?>';
	var hiden_column = new Array();
	var current_table_type = '<?php echo $table_type ?>';
	var index_column = '<?php echo isset($this->session->userdata['jscolumn']) ? $this->session->userdata['jscolumn'] : 0; ?>';
	var order_column = '<?php echo isset($this->session->userdata['column_order']) ? $this->session->userdata['column_order'] : 'asc'; ?>';

	var Filters = new Object();
	var DateFilter = new Object();
	//								$(window).resize(function() {
	//												manageLayout();
	//								});
	oTable = null;
	// if session is already set for keyword search then set the Filters Object
	if ($('#keyword_field_main_search').val() != '')
		Filters = JSON.parse($('#keyword_field_main_search').val());
	// if session is already set for date search then set the DateFilter Object
	if ($('#date_field_main_search').val() != '')
		DateFilter = JSON.parse($('#date_field_main_search').val());
	// initialize the date range pickers
	(function($) {
		manageLayout();
		var hash = window.location.hash.replace('#', '');
		if ($(window.parent.document).find('iframe').size()) {
			var inframe = true;
		}
		$('#date_range').daterangepicker({
			posX: null,
			posY: null,
			arrows: false,
			dateFormat: 'M d, yy',
			rangeSplitter: 'to',
			datepickerOptions: {
				changeMonth: true,
				changeYear: true,
				yearRange: '-500:+15',
				arrows: true
			},
			onOpen: function() {
				if (inframe) {
					$(window.parent.document).find('iframe:eq(1)').width(700).height('35em');
				}
			},
			onClose: function() {
				if (inframe) {
					$(window.parent.document).find('iframe:eq(1)').width('100%').height('5em');
				}
			}
		});

	})(jQuery)

	$(window).load(function() {
		$('#data_container').width($(window).width() - 300);
		$('#ui-datepicker-div').remove();
		if ('<?php echo $current_tab; ?>' == 'simple') {
			updateSimpleDataTable();
		}
		else {
			$('#simple_view').hide();
		}
		manageLayout();
		isAnySearch();
	});

	function get_timestamp() {
		var d = new Date();
		return d.getTime();
	}
	function addTokenOnEnter(name, type) {
		if (this.event.keyCode == 13 && $.trim($('#' + name).val()) != '') {
			add_token('', type);
			$('#' + name).trigger('click');
		}
	}
	function add_token(name, type, isRemoved) {
		if (type == 'keyword_field_main') {
			name = $('#search').val();
			if (isRemoved != 1) {
				if ($.trim($('#search').val()) != '') {
					var count = get_timestamp();
					if (Filters[customColumnName] == undefined) {
						Filters[customColumnName] = new Object();
					} else {
						for (x in Filters[customColumnName]) {
							if (Filters[customColumnName][x].value == $('#search').val()) {
								alert($('#search').val() + " filter is already applied.");
								return false;
							}
						}
					}
					var temp = {};
					temp.id = count;
					temp.value = $('#search').val();
					Filters[customColumnName][count] = temp;
					$('#keyword_field_main_search').val(JSON.stringify(Filters));

				}
				else {
					return false;
				}
			}


		}
		else if (type == 'date_field_main') {
			if (isRemoved != 1) {
				if ($.trim($('#date_range').val()) != '') {
					date_type_text = 'All';
					if ($('#date_type').val() != '')
						date_type_text = $('#date_type').val();
					var count = get_timestamp();
					if (DateFilter[date_type_text] == undefined) {
						DateFilter[date_type_text] = new Object();
					} else {
						for (x in DateFilter[date_type_text]) {
							if (DateFilter[date_type_text][x].value == $('#date_range').val()) {
								alert($('#date_range').val() + " filter is already applied.");
								return false;
							}
						}
					}
					var temp = {};
					temp.id = count;
					temp.value = $('#date_range').val();
					DateFilter[date_type_text][count] = temp;
					$('#date_field_main_search').val(JSON.stringify(DateFilter));
				}
				else {
					return false;
				}
			}
		}
		else {
			if (isRemoved != 1) {
				if ($('#' + type + '_search').val().indexOf(name) < 0) {
					var random_id = rand(0, 1000365);
					slugName = make_slug_name(name);
					var search_id = slugName + random_id;
					$('#' + type).append('<div class="btn-img" id="' + search_id + '" ><span class="search_keys">' + name + '</span><i class="icon-remove-sign" style="float: right;" onclick="remove_token(\'' + name + '\',\'' + search_id + '\',\'' + type + '\')"></i></div>');
					$('#' + type).show();
				}
			}
			var my_search_words = '';
			$('#' + type + '_search').val('');
			$("#" + type + " .search_keys").each(function(index) {
				if (index == 0)
					my_search_words = $(this).text();
				else
					my_search_words += '|||' + $(this).text();

			});
			if (my_search_words != '' && typeof(my_search_words) != undefined)
			{
				$('#' + type + '_search').val(my_search_words);
			}
		}
		facet_search('0');

	}
	var customFieldName = 'All';
	var customColumnName = 'all';

	function add_custom_token(fieldName, columnName) {
		text = $('#search').val();
		customFieldName = fieldName;
		customColumnName = columnName;
		$('#limit_field_text').html(fieldName);
	}
	function add_date_token(type) {
		$('#date_type').val(type);
		$('#date_field_text').html(type);

	}
	function make_slug_name(string) {
		string = string.split('/').join('');
		string = string.split('?').join('');
		string = string.split(' ').join('');
		string = string.split('(').join('');
		string = string.split(')').join('');
		string = string.split(',').join('');
		string = string.split('.').join('');
		string = string.split('"').join('');
		string = string.split('\'').join('');
		string = string.split(':').join('');
		string = string.split(';').join('');
		string = string.split('&').join('');
		string = string.toLowerCase();
		return string;
	}
	function size_of_object(obj) {

		var size = 0, key;
		for (key in obj) {
			if (obj.hasOwnProperty(key))
				size++;
		}
		return size;
	}
	function remove_token(name, id, type, field)
	{
		if (type == 'keyword_field_main') {
			delete (Filters[field][id]);
			if (size_of_object(Filters[field]) == 0)
				delete (Filters[field]);
			$('#keyword_field_main_search').val(JSON.stringify(Filters));
		}
		else if (type == 'date_field_main') {
			delete (DateFilter[field][id]);
			if (size_of_object(DateFilter[field]) == 0)
				delete (DateFilter[field]);
			$('#date_field_main_search').val(JSON.stringify(DateFilter));
		}
		$("#" + id).remove();
		if ($('#' + type + ' div').length <= 1) {
			$('#' + type).hide();
		}
		add_token(unescape(name), type, 1);
	}

	function facet_search(page)
	{
		if (typeof(page) == undefined)
		{
			page = 0;
		}
		$.blockUI({
			css: {
				border: 'none',
				padding: '15px',
				backgroundColor: '#000',
				'-webkit-border-radius': '10px',
				'-moz-border-radius': '10px',
				opacity: .5,
				color: '#fff',
				zIndex: 999999
			}
		});
		$.ajax({
			type: 'POST',
			url: '<?php echo $facet_search_url ?>/' + page,
			data: $('#form_search').serialize(),
			success: function(result, textStatus, request)
			{

				$('.row-fluid').html(result);
				
				if ('<?php echo $current_tab; ?>' == 'simple')
					updateSimpleDataTable();
				else {
					$('#simple_view').hide();


					updateDataTable();
				}
				if (current_table_type == 'assets') {
					load_facet_columns('assets_list', $('.search_keys').length);
				}
				else {
					load_facet_columns('instantiations_list', $('.search_keys').length);
				}
				isAnySearch();
				$.unblockUI();
			}

		});
	}
	function change_view(id)
	{
		$('#current_tab').val(id);
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('records/set_current_tab') ?>/' + id,
			success: function(result)
			{
				window.location.reload();
			}
		});
	}
	sTable = null;
	function updateSimpleDataTable()
	{

		if ($('#assets_table').length > 0)
		{
			column_index = '<?php echo isset($this->session->userdata['jscolumn']) ? $this->session->userdata['jscolumn'] : 0; ?>';
			column_order = '<?php echo isset($this->session->userdata['column_order']) ? $this->session->userdata['column_order'] : 'asc'; ?>';

			sTable =
			$('#assets_table').dataTable(
			{
				"sDom": 'frtiS',
				"aoColumns": [
					{'sWidth': '15%'},
					{'sWidth': '15%'},
					{'sWidth': '18%'},
					{'sWidth': '20%'},
					{'sWidth': '40%'}
				],
				"aaSorting": [[column_index, column_order]],
				'bPaginate': false,
				'bInfo': false,
				'bFilter': false,
				"bSort": true,
				"sScrollY": $(window).height() - 230,
				"sScrollX": "200%",
				"bScrollCollapse": true,
				"bDeferRender": true,
				"bDestroy": is_destroy,
				"bRetrieve": true,
				"bAutoWidth": true,
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": site_url + "records/sort_simple_table"
			});
			$.extend($.fn.dataTableExt.oStdClasses, {
				"sWrapper": "dataTables_wrapper form-inline"
			});

		}
	}
	function showHideSearch(divID, obj) {
		$(obj).children('span').toggleClass('custom-caret');
		$('#' + divID).toggle(function() {
			$('#' + divID).animate({
			}, 500);
		});
	}
	function add_checked_token(id, name) {
		if ($('#' + id).is(':checked')) {
			$('#checked_token').append('<div class="btn-img" id="' + id + '_token" ><span class="search_keys">' + name + '</span><i class="icon-remove-sign" style="float: right;" onclick="remove_checked_token(\'' + id + '\')"></i></div>');
			$('#checked_token').show();
			facet_search('0');
		}
		else {
			remove_checked_token(id);
		}
	}
	function remove_checked_token(id) {
		$('#' + id).attr('checked', false);
		$('#' + id + '_token').remove();

		facet_search('0');
	}
	function isAnySearch() {
		if ($('.search_keys').length > 0) {
			$('#filter_criteria').show();
			$('#reset_all').show();
			count = '0 RECORD';
			if ($('#total_list_count').html() != undefined) {
				if ($('#total_list_count').html() == 1)
					count = $('#total_list_count').html() + ' RECORD';
				else
					count = $('#total_list_count').html() + ' RECORDS';
			}
			$('#filter_record_count').html('(' + count + ')');
			$('#search_bar_val').css('margin-bottom', '10px');
			$('#search_bar_val').css('padding-bottom', '10px');


		}
		else {
			$('#filter_criteria').hide();
			$('#reset_all').hide();
			$('#search_bar_val').css('margin-bottom', '0px');
			$('#search_bar_val').css('padding-bottom', '0px');
		}

	}
	function resetAll() {
		$('#form_search').find('input:hidden, input:text, select').val('');
		$('#form_search').find('input:radio, input:checkbox')
		.removeAttr('checked').removeAttr('selected');
		facet_search('0');
	}
	function manageLayout() {
		$('.container').width($(window).width()-10);
		$('.navbar-inner').width($(window).width() - 50);
		$('#top_setting_nav').width($(window).width() - 50);
		$('body').css('overflow', 'hidden');
		$('.span3').css('min-height', $(window).height() - 90);
		$('.span3').css('max-height', $(window).height() - 90);
		$('#search_bar').css('height', $(window).height() - 95);
		$('#data_container').width($(window).width() - 279);
	}
	function load_facet_columns(index, isSearch) {
		$.ajax({
			type: 'POST',
			url: site_url + 'instantiations/load_facet_columns',
			data: {'index': index, 'issearch': isSearch},
			dataType: 'html',
			success: function(result) {
				$('#append_facet_columns').html(result);
			}
		});
	}
</script>
