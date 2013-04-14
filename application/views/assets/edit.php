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
			$add = ' ADD DATE';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Asset Date:</b></label>
			</td>
			<td>
				<?php
				if (count($asset_dates) > 0 && isset($asset_dates[0]) && ! empty($asset_dates[0]))
				{
					$add = ' ADD ANOTHER DATE';
					foreach ($asset_dates as $index => $dates)
					{
						?>
						<div id="remove_date_<?php echo $index; ?>">
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
							<div class="remove_element" onclick="removeElement('#remove_date_<?php echo $index; ?>');"><img src="/images/remove-item.png" /></div>
						</div>
				<div class="clearfix" style="margin-bottom: 10px;"></div>
						<?php
					}
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>

			</td>

		</tr>
		<tr>
			<?php
			$identifiers = explode(' | ', trim(str_replace('(**)', '', $asset_detail->identifier)));
			$identifier_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->identifier_source)));
			$identifier_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->identifier_ref)));
			$add = ' ADD LOCAL ID';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Local ID:</b></label>
			</td>
			<td>
				<div id="main_local_id">
					<?php
					if (count($identifiers) > 0 && isset($identifiers[0]) && ! empty($identifiers[0]))
					{
						$add = ' ADD ANOTHER LOCAL ID';
						foreach ($identifiers as $index => $identifier)
						{
							?>
							<div id="remove_local_<?php echo $index; ?>" class="remove_local_id">
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
								<div class="remove_element" onclick="removeElement('#remove_local_<?php echo $index; ?>', 'local_id');"><img src="/images/remove-item.png" /></div>
							</div>
							<div class="clearfix" style="margin-bottom: 10px;"></div>
							<?php
						}
					}
					?>
				</div>
				<div class="add-new-element" onclick="addElement('#main_local_id');"><i class="icon-plus-sign icon-white"></i><span id="add_local_id"><?php echo $add; ?></span></div>

			</td>

		</tr>
		<tr>
			<?php
			$titles = explode(' | ', trim(str_replace('(**)', '', $asset_detail->title)));
			$title_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->title_source)));
			$title_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->title_ref)));
			$title_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->title_type)));
			$add = ' ADD TITLE';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Title:</b></label>
			</td>
			<td>
				<?php
				if (count($titles) > 0 && isset($titles[0]) && ! empty($titles[0]))
				{
					$add = ' ADD ANOTHER TITLE';
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
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>

			</td>

		</tr>
		<tr>
			<?php
			$subjects = explode(' | ', trim(str_replace('(**)', '', $asset_detail->subject)));
			$subject_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->subject_source)));
			$subject_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->subject_ref)));
			$subject_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->subject_type)));
			$add = ' ADD SUBJECT';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Subject:</b></label>
			</td>
			<td>
				<?php
				if (count($subjects) > 0 && isset($subjects[0]) && ! empty($subjects[0]))
				{
					$add = ' ADD ANOTHER SUBJECT';
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
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>

			</td>

		</tr>
		<tr>
			<?php
			$descriptions = explode(' | ', trim(str_replace('(**)', '', $asset_detail->description)));
			$description_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->description_type)));
			$add = ' ADD DESCRIPTION';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Description:</b></label>
			</td>
			<td>
				<?php
				if (count($descriptions) > 0 && isset($descriptions[0]) && ! empty($descriptions[0]))
				{
					$add = ' ADD ANOTHER DESCRIPTION';
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
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>

			</td>

		</tr>
		<tr>
			<?php
			$genres = explode(' | ', trim(str_replace('(**)', '', $asset_detail->genre)));
			$genre_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->genre_source)));
			$genre_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->genre_ref)));
			$add = ' ADD GENRE';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Genre:</b></label>
			</td>
			<td>
				<?php
				if (count($genres) > 0 && isset($genres[0]) && ! empty($genres[0]))
				{
					$add = ' ADD ANOTHER GENRE';
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
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>

			</td>

		</tr>
		<tr>
			<?php
			$coverages = explode(' | ', trim(str_replace('(**)', '', $asset_detail->coverage)));
			$coverage_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->coverage_type)));
			$add = ' ADD COVERAGE';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Coverage:</b></label>
			</td>
			<td>
				<?php
				if (count($coverages) > 0 && isset($coverages[0]) && ! empty($coverages[0]))
				{
					$add = ' ADD ANOTHER COVERAGE';
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
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>

			</td>

		</tr>
		<tr>
			<?php
			$audience_levels = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level)));
			$audience_level_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level_source)));
			$audience_level_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level_ref)));
			$add = ' ADD AUDIENCE LEVEL';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Audience Level:</b></label>
			</td>
			<td>
				<?php
				if (count($audience_levels) > 0 && isset($audience_levels[0]) && ! empty($audience_levels[0]))
				{
					$add = ' ADD ANOTHER AUDIENCE LEVEL';
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
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>

			</td>

		</tr>
		<tr>
			<?php
			$audience_ratings = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level)));
			$audience_rating_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level_source)));
			$audience_rating_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->audience_level_ref)));
			$add = ' ADD AUDIENCE RATING';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Audience Rating:</b></label>
			</td>
			<td>
				<?php
				if (count($audience_ratings) > 0 && isset($audience_ratings[0]) && ! empty($audience_ratings[0]))
				{
					$add = ' ADD ANOTHER AUDIENCE RATING';
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
								<select id="asset_audience_rating_ref_<?php echo $index; ?>" name="asset_audience_rating_ref[]">
									<option value="">Select Audience Rating Source</option>
									<option value="http://www.filmratings.com" <?php echo (isset($audience_rating_refs[$index]) && $audience_rating_refs[$index] == 'http://www.filmratings.com') ? 'selected="selected"' : ''; ?> >http://www.filmratings.com</option>
									<option value="http://www.tvguidelines.org/ratings.htm" <?php echo (isset($audience_rating_refs[$index]) && $audience_rating_refs[$index] == 'http://www.tvguidelines.org/ratings.htm') ? 'selected="selected"' : ''; ?>>http://www.tvguidelines.org/ratings.htm</option>
								</select>

							</p>

						</div>
						<div class="remove_element"><img src="/images/remove-item.png"/></div>
						<div class="clearfix" style="margin-bottom: 10px;"></div>
						<?php
					}
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>
			</td>

		</tr>
		<tr>
			<?php
			$annotations = explode(' | ', trim(str_replace('(**)', '', $asset_detail->annotation)));
			$annotation_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->annotation_type)));
			$annotation_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->annotation_ref)));
			$add = ' ADD ANNOTATION';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Annotation:</b></label>
			</td>
			<td>
				<?php
				if (count($annotations) > 0 && isset($annotations[0]) && ! empty($annotations[0]))
				{
					$add = ' ADD ANOTHER ANNOTATION';
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
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>
			</td>

		</tr>
		<tr>
			<?php
			$relation_identifiers = explode(' | ', trim(str_replace('(**)', '', $asset_detail->relation_identifier)));
			$relation_types = explode(' | ', trim(str_replace('(**)', '', $asset_detail->relation_type)));
			$relation_type_sources = explode(' | ', trim(str_replace('(**)', '', $asset_detail->relation_type_source)));
			$relation_type_refs = explode(' | ', trim(str_replace('(**)', '', $asset_detail->relation_type_ref)));
			$add = ' ADD RELATION';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Relation:</b></label>
			</td>
			<td>
				<?php
				if (count($relation_identifiers) > 0 && isset($relation_identifiers[0]) && ! empty($relation_identifiers[0]))
				{
					$add = ' ADD ANOTHER RELATION';
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
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>
			</td>

		</tr>
		<tr>
			<?php
			$creator_names = explode(' | ', trim(str_replace('(**)', '', $asset_detail->creator_name)));
			$creator_affiliation = explode(' | ', trim(str_replace('(**)', '', $asset_detail->creator_affiliation)));
			$creator_ref = explode(' | ', trim(str_replace('(**)', '', $asset_detail->creator_ref)));
			$creator_role = explode(' | ', trim(str_replace('(**)', '', $asset_detail->creator_role)));
			$creator_role_source = explode(' | ', trim(str_replace('(**)', '', $asset_detail->creator_role_source)));
			$creator_role_ref = explode(' | ', trim(str_replace('(**)', '', $asset_detail->creator_role_ref)));
			$add = ' ADD CREATOR';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Creator:</b></label>
			</td>
			<td>
				<?php
				if (count($creator_names) > 0 && isset($creator_names[0]) && ! empty($creator_names[0]))
				{
					$add = ' ADD ANOTHER CREATOR';
					foreach ($creator_names as $index => $creator_name)
					{
						?>
						<div class="edit_form_div">
							<p>
								Creator:
							</p>
							<p>
								<input id="asset_creator_name_<?php echo $index; ?>" name="asset_creator_name[]" value="<?php echo $creator_name; ?>" />
							</p>
							<p>
								Creator Affiliation:
							</p>
							<p>
								<input id="asset_creator_affiliation_<?php echo $index; ?>" name="asset_creator_affiliation[]" value="<?php echo (isset($creator_affiliation[$index])) ? $creator_affiliation[$index] : ''; ?>" />
							</p>
							<p>
								Creator Ref:
							</p>
							<p>
								<input id="asset_creator_ref_<?php echo $index; ?>" name="asset_creator_ref[]" value="<?php echo (isset($creator_ref[$index])) ? $creator_ref[$index] : ''; ?>" />
							</p>
							<p> Creator Role:</p>
							<p>
								<select id="asset_creator_role_<?php echo $index; ?>" name="asset_creator_role[]">
									<option value="">Select Creator Role</option>
									<?php
									foreach ($pbcore_asset_creator_roles as $row)
									{
										$selected = '';
										if (isset($creator_role[$index]) && $creator_role[$index] == $row->value)
											$selected = 'selected="selected"'
											?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>

							</p>
							<p> Creator Role Source:</p>
							<p>
								<input id="asset_creator_role_source_<?php echo $index; ?>" name="asset_creator_role_source[]" value="<?php echo (isset($creator_role_source[$index])) ? $creator_role_source[$index] : ''; ?>" />
							</p>
							<p> Creator Role Ref:</p>
							<p>
								<input id="asset_creator_role_ref_<?php echo $index; ?>" name="asset_creator_role_ref[]" value="<?php echo (isset($creator_role_ref[$index])) ? $creator_role_ref[$index] : ''; ?>" />
							</p>

						</div>
						<div class="remove_element"><img src="/images/remove-item.png"/></div>
						<div class="clearfix" style="margin-bottom: 10px;"></div>
						<?php
					}
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>
			</td>

		</tr>
		<tr>
			<?php
			$contributor_names = explode(' | ', trim(str_replace('(**)', '', $asset_detail->contributor_name)));
			$contributor_affiliation = explode(' | ', trim(str_replace('(**)', '', $asset_detail->contributor_affiliation)));
			$contributor_ref = explode(' | ', trim(str_replace('(**)', '', $asset_detail->contributor_ref)));
			$contributor_role = explode(' | ', trim(str_replace('(**)', '', $asset_detail->contributor_role)));
			$contributor_role_source = explode(' | ', trim(str_replace('(**)', '', $asset_detail->contributor_role_source)));
			$contributor_role_ref = explode(' | ', trim(str_replace('(**)', '', $asset_detail->contributor_role_ref)));
			$add = ' ADD CONTRIBUTOR';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Contributor:</b></label>
			</td>
			<td>
				<?php
				if (count($contributor_names) > 0 && isset($contributor_names[0]) && ! empty($contributor_names[0]))
				{
					$add = ' ADD ANOTHER CONTRIBUTOR';
					foreach ($contributor_names as $index => $contributor_name)
					{
						?>
						<div class="edit_form_div">
							<p>
								Contributor:
							</p>
							<p>
								<input id="asset_contributor_name_<?php echo $index; ?>" name="asset_contributor_name[]" value="<?php echo $contributor_name; ?>" />
							</p>
							<p>
								Contributor Affiliation:
							</p>
							<p>
								<input id="asset_contributor_affiliation_<?php echo $index; ?>" name="asset_contributor_affiliation[]" value="<?php echo (isset($contributor_affiliation[$index])) ? $contributor_affiliation[$index] : ''; ?>" />
							</p>
							<p>
								Contributor Ref:
							</p>
							<p>
								<input id="asset_contributor_ref_<?php echo $index; ?>" name="asset_contributor_ref[]" value="<?php echo (isset($contributor_ref[$index])) ? $contributor_ref[$index] : ''; ?>" />
							</p>
							<p> Contributor Role:</p>
							<p>
								<select id="asset_contributor_role_<?php echo $index; ?>" name="asset_contributor_role[]">
									<option value="">Select Contributor Role</option>
									<?php
									foreach ($pbcore_asset_contributor_roles as $row)
									{
										$selected = '';
										if (isset($contributor_role[$index]) && $contributor_role[$index] == $row->value)
											$selected = 'selected="selected"'
											?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>

							</p>
							<p> Contributor Role Source:</p>
							<p>
								<input id="asset_contributor_role_source_<?php echo $index; ?>" name="asset_contributor_role_source[]" value="<?php echo (isset($contributor_role_source[$index])) ? $contributor_role_source[$index] : ''; ?>" />
							</p>
							<p> Contributor Role Ref:</p>
							<p>
								<input id="asset_contributor_role_ref_<?php echo $index; ?>" name="asset_contributor_role_ref[]" value="<?php echo (isset($contributor_role_ref[$index])) ? $contributor_role_ref[$index] : ''; ?>" />
							</p>

						</div>
						<div class="remove_element"><img src="/images/remove-item.png"/></div>
						<div class="clearfix" style="margin-bottom: 10px;"></div>
						<?php
					}
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>
			</td>

		</tr>
		<tr>
			<?php
			$publishers = explode(' | ', trim(str_replace('(**)', '', $asset_detail->publisher)));
			$publisher_affiliation = explode(' | ', trim(str_replace('(**)', '', $asset_detail->publisher_affiliation)));
			$publisher_ref = explode(' | ', trim(str_replace('(**)', '', $asset_detail->publisher_ref)));
			$publisher_role = explode(' | ', trim(str_replace('(**)', '', $asset_detail->publisher_role)));
			$publisher_role_source = explode(' | ', trim(str_replace('(**)', '', $asset_detail->publisher_role_source)));
			$publisher_role_ref = explode(' | ', trim(str_replace('(**)', '', $asset_detail->publisher_role_ref)));
			$add = ' ADD PUBLISHER';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Publisher:</b></label>
			</td>
			<td>
				<?php
				if (count($publishers) > 0 && isset($publishers[0]) && ! empty($publishers[0]))
				{
					$add = ' ADD ANOTHER PUBLISHER';
					foreach ($publishers as $index => $publisher)
					{
						?>
						<div class="edit_form_div">
							<p>
								Publisher:
							</p>
							<p>
								<input id="asset_publisher_<?php echo $index; ?>" name="asset_publisher[]" value="<?php echo $publisher; ?>" />
							</p>
							<p>
								Publisher Affiliation:
							</p>
							<p>
								<input id="asset_publisher_affiliation_<?php echo $index; ?>" name="asset_publisher_affiliation[]" value="<?php echo (isset($publisher_affiliation[$index])) ? $publisher_affiliation[$index] : ''; ?>" />
							</p>
							<p>
								Publisher Ref:
							</p>
							<p>
								<input id="asset_publisher_ref_<?php echo $index; ?>" name="asset_publisher_ref[]" value="<?php echo (isset($publisher_ref[$index])) ? $publisher_ref[$index] : ''; ?>" />
							</p>
							<p> Publisher Role:</p>
							<p>
								<select id="asset_publisher_role_<?php echo $index; ?>" name="asset_publisher_role[]">
									<option value="">Select Publisher Role</option>
									<?php
									foreach ($pbcore_asset_publisher_roles as $row)
									{
										$selected = '';
										if (isset($publisher_role[$index]) && $publisher_role[$index] == $row->value)
											$selected = 'selected="selected"'
											?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>

							</p>
							<p> Publisher Role Source:</p>
							<p>
								<input id="asset_publisher_role_source_<?php echo $index; ?>" name="asset_publisher_role_source[]" value="<?php echo (isset($publisher_role_source[$index])) ? $publisher_role_source[$index] : ''; ?>" />
							</p>
							<p> Publisher Role Ref:</p>
							<p>
								<input id="asset_publisher_role_ref_<?php echo $index; ?>" name="asset_publisher_role_ref[]" value="<?php echo (isset($publisher_role_ref[$index])) ? $publisher_role_ref[$index] : ''; ?>" />
							</p>

						</div>
						<div class="remove_element"><img src="/images/remove-item.png"/></div>
						<div class="clearfix" style="margin-bottom: 10px;"></div>
						<?php
					}
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>
			</td>

		</tr>
		<tr>
			<?php
			$rights = explode(' | ', trim(str_replace('(**)', '', $asset_detail->rights)));
			$rights_link = explode(' | ', trim(str_replace('(**)', '', $asset_detail->rights_link)));
			$add = ' ADD RIGHT';
			?>
			<td class="record-detail-page">
				<label><i class="icon-question-sign"></i><b> Right Summary:</b></label>
			</td>
			<td>
				<?php
				if (count($rights) > 0 && isset($rights[0]) && ! empty($rights[0]))
				{
					$add = ' ADD ANOTHER RIGHT';
					foreach ($rights as $index => $right)
					{
						?>
						<div class="edit_form_div">
							<p>
								Right:
							</p>
							<p>
								<input id="asset_rights_<?php echo $index; ?>" name="asset_rights[]" value="<?php echo $right; ?>" />
							</p>
							<p> Right Link:</p>
							<p>
								<input id="asset_right_link_<?php echo $index; ?>" name="asset_right_link[]" value="<?php echo (isset($rights_link[$index])) ? $rights_link[$index] : ''; ?>" />

							</p>
						</div>
						<div class="remove_element"><img src="/images/remove-item.png"/></div>
						<div class="clearfix" style="margin-bottom: 10px;"></div>
						<?php
					}
				}
				?>
				<div class="add-new-element"><i class="icon-plus-sign icon-white"></i><?php echo $add; ?></div>
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

							$('input[name="asset_identifier_source[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=identifiers&column=identifier_source",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_title_source[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=asset_titles&column=title_source",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_subject[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=subjects&column=subject",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_subject_source[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=subjects&column=subject_source",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_genre_source[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=genres&column=genre_source",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_audience_level_source[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=audience_levels&column=audience_level_source",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_annotation_type[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=annotations&column=annotation_type",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_relation_identifier[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=assets_relations&column=relation_identifier",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_relation_source[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=relation_types&column=relation_type_source",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_creator_name[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=creators&column=creator_name",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_creator_affiliation[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=creators&column=creator_affiliation",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_creator_role_source[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=creator_roles&column=creator_role_source",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_contributor_name[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=contributors&column=contributor_name",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_contributor_affiliation[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=contributors&column=contributor_affiliation",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_contributor_role_source[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=contributor_roles&column=contributor_role_source",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_publisher[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=publishers&column=publisher",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_publisher_affiliation[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=publishers&column=publisher_affiliation",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
							$('input[name="asset_publisher_role_source[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=publisher_roles&column=publisher_role_source",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});

						});
						function removeElement(elementID, type) {
							$(elementID).delay(200).fadeOut(1000);
							$(elementID).animate({
								"opacity": "0",
							}, {
								"complete": function() {
									$(elementID).remove();
									if ($('.remove_' + type).length == 0) {
										$('#add_' + type).html(' ADD ' + type.replace(/_/g, " ").toUpperCase());
									}
									else
										$('#add_' + type).html(' ADD ANOTHER ' + type.replace(/_/g, " ").toUpperCase());
								}
							});

						}
						function addElement(elementID) {
							if (elementID == '#main_local_id') {
								var number = 1 + Math.floor(Math.random() * 100);

								html = '<div id="remove_local_' + number + '" class="remove_local_id">' +
								'<div class="edit_form_div"><p>Local ID:</p>' +
								'<p><input id="asset_identifier_' + number + '" name="asset_identifier[]" value="" /></p>' +
								'<p>ID Source:</p>' +
								'<p><input id="asset_identifier_source_' + number + '" name="asset_identifier_source[]" value="" /></p>' +
								'<p>ID Ref:</p>' +
								'<p><input id="asset_identifier_ref_' + number + '" name="asset_identifier_ref[]" value="" /></p>' +
								'</div><div class="remove_element" onclick="removeElement(\'#remove_local_' + number + '\', \'local_id\');"><img src="/images/remove-item.png" /></div></div>' +
								'<div class="clearfix" style="margin-bottom: 10px;"></div>'


								$(elementID).append(html);
														$('input[name="asset_identifier_source[]"]').autocomplete({
								source: site_url + "autocomplete/values?table=identifiers&column=identifier_source",
								minLength: 1,
								delay: 100,
								enable: true,
								cacheLength: 1
							});
	

							}
						}
</script>