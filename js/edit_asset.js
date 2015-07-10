$(function() {
//	$("#asset_type").multiselect({
//		noneSelectedText: 'Select Asset Type',
//		selectedList: 3
//	});
//	$('input[name="asset_date[]"]').datepicker({"dateFormat": 'yy-mm-dd'});


	$('input[name="asset_date[]"]').datepick({dateFormat: 'yyyy-mm-dd', yearRange: 'c-500:c+20',
		renderer: $.datepick.themeRollerRenderer, pickerClass: 'ui-datepicker'});
	
	$('input[name="asset_identifier_source[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=identifiers&column=identifier_source",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_title_source[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=asset_titles&column=title_source",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_subject[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=subjects&column=subject",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_subject_source[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=subjects&column=subject_source",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_genre_source[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=genres&column=genre_source",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_audience_level_source[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=audience_levels&column=audience_level_source",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_annotation_type[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=annotations&column=annotation_type",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_relation_identifier[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=assets_relations&column=relation_identifier",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_relation_source[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=relation_types&column=relation_type_source",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_creator_name[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=creators&column=creator_name",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_creator_affiliation[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=creators&column=creator_affiliation",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_creator_role_source[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=creator_roles&column=creator_role_source",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_contributor_name[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=contributors&column=contributor_name",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_contributor_affiliation[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=contributors&column=contributor_affiliation",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_contributor_role_source[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=contributor_roles&column=contributor_role_source",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_publisher[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=publishers&column=publisher",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_publisher_affiliation[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=publishers&column=publisher_affiliation",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_publisher_role_source[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=publisher_roles&column=publisher_role_source",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="asset_genre[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=pbcore_picklist_value_by_type&column=16",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
});
function removeElement(elementID, type) {
	$(elementID).delay(200).fadeOut(1000);
	$(elementID).animate({
		"opacity": "0",
	}, {
		"complete": function() {
			$(elementID).remove();
			if ($('.remove_' + type).length == 0) {
				$('#add_' + type).html(' ADD ' + type.replace(/_/g, " ").toUpperCase());
			}
			else {
				$('#add_' + type).html(' ADD ANOTHER ' + type.replace(/_/g, " ").toUpperCase());
			}
		}
	});
}
function addElement(elementID, type) {
	var number = 10 + Math.floor(Math.random() * 100);
	common = less = false;
	if (elementID == '#main_local_id') {


		html = '<div id="remove_local_' + number + '" class="remove_local_id">' +
		'<div class="edit_form_div"><div><p>Local ID: <span class="label_star"> *</span> </p>' +
		'<p><input type="text" id="asset_identifier_' + number + '" name="asset_identifier[]" value="" /></p></div>' +
		'<div><p>ID Source: <span class="label_star"> *</span> </p>' +
		'<p><input type="text" id="asset_identifier_source_' + number + '" name="asset_identifier_source[]" value="" /></p></div>' +
		'<div><p>ID Ref:</p>' +
		'<p><input type="text" id="asset_identifier_ref_' + number + '" name="asset_identifier_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span></p></div>' +
		'</div><div class="remove_element" onclick="removeElement(\'#remove_local_' + number + '\', \'local_id\');"><img src="/images/remove-item.png" /></div></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div>';


		$(elementID).append(html);
		$('input[name="asset_identifier_source[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=identifiers&column=identifier_source",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
	}
	else if (elementID == '#main_date') {
		dateTypes = '<option value="">Select</option>';

		for (cnt in pbcoreDateTypes)
		{
			if (pbcoreDateTypes[cnt]['display_value'] == 1 && common == false) {
				common = true;
				dateTypes += '<optgroup label="Commonly Used">Commonly Used</optgroup>';
			}
			else if (pbcoreDateTypes[cnt]['display_value'] == 2 && less == false) {
				less = true;
				dateTypes += '<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>';
			}
			dateTypes += '<option value= "' + pbcoreDateTypes[cnt]['value'] + '">' + pbcoreDateTypes[cnt]['value'] + '</option>';
		}


		html = '<div id="remove_date_' + number + '" class="remove_date"><div class="edit_form_div">' +
		'<div><p>Asset Date:</p><p><input type="text" id="asset_date_' + number + '" name="asset_date[]" value="" /></p></div>' +
		'<div><p>Asset Date Type:</p><p><select id="asset_date_type_' + number + '" name="asset_date_type[]">' +
		dateTypes +
		'</select></p></div></div>' +
		'<div class="remove_element" onclick="removeElement(\'#remove_date_' + number + '\', \'date\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
		$('input[name="asset_date[]"]').datepick({dateFormat: 'yyyy-mm-dd', yearRange: 'c-500:c+20',
		renderer: $.datepick.themeRollerRenderer, pickerClass: 'ui-datepicker'});
	}
	else if (elementID == '#main_title') {
		titleTypes = '<option value="">Select</option>';

		for (cnt in pbcoreTitleTypes)
		{
			if (pbcoreTitleTypes[cnt]['display_value'] == 1 && common == false) {
				common = true;
				titleTypes += '<optgroup label="Commonly Used">Commonly Used</optgroup>';
			}
			else if (pbcoreTitleTypes[cnt]['display_value'] == 2 && less == false) {
				less = true;
				titleTypes += '<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>';
			}
			titleTypes += '<option value= "' + pbcoreTitleTypes[cnt]['value'] + '">' + pbcoreTitleTypes[cnt]['value'] + '</option>';
		}
		html = '<div id="remove_title_' + number + '" class="remove_title"><div class="edit_form_div">' +
		'<div><p>Title: <span class="label_star"> *</span> </p><p><textarea id="asset_title_<?php echo $index; ?>" name="asset_title[]"></textarea>' +
		'</p></div><div><p>Title Type:</p><p><select id="asset_title_type_<?php echo $index; ?>" name="asset_title_type[]">' +
		titleTypes + '</select></p></div><div><p>Title Source:</p>' +
		'<p><input type="text" id="asset_title_source_' + number + '" name="asset_title_source[]" value="" /></p>' +
		'</div><div><p>Title Ref:</p><p><input type="text" id="asset_title_ref_' + number + '" name="asset_title_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span></p>' +
		'</div></div><div class="remove_element" onclick="removeElement(\'#remove_title_' + number + '\', \'title\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
		$('input[name="asset_title_source[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=asset_titles&column=title_source",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
	}
	else if (elementID == '#main_subject') {
		subjectType = '<option value="">Select</option>';

		for (cnt in pbcoreSubjectTypes)
		{
			subjectType += '<option value= "' + pbcoreSubjectTypes[cnt]['subject_type'] + '">' + pbcoreSubjectTypes[cnt]['subject_type'] + '</option>';
		}

		html = '<div id="remove_subject_' + number + '" class="remove_subject"><div class="edit_form_div"><div>' +
		'<p>Subject:</p><p><input type="text" id="asset_subject_' + number + '" name="asset_subject[]" value=""/></p></div>' +
		'<div><p>Subject Type:</p><p><select id="asset_subject_type_' + number + '" name="asset_subject_type[]">' +
		subjectType + '</select></p></div><div><p>Subject Source:</p>' +
		'<p><input type="text" id="asset_subject_source_' + number + '" name="asset_subject_source[]" value="" /></p></div>' +
		'<div><p>Subject Ref:</p><p><input type="text" id="asset_subject_ref_' + number + '" name="asset_subject_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span></p></div>' +
		'</div><div class="remove_element" onclick="removeElement(\'#remove_subject_' + number + '\', \'subject\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
		$('input[name="asset_subject[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=subjects&column=subject",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
		$('input[name="asset_subject_source[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=subjects&column=subject_source",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
	}
	else if (elementID == '#main_description') {
		descriptionType = '<option value="">Select</option>';

		for (cnt in pbcoreDescriptionTypes)
		{
			if (pbcoreDescriptionTypes[cnt]['display_value'] == 1 && common == false) {
				common = true;
				descriptionType += '<optgroup label="Commonly Used">Commonly Used</optgroup>';
			}
			else if (pbcoreDescriptionTypes[cnt]['display_value'] == 2 && less == false) {
				less = true;
				descriptionType += '<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>';
			}
			descriptionType += '<option value= "' + pbcoreDescriptionTypes[cnt]['value'] + '">' + pbcoreDescriptionTypes[cnt]['value'] + '</option>';
		}
		html = '<div id="remove_description_' + number + '" class="remove_description"><div class="edit_form_div">' +
		'<div><p>Description: <span class="label_star"> *</span> </p><p><textarea id="asset_description_' + number + '" name="asset_description[]"></textarea>' +
		'</p></div><div><p>Description Type:</p><p><select id="asset_description_type_' + number + '" name="asset_description_type[]">' +
		descriptionType + '</select></p></div></div>' +
		'<div class="remove_element" onclick="removeElement(\'#remove_description_' + number + '\', \'description\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
	}
	else if (elementID == '#main_genre') {
		html = '<div id="remove_genre_' + number + '" class="remove_genre"><div class="edit_form_div"><div>' +
		'<p>Genre:</p><p><input type="text" id="asset_genre_' + number + '" name="asset_genre[]" value="" /></p></div>' +
		'<div><p>Genre Source:</p><p><input type="text" id="asset_genre_source_' + number + '" name="asset_genre_source[]" value="" /></p>' +
		'</div><div><p>Genre Ref:</p><p><input type="text" id="asset_genre_ref_' + number + '" name="asset_genre_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span></p>' +
		'</div></div><div class="remove_element" onclick="removeElement(\'#remove_genre_' + number + '\', \'genre\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
		$('input[name="asset_genre_source[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=genres&column=genre_source",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
		$('input[name="asset_genre[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=pbcore_picklist_value_by_type&column=16",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
	}
	else if (elementID == '#main_coverage') {
		html = '<div id="remove_coverage_' + number + '" class="remove_coverage"><div class="edit_form_div"><div>' +
		'<p>Coverage:</p><p><input type="text" id="asset_coverage_' + number + '" name="asset_coverage[]" value="" /></p>' +
		'</div><div><p>Coverage Type:</p><p><select id="asset_coverage_type_' + number + '" name="asset_coverage_type[]">' +
		'<option value="spatial">spatial</option><option value="temporal">temporal</option>' +
		'</select></p></div></div><div class="remove_element" onclick="removeElement(\'#remove_coverage_' + number + '\', \'coverage\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
	}
	else if (elementID == '#main_audience_level') {
		audienceLevel = '<option value="">Select</option>';

		for (cnt in pbcoreAudienceLevel)
		{
			if (pbcoreAudienceLevel[cnt]['display_value'] == 1 && common == false) {
				common = true;
				audienceLevel += '<optgroup label="Commonly Used">Commonly Used</optgroup>';
			}
			else if (pbcoreAudienceLevel[cnt]['display_value'] == 2 && less == false) {
				less = true;
				audienceLevel += '<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>';
			}
			audienceLevel += '<option value= "' + pbcoreAudienceLevel[cnt]['value'] + '">' + pbcoreAudienceLevel[cnt]['value'] + '</option>';
		}
		html = '<div id="remove_audience_level_' + number + '" class="remove_audience_level"><div class="edit_form_div">' +
		'<div><p>Audience Level:</p><p><select id="asset_audience_level_' + number + '" name="asset_audience_level[]">' +
		audienceLevel + '</select></p></div><div><p> Audience Level Source:</p>' +
		'<p><input type="text" id="asset_audience_level_source_' + number + '" name="asset_audience_level_source[]" value="" />' +
		'</p></div><div><p> Audience Level Ref:</p><p><input type="text" id="asset_audience_level_ref_' + number + '" name="asset_audience_level_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>' +
		'</p></div></div><div class="remove_element" onclick="removeElement(\'#remove_audience_level_' + number + '\', \'audience_level\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
		$('input[name="asset_audience_level_source[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=audience_levels&column=audience_level_source",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
	}
	else if (elementID == '#main_audience_rating') {
		audienceRating = '<option value="">Select</option>';

		for (cnt in pbcoreAudienceRating)
		{
			if (pbcoreAudienceRating[cnt]['display_value'] == 1 && common == false) {
				common = true;
				audienceRating += '<optgroup label="Commonly Used">Commonly Used</optgroup>';
			}
			else if (pbcoreAudienceRating[cnt]['display_value'] == 2 && less == false) {
				less = true;
				audienceRating += '<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>';
			}
			audienceRating += '<option value= "' + pbcoreAudienceRating[cnt]['value'] + '">' + pbcoreAudienceRating[cnt]['value'] + '</option>';
		}

		html = '<div id="remove_audience_rating_' + number + '" class="remove_audience_rating"><div class="edit_form_div"><div>' +
		'<p>Audience Rating:</p><p><select id="asset_audience_rating_' + number + '" name="asset_audience_rating[]">' +
		audienceRating + '</select></p></div><div><p> Audience Rating Source:</p>' +
		'<p><select id="asset_audience_rating_source_' + number + '" name="asset_audience_rating_source[]"><option value="">Select</option>' +
		'<option value="MPAA" >MPAA</option><option value="TV Parental Guidelines" >TV Parental Guidelines</option></select></p></div>' +
		'<div><p> Audience Rating Ref:</p><p><select id="asset_audience_rating_ref_' + number + '" name="asset_audience_rating_ref[]">' +
		'<option value="">Select</option><option value="http://www.filmratings.com">http://www.filmratings.com</option>' +
		'<option value="http://www.tvguidelines.org/ratings.htm">http://www.tvguidelines.org/ratings.htm</option></select></p></div></div>' +
		'<div class="remove_element" onclick="removeElement(\'#remove_audience_rating_' + number + '\', \'audience_rating\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
	}
	else if (elementID == '#main_annotation') {

		html = '<div id="remove_annotation_' + number + '" class="remove_annotation"><div class="edit_form_div"><div>' +
		'<p>Annotation:</p><p><input type="text" id="asset_annotation_' + number + '" name="asset_annotation[]" value="" />' +
		'</p></div><div><p> Annotation Type:</p><p><input type="text" id="asset_annotation_type_' + number + '" name="asset_annotation_type[]" value="" />' +
		'</p></div><div><p> Annotation Ref:</p><p><input type="text" id="asset_annotation_ref_' + number + '" name="asset_annotation_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>' +
		'</p></div></div><div class="remove_element" onclick="removeElement(\'#remove_annotation_' + number + '\', \'annotation\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
		$('input[name="asset_annotation_type[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=annotations&column=annotation_type",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
	}
	else if (elementID == '#main_relation') {
		relationType = '<option value="">Select</option>';

		for (cnt in pbcoreRelationTypes)
		{
			if (pbcoreRelationTypes[cnt]['display_value'] == 1 && common == false) {
				common = true;
				relationType += '<optgroup label="Commonly Used">Commonly Used</optgroup>';
			}
			else if (pbcoreRelationTypes[cnt]['display_value'] == 2 && less == false) {
				less = true;
				relationType += '<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>';
			}
			relationType += '<option value= "' + pbcoreRelationTypes[cnt]['value'] + '">' + pbcoreRelationTypes[cnt]['value'] + '</option>';
		}
		html = '<div id="remove_relation_' + number + '" class="remove_relation"><div class="edit_form_div"><div><p>Relation:</p>' +
		'<p><input type="text" id="asset_relation_identifier_' + number + '" name="asset_relation_identifier[]" value="" /></p></div>' +
		'<div><p> Relation Type:</p><p><select id="asset_relation_type_' + number + '" name="asset_relation_type[]">' +
		relationType + '</select></p></div><div><p> Relation Source:</p><p>' +
		'<input type="text" id="asset_relation_source_' + number + '" name="asset_relation_source[]" value="" /></p></div>' +
		'<div><p> Relation Ref:</p><p><input type="text" id="asset_relation_ref_' + number + '" name="asset_relation_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>' +
		'</p></div></div><div class="remove_element" onclick="removeElement(\'#remove_relation_' + number + '\', \'relation\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
		$('input[name="asset_relation_identifier[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=assets_relations&column=relation_identifier",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
		$('input[name="asset_relation_source[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=relation_types&column=relation_type_source",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
	}
	else if (elementID == '#main_creator') {
		creatorRoles = '<option value="">Select</option>';

		for (cnt in pbcoreCreatorRoles)
		{
			if (pbcoreCreatorRoles[cnt]['display_value'] == 1 && common == false) {
				common = true;
				creatorRoles += '<optgroup label="Commonly Used">Commonly Used</optgroup>';
			}
			else if (pbcoreCreatorRoles[cnt]['display_value'] == 2 && less == false) {
				less = true;
				creatorRoles += '<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>';
			}
			creatorRoles += '<option value= "' + pbcoreCreatorRoles[cnt]['value'] + '">' + pbcoreCreatorRoles[cnt]['value'] + '</option>';
		}
		html = '<div id="remove_creator_' + number + '" class="remove_creator"><div class="edit_form_div"><div>' +
		'<p>Creator:</p><p><input type="text" id="asset_creator_name_' + number + '" name="asset_creator_name[]" value="" /></p></div>' +
		'<div><p>Creator Affiliation:</p><p><input type="text" id="asset_creator_affiliation_' + number + '" name="asset_creator_affiliation[]" value="" /></p></div>' +
		'<div><p>Creator Ref:</p><p><input type="text" id="asset_creator_ref_' + number + '" name="asset_creator_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span></p></div>' +
		'<div><p> Creator Role:</p><p><select id="asset_creator_role_' + number + '" name="asset_creator_role[]">' +
		creatorRoles + '</select></p></div><div><p> Creator Role Source:</p><p>' +
		'<input type="text" id="asset_creator_role_source_' + number + '" name="asset_creator_role_source[]" value="" /></p></div><div><p> Creator Role Ref:</p>' +
		'<p><input type="text" id="asset_creator_role_ref_' + number + '" name="asset_creator_role_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span></p></div></div>' +
		'<div class="remove_element" onclick="removeElement(\'#remove_creator_' + number + '\', \'creator\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
		$('input[name="asset_creator_name[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=creators&column=creator_name",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
		$('input[name="asset_creator_affiliation[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=creators&column=creator_affiliation",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
		$('input[name="asset_creator_role_source[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=creator_roles&column=creator_role_source",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
	}
	else if (elementID == '#main_contributor') {
		contributorRoles = '<option value="">Select</option>';

		for (cnt in pbcoreContributorRoles)
		{
			if (pbcoreContributorRoles[cnt]['display_value'] == 1 && common == false) {
				common = true;
				contributorRoles += '<optgroup label="Commonly Used">Commonly Used</optgroup>';
			}
			else if (pbcoreContributorRoles[cnt]['display_value'] == 2 && less == false) {
				less = true;
				contributorRoles += '<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>';
			}
			contributorRoles += '<option value= "' + pbcoreContributorRoles[cnt]['value'] + '">' + pbcoreContributorRoles[cnt]['value'] + '</option>';
		}
		html = '<div id="remove_contributor_' + number + '" class="remove_contributor"><div class="edit_form_div"><div><p>Contributor:</p>' +
		'<p><input type="text" id="asset_contributor_name_' + number + '" name="asset_contributor_name[]" value="" /></p></div>' +
		'<div><p>Contributor Affiliation:</p><p><input type="text" id="asset_contributor_affiliation_' + number + '" name="asset_contributor_affiliation[]" value="" /></p>' +
		'</div><div><p>Contributor Ref:</p><p><input type="text" id="asset_contributor_ref_' + number + '" name="asset_contributor_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span></p>' +
		'</div><div><p> Contributor Role:</p><p><select id="asset_contributor_role_' + number + '" name="asset_contributor_role[]">' +
		contributorRoles + '</select></p></div><div><p> Contributor Role Source:</p>' +
		'<p><input type="text" id="asset_contributor_role_source_' + number + '" name="asset_contributor_role_source[]" value="" /></p>' +
		'</div><div><p> Contributor Role Ref:</p><p><input type="text" id="asset_contributor_role_ref_' + number + '" name="asset_contributor_role_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>' +
		'</p></div></div><div class="remove_element" onclick="removeElement(\'#remove_contributor_' + number + '\', \'contributor\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
		$('input[name="asset_contributor_name[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=contributors&column=contributor_name",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
		$('input[name="asset_contributor_affiliation[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=contributors&column=contributor_affiliation",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
		$('input[name="asset_contributor_role_source[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=contributor_roles&column=contributor_role_source",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
	}
	else if (elementID == '#main_publisher') {
		publisherRoles = '<option value="">Select</option>';

		for (cnt in pbcorePublisherRoles)
		{
			if (pbcorePublisherRoles[cnt]['display_value'] == 1 && common == false) {
				common = true;
				publisherRoles += '<optgroup label="Commonly Used">Commonly Used</optgroup>';
			}
			else if (pbcorePublisherRoles[cnt]['display_value'] == 2 && less == false) {
				less = true;
				publisherRoles += '<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>';
			}
			publisherRoles += '<option value= "' + pbcorePublisherRoles[cnt]['value'] + '">' + pbcorePublisherRoles[cnt]['value'] + '</option>';
		}

		html = '<div id="remove_publisher_' + number + '" class="remove_publisher"><div class="edit_form_div"><div><p>Publisher:</p>' +
		'<p><input type="text" id="asset_publisher_' + number + '" name="asset_publisher[]" value="" /></p></div>' +
		'<div><p>Publisher Affiliation:</p><p><input type="text" id="asset_publisher_affiliation_' + number + '" name="asset_publisher_affiliation[]" value="" /></p></div>' +
		'<div><p>Publisher Ref:</p><p><input type="text" id="asset_publisher_ref_' + number + '" name="asset_publisher_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span></p></div>' +
		'<div><p> Publisher Role:</p><p><select id="asset_publisher_role_' + number + '" name="asset_publisher_role[]">' +
		publisherRoles + '</select></p></div><div><p> Publisher Role Source:</p>' +
		'<p><input type="text" id="asset_publisher_role_source_' + number + '" name="asset_publisher_role_source[]" value="" /></p></div>' +
		'<div><p> Publisher Role Ref:</p><p><input type="text" id="asset_publisher_role_ref_' + number + '" name="asset_publisher_role_ref[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>' +
		'</p></div></div><div class="remove_element" onclick="removeElement(\'#remove_publisher_' + number + '\', \'publisher\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
		$('input[name="asset_publisher[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=publishers&column=publisher",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
		$('input[name="asset_publisher_affiliation[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=publishers&column=publisher_affiliation",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
		$('input[name="asset_publisher_role_source[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=publisher_roles&column=publisher_role_source",
			minLength: 1,
			delay: 100,
			enable: true,
			cacheLength: 1
		});
	}
	else if (elementID == '#main_right') {
		html = '<div id="remove_right_' + number + '" class="remove_right"><div class="edit_form_div"><div>' +
		'<p>Right:</p><p><input type="text" id="asset_rights_' + number + '" name="asset_rights[]" value="" /></p></div>' +
		'<div><p> Right Link:</p><p><input type="text" id="asset_right_link_' + number + '" name="asset_right_link[]" value="" /><span class="help-block">Must be a valid URI/URL (e.g. http://www.example.com)</span>' +
		'</p></div></div><div class="remove_element" onclick="removeElement(\'#remove_right_' + number + '\', \'right\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
	}
	else if (elementID == '#main_type') {
		assetTypes = '<option value="">Select</option>';
		for (cnt in pbcoreAssetTypes)
		{
			if (pbcoreAssetTypes[cnt]['display_value'] == 1 && common == false) {
				common = true;
				assetTypes += '<optgroup label="Commonly Used">Commonly Used</optgroup>';
			}
			else if (pbcoreAssetTypes[cnt]['display_value'] == 2 && less == false) {
				less = true;
				assetTypes += '<optgroup label="Less Commonly Used">Less Commonly Used</optgroup>';
			}
			assetTypes += '<option value= "' + pbcoreAssetTypes[cnt]['value'] + '">' + pbcoreAssetTypes[cnt]['value'] + '</option>';
		}
		html = '<div id="remove_type_' + number + '" class="remove_type"><div class="edit_form_div"><div><p>Asset Type:</p></div><div><p>' +
		'<select id="asset_type_' + number + '" name="asset_type[]">' +
		assetTypes + '</select></p></div></div>' +
		'<div class="remove_element" onclick="removeElement(\'#remove_type_' + number + '\', \'type\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
	}




	if ($('.remove_' + type).length == 0) {
		$('#add_' + type).html(' ADD ' + type.replace(/_/g, " ").toUpperCase());
	}
	else {
		$('#add_' + type).html(' ADD ANOTHER ' + type.replace(/_/g, " ").toUpperCase());
	}
}
function validateForm() {
	var isValid = true;
	var field = new Array('asset_identifier', 'asset_identifier_source', 'asset_title', 'asset_description');
	for (cnt in field) {
		type = 'input';
		if (cnt > 1)
			type = 'textarea';
		$('' + type + '[name="' + field[cnt] + '[]"]').each(function() {

			if (isValid) {
				if ($(this).length > 0) {

					if ($.trim($(this).val()) == "") {
						$('body').animate({
							scrollTop: $(this).parent().parent().offset().top - 100

						}, 'slow');
						$(this).parent().parent().addClass('error-div');
						isValid = false;

					}
					else {
						$(this).parent().parent().removeClass('error-div');
					}
				}
				else {
					splitName = field[cnt].split('_');
					mainElementName = '#main_' + splitName[1];
					$('body').animate({
						scrollTop: $(mainElementName).top - 100

					}, 'slow');
					isValid = false;
				}
			}
		});
	}
	if (isValid) {
		var name = new Array('asset_identifier_ref', 'asset_title_ref', 'asset_subject_ref', 'asset_genre_ref',
		'asset_audience_level_ref', 'asset_annotation_ref', 'asset_relation_ref', 'asset_creator_ref', 'asset_creator_role_ref',
		'asset_contributor_ref', 'asset_contributor_role_ref', 'asset_publisher_ref', 'asset_publisher_role_ref', 'asset_right_link');
		for (cnt in name) {
			$('input[name="' + name[cnt] + '[]"]').each(function() {
				var urlregex = new RegExp(
				"^(https?:\/\/|ftp:\/\/)?((www|id\.loc|lccn\.loc|metadataregistry|ebu|w3|loc|cv\.iptc|www-tc.pbs|getty|geonames)\.([0-9A-Za-z]))|(www3\.ebu\.ch)");
				/* was "^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)" */
				if ($(this).val() != '') {
					if (!urlregex.test($(this).val())) {

						$('body').animate({
							scrollTop: $(this).parent().parent().offset().top - 100

						}, 'slow');
						$(this).parent().parent().addClass('error-div');
						isValid = false;
						return false;

					}
					else {
						$(this).parent().parent().removeClass('error-div');
					}
				}

			});
		}
	}

	if (isValid)
		$('#edit_asset_form').submit();

}
