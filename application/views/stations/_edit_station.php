
<a href="#myStationModal" data-toggle="modal" id="showPopUp"></a>
<div class="modal hide" id="myStationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>Edit Station Record(s)</h3>
		<p id="subLabel" style="font-size: 10px;"></p>
	</div>
	<div class="modal-body">

		<input type="hidden" id="station_id" name="station_id"/>
		<div><div style="float: left;width: 130px;text-align: right;margin-right: 10px;">Digitization Start Date:</div><input type="text" id="start_date" name="start_date" value=""/><span id="start_date_message" style="display: none;color: #C09853;margin-left: 10px;">Please select date.</span></div>
		<div><div style="float: left;width: 130px;text-align: right;margin-right: 10px;">Digitization End Date:</div><input type="text" id="end_date" name="end_date" value=""/><span id="end_date_message" style="display: none;color: #C09853;margin-left: 10px;">Please select date.</span></div>
		<?php if ($this->role_id != 5)
		{ ?>
			<div><div style="float: left;width: 130px;text-align: right;margin-right: 10px;">Certified:</div><input type="checkbox" id="station_certified" name="station_certified" value="1"/></div>
			<div><div style="float: left;width: 130px;text-align: right;margin-right: 10px;">Agreed:</div><input type="checkbox" id="station_agreed" name="station_agreed" value="1"/></div>
<?php } ?>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true" id="">Cancel</button>
		<button class="btn btn-primary" onclick="validateFields();">Save</button>
	</div>
</div>
<a href="#confirmModel" data-toggle="modal" id="showConfirmPopUp"></a>
<div class="modal hide" id="confirmModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>Are you sure you want to save?</h3>

	</div>

	<div class="modal-footer" style="text-align: left;">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="$('#showPopUp').trigger('click');">Go Back</button>
		<button class="btn" data-dismiss="modal" onclick="UpdateStations();">Save</button>
	</div>
