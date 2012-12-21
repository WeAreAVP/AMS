<?php
if(	!	$isAjax)
{
				?>

				<div class="row-fluid">
								<div class="span3" style="background-color: whiteSmoke;">
												<h4 style="margin: 6px 14px;">Assets</h4>
												</b>
												<div style="padding: 8px;background: none repeat scroll 0% 0% rgb(0, 152, 214); " ><a style="color: white;" href="<?php	echo	site_url('records/index')	?>" >All Assets</a></div>
												<div style="padding: 8px;" > <a href="<?php	echo	"javascript:;";	//echo site_url('records/flagged') 												?>" >Flagged</a></div>
												<?php	$this->load->view('instantiations/_facet_search');	?>
								</div>
								<div  class="span9" id="data_container">
								<?php	}	?>
								<ul class="nav nav-tabs">
												<li ><a href="javascript:;" style="color:#000;cursor:default;">View type :</a></li>
												<li id="simple_li" <?php
								if($current_tab	==	'simple')
								{
												?>class="active" <?php	}	?>><a href="javascript:;" <?php
																if($current_tab	!=	'simple')
																{
												?>onClick="change_view('simple')" <?php	}	?> >Simple Table</a></li>
												<li id="full_table_li" <?php
																																									if($current_tab	==	'full_table')
																																									{
												?>class="active" <?php	}	?>><a href="javascript:;" <?php
																if($current_tab	!=	'full_table')
																{
												?>onClick="change_view('full_table')" <?php	}	?> >Full Table</a></li>
												<li id="thumbnails_li" <?php
																																									if($current_tab	==	'thumbnails')
																																									{
												?>class="active" <?php	}	?>><a href="javascript:;" >Thumbnails</a></li>
								</ul><?php
																if(isset($records)	&&	($total	>	0))
																{
												?>
												<div style="width: 860px;">
																<?php
																if(isset($current_tab)	&&	$current_tab	==	'full_table')
																{
																				$this->load->view('instantiations/_gear_dropdown');
																}
																?>
																<div style="float: right;">
																				<strong><?php	echo	$start;	?> - <?php	echo	$end;	?></strong> of <strong style="margin-right: 10px;"><?php	echo	$total;	?></strong>
																				<?php	echo	$this->ajax_pagination->create_links();	?>
																</div>
												</div><?php
																if(	!	isset($current_tab)	||	$current_tab	==	'simple')
																{
																								?>
																<div style="width:865px;overflow:hidden;" id="simple_view">
																				<table class="table table-bordered" id="assets_table" >
																								<thead>
																												<tr>
																																<th style='width: 14px;'><span style="float:left;" ><i class="icon-flag "></i></span></th>
																																<th style='width: 150px;'><span style="float:left;min-width: 100px;" >AA GUID</span></th>
																																<th style='width: 110px;'><span style="float:left;min-width: 100px;" >Local ID</span></th>
																																<th style='width: 185px;'><span style="float:left;min-width: 175px;" >Titles</span></th>
																																<th style='width: 175px;'><span style="float:left;min-width: 175px;" >Description</span></th>
																												</tr>
																								</thead>
																								<tbody><?php
								foreach($records	as	$asset)
								{

												$guid_identifier	=	str_replace("(**)",	"N/A",	$asset->guid_identifier);
												$local_identifier	=	str_replace("(**)",	"N/A",	$asset->local_identifier);
												$asset_description	=	str_replace("(**)",	"N/A",	$asset->description);
												$asset_title	=	str_replace("(**)",	"N/A",	$asset->asset_title);
																												?>
																																<tr style="cursor: pointer;">
																																				<td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="unflag"></i></td>
																																				<td><?php
																				if($guid_identifier)
																								echo	$guid_identifier;
																				else
																								echo	'N/A';
																												?>
																																				</td>
																																				<td><?php
																												if($local_identifier)
																																echo	$local_identifier;
																												else
																																echo	'N/A';
																												?>
																																				</td>
																																				<td>

																																								<a href="<?php	echo	site_url('records/details/'	.	$asset->id)	?>" ><?php
																												if($asset_title)
																																echo	$asset_title;
																												else
																																echo	'N/A';
																												?>
																																								</a>

																																				</td>
																																				<td><?php
																																if($asset_description)
																																{

																																				if(strlen($asset_description)	>	160)
																																				{
																																								$messages	=	str_split($asset_description,	160);
																																								echo	$messages[0]	.	' ...';
																																				}
																																				else
																																				{
																																								echo	$asset_description;
																																				}
																																}
																																else
																																				echo	'N/A';
																												?>
																																				</td>
																																</tr><?php	}
																								?>
																								</tbody>
																								<script> setTimeout(function (){updateSimpleDataTable();},500);</script>

																				</table>
																</div><?php
																}

																if(isset($current_tab)	&&	$current_tab	==	'full_table')
																{
																								?>
																<br clear="all"/>
																<div style="width: 865px;overflow:hidden;" id="full_table_view" >
																				<table class="table table-bordered" id="listing_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;"  >
																								<thead>
																												<tr >

																																<?php
																																if(	!	empty($this->column_order))
																																{

																																				foreach($this->column_order	as	$key	=>	$value)
																																				{
																																								$class	=	'';
																																								$type	=	$value['title'];
																																								if(isset($type)	&&	!	empty($type))
																																								{
																																												if($type	==	'flag')
																																												{
																																																?>
																																																<th id="flag"><span style="float:left;" ><i class="icon-flag "></i></span></th>
																																																<?php
																																												}
																																												else
																																												{

																																																if(	!	($this->frozen_column	>	$key))
																																																				$class	=	'drap-drop';
																																																if(in_array($type,	array("Local_ID",	"Subjects",	"Genre",	"Creator",	"Contributor",	"Publisher",	"Assets_Date",	"Coverage",	"Audience",	"Annotation",	"Rights")))
																																																{
																																																				$width	=	'min-width:100px;';
																																																}
																																																else	if($type	==	'Titles'	||	$type	==	'Description'	||	$type	==	"AA_GUID")
																																																{
																																																				$width	=	'min-width:175px;';
																																																}
																																																echo	'<th id="'	.	$value['title']	.	'"  class="'	.	$class	.	'"><span style="float:left;'	.	$width	.	'">'	.	str_replace("_",	' ',	$value['title'])	.	'</span></th>';
																																												}
																																								}
																																				}
																																}
																																?>
																												</tr>
																								</thead>
																								<tbody>
																												<?php
																												$def_setting	=	$this->config->item('assets_setting');
																												$def_setting	=	$def_setting['full'];
																												$body	=	'';
																												foreach($records	as	$asset)
																												{

																																echo	'<tr>';
																																foreach($this->column_order	as	$row)
																																{
																																				$type	=	$row['title'];
																																				if(isset($type)	&&	!	empty($type))
																																				{
																																								if($type	==	'flag')
																																								{
																																												echo	'<td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="unflag"></i></td>';
																																								}
																																								else
																																								{
																																												$column	=	'';
																																												if($type	==	'Titles')
																																												{
																																																$asset_title_type	=	trim(str_replace('(**)',	'',	$asset->asset_title_type));
																																																$asset_title_type	=	explode(' | ',	$asset_title_type);
																																																$asset_title	=	trim(str_replace('(**)',	'',	$asset->asset_title));
																																																$asset_title	=	explode(' | ',	$asset_title);
																																																$asset_title_ref	=	trim(str_replace('(**)',	'',	$asset->asset_title_ref));
																																																$asset_title_ref	=	explode(' | ',	$asset_title_ref);
																																																$column	=	'';
																																																foreach($asset_title	as	$index	=>	$title)
																																																{
																																																				if(isset($asset_title_type[$index])	&&	$asset_title_type[$index]	!=	'')
																																																								$column.=	$asset_title_type[$index]	.	': ';
																																																				if(isset($asset_title_ref[$index]))
																																																				{
																																																								if($asset_title_ref[$index]	!=	'')
																																																								{
																																																												$column.="<a target='_blank' href='$asset_title_ref[$index]'>$title</a>: ";
																																																												$column.=' ('	.	$asset_title_ref[$index]	.	')';
																																																								}
																																																								else
																																																												$column.=$title;
																																																				}
																																																				else
																																																								$column.=$title;
																																																				$column.='<div class="clearfix"></div>';
																																																}
																																												}
																																												else	if($type	==	'AA_GUID')
																																												{
																																																$column	=	($asset->guid_identifier)	?	$asset->guid_identifier	:	'';
																																												}
																																												else	if($type	==	'Local_ID')
																																												{
																																																$column	=	$asset->local_identifier;
																																												}
																																												else	if($type	==	'Description')
																																												{
																																																if(isset($asset->description)	&&	!	empty($asset->description))
																																																{
																																																				$des	=	str_replace("(**)",	"",	$asset->description);
																																																				if(isset($des)	&&	!	empty($des)	&&	strlen($des)	>	160)
																																																				{
																																																								$messages	=	str_split($des,	160);
																																																								$des	=	$messages[0]	.	' ...';
																																																				}
																																																				$column	=	$des	.	'<br/>';
																																																}
																																																if(isset($asset->description_type)	&&	!	empty($asset->description_type))
																																																{
																																																				$column	.=	$asset->description_type;
																																																}
																																												}
																																												else	if($type	==	'Subjects')
																																												{




																																																$asset_subject	=	trim(str_replace('(**)',	'',	$asset->asset_subject));
																																																$asset_subject	=	explode(' | ',	$asset_subject);
																																																$asset_subject_ref	=	trim(str_replace('(**)',	'',	$asset->asset_subject_ref));
																																																$asset_subject_ref	=	explode(' | ',	$asset_subject_ref);
																																																$asset_subject_source	=	trim(str_replace('(**)',	'',	$asset->asset_subject_source));
																																																$asset_subject_source	=	explode(' | ',	$asset_subject_source);
																																																$column	=	'';
																																																if(count($asset_subject)>0)
																																																{
																																																				foreach($asset_subject	as	$index	=>	$subject)
																																																				{

																																																								if(isset($asset_subject_ref[$index]))
																																																								{
																																																												if($asset_subject_ref[$index]	!=	'')
																																																												{
																																																																$column.="<a target='_blank' href='$asset_subject_ref[$index]'>$subject</a>: ";
																																																												}
																																																												else
																																																																$column.=$subject;
																																																								}
																																																								else
																																																												$column.=$subject;
																																																								if(isset($asset_subject_source[$index])	&&	$asset_subject_source[$index]	!=	'')
																																																												$column.=$asset_subject_source[$index];
																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Genre')
																																												{
																																																$asset_genre	=	'';
																																																if(isset($asset->asset_genre)	&&	!	empty($asset->asset_genre))
																																																{
																																																				$asset_genre	=	trim(str_replace('(**)',	'',	$asset->asset_genre));
																																																}
																																																if(isset($asset->asset_genre_ref)	&&	!	empty($asset->asset_genre_ref)	&&	!	empty($asset_genre))
																																																{
																																																				$asset_genre	=	'<a href="'	.	$asset->asset_subject_ref	.	'" >'	.	$asset_genre	.	'</a>';
																																																}
																																																if(	!	empty($asset_genre))
																																																{
																																																				$column	.=	$asset_genre	.	'<br/>';
																																																}
																																																if(isset($asset->asset_genre_source)	&&	!	empty($asset->asset_genre_source))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_genre_source));
																																																				$column	.=	$val;
																																																}
																																												}
																																												else	if($type	==	'Assets_Date')
																																												{
																																																$column	=	($asset->dates	==	0)	?	'No Date'	:	date('Y-m-d',	$asset->dates);
																																																if(isset($asset->date_type)	&&	!	empty($asset->date_type))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->date_type));
																																																				$column	.=	'<br/>'	.	$val;
																																																}
																																												}
																																												else	if($type	==	'Creator')
																																												{

																																																$asset_creator_name	=	'';
																																																if(isset($asset->asset_creator_name)	&&	!	empty($asset->asset_creator_name))
																																																{
																																																				$asset_creator_name	=	trim(str_replace('(**)',	'',	$asset->asset_creator_name));
																																																}
																																																if(isset($asset->asset_creator_ref)	&&	!	empty($asset->asset_creator_ref)	&&	!	empty($asset_creator_name))
																																																{
																																																				$asset_creator_name	=	'<a href="'	.	$asset->asset_creator_ref	.	'" >'	.	$asset_creator_name	.	'</a>';
																																																}
																																																if(	!	empty($asset_creator_name))
																																																{
																																																				$column	.=	$asset_creator_name	.	'<br/>';
																																																}
																																																if(isset($asset->asset_creator_affiliation)	&&	!	empty($asset->asset_creator_affiliation))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_creator_affiliation));
																																																				$column	.=	$val	.	'<br/>';
																																																}
																																																if(isset($asset->asset_creator_source)	&&	!	empty($asset->asset_creator_source))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_creator_source));
																																																				$column	.=	$val	.	'<br/>';
																																																}
																																																$asset_creator_role	=	'';
																																																if(isset($asset->asset_creator_role)	&&	!	empty($asset->asset_creator_role))
																																																{
																																																				$asset_creator_role	=	trim(str_replace('(**)',	'',	$asset->asset_creator_role));
																																																}
																																																if(isset($asset->asset_creator_role_ref)	&&	!	empty($asset->asset_creator_role_ref)	&&	!	empty($asset_creator_role))
																																																{
																																																				$asset_creator_role	=	'<a href="'	.	$asset->asset_creator_ref	.	'" >'	.	$asset_creator_role	.	'</a>';
																																																}
																																																if(	!	empty($asset_creator_role))
																																																{
																																																				$column	.=	$asset_creator_role	.	'<br/>';
																																																}
																																																if(isset($asset->asset_creator_role_source)	&&	!	empty($asset->asset_creator_role_source))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_creator_role_source));
																																																				$column	.=	$val	.	'<br/>';
																																																}
																																												}
																																												else	if($type	==	'Contributor')
																																												{

																																																$asset_contributor_name	=	'';
																																																if(isset($asset->asset_contributor_name)	&&	!	empty($asset->asset_contributor_name))
																																																{
																																																				$asset_contributor_name	=	trim(str_replace('(**)',	'',	$asset->asset_contributor_name));
																																																}
																																																if(isset($asset->asset_contributor_ref)	&&	!	empty($asset->asset_contributor_ref)	&&	!	empty($asset_creator_name))
																																																{
																																																				$asset_contributor_name	=	'<a href="'	.	$asset->asset_contributor_ref	.	'" >'	.	$asset_contributor_name	.	'</a>';
																																																}
																																																if(	!	empty($asset_creator))
																																																{
																																																				$column	.=	$asset_contributor_name	.	'<br/>';
																																																}
																																																if(isset($asset->asset_contributor_affiliation)	&&	!	empty($asset->asset_contributor_affiliation))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_contributor_affiliation));
																																																				$column	.=	$val	.	'<br/>';
																																																}
																																																if(isset($asset->asset_contributor_source)	&&	!	empty($asset->asset_contributor_source))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_contributor_source));
																																																				$column	.=	$val	.	'<br/>';
																																																}
																																																$asset_contributor_role	=	'';
																																																if(isset($asset->asset_contributor_role)	&&	!	empty($asset->asset_contributor_role))
																																																{
																																																				$asset_contributor_role	=	trim(str_replace('(**)',	'',	$asset->asset_contributor_role));
																																																}
																																																if(isset($asset->asset_contributor_role_ref)	&&	!	empty($asset->asset_contributor_role_ref)	&&	!	empty($asset_contributor_role))
																																																{
																																																				$asset_contributor_role	=	'<a href="'	.	$asset->asset_contributor_role_ref	.	'" >'	.	$asset_contributor_role	.	'</a>';
																																																}
																																																if(	!	empty($asset_contributor_role))
																																																{
																																																				$column	.=	$asset_contributor_role	.	'<br/>';
																																																}
																																																if(isset($asset->asset_contributor_role_source)	&&	!	empty($asset->asset_contributor_role_source))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_contributor_role_source));
																																																				$column	.=	$val	.	'<br/>';
																																																}
																																												}
																																												else	if($type	==	'Publisher')
																																												{
																																																$asset_publisher_name	=	'';
																																																if(isset($asset->asset_publisher_name)	&&	!	empty($asset->asset_publisher_name))
																																																{
																																																				$asset_publisher_name	=	trim(str_replace('(**)',	'',	$asset->asset_publisher_name));
																																																}
																																																if(isset($asset->asset_publisher_ref)	&&	!	empty($asset->asset_publisher_ref)	&&	!	empty($asset_publisher_name))
																																																{
																																																				$asset_publisher_name	=	'<a href="'	.	$asset->asset_publisher_ref	.	'" >'	.	$asset_publisher_name	.	'</a>';
																																																}
																																																if(	!	empty($asset_publisher_name))
																																																{
																																																				$column	.=	$asset_publisher_name	.	'<br/>';
																																																}
																																																if(isset($asset->asset_publisher_affiliation)	&&	!	empty($asset->asset_publisher_affiliation))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_publisher_affiliation));
																																																				$column	.=	$val	.	'<br/>';
																																																}
																																																$asset_publisher_role	=	'';
																																																if(isset($asset->asset_publisher_role)	&&	!	empty($asset->asset_publisher_role))
																																																{
																																																				$asset_publisher_role	=	trim(str_replace('(**)',	'',	$asset->asset_publisher_role));
																																																}
																																																if(isset($asset->asset_publisher_role_ref)	&&	!	empty($asset->asset_publisher_role_ref)	&&	!	empty($asset_publisher_role))
																																																{
																																																				$asset_publisher_role	=	'<a href="'	.	$asset->asset_publisher_role_ref	.	'" >'	.	$asset_publisher_role	.	'</a>';
																																																}
																																																if(	!	empty($asset_publisher_role))
																																																{
																																																				$column	.=	$asset_publisher_role	.	'<br/>';
																																																}
																																																if(isset($asset->asset_publisher_role_source)	&&	!	empty($asset->asset_publisher_role_source))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_publisher_role_source));
																																																				$column	.=	$val	.	'<br/>';
																																																}
																																												}
																																												else	if($type	==	'Coverage')
																																												{
																																																if(isset($asset->asset_coverage)	&&	!	empty($asset->asset_coverage))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_coverage));
																																																				$column	.=	$val	.	'<br/>';
																																																}
																																																if(isset($asset->asset_coverage_type)	&&	!	empty($asset->asset_coverage_type))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_coverage_type));
																																																				$column	.=	$val;
																																																}
																																												}
																																												else	if($type	==	'Audience_Level')
																																												{
																																																$asset_audience_level	=	'';
																																																if(isset($asset->asset_audience_level)	&&	!	empty($asset->asset_audience_level))
																																																{
																																																				$asset_audience_level	=	trim(str_replace('(**)',	'',	$asset->asset_audience_level));
																																																}
																																																if(isset($asset->asset_audience_level_ref)	&&	!	empty($asset->asset_audience_level_ref)	&&	!	empty($asset_audience_level))
																																																{
																																																				$asset_audience_level	=	'<a href="'	.	$asset->asset_audience_level_ref	.	'" >'	.	$asset_audience_level	.	'</a>';
																																																}
																																																if(	!	empty($asset_audience_level))
																																																{
																																																				$column	.=	$asset_audience_level	.	'<br/>';
																																																}
																																																if(isset($asset->asset_audience_level_source)	&&	!	empty($asset->asset_audience_level_source))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_audience_level_source));
																																																				$column	.=	$val;
																																																}
																																												}
																																												else	if($type	==	'Audience_Rating')
																																												{
																																																$asset_audience_rating	=	'';
																																																if(isset($asset->asset_audience_rating)	&&	!	empty($asset->asset_audience_rating))
																																																{
																																																				$asset_audience_rating	=	trim(str_replace('(**)',	'',	$asset->asset_audience_rating));
																																																}
																																																if(isset($asset->asset_audience_rating_ref)	&&	!	empty($asset->asset_audience_rating_ref)	&&	!	empty($asset_audience_rating))
																																																{
																																																				$asset_audience_rating	=	'<a href="'	.	$asset->asset_audience_rating_ref	.	'" >'	.	$asset_audience_rating	.	'</a>';
																																																}
																																																if(	!	empty($asset_audience_rating))
																																																{
																																																				$column	.=	$asset_audience_rating	.	'<br/>';
																																																}
																																																if(isset($asset->asset_audience_rating_source)	&&	!	empty($asset->asset_audience_rating_source))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_audience_rating_source));
																																																				$column	.=	$val;
																																																}
																																												}
																																												else	if($type	==	'Annotation')
																																												{
																																																$asset_annotation	=	'';
																																																if(isset($asset->asset_annotation)	&&	!	empty($asset->asset_annotation))
																																																{
																																																				$asset_annotation	=	trim(str_replace('(**)',	'',	$asset->asset_annotation));
																																																}
																																																if(isset($asset->asset_annotation_ref)	&&	!	empty($asset->asset_annotation_ref)	&&	!	empty($asset_annotation))
																																																{
																																																				$asset_annotation	=	'<a href="'	.	$asset->asset_annotation_ref	.	'" >'	.	$asset_annotation	.	'</a>';
																																																}
																																																if(	!	empty($asset_annotation))
																																																{
																																																				$column	.=	$asset_annotation	.	'<br/>';
																																																}
																																																if(isset($asset->asset_annotation_type)	&&	!	empty($asset->asset_annotation_type))
																																																{
																																																				$val	=	trim(str_replace('(**)',	'',	$asset->asset_annotation_type));
																																																				$column	.=	$val;
																																																}
																																												}
																																												else	if($type	==	'Rights')
																																												{
																																																$asset_rights	=	'';
																																																if(isset($asset->asset_rights)	&&	!	empty($asset->asset_rights))
																																																{
																																																				$asset_rights	=	trim(str_replace('(**)',	'',	$asset->asset_rights));
																																																}
																																																if(isset($asset->asset_rights_link)	&&	!	empty($asset->asset_rights_link)	&&	!	empty($asset_rights))
																																																{
																																																				$asset_rights	=	'<a href="'	.	trim(str_replace('(**)',	'',	$asset->asset_rights_link))	.	'" >'	.	$asset_rights	.	'</a>';
																																																}
																																																if(	!	empty($asset_rights))
																																																{
																																																				$column	.=	$asset_rights	.	'<br/>';
																																																}
																																												}
																																												if(in_array($type,	array("Local_ID",	"Subjects",	"Genre",	"Creator",	"Contributor",	"Publisher",	"Assets_Date",	"Coverage",	"Audience",	"Annotation",	"Rights")))
																																												{
																																																$width	=	'min-width:100px;';
																																												}
																																												else	if($type	==	'Titles'	||	$type	==	'Description'	||	$type	==	"AA_GUID")
																																												{
																																																$width	=	'min-width:175px;';
																																												}
																																												echo	'<td><span style="float:left;'	.	$width	.	'">'	.	$column	.	'</span></td>';
																																								}
																																				}
																																}
																																echo	'</tr>';
																												}
																												?>
																								</tbody>
																				</table>
																</div><?php
																}
																if(isset($current_tab)	&&	$current_tab	==	'thumbnails')
																{
																												?>

												<?php	}	?>
												<div style="text-align: right;width: 860px;"> <strong><?php	echo	$start;	?> - <?php	echo	$end;	?></strong> of <strong style="margin-right: 10px;"><?php	echo	$total;	?></strong> <?php	echo	$this->ajax_pagination->create_links();	?> </div>
												<?php
								}
								else	if($start	>=	1000)
								{
												?>
												<div  style="text-align: center;width: 860px;margin-top: 50px;font-size: 20px;">Please refine your search</div><?php
				}
				else
				{
												?>
												<div  style="text-align: center;width: 860px;margin-top: 50px;font-size: 20px;"> No Assets Found</div><?php	}
								?>
								<?php
								if(	!	$isAjax)
								{
												?>
								</div>
				</div>
<?php	}	?>

