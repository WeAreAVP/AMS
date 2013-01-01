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
																				debug($asset_genres);
																				?>

																				<?php
																				$asset_genre	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset_genres->genre)));
																				$asset_genre_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset_genres->genre_ref)));
																				$asset_genre_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset_genres->genre_source)));
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
																<?php	}	?>
																<!--				Asset Genre End		-->
												</table>

								</div>
				</div>
</div>