</div>
<script type="text/javascript">
		var stationName = null;
		var detailPage = false;
		function validateFields() {
			if ($('#start_date').val() == '--' || $('#start_date').val() == '0000-00-00') {
				$('#start_date_message').show();
			}
			else {
				$('#start_date_message').hide();
			}
			if ($('#end_date').val() == '--' || $('#end_date').val() == '0000-00-00') {
				$('#end_date_message').show();

			}
			else {
				$('#end_date_message').hide();
			}
			if ($('#start_date').val() != '--' && $('#start_date').val() != '0000-00-00' && $('#end_date').val() != '--' && $('#end_date').val() != '0000-00-00') {
				$('#showPopUp').trigger('click');
				$('#showConfirmPopUp').trigger('click');
			}

		}
		function editStations() {
			var stations = new Array();
			$('input[name="station[]"]:checked').each(function(index, a) {
				stations[index] = $(this).val();
			});
			if (stations.length > 0) {
				$.ajax({
					type: 'POST',
					url: site_url + 'stations/get_stations',
					data: {id: stations},
					dataType: 'json',
					cache: false,
					success: function(result) {
						if (result.success == true) {
							var station_name = '';
							var compare_start_date = 0;
							var compare_end_date = 0;
							var compare_is_agreed = 0;
							var compare_is_certified = 0;
							var start_date = false;
							var end_date = false;
							var is_agreed = false;
							var is_certified = false;
							for (cnt in result.records) {
								if (cnt == 0) {
									start_date = result.records[cnt].start_date;
									end_date = result.records[cnt].end_date;
									is_agreed = result.records[cnt].is_agreed;
									is_certified = result.records[cnt].is_certified;

								}
								if (cnt >= result.records.length - 1) {
									if (start_date == result.records[cnt].start_date && compare_start_date == 0) {
										compare_start_date = 0;
									}
									else {
										compare_start_date = 1;
									}
									if (end_date == result.records[cnt].end_date && compare_end_date == 0) {
										compare_end_date = 0;
									}
									else {
										compare_end_date = 1;
									}
									if (is_agreed == result.records[cnt].is_agreed && compare_is_agreed == 0) {
										compare_is_agreed = 0;
									}
									else {
										compare_is_agreed = 1;
									}
									if (is_certified == result.records[cnt].is_certified && compare_is_certified == 0) {
										compare_is_certified = 0;
									}
									else {
										compare_is_certified = 1;
									}
								}

								if (cnt == result.records.length - 1)
									station_name += result.records[cnt].station_name;
								else
									station_name += result.records[cnt].station_name + ',';
							}
							if (compare_start_date == 0 && start_date != 0)
								$('#start_date').val(start_date);
							else if (compare_start_date == 0 && start_date == 0)
								$('#start_date').val('');
							else
								$('#start_date').val('--');
							if (compare_end_date == 0 && end_date != 0)
								$('#end_date').val(end_date);
							else if (compare_end_date == 0 && end_date == 0)
								$('#end_date').val('');
							else
								$('#end_date').val('--');
							if (compare_is_certified == 0) {
								if (is_certified == 1)
									$('#station_certified').attr('checked', true);
								else
									$('#station_certified').attr('checked', false);
							}
							else {
								$('#station_certified').attr('checked', false);
							}
							if (compare_is_agreed == 0) {
								if (is_agreed == 1)
									$('#station_agreed').attr('checked', true);
								else
									$('#station_agreed').attr('checked', false);
							}
							else {
								$('#station_certified').attr('checked', false);
							}
							$('#subLabel').html('Record(s) being edited: ' + station_name);
							$('#station_id').val(stations);
							$('#showPopUp').trigger('click');
						}
						else {
							console.log(result);
						}

					}
				});
			}

		}
		function UpdateStations() {
			ids = $('#station_id').val();
			start_date = $('#start_date').val();
			end_date = $('#end_date').val();
			if ($('#station_agreed').attr('checked') == undefined) {
				agreed = 0;
				is_agree = 'No';
			}
			else {
				agreed = 1;
				is_agree = 'Yes';
			}
			if ($('#station_certified').attr('checked') == undefined) {
				certified = 0;
				is_certify = 'No';
			}
			else {
				certified = 1;
				is_certify = 'Yes';
			}
			$.ajax({
				type: 'POST',
				url: site_url + 'stations/update_stations',
				data: {id: ids, start_date: start_date, end_date: end_date, is_agreed: agreed, is_certified: certified},
				dataType: 'json',
				cache: false,
				success: function(result) {
					if (result.success == true) {
						if (detailPage == false) {
							$('#success_message').html('<strong>' + result.total + ' Record(s) Changed.</strong> <a style="color:#C09853;text-decoration: underline;" href="' + site_url + 'stations/undostations">Undo</a>');
							$('#success_message').show();
							ids = ids.split(',');
							for (cnt in ids) {
								if (start_date == '')
									start_date = 'No DSD';

								$('#start_date_' + ids[cnt]).html(start_date);
								$('#certified_' + ids[cnt]).html(is_certify);
								$('#agreed_' + ids[cnt]).html(is_agree);

							}
						}
						else {
							if (start_date == '')
								start_date = 'No DSD';
							if (end_date == '')
								end_date = 'No DED';
							$('#dsd_').html(start_date);
							$('#ded_').html(end_date);
							$('#certified_').html(is_certify);
							$('#agreed_').html(is_agree);
						}
					}

				}
			});
		}
		function editSingleStation(id, start, end, certified, agreed) {
			$('#start_date').val(start);
			$('#end_date').val(end);
			if (certified == 1)
				$('#station_certified').attr('checked', true);
			$('#station_certified').val(certified);
			if (agreed == 1)
				$('#station_agreed').attr('checked', true);
			$('#station_agreed').val(agreed);
			$('#station_id').val(id);
			detailPage = true;
		}

</script>

