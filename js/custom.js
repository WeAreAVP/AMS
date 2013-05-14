// JavaScript Document
tinyMCE.init({
	// General options
	mode: "exact",
	elements: "body_html",
	theme: "advanced",
	plugins: "table,inlinepopups",
	// Theme options
	theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,|,table,removeformat,code",
	theme_advanced_buttons2: "",
	theme_advanced_buttons3: "",
	theme_advanced_buttons4: "",
	theme_advanced_toolbar_location: "top",
	theme_advanced_toolbar_align: "left",
	theme_advanced_statusbar_location: "bottom",
	theme_advanced_resizing: true,
	// Style formats
	style_formats: [
		{
			title: 'Bold text',
			inline: 'b'
		},
		{
			title: 'Red text',
			inline: 'span',
			styles: {
				color: '#ff0000'
			}
		},
		{
			title: 'Red header',
			block: 'h1',
			styles: {
				color: '#ff0000'
			}
		},
		{
			title: 'Example 1',
			inline: 'span',
			classes: 'example1'
		},
		{
			title: 'Example 2',
			inline: 'span',
			classes: 'example2'
		},
		{
			title: 'Table styles'
		},
		{
			title: 'Table row 1',
			selector: 'tr',
			classes: 'tablerow1'
		}
	],
	formats: {
		alignleft: {
			selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
			classes: 'left'
		},
		aligncenter: {
			selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
			classes: 'center'
		},
		alignright: {
			selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
			classes: 'right'
		},
		alignfull: {
			selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
			classes: 'full'
		},
		bold: {
			inline: 'span',
			'classes': 'bold'
		},
		italic: {
			inline: 'span',
			'classes': 'italic'
		},
		underline: {
			inline: 'span',
			'classes': 'underline',
			exact: true
		},
		strikethrough: {
			inline: 'del'
		},
		customformat: {
			inline: 'span',
			styles: {
				color: '#00ff00',
				fontSize: '20px'
			},
			attributes: {
				title: 'My custom format'
			}
		}
	}
});
function rand(min, max) {
	var argc = arguments.length;
	if (argc === 0) {
		min = 0;
		max = 2147483647;
	} else if (argc === 1) {
		throw new Error('Warning: rand() expects exactly 2 parameters, 1 given');
	}
	return Math.floor(Math.random() * (max - min + 1)) + min;
}
function implode(glue, pieces) {
	var i = '',
	retVal = '',
	tGlue = '';
	if (arguments.length === 1) {
		pieces = glue;
		glue = '';
	}
	if (typeof(pieces) === 'object') {
		if (Object.prototype.toString.call(pieces) === '[object Array]') {
			return pieces.join(glue);
		}
		for (i in pieces) {
			retVal += tGlue + pieces[i];
			tGlue = glue;
		}
		return retVal;
	}
	return pieces;
}
function toggal_message_body()
{
	var email_type = $('#email_type').val();
	if (email_type == 'plain')
	{
		$('#html_body_msg').hide();
	}
	else
	{
		$('#html_body_msg').show();
	}

}
function checkAll() {
	var boxes = document.getElementsByTagName('input');
	for (var index = 0; index < boxes.length; index++) {
		box = boxes[index];
		if (box.type == 'checkbox' && box.className == 'checkboxes' && box.disabled == false)
			box.checked = document.getElementById('check_all').checked;
	}
	return true;
}
function str_replace(search, replace, subject, count) {

	var i = 0,
	j = 0,
	temp = '',
	repl = '',
	sl = 0,
	fl = 0,
	f = [].concat(search),
	r = [].concat(replace),
	s = subject,
	ra = Object.prototype.toString.call(r) === '[object Array]',
	sa = Object.prototype.toString.call(s) === '[object Array]';
	s = [].concat(s);
	if (count) {
		this.window[count] = 0;
	}

	for (i = 0, sl = s.length; i < sl; i++) {
		if (s[i] === '') {
			continue;
		}
		for (j = 0, fl = f.length; j < fl; j++) {
			temp = s[i] + '';
			repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
			s[i] = (temp).split(f[j]).join(repl);
			if (count && s[i] !== temp) {
				this.window[count] += (temp.length - s[i].length) / f[j].length;
			}
		}
	}
	return sa ? s : s[0];
}
function in_array(needle, haystack, argStrict) {
	// http://kevin.vanzonneveld.net
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: vlado houba
	// +   input by: Billy
	// +   bugfixed by: Brett Zamir (http://brett-zamir.me)
	// *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
	// *     returns 1: true
	// *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
	// *     returns 2: false
	// *     example 3: in_array(1, ['1', '2', '3']);
	// *     returns 3: true
	// *     example 3: in_array(1, ['1', '2', '3'], false);
	// *     returns 3: true
	// *     example 4: in_array(1, ['1', '2', '3'], true);
	// *     returns 4: false
	var key = '',
	strict = !!argStrict;

	if (strict) {
		for (key in haystack) {
			if (haystack[key] === needle) {
				return true;
			}
		}
	} else {
		for (key in haystack) {
			if (haystack[key] == needle) {
				return true;
			}
		}
	}

	return false;
}

