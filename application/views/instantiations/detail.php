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
	<?php
	if ($next_result_id)
	{
		?>
		<div style="float: right;margin-left:5px"><a href="<?php echo site_url('instantiations/detail/' . $next_result_id); ?>" class="btn">Next >></a></div>
		<?php
	}
	if ($prev_result_id)
	{
		?>
		<div style="float: right;margin-left:5px"><a href="<?php echo site_url('instantiations/detail/' . $prev_result_id); ?>" class="btn"><< Previous</a></div>
		<?php
	} if ( ! is_empty($last_page))
	{
		?>
		<div style="float: right;margin-left:5px;"><a href="<?php echo site_url($last_page); ?>" class="btn">Return</a></div>
	<?php } ?>
	<!--	<div style="float: right;">
			<button class="btn "><span class="icon-download-alt"></span>Export Instantiation</button>
		</div>-->
	<div class="clearfix"></div>
	<?php $this->load->view('partials/_list'); ?>

	<div class="span9" style="margin-left: 250px;" id="ins_view_detail">
		<?php $this->load->view('partials/_proxy_files'); ?>
		<div style="float: left;margin-left: 10px;">

			<?php
			if ($this->role_id != '20')
			{
				?>

				<div>
					<a href="<?php echo site_url('instantiations/edit/' . $inst_id); ?>" class="btn">Edit Instantiation</a>
					<a href="<?php echo site_url('instantiations/add/' . $asset_id); ?>" class="btn">Add Instantiation</a>
				</div>
			<?php } ?>
			<table  cellPadding="8" class="record-detail-table">
				<!--				Instantiation ID	Start		-->
				<?php
				if (count($inst_identifier) > 0)
				{
					$combine_identifier = '';
					foreach ($inst_identifier as $index => $identifier)
					{
						$combine_identifier.= '<p>';
						$combine_identifier.= $identifier->instantiation_identifier;
						if ( ! empty($identifier->instantiation_source))
							$combine_identifier.=' (' . $identifier->instantiation_source . ')';
						$combine_identifier.= '</p>';
					}
					if ( ! empty($combine_identifier) && trim($combine_identifier) != ':')
					{
						?>
						<tr>
							<td class="record-detail-page">
								<label>
									<a data-placement="left" rel="tooltip" href="#" data-original-title="Unique Identifier: A unique identifier string for a particular instantiation of a media item. Best practice is to use an identification method that is in use within your agency, station, production company, office, or institution.<br/><br/>Identifier Source: Used in conjunction with Unique Identifer. Provides not only a locator number, but also indicates an agency or institution who assigned it. Therefore, if your station or organization created this ID, enter in your station/organization name in this field. If the ID came from an outside entity or standards organization, enter the name of that entity here."><i class="icon-question-sign"></i></a>
									<b><span class="label_star"> *</span> Instantiation ID:</b></label>
							</td>
							<td>

								<p><?php echo $combine_identifier; ?></p>

							</td>
						</tr>
						<?php
					}
				}
				?>
				<!--				Instantiation ID	End		-->
				<!--				Date 	Start		-->
				<?php
				if (count($inst_dates) > 0)
				{
					$combine_dates = '';
					foreach ($inst_dates as $index => $date)
					{
						if (isset($date->date_type) && ! empty($date->date_type))
							$combine_dates .=$date->date_type . ' : ';
						$combine_dates .=$date->instantiation_date . '<br/>';
					}
					if ( ! empty($combine_dates) && trim($combine_dates) != ':')
					{
						?>
						<tr>
							<td class="record-detail-page">
								<label>
									<a data-placement="left" rel="tooltip" href="#" data-original-title="Date Created: Specifies the creation date for a particular version or rendition of a media item across its life cycle. It is the moment in time that the media item was finalized during its production process and is forwarded to other divisions or agencies to make it ready for publication or distribution. The recommended format consists of a text string for the representation of dates YYYY-MM-DD (1998–01-24). If you don’t have a full YYYY-MM-DD then use this format to the extent of the information you do have.<br/><br/>Date Broadcast/Issued: Specifies the formal date for a particular version or rendition of a media item has been made ready or officially released for distribution, publication or consumption. The recommended format consists of a text string for the representation of dates YYYY-MM-DD (1998–01-24). If you don’t have a full YYYY-MM-DD then use this format to the extent of the information you do have."><i class="icon-question-sign"></i></a>
									<b> Date:</b></label>
							</td>
							<td>
								<p>	<?php echo $combine_dates; ?></p>

							</td>
						</tr>
						<?php
					}
				}
				?>


				<!--				Date 	End		-->
				<!--				Media Type 	Start		-->
				<?php
				if (isset($inst_media_type->media_type) && $inst_media_type->media_type != '')
				{
					$media_type = explode(' | ', $inst_media_type->media_type);
					?>
					<tr>
						<td class="record-detail-page">
							<label>
								<a data-placement="left" rel="tooltip" href="#" data-original-title="Identifies the general, high level nature of the content of a media item. It uses categories that show how content is presented to an observer, e.g., as a sound, text or moving image."><i class="icon-question-sign"></i></a>
								<b><span class="label_star"> *</span> Media Type:</b></label>
						</td>
						<td>
							<?php
							foreach ($media_type as $value)
							{
								?>
								<p><?php echo $value; ?></p>
							<?php }
							?>
						</td>
					</tr>
				<?php } ?>
				<!--				Media Type	End		-->
				<!--				Format 	Start		-->
				<?php
				if (isset($inst_format->format_name) && $inst_format->format_name != '')
				{

					$format = 'Format: ';
					if (isset($inst_format->format_type) && ($inst_format->format_type != NULL))
					{
						if ($inst_format->format_type === 'physical')
						{
							$format = 'Physical Format: ';
							$format_tooltip = 'The format of a particular version or rendition of a media item as it exists in an actual physical form.';
						}
						if ($inst_format->format_type === 'digital')
						{
							$format = 'Digital Format: ';
							$format_tooltip = 'Identifies the format of a particular rendition of a media item in its digital form. Digital media formats may be expressed with formal Internet MIME types.MIME types available at IANA:
video:http://www.iana.org/assignments/media-types/video/index.html
audio:http://www.iana.org/assignments/media-types/audio/index.html';
						}
					}
					?>	
					<tr>
						<td class="record-detail-page">
							<label>
								<a data-placement="left" rel="tooltip" href="#" data-original-title="<?php echo $format_tooltip; ?>"><i class="icon-question-sign"></i></a>
								<b>  <?php echo $format; ?></b></label>
						</td>
						<td>
							<span>	<?php echo $inst_format->format_name; ?></span>
						</td>
					</tr>
				<?php } ?>
				<!--				Format	End		-->
				<!--				Generation 	Start		-->
				<?php
				if (isset($inst_generation) && $inst_generation->generation != '')
				{

					$generations = explode(' | ', $inst_generation->generation);
					?>	
					<tr>
						<td class="record-detail-page">
							<label>
								<a data-placement="left" rel="tooltip" href="#" data-original-title="Identifies the particular use or manner in which an instantiation of a media item is used. See also explanations of generation terms."><i class="icon-question-sign"></i></a>
								<b> Generation:</b></label>
						</td>
						<td>
							<?php
							foreach ($generations as $generation)
							{
								?>
								<p>	<?php echo $generation; ?></p>
							<?php } ?>
						</td>
					</tr>

				<?php } ?>
				<!--				Generation	End		-->
				<!--				Location 	Start		-->
				<?php
				if ($detail_instantiation->location && $detail_instantiation->location != '')
				{
					?>	
					<tr>
						<td class="record-detail-page">
							<label>
								<a data-placement="left" rel="tooltip" href="#" data-original-title="May contain information about an organization or building, a specific vault location for an asset, including an organization’s name, departmental name, shelf ID and contact information. For a data file or web page, this location may be virtual and include domain, path, file name or html page. The data may be a name (person or organization),URL, URI, physical location ID, barcode, etc."><i class="icon-question-sign"></i></a>
								<span class="label_star"> *</span><b> Location:</b></label>
						</td>
						<td>
							<p>	<?php echo $detail_instantiation->location; ?></p>

						</td>
					</tr>

				<?php } ?>
				<!--				Location	End		-->
				<!--				Duration 	Start		-->
				<?php
				if ($detail_instantiation->projected_duration !== NULL && $detail_instantiation->projected_duration !== '')
				{
					?>	
					<tr>
						<td class="record-detail-page">
							<label>
								<a data-placement="left" rel="tooltip" href="#" data-original-title="Provides a timestamp for the overall length or duration of a time-based media item. It represents the playback time. NOTE— In many instances you may not know the ACTUAL recorded time of the item you are inventorying. If this is the case, please check YES in the column to the right marked “Approximate?” This will help us differentiate from actual vs. estimated durations."><i class="icon-question-sign"></i></a>
								<span class="label_star"> *</span><b> Duration:</b></label>
						</td>
						<td>

							<p><?php echo $detail_instantiation->projected_duration; ?></p>

						</td>
					</tr>

					<?php
				}
				else if ($detail_instantiation->actual_duration !== '' && $detail_instantiation->actual_duration !== NULL)
				{
					?>	
					<tr>
						<td class="record-detail-page">
							<label>
								<a data-placement="left" rel="tooltip" href="#" data-original-title="Provides a timestamp for the overall length or duration of a time-based media item. It represents the playback time. NOTE— In many instances you may not know the ACTUAL recorded time of the item you are inventorying. If this is the case, please check YES in the column to the right marked “Approximate?” This will help us differentiate from actual vs. estimated durations."><i class="icon-question-sign"></i></a>
								<span class="label_star"> *</span><b> Duration:</b></label>
						</td>
						<td>
							<p>	<?php echo date('H:i:s', strtotime($detail_instantiation->actual_duration)); ?></p>

						</td>
					</tr>

				<?php } ?>
				<!--				Duration	End		-->
				<!--				Time Start 	Start		-->
				<?php
				if ($detail_instantiation->time_start && $detail_instantiation->time_start != '')
				{
					?>	
					<tr>
						<td class="record-detail-page">
							<label><b> Time Start:</b></label>
						</td>
						<td>
							<p>	<?php echo $detail_instantiation->time_start; ?></p>

						</td>
					</tr>

				<?php } ?>
				<!--				Time Start	End		-->
				<!--				File Size 	Start		-->
				<?php
				if ($detail_instantiation->file_size && $detail_instantiation->file_size != '')
				{
					?>	
					<tr>
						<td class="record-detail-page">
							<label>
								<a data-placement="left" rel="tooltip" href="#" data-original-title="Indicates the storage requirements or file size of a digital media item. Include your unit of measure (kB, MB, GB)."><i class="icon-question-sign"></i></a>
								<b> File Size:</b></label>
						</td>
						<td>
							<p>	<?php echo $detail_instantiation->file_size . ' ' . $detail_instantiation->file_size_unit_of_measure; ?></p>

						</td>
					</tr>

				<?php } ?>
				<!--				File Size	End		-->
				<!--				Standard 	Start		-->
				<?php
				if ($detail_instantiation->standard && $detail_instantiation->standard != '')
				{
					?>	
					<tr>
						<td class="record-detail-page">
							<label><b> Standard:</b></label>
						</td>
						<td>
							<p>	<?php echo $detail_instantiation->standard; ?></p>

						</td>
					</tr>

				<?php } ?>
				<!--				Standard	End		-->
				<!--				Dimensions: 	Start		-->
				<?php
				if (count($inst_demension) > 0)
				{
					$combine_demension = '';
					foreach ($inst_demension as $index => $demension)
					{
						$combine_demension .=$demension->instantiation_dimension . ' ' . $demension->unit_of_measure . '<br/>';
					}
					if ( ! empty($combine_demension))
					{
						?>
						<tr>
							<td class="record-detail-page">
								<label><b> Dimensions:</b></label>
							</td>
							<td>
								<p>	<?php echo $combine_demension; ?></p>

							</td>
						</tr>
						<?php
					}
				}
				?>
				<!--				Dimensions	End		-->
				<!--				Data Rate 	Start		-->
				<?php
				if ($detail_instantiation->data_rate != '')
				{
					?>	
					<tr>
						<td class="record-detail-page">
							<label><b> Data Rate:</b></label>
						</td>
						<td>
							<?php $data_rate_unit = (isset($inst_data_rate_unit->unit_of_measure)) ? $inst_data_rate_unit->unit_of_measure : ''; ?>
							<p>	<?php echo $detail_instantiation->data_rate . ' ' . $data_rate_unit; ?></p>

						</td>
					</tr>

				<?php } ?>
				<!--				Data Rate	End		-->
				<!--			 Color 	Start		-->
				<?php
				if (isset($inst_color->color) && $inst_color->color != '')
				{
					?>	
					<tr>
						<td class="record-detail-page">
							<label><b> Color:</b></label>
						</td>
						<td>
							<p>	<?php echo $inst_color->color; ?></p>

						</td>
					</tr>

				<?php } ?>
				<!--				Color	End		-->
				<!--			 Tracks 	Start		-->
				<?php
				if ($detail_instantiation->tracks && $detail_instantiation->tracks != '')
				{
					?>	
					<tr>
						<td class="record-detail-page">
							<label><b> Tracks:</b></label>
						</td>
						<td>
							<p>	<?php echo $detail_instantiation->tracks; ?></p>

						</td>
					</tr>

				<?php } ?>
				<!--				Tracks	End		-->
				<!--			 Channel Configuration 	Start		-->
				<?php
				if ($detail_instantiation->channel_configuration && $detail_instantiation->channel_configuration)
				{
					?>	
					<tr>
						<td class="record-detail-page">
							<label><b> Channel Configuration:</b></label>
						</td>
						<td>
							<p>	<?php echo $detail_instantiation->channel_configuration; ?></p>

						</td>
					</tr>

				<?php } ?>
				<!--				Channel Configuration	End		-->
				<!--			 Language 	Start		-->
				<?php
				if ($detail_instantiation->language && $detail_instantiation->language)
				{
					?>	
					<tr>
						<td class="record-detail-page">
							<label>
								<a data-placement="left" rel="tooltip" href="#" data-original-title="Identifies the primary language of a media item’s audio or text. Best practice is to use the 3 letter ISO 639.2 or 639.3 code for languages. If the media item has more than one language that is considered part of the same primary audio or text, then a combination statement can be crafted, e.g., eng;fre for the presence of both English and French in the primary audio. Separating three-letter language codes with a semi-colon (no additional spaces) is preferred."><i class="icon-question-sign"></i></a>
								<b> Language:</b></label>
						</td>
						<td>
							<p>	<?php echo $detail_instantiation->language; ?></p>

						</td>
					</tr>

				<?php } ?>
				<!--				Language	End		-->
				<!--			 Annotation 	Start		-->
				<?php
				if (count($inst_annotation) > 0)
				{
					$combine_annotation = '';
					foreach ($inst_annotation as $index => $annotation)
					{
						if (isset($annotation->annotation_type) && ! empty($annotation->annotation_type))
							$combine_annotation .=$annotation->annotation_type . ' : ';
						$combine_annotation .=$annotation->annotation . '<br/>';
						?>

						<?php
					}
					if ( ! empty($combine_annotation) && trim($combine_annotation) != ':')
					{
						?>
						<tr>
							<td class="record-detail-page">
								<label><b> Annotation:</b></label>
							</td>
							<td>
								<p>	<?php echo $combine_annotation; ?></p>

							</td>
						</tr>
						<?php
					}
				}
				?>

				<!--				Annotation	End		-->
				<!--  Relation Start  -->
				<?php
				if (count($inst_relation) > 0)
				{
					$combine_relation = '';
					foreach ($inst_relation as $index => $relation)
					{
						if (isset($relation->relation_type) && ! empty($relation->relation_type))
							$combine_relation .=$relation->relation_type . ' : ';
						$combine_relation .=$relation->relation_identifier;
						if (isset($relation->relation_type_source) && ! empty($relation->relation_type_source))
							$relation_type_src = $relation->relation_type_source;
						if (isset($relation->relation_type_ref) && ! empty($relation->relation_type_ref))
							$combine_relation .= " (<a href='$relation->relation_type_ref' target='_blank'>$relation_type_src</a>)";
						else if (isset($relation_type_src) && ! empty($relation_type_src))
							$combine_relation .=' (' . $relation_type_src . ')';
						$combine_relation .='<br/>';
					}
					if ( ! empty($combine_relation) && trim($combine_relation) != ':')
					{
						?>
						<tr>
							<td class="record-detail-page">
								<label><b> Relation:</b></label>
							</td>
							<td>
								<p>	<?php echo $combine_relation; ?></p>

							</td>
						</tr>
						<?php
					}
				}
				?>
				<!--  Relation End  -->
			</table>

		</div>

		<?php
		if (isset($ins_nomination) && ! empty($ins_nomination))
		{
			?>
			<div class="nomination-container">
				<?php
				if ($ins_nomination->status == 'Nominated/1st Priority')
				{
					?>
					<div style="border-bottom: 1px solid #DDD;float: left;width: 100%;">
						<div style="padding: 10px; width: 90px;float: left;"><div class="nomination_status">NOMINATION PRIORITY</div></div>
						<div class="priority">1</div>
					</div>
					<?php
				}
				else if ($ins_nomination->status == 'Nominated/2nd Priority')
				{
					?>
					<div style="border-bottom: 1px solid #DDD;float: left;width: 100%;">
						<div style="padding: 10px; width: 90px;float: left;"><div class="nomination_status">NOMINATION PRIORITY</div></div>
						<div class="priority">2</div>
					</div>


					<?php
				}
				else
				{
					?>
					<div style="border-bottom: 1px solid #DDD;float: left;width: 100%;">
						<div style="padding: 10px; float: left;"><div class="nomination_status">WAITING LIST</div></div>

					</div>
				<?php } ?>
				<div class="clearfix"></div>
				<?php
				if ( ! empty($ins_nomination->nomination_reason))
				{
					?>
					<div>Nomination reason: <?php echo $ins_nomination->nomination_reason; ?></div>
				<?php } ?>
				<?php
				if ($ins_nomination->nominated_by != '')
				{
					?>
					<p><?php echo 'Nominated by ' . $ins_nomination->first_name . ' ' . $ins_nomination->last_name; ?></p>
					<?php
				}
				if ($ins_nomination->nominated_at != '')
				{
					?>
					<p><?php echo ' at ' . $ins_nomination->nominated_at; ?></p>

				<?php }
				?>
			</div>
			<?php
		}
		?>
		<?php
		if (isset($instantiation_events) && ! is_empty($instantiation_events))
		{
			?>
			<table cellpadding="4" class="table table-bordered" >
				<thead>
				<th>Event Type</th>
				<th>Event Date</th>
				<th>Event Note</th>
				<th>Event Outcome</th>
				</thead>
				<tbody>
					<?php
					foreach ($instantiation_events as $events)
					{
						?>
						<tr>
							<td><?php echo (isset($events->event_type) && ! is_empty($events->event_type)) ? $events->event_type : ''; ?></td>
							<td><?php echo (isset($events->event_date) && ! is_empty($events->event_date)) ? $events->event_date : ''; ?></td>
							<td><?php echo (isset($events->event_note) && ! is_empty($events->event_note)) ? $events->event_note : ''; ?></td>
							<td><?php echo (isset($events->event_outcome) && ! is_empty($events->event_outcome)) ? $events->event_outcome : ''; ?></td>
						</tr>
			<?php } ?>
				</tbody></table>
		<?php }
		?>
	</div>
	<div class="clearfix"></div>


<?php $this->load->view('essence_track/list'); ?>
</div>
