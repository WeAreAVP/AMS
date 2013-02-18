

<form name="form_search" id="form_search" method="post" onsubmit="return false;">



				<div id="search_bar_val" class="facet-search"> 
								<h5 class="filter_title" id="filter_criteria" style="display: none;">FILTER CRITERIA&nbsp;<span id="filter_record_count"></span></h5>
								<div id="tokens">

												<!-- Checked Token Start  -->
												<div id="checked_token">
																<?php
																if(isset($this->session->userdata['digitized'])	&&	$this->session->userdata['digitized']	===	'1')
																{
																				?>
																				<div class="btn-img" id="digitized_token" ><span class="search_keys">Digitized</span><i class="icon-remove-sign" style="float: right;" onclick="remove_checked_token('digitized')"></i></div>
																				<?php
																}
																if(isset($this->session->userdata['migration_failed'])	&&	$this->session->userdata['migration_failed']	===	'1')
																{
																				?>
																				<div class="btn-img" id="migration_failed_token" ><span class="search_keys">Migration Failed</span><i class="icon-remove-sign" style="float: right;" onclick="remove_checked_token('migration_failed')"></i></div>
																<?php	}
																?>
												</div>

												<!-- Checked Token End  -->

												<!-- Custom  Search Display End  -->
												<?php
												if(isset($this->session->userdata['custom_search'])	&&	$this->session->userdata['custom_search']	!=	'')
												{

																$custom_search	=	$this->session->userdata['custom_search'];

																$column_name	=	explode('@',	$custom_search);
																if(count($column_name)	>	1)
																{
																				$column_name	=	implode('',	$column_name);
																				$column_name	=	explode('|||',	$column_name);
																				$column_name	=	': '	.	$get_column_name[trim($column_name[0])];
																}

																else
																				$column_name	=	': All';

																$custom_search	=	explode('|||',	$custom_search);
																$custom_value	=	null;
																foreach($custom_search	as	$value)
																{
																				if(	!	empty($value)	&&	!	is_null($value)	&&	trim($value)	!=	'')
																				{
																								$custom_value	=	$value;
																				}
																}

																$search_id	=	name_slug($custom_value);
																?>
																<div id="keyword_field_main">
																				<div class="filter-fileds"><b id="keyword_field_name">Keyword<?php	echo	$column_name;	?></b></div>
																				<div class="btn-img" id="<?php	echo	$search_id;	?>" ><span class="search_keys"><?php	echo	$custom_value;	?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php	echo	htmlentities($custom_value);	?>','<?php	echo	$search_id;	?>','keyword_field_main');"></i></div>
																				<input type="hidden" id="keyword_field_main_search" name="keyword_field_main_search" value="<?php	echo	$custom_value;	?>" />
																				<div class="clearfix"></div>

																</div>
																<div class="clearfix"></div>
				<!--																<div><input type="reset"  id="reset_search" name="reset_search" value="Reset" style="margin-left:10px;" class="btn" onclick="resetKeyword();"/></div>-->
																<?php
												}
												else
												{
																?>

																<div id="keyword_field_main" style="display: none;">
																				<div class="filter-fileds"><b id="keyword_field_name">Keyword</b></div>
																				<input type="hidden" id="keyword_field_main_search" name="keyword_field_main_search" value="" />
																</div>
												<?php	}	?>
												<div class="clearfix"></div>
