<?php
if(	!	$isAjax)
{
				?>
				<div class="row-fluid">
				<?php	}	?>
				<div class="span3">

								<?php	$this->load->view('instantiations/_facet_search');	?>
				</div>

				<div  class="span9" id="data_container">


								<?php	$this->load->view('layouts/_records_nav');	?>
								<ul class="nav nav-tabs records-nav-sub">

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
												<li id="flagged_li" <?php
																if($current_tab	==	'flagged')
																{
												?>class="active" <?php	}	?>><a href="javascript:;" >Flagged</a></li>
								</ul>
								<?php
								if(isset($records)	&&	($total	>	0))
								{
												?>
												<div style="width: 710px;">
																<?php
																if(isset($current_tab)	&&	$current_tab	==	'full_table')
																{
																				$this->load->view('instantiations/_gear_dropdown');
																}
																?>
																<div style="float: right;">
																				<strong><?php	echo	number_format($start);	?> - <?php	echo	number_format($end);	?></strong> of <strong style="margin-right: 10px;" id="total_list_count"><?php	echo	number_format($total);	?></strong>
																				<?php	echo	$this->ajax_pagination->create_links();	?>
																</div>
												</div>
								<div class="clearfix"></div>
												<?php
												if(	!	isset($current_tab)	||	$current_tab	==	'simple')
												{
																?>
																<div style="" id="simple_view">
																				<table class="table table-bordered" id="assets_table" style="border-collapse:collapse;">
																								<thead>
																												<tr style="background: rgb(235, 235, 235);">
																																<td><i class="icon-flag "></i></td>
																																<th><span style="margin-right: 10px;">Organization</span></th>
																																<th>AA GUID</th>
																																<th>Local ID</th>
																																<th>Titles</th>
																																<th>Description</th>
																												</tr>
																								</thead>
																								<tbody>
																											
																								</tbody>


																				</table>
																</div><?php
																}

																if(isset($current_tab)	&&	$current_tab	==	'full_table')
																{
																												?>
																<br clear="all"/>
																<div style="width: 710px;overflow:hidden;" id="full_table_view">
																				<table class="table table-bordered" id="listing_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;border-collapse:collapse;"  >
																								<thead>
																												<tr>

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
																																																if(in_array($type,	array("Organization",	"Local_ID",	"Subjects",	"Genre",	"Creator",	"Publisher",	"Assets_Date",	"Coverage",	"Audience_Level",	"Annotation",	"Rights")))
																																																{
																																																				$width	=	'width:100px;';
																																																}
																																																else	if($type	==	"Contributor")
																																																{
																																																				$width	=	'width:125px;';
																																																}
																																																else	if($type	==	'Titles'	||	$type	==	'Description'	||	$type	==	"AA_GUID"	||	$type	==	'Audience_Rating')
																																																{
																																																				$width	=	'width:175px;';
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

																																echo	'<tr id="tr_'	.	$asset->id	.	'">';
																																foreach($this->column_order	as	$row)
																																{
																																				$type	=	$row['title'];
																																				$column	=	'';
																																				if(isset($type)	&&	!	empty($type))
																																				{
																																								if($type	==	'flag')
																																								{
																																												echo	'<td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="unflag"></i></td>';
																																								}
																																								else
																																								{
																																												$column	=	'';
																																												if($type	==	'Organization')
																																												{
																																																$column	=	$asset->organization;
																																												}
																																												if($type	==	'Titles')
																																												{
																																																$asset_title_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_title_type)));
																																																$asset_title	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_title)));
																																																$asset_title_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_title_ref)));
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
																																																$column	=	($asset->guid_identifier)	?	'<a href="'	.	site_url('records/details/'	.	$asset->id)	.	'" >'	.	$asset->guid_identifier	:	'';
																																												}
																																												else	if($type	==	'Local_ID')
																																												{
																																																$column	=	$asset->local_identifier;
																																												}
																																												else	if($type	==	'Description')
																																												{
																																																if(isset($asset->description)	&&	!	empty($asset->description))
																																																{
																																																				$asset_description	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->description)));
																																																				$description_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->description_type)));
																																																				$column	=	'';
																																																				if(count($asset_description)	>	0)
																																																				{
																																																								foreach($asset_description	as	$index	=>	$description)
																																																								{
																																																												if(isset($description)	&&	!	empty($description))
																																																												{
																																																																if(isset($description_type[$index])	&&	$description_type[$index]	!=	'')
																																																																				$column.='Type:'	.	$description_type[$index]	.	'<br/>';
																																																																if(strlen($description)	>	160)
																																																																{
																																																																				$messages	=	str_split($description,	160);
																																																																				$column.=	$messages[0]	.	' ...';
																																																																}
																																																																else
																																																																				$column.=$description;
																																																												}
																																																												$column	.=	'<div class="clearfix"></div>';
																																																								}
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Subjects')
																																												{

																																																$asset_subject	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_subject)));
																																																$asset_subject_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_subject_ref)));
																																																$asset_subject_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_subject_source)));
																																																$column	=	'';
																																																if(count($asset_subject)	>	0)
																																																{
																																																				foreach($asset_subject	as	$index	=>	$subject)
																																																				{

																																																								if(isset($asset_subject_ref[$index]))
																																																								{
																																																												if($asset_subject_ref[$index]	!=	'')
																																																												{
																																																																$column.="<a target='_blank' href='$asset_subject_ref[$index]'>$subject</a>";
																																																												}
																																																												else
																																																																$column.=$subject;
																																																								}
																																																								else
																																																												$column.=$subject;
																																																								if(isset($asset_subject_source[$index])	&&	$asset_subject_source[$index]	!=	'')
																																																												$column.=' ('	.	$asset_subject_source[$index]	.	')';
																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Genre')
																																												{

																																																$asset_genre	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_genre)));
																																																$asset_genre_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_genre_ref)));
																																																$asset_genre_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_genre_source)));
																																																$column	=	'';
																																																if(count($asset_genre)	>	0)
																																																{
																																																				foreach($asset_genre	as	$index	=>	$genre)
																																																				{

																																																								if(isset($asset_genre_ref[$index]))
																																																								{
																																																												if($asset_genre_ref[$index]	!=	'')
																																																												{
																																																																$column.="<a target='_blank' href='$asset_genre_ref[$index]'>$genre</a>";
																																																												}
																																																												else
																																																																$column.=$genre;
																																																								}
																																																								else
																																																												$column.=$genre;
																																																								if(isset($asset_genre_source[$index])	&&	$asset_genre_source[$index]	!=	'')
																																																												$column.=' ('	.	$asset_genre_source[$index]	.	')';
																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Assets_Date')
																																												{

																																																$asset_dates	=	explode(' | ',	$asset->dates);
																																																$asset_dates_types	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->date_type)));
																																																if(count($asset_dates)	>	0)
																																																{
																																																				foreach($asset_dates	as	$index	=>	$dates)
																																																				{

																																																								if(isset($asset_dates_types[$index])	&&	$dates	>	0)
																																																								{
																																																												$column	.=	$asset_dates_types[$index]	.	': '	.	date('Y-m-d',	$dates);
																																																								}
																																																								else	if($dates	>	0)
																																																								{
																																																												$column.=date('Y-m-d',	$dates);
																																																								}
																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Creator')
																																												{
																																																$asset_creator_name	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_name)));
																																																$asset_creator_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_ref)));
																																																$asset_creator_affiliation	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_affiliation)));
																																																$asset_creator_role	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_role)));
																																																$asset_creator_role_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_role_ref)));
																																																$asset_creator_role_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_role_source)));
																																																$column	=	'';
																																																if(count($asset_creator_name)	>	0)
																																																{
																																																				foreach($asset_creator_name	as	$index	=>	$creator_name)
																																																				{

																																																								if(isset($asset_creator_ref[$index])	&&	!	empty($asset_creator_ref[$index]))
																																																								{
																																																												$column.="<a target='_blank' href='$asset_creator_ref[$index]'>$creator_name</a>";
																																																								}
																																																								else
																																																												$column.=$creator_name;
																																																								if(isset($asset_creator_affiliation[$index])	&&	$asset_creator_affiliation[$index]	!=	'')
																																																												$column.=','	.	$asset_creator_affiliation[$index];

																																																								if(isset($asset_creator_role[$index])	&&	!	empty($asset_creator_role[$index]))
																																																								{
																																																												if(isset($asset_creator_role_ref[$index])	&&	!	empty($asset_creator_role_ref[$index]))
																																																												{
																																																																$column.=",<a target='_blank' href='$asset_contributor_role_ref[$index]'>$asset_creator_role[$index]</a>";
																																																												}
																																																												else
																																																																$column.=','	.	$asset_creator_role[$index];
																																																								}
																																																								if(isset($asset_creator_role_source[$index])	&&	$asset_creator_role_source[$index]	!=	'')
																																																												$column.=' ('	.	$asset_creator_role_source[$index]	.	')';
																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Contributor')
																																												{
																																																$asset_contributor_name	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_name)));
																																																$asset_contributor_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_ref)));
																																																$asset_contributor_affiliation	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_affiliation)));
																																																$asset_contributor_role	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_role)));
																																																$asset_contributor_role_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_role_ref)));
																																																$asset_contributor_role_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_role_source)));
																																																$column	=	'';
																																																if(count($asset_contributor_name)	>	0)
																																																{
																																																				foreach($asset_contributor_name	as	$index	=>	$contributor_name)
																																																				{

																																																								if(isset($asset_contributor_ref[$index])	&&	!	empty($asset_contributor_ref[$index]))
																																																								{
																																																												$column.="<a target='_blank' href='$asset_contributor_ref[$index]'>$contributor_name</a>";
																																																								}
																																																								else
																																																												$column.=$contributor_name;
																																																								if(isset($asset_contributor_affiliation[$index])	&&	$asset_contributor_affiliation[$index]	!=	'')
																																																												$column.=','	.	$asset_contributor_affiliation[$index];

																																																								if(isset($asset_contributor_role[$index])	&&	!	empty($asset_contributor_role[$index]))
																																																								{
																																																												if(isset($asset_contributor_role_ref[$index])	&&	!	empty($asset_contributor_role_ref[$index]))
																																																												{
																																																																$column.=",<a target='_blank' href='$asset_contributor_role_ref[$index]'>$asset_contributor_role[$index]</a>";
																																																												}
																																																												else
																																																																$column.=','	.	$asset_contributor_role[$index];
																																																								}
																																																								if(isset($asset_contributor_role_source[$index])	&&	$asset_contributor_role_source[$index]	!=	'')
																																																												$column.=' ('	.	$asset_contributor_role_source[$index]	.	')';
																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Publisher')
																																												{
																																																$asset_publisher_name	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_name)));
																																																$asset_publisher_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_ref)));
																																																$asset_publisher_affiliation	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_affiliation)));
																																																$asset_publisher_role	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_role)));
																																																$asset_publisher_role_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_role_ref)));
																																																$asset_publisher_role_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_role_source)));
																																																$column	=	'';
																																																if(count($asset_publisher_name)	>	0)
																																																{
																																																				foreach($asset_publisher_name	as	$index	=>	$publisher_name)
																																																				{

																																																								if(isset($asset_publisher_ref[$index])	&&	!	empty($asset_publisher_ref[$index]))
																																																								{
																																																												$column.="<a target='_blank' href='$asset_publisher_ref[$index]'>$publisher_name</a>";
																																																								}
																																																								else
																																																												$column.=$publisher_name;
																																																								if(isset($asset_publisher_affiliation[$index])	&&	$asset_publisher_affiliation[$index]	!=	'')
																																																												$column.=','	.	$asset_publisher_affiliation[$index];

																																																								if(isset($asset_publisher_role[$index])	&&	!	empty($asset_publisher_role[$index]))
																																																								{
																																																												if(isset($asset_publisher_role_ref[$index])	&&	!	empty($asset_publisher_role_ref[$index]))
																																																												{
																																																																$column.=",<a target='_blank' href='$asset_publisher_role_ref[$index]'>$asset_publisher_role[$index]</a>";
																																																												}
																																																												else
																																																																$column.=','	.	$asset_publisher_role[$index];
																																																								}
																																																								if(isset($asset_publisher_role_source[$index])	&&	$asset_publisher_role_source[$index]	!=	'')
																																																												$column.=' ('	.	$asset_publisher_affiliation[$index]	.	')';
																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Coverage')
																																												{
																																																$asset_coverage	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_coverage)));
																																																$asset_coverage_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_coverage_type)));
																																																$column	=	'';
																																																if(count($asset_coverage)	>	0)
																																																{
																																																				foreach($asset_coverage	as	$index	=>	$coverage)
																																																				{
																																																								if(isset($asset_coverage_type[$index])	&&	!	empty($asset_coverage_type[$index]))
																																																								{
																																																												$column.=	$asset_coverage_type[$index]	.	':';
																																																								}
																																																								if(isset($coverage)	&&	!	empty($coverage))
																																																								{
																																																												$column.=	$coverage;
																																																								}
																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Audience_Level')
																																												{
																																																$asset_audience_level	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_level)));
																																																$asset_audience_level_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_level_ref)));
																																																$asset_audience_level_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_level_source)));
																																																$column	=	'';
																																																if(count($asset_audience_level)	>	0)
																																																{
																																																				foreach($asset_audience_level	as	$index	=>	$audience_level)
																																																				{

																																																								if(isset($asset_audience_level_ref[$index])	&&	!	empty($asset_audience_level_ref[$index]))
																																																								{
																																																												$column.="<a target='_blank' href='$asset_audience_level_ref[$index]'>$audience_level</a>";
																																																								}
																																																								else
																																																												$column.=$audience_level;
																																																								if(isset($asset_audience_level_source[$index])	&&	$asset_audience_level_source[$index]	!=	'')
																																																												$column.=' ('	.	$asset_audience_level_source[$index]	.	')';
																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Audience_Rating')
																																												{
																																																$asset_audience_rating	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_rating)));
																																																$asset_audience_rating_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_rating_ref)));
																																																$asset_audience_rating_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_rating_source)));
																																																$column	=	'';
																																																if(count($asset_audience_rating)	>	0)
																																																{
																																																				foreach($asset_audience_rating	as	$index	=>	$audience_rating)
																																																				{

																																																								if(isset($asset_audience_rating_ref[$index])	&&	!	empty($asset_audience_rating_ref[$index]))
																																																								{
																																																												$column.="<a target='_blank' href='$asset_audience_level_ref[$index]'>$audience_rating</a>";
																																																								}
																																																								else
																																																												$column.=$audience_rating;
																																																								if(isset($asset_audience_rating_source[$index])	&&	$asset_audience_rating_source[$index]	!=	'')
																																																												$column.=' ('	.	$asset_audience_rating_source[$index]	.	')';
																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Annotation')
																																												{
																																																$asset_annotation	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_annotation)));
																																																$asset_annotation_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_annotation_ref)));
																																																$asset_annotation_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_annotation_type)));
																																																$column	=	'';
																																																if(count($asset_annotation)	>	0)
																																																{
																																																				foreach($asset_annotation	as	$index	=>	$annotation)
																																																				{
																																																								if(isset($asset_annotation_type[$index])	&&	$asset_annotation_type[$index]	!=	'')
																																																												$column.=$asset_annotation_type[$index]	.	': ';
																																																								if(isset($asset_annotation_ref[$index])	&&	!	empty($asset_annotation_ref[$index]))
																																																								{
																																																												$column.="<a target='_blank' href='$asset_annotation_ref[$index]'>$annotation</a>: ";
																																																								}
																																																								else
																																																												$column.=$annotation;

																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												else	if($type	==	'Rights')
																																												{
																																																$asset_rights	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_rights)));
																																																$asset_rights_link	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_rights_link)));
																																																$column	=	'';
																																																if(count($asset_rights)	>	0)
																																																{
																																																				foreach($asset_rights	as	$index	=>	$rights)
																																																				{

																																																								if(isset($asset_rights_link[$index])	&&	!	empty($asset_rights_link[$index]))
																																																								{
																																																												$column.="<a target='_blank' href='"	.	$asset_rights_link[$index]	.	"'>"	.	$rights	.	"</a>: ";
																																																								}
																																																								else
																																																												$column.=$rights;
																																																								$column.='<div class="clearfix"></div>';
																																																				}
																																																}
																																												}
																																												if(in_array($type,	array("Organization",	"Local_ID",	"Subjects",	"Genre",	"Creator",	"Publisher",	"Assets_Date",	"Coverage",	"Audience_Level",	"Annotation",	"Rights")))
																																												{
																																																$width	=	'width:100px;';
																																												}
																																												else	if($type	==	"Contributor")
																																												{
																																																$width	=	'width:125px;';
																																												}
																																												else	if($type	==	'Titles'	||	$type	==	'Description'	||	$type	==	"AA_GUID"	||	$type	==	'Audience_Rating')
																																												{
																																																$width	=	'width:175px;';
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
												<div style="text-align: right;width: 710px;"> <strong><?php	echo	number_format($start);	?> - <?php	echo	number_format($end);	?></strong> of <strong style="margin-right: 10px;"><?php	echo	number_format($total);	?></strong> <?php	echo	$this->ajax_pagination->create_links();	?> </div>
												<?php
								}
								else	if($start	>=	1000)
								{
												?>
												<div  style="text-align: center;width: 710px;margin-top: 50px;font-size: 20px;">Please refine your search</div><?php
				}
				else
				{
												?>
												<div  style="text-align: center;width: 710px;margin-top: 50px;font-size: 20px;"> No Assets Found</div><?php	}
								?>

				</div>
				<?php
				if(	!	$isAjax)
				{
								?>
				</div>


<?php	}	?>
