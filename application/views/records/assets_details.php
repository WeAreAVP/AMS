<div class="row">
	<div style="margin: 2px 0px 10px 0px;float:left;width: 570px;">
		<?php
		$asset_title_type = explode('|', trim(str_replace('(**)', '', $asset_details->title_type)));
		$asset_title = explode('|', trim(str_replace('(**)', '', $asset_details->title)));
		$asset_title_ref = explode('|', trim(str_replace('(**)', '', $asset_details->title_ref)));
		$combine_title = '';
		foreach ($asset_title as $index => $title)
		{
			if (isset($asset_title_type[$index]) && $asset_title_type[$index] != '')
				$combine_title.= $asset_title_type[$index] . ': ';
			if (isset($asset_title_ref[$index]))
			{
				if ($asset_title_ref[$index] != '')
				{
					$combine_title.="<a target='_blank' href='$asset_title_ref[$index]'>$title</a>: ";
					$combine_title.=' (' . $asset_title_ref[$index] . ')';
				}
				else
					$combine_title.=$title;
			}
			else
				$combine_title.=$title;



			$combine_title.='<div class="clearfix"></div>';
		}
		?>
		<h2><?php echo $combine_title; ?></h2>
	</div>
	<?php
	if ($next_result_id)
	{
		?>
		<div style="float: right;margin-left:5px"><a href="<?php echo site_url('records/details/' . $next_result_id); ?>" class="btn">Next >></a></div>
		<?php
	}
	if ($prev_result_id)
	{
		?>
		<div style="float: right;margin-left:5px"><a href="<?php echo site_url('records/details/' . $prev_result_id); ?>" class="btn"><< Previous</a></div>
		<?php
	}
	if ( ! is_empty($last_page))
	{
		?>
		<div style="float: right;margin-left:5px;"><a href="<?php echo site_url($last_page); ?>" class="btn">Return</a></div>
	<?php } ?>