<!--												<div><input type="reset"  id="reset_search" name="reset_search" value="Reset" class="btn" onclick="resetKeyword();" style="display: none;margin-left:10px;"/></div>-->

												<!-- Custom  Search Display End  -->

												<!-- Date Search Display Start  -->
												<?php
												if(isset($this->session->userdata['date_range'])	&&	$this->session->userdata['date_range']	!=	'')
												{
																$date	=	$this->session->userdata['date_range'];
																$search_id	=	name_slug($date);
																if(isset($this->session->userdata['date_type'])	&&	$this->session->userdata['date_type']	!=	'')
																{
																				$type	=	$this->session->userdata['date_type'];
																}
																else
																{
																				$type	=	'All';
																}
																?>
																<div id = "date_field_main">
																				<input id="date_type" name="date_type" value="<?php	echo	$this->session->userdata['date_type'];	?>" type="hidden"/>
																				<div class = "filter-fileds"><b id = "date_field_name">Date Keyword: <?php	echo	$type;	?></b></div>
																				<div class="btn-img" id="<?php	echo	$search_id;	?>" ><span class="search_keys"><?php	echo	$date;	?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php	echo	htmlentities($date);	?>','<?php	echo	$search_id;	?>','date_field_main');"></i></div>
																				<input type="hidden" id="date_field_main_search" name="date_field_main_search" value="<?php	echo	$date;	?>" />

																				<div class="clearfix"></div>
																</div>
																<div class="clearfix"></div>
				<!--																<div><input type="reset"  id="reset_date_search" name="reset_date_search" value="Reset" style="margin: 5px 10px;" class="btn" onclick="resetKeyword('date');"/></div>-->
																<?php
												}
												else
												{
																?>
																<div id="date_field_main" style="display: none;">
																				<input id="date_type" name="date_type" value="" type="hidden"/>
																				<div class="filter-fileds"><b id="date_field_name">Keyword</b></div>
																				<input type="hidden" id="date_field_main_search" name="date_field_main_search" value="" />

																</div>
																<div class="clearfix"></div>
				<!--																<div><input type="reset"  id="reset_date_search" name="reset_date_search" value="Reset"  style="margin: 5px 10px;display:none;" class="btn" onclick="resetKeyword('date');"/></div>-->
												<?php	}
												?>

												<!-- Date Search Display End  -->
												<div class="clearfix"></div>
												<!-- Organization Search Display Start  -->

												<?php
												if(isset($this->session->userdata['organization'])	&&	$this->session->userdata['organization']	!=	'')
												{

																$organization	=	$this->session->userdata['organization'];
																$organization_array	=	explode('|||',	$organization);
																?>
																<div id="organization_main">
																				<div class="filter-fileds"><b>Organization</b></div>
																				<input type="hidden" id="organization_main_search" name="organization_main_search" value="<?php	echo	$organization;	?>" />
																				<?php
																				foreach($organization_array	as	$value)
																				{
																								$search_id	=	name_slug($value);
																								?>
																								<div class="btn-img" id="<?php	echo	$search_id	?>" ><span class="search_keys"><?php	echo	$value;	?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php	echo	htmlentities($value);	?>','<?php	echo	$search_id	?>','organization_main')"></i></div>
																				<?php	}	?>
																</div>
																<?php
												}
												else
												{
																?>

																<div id="organization_main" style="display: none;">
																				<div class="filter-fileds"><b>Organization</b></div>
																				<input type="hidden" id="organization_main_search" name="organization_main_search"/>
																</div>
												<?php	}	?>

												<!-- Organization Search Display End  -->
												<div class="clearfix"></div>
												<!-- State Search Display Start  -->

												<?php
												if(isset($this->session->userdata['states'])	&&	$this->session->userdata['states']	!=	'')
												{

																$state	=	$this->session->userdata['states'];
																$state_array	=	explode('|||',	$state);
																?>
																<div id="states_main">
																				<div class="filter-fileds"><b>State</b></div>
																				<input type="hidden" id="states_main_search" name="states_main_search" value="<?php	echo	$state;	?>" />
																				<?php
																				foreach($state_array	as	$value)
																				{
																								$search_id	=	name_slug($value);
																								?>
																								<div class="btn-img" id="<?php	echo	$search_id	?>" ><span class="search_keys"><?php	echo	$value;	?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php	echo	htmlentities($value);	?>','<?php	echo	$search_id	?>','states_main')"></i></div>
																				<?php	}	?>
																</div>
																<?php
												}
												else
												{
																?>

																<div id="states_main" style="display: none;">
																				<div class="filter-fileds"><b>State</b></div>
																				<input type="hidden" id="states_main_search" name="states_main_search"/>
																</div>
												<?php	}	?>

												<!-- State Search Display End  -->
												<div class="clearfix"></div>
												<!-- Nomination Status Search Display Start  -->
												<?php
												if(isset($this->session->userdata['nomination'])	&&	$this->session->userdata['nomination']	!=	'')
												{

																$nomination	=	$this->session->userdata['nomination'];
																$nomination_array	=	explode('|||',	$nomination);
																?>
																<div id="nomination_status_main">
																				<div class="filter-fileds"><b>Nomination Status</b></div>
																				<input type="hidden" id="nomination_status_main_search" name="nomination_status_main_search" value="<?php	echo	$nomination;	?>" />
																				<?php
																				foreach($nomination_array	as	$value)
																				{
																								$search_id	=	name_slug($value);
																								?>
																								<div class="btn-img" id="<?php	echo	$search_id	?>" ><span class="search_keys"><?php	echo	$value;	?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php	echo	htmlentities($value);	?>','<?php	echo	$search_id	?>','nomination_status_main')"></i></div>
																				<?php	}	?>
																</div>
																<?php
												}
												else
												{
																?>

																<div id="nomination_status_main" style="display: none;">
																				<div class="filter-fileds"><b>Nomination Status</b></div>
																				<input type="hidden" id="nomination_status_main_search" name="nomination_status_main_search"/>
																</div>
												<?php	}	?>
												<!-- Nomination Status Search Display End  -->
												<div class="clearfix"></div>
												<!-- Media Type Search Display Start  -->
												<?php
												if(isset($this->session->userdata['media_type'])	&&	$this->session->userdata['media_type']	!=	'')
												{

																$media_type	=	$this->session->userdata['media_type'];
																$media_type_array	=	explode('|||',	$media_type);
																?>
																<div id="media_type_main">
																				<div class="filter-fileds"><b>Media Type</b></div>
																				<input type="hidden" id="media_type_main_search" name="media_type_main_search" value="<?php	echo	$media_type;	?>" />
																				<?php
																				foreach($media_type_array	as	$value)
																				{
																								$search_id	=	name_slug($value);
																								?>
																								<div class="btn-img" id="<?php	echo	$search_id	?>" ><span class="search_keys"><?php	echo	$value;	?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php	echo	htmlentities($value);	?>','<?php	echo	$search_id	?>','media_type_main')"></i></div>
																				<?php	}	?>
																</div>
																<?php
												}
												else
												{
																?>

																<div id="media_type_main" style="display: none;">
																				<div class="filter-fileds"><b>Media Type</b></div>
																				<input type="hidden" id="media_type_main_search" name="media_type_main_search"/>
																</div>
												<?php	}	?>
												<!-- Media Type Search Display End  -->
												<div class="clearfix"></div>
												<!-- Physical Format Search Display Start  -->
												<?php
												if(isset($this->session->userdata['physical_format'])	&&	$this->session->userdata['physical_format']	!=	'')
												{

																$physical_format	=	$this->session->userdata['physical_format'];
																$physical_format_array	=	explode('|||',	$physical_format);
																?>
																<div id="physical_format_main">
																				<div class="filter-fileds"><b>Physical Format</b></div>
																				<input type="hidden" id="physical_format_main_search" name="physical_format_main_search" value="<?php	echo	$physical_format;	?>" />
																				<?php
																				foreach($physical_format_array	as	$value)
																				{
																								$search_id	=	name_slug($value);
																								?>
																								<div class="btn-img" id="<?php	echo	$search_id	?>" ><span class="search_keys"><?php	echo	$value;	?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php	echo	htmlentities($value);	?>','<?php	echo	$search_id	?>','physical_format_main')"></i></div>
																				<?php	}	?>
																</div>
																<?php
												}
												else
												{
																?>

																<div id="physical_format_main" style="display: none;">
																				<div class="filter-fileds"><b>Physical Format</b></div>
																				<input type="hidden" id="physical_format_main_search" name="physical_format_main_search"/>
																</div>
												<?php	}	?>
												<!-- Physical Format Search Display End  -->
												<div class="clearfix"></div>
												<!-- Digital Format Search Display Start  -->
												<?php
												if(isset($this->session->userdata['digital_format'])	&&	$this->session->userdata['digital_format']	!=	'')
												{

																$digital_format	=	$this->session->userdata['digital_format'];
																$digital_format_array	=	explode('|||',	$digital_format);
																?>
																<div id="digital_format_main">
																				<div class="filter-fileds"><b>Digital Format</b></div>
																				<input type="hidden" id="digital_format_main_search" name="digital_format_main_search" value="<?php	echo	$digital_format;	?>" />
																				<?php
																				foreach($digital_format_array	as	$value)
																				{
																								$search_id	=	name_slug($value);
																								?>
																								<div class="btn-img" id="<?php	echo	$search_id	?>" ><span class="search_keys"><?php	echo	$value;	?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php	echo	htmlentities($value);	?>','<?php	echo	$search_id	?>','digital_format_main')"></i></div>
																				<?php	}	?>
																</div>
																<?php
												}
												else
												{
																?>

																<div id="digital_format_main" style="display: none;">
																				<div class="filter-fileds"><b>Digital Format</b></div>
																				<input type="hidden" id="digital_format_main_search" name="digital_format_main_search"/>
																</div>
												<?php	}	?>
												<!-- Digital Format Search Display End  -->
												<div class="clearfix"></div>
												<!-- Generation Search Display Start  -->
												<?php
												if(isset($this->session->userdata['generation'])	&&	$this->session->userdata['generation']	!=	'')
												{

																$generation	=	$this->session->userdata['generation'];
																$generation_array	=	explode('|||',	$generation);
																?>
																<div id="generation_main">
																				<div class="filter-fileds"><b>Generation</b></div>
																				<input type="hidden" id="generation_main_search" name="generation_main_search" value="<?php	echo	$generation;	?>" />
																				<?php
																				foreach($generation_array	as	$value)
																				{
																								$search_id	=	name_slug($value);
																								?>
																								<div class="btn-img" id="<?php	echo	$search_id	?>" ><span class="search_keys"><?php	echo	$value;	?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php	echo	htmlentities($value);	?>','<?php	echo	$search_id	?>','generation_main')"></i></div>
																				<?php	}	?>
																</div>
																<?php
												}
												else
												{
																?>

																<div id="generation_main" style="display: none;">
																				<div class="filter-fileds"><b>Generation</b></div>
																				<input type="hidden" id="generation_main_search" name="generation_main_search"/>
																</div>
												<?php	}	?>
												<!-- Generation Search Display End  -->
												<div class="clearfix"></div>

								</div>
								<div style="margin: 4px 6px;">

												<input type="button" value="Reset" id="reset_all" name="reset_all" style="display: none;" class="btn" onclick="resetAll();"/>
								</div>
				</div>
				<div class="clearfix"></div>
				<div id="search_bar" class="facet-search"> 
								<input type="hidden" name="current_tab" id="current_tab" value="<?php	echo	isset($this->session->userdata['current_tab'])	?	$this->session->userdata['current_tab']	:	''	?>"  />
								<b>
												<h5 class="filter_title">FILTER</h5>
								</b>


								<?php
								if(isset($this->session->userdata['custom_search'])	&&	$this->session->userdata['custom_search']	!=	'')
								{

												$style	=	"none;";
												$reset	=	"block;";
								}
								else
								{
												$style	=	"block;";
												$reset	=	"none;";
								}
								?>
								<div class="field-filters" id="limit_field_div" style="display:<?php	echo	$style;	?>">
												<div class="filter-fileds" >
																<div><b>Keyword Search</b>	<span class="caret" style="margin-top: 8px;margin-left: 3px;cursor: pointer;" onclick="showHideSearch('sk_div',this);"></span></div>

																<!--																</div>-->
																<div id="sk_div">
																				<div>
																								<input type="text" name="search" id="search" value="" style="width: 190px;"/>
																				</div>

																				<div class="btn-group" id="limit_field_dropdown">
																								<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
																												<span id="limit_field_text">Limit Search to Field</span>
																												<span class="caret"></span>
																								</a>
																								<ul class="dropdown-menu">
																												<li class="dropdown"><a href="#" style="white-space: normal;">Asset Fields <i class="icon-chevron-right" style="float: right;"></i></a>
																																<ul class="sub-menu dropdown-menu">
																																				<li href="javascript://;" onclick="add_custom_token('AA GUID','guid_identifier');"><a>AA GUID</a></li>
																																				<li href="javascript://;" onclick="add_custom_token('Title','asset_title');"><a>Title</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Subject','asset_subject');">Subject</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Coverage','asset_coverage');">Coverage</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Genre','asset_genre');">Genre</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Publisher','asset_publisher_name');">Publisher</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Description','asset_description');">Description</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Creator Name','asset_creator_name');">Creator Name</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Creator Affiliation','asset_creator_affiliation');">Creator Affiliation</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Contributor Name','asset_contributor_name');">Contributor Name</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Contributor Affiliation','asset_contributor_affiliation');">Contributor Affiliation</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Rights Summaries','asset_rights');">Rights Summaries</a></li>

																																</ul>
																												</li>
																												<li class="dropdown"><a href="#"  style="white-space: normal;">Instantiation Fields <i class="icon-chevron-right" style="float: right;"></i></a>
																																<ul class="sub-menu dropdown-menu">
																																				<li><a href="javascript://;" onclick="add_custom_token('ID','instantiation_identifier');">ID</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('ID Source','instantiation_source');">Identifier Source</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Dimensions','instantiation_dimension');">Dimensions</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Unit of Measure','unit_of_measure');">Unit of Measure</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Standard','standard');">Standard</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Location','location');">Location</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('File Size','file_size');">File Size</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Duration','actual_duration track_duration');">Duration</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Data Rate','track_data_rate data_rate');">Data Rate</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Tracks','tracks');">Tracks</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Channel Configuration','channel_configuration');">Channel Configuration</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Language','language track_language');">Language</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Alternative Modes','alternative_modes');">Alternative Modes</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Annotation','ins_annotation track_annotation');">Annotation</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Annotation Type','ins_annotation_type');">Annotation Type</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Track Type','track_essence_track_type');">Track Type</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Encoding','track_encoding');">Encoding</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Track Standard','track_standard');">Track Standard</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Frame Rate','track_frame_rate');">Frame Rate</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Playback Speed','track_playback_speed');">Playback Speed</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Sampling Rate','track_sampling_rate');">Sampling Rate</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Bit Depth','track_bit_depth');">Bit Depth</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Frame Size','track_width track_height');">Frame Size</a></li>
																																				<li><a href="javascript://;" onclick="add_custom_token('Aspect Ratio','track_aspect_ratio');">Aspect Ratio</a></li>



																																</ul>
																												</li>
																								</ul>
																				</div>
																				<div id="limit_btn">
																								<input type="button"  style="margin-top: 10px;" id="add_keyword" name="add_keyword" value="Add Keyword" class="btn btn-primary" onclick="add_token('','keyword_field_main');"/>
																				</div>
																</div>
												</div>
								</div>
								<div class="clearfix"></div>
								<?php
								if((	!	isset($this->session->userdata['date_range'])	||	isset($this->session->userdata['date_range'])	)	&&	empty($this->session->userdata['date_range']))
								{
												$DateStyleDisplay	=	1;
								}
								else
								{
												$DateStyleDisplay	=	0;
								}
								?>
								<div class="field-filters" id="date_range_filter_div">
												<div class="filter-fileds"><b>Date</b><span class="caret" style="margin-top: 8px;margin-left: 3px;cursor: pointer;" onclick="showHideSearch('date_range_filter_div',this);"></span></div>
												<div>
																<div id="widget">
																				<div id="date_range_filter">

																								<div class="controls filter-fileds">
																												<div class="input-append">
																																<input type="text" name="date_range" id="date_range" value="" style="width: 180px;"/>
																												</div>
																								</div>
																				</div>

																</div>
																<div class="filter-fileds">
																				<?php
																				if(count($date_types)	>	0)
																				{
																								?>


																								<div class="btn-group" id="limit_field_dropdown">
																												<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
																																<span id="date_field_text">Date Type</span>

																																<span class="caret"></span>
																												</a>
																												<ul class="dropdown-menu">
																																<?php
																																foreach($date_types	as	$value)
																																{
																																				?>
																																				<li><a href="javascript://;" onclick="add_date_token('<?php	echo	$value->date_type;	?>');"><?php	echo	$value->date_type;	?></a></li>
																																<?php	}
																																?>
																												</ul>
																								</div>




																								<?php
																				}
																				?>
																				<div id="add_date_btn">
																								<input type="button" id="add_date_keyword" name="add_date_keyword" value="Add Date" class="btn btn-primary" onclick="add_token($('#date_range').val(),'date_field_main');"/>
																				</div>

																</div>
												</div>
								</div>
								<!-- Organization  Start      -->
								<?php
								if(count($stations)	>	0	&&	!	$this->is_station_user)
								{
												?>
												<div class="field-filters">
																<div class="filter-fileds">
																				<b>Organization</b><span class="caret" style="margin-top: 8px;margin-left: 3px;cursor: pointer;" onclick="showHideSearch('org_div',this);"></span>
																</div>
																<div class="filter-fileds" id="org_div">
																				<?php
																				foreach($stations	as	$key	=>	$value)
																				{
																								?>
																								<?php
																								if($key	<	4)
																								{
																												?>
																												<div><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->station_name);	?>','organization_main');"><?php	echo	$value->station_name	.	' ('	.	number_format($value->total)	.	')';	?></a></div>
																												<?php
																								}
																								else	if($key	==	4)
																								{
																												?>
																												<div class="dropdown">
																																<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
																																				More
																																				<b class="caret"></b>
																																</a>
																																<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
																																				<?php
																																}
																																else
																																{
																																				?>
																																				<li><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->station_name);	?>','organization_main');"><?php	echo	$value->station_name	.	' ('	.	number_format($value->total)	.	')';	?></a></li>
																																				<?php
																																}
																												}
																												if(count($stations)	>	4)
																												{
																																?>
																												</ul>
																								</div>
																				<?php	}	?>
																</div>
												</div>
								<?php	}	?>
								<!-- Organization  End      -->
								<!--  States Start      -->
								<?php
								if(count($org_states)	>	0)
								{
												?>
												<div class="field-filters">
																<div class="filter-fileds">
																				<b>States</b><span class="caret" style="margin-top: 8px;margin-left: 3px;cursor: pointer;" onclick="showHideSearch('org_st_div',this);"></span>
																</div>
																<div class="filter-fileds" id="org_st_div">
																				<?php
																				foreach($org_states	as	$key	=>	$value)
																				{
																								if($key	<	4)
																								{
																												?>
																												<div><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->state);	?>','states_main');"><?php	echo	$value->state	.	' ('	.	number_format($value->total)	.	')';	?></a></div>
																												<?php
																								}
																								else	if($key	==	4)
																								{
																												?>
																												<div class="dropdown">
																																<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
																																				More
																																				<b class="caret"></b>
																																</a>
																																<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
																																				<?php
																																}
																																else
																																{
																																				?>
																																				<li><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->state);	?>','states_main');"><?php	echo	$value->state	.	' ('	.	number_format($value->total)	.	')';	?></a></li>  
																																				<?php
																																}
																												}
																												if(count($org_states)	>	4)
																												{
																																?>
																												</ul>
																								</div>
																				<?php	}	?>
																</div>
												</div>
								<?php	}	?>
								<!--  States End      -->
								<!--  Nomination Status Start      -->
								<?php
								if(count($nomination_status)	>	0)
								{
												?>
												<div class="field-filters">
																<div class="filter-fileds">
																				<b>Nomination Status</b><span class="caret" style="margin-top: 8px;margin-left: 3px;cursor: pointer;" onclick="showHideSearch('n_div',this);"></span>
																</div>
																<div class="filter-fileds" id="n_div">
																				<?php
																				foreach($nomination_status	as	$key	=>	$value)
																				{
																								if($key	<	4)
																								{
																												?>
																												<div><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->status);	?>','nomination_status_main');"><?php	echo	$value->status	.	' ('	.	number_format($value->total)	.	')';	?></a></div>
																												<?php
																								}
																								else	if($key	==	4)
																								{
																												?>
																												<div class="dropdown">
																																<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
																																				More
																																				<b class="caret"></b>
																																</a>
																																<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
																																				<?php
																																}
																																else
																																{
																																				?>
																																				<li><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->status);	?>','nomination_status_main');"><?php	echo	$value->status	.	' ('	.	number_format($value->total)	.	')';	?></a></li>  
																																				<?php
																																}
																												}
																												if(count($nomination_status)	>	4)
																												{
																																?>
																												</ul>
																								</div>
																				<?php	}	?>
																</div>
												</div>
								<?php	}	?>
								<!--  Nomination Status End      -->
								<!--  Media Type Start      -->
								<?php
								if(count($media_types)	>	0)
								{
												?>
												<div class="field-filters">
																<div class="filter-fileds">
																				<b>Media Type</b><span class="caret" style="margin-top: 8px;margin-left: 3px;cursor: pointer;" onclick="showHideSearch('md_div',this);"></span>
																</div>
																<div class="filter-fileds" id="md_div">
																				<?php
																				foreach($media_types	as	$key	=>	$value)
																				{
																								if($key	<	4)
																								{
																												?>
																												<div><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->media_type);	?>','media_type_main');"><?php	echo	$value->media_type	.	' ('	.	number_format($value->total)	.	')';	?></a></div>
																												<?php
																								}
																								else	if($key	==	4)
																								{
																												?>
																												<div class="dropdown">
																																<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
																																				More
																																				<b class="caret"></b>
																																</a>
																																<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
																																				<?php
																																}
																																else
																																{
																																				?>
																																				<li><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->media_type);	?>','media_type_main');"><?php	echo	$value->media_type	.	' ('	.	number_format($value->total)	.	')';	?></a></li>  
																																				<?php
																																}
																												}
																												if(count($media_types)	>	4)
																												{
																																?>
																												</ul>
																								</div>
																				<?php	}	?>
																</div>
												</div>
								<?php	}	?>
								<!--  Media Type End      -->
								<!--  Physical Format Start      -->
								<?php
								if(count($physical_formats)	>	0)
								{
												?>
												<div class="field-filters">
																<div class="filter-fileds">
																				<b>Physical Format</b><span class="caret" style="margin-top: 8px;margin-left: 3px;cursor: pointer;" onclick="showHideSearch('pf_div',this);"></span>
																</div>
																<div class="filter-fileds" id="pf_div">
																				<?php
																				foreach($physical_formats	as	$key	=>	$value)
																				{
																								if($key	<	4)
																								{
																												?>
																												<div><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->format_name);	?>','physical_format_main');"><?php	echo	$value->format_name	.	' ('	.	number_format($value->total)	.	')';	?></a></div>
																												<?php
																								}
																								else	if($key	==	4)
																								{
																												?>
																												<div class="dropdown">
																																<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
																																				More
																																				<b class="caret"></b>
																																</a>
																																<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
																																				<?php
																																}
																																else
																																{
																																				?>
																																				<li><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->format_name);	?>','physical_format_main');"><?php	echo	$value->format_name	.	' ('	.	number_format($value->total)	.	')';	?></a></li>  
																																				<?php
																																}
																												}
																												if(count($physical_formats)	>	4)
																												{
																																?>
																												</ul>
																								</div>
																				<?php	}	?>
																</div>
												</div>
								<?php	}	?>
								<!-- Physical Format End      -->
								<!--  Digital Format Start      -->
								<?php
								if(count($digital_formats)	>	0)
								{
												?>
												<div class="field-filters">
																<div class="filter-fileds">
																				<b>Digital Format</b><span class="caret" style="margin-top: 8px;margin-left: 3px;cursor: pointer;" onclick="showHideSearch('df_div',this);"></span>
																</div>
																<div class="filter-fileds" id="df_div">
																				<?php
																				foreach($digital_formats	as	$key	=>	$value)
																				{
																								if($key	<	4)
																								{
																												?>
																												<div><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->format_name);	?>','digital_format_main');"><?php	echo	$value->format_name	.	' ('	.	number_format($value->total)	.	')';	?></a></div>
																												<?php
																								}
																								else	if($key	==	4)
																								{
																												?>
																												<div class="dropdown">
																																<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
																																				More
																																				<b class="caret"></b>
																																</a>
																																<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
																																				<?php
																																}
																																else
																																{
																																				?>
																																				<li><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->format_name);	?>','digital_format_main');"><?php	echo	$value->format_name	.	' ('	.	number_format($value->total)	.	')';	?></a></li>  
																																				<?php
																																}
																												}
																												if(count($digital_formats)	>	4)
																												{
																																?>
																												</ul>
																								</div>
																				<?php	}	?>
																</div>
												</div>
								<?php	}	?>
								<!-- Digital Format End      -->
								<!--  Generation Start      -->
								<?php
								if(count($generations)	>	0)
								{
												?>
												<div class="field-filters">
																<div class="filter-fileds">
																				<b>Generations</b><span class="caret" style="margin-top: 8px;margin-left: 3px;cursor: pointer;" onclick="showHideSearch('generation_search_div',this);"></span>
																</div>
																<div class="filter-fileds" id="generation_search_div">
																				<?php
																				foreach($generations	as	$key	=>	$value)
																				{
																								if($key	<	4)
																								{
																												?>
																												<div><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->generation);	?>','generation_main');"><?php	echo	$value->generation	.	' ('	.	number_format($value->total)	.	')';	?></a></div>
																												<?php
																								}
																								else	if($key	==	4)
																								{
																												?>
																												<div class="dropdown">
																																<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
																																				More
																																				<b class="caret"></b>
																																</a>
																																<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
																																				<?php
																																}
																																else
																																{
																																				?>
																																				<li><a href="javascript://" onclick="add_token('<?php	echo	htmlentities($value->generation);	?>','generation_main');"><?php	echo	$value->generation	.	' ('	.	number_format($value->total)	.	')';	?></a></li>  
																																				<?php
																																}
																												}
																												if(count($generations)	>	4)
																												{
																																?>
																												</ul>
																								</div>
																				<?php	}	?>
																</div>
												</div>
								<?php	}	?>
								<!-- Generation End      -->
								<!--				Digitized Start				-->
								<div class="field-filters">
												<div class="filter-fileds">
																<?php
																$checked	=	'';
																if(isset($this->session->userdata['digitized'])	&&	$this->session->userdata['digitized']	===	'1')
																{
																				$checked	=	'checked="checked"';
																}
																?>
																<b>Digitized</b><span style="margin: 0px 10px;"><input type="checkbox" name="digitized" id="digitized" value="1" <?php	echo	$checked;	?> onchange="add_checked_token('digitized','Digitized');" /></span>
												</div>
								</div>
								<!--				Digitized End      -->
								<!--				Migration Start				-->
								<div class="field-filters">
												<div class="filter-fileds">
																<?php
																$checked	=	'';
																if(isset($this->session->userdata['migration_failed'])	&&	$this->session->userdata['migration_failed']	===	'1')
																{
																				$checked	=	'checked="checked"';
																}
																?>
																<b>Migration Failed?</b><span style="margin: 0px 10px;"><input type="checkbox" name="migration_failed" id="migration_failed" value="1"  <?php	echo	$checked;	?> onchange="add_checked_token('migration_failed','Migration Failed');" /></span>
												</div>
								</div>
								<!--				Migration End      -->





				</div>
