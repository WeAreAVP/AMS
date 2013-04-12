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
			if (count($asset_dates) > 1)
			{
				?>
				<td class="record-detail-page">
					<label><i class="icon-question-sign"></i><b> Asset Date:</b></label>
				</td>
				<td>
					<?php
					foreach ($asset_dates as $index => $dates)
					{
						?>
						<div class="edit_form_div">
							<p>Asset Date:</p>
							<p>
								<input id="asset_date_<?php echo $index; ?>" name="asset_date[]" value="<?php echo $dates; ?>" />
							</p>

							<p>Asset Date Type:</p>
							<p>
								<select id="asset_date_type_<?php echo $index; ?>" name="asset_date_type[]">
									<option value="">Select Date Type</option>
									<?php
									foreach ($pbcore_asset_date_types as $row)
									{
										$selected = '';
										if (isset($asset_date_types[$index]) && $asset_date_types[$index] == $row->value)
											$selected = 'selected="selected"'
											?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>
							</p>

						</div>
						<div class="remove_element"><img src="/images/remove-item.png"/></div>
						<?php
					}
					?>


				</td>
			<?php } ?>
		</tr>
		<tr>
			<?php
			$identifiers = explode(' | ', trim(str_replace('(**)', '', $asset_detail->identifier)));
			$identifier_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->identifier_source)));
			$identifier_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->identifier_ref)));
			if (count($identifiers) > 1)
			{
				?>
				<td class="record-detail-page">
					<label><i class="icon-question-sign"></i><b> Local ID:</b></label>
				</td>
				<td>
					<?php
					foreach ($identifiers as $index => $identifier)
					{
						?>
						<div class="edit_form_div">
							<p>Local ID:</p>
							<p>
								<input id="asset_identifier_<?php echo $index; ?>" name="asset_identifier[]" value="<?php echo $identifier; ?>" />
							</p>

							<p>ID Source:</p>
							<p>
								<input id="asset_identifier_source_<?php echo $index; ?>" name="asset_identifier_source[]" value="<?php echo (isset($identifier_sources[$index])) ? $identifier_sources[$index] : ''; ?>" />
							</p>
							<p>ID Ref:</p>
							<p>
								<input id="asset_identifier_ref_<?php echo $index; ?>" name="asset_identifier_ref[]" value="<?php echo (isset($identifier_refs[$index])) ? $identifier_refs[$index] : ''; ?>" />
							</p>

						</div>
						<div class="remove_element"><img src="/images/remove-item.png"/></div>
						<div class="clearfix" style="margin-bottom: 10px;"></div>
						<?php
					}
					?>


				</td>
			<?php } ?>
		</tr>
		<tr>
			<?php
			$titles = explode(' | ', trim(str_replace('(**)', '', $asset_detail->title)));
			$title_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->title_source)));
			$title_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->title_ref)));
			$title_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->title_type)));
			if (count($titles) > 1)
			{
				?>
				<td class="record-detail-page">
					<label><i class="icon-question-sign"></i><b> Title:</b></label>
				</td>
				<td>
					<?php
					foreach ($titles as $index => $title)
					{
						?>
						<div class="edit_form_div">
							<p>Title:</p>
							<p>
								<textarea id="asset_title_<?php echo $index; ?>" name="asset_title[]"><?php echo $title; ?></textarea>
							</p>
							<p>
								Title Type:
							</p>
							<p>
								<select id="asset_title_type_<?php echo $index; ?>" name="asset_title_type[]">
									<option value="">Select Title Type</option>
									<?php
									foreach ($pbcore_asset_title_types as $row)
									{
										$selected = '';
										if (isset($title_types[$index]) && $title_types[$index] == $row->value)
											$selected = 'selected="selected"'
											?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>
							</p>
							<p>Title Source:</p>
							<p>
								<input id="asset_title_source_<?php echo $index; ?>" name="asset_title_source[]" value="<?php echo (isset($title_sources[$index])) ? $title_sources[$index] : ''; ?>" />
							</p>
							<p>Title Ref:</p>
							<p>
								<input id="asset_title_ref_<?php echo $index; ?>" name="asset_title_ref[]" value="<?php echo (isset($title_refs[$index])) ? $title_refs[$index] : ''; ?>" />
							</p>

						</div>
						<div class="remove_element"><img src="/images/remove-item.png"/></div>
						<div class="clearfix" style="margin-bottom: 10px;"></div>
						<?php
					}
					?>


				</td>
			<?php } ?>
		</tr>
		<tr>
			<?php
			$subjects = explode(' | ', trim(str_replace('(**)', '', $asset_detail->subject)));
			$subject_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->subject_source)));
			$subject_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->subject_ref)));
			$subject_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->subject_type)));
			if (count($subjects) > 1)
			{
				?>
				<td class="record-detail-page">
					<label><i class="icon-question-sign"></i><b> Subject:</b></label>
				</td>
				<td>
					<?php
					foreach ($subjects as $index => $subject)
					{
						?>
						<div class="edit_form_div">
							<p>Subject:</p>
							<p>
								<input id="asset_subject_<?php echo $index; ?>" name="asset_subject[]" value="<?php echo $subject; ?>"/>
							</p>
							<p>
								Subject Type:
							</p>
							<p>
								<select id="asset_subject_type_<?php echo $index; ?>" name="asset_subject_type[]">
									<option value="">Select Subject Type</option>
									<?php
									foreach ($pbcore_asset_subject_types as $row)
									{
										$selected = '';
										if (isset($subject_types[$index]) && $subject_types[$index] == $row->value)
											$selected = 'selected="selected"'
											?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>
							</p>
							<p>Subject Source:</p>
							<p>
								<input id="asset_subject_source_<?php echo $index; ?>" name="asset_subject_source[]" value="<?php echo (isset($subject_sources[$index])) ? $subject_sources[$index] : ''; ?>" />
							</p>
							<p>Subject Ref:</p>
							<p>
								<input id="asset_subject_ref_<?php echo $index; ?>" name="asset_subject_ref[]" value="<?php echo (isset($subject_refs[$index])) ? $subject_refs[$index] : ''; ?>" />
							</p>

						</div>
						<div class="remove_element"><img src="/images/remove-item.png"/></div>
						<div class="clearfix" style="margin-bottom: 10px;"></div>
						<?php
					}
					?>


				</td>
			<?php } ?>
		</tr>
		<tr>
			<?php
			$descriptions = explode(' | ', trim(str_replace('(**)', '', $asset_detail->description)));
			$description_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->description_type)));
			echo count($descriptions);exit;
			if (count($descriptions) > 1)
			{
				?>
				<td class="record-detail-page">
					<label><i class="icon-question-sign"></i><b> Description:</b></label>
				</td>
				<td>
					<?php
					foreach ($descriptions as $index => $description)
					{
						?>
						<div class="edit_form_div">
							<p>Description:</p>
							<p>
								<textarea id="asset_description_<?php echo $index; ?>" name="asset_description[]"><?php echo $description; ?></textarea>
							</p>
							<p>
								Subject Type:
							</p>
							<p>
								<select id="asset_description_type_<?php echo $index; ?>" name="asset_description_type[]">
									<option value="">Select Description Type</option>
									<?php
									foreach ($pbcore_asset_description_types as $row)
									{
										$selected = '';
										if (isset($description_types[$index]) && $description_types[$index] == $row->value)
											$selected = 'selected="selected"'
											?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>
							</p>


						</div>
						<div class="remove_element"><img src="/images/remove-item.png"/></div>
						<div class="clearfix" style="margin-bottom: 10px;"></div>
						<?php
					}
					?>


				</td>
			<?php } ?>
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