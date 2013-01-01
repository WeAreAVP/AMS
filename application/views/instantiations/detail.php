<div class="row">
				<div style="margin: 2px 0px 10px 0px;">

								<?php
								$asset_title_type	=	trim(str_replace('(**)',	'',	$asset_details->title_type));
								$asset_title_type	=	explode(' | ',	$asset_title_type);
								$asset_title	=	trim(str_replace('(**)',	'',	$asset_details->title));
								$asset_title	=	explode(' | ',	$asset_title);
								$asset_title_ref	=	trim(str_replace('(**)',	'',	$asset_details->title_ref));
								$asset_title_ref	=	explode(' | ',	$asset_title_ref);
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
								<h2>Instantiation Details: <?php	echo	$combine_title;	?></h2>
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
																								$combine_identifier.=	$identifier;
																								if(isset($ins_identifier_src[$index])	&&	!	empty($ins_identifier_src[$index]))
																												$combine_identifier.=' ('	.	$ins_identifier_src[$index]	.	')';
																								$combine_identifier.='<div class="clearfix"></div>';
																				}
																				?>
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b>* Instantiation ID:</b></label>
																								</td>
																								<td>
																												
																												<p><?php echo $combine_identifier; ?></p>
																												
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
																												<label><i class="icon-question-sign"></i><b>  Date:</b></label>
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
																																				<span><?php	echo	date('Y-m-d',	$instantiation_detail->dates);	?></span>

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
																												<label><i class="icon-question-sign"></i><b>* Media Type:</b></label>
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
																												<label><i class="icon-question-sign"></i><b> Generation</b></label>
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
																<!--				Generation 	Start		-->
																<?php
																if($instantiation_detail->location)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b>* Location</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	$instantiation_detail->location;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Generation	End		-->
																<!--				Duration 	Start		-->
																<?php
																if($instantiation_detail->projected_duration	>	0)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b>* Duration</b></label>
																								</td>
																								<td>

																												<p><?php	echo	$instantiation_detail->projected_duration;	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Duration	End		-->
																<!--				Start Time 	Start		-->
																<?php
																if($instantiation_detail->actual_duration	>	0)
																{
																				?>	
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i><b>* Duration</b></label>
																								</td>
																								<td>
																												<p>	<?php	echo	date('H:i:s',	strtotime($instantiation_detail->actual_duration));	?></p>

																								</td>
																				</tr>

																<?php	}	?>
																<!--				Start Time	End		-->
												</table>
								</div>
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

</div>



