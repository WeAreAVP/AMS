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
					<select id="asset_type" name="asset_type">
						<?php
						foreach ($pbcore_asset_types as $row)
						{
							$selected = '';
							if ($asset_detail->asset_type == $row->value)
								$selected = 'selected="selected"'
								?>
							<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
						<?php }
						?>
					</select>
				</p>

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
	});
</script>