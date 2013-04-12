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
	</table>
</div>