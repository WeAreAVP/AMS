<div class="row">
	<div style="margin: 2px 0px 10px 0px;float:left;width: 570px;">

		<?php
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
		<form class="form-horizontal" method="POST" action="<?php echo site_url('assets/edit/' . $asset_id); ?>" id="edit_asset_form">
			<table cellPadding="8" class="record-detail-table">
				<tr>
					<?php
					$add = ' ADD INSTANTIATION ID';
					?>
					<td class="record-detail-page">
						<label><i class="icon-question-sign"></i><b> INSTANTIATION ID:</b></label>
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
										<div class="edit_form_div">
											<div>
												<p>INSTANTIATION ID:</p>
												<p>
													<input type="text" id="instantiation_id_identifier_<?php echo $index; ?>" name="instantiation_id_identifier[]" value="<?php echo trim($identifier->instantiation_identifier); ?>" />
												</p>
											</div>
											<div>
												<p>INSTANTIATION ID SOURCE:</p>
												<p>
													<input type="text" id="instantiation_id_source_<?php echo $index; ?>" name="instantiation_id_source[]" value="<?php echo trim($identifier->instantiation_source); ?>" />
												</p>
											</div>

										</div>
										<div class="remove_element" onclick="removeElement('#remove_instantiation_id_<?php echo $index; ?>', 'instantiation_id');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>

									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_instantiation_id', 'instantiation_id');"><i class="icon-plus-sign icon-white"></i><span id="add_local_id"><?php echo $add; ?></span></div>

					</td>

				</tr>
				<tr>
					<?php
					$add = ' ADD DATE';
					?>
					<td class="record-detail-page">
						<label><i class="icon-question-sign"></i><b> Date:</b></label>
					</td>
					<td>
						<div id="main_date">
							<?php
							if (count($inst_dates) > 0)
							{
								$add = ' ADD ANOTHER DATE';
								foreach ($inst_dates as $index => $date)
								{
									?>
									<div id="remove_date_<?php echo $index; ?>" class="remove_date">
										<div class="edit_form_div">
											<div>
												<p>Instantiation Date:</p>
												<p>
													<input type="text" id="asset_date_<?php echo $index; ?>" name="asset_date[]" value="<?php echo $date->instantiation_date; ?>" />
												</p>
											</div>
											<div>
												<p>Instantiation Date Type:</p>
												<p>
													<select id="asset_date_type_<?php echo $index; ?>" name="asset_date_type[]">
														<?php
														foreach ($pbcore_asset_date_types as $row)
														{
															$selected = '';
															if ($date->date_type == $row->value)
																$selected = 'selected="selected"'
																?>
															<option value="<?php echo $row->value; ?>" <?php echo $selected; ?>><?php echo $row->value; ?></option>
														<?php }
														?>
													</select>
												</p>
											</div>
										</div>

										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>

									<?php
								}
							}
							?>
						</div>


					</td>

				</tr>
				<tr>
					<td class="record-detail-page">
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
										$selected = 'selected="selected"'
										?>
									<option value="<?php echo $row->status; ?>" <?php echo $selected; ?>><?php echo $row->status; ?></option>
								<?php }
								?>
							</select>
						</p>

					</td>
				</tr>
				<tr>
					<td class="record-detail-page">
						<label><i class="icon-question-sign"></i><b> Nomination Reason:</b></label>
					</td>
					<td>
						<p>
							<textarea style="width: 540px;height: 90px;" id="nomination_reason" name="nomination_reason"><?php echo (isset($ins_nomination->nomination_reason)) ? $ins_nomination->nomination_reason : ''; ?></textarea>
						</p>

					</td>
				</tr>

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



</script>
<!--<script type="text/javascript" src="/js/edit_asset.js"></script>-->