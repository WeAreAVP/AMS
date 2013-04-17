$(function() {

	$('input[name="instantiation_id_source[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=instantiation_identifier&column=instantiation_source",
		minLength: 1,
		delay: 300,
		enable: true,
		cacheLength: 1


	});
	$("#language").autocomplete({
		source: site_url + "autocomplete/values?table=language_lookup&column=Print_Name",
		minLength: 0,
		delay: 100,
		enable: true,
		cacheLength: 3


	});
	$('input[name="annotation_type[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=instantiation_annotations&column=annotation_type",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="relation_identifier[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=instantiation_relations&column=relation_identifier",
		minLength: 1,
		delay: 100,
		enable: true,
		cacheLength: 1
	});
	$('input[name="relation_source[]"]').autocomplete({
		source: site_url + "autocomplete/values?table=relation_types&column=relation_type_source",
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
	if (elementID == '#main_instantiation_id') {


		html = '<div id="remove_instantiation_id_' + number + '" class="remove_instantiation_id"><div class="edit_form_div"><div><p>INSTANTIATION ID:</p><p>' +
		'<input type="text" id="instantiation_id_identifier_' + number + '" name="instantiation_id_identifier[]" value="" />' +
		'</p></div><div><p>INSTANTIATION ID SOURCE:</p><p>' +
		'<input type="text" id="instantiation_id_source_' + number + '" name="instantiation_id_source[]" value="" />' +
		'</p></div></div>' +
		'<div class="remove_element" onclick="removeElement(\'#remove_instantiation_id_' + number + '\', \'instantiation_id\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';


		$(elementID).append(html);
		$('input[name="instantiation_id_source[]"]').autocomplete({
			source: site_url + "autocomplete/values?table=instantiation_identifier&column=instantiation_source",
			minLength: 1,
			delay: 300,
			enable: true,
			cacheLength: 1


		});
	}
	else if (elementID == '#main_dimension') {
		html = '<div id="remove_dimension_' + number + '" class="remove_dimension"><div class="edit_form_div"><div><p>Dimension:</p>' +
		'<p><input type="text" id="dimension_' + number + '" name="asset_dimension[]" value="" /></p>' +
		'</div><div><p>Unit of measure:</p><p>' +
		'<input type="text" id="dimension_unit_' + number + '" name="dimension_unit[]" value="" /></p></div></div>' +
		'<div class="remove_element" onclick="removeElement(\'#remove_dimension_' + number + '\', \'dimension\');"><img src="/images/remove-item.png" /></div>' +
		'<div class="clearfix" style="margin-bottom: 10px;"></div></div>';
		$(elementID).append(html);
	}
	else if (elementID = '#main_generation') {
		generationTypes = '';

		for (cnt in pbcoreGeneration)
		{
			generationTypes += '<option value= "' + pbcoreGeneration[cnt]['value'] + '">' + pbcoreGeneration[cnt]['value'] + '</option>';
		}
		html = '<div id="remove_generation_' + number + '" class="remove_generation"><div class="edit_form_div"><div><p>Generation:</p></div>' +
		'<div><p><select id="generation_' + number + '" name="generation[]">' + generationTypes + '</select></p></div></div>' +
		'<div class="remove_element" onclick="removeElement(\'#remove_generation_' + number + '\', \'generation\');"><img src="/images/remove-item.png" /></div>' +
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