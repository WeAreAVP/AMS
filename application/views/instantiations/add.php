<div class="row">



	<div class="span9" style="margin-left: 250px;" id="ins_view_detail">
		<form class="form-horizontal" method="POST" action="<?php echo site_url('instantiations/add/' . $asset_id); ?>" id="edit_instantiation_form">
			<table cellPadding="8" class="record-detail-table">
				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Instantiation ID:</b></label>
					</td>
					<td>
						<div id="main_instantiation_id">

							<div id="remove_instantiation_id_0" class="remove_instantiation_id">
								<div class="edit_form_div ins_edit_div">
									<div>
										<p>Instantiation ID:</p>
										<p>
											<input type="text" id="instantiation_id_identifier_0" name="instantiation_id_identifier[]" value="" />
											<span  class="help-block" style="display:none;">Instantiation ID is required.</span>
										</p>
									</div>

									<div>
										<p>Instantiation ID Source:</p>
										<p>
											<input type="text" id="instantiation_id_source_0" name="instantiation_id_source[]" value="" />
											<span  class="help-block" style="display:none;">Instantiation ID Source is required.</span>
										</p>
									</div>

								</div>


								<div class="clearfix" style="margin-bottom: 10px;"></div>
							</div>


						</div>

						<div class="add-new-element" onclick="addElement('#main_instantiation_id', 'instantiation_id');"><i class="icon-plus-sign icon-white"></i><span id="add_instantiation_id"> ADD ANOTHER INSTANTIATION ID</span></div>

					</td>

				</tr>


				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Instantiation Date:</b></label>
					</td>
					<td>
						<div id="main_instantiation_date">
							<div id="remove_instantiation_date_0" class="remove_instantiation_date">
								<div class="edit_form_div ins_edit_div">
									<div>
										<p>Instantiation ID:</p>
										<p><input readonly="readonly" type="text" id="inst_date_0" name="inst_date[]" value="" /></p>
									</div>

									<div>
										<p>Instantiation ID Source:</p>
										<p>
											<select id="inst_date_type_0" name="inst_date_type[]">
												<?php
												foreach ($pbcore_asset_date_types as $row)
												{
													?>
													<option value="<?php echo $row->value; ?>"><?php echo $row->value; ?></option>
												<?php }
												?>
											</select>
										</p>
									</div>

								</div>

								<div class="remove_element" onclick="removeElement('#remove_instantiation_date_0', 'instantiation_date');"><img src="/images/remove-item.png" /></div>

								<div class="clearfix" style="margin-bottom: 10px;"></div>
							</div>

						</div>

						<div class="add-new-element" onclick="addElement('#main_instantiation_date', 'instantiation_date');"><i class="icon-plus-sign icon-white"></i><span id="add_instantiation_date"> ADD ANOTHER INSTANTIATION DATE</span></div>
					</td>
				</tr>

				<tr>

					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Dimension:</b></label>
					</td>
					<td>
						<div id="main_dimension">

							<div id="remove_dimension_0" class="remove_dimension">
								<div class="edit_form_div ins_edit_div">
									<div>
										<p>Dimension:</p>
										<p>
											<input type="text" id="dimension_0" name="asset_dimension[]" value="" />

										</p>
									</div>
									<div>
										<p>Unit of measure:</p>
										<p>
											<input type="text" id="dimension_unit_0" name="dimension_unit[]" value="" />
										</p>
									</div>
								</div>

								<div class="clearfix" style="margin-bottom: 10px;"></div>
							</div>


						</div>
						<div class="add-new-element" onclick="addElement('#main_dimension', 'dimension');"><i class="icon-plus-sign icon-white"></i><span id="add_dimension"> ADD ANOTHER DIMENSION</span></div>

					</td>

				</tr>
				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Physical Format:</b></label>
					</td>
					<td>
						<p>
							<select id="physical_format" name="physical_format">
								<?php
								foreach ($pbcore_physical_formats as $row)
								{
									?>
									<option value="<?php echo $row->value; ?>"><?php echo $row->value; ?></option>
								<?php }
								?>
							</select>
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
								<?php
								foreach ($pbcore_standards as $row)
								{
									?>
									<option value="<?php echo $row->value; ?>" ><?php echo $row->value; ?></option>
								<?php }
								?>
							</select>
						</p>

					</td>
				</tr>
				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Location:</b></label>
					</td>
					<td>
						<p>
							<input type="text" id="location" name="location" value="" />
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
							<input type="text" id="time_start" name="time_start" value="" />
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
							<input type="text" id="projected_duration" name="projected_duration" value="" />
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
								<?php
								foreach ($pbcore_colors as $row)
								{
									?>
									<option value="<?php echo $row->value; ?>" ><?php echo $row->value; ?></option>
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
							<input type="text" id="tracks" name="tracks" value="" />
						</p>

					</td>
				</tr>
				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Channel Configuration:</b></label>
					</td>
					<td>
						<p>
							<input type="text" id="channel_configuration" name="channel_configuration" value="" />
						</p>

					</td>
				</tr>

				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Language:</b></label>
					</td>
					<td>
						<p>
							<input type="text" value=""  id="language" name="language"/>
						</p>

					</td>
				</tr>
				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Media Type:</b></label>
					</td>
					<td>
						<p>

							<select  id="media_type" name="media_type" style="width: 300px;">
								<?php
								foreach ($pbcore_media_types as $row)
								{
									?>
									<option value="<?php echo $row->value; ?>" ><?php echo $row->value; ?></option>
								<?php }
								?>

							</select>
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
									?>
									<option value="<?php echo $row->status; ?>" ><?php echo $row->status; ?></option>
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
							<textarea style="width: 450px;height: 90px;" id="nomination_reason" name="nomination_reason"></textarea>
						</p>

					</td>
				</tr>

				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Frame Rate:</b></label>
					</td>
					<td>
						<p>
							<input type="text" value=""  id="frame_rate" name="frame_rate"/>
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
							<input type="text" value=""  id="playback_speed" name="playback_speed"/>
						</p>

					</td>
				</tr>
				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b>Sampling Rate:</b></label>
					</td>
					<td>
						<p>
							<input type="text" value=""  id="sampling_rate" name="sampling_rate"/>
						</p>

					</td>
				</tr>
				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b>Frame Size:</b></label>
					</td>
					<td>
						<p>
							<input type="text" value=""  id="width" name="width" class="input-mini" placeholder="Width" /> x
							<input type="text" value=""  id="height" name="height" class="input-mini" placeholder="Height" />
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
							<input type="text" value=""  id="aspect_ratio" name="aspect_ratio" />
						</p>

					</td>
				</tr>

				<tr>

					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Generation:</b></label>
					</td>
					<td>
						<div id="main_generation">

							<div id="remove_generation_0" class="remove_generation">
								<div class="edit_form_div ins_edit_div">
									<div><p>Generation:</p></div>
									<div><p>
											<select id="generation_0" name="generation[]">
												<?php
												foreach ($pbcore_generations as $row)
												{
													?>
													<option value="<?php echo $row->value; ?>" ><?php echo $row->value; ?></option>
												<?php }
												?>
											</select>
										</p>
									</div>
								</div>

								<div class="clearfix" style="margin-bottom: 10px;"></div>
							</div>


						</div>
						<div class="add-new-element" onclick="addElement('#main_generation', 'generation');"><i class="icon-plus-sign icon-white"></i><span id="add_generation"> ADD ANOTHER GENERATION</span></div>
					</td>
				</tr>

				<tr>
					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b>Alternative Modes:</b></label>
					</td>
					<td>
						<p>
							<input type="text" value=""  id="alternative_modes" name="alternative_modes"/>
						</p>

					</td>
				</tr>
				<tr>

					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Annotation:</b></label>
					</td>
					<td>
						<div id="main_annotation">

							<div id="remove_annotation_0" class="remove_annotation">
								<div class="edit_form_div ins_edit_div">
									<div>
										<p>Annotation:</p>
										<p>
											<input type="text" id="annotation_0" name="annotation[]" value="" />
										</p>
									</div>
									<div>
										<p>Annotation Type:</p>
										<p>
											<input type="text" id="annotation_type_0" name="annotation_type[]" value="" />
										</p>
									</div>

								</div>

								<div class="clearfix" style="margin-bottom: 10px;"></div>
							</div>


						</div>
						<div class="add-new-element" onclick="addElement('#main_annotation', 'annotation');"><i class="icon-plus-sign icon-white"></i><span id="add_annotation"> ADD ANOTHER INSTANTIATION ID</span></div>

					</td>

				</tr>
				<tr>

					<td class="record-detail-page ins_detail">
						<label><i class="icon-question-sign"></i><b> Relation:</b></label>
					</td>
					<td>
						<div id="main_relation">

							<div id="remove_relation_0" class="remove_relation">
								<div class="edit_form_div ins_edit_div">
									<div>
										<p>Relation:</p>
										<p>
											<input type="text" id="relation_0" name="relation[]" value="" />
										</p>
									</div>
									<div>
										<p> Relation Type:</p>
										<p>
											<select id="relation_type_0" name="relation_type[]">
												<?php
												foreach ($pbcore_relation_types as $row)
												{
													?>
													<option value="<?php echo $row->value; ?>" ><?php echo $row->value; ?></option>
												<?php }
												?>
											</select>

										</p>
									</div>
									<div>
										<p> Relation Source:</p>
										<p>
											<input type="text" id="relation_source_0" name="relation_source[]" value="" />
										</p>
									</div>
									<div>
										<p> Relation Ref:</p>
										<p>
											<input type="text" id="relation_ref_0" name="relation_ref[]" value="" />
											<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
										</p>
									</div>

								</div>

								<div class="clearfix" style="margin-bottom: 10px;"></div>
							</div>


						</div>
						<div class="add-new-element" onclick="addElement('#main_relation', 'relation');"><i class="icon-plus-sign icon-white"></i><span id="add_relation"> ADD ANOTHER RELATION</span></div>

					</td>

				</tr>

				<tr>
					<td colspan="2">

						<input type="checkbox"  value="1" name="add_another" /> <span>Create Another </span>
						<input type="button" onclick="validateForm();" value="Create" class="btn btn-primary"/>
						<a href="<?php echo site_url('records/details/' . $asset_id); ?>" class="btn">Cancel</a>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
<script type="text/javascript">
							var disable = '0';
							var pbcoreDateTypes =<?php echo json_encode($pbcore_asset_date_types); ?>;
							var pbcoreRelationTypes =<?php echo json_encode($pbcore_relation_types); ?>;
							var pbcoreMediaTypes =<?php echo json_encode($pbcore_media_types); ?>;
							var pbcoreGeneration =<?php echo json_encode($pbcore_generations); ?>;

</script>
<script type="text/javascript" src="/js/edit_instantiation.js?<?php echo time(); ?>"></script>