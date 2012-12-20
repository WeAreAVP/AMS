<?php
if	(!$isAjax)
{
				?>

				<div class="row-fluid">
								<div class="span3" style="background-color: whiteSmoke;">
												<h4 style="margin: 6px 14px;">Assets</h4>
												</b>
												<div style="padding: 8px;background: none repeat scroll 0% 0% rgb(0, 152, 214); " ><a style="color: white;" href="<?php	echo	site_url	('records/index')	?>" >All Assets</a></div>
												<div style="padding: 8px;" > <a href="<?php	echo	"javascript:;";	//echo site_url('records/flagged') 		?>" >Flagged</a></div>
												<?php	$this->load->view	('instantiations/_facet_search');	?>
								</div>
								<div  class="span9" id="data_container">
								<?php	}	?>
								<ul class="nav nav-tabs">
												<li ><a href="javascript:;" style="color:#000;cursor:default;">View type :</a></li>
												<li id="simple_li" <?php	if	($current_tab	==	'simple')
								{
												?>class="active" <?php	}	?>><a href="javascript:;" <?php	if	($current_tab	!=	'simple')
																																									{
																																													?>onClick="change_view('simple')" <?php	}	?> >Simple Table</a></li>
												<li id="full_table_li" <?php	if	($current_tab	==	'full_table')
																{
																				?>class="active" <?php	}	?>><a href="javascript:;" <?php	if	($current_tab	!=	'full_table')
																{
																				?>onClick="change_view('full_table')" <?php	}	?> >Full Table</a></li>
												<li id="thumbnails_li" <?php	if	($current_tab	==	'thumbnails')
								{
												?>class="active" <?php	}	?>><a href="javascript:;" >Thumbnails</a></li>
								</ul><?php
				if	(isset	($records)	&&	($total	>	0))
				{
												?>
												<div style="width: 860px;">
																				<?php
																				if	(isset	($current_tab)	&&	$current_tab	==	'full_table')
																				{
																								$this->load->view	('instantiations/_gear_dropdown');
																				}
																				?>
																<div style="float: right;">
																				<strong><?php	echo	$start;	?> - <?php	echo	$end;	?></strong> of <strong style="margin-right: 10px;"><?php	echo	$total;	?></strong>
				<?php	echo	$this->ajax_pagination->create_links	();	?>
																</div>
												</div><?php	if	(!isset	($current_tab)	||	$current_tab	==	'simple')
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
								foreach	($records	as	$asset)
								{

												$guid_identifier	=	str_replace	("(**)",	"N/A",	$asset->guid_identifier);
												$local_identifier	=	str_replace	("(**)",	"N/A",	$asset->local_identifier);
												$asset_description	=	str_replace	("(**)",	"N/A",	$asset->description);
												$asset_title	=	str_replace	("(**)",	"N/A",	$asset->asset_title);
												?>
																																<tr style="cursor: pointer;">
																																				<td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="unflag"></i></td>
																																				<td><?php
																												if	($guid_identifier)
																																echo	$guid_identifier;
																												else
																																echo	'N/A';
																												?>
																																				</td>
																																				<td><?php
																												if	($local_identifier)
																																echo	$local_identifier;
																												else
																																echo	'N/A';
												?>
																																				</td>
																																				<td>

																																								<a href="<?php	echo	site_url	('records/details/'	.	$asset->id)	?>" ><?php
																																if	($asset_title)
																																				echo	$asset_title;
																																else
																																				echo	'N/A';
												?>
																																								</a>

																																				</td>
																																				<td><?php
																												if	($asset_description)
																												{

																																if	(strlen	($asset_description)	>	160)
																																{
																																				$messages	=	str_split	($asset_description,	160);
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

				if	(isset	($current_tab)	&&	$current_tab	==	'full_table')
				{
								?>
																<br clear="all"/>
																<div style="width: 865px;overflow:hidden;" id="full_table_view" >
																				<table class="table table-bordered" id="listing_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;"  >
																								<thead>
																												<tr >

																																<?php
																																if	(!empty	($this->column_order))
																																{

																																				foreach	($this->column_order	as	$key	=>	$value)
																																				{
																																								$class	=	'';
																																								$type	=	$value['title'];
																																								if	(isset	($type)	&&	!empty	($type))
																																								{
																																												if	($type	==	'flag')
																																												{
																																																?>
																																																<th id="flag"><span style="float:left;" ><i class="icon-flag "></i></span></th><?php
																				}
																				else
																				{

																								if	(!($this->frozen_column	>	$key))
																												$class	=	'drap-drop';
																								if	(in_array	($type,array	("Local_ID",	"Subjects",		"Genre",	"Creator",	"Contributor",	"Publisher",		"Assets_Date",		"Coverage",	"Audience",			"Annotation",		"Rights")))
																								{
																												$width	=	'min-width:100px;';
																								}
																								else	if	($type	==	'Titles'	||	$type	==	'Description'	||	$type	==	"AA_GUID")
																								{
																												$width	=	'min-width:175px;';
																								}
																								echo	'<th id="'	.	$value['title']	.	'"  class="'	.	$class	.	'"><span style="float:left;'	.	$width	.	'">'	.	str_replace	("_",	' ',	$value['title'])	.	'</span></th>';
																				}
																}
												}
								}
																																?>
																												</tr>
																								</thead>
																								<tbody><?php
																				$def_setting	=	$this->config->item	('assets_setting');
																				$def_setting	=	$def_setting['full'];
																				$body	=	'';
																				foreach	($records	as	$asset)
																				{

																								echo	'<tr>';
																								foreach	($this->column_order	as	$row)
																								{
																												$type	=	$row['title'];
																												if	(isset	($type)	&&	!empty	($type))
																												{
																																if	($type	==	'flag')
																																{
																																				echo	'<td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="unflag"></i></td>';
																																}
																																else
																																{
																																				$column='';
																																				if	($type	==	'Titles')
																																				{
																																								$asset_title	=	'';
																																								$asset_title_type	=	'';
																																								$asset_title_source	=	'';
																																								if	(isset	($asset->asset_title)	&&	!empty	($asset->asset_title ))
																																								{
																																												$val	=	trim	(str_replace	('(**)',	'',	$asset->asset_title));
																																												$column	=	'<a href="'	.	site_url	('records/details/'	.	$asset->id)	.	'" >'	.	$val .	'</a><br/>';
																																								}
																																							
																																								if	(isset	($asset->asset_title_type)	&&	!empty	($asset->asset_title_type ))
																																								{
																																												$val	=	trim	(str_replace	('(**)',	'',	$asset->asset_title_type));
																																												$asset_title_type	=	$val;
																																								}
																																								if	(isset	($asset->asset_title_ref)	&&	!empty	($asset->asset_title_ref ) && ! empty($asset_title_type) )
																																								{
																																												$asset_title_type	=	'<a href="'	.$asset->asset_title_ref.	'" >'.$asset_title_type.'</a>';
																																								}
																																								if(!empty($asset_title_type))
																																								{
																																											$column	.=	$asset_title_type.'<br/>';
																																								}
																																								if	(isset	($asset->asset_title_source)	&&	!empty	($asset->asset_title_source ))
																																								{
																																												$val	=	'('.trim	(str_replace	('(**)',	'',	$asset->asset_title_source)).')';
																																													$column	.= 	$val;
																																								}

																																								
																																								
																																								
																																				}
																																				else if ($type == 'AA_GUID')
																																				{
																																								$column = ($value->guid_identifier) ? $value->guid_identifier : '';
																																				}
																																				else if ($type == 'Local_ID')
																																				{
																																								$column = $value->local_identifier;
																																				}
																																				else if ($type == 'Description')
																																				{
																																								if(isset($value->description) && ! empty($value->description) )
																																								{
																																												$des	=	str_replace	("(**)",	"",	$asset->description);
																																												if	(isset	($des)	&&	!empty	($des)	&&	strlen	($des)	>	160)
																																												{
																																																$messages	=	str_split	($des,	160);
																																																$des	=	$messages[0]	.	' ...';
																																												}
																																												$column	=	$des.'<br/>';
																																								}
																																								if(isset($value->description_type) && ! empty($value->description_type) )
																																								{
																																											$column	.= $value->description_type;	
																																								}
																																				}
																																				else	if	($type	==	'Subjects')
																																				{
																																								$asset_subject	=	'';
																																								$asset_subject_source	=	'';
																																								if	(isset	($asset->asset_subject)	&&	!empty	($asset->asset_subject ))
																																								{
																																												$asset_subject	=	trim	(str_replace	('(**)',	'',	$asset->asset_subject));
																																								}
																																								if	(isset	($asset->asset_subject_ref)	&&	!empty	($asset->asset_subject_ref ) && ! empty($asset_subject) )
																																								{
																																												$asset_subject	=	'<a href="'	.$asset->asset_subject_ref.	'" >'.$asset_subject.'</a>';
																																								}
																																								if(!empty($asset_subject))
																																								{
																																											$column	.=	$asset_subject.'<br/>';
																																								}
																																								if	(isset	($asset->asset_subject_source)	&&	!empty	($asset->asset_subject_source ))
																																								{
																																											$val	=	'('.trim	(str_replace	('(**)',	'',	$asset->asset_subject_source)).')';
																																											$column	.= 	$val;
																																								}
																																				}
																																				else	if	($type	==	'Assets_Date')
																																				{
																																								$column	=	($asset->dates	==	0)	?	'No Date'	:	date	('Y-m-d',	$asset->dates);
																																								if	(isset	($asset->date_type)	&&	!empty	($asset->date_type ))
																																								{
																																											$val	=	'('.trim	(str_replace	('(**)',	'',	$asset->date_type)).')';
																																											$column	.= 	'<br/>'.$val;
																																								}
																																				}
//																																				else	if	($type	!=	'Description')
//																																				{
//
//																																								if	(isset	($asset->$def_setting[$row['title']])	&&	!empty	($asset->$def_setting[$row['title']]))
//																																								{
//																																												$val	=	trim	(str_replace	("(**)",	"N/A",	$asset->$def_setting[$row['title']]));
//																																												$column	=	$val;
//																																								}
//																																								else
//																																								{
//																																												$column	=	'N/A';
//																																								}
//																																				}
//																																				else
//																																				{
//																																								$des	=	str_replace	("(**)",	"N/A",	$asset->$def_setting[$row['title']]);
//																																								if	(isset	($des)	&&	!empty	($des)	&&	strlen	($des)	>	160)
//																																								{
//																																												$messages	=	str_split	($des,	160);
//																																												$des	=	$messages[0]	.	' ...';
//																																								}
//																																								$column	=	$des;
//																																				}
																																				if	(in_array	($type,	array	("Local_ID",	"Subjects",		"Genre",	"Creator",	"Contributor",	"Publisher",		"Assets_Date",		"Coverage",	"Audience",			"Annotation",		"Rights")))
																																				{
																																								$width	=	'min-width:100px;';
																																				}
																																				else	if	($type	==	'Titles'	||	$type	==	'Description'	||	$type	==	"AA_GUID")
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
																if	(isset	($current_tab)	&&	$current_tab	==	'thumbnails')
																{
																				?>
																
												<?php	}	?>
												<div style="text-align: right;width: 860px;"> <strong><?php	echo	$start;	?> - <?php	echo	$end;	?></strong> of <strong style="margin-right: 10px;"><?php	echo	$total;	?></strong> <?php	echo	$this->ajax_pagination->create_links	();	?> </div>
												<?php
								}
								else	if	($start	>=	1000)
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
if	(!$isAjax)
{
				?>
								</div>
				</div>
<?php	}	?>

