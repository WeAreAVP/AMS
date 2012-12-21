<?php
if(	!	$isAjax)
{
				?>
				<div class="row-fluid">
								<div class="span3">
												<?php	$this->load->view('instantiations/_facet_search');	?>
								</div>
								<div  class="span9" id="data_container">
								<?php	}	?>
								<?php
								if(count($records)	>	0)
								{
												?>
												<div style="width: 860px;">
																<?php	$this->load->view('instantiations/_gear_dropdown');	?>
																<div style="float: right;">
																				<strong><?php	echo	$start;	?> - <?php	echo	$end;	?></strong> of <strong style="margin-right: 10px;"><?php	echo	$total;	?></strong>
																				<?php	echo	$this->ajax_pagination->create_links();	?>
																</div>
												</div>
												<br clear="all"/>
												<div style="width: 865px;overflow: hidden;" id="instantiation-main">

																<table class="table table-bordered" id="listing_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;width: 860px;">
																				<thead>
																								<tr>
																												<?php
																												foreach($this->column_order	as	$key	=>	$value)
																												{
																																$class	=	'';
																																if(	!	($this->frozen_column	>	$key))
																																				$class	=	'drap-drop';
																																$type	=	$value['title'];

																																if(in_array($type,	array('Organization',	'Instantiation_ID',	'Nomination',	'Media_Type',	'Generation',	'Format',	'Duration',	'Date',	'File_size',	'Colors',	'Language')))
																																{
																																				$width	=	'min-width:100px;';
																																}
																																else	if($type	==	'Instantiation\'s_Asset_Title')
																																{
																																				$width	=	'min-width:200px;';
																																}
																																echo	'<th id="'	.	$value['title']	.	'" class="'	.	$class	.	'"><span style="float:left;'	.	$width	.	'">'	.	str_replace("_",	' ',	$value['title'])	.	'</span></th>';
																												}
																												?>
																								</tr>
																				</thead>
																				<tbody>
																								<?php
																								foreach($records	as	$key	=>	$value)
																								{
																												?>
																												<tr>
																																<?php
																																foreach($this->column_order	as	$key	=>	$row)
																																{
																																				$type	=	$row['title'];
																																				if($type	==	'Organization')
																																				{
																																								$column	=	$value->organization;
																																				}
																																				else	if($type	==	'Instantiation_ID')
																																				{
																																								$column	=	$value->instantiation_identifier	.	' ('	.	$value->instantiation_source	.	')';
																																				}
																																				else	if($type	==	'Nomination')
																																				{
																																								$column	=	($value->status)	?	$value->status	:	'';
																																				}
																																				else	if($type	==	'Instantiation\'s_Asset_Title')
																																				{

																																								$asset_title_type	=	trim(str_replace('(**)',	'',	$value->asset_title_type));
																																								$asset_title_type	=	explode(' | ',	$asset_title_type);
																																								$asset_title	=	trim(str_replace('(**)',	'',	$value->asset_title));
																																								$asset_title	=	explode(' | ',	$asset_title);
																																								$asset_title_ref	=	trim(str_replace('(**)',	'',	$value->asset_title_ref));
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
//																																												$column.=	'<a href="'	.	site_url('instantiations/detail/'	.	$value->id)	.	'">'	.	$title	.	'</a>';


																																												$column.='<div class="clearfix"></div>';
																																								}
																																				}
																																				else	if($type	==	'Media_Type')
																																				{
																																								$column	=	$value->media_type;
																																				}
																																				else	if($type	==	'Generation')
																																				{
																																								$column	=	$value->generation;
																																				}
																																				else	if($type	==	'Format')
																																				{
																																								$column	=	$value->format_name	.	' '	.	$value->format_type;
																																				}
																																				else	if($type	==	'Duration')
																																				{
																																								$column	=	($value->actual_duration)	?	date('H:i:s',	strtotime($value->actual_duration))	:	date('H:i:s',	strtotime($value->projected_duration));
																																				}
																																				else	if($type	==	'Date')
																																				{
																																								$column	=	($value->dates	==	0)	?	''	:	date('Y-m-d',	$value->dates)	.	' '	.	$value->date_type;
																																				}
																																				else	if($type	==	'File_size')
																																				{
																																								$column	=	($value->file_size	==	0)	?	''	:	$value->file_size	.	' '	.	($value->file_size_unit_of_measure)	?	$value->file_size_unit_of_measure	:	'';
																																				}
																																				else	if($type	==	'Colors')
																																				{
																																								$column	=	($value->color)	?	$value->color	:	'';
																																				}
																																				else	if($type	==	'Language')
																																				{
																																								$column	=	($value->language)	?	$value->language	:	'';
																																				}
																																				if(in_array($type,	array('Organization',	'Instantiation_ID',	'Nomination',	'Media_Type',	'Generation',	'Format',	'Duration',	'Date',	'File_size',	'Colors',	'Language')))
																																				{
																																								$width	=	'min-width:100px;';
																																				}
																																				else	if($type	==	'Asset_Title')
																																				{
																																								$width	=	'min-width:200px;';
																																				}
																																				echo	'<td><span style="float:left;'	.	$width	.	'">'	.	$column	.	'</span></td>';
																																}
																																?>
																												</tr>
																												<?php
																								}
																								?>
																				</tbody>

																</table>

												</div>

												<div style="text-align: right;width: 860px;">
																<strong><?php	echo	$start;	?> - <?php	echo	$end;	?></strong> of <strong style="margin-right: 10px;" id="total_record_count"><?php	echo	$total;	?></strong>
																<?php	echo	$this->ajax_pagination->create_links();	?>
												</div>
												<?php
								}
								else
								{
												?>
												<div  style="text-align: center;width: 860px;margin-top: 50px;font-size: 20px;">No instantiation record found.</div>
								<?php	}
								?>
								<?php
								if(	!	$isAjax)
								{
												?>
								</div>
				</div>

<?php	}	?>