</form>

<script type="text/javascript">
    var is_destroy=false;
				var columnsOrder=new Array();
				var orderString='';
				var frozen='<?php	echo	$this->frozen_column;	?>';
				var hiden_column=new Array();
				var current_table_type='<?php	echo	$table_type	?>';
					
								
								
				oTable=null;
				
				(function($){
								var hash = window.location.hash.replace('#', '');
								if($(window.parent.document).find('iframe').size()){
												var inframe = true;
								}
								$('#date_range').daterangepicker({
											 
												posX: null,
												posY: null,
												arrows: false, 
												dateFormat: 'M d, yy',
												rangeSplitter: 'to',
										 	
												datepickerOptions: {
																changeMonth: true,
																changeYear: true,
																yearRange: '-500:+15',
																arrows: true
												},
												onOpen:function(){ if(inframe){ $(window.parent.document).find('iframe:eq(1)').width(700).height('35em');} }, 
												onClose: function(){ if(inframe){ $(window.parent.document).find('iframe:eq(1)').width('100%').height('5em');} }
								}); 
								
								
							
								
				})(jQuery)
				
				$(window).load(function(){
								display=<?php	echo	$DateStyleDisplay;	?>;
								if(display==0){
												$('#date_range_filter_div').hide();
								}
								if('<?php	echo	$current_tab;	?>'=='simple'){
												updateSimpleDataTable();
								}
								isAnySearch();
				});
				function add_token(name,type,isRemoved){
								if(type=='keyword_field_main'){
                
												name=$('#search').val();  
												if(isRemoved==1){
																$('#'+type+' .btn-img').each(function(){
																				$(this).remove();
																});
																$('#'+type+'_search').val('');
																$('#keyword_field_name').html();
																$('#limit_btn').show(); 
																//																$('#add_keyword').show(); 
																//																$('#reset_search').hide();
																$('#limit_field_text').html('Limit Search to Field');
																//																$('#limit_field_dropdown').show();
																$('#search').val('');
																$('#limit_field_div').show();
																customColumnName='';
																customFieldName='All';
                        
												}
												else{
																if($('#search').val()!=''){
																				$('#keyword_field_main .btn-img').each(function(){
																								$(this).remove();
																				});
																				//																				$('#add_keyword').hide(); 
																				//																				$('#reset_search').show();
																				//																				$('#limit_field_dropdown').hide();
																				$('#limit_field_div').hide();
																				var random_id=rand(0,1000365);
																				slugName=make_slug_name(name);
																				var search_id=slugName+random_id;
																				$('#keyword_field_name').html('Keyword: '+customFieldName);
																				$('#'+type).append('<div class="btn-img" id="'+search_id+'" ><span class="search_keys">'+name+'</span><i class="icon-remove-sign" style="float: right;" onclick="remove_token(\''+escape(name)+'\',\''+search_id+'\',\''+type+'\');"></i></div>');
																				$('#'+type).show();
																				var searchString='';
																				if(customColumnName!=''){
																								customColumnName= customColumnName.split(' ');
																								if(customColumnName.length>1){
																												searchString=' @'+customColumnName[0]+' |||'+$('#search').val()+'||| ';
																												searchString +=' @'+customColumnName[1]+' |||'+$('#search').val()+'||| ';
																								}
																								else{
																												searchString=' @'+customColumnName[0]+' |||'+$('#search').val()+'||| ';
																								}
                            
																				}
																				else{
																								searchString+=' |||'+$('#search').val()+'||| ';
																				}
                        
																				$('#keyword_field_main_search').val(searchString);
                            
																}
																else{
																				return false;
																}
												}
								}
								else if(type=='date_field_main'){
        
            
												if(isRemoved==1){
																$('#date_range').val('');
																$('#'+type+'_search').val('');
																$('#date_type').val('');
																$('#date_range_filter_div').show();
																$('#date_field_text').html('Date Type');
																//																$('#reset_date_search').hide();
												}
												else{
																if($('#date_range').val()=='')
																				return false;
																$('#date_range_filter_div').hide();
																	
																var random_id=rand(0,1000365);
																slugName=make_slug_name(name);
																$('#'+type).append('<div class="btn-img" id="'+search_id+'" ><span class="search_keys">'+name+'</span><i class="icon-remove-sign" style="float: right;" onclick="remove_token(\''+escape(name)+'\',\''+search_id+'\',\''+type+'\');"></i></div>');
																$('#'+type).show();
																//																$('#reset_date_search').show();
																if($('#date_type').val()=='')
																				date_type_text='All';
																else
																				date_type_text=$('#date_type').val();
																$('#date_field_name').html('Date Keyword: '+date_type_text);
												}
								}
								else{
												if(isRemoved!=1){
																if($('#'+type+'_search').val().indexOf(name) < 0){
																				var random_id=rand(0,1000365);
																				slugName=make_slug_name(name);
																				var search_id=slugName+random_id;
																				$('#'+type).append('<div class="btn-img" id="'+search_id+'" ><span class="search_keys">'+name+'</span><i class="icon-remove-sign" style="float: right;" onclick="remove_token(\''+name+'\',\''+search_id+'\',\''+type+'\')"></i></div>');
																				$('#'+type).show();
																}
												}
												var my_search_words='';
												$('#'+type+'_search').val('');
												$("#"+type+" .search_keys").each(function(index) {
																if(index==0)
																				my_search_words=$(this).text();
																else
																				my_search_words+='|||'+$(this).text();
                
												});
												if(my_search_words!='' && typeof(my_search_words)!=undefined)
												{
																$('#'+type+'_search').val(my_search_words);
												}
								}
								
								facet_search('0');
            
				}
				var customFieldName='All';
				var customColumnName='';
				function add_custom_token(fieldName,columnName){
								text=$('#search').val();
								customFieldName=fieldName;
								customColumnName=columnName;
								$('#limit_field_text').html(fieldName);
				}
				function add_date_token(type){
								$('#date_type').val(type);
								$('#date_field_text').html(type);
				}
				function make_slug_name(string){
								string = string.split('/').join('');
								string = string.split('?').join('');
								string = string.split(' ').join('');
								string = string.split('(').join('');
								string = string.split(')').join('');
								string = string.split(',').join('');
								string = string.split('.').join('');
								string = string.split('"').join('');
								string = string.split('\'').join('');
								string = string.split(':').join('');
								string = string.split(';').join('');
								string = string.split('&').join('');
								string = string.toLowerCase();
								return string;
				}
				function remove_token(name,id,type)
				{
								if(type=='keyword_field_main' || type=='date_field_main'){
												$('#'+type).hide();
												$('#'+type+' .btn-img').each(function(){
																$(this).remove();
												});
								}
								$("#"+id).remove();
								if($('#'+type+' div').length<=1){
												$('#'+type).hide();
								}
								add_token(unescape(name),type,1);        
				}
				function resetKeyword(type){
								if(type=='date'){
												$('#date_field_main .btn-img').each(function(){
																$(this).remove();
												});
												$('#date_field_main').hide();
												$('#date_range').val('');
												$('#date_type').val('');
												$('#date_range_filter_div').show();
												$('#date_field_text').html('Date Type');
												$('#date_field_main_search').val('');
												//												$('#reset_date_search').hide();
												
								}
								else{
												$('#keyword_field_main .btn-img').each(function(){
																$(this).remove();
												});
												$('#keyword_field_main_search').val('');
												$('#limit_btn').show(); 
												//												$('#add_keyword').show(); 
												//												$('#reset_search').hide();
												$('#limit_field_text').html('Limit Search to Field');
												//												$('#limit_field_dropdown').show();
												$('#search').val('');
												$('#keyword_field_main').hide();
												$('#limit_field_div').show();
												customColumnName='';
												customFieldName='All';
								}
								facet_search('0');
				}
				function facet_search(page)
				{
								if(typeof(page) == undefined)
								{
												page=0;
								}
								$.blockUI({
												css: { 
																border: 'none', 
																padding: '15px', 
																backgroundColor: '#000', 
																'-webkit-border-radius': '10px', 
																'-moz-border-radius': '10px', 
																opacity: .5, 
																color: '#fff',
																zIndex:999999
												}
								});
								$.ajax({
												type: 'POST', 
												url: '<?php	echo	$facet_search_url	?>/'+page,
												data:$('#form_search').serialize(),
												success: function (result)
												{ 
																$('.row-fluid').html(result);
																if('<?php	echo	$current_tab;	?>'=='simple')
																				updateSimpleDataTable();
																else
																				updateDataTable();
																isAnySearch();
																$.unblockUI();
                                    
												}
								});
				}
				function change_view(id)
				{
								$('#current_tab').val(id);
								$.ajax({
												type: 'POST', 
												url: '<?php	echo	site_url('records/set_current_tab')	?>/'+id,
												success: function (result)
												{ 
																window.location.reload();
												}
								});
				}
				function updateSimpleDataTable()
				{
								
								if($('#assets_table').length>0)
								{
												column_index='<?php	echo	isset($this->session->userdata['jscolumn'])	?	$this->session->userdata['jscolumn']	:	1;	?>';
												column_order='<?php	echo	isset($this->session->userdata['column_order'])	?	$this->session->userdata['column_order']	:	'desc';	?>';
												
												var oTable = $('#assets_table').dataTable({
																"aoColumns": [
																				{"bSortable": false },
																				null,
																				null,
																				null,
																				null,
																				null
																],
																"aSorting": [[ column_index, column_order ]],
																'bPaginate':false,
																'bInfo':false,
																'bFilter': false,
																"bSort": true,
																"bDeferRender": true,
																"bAutoWidth": false,
																"bProcessing": true,
																"bServerSide": true,
																"sAjaxSource": "http://amsqa.avpreserve.com/records/sort_simple_table"
																
																
												});
												new FixedHeader( oTable, {
																"offsetTop": 60,
																'width':'100%',
												}  );
												$.extend( $.fn.dataTableExt.oStdClasses, {
																"sWrapper": "dataTables_wrapper form-inline"
												} );
												$('.FixedHeader_Header table').width('100%');
												oTable.fnAdjustColumnSizing();
								}
				}
				function showHideSearch(divID,obj){
								
								$(obj).toggleClass('custom-caret');
								$('#'+divID).toggle();
				}
				function add_checked_token(id,name){
								if($('#'+id).is(':checked')){
												$('#checked_token').append('<div class="btn-img" id="'+id+'_token" ><span class="search_keys">'+name+'</span><i class="icon-remove-sign" style="float: right;" onclick="remove_checked_token(\''+id+'\')"></i></div>');			
												$('#checked_token').show();
												facet_search('0');
								}
								else{
												remove_checked_token(id);
								}
				}
				function remove_checked_token(id){
								$('#'+id).attr('checked',false);
								$('#'+id+'_token').remove();
								
								facet_search('0');
				}
				function isAnySearch(){
								if($('.search_keys').length>0){
												$('#filter_criteria').show();
												$('#reset_all').show();
												count='0 RECORD';
												if($('#total_list_count').html()!=undefined){
																if($('#total_list_count').html()==1)
																				count=$('#total_list_count').html()+' RECORD';
																else
																				count=$('#total_list_count').html()+' RECORDS';
												}
												$('#filter_record_count').html('('+count+')');
												$('#search_bar_val').css('margin-bottom','10px');
												$('#search_bar_val').css('padding-bottom','10px');
												
												
								}
								else{
												$('#filter_criteria').hide();
												$('#reset_all').hide();
												$('#search_bar_val').css('margin-bottom','0px');
												$('#search_bar_val').css('padding-bottom','0px');
								}
								
				}
				function resetAll(){
								$('.btn-img').each(function(){
												$(this).remove();
								});
								type=new Array('keyword_field_main','organization_main' ,'states_main' ,'nomination_status_main','media_type_main',
								'physical_format_main','digital_format_main','generation_main','date_field_main');
								for(cnt in type){
												if($('#'+type[cnt]+' div').length<=1){
																$('#'+type[cnt]).hide();
												}
								}
								$('#date_range_filter_div').show();
								$('#date_field_text').html('Date Type');
								$('#limit_field_text').html('Limit Search to Field');
								$('#limit_field_dropdown').show();
								$('#search').val('');
								$('#limit_field_div').show();
								$('#keyword_field_main_search').val('');
								$('#organization_main_search').val('');
								$('#states_main_search').val('');
								$('#nomination_status_main_search').val('');
								$('#media_type_main_search').val('');
								$('#physical_format_main_search').val('');
								$('#digital_format_main_search').val('');
								$('#generation_main_search').val('');
								$('#date_range').val('');
								$('#date_type').val('');
								$('#digitized').attr('checked',false);
								$('#migration_failed').attr('checked',false);
								$('#checked_token').hide();
								
								facet_search('0');
				}
</script>
