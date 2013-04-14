$(function() {
	$("#asset_type").multiselect({
		noneSelectedText: 'Select Asset Type',
		selectedList: 3
	});
	$('input[name="asset_date[]"]').datepicker({"dateFormat": 'yy-mm-dd'});
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
			else
				$('#add_' + type).html(' ADD ANOTHER ' + type.replace(/_/g, " ").toUpperCase());
		}
	});
}
function addElement(elementID) {
	var number = 10 + Math.floor(Math.random() * 100);
	if (elementID == '#main_local_id') {


		html = '<div id="remove_local_' + number + '" class="remove_local_id">' +
		'<div class="edit_form_div"><div><p>Local ID:</p>' +
		'<p><input id="asset_identifier_' + number + '" name="asset_identifier[]" value="" /></p></div>' +
		'<div><p>ID Source:</p>' +
		'<p><input id="asset_identifier_source_' + number + '" name="asset_identifier_source[]" value="" /></p></div>' +
		'<div><p>ID Ref:</p>' +
		'<p><input id="asset_identifier_ref_' + number + '" name="asset_identifier_ref[]" value="" /></p></div>' +
		'</div><div class="remove_element" onclick="removeElement(\'#remove_local_' + number + '\', \'local_id\');"><img src="/images/remove-item.png" /></div></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div>'


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
		dateTypes = '';

		for (cnt in pbcoreDateTypes)
		{
			dateTypes += '<option value= "' + pbcoreDateTypes[cnt]['value'] + '">' + pbcoreDateTypes[cnt]['value'] + '</option>';
		}


		html = '<div id="remove_date_' + number + '" class="remove_date"><div class="edit_form_div">' +
		'<div><p>Asset Date:</p><p><input id="asset_date_' + number + '" name="asset_date[]" value="" /></p></div>' +
		'<div><p>Asset Date Type:</p><p><select id="asset_date_type_' + number + '" name="asset_date_type[]">' +
		'<option value="">Select Date Type</option>' +
		dateTypes +
		'</select></p></div></div>' +
		'<div class="remove_element" onclick="removeElement(\'#remove_date_' + number + '\', \'date\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
		$('input[name="asset_date[]"]').datepicker({"dateFormat": 'yy-mm-dd'});
	}
	else if (elementID == '#main_title') {
		titleTypes = '';

		for (cnt in pbcoreTitleTypes)
		{
			titleTypes += '<option value= "' + pbcoreTitleTypes[cnt]['value'] + '">' + pbcoreTitleTypes[cnt]['value'] + '</option>';
		}
		html = '<div id="remove_title_' + number + '" class="remove_title"><div class="edit_form_div">' +
		'<div><p>Title:</p><p><textarea id="asset_title_<?php echo $index; ?>" name="asset_title[]"></textarea>' +
		'</p></div><div><p>Title Type:</p><p><select id="asset_title_type_<?php echo $index; ?>" name="asset_title_type[]">' +
		'<option value="">Select Title Type</option>' + titleTypes + '</select></p></div><div><p>Title Source:</p>' +
		'<p><input id="asset_title_source_' + number + '" name="asset_title_source[]" value="" /></p>' +
		'</div><div><p>Title Ref:</p><p><input id="asset_title_ref_' + number + '" name="asset_title_ref[]" value="" /></p>' +
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
}