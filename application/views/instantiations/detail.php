<div class="row">
				<div style="margin: 2px 0px 10px 0px;float:left;">

								<?php
								$asset_title_type	=	explode('|',	trim(str_replace('(**)',	'',	$asset_details->title_type)));
								$asset_title	=	explode('|',	trim(str_replace('(**)',	'',	$asset_details->title)));
								$asset_title_ref	=	explode('|',	trim(str_replace('(**)',	'',	$asset_details->title_ref)));
								$combine_title	=	'';
								foreach($asset_title	as	$index	=>	$title)
								{
												if(isset($asset_title_type[$index])	&&	$asset_title_type[$index]	!=	'')
																$combine_title.=	$asset_title_type[$index]	.	': ';
												if(isset($asset_title_ref[$index]))
												{
																if($asset_title_ref[$index]	!=	'')
																{
																				$combine_title.="<a target='_blank' href='$asset_title_ref[$index]'>$title</a>: ";
																				$combine_title.=' ('	.	$asset_title_ref[$index]	.	')';
																}
																else
																				$combine_title.=$title;
												}
												else
																$combine_title.=$title;
												$combine_title.='<div class="clearfix"></div>';
								}
								?>
								<h2><?php	echo	$combine_title;	?></h2>
				</div>
				<?php
				if($next_result_id)
				{
								?>
								<div style="float: right;margin-left:5px"><a href="<?php	echo	site_url('instantiations/detail/'	.	$next_result_id);	?>" class="btn">Next >></a></div>
								<?php
				}
				if($prev_result_id)
				{
								?>
								<div style="float: right;margin-left:5px"><a href="<?php	echo	site_url('instantiations/detail/'	.	$prev_result_id);	?>" class="btn"><< Previous</a></div>
								<?php
				}	if(	!	is_empty($last_page))
				{
								?>
								<div style="float: right;margin-left:5px;"><a href="<?php	echo	site_url($last_page);	?>" class="btn">Return</a></div>
				<?php	}	?>
				<div style="float: right;">
								<button class="btn "><span class="icon-download-alt"></span>Export Instantiation</button>
				</div>
				<div class="clearfix"></div>
				<?php	$this->load->view('partials/_list');	?>

				<div class="span12" style="margin-left: 285px;">
								<div style="float: left;">
												<table  cellPadding="8" class="record-detail-table">
																<!--				Instantiation ID	Start		-->
																<?php
																if($instantiation_detail->instantiation_identifier	||	$instantiation_detail->instantiation_source)
																{
																				$ins_identifier	=	explode(' | ',	trim(str_replace('(**)',	'',	$instantiation_detail->instantiation_identifier)));
																				$ins_identifier_src	=	explode(' | ',	trim(str_replace('(**)',	'',	$instantiation_detail->instantiation_source)));
																				$combine_identifier	=	'';
																				foreach($ins_identifier	as	$index	=>	$identifier)
																				{
																								$combine_identifier.=	'<p>';
																								$combine_identifier.=	$identifier;
																								if(isset($ins_identifier_src[$index])	&&	!	empty($ins_identifier_src[$index]))
																												$combine_identifier.=' ('	.	$ins_identifier_src[$index]	.	')';
																								$combine_identifier.=	'</p>';
																				}
																				?>
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b><span class="label_star"> *</span> Instantiation ID:</b></label>
																								</td>
																								<td>

																												<p><?php	echo	$combine_identifier;	?></p>

																								</td>
																				</tr>
																<?php	}	?>
																<!--				Instantiation ID	End		-->
																<!--				Date 	Start		-->
																<?php
																if($instantiation_detail->dates	||	$instantiation_detail->date_type)
																{
																				?>
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b> Date:</b></label>
																								</td>
																								<td>
																												<?php
																												if($instantiation_detail->date_type)
																												{
																																?>
																																<span><?php	echo	$instantiation_detail->date_type	.	':';	?></span>
																																<?php
																																if($instantiation_detail->dates)
																																{
																																				?>
																																				<span><?php	echo	date("Y-m-d",	$instantiation_detail->dates);	?></span>

																																<?php	}	?>

																												<?php	}	?>
																								</td>
																				</tr>
																<?php	}	?>
																<!--				Date 	End		-->
																<!--				Media Type 	Start		-->
																<?php
																if($instantiation_detail->media_type)
																{
																				$media_type	=	explode(' | ',	$instantiation_detail->media_type);
																				?>
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b><span class="label_star"> *</span> Media Type:</b></label>
																								</td>
																								<td>
																												<?php
																												foreach($media_type	as	$value)
																												{
																																?>
																																<p><?php	echo	$value;	?></p>
																												<?php	}
																												?>
																								</td>
																				</tr>
																<?php	}	?>
																<!--				Media Type	End		-->
																<!--				Format 	Start		-->
																<?php
																if($instantiation_detail->format_name)
																{

																				$format	=	'Format: ';
																				if(isset($instantiation_detail->format_type)	&&	($instantiation_detail->format_type	!=	NULL))
																				{
																								if($instantiation_detail->format_type	===	'physical')
																												$format	=	'Physical Format: ';
																								if($instantiation_detail->format_type	===	'digital')
																												$format	=	'Digital Format: ';
																				}
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b>  <?php	echo	$format;	?></b></label>
																								</td>
																								<td>
																												<span>	<?php	echo	$instantiation_detail->format_name;	?></span>
																								</td>
																				</tr>
																<?php	}	?>
																<!--				Format	End		-->
																<!--				Generation 	Start		-->
																<?php
																if($instantiation_detail->generation)
																{

																				$generations	=	explode(' | ',	$instantiation_detail->generation);
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b> Generation:</b></label>
																								</td>
																								<td>
																												<?php
																												foreach($generations	as	$generation)
																												{
																																?>
																																<p>	<?php	echo	$generation;	?></p>
																												<?php	}	?>
																								</td>
																				</tr>

																<?php	}	?>
																<!--				Generation	End		-->
																<!--				Location 	Start		-->
																<?php
																if($instantiation_detail->location)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><span class="label_star"> *</span><b> Location:</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	$instantiation_detail->location;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Location	End		-->
																<!--				Duration 	Start		-->
																<?php
																if($instantiation_detail->projected_duration	!==	'0')
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><span class="label_star"> *</span><b> Duration:</b></label>
																								</td>
																								<td>

																												<p><?php	echo	$instantiation_detail->projected_duration;	?></p>

																								</td>
																				</tr>

																				<?php
																}
																else	if($instantiation_detail->actual_duration	!==	'0')
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><span class="label_star"> *</span><b> Duration:</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	date('H:i:s',	strtotime($instantiation_detail->actual_duration));	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Duration	End		-->
																<!--				Time Start 	Start		-->
																<?php
																if($instantiation_detail->time_start)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b> Time Start:</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	$instantiation_detail->time_start;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Time Start	End		-->
																<!--				File Size 	Start		-->
																<?php
																if($instantiation_detail->file_size)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b> File Size:</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	$instantiation_detail->file_size	.	' '	.	$instantiation_detail->file_size_unit_of_measure;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				File Size	End		-->
																<!--				Standard 	Start		-->
																<?php
																if($instantiation_detail->standard)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b> Standard:</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	$instantiation_detail->standard;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Standard	End		-->
																<!--				Dimensions: 	Start		-->
																<?php
																if($instantiation_detail->instantiation_dimension)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b> Dimensions:</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	$instantiation_detail->instantiation_dimension	.	' '	.	$instantiation_detail->unit_of_measure;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Dimensions	End		-->
																<!--				Data Rate 	Start		-->
																<?php
																if($instantiation_detail->data_rate)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b> Data Rate:</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	$instantiation_detail->data_rate	.	' '	.	$instantiation_detail->data_rate_unit_of_measure;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Data Rate	End		-->
																<!--			 Color 	Start		-->
																<?php
																if($instantiation_detail->color)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b> Color:</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	$instantiation_detail->color;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Color	End		-->
																<!--			 Tracks 	Start		-->
																<?php
																if($instantiation_detail->tracks)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b> Tracks:</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	$instantiation_detail->tracks;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Tracks	End		-->
																<!--			 Channel Configuration 	Start		-->
																<?php
																if($instantiation_detail->channel_configuration)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b> Channel Configuration:</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	$instantiation_detail->channel_configuration;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Channel Configuration	End		-->
																<!--			 Language 	Start		-->
																<?php
																if($instantiation_detail->language)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b> Language:</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	$instantiation_detail->language;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Language	End		-->
																<!--			 Annotation 	Start		-->
																<?php
																if($instantiation_detail->ins_annotation	||	$instantiation_detail->ins_annotation_type)
																{
																				$ins_annotation	=	explode(' | ',	trim(str_replace('(**)',	'',	$instantiation_detail->ins_annotation)));
																				$ins_annotation_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$instantiation_detail->ins_annotation_type)));
																				$combine_annotation	=	'';
																				if((count($ins_annotation)	>	0	&&	$ins_annotation[0]	!=	'')	||	(count($ins_annotation_type)	>	0	&&	$ins_annotation_type[0]	!=	''))
																				{
																								if(count($ins_annotation)	>	count($ins_annotation_type))
																								{
																												foreach($ins_annotation	as	$index	=>	$row)
																												{
																																if(isset($ins_annotation_type[$index])	&&	$ins_annotation_type[$index]	!=	'')
																																{
																																				$combine_annotation.=$ins_annotation_type[$index]	.	': ';
																																}
																																$combine_annotation.=$row;
																																$combine_annotation.='<div class="clearfix"></div>';
																												}
																								}
																								else
																								{
																												foreach($ins_annotation_type	as	$index	=>	$row)
																												{
																																$combine_annotation.=$row	.	': ';
																																if(isset($ins_annotation[$index])	&&	$ins_annotation[$index]	!=	'')
																																{
																																				$combine_annotation.=$ins_annotation[$index];
																																}

																																$combine_annotation.='<div class="clearfix"></div>';
																												}
																								}

																								if(	!	empty($combine_annotation)	&&	trim($combine_annotation)	!=	':')
																								{
																												?>
																												<tr>
																																<td class="record-detail-page">
																																				<label><i class="icon-question-sign"></i><b> Annotation:</b></label>
																																</td>
																																<td>
																																				<p>	<?php	echo	$combine_annotation;	?></p>

																																</td>
																												</tr>
																												<?php
																								}
																				}
																				?>	


																<?php	}	?>
																<!--				Annotation	End		-->
												</table>

								</div>
								<?php
								if(isset($instantiation_events)	&&	!	is_empty($instantiation_events))
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
																				foreach($instantiation_events	as	$events)
																				{
																								?>
																								<tr>
																												<td><?php	echo	(isset($events->event_type)	&&	!	is_empty($events->event_type))	?	$events->event_type	:	'';	?></td>
																												<td><?php	echo	(isset($events->event_date)	&&	!	is_empty($events->event_date))	?	$events->event_date	:	'';	?></td>
																												<td><?php	echo	(isset($events->event_note)	&&	!	is_empty($events->event_note))	?	$events->event_note	:	'';	?></td>
																												<td><?php	echo	(isset($events->event_outcome)	&&	!	is_empty($events->event_outcome))	?	$events->event_outcome	:	'';	?></td>
																								</tr>
																				<?php	}	?>
																</tbody></table>
								<?php	}
								?>
								<?php
								if($instantiation_detail->status)
								{
												?>
												<div class="nomination-container">

																<p><b><?php	echo	$instantiation_detail->status;	?></b></p>
																<p><?php	echo	$instantiation_detail->nomination_reason;	?></p>
																<?php
																if($instantiation_detail->nominated_by	&&	$instantiation_detail->nominated_by	!=	NULL)
																{
																				?>
																				<p><?php	echo	'Nominated by '	.	$instantiation_detail->nominated_by;	?></p>
																				<?php
																}
																if($instantiation_detail->nominated_at	&&	$instantiation_detail->nominated_at	!=	NULL)
																{
																				?>
																				<p><?php	echo	' at '	.	$instantiation_detail->nominated_at;	?></p>
																</div>
																<?php
												}
								}
								?>
				</div>
				<div class="clearfix"></div>
				<div>
								<table cellPadding="8" class="record-detail-table">
												<tr>
																<td class="record-detail-page">
																				<label><i class="icon-question-sign"></i><b> Nomination Status:</b></label>
																</td>
																<td>
																				<p>
																								<select>
																												<?php
																												foreach($nominations	as	$row)
																												{
																																$selected	=	'';
																																if($instantiation_detail->status	==	$row->status)
																																				$selected	=	'selected="selected"'
																																				?>
																																<option id="<?php	echo	$row->status;	?>" <?php	echo	$selected;	?>><?php	echo	$row->status;	?></option>
																												<?php	}
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
																								<textarea style="width: 750px;height: 90px;"><?php	echo	$instantiation_detail->nomination_reason;	?></textarea>
																				</p>

																</td>
												</tr>
												<tr>
																<td class="record-detail-page">
																				<label><i class="icon-question-sign"></i><b> Instantiation ID Source:</b></label>
																</td>
																<td>
																				<p>
																								<?php	$ins_identifier_src	=	trim(str_replace('(**)',	'',	$instantiation_detail->instantiation_source));	?>
																								<input value="<?php	echo	$ins_identifier_src;	?>" style="width: 755px;"/>
																				</p>

																</td>
												</tr>
												<tr>
																<td class="record-detail-page">
																				<label><i class="icon-question-sign"></i><b> Media Type:</b></label>
																</td>
																<td>
																				<p>
																								<?php
																								$media_type	=	explode(' | ',	$instantiation_detail->media_type);
																								$static_types	=	array('Animation',	'Artifact',	'Collection',	'Dataset',	'Event',	'Interactive',	'Moving Image',	'Object',	'Presentation',	'Service',	'Software',	'Sound',	'Static Image',	'Text');
																								?>
																								<select multiple="multiple" id="media_type" name="media_type">
																												<?php
																												foreach($static_types	as	$row)
																												{
																																$selected	=	'';
																																if(in_array($row,	$media_type))
																																				$selected	=	'selected="selected"';
																																?>
																																<option value="<?php	echo	$row;	?>" <?php	echo	$selected;	?>><?php	echo	$row;	?></option>
																												<?php	}
																												?>

																								</select>

																				</p>

																</td>
												</tr>
												<tr>
																<td class="record-detail-page">
																				<label><i class="icon-question-sign"></i><b> Generation:</b></label>
																</td>
																<td>
																				<p>
																								<?php
																								$generations	=	explode(' | ',	$instantiation_detail->generation);
																								$static_gen	=	array('A&B rolls',	'Accounting statements',	'Air print',	'Air track',	'Answer print',	'Autochrome',	'Award',
																								'Backup',	'Budget',	'Caption file',	'Caricature',	'Clip reel',	'Color reversal intermediate (CRI)',	'Composite answer print',
																								'Composite duplicate negative',	'Composite masterpositive',	'Composite negative',	'Composite original negative',	'Composite original positive',	'Composite positive');
																								foreach($generations	as	$type)
																								{
																												?>
																												<select>
																																<?php
																																foreach($static_gen	as	$row)
																																{
																																				$selected	=	'';
																																				if($row	==	$type)
																																								$selected	=	'selected="selected"';
																																				?>
																																				<option value="<?php	echo	$row;	?>" <?php	echo	$selected;	?>><?php	echo	$row;	?></option>
																																<?php	}
																																?>

																												</select>
																								<?php	}	?>
																				</p>

																</td>
												</tr>
												<tr>
																<td class="record-detail-page">
																				<label><i class="icon-question-sign"></i><b> Language:</b></label>
																</td>
																<td>
																				<p>
																								<?php	$ins_identifier_src	=	trim(str_replace('(**)',	'',	$instantiation_detail->instantiation_source));	?>
																								<input value="<?php	echo	$instantiation_detail->language;	?>" style="width: 755px;"/>
																				</p>

																</td>
												</tr>
								</table>
				</div>
</div>
<script type="text/javascript">
$(function(){
        $("#media_type").multiselect(); 
        $(".ui-multiselect-menu").width('400px');
    });
</script>