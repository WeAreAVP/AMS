<div class="row">
	<div style="margin: 2px 0px 10px 0px;float:left;width: 570px;">

		<?php
		$disable = FALSE;
		if (isset($inst_format->format_type) && $inst_format->format_type == 'physical')
		{
			$disable = TRUE;
			if (( empty($instantiation_detail->digitized) && $instantiation_detail->digitized == NULL) || $instantiation_detail->digitized == 1)
			{
				$disable = TRUE;
			}
		}
		$asset_title_type = explode('|', trim(str_replace('(**)', '', $asset_details->title_type)));
		$asset_title = explode('|', trim(str_replace('(**)', '', $asset_details->title)));
		$asset_title_ref = explode('|', trim(str_replace('(**)', '', $asset_details->title_ref)));
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
		?>
		<h2><?php echo $combine_title; ?></h2>
	</div>
	<div class="clearfix"></div>
	<?php $this->load->view('partials/_list'); ?>
	<div class="span9" style="margin-left: 250px;" id="ins_view_detail">
		<form class="form-horizontal" method="POST" action="<?php echo site_url('instantiations/edit/' . $inst_id); ?>" id="edit_instantiation_form">
			<table cellPadding="8" class="record-detail-table">
				<tr>
					<?php
					$add = ' ADD INSTANTIATION ID';
					?>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Instantiation ID:</b></label>
					</td>
					<td>
						<div id="main_instantiation_id">
							<?php
							if (count($inst_identifier) > 0)
							{
								$add = ' ADD ANOTHER INSTANTIATION ID';
								foreach ($inst_identifier as $index => $identifier)
								{
									?>
									<div id="remove_instantiation_id_<?php echo $index; ?>" class="remove_instantiation_id">
										<div class="edit_form_div ins_edit_div">
											<input type="hidden" id="instantiation_id_identifier_<?php echo $index; ?>" name="instantiation_id_identifier_id[]" value="<?php echo trim($identifier->id); ?>" />
											<?php
											if ($disable)
											{
												?>
												<div>
													<p>Instantiation ID: <span class="label_star"> *</span> </p>
													<p>
														<input type="text" id="instantiation_id_identifier_<?php echo $index; ?>" name="instantiation_id_identifier[]" value="<?php echo trim($identifier->instantiation_identifier); ?>" />
														<span  class="help-block" style="display:none;">Instantiation ID is required.</span>
													</p>
												</div>
											<?php } ?>
											<div>
												<p>Instantiation ID Source: <span class="label_star"> *</span> </p>
												<p>
													<input type="text" id="instantiation_id_source_<?php echo $index; ?>" name="instantiation_id_source[]" value="<?php echo trim($identifier->instantiation_source); ?>" />
													<span  class="help-block" style="display:none;">Instantiation ID Source is required.</span>
												</p>
											</div>

										</div>
										<?php
										if ($index != 0)
										{
											?>
											<div class="remove_element" onclick="removeElement('#remove_instantiation_id_<?php echo $index; ?>', 'instantiation_id');"><img src="/images/remove-item.png" /></div>
										<?php } ?>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>

									<?php
								}
							}
							else
							{
								?>
								<div id="remove_instantiation_id_0" class="remove_instantiation_id">
									<div class="edit_form_div ins_edit_div">
										<input type="hidden" id="instantiation_id_identifier_0" name="instantiation_id_identifier_id[]" value="" />
										<?php
										if ($disable)
										{
											?>
											<div>
												<p>INSTANTIATION ID: <span class="label_star"> *</span> </p>
												<p>
													<input type="text" id="instantiation_id_identifier_0" name="instantiation_id_identifier[]" value="" />
													<span  class="help-block" style="display:none;">Instantiation ID is required.</span>
												</p>
											</div>
										<?php } ?>
										<div>
											<p>INSTANTIATION ID SOURCE: <span class="label_star"> *</span> </p>
											<p>
												<input type="text" id="instantiation_id_source_0" name="instantiation_id_source[]" value="" />
												<span  class="help-block" style="display:none;">Instantiation ID Source is required.</span>
											</p>
										</div>

									</div>

									<div class="clearfix" style="margin-bottom: 10px;"></div>
								</div>
							<?php }
							?>
						</div>
						<?php
						if ($disable)
						{
							?>
							<div class="add-new-element" onclick="addElement('#main_instantiation_id', 'instantiation_id');"><i class="icon-plus-sign icon-white"></i><span id="add_instantiation_id"><?php echo $add; ?></span></div>
						<?php } ?>
					</td>

				</tr>
				<?php
				if ($disable)
				{
					?>
					<tr>
						<?php
						$add = ' ADD INSTANTIATION DATE';
						?>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Instantiation Date:</b></label>
						</td>
						<td>
							<div id="main_instantiation_date">
								<?php
								if (count($date) > 0)
								{
									$add = ' ADD ANOTHER INSTANTIATION DATE';
									foreach ($date as $index => $row)
									{
										?>
										<div id="remove_instantiation_date_<?php echo $index; ?>" class="remove_instantiation_date">
											<div class="edit_form_div ins_edit_div">
												<div>
													<p>Instantiation ID:</p>
													<p><input readonly="readonly" type="text" id="inst_date_<?php echo $index; ?>" name="inst_date[]" value="<?php echo $row->instantiation_date; ?>" /></p>
												</div>

												<div>
													<p>Instantiation ID Source:</p>
													<p>
														<select id="inst_date_type_<?php echo $index; ?>" name="inst_date_type[]">
															<option value="">Select</option>
															<?php
															$commonly = $less = FALSE;
															foreach ($pbcore_asset_date_types as $row)
															{
																$selected = '';
																if ($row->date_type == $row->value)
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

											<div class="remove_element" onclick="removeElement('#remove_instantiation_date_<?php echo $index; ?>', 'instantiation_date');"><img src="/images/remove-item.png" /></div>

											<div class="clearfix" style="margin-bottom: 10px;"></div>
										</div>
										<?php
									}
								}
								?>
							</div>

							<div class="add-new-element" onclick="addElement('#main_instantiation_date', 'instantiation_date');"><i class="icon-plus-sign icon-white"></i><span id="add_instantiation_date"><?php echo $add; ?></span></div>
						</td>
					</tr>



					<tr>
						<?php
						$add = ' ADD DIMENSION';
						?>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Dimension:</b></label>
						</td>
						<td>
							<div id="main_dimension">
								<?php
								if (count($inst_demension) > 0)
								{
									$add = ' ADD ANOTHER DIMENSION';
									foreach ($inst_demension as $index => $demension)
									{
										?>
										<div id="remove_dimension_<?php echo $index; ?>" class="remove_dimension">
											<div class="edit_form_div ins_edit_div">
												<div>
													<p>Dimension:</p>
													<p>
														<input type="text" id="dimension_<?php echo $index; ?>" name="asset_dimension[]" value="<?php echo $demension->instantiation_dimension; ?>" />

													</p>
												</div>
												<div>
													<p>Unit of measure:</p>
													<p>
														<input type="text" id="dimension_unit_<?php echo $index; ?>" name="dimension_unit[]" value="<?php echo $demension->unit_of_measure; ?>" />
													</p>
												</div>
											</div>
											<div class="remove_element" onclick="removeElement('#remove_dimension_<?php echo $index; ?>', 'dimension');"><img src="/images/remove-item.png" /></div>
											<div class="clearfix" style="margin-bottom: 10px;"></div>
										</div>

										<?php
									}
								}
								?>
							</div>
							<div class="add-new-element" onclick="addElement('#main_dimension', 'dimension');"><i class="icon-plus-sign icon-white"></i><span id="add_dimension"><?php echo $add; ?></span></div>

						</td>

					</tr>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Physical Format: <span class="label_star"> *</span> </b></label>
						</td>
						<td>
							<p>
								<select id="physical_format" name="physical_format">
									<option value="">Select</option>
									<?php
									$commonly = $less = FALSE;
									foreach ($pbcore_physical_formats as $row)
									{
										$selected = '';
										if (isset($inst_format->format_name) && $inst_format->format_name == $row->value)
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
								<span id="physical_format_error" class="help-block" style="color: #c65f5a;display: none;">Physical Format is required.</span>
							</p>

						</td>
					</tr>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Standard:</b></label>
						</td>
						<td>
							<p>
								<select id="standard" name="standard">
									<option value="">Select</option>
									<?php
									foreach ($pbcore_standards as $row)
									{
										$selected = '';
										if ($instantiation_detail->standard == $row->value)
											$selected = 'selected="selected"';
										?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>
							</p>

						</td>
					</tr>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Location: <span class="label_star"> *</span> </b></label>
						</td>
						<td>
							<p>
								<input type="text" id="location" name="location" value="<?php echo $instantiation_detail->location; ?>" />
								<span id="location_error" class="help-block" style="color: #c65f5a;display: none;">Location is required.</span>
							</p>

						</td>
					</tr>


					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Time start:</b></label>
						</td>
						<td>
							<p>
								<input type="text" id="time_start" name="time_start" value="<?php echo $instantiation_detail->time_start; ?>" />
								<span id="time_start_error" class="help-block" style="color: #c65f5a;display: none;">Time Start should be hh:mm:ss.</span>
							</p>

						</td>
					</tr>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Projected Duration:</b></label>
						</td>
						<td>
							<p>
								<input type="text" id="projected_duration" name="projected_duration" value="<?php echo $instantiation_detail->projected_duration; ?>" />
								<span id="projected_duration_error" class="help-block" style="color: #c65f5a;display: none;">Projected Duration should be hh:mm:ss.</span>
							</p>

						</td>
					</tr>



					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Color:</b></label>
						</td>
						<td>
							<p>

								<select id="color" name="color">
									<option value="">Select</option>
									<?php
									foreach ($pbcore_colors as $row)
									{
										$selected = '';
										if (isset($inst_color->color) && $inst_color->color == $row->value)
											$selected = 'selected="selected"';
										?>
										<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
									<?php }
									?>
								</select>
							</p>

						</td>
					</tr>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Tracks:</b></label>
						</td>
						<td>
							<p>
								<input type="text" id="tracks" name="tracks" value="<?php echo $instantiation_detail->tracks; ?>" />
							</p>

						</td>
					</tr>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Channel Configuration:</b></label>
						</td>
						<td>
							<p>
								<input type="text" id="channel_configuration" name="channel_configuration" value="<?php echo $instantiation_detail->channel_configuration; ?>" />
							</p>

						</td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Language:</b></label>
					</td>
					<td>
						<p>
							<input type="text" value="<?php echo $instantiation_detail->language; ?>"  id="language" name="language"/>
						</p>

					</td>
				</tr>
				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Media Type: <span class="label_star"> *</span> </b></label>
					</td>
					<td>
						<p>

							<select  id="media_type" name="media_type" style="width: 300px;">
								<option value="">Select</option>
								<?php
								foreach ($pbcore_media_types as $row)
								{
									$selected = '';
									if ($inst_media_type->media_type == $row->value)
										$selected = 'selected="selected"';
									?>
									<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
								<?php }
								?>

							</select>
							<span id="media_type_error" class="help-block" style="color: #c65f5a;display: none;">Media Type is required.</span>
						</p>

					</td>
				</tr>

				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Nomination Status:</b></label>
					</td>
					<td>
						<p>
							<select id="nomination" name="nomination">
								<option value="">Select</option>
								<?php
								foreach ($nominations as $row)
								{
									$selected = '';
									if (isset($ins_nomination->status) && $ins_nomination->status == $row->status)
										$selected = 'selected="selected"';
									?>
									<option value="<?php echo $row->status; ?>" <?php echo $selected; ?>><?php echo $row->status; ?></option>
								<?php }
								?>
							</select>
						</p>

					</td>
				</tr>
				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Nomination Reason:</b></label>
					</td>
					<td>
						<p>
							<textarea style="width: 450px;height: 90px;" id="nomination_reason" name="nomination_reason"><?php echo (isset($ins_nomination->nomination_reason)) ? $ins_nomination->nomination_reason : ''; ?></textarea>
						</p>

					</td>
				</tr>
				<?php
				if ($disable)
				{
					?>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Frame Rate:</b></label>
						</td>
						<td>
							<p>
								<input type="text" value="<?php echo (isset($essence_track->frame_rate) ? $essence_track->frame_rate : ''); ?>"  id="frame_rate" name="frame_rate"/>
								<span id="frame_rate_error" class="help-block" style="color: #c65f5a;display: none;">Frame rate must be numeric.</span>
							</p>

						</td>
					</tr>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Playback Speed:</b></label>
						</td>
						<td>
							<p>
								<input type="text" value="<?php echo (isset($essence_track->playback_speed) ? $essence_track->playback_speed : ''); ?>"  id="playback_speed" name="playback_speed"/>
							</p>

						</td>
					</tr>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b>Sampling Rate:</b></label>
						</td>
						<td>
							<p>
								<input type="text" value="<?php echo (isset($essence_track->sampling_rate) ? $essence_track->sampling_rate : ''); ?>"  id="sampling_rate" name="sampling_rate"/>
							</p>

						</td>
					</tr>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b>Frame Size:</b></label>
						</td>
						<td>
							<p>
								<input type="text" value="<?php echo (isset($essence_track->width) ? $essence_track->width : ''); ?>"  id="width" name="width" class="input-mini" placeholder="Width" /> x
								<input type="text" value="<?php echo (isset($essence_track->height) ? $essence_track->height : ''); ?>"  id="height" name="height" class="input-mini" placeholder="Height" />
								<span id="width_error" class="help-block" style="color: #c65f5a;display: none;">Width must be numeric.</span>
								<span id="height_error" class="help-block" style="color: #c65f5a;display: none;">Heigth rate must be numeric.</span>
							</p>

						</td>
					</tr>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b>Aspect Ratio:</b></label>
						</td>
						<td>
							<p>
								<input type="text" value="<?php echo (isset($essence_track->aspect_ratio) ? $essence_track->aspect_ratio : ''); ?>"  id="aspect_ratio" name="aspect_ratio" />
							</p>

						</td>
					</tr>
					<?php
				}
				?>
				<tr>
					<?php
					$add = ' ADD GENERATION';
					if (isset($inst_generation) && $inst_generation->generation != '')
					{
						$generations = explode('|', $inst_generation->generation);
					}
					?>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Generation:</b></label>
					</td>
					<td>
						<div id="main_generation">
							<?php
							if (isset($generations) && count($generations) > 0)
							{
								$add = ' ADD ANOTHER GENERATION';
								foreach ($generations as $index => $gen)
								{
									?>
									<div id="remove_generation_<?php echo $index; ?>" class="remove_generation">
										<div class="edit_form_div ins_edit_div">
											<div><p>Generation:</p></div>
											<div><p>
													<select id="generation_<?php echo $index; ?>" name="generation[]">
														<option value="">Select</option>
														<?php
														$commonly = $less = FALSE;
														foreach ($pbcore_generations as $row)
														{
															$selected = '';
															if (trim($gen) == $row->value)
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
										<div class="remove_element" onclick="removeElement('#remove_generation_<?php echo $index; ?>', 'generation');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>
									<?php
								}
							}
							?>

						</div>
						<div class="add-new-element" onclick="addElement('#main_generation', 'generation');"><i class="icon-plus-sign icon-white"></i><span id="add_generation"><?php echo $add; ?></span></div>
					</td>
				</tr>
				<?php
				if ($disable)
				{
					?>
					<tr>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b>Alternative Modes:</b></label>
						</td>
						<td>
							<p>
								<input type="text" value="<?php echo $instantiation_detail->alternative_modes; ?>"  id="alternative_modes" name="alternative_modes"/>
							</p>

						</td>
					</tr>
					<tr>
						<?php
						$add = ' ADD ANNOTATION';
						?>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Annotation:</b></label>
						</td>
						<td>
							<div id="main_annotation">
								<?php
								if (count($inst_annotation) > 0)
								{
									$add = ' ADD ANOTHER INSTANTIATION ID';
									foreach ($inst_annotation as $index => $annotation)
									{
										?>
										<div id="remove_annotation_<?php echo $index; ?>" class="remove_annotation">
											<div class="edit_form_div ins_edit_div">
												<div>
													<p>Annotation:</p>
													<p>
														<input type="text" id="annotation_<?php echo $index; ?>" name="annotation[]" value="<?php echo trim($annotation->annotation); ?>" />
													</p>
												</div>
												<div>
													<p>Annotation Type:</p>
													<p>
														<input type="text" id="annotation_type_<?php echo $index; ?>" name="annotation_type[]" value="<?php echo trim($annotation->annotation_type); ?>" />
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
						$add = ' ADD RELATION';
						?>
						<td class="record-detail-page ins_detail">
							<label><i class="icon-question-sign"></i><b> Relation:</b></label>
						</td>
						<td>
							<div id="main_relation">
								<?php
								if (count($inst_relation) > 0)
								{
									$add = ' ADD ANOTHER RELATION';
									foreach ($inst_relation as $index => $relation)
									{
										?>
										<div id="remove_relation_<?php echo $index; ?>" class="remove_relation">
											<div class="edit_form_div ins_edit_div">
												<div>
													<p>Relation:</p>
													<p>
														<input type="text" id="relation_<?php echo $index; ?>" name="relation[]" value="<?php echo trim($relation->relation_identifier); ?>" />
													</p>
												</div>
												<div>
													<p> Relation Type:</p>
													<p>
														<select id="relation_type_<?php echo $index; ?>" name="relation_type[]">
															<option value="">Select</option>
															<?php
															$commonly = $less = FALSE;
															foreach ($pbcore_relation_types as $row)
															{
																$selected = '';
																if ($relation->relation_type == $row->value)
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
														<input type="text" id="relation_source_<?php echo $index; ?>" name="relation_source[]" value="<?php echo $relation->relation_type_source; ?>" />
													</p>
												</div>
												<div>
													<p> Relation Ref:</p>
													<p>
														<input type="text" id="relation_ref_<?php echo $index; ?>" name="relation_ref[]" value="<?php echo $relation->relation_type_ref; ?>" />
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
					<?php
				}
				?>
				<tr>
					<td colspan="2">
						<a class="btn" href="<?php echo site_url('instantiations/detail/' . $inst_id); ?>">Cancel</a>
						<input type="button" onclick="validateForm();" value="Save Changes" class="btn btn-primary"/>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
<script type="text/javascript">
												var disable = '<?php echo ($disable ? 1 : 0); ?>';
												var pbcoreDateTypes =<?php echo json_encode($pbcore_asset_date_types); ?>;
												var pbcoreRelationTypes =<?php echo json_encode($pbcore_relation_types); ?>;
												var pbcoreMediaTypes =<?php echo json_encode($pbcore_media_types); ?>;
												var pbcoreGeneration =<?php echo json_encode($pbcore_generations); ?>;

</script>
<script type="text/javascript" src="/js/edit_instantiation.js?<?php echo time(); ?>"></script>