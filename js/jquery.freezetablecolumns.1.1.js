/*
  @author:   Conan Albrecht <ca@byu.edu>
  @version:  1.1
  @modified: 2012-02-29
  @license:  Public Domain; No warranty of any kind is given.  Use at your own risk.
  
  A JQuery plugin to freeze table columns and headers -- spreadsheet style.

    * Any items in the <thead> are frozen as headers.
    * The given number of columns are frozen.
  
  Please note that this is a big hack on the <table> tag in HTML.  The script takes your table
  and splits it into 4 tables: 
    Region 1    Region 2
    Region 3    Region 4
  
    - Region 1 is the top-left corner cell (or cells for multiple frozen columns/headers)
    - Region 2 is your <thead> cells, less the ones pulled off the left
    - Region 3 is your frozen columns on the left side.
    - Region 4 is the regular, unfrozen data of the table.

    The script keeps the row height of Regions 3 and 4 the same, and it keeps the column width of
    Regions 1 and 3/2 and 4 the same.  That makes the user think it's one big table still.
    When either Region 3 or 4 are scrolled, the script matches the other tables to it.
  
    The script makes four copies of your table, then deletes rows and columns as needed.  This ensures that
    the table/row/cell properties, events, etc. move into each area.  This approach works well with regular-sized
    tables, but it won't be efficient with huge tables.
    
  Options:
    The script takes several options.  All of these are listed (with comments) in the example below.
    
  Known issues:
  
    * It works with columns that span (<td colspan="">), but *at least one <tr>* in each region must be
      free of colspans.  In other words, you can span columns all you want, but you must leave at least 
      one row a normal row with non-spanning columns in each of the four regions.  The algorithm in this
      script uses this non-colspanning row to figure out column widths.  In addition, tables can behave
      erratically when columns are spanned no the first row.  YMMV.
    * It has to allow the tables unlimited width to get a calculation of how big they need to be.  This
      "unlimited" width is currently set at 50,000 pixels.  You may want to adjust this if your table
      is bigger than this (wow!) or subsantially smaller (to save memory).
    * It doesn't support freezing of <tfoot> areas.

  Example (don't forget to set the path to jquery.freezetablecolumns.1.1.js appropriately):

      <html>
      <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="jquery.freezetablecolumns.1.1.js"></script>
          <style type="text/css">
            #mytable-div table {
              border-collapse: collapse;
            }
            #mytable-div td,th {
              border: 1px solid #B3B3B3;
              white-space: nowrap;
              padding: 2px 4px;
            }
          </style>
      </head>
      <body>
        <script>
          $(document).ready(function() {
            // I'm going to create a big table here in Javascript.  Normally you'd do this in server-side code
            // or just in HTML.  Doing it here in pure HTML would make for a pretty large example.
            var html = '';
            for (var c = 1; c <= 15; c++) {
              html += '<th>Column ' + c + ' Here</th>';
            }
            $('#mytable > thead > tr').append(html);
            for (var r = 1; r <= 100; r++) {
              html = '';
              html += '<tr><td>Row ' + r + ' Here</td>';
              for (var c = 1; c <= 15; c++) {
                html += '<td>';
                html += 'Row ' + r + ' Column ' + c + ' Data';
                html += '</td>';
              }
              html += '</tr>';
              $('#mytable > tbody').append(html);
            }//for
  
            // freeze the columns
            $('#mytable').freezeTableColumns({
              width:       $(window).width(),   // required (exact px here, not % or em)
              height:      500,                 // required (exact px here, not % or em)
              numFrozen:   2,                   // optional, defaults to 1 left-most column frozen
           // frozenWidth: 200,                 // optional, defaults to natural width of frozen columns
           // clearWidths: true,                // optional, defaults to true, meaning that any set widths on the columns are cleared
            });//freezeTableColumns
            
            // example of adjusting the table once it is already displayed (note I'm calling freezeTableColumnsLayout() this time)
            $('#mybutton').click(function() {
              $('#mytable').freezeTableColumnsLayout({
                width:       400, // new width of table
                height:      400, // new height of table
                frozenWidth: 125, // specify the frozen width this time
                numFrozen:   1,   // this option won't do anything -- you can't change the number of frozen cols after initial display
              });
            });//click
          });//ready
        </script>
        <div><button id="mybutton">Adjust the Layout After Display (just another example)</button></div>
        <table id="mytable">
          <thead><tr><th>&nbsp;</th></tr></thead>
          <tbody></tbody>
        </table>
      </body>
      </html>
*/