function showHideColumns(column)
{
	if (frozen < column + 1)
	{
//		$('#listing_table').dataTable().fnSetColumnVis(column, true);
		$('#' + column + '_column i').toggle();
		updateDatabase(1);
//		if ($('#' + column + '_column i').css('display') == "none")
//		{
//			$('#listing_table').dataTable().fnSetColumnVis(column, false);
//
//		}
//		else
//		{
//			$('#listing_table').dataTable().fnSetColumnVis(column, true);
//
//		}

	}
	else
	{
		alert('Frozen Column will not take any affect');
	}
}
function getColumnOrder()
{
	var orderString = new Array;
	$('#listing_table_wrapper table th').each(function(index)
	{
		if (index == 0)
		{
			orderString[index] = this.id;
		}
		else
		{
			if (!in_array(this.id, orderString, true))
			{
				orderString[index] = this.id;
			}
		}
	});
	return orderString;
}
function reOrderDropDown(columnArray)
{
	var columnShowHide = new Array();
	$('#show_hide_li a').each(function(index, id)
	{
		if ($('#' + this.id + ' i').css('display') == "none")
		{
			columnShowHide[index] = str_replace(' ', '_', $(this).text());
		}

	});
	
	$('#show_hide_li').html('');
	for (cnt in columnArray)
	{
		display = '';
		if (in_array(columnArray[cnt], columnShowHide, true))
		{
			console.log('yep');
			display = 'style="display:none;"';
		}
		name = columnArray[cnt].split('_').join(' ');
		$('#show_hide_li').append('<li><a href="javascript://;" onclick="showHideColumns(' + cnt + ');" id="' + cnt + '_column"><i class="icon-ok" ' + display + '></i>' + name + '</a></li>');
	}
}
function freezeColumns(count)
{
	frozen = count;
	$('#frozen_ul i').each(function(id, obj) {
		if (id != frozen) {
			$(this).hide();
		}
	});
	$('#freeze_col_' + frozen).toggle();
	updateDatabase(1);

}
oFC = null;
function updateDataTable()
{
	if (current_table_type == 'assets') {
		url_tab_type = 'records/sort_full_table';
		height = $(window).height() - 230;

	}
	else {
		url_tab_type = 'instantiations/instantiation_table';
		height = $(window).height() - 195;

	}
	if ($('#listing_table').length > 0)
	{

		oTable =
		$('#listing_table').dataTable(
		{
			"sDom": 'RlfrtipS',
			"aoColumnDefs": [{
					"bVisible": false,
					"aTargets": hiden_column

				}
			],
			"aaSorting": [[index_column, order_column]],
			"oColReorder": {
				"iFixedColumns": frozen
//				"fnReorderCallback": function() {
//					columnArray = getColumnOrder();
//					reOrderDropDown(columnArray);
//					updateDatabase(0);
//				}
			},
			'bPaginate': false,
			'bInfo': false,
			'bFilter': false,
			"bSort": true,
			"sScrollY": height,
			"sScrollX": "200%",
			"bDeferRender": true,
			"bDestroy": is_destroy,
			"bRetrieve": true,
			"bAutoWidth": true,
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": site_url + url_tab_type,
			"fnServerData": function(sSource, aoData, fnCallback) {
				columnArray = getColumnOrder();
				reOrderDropDown(columnArray);
				updateDatabase(0);
				jQuery.getJSON(sSource, aoData, function(json) {

					/* Do whatever additional processing you want on the callback, then tell DataTables */
					fnCallback(json);
				});
			},
			"fnInitComplete": function() {
				oFC = new FixedColumns(oTable, {
					"iLeftColumns": frozen
				});
			}

		});
		$('#freeze_col_' + frozen).show();
		$.extend($.fn.dataTableExt.oStdClasses, {
			"sWrapper": "dataTables_wrapper form-inline"
		});
		if (current_table_type != 'assets') {
			$('#listing_table_processing').css('top', '125px');
		}



	}
}
function updateDatabase(refresh)
{
	userSettings = new Array();
	$('#show_hide_li a').each(function(index, id)
	{
		columnAnchorID = this.id;
		if ($('#' + columnAnchorID + ' i').css('display') == "none")
		{
			userSettings[index] = {
				title: str_replace(' ', '_', $(this).text()),
				hidden: 1
			};
		}
		else
		{
			userSettings[index] = {
				title: str_replace(' ', '_', $(this).text()),
				hidden: 0
			};
		}
	});
	$.ajax({
		type: 'POST',
		url: site_url + 'instantiations/update_user_settings',
		async: false,
		data: {
			settings: userSettings,
			frozen_column: frozen,
			table_type: current_table_type
		},
		success: function(result) {

			if (refresh == 1)
				window.location.reload();


		}
	});
}