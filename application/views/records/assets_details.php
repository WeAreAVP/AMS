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
								<h2><?php	echo	$combine_title;	?></h2>
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
																								<label><i class="icon-question-sign"></i>* Organization:</label>
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

																								if(count($asset_creator_name)	>	0	&&	$asset_creator_name[0]	!=	'')
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
																																												$combine_creator.=$creator_name;
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
																																				?>
																																				<p><?php	echo	$combine_creator;	?></p>
																																</td>
																												</tr>
																												<?php
																								}
																								?>



																								<?php
																				}
																}
																?>
																<!--				Asset Creator End		-->
																<!--				Asset Contributor Start		-->
																<?php
																if(isset($asset_contributor_roles)	&&	!	empty($asset_contributor_roles))
																{
																				foreach($asset_contributor_roles	as	$contributor)
																				{
																								$asset_contributor_name	=	explode(' | ',	trim(str_replace('(**)',	'',	$contributor->asset_contributor_name)));
																								$asset_contributor_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$contributor->asset_contributor_ref)));
																								$asset_contributor_affiliation	=	explode(' | ',	trim(str_replace('(**)',	'',	$contributor->asset_contributor_affiliation)));
																								$asset_contributor_role	=	explode(' | ',	trim(str_replace('(**)',	'',	$contributor->asset_contributor_role)));
																								$asset_contributor_role_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$contributor->asset_contributor_role_ref)));
																								$asset_contributor_role_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$contributor->asset_contributor_role_source)));
																								$combine_contributor	=	'';
																								if(count($asset_contributor_name)	>	0	&&	$asset_contributor_name[0]	!=	'')
																								{
																												?>
																												<tr>
																																<td class="record-detail-page">
																																				<label><i class="icon-question-sign"></i>* Contributor:</label>
																																</td>
																																<td>
																																				<?php
																																				foreach($asset_contributor_name	as	$index	=>	$contributor_name)
																																				{

																																								if(isset($asset_contributor_ref[$index])	&&	!	empty($asset_contributor_ref[$index]))
																																								{
																																												$combine_contributor.="<a target='_blank' href='$asset_contributor_ref[$index]'>$contributor_name</a>";
																																								}
																																								else
																																												$combine_contributor.=$contributor_name;
																																								if(isset($asset_contributor_affiliation[$index])	&&	$asset_contributor_affiliation[$index]	!=	'')
																																												$combine_contributor.=','	.	$asset_contributor_affiliation[$index];

																																								if(isset($asset_contributor_role[$index])	&&	!	empty($asset_contributor_role[$index]))
																																								{
																																												if(isset($asset_contributor_role_ref[$index])	&&	!	empty($asset_contributor_role_ref[$index]))
																																												{
																																																$combine_contributor.=",<a target='_blank' href='$asset_contributor_role_ref[$index]'>$asset_contributor_role[$index]</a>";
																																												}
																																												else
																																																$combine_contributor.=','	.	$asset_contributor_role[$index];
																																								}
																																								if(isset($asset_contributor_role_source[$index])	&&	$asset_contributor_role_source[$index]	!=	'')
																																												$combine_contributor.=' ('	.	$asset_contributor_role_source[$index]	.	')';
																																								$combine_contributor.='<div class="clearfix"></div>';
																																				}
																																				?>
																																				<p><?php	echo	$combine_contributor;	?></p>
																																</td>
																												</tr>
																												<?php
																								}
																								?>



																								<?php
																				}
																}
																?>
																<!--				Asset Contributor End		-->
																<!--				Asset Publisher Start		-->
																<?php
																if(isset($asset_publishers_roles)	&&	!	empty($asset_publishers_roles))
																{
																				foreach($asset_publishers_roles	as	$publisher)
																				{
																								$asset_publisher_name	=	explode(' | ',	trim(str_replace('(**)',	'',	$publisher->asset_publisher_name)));
																								$asset_publisher_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$publisher->asset_publisher_ref)));
																								$asset_publisher_affiliation	=	explode(' | ',	trim(str_replace('(**)',	'',	$publisher->asset_publisher_affiliation)));
																								$asset_publisher_role	=	explode(' | ',	trim(str_replace('(**)',	'',	$publisher->asset_publisher_role)));
																								$asset_publisher_role_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$publisher->asset_publisher_role_ref)));
																								$asset_publisher_role_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$publisher->asset_publisher_role_source)));
																								$combine_publisher	=	'';
																								if(count($asset_publisher_name)	>	0	&&	$asset_publisher_name[0]	!=	'')
																								{
																												?>
																												<tr>
																																<td class="record-detail-page">
																																				<label><i class="icon-question-sign"></i>* Publisher:</label>
																																</td>
																																<td>
																																				<?php
																																				foreach($asset_publisher_name	as	$index	=>	$publisher_name)
																																				{

																																								if(isset($asset_publisher_ref[$index])	&&	!	empty($asset_publisher_ref[$index]))
																																								{
																																												$combine_publisher.="<a target='_blank' href='$asset_publisher_ref[$index]'>$publisher_name</a>";
																																								}
																																								else
																																												$combine_publisher.=$publisher_name;
																																								if(isset($asset_publisher_affiliation[$index])	&&	$asset_publisher_affiliation[$index]	!=	'')
																																												$combine_publisher.=','	.	$asset_publisher_affiliation[$index];

																																								if(isset($asset_publisher_role[$index])	&&	!	empty($asset_publisher_role[$index]))
																																								{
																																												if(isset($asset_publisher_role_ref[$index])	&&	!	empty($asset_publisher_role_ref[$index]))
																																												{
																																																$combine_publisher.=",<a target='_blank' href='$asset_publisher_role_ref[$index]'>$asset_publisher_role[$index]</a>";
																																												}
																																												else
																																																$combine_publisher.=','	.	$asset_publisher_role[$index];
																																								}
																																								if(isset($asset_publisher_role_source[$index])	&&	$asset_publisher_role_source[$index]	!=	'')
																																												$combine_publisher.=' ('	.	$asset_publisher_affiliation[$index]	.	')';
																																								$combine_publisher.='<div class="clearfix"></div>';
																																				}
																																				?>
																																				<p><?php	echo	$combine_publisher;	?></p>
																																</td>
																												</tr>
																												<?php
																								}
																								?>
																								<?php
																				}
																}
																?>
																<!--				Asset Publisher End		-->
																<!--				Asset Date Start		-->
																<?php
																if(isset($asset_dates)	&&	!	empty($asset_dates))
																{
																				foreach($asset_dates	as	$date)
																				{
																								$date_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$date->asset_date)));
																								$asset_date	=	explode(' | ',	trim(str_replace('(**)',	'',	$date->date_type)));
																								if((isset($asset_date)	&&	$asset_date[0]	!=	'')	||	(isset($date_type)	&&	$date_type[0]	!=	''))
																								{
																												// Need to be done 
																								}
																								?>
																								<?php
																				}
																}
																?>
																<!--				Asset Date End		-->
																<!--				Asset Subject Start		-->
																<?php
																if(isset($asset_subjects)	&&	!	empty($asset_subjects))
																{
																				foreach($asset_subjects	as	$main_subject)
																				{
																								$asset_subject	=	explode(' | ',	trim(str_replace('(**)',	'',	$main_subject->asset_subject)));
																								$asset_subject_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$main_subject->asset_subject_ref)));
																								$asset_subject_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$main_subject->asset_subject_source)));
																								$combine_subject	=	'';
																								if(count($asset_subject)	>	0	&&	$asset_subject[0]	!=	'')
																								{
																												?>
																												<tr>
																																<td class="record-detail-page">
																																				<label><i class="icon-question-sign"></i>* Subject:</label>
																																</td>
																																<td>
																																				<?php
																																				foreach($asset_subject	as	$index	=>	$subject)
																																				{

																																								if(isset($asset_subject_ref[$index]))
																																								{
																																												if($asset_subject_ref[$index]	!=	'')
																																												{
																																																$combine_subject.="<a target='_blank' href='$asset_subject_ref[$index]'><b>$subject</b></a>";
																																												}
																																												else
																																																$combine_subject.='<b>'	.	$subject	.	'</b>';
																																								}
																																								else
																																												$combine_subject.=$subject;
																																								if(isset($asset_subject_source[$index])	&&	$asset_subject_source[$index]	!=	'')
																																												$combine_subject.=' ('	.	$asset_subject_source[$index]	.	')';
																																								$combine_subject.='<div class="clearfix"></div>';
																																				}
																																				?>
																																				<p><?php	echo	$combine_subject;	?></p>
																																</td>
																												</tr>
																												<?php
																								}
																								?>
																								<?php
																				}
																}
																?>
																<!--				Asset Subject End		-->
																<!--				Coverage Start		-->
																<?php
																if(isset($asset_coverages)	&&	!	empty($asset_coverages))
																{
																				foreach($asset_coverages	as	$coverage)
																				{
																								$asset_coverage	=	explode(' | ',	trim(str_replace('(**)',	'',	$coverage->coverage)));
																								$asset_coverage_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$coverage->coverage_type)));
																								$combine_coverage	=	'';
																								if(count($asset_coverage)	&&	$asset_coverage[0]	!=	'')
																								{
																												foreach($asset_coverage	as	$index	=>	$row)
																												{
																																if(isset($asset_coverage_type[$index]))
																																{
																																				$combine_coverage.=$asset_coverage_type[$index]	.	': ';
																																}
																																$combine_coverage.=$row;
																																$combine_coverage.='<div class="clearfix"></div>';
																												}
																												?>
																												<tr>
																																<td class="record-detail-page">
																																				<label><i class="icon-question-sign"></i>* Coverage:</label>
																																</td>
																																<td>
																																				<?php	echo	$combine_coverage;	?>
																																</td>
																												</tr>

																								<?php	}
																								?>
																								<?php
																				}
																}
																?>
																<!--				Coverage End		-->
																<!--				Rights Start		-->
																<?php
																if(isset($rights_summaries)	&&	!	empty($rights_summaries))
																{
																				foreach($rights_summaries	as	$right_summary)
																				{
																								$rights	=	explode(' | ',	trim(str_replace('(**)',	'',	$right_summary->rights)));
																								$right_link	=	explode(' | ',	trim(str_replace('(**)',	'',	$right_summary->rights_link)));
																								$combine_right	=	'';
																								if(count($rights)	>	0	&&	$rights[0]	!=	'')
																								{
																												foreach($rights	as	$index	=>	$right)
																												{
																																if(isset($right_link[$index])	&&	$right_link[$index]	!=	'')
																																{
																																				$combine_right.="<a href='$right_link[$index]'>$right</a>";
																																}
																																else
																																{
																																				$combine_right.=$right;
																																}
																																$combine_right.='<div class="clearfix"></div>';
																												}
																												?>
																												<tr>
																																<td class="record-detail-page">
																																				<label><i class="icon-question-sign"></i> Rights:</label>
																																</td>
																																<td>
																																				<?php	echo	$combine_right;	?>
																																</td>
																												</tr>
																												<?php
																								}
																								?>
																								<?php
																				}
																}
																?>
																<!--				Rights End		-->
												</table>

								</div>
				</div>
</div>