<!--	<div style="float: right;">
		<button class="btn"><span class="icon-download-alt"></span>Export Asset</button>
	</div>-->
	<div class="clearfix"></div>

	<?php $this->load->view('partials/_list'); ?>
    <div class="span9" style="margin-left: 250px;">
		<?php $this->load->view('partials/_proxy_files'); ?>

		<div style="float: left;">
			<?php
			if ($this->role_id != '20')
			{
				?>

				<div style="margin-left: 20px;">
					<a href="<?php echo site_url('asset/edit/' . $asset_id); ?>" class="btn">Edit Asset</a>
					<a href="<?php echo site_url('instantiations/add/' . $asset_id); ?>" class="btn">Add Instantiation</a>
					
				</div>
			<?php } ?>

			<table  cellPadding="8" class="record-detail-table">
				<!--				Organization Start		-->
				<tr>
					<td class="record-detail-page">
						<label><i class="icon-question-sign"></i><span class="label_star"> *</span> <b>Organization:</b></label>
					</td>
					<td>

						<p><?php echo $asset_details->organization; ?></p>

					</td>
				</tr>
				<!--				Organization End		-->
				<!--				Asset Type Start		-->
				<?php
				if (isset($asset_details->asset_type) && ! empty($asset_details->asset_type))
				{
					?>
					<tr>
						<td class="record-detail-page">
							<label><i class="icon-question-sign"></i> <b>Asset Type:</b></label>
						</td>
						<td>
							<?php
							$asset_types = explode(" | ", $asset_details->asset_type);
							foreach ($asset_types as $asset_type)
							{
								?>
								<p><?php echo trim($asset_type); ?></p>
							<?php } ?>
						</td>					
					</tr>	

				<?php } ?>
				<!--				Asset Type End		-->
				<!--				Asset Title Start		-->
				<?php
				if (isset($combine_title) && ! empty($combine_title))
				{
					?>
					<tr>
						<td class="record-detail-page">
							<label><i class="icon-question-sign"></i><span class="label_star"> *</span> <b>Title(s):</b></label>
						</td>
						<td>
							<?php echo $combine_title; ?>
						</td>
					</tr>
				<?php } ?>
				<!--				Asset Title End		-->
				<!--				Asset Description Start		-->
				<?php
				if (isset($asset_description) && ! empty($asset_description))
				{
					?>
					<tr>
						<td class="record-detail-page">
							<label><i class="icon-question-sign"></i><span class="label_star"> *</span><b> Description:</b></label>
						</td>
						<td>
							<?php
							$combine_description = '';
							foreach ($asset_description as $value)
							{
								if ( ! empty($value->description_type))
									$combine_description .=$value->description_type . ':';
								$combine_description .=$value->description . '<br/>';
							}
							?>
							<p><?php echo $combine_description; ?></p>
						</td>
					</tr>
				<?php } ?>
				<!--				Asset Description Start		-->
				<!--				Asset Genre Start		-->
				<?php
				if (isset($asset_genres) && ! empty($asset_genres))
				{
					$combine_genre = '';
					foreach ($asset_genres as $main_genre)
					{
						?>

						<?php
						$asset_genre = explode(' | ', trim(str_replace('(**)', '', $main_genre->genre)));
						$asset_genre_ref = explode(' | ', trim(str_replace('(**)', '', $main_genre->genre_ref)));
						$asset_genre_source = explode(' | ', trim(str_replace('(**)', '', $main_genre->genre_source)));

						if (count($asset_genre) > 0)
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i> <b>Genres:</b></label>
								</td>
								<td>
									<?php
									foreach ($asset_genre as $index => $genre)
									{

										if (isset($asset_genre_ref[$index]))
										{
											if ($asset_genre_ref[$index] != '')
											{
												$combine_genre.="<a target='_blank' href='$asset_genre_ref[$index]'>$genre</a>";
											}
											else
												$combine_genre.=$genre;
										}
										else
											$combine_genre.=$genre;
										if (isset($asset_genre_source[$index]) && $asset_genre_source[$index] != '')
											$combine_genre.=' (' . $asset_genre_source[$index] . ')';
										$combine_genre.='<div class="clearfix"></div>';
									}
									?>
									<p><?php echo $combine_genre; ?></p>
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
				if (isset($asset_creators_roles) && ! empty($asset_creators_roles))
				{
					$combine_creator = '';
					foreach ($asset_creators_roles as $creator)
					{

						$asset_creator_name = explode(' | ', trim(str_replace('(**)', '', $creator->asset_creator_name)));
						$asset_creator_ref = explode(' | ', trim(str_replace('(**)', '', $creator->asset_creator_ref)));
						$asset_creator_affiliation = explode(' | ', trim(str_replace('(**)', '', $creator->asset_creator_affiliation)));
						$asset_creator_role = explode(' | ', trim(str_replace('(**)', '', $creator->asset_creator_role)));
						$asset_creator_role_ref = explode(' | ', trim(str_replace('(**)', '', $creator->asset_creator_role_ref)));
						$asset_creator_role_source = explode(' | ', trim(str_replace('(**)', '', $creator->asset_creator_role_source)));


						if (count($asset_creator_name) > 0 && $asset_creator_name[0] != '')
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i><b>Creator:</b></label>
								</td>
								<td>
									<?php
									foreach ($asset_creator_name as $index => $creator_name)
									{

										if (isset($asset_creator_ref[$index]) && ! empty($asset_creator_ref[$index]))
										{
											$combine_creator.="<a target='_blank' href='$asset_creator_ref[$index]'>$creator_name</a>";
										}
										else
											$combine_creator.=$creator_name;
										if (isset($asset_creator_affiliation[$index]) && $asset_creator_affiliation[$index] != '')
											$combine_creator.=',' . $asset_creator_affiliation[$index];

										if (isset($asset_creator_role[$index]) && ! empty($asset_creator_role[$index]))
										{
											if (isset($asset_creator_role_ref[$index]) && ! empty($asset_creator_role_ref[$index]))
											{
												$combine_creator.=",<a target='_blank' href='$asset_creator_role_ref[$index]'>$asset_creator_role[$index]</a>";
											}
											else
												$combine_creator.=',' . $asset_creator_role[$index];
										}
										if (isset($asset_creator_role_source[$index]) && $asset_creator_role_source[$index] != '')
											$combine_creator.=' (' . $asset_creator_role_source[$index] . ')';
										$combine_creator.='<div class="clearfix"></div>';
									}
									?>
									<p><?php echo $combine_creator; ?></p>
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
				if (isset($asset_contributor_roles) && ! empty($asset_contributor_roles))
				{
					$combine_contributor = '';
					foreach ($asset_contributor_roles as $contributor)
					{
						$asset_contributor_name = explode(' | ', trim(str_replace('(**)', '', $contributor->asset_contributor_name)));
						$asset_contributor_ref = explode(' | ', trim(str_replace('(**)', '', $contributor->asset_contributor_ref)));
						$asset_contributor_affiliation = explode(' | ', trim(str_replace('(**)', '', $contributor->asset_contributor_affiliation)));
						$asset_contributor_role = explode(' | ', trim(str_replace('(**)', '', $contributor->asset_contributor_role)));
						$asset_contributor_role_ref = explode(' | ', trim(str_replace('(**)', '', $contributor->asset_contributor_role_ref)));
						$asset_contributor_role_source = explode(' | ', trim(str_replace('(**)', '', $contributor->asset_contributor_role_source)));

						if (count($asset_contributor_name) > 0 && $asset_contributor_name[0] != '')
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i><b>Contributor:</b></label>
								</td>
								<td>
									<?php
									foreach ($asset_contributor_name as $index => $contributor_name)
									{

										if (isset($asset_contributor_ref[$index]) && ! empty($asset_contributor_ref[$index]))
										{
											$combine_contributor.="<a target='_blank' href='$asset_contributor_ref[$index]'>$contributor_name</a>";
										}
										else
											$combine_contributor.=$contributor_name;
										if (isset($asset_contributor_affiliation[$index]) && $asset_contributor_affiliation[$index] != '')
											$combine_contributor.=',' . $asset_contributor_affiliation[$index];

										if (isset($asset_contributor_role[$index]) && ! empty($asset_contributor_role[$index]))
										{
											if (isset($asset_contributor_role_ref[$index]) && ! empty($asset_contributor_role_ref[$index]))
											{
												$combine_contributor.=",<a target='_blank' href='$asset_contributor_role_ref[$index]'>$asset_contributor_role[$index]</a>";
											}
											else
												$combine_contributor.=',' . $asset_contributor_role[$index];
										}
										if (isset($asset_contributor_role_source[$index]) && $asset_contributor_role_source[$index] != '')
											$combine_contributor.=' (' . $asset_contributor_role_source[$index] . ')';
										$combine_contributor.='<div class="clearfix"></div>';
									}
									?>
									<p><?php echo $combine_contributor; ?></p>
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
				if (isset($asset_publishers_roles) && ! empty($asset_publishers_roles))
				{
					$combine_publisher = '';
					foreach ($asset_publishers_roles as $publisher)
					{
						$asset_publisher_name = explode(' | ', trim(str_replace('(**)', '', $publisher->asset_publisher_name)));
						$asset_publisher_ref = explode(' | ', trim(str_replace('(**)', '', $publisher->asset_publisher_ref)));
						$asset_publisher_affiliation = explode(' | ', trim(str_replace('(**)', '', $publisher->asset_publisher_affiliation)));
						$asset_publisher_role = explode(' | ', trim(str_replace('(**)', '', $publisher->asset_publisher_role)));
						$asset_publisher_role_ref = explode(' | ', trim(str_replace('(**)', '', $publisher->asset_publisher_role_ref)));
						$asset_publisher_role_source = explode(' | ', trim(str_replace('(**)', '', $publisher->asset_publisher_role_source)));

						if (count($asset_publisher_name) > 0 && $asset_publisher_name[0] != '')
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i><b>Publisher:</b></label>
								</td>
								<td>
									<?php
									foreach ($asset_publisher_name as $index => $publisher_name)
									{

										if (isset($asset_publisher_ref[$index]) && ! empty($asset_publisher_ref[$index]))
										{
											$combine_publisher.="<a target='_blank' href='$asset_publisher_ref[$index]'>$publisher_name</a>";
										}
										else
											$combine_publisher.=$publisher_name;
										if (isset($asset_publisher_affiliation[$index]) && $asset_publisher_affiliation[$index] != '')
											$combine_publisher.=',' . $asset_publisher_affiliation[$index];

										if (isset($asset_publisher_role[$index]) && ! empty($asset_publisher_role[$index]))
										{
											if (isset($asset_publisher_role_ref[$index]) && ! empty($asset_publisher_role_ref[$index]))
											{
												$combine_publisher.=",<a target='_blank' href='$asset_publisher_role_ref[$index]'>$asset_publisher_role[$index]</a>";
											}
											else
												$combine_publisher.=',' . $asset_publisher_role[$index];
										}
										if (isset($asset_publisher_role_source[$index]) && $asset_publisher_role_source[$index] != '')
											$combine_publisher.=' (' . $asset_publisher_affiliation[$index] . ')';
										$combine_publisher.='<div class="clearfix"></div>';
									}
									?>
									<p><?php echo $combine_publisher; ?></p>
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
				if (isset($asset_dates) && ! empty($asset_dates))
				{
					foreach ($asset_dates as $date)
					{
						$date_type = explode(' | ', trim(str_replace('(**)', '', $date->asset_date)));
						$asset_date = explode(' | ', trim(str_replace('(**)', '', $date->date_type)));
						if ((isset($asset_date) && $asset_date[0] != '') || (isset($date_type) && $date_type[0] != ''))
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
				if (isset($asset_subjects) && ! empty($asset_subjects))
				{
					$combine_subject = '';
					foreach ($asset_subjects as $main_subject)
					{
						$asset_subject = explode(' | ', trim(str_replace('(**)', '', $main_subject->asset_subject)));
						$asset_subject_ref = explode(' | ', trim(str_replace('(**)', '', $main_subject->asset_subject_ref)));
						$asset_subject_source = explode(' | ', trim(str_replace('(**)', '', $main_subject->asset_subject_source)));

						if (count($asset_subject) > 0 && $asset_subject[0] != '')
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i><b>Subject:</b></label>
								</td>
								<td>
									<?php
									foreach ($asset_subject as $index => $subject)
									{

										if (isset($asset_subject_ref[$index]))
										{
											if ($asset_subject_ref[$index] != '')
											{
												$combine_subject.="<a target='_blank' href='$asset_subject_ref[$index]'><b>$subject</b></a>";
											}
											else
												$combine_subject.='<b>' . $subject . '</b>';
										}
										else
											$combine_subject.=$subject;
										if (isset($asset_subject_source[$index]) && $asset_subject_source[$index] != '')
											$combine_subject.=' (' . $asset_subject_source[$index] . ')';
										$combine_subject.='<div class="clearfix"></div>';
									}
									?>
									<p><?php echo $combine_subject; ?></p>
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
				if (isset($asset_coverages) && ! empty($asset_coverages))
				{
					$combine_coverage = '';
					foreach ($asset_coverages as $coverage)
					{
						$asset_coverage = explode(' | ', trim(str_replace('(**)', '', $coverage->coverage)));
						$asset_coverage_type = explode(' | ', trim(str_replace('(**)', '', $coverage->coverage_type)));

						if (count($asset_coverage) && $asset_coverage[0] != '')
						{
							foreach ($asset_coverage as $index => $row)
							{
								if (isset($asset_coverage_type[$index]))
								{
									$combine_coverage.=$asset_coverage_type[$index] . ': ';
								}
								$combine_coverage.=$row;
								$combine_coverage.='<div class="clearfix"></div>';
							}
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i><b>Coverage:</b></label>
								</td>
								<td>
									<?php echo $combine_coverage; ?>
								</td>
							</tr>

						<?php }
						?>
						<?php
					}
				}
				?>
				<!--				Coverage End		-->
				<!--				Rights Start		-->
				<?php
				if (isset($rights_summaries) && ! empty($rights_summaries))
				{
					$combine_right = '';
					foreach ($rights_summaries as $right_summary)
					{
						$rights = explode(' | ', trim(str_replace('(**)', '', $right_summary->rights)));
						$right_link = explode(' | ', trim(str_replace('(**)', '', $right_summary->rights_link)));

						if (count($rights) > 0 && $rights[0] != '')
						{
							foreach ($rights as $index => $right)
							{
								if (isset($right_link[$index]) && $right_link[$index] != '')
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
									<label><i class="icon-question-sign"></i> <b>Rights:</b></label>
								</td>
								<td>
									<?php echo $combine_right; ?>
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
				<!--				Audience Level Start		-->
				<?php
				if ((isset($asset_audience_level) && ! empty($asset_audience_level)))
				{
					$combine_audience = '';
					foreach ($asset_audience_level as $aa_level)
					{
						$a_level = explode(' | ', trim(str_replace('(**)', '', $right_summary->audience_level)));
						$a_level_source = explode(' | ', trim(str_replace('(**)', '', $right_summary->audience_level_source)));
						$a_level_ref = explode(' | ', trim(str_replace('(**)', '', $right_summary->audience_level_ref)));
						if (count($a_level) > 0)
						{
							foreach ($a_level as $index => $row)
							{
								if (isset($a_level_ref[$index]) && $a_level_ref[$index] != '')
								{
									$combine_audience.="<a href='$a_level_ref[$index]'>$row</a>";
								}
								else
									$combine_audience.=$row;
								if (isset($a_level_source[$index]) && $a_level_source[$index] != '')
								{
									$combine_audience.=" ($a_level_source[$index])";
								}
								$combine_audience.='<div class="clearfix"></div>';
							}
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i> <b>Audience Level:</b></label>
								</td>
								<td>
									<?php echo $combine_audience; ?>
								</td>
							</tr>
							<?php
						}
					}
				}
				?>

				<!--				Audience Level End		-->
				<!--				Audience Rating Start		-->
				<?php
				if (isset($asset_audience_rating) && ! empty($asset_audience_rating))
				{
					$combine_audience_rating = '';
					foreach ($asset_audience_rating as $aa_rating)
					{
						$a_rating = explode(' | ', trim(str_replace('(**)', '', $aa_rating->audience_rating)));
						$a_rating_source = explode(' | ', trim(str_replace('(**)', '', $aa_rating->audience_rating_source)));
						$a_rating_ref = explode(' | ', trim(str_replace('(**)', '', $aa_rating->audience_rating_ref)));
						if (count($a_rating) > 0)
						{
							foreach ($a_rating as $index => $row)
							{
								if (isset($a_rating_ref[$index]) && $a_rating_ref[$index] != '')
								{
									$combine_audience_rating.="<a href='$a_rating_ref[$index]'>$row</a>";
								}
								else
									$combine_audience_rating.=$row;
								if (isset($a_rating_source[$index]) && $a_rating_source[$index] != '')
								{
									$combine_audience_rating.=" ($a_rating_source[$index])";
								}
								$combine_audience_rating.='<div class="clearfix"></div>';
							}
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i> <b>Audience Rating:</b></label>
								</td>
								<td>
									<?php echo $combine_audience_rating; ?>
								</td>
							</tr>
							<?php
						}
					}
				}
				?>

				<!--				Audience Rating End		-->
				<!--				Annotation Start		-->
				<?php
				if (isset($annotations) && ! empty($annotations))
				{
					$combine_annotations = '';
					foreach ($annotations as $a_annotations)
					{
						$a_anno = explode(' | ', trim(str_replace('(**)', '', $a_annotations->annotation)));
						$a_anno_type = explode(' | ', trim(str_replace('(**)', '', $a_annotations->annotation_type)));
						$a_anno_ref = explode(' | ', trim(str_replace('(**)', '', $a_annotations->annotation_ref)));
						if (count($a_anno) > 0 && $a_anno[0] != '')
						{
							foreach ($a_anno as $index => $row)
							{
								if (isset($a_anno_type[$index]) && $a_anno_type[$index] != '')
								{
									$combine_annotations.="$a_anno_type[$index]: ";
								}
								if (isset($a_anno_ref[$index]) && $a_anno_ref[$index] != '')
								{
									$combine_annotations.="<a href='$a_anno_ref[$index]'>$row</a>";
								}
								else
									$combine_annotations.=$row;

								$combine_annotations.='<div class="clearfix"></div>';
							}
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i> <b>Annotation:</b></label>
								</td>
								<td>
									<?php echo $combine_annotations; ?>
								</td>
							</tr>
							<?php
						}
					}
				}
				?>

				<!--			Annotation End		-->
				<!--  Relation Start  -->
				<?php
				if (count($relation) > 0)
				{
					$combine_relation = '';
					foreach ($relation as $index => $relation)
					{
						if (isset($relation->relation_type) && ! empty($relation->relation_type))
							$combine_relation .=$relation->relation_type . ' : ';
						$combine_relation .=$relation->relation_identifier;
						if (isset($relation->relation_type_source) && ! empty($relation->relation_type_source))
							$relation_type_src = $relation->relation_type_source;
						if (isset($relation->relation_type_ref) && ! empty($relation->relation_type_ref))
							$combine_relation .= " (<a href='$relation->relation_type_ref' target='_blank'>$relation_type_src</a>)";
						else
							$combine_relation .=' (' . $relation_type_src . ')';
						if ( ! empty($combine_relation) && trim($combine_relation) != ':')
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i><b> Relation:</b></label>
								</td>
								<td>
									<p>	<?php echo $combine_relation; ?></p>

								</td>
							</tr>
							<?php
						}
					}
				}
				?>
				<!--  Relation End  -->
				<!--			Local ID Start		-->
				<?php
				if (isset($asset_localid) && $asset_localid != '(**)')
				{
					$combine_local_identifier = '';
					$a_local = explode(' | ', trim(str_replace('(**)', '', $asset_localid->local_identifier)));
					$a_local_source = explode(' | ', trim(str_replace('(**)', '', $asset_localid->local_identifier_source)));
					$a_local_ref = explode(' | ', trim(str_replace('(**)', '', $asset_localid->local_identifier_ref)));
					if (count($a_local) > 0 && $a_local[0] != '')
					{
						foreach ($a_local as $index => $row)
						{
							if (isset($a_local_ref[$index]) && $a_local_ref[$index] != '')
							{
								$combine_local_identifier.="<a href='$a_local_ref[$index]'>$row</a>";
							}
							else
								$combine_local_identifier.=$row;
							if (isset($a_local_source[$index]) && $a_local_source[$index] != '')
							{
								$combine_local_identifier.=" ($a_local_source[$index])";
							}
							$combine_local_identifier.='<div class="clearfix"></div>';
						}
						?>
						<tr>
							<td class="record-detail-page">
								<label><i class="icon-question-sign"></i><span class="label_star"> *</span> <b>Local ID:</b></label>
							</td>
							<td>
								<?php echo $combine_local_identifier; ?>
							</td>
						</tr>
						<?php
					}
				}
				?>

				<!--			Local ID End		-->
				<!--		American Archive GUID Start		-->
				<?php
				if (isset($asset_guid) && ! empty($asset_guid))
				{
					$combine_guid = '';
					if (isset($asset_guid->guid_identifier_ref) && ! empty($asset_guid->guid_identifier_ref))
					{
						$combine_guid.="<a href='$asset_guid->guid_identifier_ref'>$asset_guid->guid_identifier</a>";
					}
					else
						$combine_guid.=$asset_guid->guid_identifier;
					?>
					<tr>
						<td class="record-detail-page">
							<label><i class="icon-question-sign"></i><span class="label_star"> *</span> <b>American Archive GUID:</b></label>
						</td>
						<td>
							<?php echo $combine_guid; ?>
						</td>
					</tr>
				<?php } ?>
				<!--			American Archive GUID End		-->
			</table>

		</div>
	</div>
</div>