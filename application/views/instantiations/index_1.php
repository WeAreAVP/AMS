
												<?php
								}
								if(isset($asset_coverages)	&&	!	empty($asset_coverages))
								{
												?>
												<div class="span12 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i> Coveragee:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span8"><?php
								foreach($asset_coverages	as	$asset_coverage)
								{
																?>
																								<div class="disabled-field"><?php
																if(isset($asset_coverage->coverage))
																{
																				?>
																																<strong>Coverage:</strong><br/><?php	echo	$asset_coverage->coverage;	?><br/><br/>
																																<?php
																												}
																												if(isset($asset_coverage->coverage_type))
																												{
																																?>

																																<strong>Coverage Type:</strong><br/>
																																<?php	echo	$asset_coverage->coverage_type;	?><br/><br/><?php	}
																												?>   
																								</div><?php	}
																								?>
																</div>
												</div>
												<?php
								}
								if(isset($rights_summaries)	&&	!	empty($rights_summaries))
								{
												?>
												<div class="span12 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i> Rights Summaries:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span8"><?php
								foreach($rights_summaries	as	$rights_summarie)
								{
																?>
																								<div class="disabled-field"><?php
																if(isset($rights_summarie->rights))
																{
																				?>

																																<strong>Rights:</strong><br/><?php	echo	$rights_summarie->rights;	?><br/><br/>
																																<?php
																												}
																												if(isset($rights_summarie->rights_link))
																												{
																																?>
																																<strong>Rights Link:</strong><br/>
																																<?php	echo	$rights_summarie->rights_link;	?><br/><br/><?php	}
																												?>   
																								</div><?php	}
																								?>
																</div>
												</div><?php
												}
												if((isset($asset_audience_level)	&&	!	empty($asset_audience_level))	||	(isset($asset_audience_rating)	&&	!	empty($asset_audience_rating)))
												{
																								?>
												<div class="span12 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i> Audience:</label>
																</div>
																<div id="search_bar" class="span8"><?php
								if(isset($asset_audience_level)	&&	!	empty($asset_audience_level))
								{
												foreach($asset_audience_levels	as	$asset_audience_level)
												{
																																?>
																												<div class="disabled-field"><?php
																if(isset($asset_audience_level->audience_level)	&&	!	empty($asset_audience_level->pubaudience_levellisher))
																{
																																				?>
																																				<strong>Audience Level:</strong><br/><?php	echo	$asset_audience_level->audience_level;	?><br/><br/><?php
																}
																if(isset($asset_audience_level->audience_level_source)	&&	!	empty($asset_audience_level->audience_level_source))
																{
																																				?>
																																				<strong>Audience Level Source:</strong><br/>
																																				<?php
																																				echo	$asset_audience_level->audience_level_source;
																																}
																																if(isset($asset_audience_level->audience_level_ref)	&&	!	empty($asset_audience_level->audience_level_ref))
																																{
																																				?>
																																				<strong>Audience Level Ref:</strong><br/>
																																				<?php
																																				echo	$asset_audience_level->audience_level_ref;
																																}
																																?>
																												</div><?php
																}
												}
												if(isset($asset_audience_rating)	&&	!	empty($asset_audience_rating))
												{
																foreach($asset_audience_rating	as	$asset_audience_rating)
																{
																																?>

																												<div class="disabled-field"><?php
																if(isset($asset_audience_rating->audience_rating)	&&	!	empty($asset_audience_rating->audience_rating))
																{
																																				?>
																																				<strong>Audience Rating:</strong><br/>
																																				<?php	echo	$asset_audience_rating->audience_rating;	?><br/><br/><?php
																}
																if(isset($asset_audience_rating->audience_rating_source)	&&	!	empty($asset_audience_rating->audience_rating_source))
																{
																																				?>
																																				<strong>Audience Rating Source:</strong><br/><?php
																				echo	$asset_audience_rating->audience_rating_source;
																}
																if(isset($asset_audience_rating->audience_rating_ref)	&&	!	empty($asset_audience_rating->audience_rating_ref))
																{
																																				?>
																																				<strong>Audience Rating Ref:</strong><br/><?php
																				echo	$asset_audience_rating->audience_rating_ref;
																}
																																?>
																												</div><?php
																}
												}
																								?>
																</div>
												</div><?php
												}
												if(isset($annotations)	&&	!	empty($annotations))
												{
																								?>
												<div class="span12 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i> Annotation:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span8"><?php
								foreach($annotations	as	$annotation)
								{
																												?>
																								<div class="disabled-field"><?php
																if(isset($rights_summarie->rights))
																{
																																?>

																																<strong>Rights:</strong><br/><?php	echo	$rights_summarie->rights;	?><br/><br/>
																																<?php
																												}
																												if(isset($rights_summarie->rights_link))
																												{
																																?>
																																<strong>Rights Link:</strong><br/>
																																<?php	echo	$rights_summarie->rights_link;	?><br/><br/><?php	}
																												?>   
																								</div><?php	}
																								?>
																</div>
												</div><?php	}
																				?>











								<?php
								if(
																(isset($asset_details->local_identifier)	&&	!	empty($asset_details->local_identifier))	||
																(isset($asset_details->local_identifier_source)	&&	!	empty($asset_details->local_identifier_source))	||
																(isset($asset_details->local_identifier_ref)	&&	!	empty($asset_details->local_identifier_ref))
								)
								{
												?>
												<div class="span12 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i>* Local ID:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span8">
																				<div class="disabled-field">
																								<?php
																								if(isset($asset_details->local_identifier)	&&	!	empty($asset_details->local_identifier))
																								{
																												?>
																												<strong>LOCAL ID:</strong><br/>
																												<?php	echo	$asset_details->local_identifier	?><br/>
																												<br/>
																								<?php	}	?>
																								<?php
																								if(isset($asset_details->local_identifier_source)	&&	!	empty($asset_details->local_identifier_source))
																								{
																												?>
																												<strong>LOCAL ID REF:</strong> <br/>
																												<?php	echo	$asset_details->local_identifier_source;	?><br/>
																												<br/>
																								<?php	}	?>
																								<?php
																								if(isset($asset_details->local_identifier_ref)	&&	!	empty($asset_details->local_identifier_ref))
																								{
																												?>
																												<strong>LOCAL SOURCE:</strong><br/>
																												<?php	echo	$asset_details->local_identifier_ref;	?>
																								<?php	}	?>
																				</div>
																</div>
												</div>
								<?php	}	?>
								<br clear="all">
								<?php
								if(isset($asset_details->guid_identifier)	&&	!	empty($asset_details->guid_identifier))
								{
												?>
												<div class="my-navbar span11">
																<div>Organiztion</div>
												</div>
												<div class="span12 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i>American Archive GUID:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span8">
																				<div class="disabled-field"> <?php	echo	$asset_details->guid_identifier	?><!--end of btn_group--> 
																				</div>
																				<!--end of disabled-field--> 
																</div>
																<!--end of span9--> 
												</div>
								<?php	}	?>
				