(function( $ ) {
  $.fn.freezeTableColumns = function(options) {
    options = initOptions(options);
		var source_table = $(this);
		var main_id = source_table.attr('id');
		if (!main_id) {
		  console.log('jquery.freezetablecolumns.js: Error initializing frozen columns - source table must have a unique id attribute.');
		  return;
	  }

		// set up the four regions
		source_table.after('<div id="' + main_id + '-div" style="display: inline-block;"></div>');
		source_table.detach(); // remove the table from the document flow
		main_div = $('#' + main_id + '-div');
		main_div.append(
		  '<div id="' + main_id + '-row1" style="white-space: nowrap;height:30px;">' + 
		  '<div id="' + main_id + '-region1" style="display: inline-block; vertical-align: top; width: 5px; overflow: hidden;"><div></div></div>' + 
      '<div id="' + main_id + '-region2" style="display: inline-block; vertical-align: top; width: 5px; overflow-x: hidden; overflow-y: hidden;"><div></div></div>' + 
		  '</div>'
		);
		main_div.append(
      '<div id="' + main_id + '-row2"  style="white-space: nowrap;">' +
		  '<div id="' + main_id + '-region3" style="display: inline-block; vertical-align: top; height: 100%; width: 5px; overflow-y: hidden; overflow-x: hidden;"><div></div></div>' + 
      '<div id="' + main_id + '-region4" style="display: inline-block; vertical-align: top; height: 100%; width: 5px; overflow: scroll;"><div></div></div>' + 
	    '</div>'
	  );

		// row 1 (corner area and header divs)
		// note that I use .children() rather than .find() in case the user's table cells have additional tables embedded within them
		var source_table_region1 = source_table.get(0).cloneNode(false);
		$(source_table_region1).removeAttr('id'); // only one of the four tables can have the original id
    moveElement(source_table_region1, $('#' + main_id + '-region1').children('div'));
    $('#' + main_id + '-region1').children('div').children('table').append('<thead></thead>');
    var thead = $('#' + main_id + '-region1 > div > table > thead');
    source_table.children('thead').children('tr').each(function(rowindex, rowelement) {
      thead.append('<tr></tr>');
      var tr = thead.children('tr:last');
      var cellindex = 0;
      $(rowelement).children('td,th').each(function() {
        if (cellindex >= options.numFrozen) {
          return false;
        }
        moveElement(this, tr);
        if (typeof $(this).attr('colspan') === "undefined") {
          cellindex += 1;
        }else{
          cellindex += $(this).attr('colspan');
        }
      });
    });
    var source_table_region2 = source_table.get(0).cloneNode(false);
		$(source_table_region2).removeAttr('id'); // only one of the four tables can have the original id
    moveElement(source_table_region2, $('#' + main_id + '-region2').children('div')); // second table is easier because we already removed the frozen td's -- just add what's left in the thead
		moveElement(source_table.children('thead'), $('#' + main_id + '-region2').children('div').children('table'));

    // row 2 (frozen columns and main data table divs)
    var source_table_region3 = source_table.get(0).cloneNode(false);
		$(source_table_region3).removeAttr('id'); // only one of the four tables can have the original id
    moveElement(source_table_region3, $('#' + main_id + '-region3').children('div'));
    $('#' + main_id + '-region3').children('div').children('table').append('<tbody></tbody>');
    var tbody = $('#' + main_id + '-region3 > div > table > tbody');
    source_table.children('tbody').children('tr').each(function(rowindex, rowelement) {
      tbody.append('<tr></tr>');
      var tr = tbody.children('tr:last');
      var cellindex = 0;
      $(rowelement).children('td,th').each(function() {
        if (cellindex >= options.numFrozen) {
          return false;
        }
        moveElement(this, tr);
        if (typeof $(this).attr('colspan') === "undefined") {
          cellindex += 1;
        }else{
          cellindex += $(this).attr('colspan');
        }
      });
    });
    moveElement(source_table, $('#' + main_id + '-region4').children('div')) // move whatever is left in the table
  
    // set to fixed-with
    for (var i = 1; i <= 4; i++) {
      $('#' + main_id + '-region' + i).children('div').children('table').css('table-layout', 'fixed');
    }
  
    // lay everything out
    source_table.freezeTableColumnsLayout(options);
  
    // set up the events to match elements when scrolled
    var scroll_affects = {  // source -> xscroll, yscroll
      '-region1': [ '-region3', '-region2' ],
      '-region2': [ '-region4', '-region1' ],
      '-region3': [ '-region1', '-region4' ],
      '-region4': [ '-region2', '-region3' ],
    }
    main_div.children('div').children('div').scroll(function(event) {
      var sourceid = $(this).attr('id').substr($(this).attr('id').lastIndexOf('-'));
      $('#' + main_id + scroll_affects[sourceid][0]).scrollLeft($(this).scrollLeft());
      $('#' + main_id + scroll_affects[sourceid][1]).scrollTop($(this).scrollTop());
    });//scroll
    
  };//freezeTableColumns

  
  /* 
    Moves an element to a new parent.  I use the Javascript DOM methods here because
    JQuery's append strips and evaluates any attached javascript the user has in the table.
    This is not what the user expects, so move the table elements using DOM.
    I use this method any time I'm moving existing HTML I didn't place in the document.
  */
  function moveElement(element, newparent) {
    $(element).each(function(eindex, e) {
      $(newparent).get(0).appendChild(e);
    });
  }


  /* 
    Adjusts the widths to an already-existing frozen table.  This allows you to adjust the main div height or width,
    cell contents, etc., and then lay everything out again.  The options are the same as the main function, but
    you can't change the number of frozen columns.  See the example at the top for, well, an example.
  */
  $.fn.freezeTableColumnsLayout = function(options) {
    options = initOptions(options);
    var main_id = $(this).attr('id');
    
		// make all the columns the same width
		function setColWidths(topRegion, bottomRegion) {
		  // clear the widths if needed
		  if (options.clearWidths) {
  		  topRegion.children('div').children('table').width('');
	  	  bottomRegion.children('div').children('table').width('');
		    topRegion.children('div').children('table').children('thead,tbody').children('tr').children('td,th').each(function() {
		      $(this).removeAttr('width');
		      $(this).css('width', '');
	      });
		    bottomRegion.children('div').children('table').children('thead,tbody').children('tr').children('td,th').each(function() {
		      $(this).removeAttr('width');
		      $(this).css('width', '');
	      });
	    }
		  // find the first <tr> without any colspan attributes (those mess up the algorithm)
		  var top_tr = null;
		  topRegion.children('div').children('table').children('thead,tbody').children('tr').each(function(index) {
		    if ($(this).children('td,th').filter('[colspan]').length == 0) { // i.e. this row has no colspans
  		    top_tr = $(this);
  		    return false;
	      }
	    });
		  var bottom_tr = null;
		  bottomRegion.children('div').children('table').children('thead,tbody').children('tr').each(function(index) {
		    if ($(this).children('td,th').filter('[colspan]').length == 0) { // i.e. this row has no colspans
  		    bottom_tr = $(this);
  		    return false;
	      }
	    });
	    if (top_tr == null || bottom_tr == null) {
  		  return;
      }
	    // set the widths of each column
	    topRegion.children('div').children('table').children('colgroup').remove();
	    topRegion.children('div').children('table').prepend('<colgroup></colgroup>');
	    bottomRegion.children('div').children('table').children('colgroup').remove();
	    bottomRegion.children('div').children('table').prepend('<colgroup></colgroup>');
  		top_tr.children('td,th').each(function(index) {
  		  var bottomcell = bottom_tr.children('td,th').eq(index);
  		  var maxwidth = Math.max($(this).width(), bottomcell.width()) + 10;
  		  topRegion.children('div').children('table').children('colgroup').append('<col width="' + maxwidth + '"/>');
  		  bottomRegion.children('div').children('table').children('colgroup').append('<col width="' + maxwidth + '"/>');
  	  });//each
    }//setColWidths function
    setColWidths($('#' + main_id + '-region1'), $('#' + main_id + '-region3'));
    setColWidths($('#' + main_id + '-region2'), $('#' + main_id + '-region4'));
    
    // make all the rows the same height
    function setRowHeights(left_region, right_region) {
      left_region.children('div').children('table').children('thead,tbody').children('tr').each(function(index) {
        var right_region_tr = right_region.children('div').children('table').children('tbody,thead').children('tr').eq(index);
        var maxheight = Math.max($(this).height(), right_region_tr.height());
        $(this).height(maxheight);
        right_region_tr.height(maxheight);
      });
    }//setRowHeights
    setRowHeights($('#' + main_id + '-region1'), $('#' + main_id + '-region2'));
    setRowHeights($('#' + main_id + '-region3'), $('#' + main_id + '-region4'));
  
		// set row 2 height to follow the required height
		var row1height = $('#' + main_id + '-div').children('#' + main_id + '-row1').outerHeight();
		$('#' + main_id + '-div').children('#' + main_id + '-row2').height(options.height - row1height);

    // calculate how big the tables want to be, given an "unlimited" space (50,000 in this case)
    $('#' + main_id + '-region1').children('div').width(50000);
    $('#' + main_id + '-region2').children('div').width(50000);
    $('#' + main_id + '-region3').children('div').width(50000);
    $('#' + main_id + '-region4').children('div').width(50000);
    var region1_3div = Math.max($('#' + main_id + '-region1').children('div').children('table').outerWidth(), $('#' + main_id + '-region3').children('div').children('table').outerWidth());
    var region2_4div = Math.max($('#' + main_id + '-region1').children('div').children('table').outerWidth(), $('#' + main_id + '-region4').children('div').children('table').outerWidth());
    if (options.frozenWidth < 0) {
      options.frozenWidth = region1_3div;
    }

    // set the widths for both the containing divs (with the overflow scrolls) and the inner divs (that give the tables all the room they want)
    $('#' + main_id + '-region1').width(options.frozenWidth);
    $('#' + main_id + '-region1').children('div').width(region1_3div);
    $('#' + main_id + '-region2').width(options.width - options.frozenWidth);
    $('#' + main_id + '-region2').children('div').width(region2_4div);
    $('#' + main_id + '-region3').width(options.frozenWidth);
    $('#' + main_id + '-region3').children('div').width(region1_3div);
    $('#' + main_id + '-region4').width(options.width - options.frozenWidth);
    $('#' + main_id + '-region4').children('div').width(region2_4div);
  };//freezeTableColumnsLayout


  /* Initializes the options with default values - not meant to be called externally */
  function initOptions(options) {
		return $.extend({
      width:       800,
      height:      800,
      numFrozen:   1,
      frozenWidth: -1, // default is dynamic
      clearWidths: true,
		}, options || {});//extend
  };//initOptions  
  
})( jQuery );








