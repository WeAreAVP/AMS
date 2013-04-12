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
			if (count($asset_dates) > 0)
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
			if (count($identifiers) > 0)
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
			if (count($titles) > 0)
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
			if (count($subjects) > 0)
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

			if (count($descriptions) > 0)
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
		<tr>
			<?php
			$genres = explode(' | ', trim(str_replace('(**)', '', $asset_detail->genre)));
			$genre_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->genre_source)));
			$genre_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->genre_ref)));

			if (count($genres) > 0)
			{
				?>
				<td class="record-detail-page">
					<label><i class="icon-question-sign"></i><b> Genre:</b></label>
				</td>
				<td>
					<?php
					foreach ($genres as $index => $genre)
					{
						?>
						<div class="edit_form_div">
							<p>Genre:</p>
							<p>
								<input id="asset_genre_<?php echo $index; ?>" name="asset_genre[]" value="<?php echo $genre; ?>" />
							</p>
							<p>
								Genre Source:
							</p>
							<p>
								<input id="asset_genre_source_<?php echo $index; ?>" name="asset_genre_source[]" value="<?php echo (isset($genre_sources[$index])) ? $genre_sources[$index] : ''; ?>" />
							</p>
							<p>
								Genre Ref:
							</p>
							<p>
								<input id="asset_genre_ref_<?php echo $index; ?>" name="asset_genre_ref[]" value="<?php echo (isset($genre_refs[$index])) ? $genre_refs[$index] : ''; ?>" />
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
			$coverages = explode(' | ', trim(str_replace('(**)', '', $asset_detail->coverage)));
			$coverage_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->coverage_type)));


			if (count($coverages) > 0)
			{
				?>
				<td class="record-detail-page">
					<label><i class="icon-question-sign"></i><b> Coverage:</b></label>
				</td>
				<td>
					<?php
					foreach ($coverages as $index => $coverage)
					{
						?>
						<div class="edit_form_div">
							<p>Coverage:</p>
							<p>
								<input id="asset_coverage_<?php echo $index; ?>" name="asset_coverage[]" value="<?php echo $coverage; ?>" />
							</p>
							<p>
								Coverage Type:
							</p>
							<p>
								<select id="asset_coverage_type_<?php echo $index; ?>" name="asset_coverage_type[]">
									<option value="">Select Coverage Type</option>
									<option value="spatial" <?php echo (isset($coverage_types[$index]) && $coverage_types[$index] == 'spatial') ? 'selected="selected"' : ''; ?> >spatial</option>
									<option value="temporal" <?php echo (isset($coverage_types[$index]) && $coverage_types[$index] == 'temporal') ? 'selected="selected"' : ''; ?>>temporal</option>

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
		<tr>
			<?php
			$audience_levels = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level)));
			$audience_level_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level_source)));
			$audience_level_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level_ref)));
			if (count($audience_levels) > 0)
			{
				?>
				<td class="record-detail-page">
					<label><i class="icon-question-sign"></i><b> Audience Level:</b></label>
				</td>
				<td>
					<?php
					foreach ($audience_levels as $index => $audience_level)
					{
						?>
						<div class="edit_form_div">
							<p>
								Audience Level:
							</p>
							<p>
								<select id="asset_audience_level_<?php echo $index; ?>" name="asset_audience_level[]">
									<option value="">Select Audience Level</option>
									<?php
									foreach ($pbcore_asset_audience_level as $row)
									{
										$selected = '';
										if ($audience_level == $row->value)
											$selected = 'selected="selected"'
											?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>
							</p>
							<p> Audience Level Source:</p>
							<p>
								<input id="asset_audience_level_source_<?php echo $index; ?>" name="asset_audience_level_source[]" value="<?php echo (isset($audience_level_sources[$index])) ? $audience_level_sources[$index] : ''; ?>" />
							</p>
							<p> Audience Level Ref:</p>
							<p>
								<input id="asset_audience_level_ref_<?php echo $index; ?>" name="asset_audience_level_ref[]" value="<?php echo (isset($audience_level_refs[$index])) ? $audience_level_refs[$index] : ''; ?>" />
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
			$audience_ratings = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level)));
			$audience_rating_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level_source)));
			$audience_rating_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level_ref)));
			if (count($audience_ratings) > 0)
			{
				?>
				<td class="record-detail-page">
					<label><i class="icon-question-sign"></i><b> Audience Rating:</b></label>
				</td>
				<td>
					<?php
					foreach ($audience_ratings as $index => $audience_rating)
					{
						?>
						<div class="edit_form_div">
							<p>
								Audience Rating:
							</p>
							<p>
								<select id="asset_audience_rating_<?php echo $index; ?>" name="asset_audience_rating[]">
									<option value="">Select Audience Rating</option>
									<?php
									foreach ($pbcore_asset_audience_rating as $row)
									{
										$selected = '';
										if ($audience_rating == $row->value)
											$selected = 'selected="selected"'
											?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>
							</p>
							<p> Audience Rating Source:</p>
							<p>
								<select id="asset_audience_rating_source_<?php echo $index; ?>" name="asset_audience_rating_source[]">
									<option value="">Select Audience Rating Source</option>
									<option value="MPAA" <?php echo (isset($audience_rating_sources[$index]) && $audience_rating_sources[$index] == 'MPAA') ? 'selected="selected"' : ''; ?> >MPAA</option>
									<option value="TV Parental Guidelines" <?php echo (isset($audience_rating_sources[$index]) && $audience_rating_sources[$index] == 'TV Parental Guidelines') ? 'selected="selected"' : ''; ?>>TV Parental Guidelines</option>
								</select>

							</p>
							<p> Audience Rating Ref:</p>
							<p>
								<input id="asset_audience_rating_ref_<?php echo $index; ?>" name="asset_audience_rating_ref[]" value="<?php echo (isset($audience_rating_refs[$index])) ? $audience_rating_refs[$index] : ''; ?>" />
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
			$annotations = explode(' | ', trim(str_replace('(**)', '', $asset_detail->annotation)));
			$annotation_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->annotation_type)));
			$annotation_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->annotation_ref)));
			if (count($annotations) > 0)
			{
				?>
				<td class="record-detail-page">
					<label><i class="icon-question-sign"></i><b> Annotation:</b></label>
				</td>
				<td>
					<?php
					foreach ($annotations as $index => $annotation)
					{
						?>
						<div class="edit_form_div">
							<p>
								Annotation:
							</p>
							<p>
								<input id="asset_annotation_<?php echo $index; ?>" name="asset_annotation[]" value="<?php echo $annotation; ?>" />
							</p>
							<p> Annotation Type:</p>
							<p>
								<input id="asset_annotation_type_<?php echo $index; ?>" name="asset_annotation_type[]" value="<?php echo (isset($annotation_types[$index])) ? $annotation_types[$index] : ''; ?>" />

							</p>
							<p> Annotation Ref:</p>
							<p>
								<input id="asset_annotation_ref_<?php echo $index; ?>" name="asset_annotation_ref[]" value="<?php echo (isset($annotation_refs[$index])) ? $annotation_refs[$index] : ''; ?>" />
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
			$relation_identifiers = explode(' | ', trim(str_replace('(**)', '', $asset_detail->relation_identifier)));
			$relation_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->relation_type)));
			$relation_type_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->relation_type_source)));
			$relation_type_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->relation_type_ref)));
			if (count($relation_identifiers) > 0)
			{
				?>
				<td class="record-detail-page">
					<label><i class="icon-question-sign"></i><b> Relation:</b></label>
				</td>
				<td>
					<?php
					foreach ($relation_identifiers as $index => $relation_identifier)
					{
						?>
						<div class="edit_form_div">
							<p>
								Relation:
							</p>
							<p>
								<input id="asset_relation_identifier_<?php echo $index; ?>" name="asset_relation_identifier[]" value="<?php echo $relation_identifier; ?>" />
							</p>
							<p> Relation Type:</p>
							<p>
								<select id="asset_relation_type_<?php echo $index; ?>" name="asset_relation_type[]">
									<option value="">Select Relation Type</option>
									<?php
									foreach ($pbcore_asset_relation_types as $row)
									{
										$selected = '';
										if (isset($relation_types[$index]) && $relation_types[$index] == $row->value)
											$selected = 'selected="selected"'
											?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>

							</p>
							<p> Relation Source:</p>
							<p>
								<input id="asset_relation_source_<?php echo $index; ?>" name="asset_relation_source[]" value="<?php echo (isset($relation_type_sources[$index])) ? $relation_type_sources[$index] : ''; ?>" />
							</p>
							<p> Relation Ref:</p>
							<p>
								<input id="asset_relation_ref_<?php echo $index; ?>" name="asset_relation_ref[]" value="<?php echo (isset($relation_type_refs[$index])) ? $relation_type_refs[$index] : ''; ?>" />
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