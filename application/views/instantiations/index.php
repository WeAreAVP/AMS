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
								<?php
								if(count($records)	>	0)
								{
												?>
												<div style="width: 710px;">
																<?php	$this->load->view('instantiations/_gear_dropdown');	?>
																<div style="float: right;">
																				<strong><?php	echo	number_format($start);	?> - <?php	echo	number_format($end);	?></strong> of <strong style="margin-right: 10px;" id="total_list_count"><?php	echo	number_format($total);	?></strong>
																				<?php	echo	$this->ajax_pagination->create_links();	?>
																</div>
												</div>
												<br clear="all"/>
												<div style="width: 710px;overflow: hidden;" id="instantiation-main">

																<table class="table table-bordered tablesorter" id="listing_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;border-collapse:collapse;">
																				<thead>
																								<tr>
																												<?php
																												foreach($this->column_order	as	$key	=>	$value)
																												{
																																$class	=	'';
																																if(	!	($this->frozen_column	>	$key))
																																				$class	=	'drap-drop';
																																$type	=	$value['title'];

																																if(in_array($type,	array('Organization',	'Nomination',	'Media_Type',	'Generation',	'Format',	'Duration',	'Date',	'File_size',	'Colors',	'Language')))
																																{
																																				$width	=	'width:100px;';
																																}
																																else	if($type	==	'Instantiation_ID')
																																{
																																				$width	=	'width:150px;';
																																}
																																else	if($type	==	'Instantiation\'s_Asset_Title')
																																{
																																				$width	=	'width:200px;';
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
																																								$ins_identifier	=	explode(' | ',	trim(str_replace('(**)',	'',	$value->instantiation_identifier)));
																																								$ins_identifier_src	=	explode(' | ',	trim(str_replace('(**)',	'',	$value->instantiation_source)));
																																								$column	=	'';
																																								foreach($ins_identifier	as	$index	=>	$identifier)
																																								{
																																												$column.=	'<a href="'	.	site_url('instantiations/detail/'	.	$value->id)	.	'">';
																																												$column.=	$identifier;
																																												if(isset($ins_identifier_src[$index])	&&	!	empty($ins_identifier_src[$index]))
																																																$column.=' ('	.	$ins_identifier_src[$index]	.	')';
																																												$column.=	'</a>';
																																												$column.='<div class="clearfix"></div>';
																																								}
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

																																								$column	=	$value->format_type;
																																								if($value->format_name	!=	'')
																																												$column.=': '	.	$value->format_name;
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
																																				if(in_array($type,	array('Organization',	'Nomination',	'Media_Type',	'Generation',	'Format',	'Duration',	'Date',	'File_size',	'Colors',	'Language')))
																																				{
																																								$width	=	'width:100px;';
																																				}
																																				else	if($type	==	'Instantiation_ID')
																																				{
																																								$width	=	'width:150px;';
																																				}
																																				else	if($type	==	'Instantiation\'s_Asset_Title')
																																				{
																																								$width	=	'width:200px;';
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

												<div style="text-align: right;width: 710px;">
																<strong><?php	echo	number_format($start);	?> - <?php	echo	number_format($end);	?></strong> of <strong style="margin-right: 10px;" id="total_record_count"><?php	echo	number_format($total);	?></strong>
																<?php	echo	$this->ajax_pagination->create_links();	?>
												</div>
												<?php
								}
								else
								{
												?>
												<div  style="text-align: center;width: 710px;margin-top: 50px;font-size: 20px;">No instantiation record found.</div>
								<?php	}
								?>

				</div>
				<?php
				if(	!	$isAjax)
				{
								?>
				</div>


				<div id="export_csv_confirm_modal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
												<h3>Limited CSV Export</h3>
								</div>
								<div class="modal-body">
												<p>Are you sure you want to export records? </p>
								</div>
								<div class="modal-footer">
												<button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
												<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="confirm_csv_export();">Yes</button>
								</div>
				</div>
				<div id="export_csv_modal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
												<h3 id="myModalLabel">Limited CSV Export</h3>
								</div>
								<div class="modal-body">
												<p id="export_csv_msg"><img src="/images/ajax-loader.gif" />Please wait...</p>
								</div>
								<div class="modal-footer">
												<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
								</div>
				</div>
				<script type="text/javascript">
								$('#export_csv_modal').on('hidden', function () {
												$('#export_csv_msg').html('<img src="/images/ajax-loader.gif" />Please wait...');
								});
								function confirm_csv_export(){
												$('#export_csv_modal').modal('toggle');
												export_csv_limited();
								}
								function export_csv_limited(){
												$.ajax({
																type: 'POST', 
																url: site_url+'instantiations/export_csv',
																dataType: 'json',
																success: function (result) { 
																				if(result.link=='true')
																								$('#export_csv_msg').html('<a href="'+result.msg+'">Download</a>');
																				else
																								$('#export_csv_msg').html(result.msg);
																																																																				                                        
																}
												});
								}
				</script>

<?php	}	?>