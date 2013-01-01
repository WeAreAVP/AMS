<div class="row">
				<div style="margin: 2px 0px 10px 0px;float:left;">
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
				<div style="float: right;">
								<button class="btn btn-large"><span class="icon-download-alt"></span>Export Asset</button>
				</div>
				<div class="clearfix"></div>

				<?php	$this->load->view('partials/_list');	?>
    <div class="span12" style="margin-left: 285px;">
								<div style="float: left;">
												<table  cellPadding="8" class="record-detail-table">
																<!--				Organization Start		-->
																<tr>
																				<td class="record-detail-page">
																								<label><i class="icon-question-sign"></i><b>* Organization:</b></label>
																				</td>
																				<td>

																								<p><?php	echo	$asset_details->organization;	?></p>

																				</td>
																</tr>
																<!--				Organization End		-->
																<!--				Asset Type Start		-->
																<?php
																if(isset($asset_details->asset_type)	&&	!	empty($asset_details->asset_type))
																{
																				?>
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i> Asset Type:</label>
																								</td>
																								<td>
																												<?php
																												$asset_types	=	explode(" | ",	$asset_details->asset_type);
																												foreach($asset_types	as	$asset_type)
																												{
																																?>
																																<p><?php	echo	trim($asset_type);	?></p>
																												<?php	}	?>
																								</td>					
																				</tr>	

																<?php	}	?>
																<!--				Asset Type End		-->
																<!--				Asset Title Start		-->
																<?php
																if(isset($combine_title)	&&	!	empty($combine_title))
																{
																				?>
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i>* Title:</label>
																								</td>
																								<td>
																												<?php	echo	$combine_title;	?>
																								</td>
																				</tr>
																<?php	}	?>
																<!--				Asset Title End		-->
																<!--				Asset Description Start		-->
																<?php
																if(isset($asset_details->description)	&&	!	empty($asset_details->description))
																{
																				?>
																				<tr>
																								<td class="record-detail-page">
																												<label><i class="icon-question-sign"></i> Description:</label>
																								</td>
																								<td>
																												<p><?php	echo	$asset_details->description;	?></p>
																								</td>
																				</tr>
																<?php	}	?>
																<!--				Asset Description Start		-->
																<!--				Asset Genre Start		-->
																<?php
																if(isset($asset_genres)	&&	!	empty($asset_genres))
																{
																				foreach($asset_genres	as	$main_genre)
																				{
																								?>

																								<?php
																								$asset_genre	=	explode(' | ',	trim(str_replace('(**)',	'',	$main_genre->genre)));
																								$asset_genre_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$main_genre->genre_ref)));
																								$asset_genre_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$main_genre->genre_source)));
																								$combine_genre	=	'';
																								if(count($asset_genre)	>	0)
																								{
																												?>
																												<tr>
																																<td class="record-detail-page">
																																				<label><i class="icon-question-sign"></i>* Genres:</label>
																																</td>
																																<td>
																																				<?php
																																				foreach($asset_genre	as	$index	=>	$genre)
																																				{

																																								if(isset($asset_genre_ref[$index]))
																																								{
																																												if($asset_genre_ref[$index]	!=	'')
																																												{
																																																$combine_genre.="<a target='_blank' href='$asset_genre_ref[$index]'>$genre</a>";
																																												}
																																												else
																																																$combine_genre.=$genre;
																																								}
																																								else
																																												$combine_genre.=$genre;
																																								if(isset($asset_genre_source[$index])	&&	$asset_genre_source[$index]	!=	'')
																																												$combine_genre.=' ('	.	$asset_genre_source[$index]	.	')';
																																								$combine_genre.='<div class="clearfix"></div>';
																																				}
																																				?>
																																				<p><?php	echo	$combine_genre;	?></p>
																																</td>
																												</tr>
																												<?php
																								}
																								?>
																								<?php
																				}
																}
																?>
																<!--				Asset Genre End		-->
																<!--				Asset Creator Start		-->
																<?php
																if(isset($asset_creators_roles)	&&	!	empty($asset_creators_roles))
																{
																				foreach($asset_creators_roles	as	$creator)
																				{
																								$asset_creator_name	=	explode(' | ',	trim(str_replace('(**)',	'',	$creator->asset_creator_name)));
																								$asset_creator_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$creator->asset_creator_ref)));
																								$asset_creator_affiliation	=	explode(' | ',	trim(str_replace('(**)',	'',	$creator->asset_creator_affiliation)));
																								$asset_creator_role	=	explode(' | ',	trim(str_replace('(**)',	'',	$creator->asset_creator_role)));
																								$asset_creator_role_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$creator->asset_creator_role_ref)));
																								$asset_creator_role_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$creator->asset_creator_role_source)));
																								$combine_creator	=	'';
																								if(count($asset_creator_name)	>	0)
																								{
																												?>
																												<tr>
																																<td class="record-detail-page">
																																				<label><i class="icon-question-sign"></i>* Creator:</label>
																																</td>
																																<td>
																																				<?php
																																				foreach($asset_creator_name	as	$index	=>	$creator_name)
																																				{

																																								if(isset($asset_creator_ref[$index])	&&	!	empty($asset_creator_ref[$index]))
																																								{
																																												$combine_creator.="<a target='_blank' href='$asset_creator_ref[$index]'>$creator_name</a>";
																																								}
																																								else
																																												$column.=$creator_name;
																																								if(isset($asset_creator_affiliation[$index])	&&	$asset_creator_affiliation[$index]	!=	'')
																																												$combine_creator.=','	.	$asset_creator_affiliation[$index];

																																								if(isset($asset_creator_role[$index])	&&	!	empty($asset_creator_role[$index]))
																																								{
																																												if(isset($asset_creator_role_ref[$index])	&&	!	empty($asset_creator_role_ref[$index]))
																																												{
																																																$combine_creator.=",<a target='_blank' href='$asset_contributor_role_ref[$index]'>$asset_creator_role[$index]</a>";
																																												}
																																												else
																																																$combine_creator.=','	.	$asset_creator_role[$index];
																																								}
																																								if(isset($asset_creator_role_source[$index])	&&	$asset_creator_role_source[$index]	!=	'')
																																												$combine_creator.=' ('	.	$asset_creator_role_source[$index]	.	')';
																																								$combine_creator.='<div class="clearfix"></div>';
																																				}
																																}
																																?>
																																<p><?php	echo	$combine_creator;	?></p>
																												</td>
																								</tr>

																								<?php
																				}
																}
																?>
																<!--				Asset Creator End		-->
												</table>

								</div>
				</div>
</div>