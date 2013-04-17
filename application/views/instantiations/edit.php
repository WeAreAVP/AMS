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
					$identifiers = explode('|', trim(str_replace('(**)', '', $asset_detail->identifier)));
					$identifier_sources = explode('|', trim(str_replace('(**)', '', $asset_detail->identifier_source)));
					$identifier_refs = explode('|', trim(str_replace('(**)', '', $asset_detail->identifier_ref)));
					$add = ' ADD INSTANTIATION ID';
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
											<div>
												<p>Local ID:</p>
												<p>
													<input type="text" id="asset_identifier_<?php echo $index; ?>" name="asset_identifier[]" value="<?php echo trim($identifier); ?>" />
												</p>
											</div>
											<div>
												<p>ID Source:</p>
												<p>
													<input type="text" id="asset_identifier_source_<?php echo $index; ?>" name="asset_identifier_source[]" value="<?php echo (isset($identifier_sources[$index])) ? trim($identifier_sources[$index]) : ''; ?>" />
												</p>
											</div>
											<div>
												<p>ID Ref:</p>
												<p>
													<input type="text" id="asset_identifier_ref_<?php echo $index; ?>" name="asset_identifier_ref[]" value="<?php echo (isset($identifier_refs[$index])) ? trim($identifier_refs[$index]) : ''; ?>" />
													<span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>
												</p>
											</div>
										</div>
										<div class="remove_element" onclick="removeElement('#remove_local_<?php echo $index; ?>', 'local_id');"><img src="/images/remove-item.png" /></div>
										<div class="clearfix" style="margin-bottom: 10px;"></div>
									</div>

									<?php
								}
							}
							?>
						</div>
						<div class="add-new-element" onclick="addElement('#main_local_id', 'local_id');"><i class="icon-plus-sign icon-white"></i><span id="add_local_id"><?php echo $add; ?></span></div>

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