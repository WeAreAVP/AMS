<div>
	<table cellPadding="8" class="record-detail-table">
		<tr>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Organization:</b></label>
			</td>
			<td>
				<p>
					<select id="organization" name="organization">
						<?php
						foreach ($organization as $row)
						{
							$selected = '';
							if ($asset_detail->stations_id == $row->id)
								$selected = 'selected="selected"'
								?>
							<option value="<?php echo $row->id; ?>" <?php echo $selected; ?>><?php echo $row->station_name; ?></option>
						<?php }
						?>
					</select>
				</p>

			</td>
		</tr>
		<tr>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Asset Type:</b></label>
			</td>
			<td>
				<p>
					<select id="asset_type" name="asset_type"  multiple="multiple">
						<?php
						$asset_type_separate = explode(' | ', trim(str_replace('(**)', '', $asset_detail->asset_type)));
						foreach ($pbcore_asset_types as $row)
						{
							$selected = '';
							if (in_array($row->value, $asset_type_separate))
								$selected = 'selected="selected"'
								?>
							<option value="<?php echo $row->value; ?>" <?php echo $selected; ?> ><?php echo $row->value; ?></option>
						<?php }
						?>
					</select>
				</p>

			</td>
		</tr>
		<tr>
			<?php
			$asset_dates = explode(' | ', trim(str_replace('(**)', '', $asset_detail->asset_date)));
			$asset_date_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->date_type)));
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Asset Date:</b></label>
			</td>
			<td>
				<?php
				if (count($asset_dates) > 0)
				{
					foreach ($asset_dates as $index => $dates)
					{
						?>
						<p>
							<input id="asset_date_<?php echo $index; ?>" name="asset_date[]" value="<?php echo $dates; ?>" />
						</p>
						<?php
						if (isset($asset_date_types[$index]) && $dates > 0)
						{
							?>
							<p>
								<select id="asset_date_type_<?php echo $index; ?>" name="asset_date_type[]">
									<option value="">Select Date Type</option>
									<?php
									foreach ($pbcore_asset_date_types as $row)
									{
										$selected = '';
										if ($asset_date_types[$index] == $row->value)
											$selected = 'selected="selected"'
											?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>
							</p>
							<?php
						}
					}
				}
				?>


			</td>
		</tr>
	</table>
</div>
<script type="text/javascript">
	$(function() {
		$("#asset_type").multiselect({
			noneSelectedText: 'Select Asset Type',
			selectedList: 3
		});
		
		$('input[name="asset_date[]"]').datepicker({"dateFormat": 'yy-mm-dd'});
	});
</script>