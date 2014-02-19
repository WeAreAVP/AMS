<?php

function simple_simple_datatable_view($records)
{
	$tablesort = array();
	foreach ($records as $index => $value)
	{

		$tablesort[$index][] = '<span style="float:left;min-width:20px;max-width:20px;"><i style="margin:0px" class="unflag"></i></span>';
		$asset_title_type = explode('|', trim(str_replace('(**)', '', $value->asset_title_type)));
		$asset_title = explode('|', trim(str_replace('(**)', '', $value->asset_title)));
		$asset_title_ref = explode('|', trim(str_replace('(**)', '', $value->asset_title_ref)));
		$asset_combine = '';
		foreach ($asset_title as $aindex => $title)
		{
			if (isset($asset_title_type[$aindex]) && $asset_title_type[$aindex] != '')
				$asset_combine.= $asset_title_type[$aindex] . ': ';
			if (isset($asset_title_ref[$aindex]))
			{
				if ($asset_title_ref[$aindex] != '')
				{
					$asset_combine.="<a target='_blank' href='$asset_title_ref[$aindex]'>$title</a>: ";
					$asset_combine.=' (' . $asset_title_ref[$aindex] . ')';
				}
				else
					$asset_combine.=$title;
			}
			else
				$asset_combine.=$title;
			$asset_combine.='<div class="clearfix"></div>';
		}

		$tablesort[$index][] = str_replace("(**)", '', '<span style="float:left;min-width:200px;max-width:200px;">' . $value->organization . '</span>');
		$tablesort[$index][] = str_replace("(**)", '', '<span style="float:left;min-width:250px;max-width:250px;"><a href="' . site_url('records/details/' . $value->id) . '">' . $value->guid_identifier . '</a></span>');
		$tablesort[$index][] = str_replace('|', '<br/>', str_replace("(**)", '', '<span style="float:left;min-width:250px;max-width:250px;">' . $value->local_identifier . '</span>'));
		$tablesort[$index][] = str_replace("(**)", '', '<span style="float:left;min-width:300px;max-width:300px;">' . $asset_combine . '</span>');
		if (strlen($value->description) > 200)
			$description = substr($value->description, 0, strpos($value->description, ' ', 200)) . '...';
		else
			$description = $value->description;
		$tablesort[$index][] = str_replace("(**)", '', '<span style="float:left;min-width:300px;max-width:350px;">' . $description . '</span>');
	}
	return $tablesort;
}

