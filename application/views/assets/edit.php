<div class="row">
	<div style="margin: 2px 0px 10px 0px;float:left;width: 570px;">

		<?php
		$asset_title_type = explode('|', trim(str_replace('(**)', '', $asset_detail->title_type)));
		$asset_title = explode('|', trim(str_replace('(**)', '', $asset_detail->title)));
		$asset_title_ref = explode('|', trim(str_replace('(**)', '', $asset_detail->title_ref)));
		$combine_title = '';
		foreach ($asset_title as $index => $title)
		{
			if (isset($asset_title_type[$index]) && $asset_title_type[$index] != '')
				$combine_title.= $asset_title_type[$index] . ': ';
			if (isset($asset_title_ref[$index]))
			{
				if ($asset_title_ref[$index] != '')
				{
					$combine_title.="<a target='_blank' href='$asset_title_ref[$index]'>$title</a>: ";
					$combine_title.=' (' . $asset_title_ref[$index] . ')';
				}
				else
					$combine_title.=$title;
			}
			else
				$combine_title.=$title;
			$combine_title.='<div class="clearfix"></div>';
		}
echo '<!-- sony_id_array is:  ';
echo $sony_id_array;
echo '-->';
		?>
		<h2><?php echo $combine_title; ?></h2>
	</div>
	<div class="clearfix"></div>
	<?php $this->load->view('partials/_list'); ?>
	<div class="span9" style="margin-left: 250px;" id="ins_view_detail">
		<form class="form-horizontal" method="POST" action="<?php echo site_url('asset/edit/' . $asset_id); ?>" id="edit_asset_form">
			<table cellPadding="8" class="record-detail-table">
				<?php
				if ( ! $this->is_station_user)
				{
					?>
					<tr>
						<td class="record-detail-page">
							<label><b> Organization: <span class="label_star"> *</span> </b></label>
						</td>
						<td>
							<p>
								<select id="organization" name="organization">
									<?php
									foreach ($organization as $row)
									{
										$selected = '';
										if ($asset_detail->stations_id == $row->id)
											$selected = 'selected="selected"';
										?>
										<option value="<?php echo $row->id; ?>" <?php echo $selected; ?>><?php echo $row->station_name; ?></option>
									<?php }
									?>
								</select>
							</p>

						</td>
					</tr>
				<?php } ?>
				<tr>
					<?php
					$add = ' ADD TYPE';
					$asset_type_separate = explode('|', trim(str_replace('(**)', '', $asset_detail->asset_type)));
					?>
					<td class="record-detail-page">
						<label>
							<a data-placement="left" rel="tooltip" href="#" data-original-title="Indicates the broad editorial format of the assets contents. AssetType describes the PBCore record as a whole and at its highest level. Though a record may contain many instantiations of different formats and generations, for example, assetType may be used to indicate that they all represent a “program” or a “clip.”"><i class="icon-question-sign"></i></a>
							<b> Asset Type:</b></label>
					</td>
					<td>
						<div id="main_type">
							<?php
							if (count($asset_type_separate) > 0 && isset($asset_type_separate[0]) && ! empty($asset_type_separate[0]))
							{
								$add = ' ADD ANOTHER TYPE';
								foreach ($asset_type_separate as $index => $type)
								{
									?>
									<div id="remove_type_<?php echo $index; ?>" class="remove_type">
										<div class="edit_form_div">
											<div><p>Asset Type:</p></div>
											<div><p>
													<select id="asset_type_<?php echo $index; ?>" name="asset_type[]">
														<option value="">Select</option>
														<?php
														$commonly = $less = FALSE;
														foreach ($pbcore_asset_types as $row)
														{
															$selected = '';
															if (trim($type) == $row->value)
																$selected = 'selected="selected"';
															if ($row->display_value == 1 && ! $commonly)
															{
																$commonly = TRUE;
																?>
																<optgroup label="Commonly Used">Commonly Used</optgroup>
																<?php
															}
															else if ($row->display_value == 2 && ! $less)
															{
																$less = TRUE;
																?>
																<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
															<?php } ?>
															<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
														<?php }
														?>
													</select>
												</p>
											</div>
										</div>
										<div class="remove_element" onclick="removeElement('#remove_type_<?php echo $index; ?>', 'type');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>

						</div>
						<div class="add-new-element" onclick="addElement('#main_type', 'type');"><i class="icon-plus-sign icon-white"></i><span id="add_type"><?php echo $add; ?></span></div>
					</td>
				</tr>
				<tr>
					<?php
					$asset_dates = explode('|', trim(str_replace('(**)', '', $asset_detail->asset_date)));
					$asset_date_types = explode('|', trim(str_replace('(**)', '', $asset_detail->date_type)));
					$add = ' ADD DATE';
					?>
					<td class="record-detail-page">
						<label><b> Asset Date:</b></label>
					</td>
					<td>
						<div id="main_date">
							<?php
							if (count($asset_dates) > 0 && isset($asset_dates[0]) && ! empty($asset_dates[0]))
							{
								$add = ' ADD ANOTHER DATE';
								foreach ($asset_dates as $index => $dates)
								{
									?>
									<div id="remove_date_<?php echo $index; ?>" class="remove_date">
										<div class="edit_form_div">
											<div>
												<p>Asset Date:</p>
												<p>
													<input type="text" id="asset_date_<?php echo $index; ?>" name="asset_date[]" value="<?php echo trim($dates); ?>" />
												</p>
											</div>
											<div>
												<p>Asset Date Type:</p>
												<p>
													<select id="asset_date_type_<?php echo $index; ?>" name="asset_date_type[]">
														<option value="">Select</option>
														<?php
														$commonly = $less = FALSE;
														foreach ($pbcore_asset_date_types as $row)
														{
															$selected = '';
															if (isset($asset_date_types[$index]) && trim($asset_date_types[$index]) == $row->value)
																$selected = 'selected="selected"';
															if ($row->display_value == 1 && ! $commonly)
															{
																$commonly = TRUE;
																?>
																<optgroup label="Commonly Used">Commonly Used</optgroup>
																<?php
															}
															else if ($row->display_value == 2 && ! $less)
															{
																$less = TRUE;
																?>
																<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
															<?php } ?>
															<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
														<?php }
														?>
													</select>
												</p>
											</div>
										</div>
										<div class="remove_element" onclick="removeElement('#remove_date_<?php echo $index; ?>', 'date');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>

									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_date', 'date');"><i class="icon-plus-sign icon-white"></i><span id="add_date"><?php echo $add; ?></span></div>

					</td>

				</tr>
				<tr>
					<?php
					$identifier_ids = explode('|', trim(str_replace('(**)', '', $asset_detail->identifier_id)));					
					$kevin_identifiers = explode('|', trim(str_replace('(**)', '', $asset_detail->kevin_test)));
					$identifiers = explode('|', trim(str_replace('(**)', '', $asset_detail->identifier)));
					$identifier_sources = explode('|', trim(str_replace('(**)', '', $asset_detail->identifier_source)));
					$identifier_refs = explode('|', trim(str_replace('(**)', '', $asset_detail->identifier_ref)));
					$add = ' ADD LOCAL ID';
					?>
					<td class="record-detail-page">
						<label>
							<a data-placement="left" rel="tooltip" href="#" data-original-title="Identifier: Used to reference or identify the entire record of metadata descriptions for a media item. In contrast to the Unique Identifier, this identifier is used to identify the CONTENT of the asset. So it links together all copies of a particular episode of NOVA or This American Life by assigning them all the same code.<br/><br/>Identifier Source: Used in combination with the identifier for a media item. Provides the name of the agency or institution who assigned it, or system used."><i class="icon-question-sign"></i></a>
							<b> Local ID:</b></label>
					</td>
					<td>
						<div id="main_local_id">
							<?php
							if (count($identifiers) > 0 && isset($identifiers[0]) && ! empty($identifiers[0]))
							{
								$add = ' ADD ANOTHER LOCAL ID';
								foreach ($kevin_identifiers as $index => $kevin_identifier)
								{
										list($this_identifier_id, $this_identifier , $this_identifier_source , $this_identifier_ref) = explode('^', trim(str_replace('(**)', '', $kevin_identifier))); 
								?>
									<div id="remove_local_<?php echo $index; ?>" class="remove_local_id">
										<div class="edit_form_div">
											<div>
												<p>Local ID: <span class="label_star"> *</span> </p>
												<p>
													<input type="text" id="asset_identifier_<?php echo $index; ?>" name="asset_identifier[]" value="<?php echo (isset($this_identifier)) ? trim($this_identifier) : ''; ?>" />
												</p>
											</div>
											<div>
												<p>ID Source: <span class="label_star"> *</span> </p>
												<p>
													<input type="text" id="asset_identifier_source_<?php echo $index; ?>" name="asset_identifier_source[]" value="<?php echo (isset($this_identifier_source)) ? trim($this_identifier_source) : ''; ?>" />
												</p>
											</div>
											<div>
												<p>ID Ref:</p>
												<p>
													<input type="text" id="asset_identifier_ref_<?php echo $index; ?>" name="asset_identifier_ref[]" value="<?php echo (isset($this_identifier_ref)) ? trim($this_identifier_ref) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>
										</div>
										<?php
										if ($index != 0)
										{
											?>
											<div class="remove_element" onclick="removeElement('#remove_local_<?php echo $index; ?>', 'local_id');"><img src="/images/remove-item.png" /></div>
										<?php } ?>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>

									<?php
								}
							}
							else
							{
								?>
								<div id="remove_local_0" class="remove_local_id">
									<div class="edit_form_div">
										<div>
											<p>Local ID: <span class="label_star"> *</span> </p>
											<p>
												<input type="text" id="asset_identifier_0" name="asset_identifier[]" value="" />
											</p>
										</div>
										<div>
											<p>ID Source: <span class="label_star"> *</span> </p>
											<p>
												<input type="text" id="asset_identifier_source_0" name="asset_identifier_source[]" value="" />
											</p>
										</div>
										<div>
											<p>ID Ref:</p>
											<p>
												<input type="text" id="asset_identifier_ref_0" name="asset_identifier_ref[]" value="" />
												<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
											</p>
										</div>
									</div>
									<div class="clearfix" style="margin-bottom: 10px;"></div>
								</div>
							<?php } ?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_local_id', 'local_id');"><i class="icon-plus-sign icon-white"></i><span id="add_local_id"><?php echo $add; ?></span></div>

					</td>

				</tr>
				<tr>
					<?php
					$titles = explode('|', trim(str_replace('(**)', '', $asset_detail->title)));
					$title_sources = explode('|', trim(str_replace('(**)', '', $asset_detail->title_source)));
					$title_refs = explode('|', trim(str_replace('(**)', '', $asset_detail->title_ref)));
					$title_types = explode('|', trim(str_replace('(**)', '', $asset_detail->title_type)));
					$add = ' ADD TITLE';
					?>
					<td class="record-detail-page">
						<label>
							<a data-placement="left" rel="tooltip" href="#" data-original-title="Title: The descriptor title is a name given to the media item you are cataloging.<br/><br/>Title Type: a companion metadata field associated with the descriptor title. For a title you give to a media item, this allows you to inform end users what type of title it is."><i class="icon-question-sign"></i></a>
							<b> Title:</label>
					</td>
					<td>
						<div id="main_title">

							<?php
							if (count($titles) > 0 && isset($titles[0]) && ! empty($titles[0]))
							{
								$add = ' ADD ANOTHER TITLE';
								foreach ($titles as $index => $title)
								{
									?>
									<div id="remove_title_<?php echo $index; ?>" class="remove_title">
										<div class="edit_form_div">
											<div>
												<p>Title: <span class="label_star"> *</span></p>
												<p>
													<textarea id="asset_title_<?php echo $index; ?>" name="asset_title[]"><?php echo trim($title); ?></textarea>
												</p>
											</div>
											<div>
												<p>
													Title Type:
												</p>
												<p>
													<select id="asset_title_type_<?php echo $index; ?>" name="asset_title_type[]">
														<option value="">Select</option>
														<?php
														$commonly = $less = FALSE;
														foreach ($pbcore_asset_title_types as $row)
														{
															$selected = '';
															if (isset($title_types[$index]) && trim($title_types[$index]) == $row->value)
																$selected = 'selected="selected"';
															if ($row->display_value == 1 && ! $commonly)
															{
																$commonly = TRUE;
																?>
																<optgroup label="Commonly Used">Commonly Used</optgroup>
																<?php
															}
															else if ($row->display_value == 2 && ! $less)
															{
																$less = TRUE;
																?>
																<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
															<?php } ?>
															<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
														<?php }
														?>
													</select>
												</p>
											</div>
											<div>
												<p>Title Source:</p>
												<p>
													<input type="text" id="asset_title_source_<?php echo $index; ?>" name="asset_title_source[]" value="<?php echo (isset($title_sources[$index])) ? trim($title_sources[$index]) : ''; ?>" />
												</p>
											</div>
											<div>
												<p>Title Ref:</p>
												<p>
													<input type="text" id="asset_title_ref_<?php echo $index; ?>" name="asset_title_ref[]" value="<?php echo (isset($title_refs[$index])) ? trim($title_refs[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>
										</div>
										<?php
										if ($index != 0)
										{
											?>
											<div class="remove_element" onclick="removeElement('#remove_title_<?php echo $index; ?>', 'title');"><img src="/images/remove-item.png" /></div>
										<?php } ?>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							else
							{
								?>
								<div id="remove_title_0" class="remove_title">
									<div class="edit_form_div">
										<div>
											<p>Title: <span class="label_star"> *</span></p>
											<p>
												<textarea id="asset_title_0" name="asset_title[]"></textarea>
											</p>
										</div>
										<div>
											<p>
												Title Type:
											</p>
											<p>
												<select id="asset_title_type_0" name="asset_title_type[]">
													<option value="">Select</option>
													<?php
													$commonly = $less = FALSE;
													foreach ($pbcore_asset_title_types as $row)
													{
														if ($row->display_value == 1 && ! $commonly)
														{
															$commonly = TRUE;
															?>
															<optgroup label="Commonly Used">Commonly Used</optgroup>
															<?php
														}
														else if ($row->display_value == 2 && ! $less)
														{
															$less = TRUE;
															?>
															<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
														<?php } ?>
														<option value="<?php echo $row->value; ?>"><?php echo $row->value; ?></option>
													<?php }
													?>
												</select>
											</p>
										</div>
										<div>
											<p>Title Source:</p>
											<p>
												<input type="text" id="asset_title_source_0" name="asset_title_source[]" value="" />
											</p>
										</div>
										<div>
											<p>Title Ref:</p>
											<p>
												<input type="text" id="asset_title_ref_0" name="asset_title_ref[]" value="" />
												<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
											</p>
										</div>
									</div>

									<div class="clearfix" style="margin-bottom: 10px;"></div>
								</div>
							<?php } ?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_title', 'title');"><i class="icon-plus-sign icon-white"></i><span id="add_title"><?php echo $add; ?></span></div>

					</td>

				</tr>
				<tr>
					<?php
					$subjects = explode('|', trim(str_replace('(**)', '', $asset_detail->subject)));
					$subject_sources = explode('|', trim(str_replace('(**)', '', $asset_detail->subject_source)));
					$subject_refs = explode('|', trim(str_replace('(**)', '', $asset_detail->subject_ref)));
					$subject_types = explode('|', trim(str_replace('(**)', '', $asset_detail->subject_type)));
					$add = ' ADD SUBJECT';
					?>
					<td class="record-detail-page">
						<label>
							<a data-placement="left" rel="tooltip" href="#" data-original-title="Subject: Used to assign topical headings or keywords that portray the intellectual content of the media item. Controlled vocabularies, authorities, or formal classification schemes may be employed when assigning descriptive subject terms (rather than using random or ad hoc terminology).<br/><br/>Subject Authority Used: If subjects are assigned to a media item using the descriptor subject and the terms used are derived from a specific authority or classification scheme, use this field to identify whose vocabularies and terms were used."><i class="icon-question-sign"></i></a>
							<b> Subject:</b></label>
					</td>
					<td>
						<div id="main_subject">
							<?php
							if (count($subjects) > 0 && isset($subjects[0]) && ! empty($subjects[0]))
							{
								$add = ' ADD ANOTHER SUBJECT';
								foreach ($subjects as $index => $subject)
								{
									?>
									<div id="remove_subject_<?php echo $index; ?>" class="remove_subject">
										<div class="edit_form_div">
											<div>
												<p>Subject:</p>
												<p>
													<input type="text" id="asset_subject_<?php echo $index; ?>" name="asset_subject[]" value="<?php echo trim($subject); ?>"/>
												</p>
											</div>
											<div>
												<p>
													Subject Type:
												</p>
												<p>
													<select id="asset_subject_type_<?php echo $index; ?>" name="asset_subject_type[]">
														<option value="">Select</option>
														<?php
														foreach ($pbcore_asset_subject_types as $row)
														{
															$selected = '';
															if (isset($subject_types[$index]) && trim($subject_types[$index]) == $row->subject_type)
																$selected = 'selected="selected"';
															?>
															<option value="<?php echo $row->id; ?>" <?php echo $selected; ?>><?php echo $row->subject_type; ?></option>
														<?php }
														?>
													</select>
												</p>
											</div>
											<div>
												<p>Subject Source:</p>
												<p>
													<input type="text" id="asset_subject_source_<?php echo $index; ?>" name="asset_subject_source[]" value="<?php echo (isset($subject_sources[$index])) ? trim($subject_sources[$index]) : ''; ?>" />
												</p>
											</div>
											<div>
												<p>Subject Ref:</p>
												<p>
													<input type="text" id="asset_subject_ref_<?php echo $index; ?>" name="asset_subject_ref[]" value="<?php echo (isset($subject_refs[$index])) ? trim($subject_refs[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>
										</div>
										<div class="remove_element" onclick="removeElement('#remove_subject_<?php echo $index; ?>', 'subject');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_subject', 'subject');"><i class="icon-plus-sign icon-white"></i><span id="add_subject"><?php echo $add; ?></span></div>

					</td>

				</tr>
				<tr>
					<?php
					$descriptions = explode('|', trim(str_replace('(**)', '', $asset_detail->description)));
					$description_types = explode('|', trim(str_replace('(**)', '', $asset_detail->description_type)));
					$add = ' ADD DESCRIPTION';
					?>
					<td class="record-detail-page">
						<label>
							<a data-placement="left" rel="tooltip" href="#" data-original-title="Description: Uses free-form text or a narrative to report general notes, abstracts, or summaries about the intellectual content of a media item. May also consist of outlines, lists, bullet points, rundowns, edit decision lists, indexes, or tables of content.<br/><br/>Description Type: A companion metadata field to the description. The purpose of descriptionType is to identify the nature of the actual description and flag the form of presentation for the information."><i class="icon-question-sign"></i></a>
							<b> Description: </b></label>
					</td>
					<td>
						<div id="main_description">
							<?php
							if (count($descriptions) > 0 && isset($descriptions[0]) && ! empty($descriptions[0]))
							{
								$add = ' ADD ANOTHER DESCRIPTION';
								foreach ($descriptions as $index => $description)
								{
									?>
									<div id="remove_description_<?php echo $index; ?>" class="remove_description">
										<div class="edit_form_div">
											<div>
												<p>Description: <span class="label_star"> *</span></p>
												<p>
													<textarea id="asset_description_<?php echo $index; ?>" name="asset_description[]"><?php echo trim($description); ?></textarea>
												</p>
											</div>
											<div>
												<p>
													Description Type:
												</p>
												<p>
													<select id="asset_description_type_<?php echo $index; ?>" name="asset_description_type[]">
														<option value="">Select</option>
														<?php
														$commonly = $less = FALSE;
														foreach ($pbcore_asset_description_types as $row)
														{
															$selected = '';
															if (isset($description_types[$index]) && trim($description_types[$index]) == $row->value)
																$selected = 'selected="selected"';
															if ($row->display_value == 1 && ! $commonly)
															{
																$commonly = TRUE;
																?>
																<optgroup label="Commonly Used">Commonly Used</optgroup>
																<?php
															}
															else if ($row->display_value == 2 && ! $less)
															{
																$less = TRUE;
																?>
																<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
															<?php } ?>
															<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
														<?php }
														?>
													</select>
												</p>
											</div>

										</div>
										<?php
										if ($index != 0)
										{
											?>
											<div class="remove_element" onclick="removeElement('#remove_description_<?php echo $index; ?>', 'description');"><img src="/images/remove-item.png" /></div>
										<?php } ?>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							else
							{
								?>
								<div id="remove_description_0" class="remove_description">
									<div class="edit_form_div">
										<div>
											<p>Description: <span class="label_star"> *</span></p>
											<p>
												<textarea id="asset_description_0" name="asset_description[]"></textarea>
											</p>
										</div>
										<div>
											<p>
												Description Type:
											</p>
											<p>
												<select id="asset_description_type_0" name="asset_description_type[]">
													<option value="">Select</option>
													<?php
													$commonly = $less = FALSE;
													foreach ($pbcore_asset_description_types as $row)
													{

														if ($row->display_value == 1 && ! $commonly)
														{
															$commonly = TRUE;
															?>
															<optgroup label="Commonly Used">Commonly Used</optgroup>
															<?php
														}
														else if ($row->display_value == 2 && ! $less)
														{
															$less = TRUE;
															?>
															<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
														<?php } ?>
														<option value="<?php echo $row->value; ?>"><?php echo $row->value; ?></option>
													<?php }
													?>
												</select>
											</p>
										</div>

									</div>

									<div class="clearfix" style="margin-bottom: 10px;"></div>
								</div>
							<?php } ?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_description', 'description');"><i class="icon-plus-sign icon-white"></i><span id="add_description"><?php echo $add; ?></span></div>

					</td>

				</tr>
				<tr>
					<?php
					$edit_genres = explode('|', trim(str_replace('(**)', '', $asset_detail->genres_edit)));
					$genres = explode('|', trim(str_replace('(**)', '', $asset_detail->genre)));
					$genre_sources = explode('|', trim(str_replace('(**)', '', $asset_detail->genre_source)));
					$genre_refs = explode('|', trim(str_replace('(**)', '', $asset_detail->genre_ref)));
					$edit_genres = explode('|', trim(str_replace('(**)', '', $asset_detail->genres_edit)));
					$add = ' ADD GENRE';
					?>
					<td class="record-detail-page">
						<label>
							<a data-placement="left" rel="tooltip" href="#" data-original-title="Genre: Describes the manner in which the intellectual content of a media item is presented, viewed or heard by a user. It indicates the structure of the presentation, as well as the topical nature of the content in a generalized form.<br/><br/>Genre Authority Used: If genre keywords are assigned to a media item using the descriptor genre and the terms used are derived from a specific authority or classification scheme, use genreAuthorityUsed to identify whose vocabularies and terms were used. PBcore supplies its own picklist of terms, but others may be employed as long as the authority for a picklist is identified. (If selecting from the drop down in “genre” — you are using the PBCore pbcoreGenre authority)."><i class="icon-question-sign"></i></a>
							<b> Genre:</b></label>
					</td>
					<td>
						<div id="main_genre">
							<?php
							if (count($genres) > 0 && isset($genres[0]) && ! empty($genres[0]))
							{
								$add = ' ADD ANOTHER GENRE';
								// foreach ($genres as $index => $genre)
								foreach ($edit_genres as $index => $edit_genre)
								{
									list($this_genre_id, $this_genre , $this_genre_source , $this_genre_ref) = explode('^', trim(str_replace('(**)', '', $edit_genre)));
									?>
									<div id="remove_genre_<?php echo $index; ?>" class="remove_genre">
										<div class="edit_form_div">
											<div>
												<p>Genre:</p>
												<p><input type="text" id="asset_genre_<?php echo $index; ?>" name="asset_genre[]" value="<?php echo trim($this_genre); ?>" /></p>
											</div>
											<div>
												<p>Genre Source:</p>
												<p><input type="text" id="asset_genre_source_<?php echo $index; ?>" name="asset_genre_source[]" value="<?php echo (isset($this_genre_source)) ? trim($this_genre_source) : ''; ?>" /></p>
											</div>
											<div>
												<p>Genre Ref:</p>
												<p><input type="text" id="asset_genre_ref_<?php echo $index; ?>" name="asset_genre_ref[]" value="<?php echo (isset($this_genre_ref)) ? trim($this_genre_ref) : ''; ?>" /></p>
												<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
											</div>

										</div>
										<div class="remove_element" onclick="removeElement('#remove_genre_<?php echo $index; ?>', 'genre');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_genre', 'genre');"><i class="icon-plus-sign icon-white"></i><span id="add_genre"><?php echo $add; ?></span></div>

					</td>

				</tr>
				<tr>
					<?php
					$coverages = explode('|', trim(str_replace('(**)', '', $asset_detail->coverage)));
					$coverage_types = explode('|', trim(str_replace('(**)', '', $asset_detail->coverage_type)));
					$add = ' ADD COVERAGE';
					?>
					<td class="record-detail-page">
						<label>
							<a data-placement="left" rel="tooltip" href="#" data-original-title="Coverage: Uses keywords to identify a span of space or time that is expressed by the intellectual content of a media item. Coverage in intellectual content may be expressed spatially by geographic location. Actual place names may be used. Numeric coordinates and geo-spatial data are also allowable, if useful or supplied. Coverage in intellectual content may also be expressed temporally by a date, period, era, or time-based event. The PBCore metadata element coverage houses the actual spatial or temporal keywords. The companion descriptor coverageType is used to identify the type of keywords that are being used.<br/><br/>Coverage Type: Used to identify the actual type of keywords that are being used by its companion metadata element coverage. coverageType provides a picklist of types, namely spatial or temporal, because coverage in intellectual content may be expressed spatially by geographic location or it may also be expressed temporally by a date, period, era, or time-based event."><i class="icon-question-sign"></i></a>
							<b> Coverage:</b></label>
					</td>
					<td>
						<div id="main_coverage">
							<?php
							if (count($coverages) > 0 && isset($coverages[0]) && ! empty($coverages[0]))
							{
								$add = ' ADD ANOTHER COVERAGE';
								foreach ($coverages as $index => $coverage)
								{
									?>
									<div id="remove_coverage_<?php echo $index; ?>" class="remove_coverage">
										<div class="edit_form_div">
											<div>
												<p>Coverage:</p>
												<p><input type="text" id="asset_coverage_<?php echo $index; ?>" name="asset_coverage[]" value="<?php echo trim($coverage); ?>" /></p>
											</div>
											<div>
												<p>Coverage Type:</p>
												<p><select id="asset_coverage_type_<?php echo $index; ?>" name="asset_coverage_type[]">
														<option value="">Select</option>
														<option value="spatial" <?php echo (isset($coverage_types[$index]) && trim($coverage_types[$index]) == 'spatial') ? 'selected="selected"' : ''; ?> >spatial</option>
														<option value="temporal" <?php echo (isset($coverage_types[$index]) && trim($coverage_types[$index]) == 'temporal') ? 'selected="selected"' : ''; ?>>temporal</option>
													</select></p>
											</div>
										</div>
										<div class="remove_element" onclick="removeElement('#remove_coverage_<?php echo $index; ?>', 'coverage');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_coverage', 'coverage');"><i class="icon-plus-sign icon-white"></i><span id="add_coverage"><?php echo $add; ?></span></div>

					</td>

				</tr>
				<tr>
					<?php
					$audience_levels = explode('|', trim(str_replace('(**)', '', $asset_detail->audience_level)));
					$audience_level_sources = explode('|', trim(str_replace('(**)', '', $asset_detail->audience_level_source)));
					$audience_level_refs = explode('|', trim(str_replace('(**)', '', $asset_detail->audience_level_ref)));
					$add = ' ADD AUDIENCE LEVEL';
					?>
					<td class="record-detail-page">
						<label><b> Audience Level:</b></label>
					</td>
					<td>
						<div id="main_audience_level">
							<?php
							if (count($audience_levels) > 0 && isset($audience_levels[0]) && ! empty($audience_levels[0]))
							{
								$add = ' ADD ANOTHER AUDIENCE LEVEL';
								foreach ($audience_levels as $index => $audience_level)
								{
									?>
									<div id="remove_audience_level_<?php echo $index; ?>" class="remove_audience_level">
										<div class="edit_form_div">
											<div>
												<p>
													Audience Level:
												</p>
												<p>
													<select id="asset_audience_level_<?php echo $index; ?>" name="asset_audience_level[]">
														<option value="">Select</option>
														<?php
														$commonly = $less = FALSE;
														foreach ($pbcore_asset_audience_level as $row)
														{
															$selected = '';
															if (trim($audience_level) == $row->value)
																$selected = 'selected="selected"';
															if ($row->display_value == 1 && ! $commonly)
															{
																$commonly = TRUE;
																?>
																<optgroup label="Commonly Used">Commonly Used</optgroup>
																<?php
															}
															else if ($row->display_value == 2 && ! $less)
															{
																$less = TRUE;
																?>
																<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
															<?php } ?>
															<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
														<?php }
														?>
													</select>
												</p>
											</div>
											<div>
												<p> Audience Level Source:</p>
												<p>
													<input type="text" id="asset_audience_level_source_<?php echo $index; ?>" name="asset_audience_level_source[]" value="<?php echo (isset($audience_level_sources[$index])) ? trim($audience_level_sources[$index]) : ''; ?>" />
												</p>
											</div>
											<div>
												<p> Audience Level Ref:</p>
												<p>
													<input type="text" id="asset_audience_level_ref_<?php echo $index; ?>" name="asset_audience_level_ref[]" value="<?php echo (isset($audience_level_refs[$index])) ? trim($audience_level_refs[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>


										</div>
										<div class="remove_element" onclick="removeElement('#remove_audience_level_<?php echo $index; ?>', 'audience_level');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_audience_level', 'audience_level');"><i class="icon-plus-sign icon-white"></i><span id="add_audience_level"><?php echo $add; ?></span></div>

					</td>

				</tr>
				<tr>
					<?php
					$audience_ratings = explode('|', trim(str_replace('(**)', '', $asset_detail->audience_rating)));
					$audience_rating_sources = explode('|', trim(str_replace('(**)', '', $asset_detail->audience_rating_source)));
					$audience_rating_refs = explode('|', trim(str_replace('(**)', '', $asset_detail->audience_rating_ref)));
					$add = ' ADD AUDIENCE RATING';
					?>
					<td class="record-detail-page">
						<label><b> Audience Rating:</b></label>
					</td>
					<td>
						<div id="main_audience_rating">
							<?php
							if (count($audience_ratings) > 0 && isset($audience_ratings[0]) && ! empty($audience_ratings[0]))
							{
								$add = ' ADD ANOTHER AUDIENCE RATING';
								foreach ($audience_ratings as $index => $audience_rating)
								{
									?>
									<div id="remove_audience_rating_<?php echo $index; ?>" class="remove_audience_rating">
										<div class="edit_form_div">
											<div>
												<p>
													Audience Rating:
												</p>
												<p>
													<select id="asset_audience_rating_<?php echo $index; ?>" name="asset_audience_rating[]">
														<option value="">Select</option>
														<?php
														$commonly = $less = FALSE;
														foreach ($pbcore_asset_audience_rating as $row)
														{
															$selected = '';
															if (trim($audience_rating) == $row->value)
																$selected = 'selected="selected"';
															if ($row->display_value == 1 && ! $commonly)
															{
																$commonly = TRUE;
																?>
																<optgroup label="Commonly Used">Commonly Used</optgroup>
																<?php
															}
															else if ($row->display_value == 2 && ! $less)
															{
																$less = TRUE;
																?>
																<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
															<?php } ?>
															<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
														<?php }
														?>
													</select>
												</p>
											</div>
											<div>
												<p> Audience Rating Source:</p>
												<p>
													<select id="asset_audience_rating_source_<?php echo $index; ?>" name="asset_audience_rating_source[]">
														<option value="">Select</option>
														<option value="MPAA" <?php echo (isset($audience_rating_sources[$index]) && trim($audience_rating_sources[$index]) == 'MPAA') ? 'selected="selected"' : ''; ?> >MPAA</option>
														<option value="TV Parental Guidelines" <?php echo (isset($audience_rating_sources[$index]) && trim($audience_rating_sources[$index]) == 'TV Parental Guidelines') ? 'selected="selected"' : ''; ?>>TV Parental Guidelines</option>
													</select>

												</p>
											</div>
											<div>
												<p> Audience Rating Ref:</p>
												<p>
													<select id="asset_audience_rating_ref_<?php echo $index; ?>" name="asset_audience_rating_ref[]">
														<option value="">Select</option>
														<option value="http://www.filmratings.com" <?php echo (isset($audience_rating_refs[$index]) && trim($audience_rating_refs[$index]) == 'http://www.filmratings.com') ? 'selected="selected"' : ''; ?> >http://www.filmratings.com</option>
														<option value="http://www.tvguidelines.org/ratings.htm" <?php echo (isset($audience_rating_refs[$index]) && trim($audience_rating_refs[$index]) == 'http://www.tvguidelines.org/ratings.htm') ? 'selected="selected"' : ''; ?>>http://www.tvguidelines.org/ratings.htm</option>
													</select>

												</p>
											</div>


										</div>

										<div class="remove_element" onclick="removeElement('#remove_audience_rating_<?php echo $index; ?>', 'audience_rating');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_audience_rating', 'audience_rating');"><i class="icon-plus-sign icon-white"></i><span id="add_audience_level"><?php echo $add; ?></span></div>
					</td>

				</tr>
				<tr>
					<?php
					$annotations = explode('|', trim(str_replace('(**)', '', $asset_detail->annotation)));
					$annotation_types = explode('|', trim(str_replace('(**)', '', $asset_detail->annotation_type)));
					$annotation_refs = explode('|', trim(str_replace('(**)', '', $asset_detail->annotation_ref)));
					$add = ' ADD ANNOTATION';
					?>
					<td class="record-detail-page">
						<label><b> Annotation:</b></label>
					</td>
					<td>
						<div id="main_annotation">
							<?php
							if (count($annotations) > 0 && isset($annotations[0]) && ! empty($annotations[0]))
							{
								$add = ' ADD ANOTHER ANNOTATION';
								foreach ($annotations as $index => $annotation)
								{
									?>
									<div id="remove_annotation_<?php echo $index; ?>" class="remove_annotation">
										<div class="edit_form_div">
											<div>
												<p>
													Annotation:
												</p>
												<p>
													<input type="text" id="asset_annotation_<?php echo $index; ?>" name="asset_annotation[]" value="<?php echo trim($annotation); ?>" />
												</p>
											</div>
											<div>
												<p> Annotation Type:</p>
												<p>
													<input type="text" id="asset_annotation_type_<?php echo $index; ?>" name="asset_annotation_type[]" value="<?php echo (isset($annotation_types[$index])) ? trim($annotation_types[$index]) : ''; ?>" />

												</p>
											</div>
											<div>
												<p> Annotation Ref:</p>
												<p>
													<input type="text" id="asset_annotation_ref_<?php echo $index; ?>" name="asset_annotation_ref[]" value="<?php echo (isset($annotation_refs[$index])) ? trim($annotation_refs[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>


										</div>
										<div class="remove_element" onclick="removeElement('#remove_annotation_<?php echo $index; ?>', 'annotation');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_annotation', 'annotation');"><i class="icon-plus-sign icon-white"></i><span id="add_annotation"><?php echo $add; ?></span></div>
					</td>

				</tr>
				<tr>
					<?php
					$relation_identifiers = explode('|', trim(str_replace('(**)', '', $asset_detail->relation_identifier)));
					$relation_types = explode('|', trim(str_replace('(**)', '', $asset_detail->relation_type)));
					$relation_type_sources = explode('|', trim(str_replace('(**)', '', $asset_detail->relation_type_source)));
					$relation_type_refs = explode('|', trim(str_replace('(**)', '', $asset_detail->relation_type_ref)));
					$add = ' ADD RELATION';
					?>
					<td class="record-detail-page">
						<label><b> Relation:</b></label>
					</td>
					<td>
						<div id="main_relation">
							<?php
							if (count($relation_identifiers) > 0 && isset($relation_identifiers[0]) && ! empty($relation_identifiers[0]))
							{
								$add = ' ADD ANOTHER RELATION';
								foreach ($relation_identifiers as $index => $relation_identifier)
								{
									?>
									<div id="remove_relation_<?php echo $index; ?>" class="remove_relation">
										<div class="edit_form_div">
											<div>
												<p>
													Relation:
												</p>
												<p>
													<input type="text" id="asset_relation_identifier_<?php echo $index; ?>" name="asset_relation_identifier[]" value="<?php echo trim($relation_identifier); ?>" />
												</p>
											</div>
											<div>
												<p> Relation Type:</p>
												<p>
													<select id="asset_relation_type_<?php echo $index; ?>" name="asset_relation_type[]">
														<option value="">Select</option>
														<?php
														$commonly = $less = FALSE;
														foreach ($pbcore_asset_relation_types as $row)
														{
															$selected = '';
															if (isset($relation_types[$index]) && trim($relation_types[$index]) == $row->value)
																$selected = 'selected="selected"';
															if ($row->display_value == 1 && ! $commonly)
															{
																$commonly = TRUE;
																?>
																<optgroup label="Commonly Used">Commonly Used</optgroup>
																<?php
															}
															else if ($row->display_value == 2 && ! $less)
															{
																$less = TRUE;
																?>
																<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
															<?php } ?>
															<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
														<?php }
														?>
													</select>

												</p>
											</div>
											<div>
												<p> Relation Source:</p>
												<p>
													<input type="text" id="asset_relation_source_<?php echo $index; ?>" name="asset_relation_source[]" value="<?php echo (isset($relation_type_sources[$index])) ? trim($relation_type_sources[$index]) : ''; ?>" />
												</p>
											</div>
											<div>
												<p> Relation Ref:</p>
												<p>
													<input type="text" id="asset_relation_ref_<?php echo $index; ?>" name="asset_relation_ref[]" value="<?php echo (isset($relation_type_refs[$index])) ? trim($relation_type_refs[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>
										</div>
										<div class="remove_element" onclick="removeElement('#remove_relation_<?php echo $index; ?>', 'relation');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_relation', 'relation');"><i class="icon-plus-sign icon-white"></i><span id="add_relation"><?php echo $add; ?></span></div>
					</td>

				</tr>
				<tr>
					<?php
					$creator_names = explode('|', trim(str_replace('(**)', '', $asset_detail->creator_name)));
					$creator_affiliation = explode('|', trim(str_replace('(**)', '', $asset_detail->creator_affiliation)));
					$creator_ref = explode('|', trim(str_replace('(**)', '', $asset_detail->creator_ref)));
					$creator_role = explode('|', trim(str_replace('(**)', '', $asset_detail->creator_role)));
					$creator_role_source = explode('|', trim(str_replace('(**)', '', $asset_detail->creator_role_source)));
					$creator_role_ref = explode('|', trim(str_replace('(**)', '', $asset_detail->creator_role_ref)));
					$add = ' ADD CREATOR';
					?>
					<td class="record-detail-page">
						<label>
							<a data-placement="left" rel="tooltip" href="#" data-original-title="Creator: Identifies a person or organization primarily responsible for creating a media item. The creator may be considered an author and could be one or more people, a business, organization, group, project or service.<br/><br/>Creator Role: Identifies the role played by the person or group identified in the companion descriptor Creator."><i class="icon-question-sign"></i></a>
							<b> Creator:</b></label>
					</td>
					<td>
						<div id="main_creator">
							<?php
							if (count($creator_names) > 0 && isset($creator_names[0]) && ! empty($creator_names[0]))
							{
								$add = ' ADD ANOTHER CREATOR';
								foreach ($creator_names as $index => $creator_name)
								{
									?>
									<div id="remove_creator_<?php echo $index; ?>" class="remove_creator">
										<div class="edit_form_div">
											<div>
												<p>
													Creator:
												</p>
												<p>
													<input type="text" id="asset_creator_name_<?php echo $index; ?>" name="asset_creator_name[]" value="<?php echo trim($creator_name); ?>" />
												</p>
											</div>
											<div>
												<p>
													Creator Affiliation:
												</p>
												<p>
													<input type="text" id="asset_creator_affiliation_<?php echo $index; ?>" name="asset_creator_affiliation[]" value="<?php echo (isset($creator_affiliation[$index])) ? trim($creator_affiliation[$index]) : ''; ?>" />
												</p>
											</div>
											<div>
												<p>
													Creator Ref:
												</p>
												<p>
													<input type="text" id="asset_creator_ref_<?php echo $index; ?>" name="asset_creator_ref[]" value="<?php echo (isset($creator_ref[$index])) ? trim($creator_ref[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>
											<div>
												<p> Creator Role:</p>
												<p>
													<select id="asset_creator_role_<?php echo $index; ?>" name="asset_creator_role[]">
														<option value="">Select</option>
														<?php
														$commonly = $less = FALSE;
														foreach ($pbcore_asset_creator_roles as $row)
														{
															$selected = '';
															if (isset($creator_role[$index]) && trim($creator_role[$index]) == $row->value)
																$selected = 'selected="selected"';
															if ($row->display_value == 1 && ! $commonly)
															{
																$commonly = TRUE;
																?>
																<optgroup label="Commonly Used">Commonly Used</optgroup>
																<?php
															}
															else if ($row->display_value == 2 && ! $less)
															{
																$less = TRUE;
																?>
																<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
															<?php } ?>
															<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
														<?php }
														?>
													</select>

												</p>
											</div>
											<div>
												<p> Creator Role Source:</p>
												<p>
													<input type="text" id="asset_creator_role_source_<?php echo $index; ?>" name="asset_creator_role_source[]" value="<?php echo (isset($creator_role_source[$index])) ? trim($creator_role_source[$index]) : ''; ?>" />
												</p>
											</div>
											<div>
												<p> Creator Role Ref:</p>
												<p>
													<input type="text" id="asset_creator_role_ref_<?php echo $index; ?>" name="asset_creator_role_ref[]" value="<?php echo (isset($creator_role_ref[$index])) ? trim($creator_role_ref[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>
										</div>
										<div class="remove_element" onclick="removeElement('#remove_creator_<?php echo $index; ?>', 'creator');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_creator', 'creator');"><i class="icon-plus-sign icon-white"></i><span id="add_creator"><?php echo $add; ?></span></div>
					</td>

				</tr>
				<tr>
					<?php
					$contributor_names = explode('|', trim(str_replace('(**)', '', $asset_detail->contributor_name)));
					$contributor_affiliation = explode('|', trim(str_replace('(**)', '', $asset_detail->contributor_affiliation)));
					$contributor_ref = explode('|', trim(str_replace('(**)', '', $asset_detail->contributor_ref)));
					$contributor_role = explode('|', trim(str_replace('(**)', '', $asset_detail->contributor_role)));
					$contributor_role_source = explode('|', trim(str_replace('(**)', '', $asset_detail->contributor_role_source)));
					$contributor_role_ref = explode('|', trim(str_replace('(**)', '', $asset_detail->contributor_role_ref)));
					$add = ' ADD CONTRIBUTOR';
					?>
					<td class="record-detail-page">
						<label>
							<a data-placement="left" rel="tooltip" href="#" data-original-title="Contributor: Identifies a person or organization that has made substantial creative contributions to the intellectual content within a media item. This contribution is considered to be secondary to the primary author(s) (person or organization) identified in the descriptor Creator.<br/><br/>Contributor Role: Identifies the role played by the person or group identified in the companion descriptor Contributor."><i class="icon-question-sign"></i></a>
							<b> Contributor:</b></label>
					</td>
					<td>
						<div id="main_contributor">
							<?php
							if (count($contributor_names) > 0 && isset($contributor_names[0]) && ! empty($contributor_names[0]))
							{
								$add = ' ADD ANOTHER CONTRIBUTOR';
								foreach ($contributor_names as $index => $contributor_name)
								{
									?>
									<div id="remove_contributor_<?php echo $index; ?>" class="remove_contributor">

										<div class="edit_form_div">
											<div>
												<p>
													Contributor:
												</p>
												<p>
													<input type="text" id="asset_contributor_name_<?php echo $index; ?>" name="asset_contributor_name[]" value="<?php echo trim($contributor_name); ?>" />
												</p>
											</div>
											<div>
												<p>
													Contributor Affiliation:
												</p>
												<p>
													<input type="text" id="asset_contributor_affiliation_<?php echo $index; ?>" name="asset_contributor_affiliation[]" value="<?php echo (isset($contributor_affiliation[$index])) ? trim($contributor_affiliation[$index]) : ''; ?>" />
												</p>
											</div>
											<div>
												<p>
													Contributor Ref:
												</p>
												<p>
													<input type="text" id="asset_contributor_ref_<?php echo $index; ?>" name="asset_contributor_ref[]" value="<?php echo (isset($contributor_ref[$index])) ? trim($contributor_ref[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>
											<div>
												<p> Contributor Role:</p>
												<p>
													<select id="asset_contributor_role_<?php echo $index; ?>" name="asset_contributor_role[]">
														<option value="">Select</option>
														<?php
														$commonly = $less = FALSE;
														foreach ($pbcore_asset_contributor_roles as $row)
														{
															$selected = '';
															if (isset($contributor_role[$index]) && trim($contributor_role[$index]) == $row->value)
																$selected = 'selected="selected"';
															if ($row->display_value == 1 && ! $commonly)
															{
																$commonly = TRUE;
																?>
																<optgroup label="Commonly Used">Commonly Used</optgroup>
																<?php
															}
															else if ($row->display_value == 2 && ! $less)
															{
																$less = TRUE;
																?>
																<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
															<?php } ?>
															<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
														<?php }
														?>
													</select>

												</p>
											</div>
											<div>
												<p> Contributor Role Source:</p>
												<p>
													<input type="text" id="asset_contributor_role_source_<?php echo $index; ?>" name="asset_contributor_role_source[]" value="<?php echo (isset($contributor_role_source[$index])) ? trim($contributor_role_source[$index]) : ''; ?>" />
												</p>
											</div>
											<div>
												<p> Contributor Role Ref:</p>
												<p>
													<input type="text" id="asset_contributor_role_ref_<?php echo $index; ?>" name="asset_contributor_role_ref[]" value="<?php echo (isset($contributor_role_ref[$index])) ? trim($contributor_role_ref[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>

										</div>
										<div class="remove_element" onclick="removeElement('#remove_contributor_<?php echo $index; ?>', 'contributor');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_contributor', 'contributor');"><i class="icon-plus-sign icon-white"></i><span id="add_contributor"><?php echo $add; ?></span></div>
					</td>

				</tr>
				<tr>
					<?php
					$publishers = explode('|', trim(str_replace('(**)', '', $asset_detail->publisher)));
					$publisher_affiliation = explode('|', trim(str_replace('(**)', '', $asset_detail->publisher_affiliation)));
					$publisher_ref = explode('|', trim(str_replace('(**)', '', $asset_detail->publisher_ref)));
					$publisher_role = explode('|', trim(str_replace('(**)', '', $asset_detail->publisher_role)));
					$publisher_role_source = explode('|', trim(str_replace('(**)', '', $asset_detail->publisher_role_source)));
					$publisher_role_ref = explode('|', trim(str_replace('(**)', '', $asset_detail->publisher_role_ref)));
					$add = ' ADD PUBLISHER';
					?>
					<td class="record-detail-page">
						<label>
							<a data-placement="left" rel="tooltip" href="#" data-original-title="Publisher: Identifies a person or organization primarily responsible for distributing or making a media item available to others. The publisher may be a person, a business, organization, group, project or service.<br/><br/>Publisher Role: Identifies the role played by the specific publisher or publishing entity identified in the companion descriptor Publisher."><i class="icon-question-sign"></i></a>
							<b> Publisher:</b></label>
					</td>
					<td>
						<div id="main_publisher">
							<?php
							if (count($publishers) > 0 && isset($publishers[0]) && ! empty($publishers[0]))
							{
								$add = ' ADD ANOTHER PUBLISHER';
								foreach ($publishers as $index => $publisher)
								{
									?>
									<div id="remove_publisher_<?php echo $index; ?>" class="remove_publisher">
										<div class="edit_form_div">
											<div>
												<p>
													Publisher:
												</p>
												<p>
													<input type="text" id="asset_publisher_<?php echo $index; ?>" name="asset_publisher[]" value="<?php echo trim($publisher); ?>" />
												</p>
											</div>
											<div>
												<p>
													Publisher Affiliation:
												</p>
												<p>
													<input type="text" id="asset_publisher_affiliation_<?php echo $index; ?>" name="asset_publisher_affiliation[]" value="<?php echo (isset($publisher_affiliation[$index])) ? trim($publisher_affiliation[$index]) : ''; ?>" />
												</p>
											</div>
											<div>
												<p>
													Publisher Ref:
												</p>
												<p>
													<input type="text" id="asset_publisher_ref_<?php echo $index; ?>" name="asset_publisher_ref[]" value="<?php echo (isset($publisher_ref[$index])) ? trim($publisher_ref[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>
											<div>
												<p> Publisher Role:</p>
												<p>
													<select id="asset_publisher_role_<?php echo $index; ?>" name="asset_publisher_role[]">
														<option value="">Select</option>
														<?php
														$commonly = $less = FALSE;
														foreach ($pbcore_asset_publisher_roles as $row)
														{
															$selected = '';
															if (isset($publisher_role[$index]) && trim($publisher_role[$index]) == $row->value)
																$selected = 'selected="selected"';
															if ($row->display_value == 1 && ! $commonly)
															{
																$commonly = TRUE;
																?>
																<optgroup label="Commonly Used">Commonly Used</optgroup>
																<?php
															}
															else if ($row->display_value == 2 && ! $less)
															{
																$less = TRUE;
																?>
																<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>
															<?php } ?>
															<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
														<?php }
														?>
													</select>

												</p>
											</div>
											<div>
												<p> Publisher Role Source:</p>
												<p>
													<input type="text" id="asset_publisher_role_source_<?php echo $index; ?>" name="asset_publisher_role_source[]" value="<?php echo (isset($publisher_role_source[$index])) ? trim($publisher_role_source[$index]) : ''; ?>" />
												</p>
											</div>
											<div>
												<p> Publisher Role Ref:</p>
												<p>
													<input type="text" id="asset_publisher_role_ref_<?php echo $index; ?>" name="asset_publisher_role_ref[]" value="<?php echo (isset($publisher_role_ref[$index])) ? trim($publisher_role_ref[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>

										</div>
										<div class="remove_element" onclick="removeElement('#remove_publisher_<?php echo $index; ?>', 'publisher');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_publisher', 'publisher');"><i class="icon-plus-sign icon-white"></i><span id="add_publisher"><?php echo $add; ?></span></div>
					</td>

				</tr>
				<tr>
					<?php
					$rights = explode('|', trim(str_replace('(**)', '', $asset_detail->rights)));
					$rights_link = explode('|', trim(str_replace('(**)', '', $asset_detail->rights_link)));
					$add = ' ADD RIGHT';
					?>
					<td class="record-detail-page">
						<label>
							<a data-placement="left" rel="tooltip" href="#" data-original-title="An all-purpose container field to identify information about copyrights and property rights held in and over a media item, whether they are open access or restricted in some way. If dates, times and availability periods are associated with a right, include them. End user permissions, constraints and obligations may also be identified, as needed."><i class="icon-question-sign"></i></a>
							<b> Right Summary:</b></label>
					</td>
					<td>
						<div id="main_right">
							<?php
							if (count($rights) > 0 && isset($rights[0]) && ! empty($rights[0]))
							{
								$add = ' ADD ANOTHER RIGHT';
								foreach ($rights as $index => $right)
								{
									?>
									<div id="remove_right_<?php echo $index; ?>" class="remove_right">
										<div class="edit_form_div">
											<div>
												<p>
													Right:
												</p>
												<p>
													<input type="text" id="asset_rights_<?php echo $index; ?>" name="asset_rights[]" value="<?php echo trim($right); ?>" />
												</p>
											</div>
											<div>
												<p> Right Link:</p>
												<p>
													<input type="text" id="asset_right_link_<?php echo $index; ?>" name="asset_right_link[]" value="<?php echo (isset($rights_link[$index])) ? trim($rights_link[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>
										</div>
										<div class="remove_element" onclick="removeElement('#remove_right_<?php echo $index; ?>', 'right');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_right', 'right');"><i class="icon-plus-sign icon-white"></i><span id="add_right"><?php echo $add; ?></span></div>
					</td>

				</tr>
				<tr>
					<td colspan="2">
						<a class="btn" href="<?php echo site_url('records/details/' . $asset_id); ?>">Cancel</a>
						<input type="button" onclick="validateForm();" value="Save Changes" class="btn btn-primary"/>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
<script type="text/javascript">
									var pbcoreAssetTypes =<?php echo json_encode($pbcore_asset_types); ?>;
									var pbcoreDateTypes =<?php echo json_encode($pbcore_asset_date_types); ?>;
									var pbcoreTitleTypes =<?php echo json_encode($pbcore_asset_title_types); ?>;
									var pbcoreSubjectTypes =<?php echo json_encode($pbcore_asset_subject_types); ?>;
									var pbcoreDescriptionTypes =<?php echo json_encode($pbcore_asset_description_types); ?>;
									var pbcoreAudienceLevel =<?php echo json_encode($pbcore_asset_audience_level); ?>;
									var pbcoreAudienceRating =<?php echo json_encode($pbcore_asset_audience_rating); ?>;
									var pbcoreRelationTypes =<?php echo json_encode($pbcore_asset_relation_types); ?>;
									var pbcoreCreatorRoles =<?php echo json_encode($pbcore_asset_creator_roles); ?>;
									var pbcoreContributorRoles =<?php echo json_encode($pbcore_asset_contributor_roles); ?>;
									var pbcorePublisherRoles =<?php echo json_encode($pbcore_asset_publisher_roles); ?>;

</script>
<script type="text/javascript" src="/js/edit_asset.js?<?php echo time(); ?>"></script>
<style type="text/css">
	.ui-datepicker,.ui-datepicker-group{
		width: 22em !important;
	}
</style>