function full_assets_datatable_view($records, $column_order)
{
	$tablesort = array();
	foreach ($records as $main_index => $asset)
	{
		foreach ($column_order as $row)
		{
			$type = $row['title'];

			if ($type == 'Organization')
			{
				$tablesort[$main_index][] = '<span style="float:left;min-width:200px;max-width:200px;">' . $asset->organization . '</span>';
			}
			else if ($type == 'Titles')
			{
				$asset_title_type = explode('|', trim(str_replace('(**)', '', $asset->asset_title_type)));
				$asset_title = explode('|', trim(str_replace('(**)', '', $asset->asset_title)));
				$asset_title_ref = explode('|', trim(str_replace('(**)', '', $asset->asset_title_ref)));
				$column = '';
				foreach ($asset_title as $index => $title)
				{
					if (isset($asset_title_type[$index]) && $asset_title_type[$index] != '')
						$column.= $asset_title_type[$index] . ': ';
					if (isset($asset_title_ref[$index]))
					{
						if ($asset_title_ref[$index] != '')
						{
							$column.="<a target='_blank' href='$asset_title_ref[$index]'>$title</a>: ";
							$column.=' (' . $asset_title_ref[$index] . ')';
						}
						else
							$column.=$title;
					}
					else
						$column.=$title;
					$column.='<div class="clearfix"></div>';
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:300px;max-width:300px;">' . $column . '</span>';
			}
			else if ($type == 'AA_GUID')
			{
				if ($asset->guid_identifier)
				{
					$tablesort[$main_index][] = '<span style="float:left;min-width:300px;max-width:300px;"><a href="' . site_url('records/details/' . $asset->id) . '" >' . $asset->guid_identifier . '</a></span>';
				}
				else
				{
					$tablesort[$main_index][] = '<span style="float:left;min-width:300px;max-width:300px;"><a href="' . site_url('records/details/' . $asset->id) . '" >No GUID</a></span>';
				}
			}
			else if ($type == 'Local_ID')
			{
				$tablesort[$main_index][] = '<span style="float:left;min-width:200px;max-width:200px;">' . str_replace('|', '<br/>', str_replace('(**)', '', $asset->local_identifier)) . '</span>';
			}
			else if ($type == 'Description')
			{
				$column = '';
				if (isset($asset->description) && ! empty($asset->description))
				{
					$asset_description = explode(' | ', trim(str_replace('(**)', '', $asset->description)));
					$description_type = explode(' | ', trim(str_replace('(**)', '', $asset->description_type)));

					if (count($asset_description) > 0)
					{
						foreach ($asset_description as $index => $description)
						{
							if (isset($description) && ! empty($description))
							{
								if (isset($description_type[$index]) && $description_type[$index] != '')
									$column.='Type:' . $description_type[$index] . '<br/>';
								if (strlen($description) > 160)
								{
									$messages = str_split($description, 160);
									$column.= $messages[0] . ' ...';
								}
								else
									$column.=$description;
							}
							$column .= '<div class="clearfix"></div>';
						}
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:300px;max-width:300px;">' . $column . '</span>';
			}
			else if ($type == 'Subjects')
			{

				$asset_subject = explode(' | ', trim(str_replace('(**)', '', $asset->asset_subject)));
				$asset_subject_ref = explode(' | ', trim(str_replace('(**)', '', $asset->asset_subject_ref)));
				$asset_subject_source = explode(' | ', trim(str_replace('(**)', '', $asset->asset_subject_source)));
				$column = '';
				if (count($asset_subject) > 0)
				{
					foreach ($asset_subject as $index => $subject)
					{

						if (isset($asset_subject_ref[$index]))
						{
							if ($asset_subject_ref[$index] != '')
							{
								$column.="<a target='_blank' href='$asset_subject_ref[$index]'>$subject</a>";
							}
							else
								$column.=$subject;
						}
						else
							$column.=$subject;
						if (isset($asset_subject_source[$index]) && $asset_subject_source[$index] != '')
							$column.=' (' . $asset_subject_source[$index] . ')';
						$column.='<div class="clearfix"></div>';
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:120px;max-width:120px;">' . $column . '</span>';
			}
			else if ($type == 'Genre')
			{

				$asset_genre = explode(' | ', trim(str_replace('(**)', '', $asset->asset_genre)));
				$asset_genre_ref = explode(' | ', trim(str_replace('(**)', '', $asset->asset_genre_ref)));
				$asset_genre_source = explode(' | ', trim(str_replace('(**)', '', $asset->asset_genre_source)));
				$column = '';
				if (count($asset_genre) > 0)
				{
					foreach ($asset_genre as $index => $genre)
					{

						if (isset($asset_genre_ref[$index]))
						{
							if ($asset_genre_ref[$index] != '')
							{
								$column.="<a target='_blank' href='$asset_genre_ref[$index]'>$genre</a>";
							}
							else
								$column.=$genre;
						}
						else
							$column.=$genre;
						if (isset($asset_genre_source[$index]) && $asset_genre_source[$index] != '')
							$column.=' (' . $asset_genre_source[$index] . ')';
						$column.='<div class="clearfix"></div>';
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:120px;max-width:120px;">' . $column . '</span>';
			}
			else if ($type == 'Assets_Date')
			{
				$column = '';
				$asset_dates = explode(' | ', $asset->dates);
				$asset_dates_types = explode(' | ', trim(str_replace('(**)', '', $asset->date_type)));
				if (count($asset_dates) > 0)
				{
					foreach ($asset_dates as $index => $dates)
					{

						if (isset($asset_dates_types[$index]) && $dates > 0)
						{
							$column .= $asset_dates_types[$index] . ': ' . date('Y-m-d', $dates);
						}
						else if ($dates > 0)
						{
							$column.=date('Y-m-d', $dates);
						}
						$column.='<div class="clearfix"></div>';
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:120px;max-width:120px;">' . $column . '</span>';
			}
			else if ($type == 'Creator')
			{
				$asset_creator_name = explode(' | ', trim(str_replace('(**)', '', $asset->asset_creator_name)));
				$asset_creator_ref = explode(' | ', trim(str_replace('(**)', '', $asset->asset_creator_ref)));
				$asset_creator_affiliation = explode(' | ', trim(str_replace('(**)', '', $asset->asset_creator_affiliation)));
				$asset_creator_role = explode(' | ', trim(str_replace('(**)', '', $asset->asset_creator_role)));
				$asset_creator_role_ref = explode(' | ', trim(str_replace('(**)', '', $asset->asset_creator_role_ref)));
				$asset_creator_role_source = explode(' | ', trim(str_replace('(**)', '', $asset->asset_creator_role_source)));
				$column = '';
				if (count($asset_creator_name) > 0)
				{
					foreach ($asset_creator_name as $index => $creator_name)
					{

						if (isset($asset_creator_ref[$index]) && ! empty($asset_creator_ref[$index]))
						{
							$column.="<a target='_blank' href='$asset_creator_ref[$index]'>$creator_name</a>";
						}
						else
							$column.=$creator_name;
						if (isset($asset_creator_affiliation[$index]) && $asset_creator_affiliation[$index] != '')
							$column.=',' . $asset_creator_affiliation[$index];

						if (isset($asset_creator_role[$index]) && ! empty($asset_creator_role[$index]))
						{
							if (isset($asset_creator_role_ref[$index]) && ! empty($asset_creator_role_ref[$index]))
							{
								$column.=",<a target='_blank' href='$asset_contributor_role_ref[$index]'>$asset_creator_role[$index]</a>";
							}
							else
								$column.=',' . $asset_creator_role[$index];
						}
						if (isset($asset_creator_role_source[$index]) && $asset_creator_role_source[$index] != '')
							$column.=' (' . $asset_creator_role_source[$index] . ')';
						$column.='<div class="clearfix"></div>';
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:120px;max-width:120px;">' . $column . '</span>';
			}
			else if ($type == 'Contributor')
			{
				$asset_contributor_name = explode(' | ', trim(str_replace('(**)', '', $asset->asset_contributor_name)));
				$asset_contributor_ref = explode(' | ', trim(str_replace('(**)', '', $asset->asset_contributor_ref)));
				$asset_contributor_affiliation = explode(' | ', trim(str_replace('(**)', '', $asset->asset_contributor_affiliation)));
				$asset_contributor_role = explode(' | ', trim(str_replace('(**)', '', $asset->asset_contributor_role)));
				$asset_contributor_role_ref = explode(' | ', trim(str_replace('(**)', '', $asset->asset_contributor_role_ref)));
				$asset_contributor_role_source = explode(' | ', trim(str_replace('(**)', '', $asset->asset_contributor_role_source)));
				$column = '';
				if (count($asset_contributor_name) > 0)
				{
					foreach ($asset_contributor_name as $index => $contributor_name)
					{

						if (isset($asset_contributor_ref[$index]) && ! empty($asset_contributor_ref[$index]))
						{
							$column.="<a target='_blank' href='$asset_contributor_ref[$index]'>$contributor_name</a>";
						}
						else
							$column.=$contributor_name;
						if (isset($asset_contributor_affiliation[$index]) && $asset_contributor_affiliation[$index] != '')
							$column.=',' . $asset_contributor_affiliation[$index];

						if (isset($asset_contributor_role[$index]) && ! empty($asset_contributor_role[$index]))
						{
							if (isset($asset_contributor_role_ref[$index]) && ! empty($asset_contributor_role_ref[$index]))
							{
								$column.=",<a target='_blank' href='$asset_contributor_role_ref[$index]'>$asset_contributor_role[$index]</a>";
							}
							else
								$column.=',' . $asset_contributor_role[$index];
						}
						if (isset($asset_contributor_role_source[$index]) && $asset_contributor_role_source[$index] != '')
							$column.=' (' . $asset_contributor_role_source[$index] . ')';
						$column.='<div class="clearfix"></div>';
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:200px;max-width:200px;">' . $column . '</span>';
			}
			else if ($type == 'Publisher')
			{
				$asset_publisher_name = explode(' | ', trim(str_replace('(**)', '', $asset->asset_publisher_name)));
				$asset_publisher_ref = explode(' | ', trim(str_replace('(**)', '', $asset->asset_publisher_ref)));
				$asset_publisher_affiliation = explode(' | ', trim(str_replace('(**)', '', $asset->asset_publisher_affiliation)));
				$asset_publisher_role = explode(' | ', trim(str_replace('(**)', '', $asset->asset_publisher_role)));
				$asset_publisher_role_ref = explode(' | ', trim(str_replace('(**)', '', $asset->asset_publisher_role_ref)));
				$asset_publisher_role_source = explode(' | ', trim(str_replace('(**)', '', $asset->asset_publisher_role_source)));
				$column = '';
				if (count($asset_publisher_name) > 0)
				{
					foreach ($asset_publisher_name as $index => $publisher_name)
					{

						if (isset($asset_publisher_ref[$index]) && ! empty($asset_publisher_ref[$index]))
						{
							$column.="<a target='_blank' href='$asset_publisher_ref[$index]'>$publisher_name</a>";
						}
						else
							$column.=$publisher_name;
						if (isset($asset_publisher_affiliation[$index]) && $asset_publisher_affiliation[$index] != '')
							$column.=',' . $asset_publisher_affiliation[$index];

						if (isset($asset_publisher_role[$index]) && ! empty($asset_publisher_role[$index]))
						{
							if (isset($asset_publisher_role_ref[$index]) && ! empty($asset_publisher_role_ref[$index]))
							{
								$column.=",<a target='_blank' href='$asset_publisher_role_ref[$index]'>$asset_publisher_role[$index]</a>";
							}
							else
								$column.=',' . $asset_publisher_role[$index];
						}
						if (isset($asset_publisher_role_source[$index]) && $asset_publisher_role_source[$index] != '')
							$column.=' (' . $asset_publisher_affiliation[$index] . ')';
						$column.='<div class="clearfix"></div>';
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:120px;max-width:120px;">' . $column . '</span>';
			}
			else if ($type == 'Coverage')
			{
				$asset_coverage = explode(' | ', trim(str_replace('(**)', '', $asset->asset_coverage)));
				$asset_coverage_type = explode(' | ', trim(str_replace('(**)', '', $asset->asset_coverage_type)));
				$column = '';
				if (count($asset_coverage) > 0)
				{
					foreach ($asset_coverage as $index => $coverage)
					{
						if (isset($asset_coverage_type[$index]) && ! empty($asset_coverage_type[$index]))
						{
							$column.= $asset_coverage_type[$index] . ':';
						}
						if (isset($coverage) && ! empty($coverage))
						{
							$column.= $coverage;
						}
						$column.='<div class="clearfix"></div>';
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:120px;max-width:120px;">' . $column . '</span>';
			}
			else if ($type == 'Audience_Level')
			{
				$asset_audience_level = explode(' | ', trim(str_replace('(**)', '', $asset->asset_audience_level)));
				$asset_audience_level_ref = explode(' | ', trim(str_replace('(**)', '', $asset->asset_audience_level_ref)));
				$asset_audience_level_source = explode(' | ', trim(str_replace('(**)', '', $asset->asset_audience_level_source)));
				$column = '';
				if (count($asset_audience_level) > 0)
				{
					foreach ($asset_audience_level as $index => $audience_level)
					{

						if (isset($asset_audience_level_ref[$index]) && ! empty($asset_audience_level_ref[$index]))
						{
							$column.="<a target='_blank' href='$asset_audience_level_ref[$index]'>$audience_level</a>";
						}
						else
							$column.=$audience_level;
						if (isset($asset_audience_level_source[$index]) && $asset_audience_level_source[$index] != '')
							$column.=' (' . $asset_audience_level_source[$index] . ')';
						$column.='<div class="clearfix"></div>';
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:120px;max-width:120px;">' . $column . '</span>';
			}
			else if ($type == 'Audience_Rating')
			{
				$asset_audience_rating = explode(' | ', trim(str_replace('(**)', '', $asset->asset_audience_rating)));
				$asset_audience_rating_ref = explode(' | ', trim(str_replace('(**)', '', $asset->asset_audience_rating_ref)));
				$asset_audience_rating_source = explode(' | ', trim(str_replace('(**)', '', $asset->asset_audience_rating_source)));
				$column = '';
				if (count($asset_audience_rating) > 0)
				{
					foreach ($asset_audience_rating as $index => $audience_rating)
					{

						if (isset($asset_audience_rating_ref[$index]) && ! empty($asset_audience_rating_ref[$index]))
						{
							$column.="<a target='_blank' href='$asset_audience_level_ref[$index]'>$audience_rating</a>";
						}
						else
							$column.=$audience_rating;
						if (isset($asset_audience_rating_source[$index]) && $asset_audience_rating_source[$index] != '')
							$column.=' (' . $asset_audience_rating_source[$index] . ')';
						$column.='<div class="clearfix"></div>';
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:300px;max-width:300px;">' . $column . '</span>';
			}
			else if ($type == 'Annotation')
			{
				$asset_annotation = explode(' | ', trim(str_replace('(**)', '', $asset->asset_annotation)));
				$asset_annotation_ref = explode(' | ', trim(str_replace('(**)', '', $asset->asset_annotation_ref)));
				$asset_annotation_type = explode(' | ', trim(str_replace('(**)', '', $asset->asset_annotation_type)));
				$column = '';
				if (count($asset_annotation) > 0)
				{
					foreach ($asset_annotation as $index => $annotation)
					{
						if (isset($asset_annotation_type[$index]) && $asset_annotation_type[$index] != '')
							$column.=$asset_annotation_type[$index] . ': ';
						if (isset($asset_annotation_ref[$index]) && ! empty($asset_annotation_ref[$index]))
						{
							$column.="<a target='_blank' href='$asset_annotation_ref[$index]'>$annotation</a>: ";
						}
						else
							$column.=$annotation;

						$column.='<div class="clearfix"></div>';
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:120px;max-width:120px;">' . $column . '</span>';
			}
			else if ($type == 'Rights')
			{
				$asset_rights = explode(' | ', trim(str_replace('(**)', '', $asset->asset_rights)));
				$asset_rights_link = explode(' | ', trim(str_replace('(**)', '', $asset->asset_rights_link)));
				$column = '';
				if (count($asset_rights) > 0)
				{
					foreach ($asset_rights as $index => $rights)
					{

						if (isset($asset_rights_link[$index]) && ! empty($asset_rights_link[$index]))
						{
							$column.="<a target='_blank' href='" . $asset_rights_link[$index] . "'>" . $rights . "</a>: ";
						}
						else
							$column.=$rights;
						$column.='<div class="clearfix"></div>';
					}
				}
				$tablesort[$main_index][] = '<span style="float:left;min-width:120px;max-width:120px;">' . $column . '</span>';
			}
		}
	}
	return $tablesort;
}

function instantiations_datatable_view($records, $column_order)
{
	$CI = & get_instance();
	$CI->load->model('sphinx_model', 'sphinx');
	$table_view = array();
	foreach ($records as $main_index => $value)
	{
		foreach ($column_order as $key => $row)
		{
			$type = $row['title'];
			if ($type == 'Organization')
			{
				$table_view[$main_index][] = '<span style="float:left;min-width:200px;max-width:200px;">' . $value->organization . '</span>';
			}
			else if ($type == 'Instantiation_ID')
			{
				$ins_identifier = explode(' | ', trim(str_replace('(**)', '', $value->instantiation_identifier)));
				$ins_identifier_src = explode(' | ', trim(str_replace('(**)', '', $value->instantiation_source)));
				$column = '';
				foreach ($ins_identifier as $index => $identifier)
				{
					$column.= '<a href="' . site_url('instantiations/detail/' . $value->id) . '">';
					$column.= $identifier;
					if (isset($ins_identifier_src[$index]) && ! empty($ins_identifier_src[$index]))
						$column.=' (' . $ins_identifier_src[$index] . ')';
					$column.= '</a>';
					$column.='<div class="clearfix"></div>';
				}
				$table_view[$main_index][] = '<span style="float:left;min-width:200px;max-width:200px;">' . $column . '</span>';
			}
			else if ($type == 'Nomination')
			{
//				if ($value->nomination_status_id == 0)
//					$status = '';
//				else
//					$status = $CI->sphinx->get_nomination_status($value->nomination_status_id)->status;
				$status = ($value->status) ? $value->status : '';
				$table_view[$main_index][] = '<span style="float:left;min-width:150px;max-width:150px;">' . $status . '</span>';
			}
			else if ($type == 'Instantiation\'s_Asset_Title')
			{

				$asset_title_type = trim(str_replace('(**)', '', $value->asset_title_type));
				$asset_title_type = explode(' | ', $asset_title_type);
				$asset_title = trim(str_replace('(**)', '', $value->asset_title));
				$asset_title = explode(' | ', $asset_title);
				$asset_title_ref = trim(str_replace('(**)', '', $value->asset_title_ref));
				$asset_title_ref = explode(' | ', $asset_title_ref);
				$column = '';
				foreach ($asset_title as $index => $title)
				{
					if (isset($asset_title_type[$index]) && $asset_title_type[$index] != '')
						$column.= $asset_title_type[$index] . ': ';
					if (isset($asset_title_ref[$index]))
					{
						if ($asset_title_ref[$index] != '')
						{
							$column.="<a target='_blank' href='$asset_title_ref[$index]'>$title</a>: ";
							$column.=' (' . $asset_title_ref[$index] . ')';
						}
						else
							$column.=$title;
					}
					else
						$column.=$title;



					$column.='<div class="clearfix"></div>';
				}
				$table_view[$main_index][] = '<span style="float:left;min-width:300px;max-width:300px;">' . $column . '</span>';
			}
			else if ($type == 'Media_Type')
			{
				$table_view[$main_index][] = '<span style="float:left;min-width:150px;max-width:150px;">' . $value->media_type . '</span>';
			}
			else if ($type == 'Generation')
			{
				$table_view[$main_index][] = '<span style="float:left;min-width:150px;max-width:150px;">' . $value->facet_generation . '</span>';
			}
			else if ($type == 'Format')
			{
				if ( ! empty($value->physical_format_name))
				{
					$column = 'physical: ' . $value->physical_format_name;
				}
				if ( ! empty($value->digital_format_name))
				{
					$column = 'digital: ' . $value->digital_format_name;
				}
//				$column = $value->format_type;
//				if ($value->format_name != '')
//					$column.=': ' . $value->format_name;
				$table_view[$main_index][] = '<span style="float:left;min-width:150px;max-width:150px;">' . $column . '</span>';
				$column = NULL;
			}
			else if ($type == 'Duration')
			{
				if ( ! empty($value->actual_duration))
					$duration = date('H:i:s', strtotime($value->actual_duration));
				else if ( ! empty($value->projected_duration))
					$duration = date('H:i:s', strtotime($value->projected_duration));
				$table_view[$main_index][] = '<span style="float:left;min-width:150px;max-width:150px;">' . $duration . '</span>';
				$duration = NULL;
			}
			else if ($type == 'Date')
			{
				$date = ($value->dates == 0) ? '' : date('Y-m-d', $value->dates) . ' ' . $value->date_type;
				$table_view[$main_index][] = '<span style="float:left;min-width:150px;max-width:150px;">' . $date . '</span>';
				$date = NULL;
			}
			else if ($type == 'File_size')
			{
				$file_size_unit = ($value->file_size_unit_of_measure) ? $value->file_size_unit_of_measure : '';
				$file_size = $value->file_size . ' ' . $file_size_unit;
				$table_view[$main_index][] = '<span style="float:left;min-width:150px;max-width:150px;">' . $file_size . '</span>';
				$file_size = NULL;
			}
			else if ($type == 'Colors')
			{
				$color = ($value->color) ? $value->color : '';
				$table_view[$main_index][] = '<span style="float:left;min-width:150px;max-width:150px;">' . $color . '</span>';
			}
			else if ($type == 'Language')
			{
				$language = ($value->language) ? $value->language : '';
				$table_view[$main_index][] = '<span style="float:left;min-width:150px;max-width:150px;">' . $language . '</span>';
			}
		}
	}
	return $table_view;
}

function standalone_datatable_view($records)
{
	$table_view = array();
	foreach ($records as $main_index => $value)
	{

		$table_view[$main_index][] = '<span style="float:left;min-width:200px;max-width:200px;">' . $value->organization . '</span>';


		$ins_identifier = explode(' | ', trim(str_replace('(**)', '', $value->instantiation_identifier)));
		$ins_identifier_src = explode(' | ', trim(str_replace('(**)', '', $value->instantiation_source)));
		$column = '';
		foreach ($ins_identifier as $index => $identifier)
		{

			$column.= $identifier;
			if (isset($ins_identifier_src[$index]) && ! empty($ins_identifier_src[$index]))
				$column.=' (' . $ins_identifier_src[$index] . ')';

			$column.='<div class="clearfix"></div>';
		}
		$table_view[$main_index][] = '<span style="float:left;min-width:200px;max-width:200px;">' . $column . '</span>';

		$status = ($value->status) ? $value->status : '';
		$table_view[$main_index][] = '<span style="float:left;min-width:100px;max-width:100px;">' . $status . '</span>';

		$asset_title_type = trim(str_replace('(**)', '', $value->asset_title_type));
		$asset_title_type = explode(' | ', $asset_title_type);
		$asset_title = trim(str_replace('(**)', '', $value->asset_title));
		$asset_title = explode(' | ', $asset_title);
		$asset_title_ref = trim(str_replace('(**)', '', $value->asset_title_ref));
		$asset_title_ref = explode(' | ', $asset_title_ref);
		$column = '';
		foreach ($asset_title as $index => $title)
		{
			if (isset($asset_title_type[$index]) && $asset_title_type[$index] != '')
				$column.= $asset_title_type[$index] . ': ';
			if (isset($asset_title_ref[$index]))
			{
				if ($asset_title_ref[$index] != '')
				{
					$column.="<a target='_blank' href='$asset_title_ref[$index]'>$title</a>: ";
					$column.=' (' . $asset_title_ref[$index] . ')';
				}
				else
					$column.=$title;
			}
			else
				$column.=$title;



			$column.='<div class="clearfix"></div>';
		}

		$table_view[$main_index][] = '<span style="float:left;min-width:300px;max-width:300px;">' . $column . '</span>';
		$table_view[$main_index][] = '<span style="float:left;min-width:100px;max-width:100px;">' . $value->generation . '</span>';
		$column = $value->format_type;
		if ($value->format_name != '')
			$column.=': ' . $value->format_name;
		$table_view[$main_index][] = '<span style="float:left;min-width:100px;max-width:100px;">' . $column . '</span>';
		$date = ($value->dates == 0) ? '' : date('Y-m-d', $value->dates) . ' ' . $value->date_type;
		$table_view[$main_index][] = '<span style="float:left;min-width:150px;max-width:150px;">' . $date . '</span>';
		$file_size_unit = ($value->file_size_unit_of_measure) ? $value->file_size_unit_of_measure : '';
		$file_size = $value->file_size . ' ' . $file_size_unit;
		$table_view[$main_index][] = '<span style="float:left;min-width:100px;max-width:100px;">' . $file_size . '</span>';
		$table_view[$main_index][] = '<span style="float:left;min-width:100px;max-width:100px;">' . $value->media_type . '</span>';






		$duration = ($value->actual_duration) ? date('H:i:s', strtotime($value->actual_duration)) : date('H:i:s', strtotime($value->projected_duration));
		$table_view[$main_index][] = '<span style="float:left;min-width:100px;max-width:100px;">' . $duration . '</span>';





		$color = ($value->color) ? $value->color : '';
		$table_view[$main_index][] = '<span style="float:left;min-width:100px;max-width:100px;">' . $color . '</span>';

		$language = ($value->language) ? $value->language : '';
		$table_view[$main_index][] = '<span style="float:left;min-width:100px;max-width:100px;">' . $language . '</span>';
	}
	return $table_view;
}

?>