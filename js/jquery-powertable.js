/*
 * jQuery Powertable
 * By: Trent Richardson [http://trentrichardson.com]
 * 
 * Copyright 2012 Trent Richardson
 * Dual licensed under the MIT or GPL licenses.
 * http://trentrichardson.com/Impromptu/GPL-LICENSE.txt
 * http://trentrichardson.com/Impromptu/MIT-LICENSE.txt
 */
;(function($){

	//########################################################################
	// create our base object
	//########################################################################
	function Powertable($this, options){
		
		this.version = 0.1;

		this._defaults = {
			// this is the controller element selector, for instance a ul, with each li has data-ptcolumn="columnDataptcolumn"
			// this element will control the table, if table columns are hidden, controller still has access
			// if not provided this will default to the master row
			controller: null,

			// row index of the table with data-ptcolumn matching that of data-ptcolumn attributes in controller
			masterRow: 0,

			// allow user ordering of column indecies
			allowMoving: true,

			// an array of column names to not allow ordering
			moveDisabled: [],

			// the selector to a drag handles.  The scope is from the controller children that are draggable
			// if no selector is supplied a handle will be created called .ptdraghandle
			moveHandle: '',

			// the text placed inside the link to move a column, only used when moveHandle=''
			moveHandleText: '&harr;',

			// where to inject the move handle (append or prepend) when moveHandle=''
			moveHandleWhere: 'append',

			// allow user show/hide of columns
			allowShowHide: true,

			// array of column names to not allow show/hide
			showHideDisabled: [],

			// this is the selector to choose the showhide button within controller to fire events
			// if no selector is supplied a handle will be created called .ptshowhide
			showHideHandle: '',

			// the text placed inside the link to show/hide, only used when showHideHandle=''
			showHideHandleText: '&plusmn;',

			// where to inject the show/hide handle (append or prepend) when showHideHandle=''
			showHideHandleWhere: 'append',

			// if we want persistant memory of this table after each page load
			persistant: false,

			// this is a string for the storage key.  It should be unique per table
			storageKey: null,

			// when persistant=true this method saves the order 
			// parameter is an array of objects per column: [ { name:'ptcolumnval' visible:true },... ]
			// save should be a function: function(columnOrder, inst){  }
			save: null,

			// when persistant=true this method restores the order.  This method should return similar object to 
			// columnOrder in save function: [ { name:'ptcolumnval' visible:true },... ]
			// restore should be a function: function(inst){  }
			restore: null,

			// this method can be called to clear any storage when a user calls $(..).powertable('clearStorage')
			clearStorage: null,

			// scrolling jquery parent element, when using fixed columns and rows they will be relative 
			// to this element's scroll event
			scrollingParent: null,

			// array of column names to be fixed
			fixedColumns: [],

			// array of row indecies to be fixed
			fixedRows: [],

			// events, "before" events can use e.preventDefault() to stop move/hide/show/etc..
			beforeShowColumn: function(e, index){},
			afterShowColumn: function(e, index){},
			beforeHideColumn: function(e, index){},
			afterHideColumn: function(e, index){},
			beforeMoveColumn: function(e, fromIndex, toIndex){},
			afterMoveColumn: function(e, fromIndex, toIndex){}
		};

		this.settings = $.extend({}, this._defaults, options);

		this.table = $this;
		this.masterRow = this.table.find('tr:eq('+ this.settings.masterRow +')');
		this.controller = this.settings.controller? $(this.settings.controller) : this.masterRow;
		this.controllerChildren = this.controller.children();
		this.scrollingParent = this.settings.scrollingParent? $(this.settings.scrollingParent) : this.table.parent();

		this.settings.storageKey = this.settings.storageKey? this.settings.storageKey : (window.location.href.split(/(\?|\#)/)[0].replace(/^[^a-zA-Z0-9]+$/, '') + '_'+ this.table.attr('id'));

		this.enable();
	}

	//########################################################################
	// extend our object
	//########################################################################
	$.extend(Powertable.prototype, {
		
		// enable the Power table, used by constructor, but also may be called as method
		enable: function(){
				var inst = this;

				this.columnOrder = this.getOrder(true);

				// bind all events
				this.table.bind('beforeShowColumn.Powertable', this.settings.beforeShowColumn);
				this.table.bind('afterShowColumn.Powertable', this.settings.afterShowColumn);
				this.table.bind('beforeHideColumn.Powertable', this.settings.beforeHideColumn);
				this.table.bind('afterHideColumn.Powertable', this.settings.afterHideColumn);
				this.table.bind('beforeMoveColumn.Powertable', this.settings.beforeMoveColumn);
				this.table.bind('afterMoveColumn.Powertable', this.settings.afterMoveColumn);

				// enable controller functionality
				if(this.settings.allowShowHide){
					var hideLinks = null,
						hideCols = this.controllerChildren;

					// filter out any columns to not showhide
					for(var i=0,l=this.settings.showHideDisabled.length; i<l; i++)
						hideCols = hideCols.not("[data-ptcolumn='"+ this.settings.showHideDisabled[i] +"']");

					if(this.settings.showHideHandle == '')
						hideLinks = hideCols[this.settings.showHideHandleWhere]('<a href="javascript:void(0)" class="ptshowhide">'+ this.settings.showHideHandleText +'</a>').children('a.ptshowhide');
					else hideLinks = hideCols.find(this.settings.showHideHandle);

					hideLinks.bind('click.Powertable', function(e){
						var $t = $(this),
							$tParent = $t.parent('[data-ptcolumn]');
							visible = $tParent.data('ptcolumnvisible');
							column = $tParent.data('ptcolumn');

						if(visible && visible == true){
							inst.hideColumn(column);
							$tParent.data('ptcolumnvisible', false);
						}
						else{ 
							inst.showColumn(column);
							$tParent.data('ptcolumnvisible', true);
						}
					});
				}

				// enable moving functionality if its available
				var div = document.createElement('div');
				if(this.settings.allowMoving && (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) ){
					var currDrag = null,
						draggables = this.controllerChildren,
						draggableHandles = null;
					
					// filter out any columns to not reorder
					for(var i=0,l=this.settings.moveDisabled.length; i<l; i++)
						draggables = draggables.not("[data-ptcolumn='"+ this.settings.moveDisabled[i] +"']");

					// ok, since dragging/dropping td's inside td's is technically not incorrect, if a handle is not 
					// specified we create a handle.  Some issues exist with dragging text nodes, except <a>. <img> should work ok too
					if(this.settings.moveHandle == ''){
						draggableHandles = draggables[this.settings.moveHandleWhere]('<a href="javascript:void(0)" class="ptdraghandle">'+ this.settings.moveHandleText +'</a>').children('a.ptdraghandle');
					}
					else dragableHandles = draggables.find(this.settings.moveHandle);

					draggableHandles.attr('draggable',true)
						.bind('dragstart.Powertable', function(e){ currDrag = this; });
					draggables
						.bind('dragenter.Powertable', function(e){ $(this).addClass('ptdragover'); })
						.bind('dragleave.Powertable', function(e){ $(this).removeClass('ptdragover'); })
						.bind('dragover.Powertable', function(e){ if(e.preventDefault){e.preventDefault();} })
						.bind('drop.Powertable', function(e){
								if(e.preventDefault){e.preventDefault();} 
								var dragParent = $(currDrag).parent('[data-ptcolumn]')[0];

								if(dragParent != this && $.contains(inst.controller[0], this)){
									inst.moveColumn($(dragParent).data('ptcolumn'), $(this).data('ptcolumn'));
								}
								draggables.filter('.ptdragover').removeClass('ptdragover');
							});
				}

				this.controllerChildren.data('ptcolumnvisible',true).attr('data-ptcolumnvisible',true);

				// handle fixed columns and rows/ create custom scrollstart/scrollend events
				inst.scrollInterval = null;
				this.scrollingParent.data({ ptscrollx:0, ptscrolly:0 }).bind('scroll.Powertable', function(e){
					var $t = $(this);
					if(!inst.scrollInterval){
						$t.trigger('scrollstart.Powertable');
						inst.scrollInterval = setInterval(function(){
							var x = $t.scrollLeft(),
								y = $t.scrollTop();

							if( (x*1 == $t.data('ptscrollx')*1) && (y*1 == $t.data('ptscrolly')*1) ){
								clearInterval(inst.scrollInterval);
								inst.scrollInterval = null;
								$t.trigger('scrollend.Powertable');
							}

							$t.data({ptscrollx:x, ptscrolly:y });
						}, 1200);
					}
				});
				this.scrollingParent.bind('scrollstart.Powertable', function(e){ inst._scrollStart(); });
				this.scrollingParent.bind('scrollend.Powertable', function(e){ inst._scrollEnd(); });
				
				// set fixed columns and rows
				for(var i=0,l=this.settings.fixedColumns.length; i<l; i++)
					this.fixedColumn(this.settings.fixedColumns[i]);

				for(var i=0,l=this.settings.fixedRows.length; i<l; i++)
					this.fixedRow(this.settings.fixedRows[i]);

				// restore table settings if persistant
				if(this.settings.persistant)
					this.restore();

				return this.table;
			},

		// used to disable, but not remove the Powertable
		disable: function(){
				this.table.undelegate('.Powertable');
				this.controller.undelegate('.Powertable');
				this.scrollingParent.unbind('.Powertable');
				return this.table;
			},

		// used to fully remove the Powertable functionality
		destroy: function(){
				this.disable();
				this.table.removeData('powertable');

				return this.table;
			},

		// set options, will not re-attach events or reset table
		option: function(key, val){
				if(val !== undefined){
					this[key] = val;
					return this.table;
				}
				return this[key];
			},

		// save the table column order to memory
		save: function(columnOrder){
				if(this.settings.save)
					this.settings.save.apply(this.table, [columnOrder, this]);
				else if (window.sessionStorage && window.JSON)
					sessionStorage.setItem(this.settings.storageKey, JSON.stringify(columnOrder));
				
				return this.table;
			},

		// restore the table to the saved spec
		restore: function(){
				var columnOrder = null;

				if(this.settings.restore)
					if($.isFunction(this.settings.restore))
						columnOrder = this.settings.restore.apply(this.table, [this]);
					else columnOrder = this.settings.restore;
				else if (window.sessionStorage && window.JSON)
					columnOrder = JSON.parse(sessionStorage.getItem(this.settings.storageKey));
				
				if(columnOrder && columnOrder.length)
					this.rebuild(columnOrder);

				return this.table;
			},

		// clear any storage for this table
		clearStorage: function(){
				if(this.settings.clearStorage)
					this.settings.clearStorage.apply(this.table, [this]);
				else if (window.sessionStorage)
					sessionStorage.removeItem(this.settings.storageKey);

				return this.table;
			},

		// rebuild the table to the given columnOrder
		rebuild: function(columnOrder){
				var currName = null,
					currVisible = null,
					currEl = null;

				for(var i=0,l=columnOrder.length; i<l; i++){
					currName = columnOrder[i].name;
					currVisible = columnOrder[i].visible;
					currEl = this.masterRow.children("[data-ptcolumn='"+ currName +"']"),
					currCtlEl = this.controller.children("[data-ptcolumn='"+ currName +"']").data('ptcolumnvisible',currVisible).attr('data-ptcolumnvisible',currVisible);

					// check the location
					if(i !== currEl.index())
						this.moveColumn(currName, i);

					// check the visibility;
					if(currVisible !== currEl.is(':visible')){
						if(currVisible)
							this.showColumn(currName);
						else this.hideColumn(currName);
					}

					// check the fixed positioning
					if(currFixed){
						this.fixedColumn(currName);
					}
				}
				this.columnOrder = columnOrder;

				return this.table;
			},

		// gets the order of the columns, returns an array of objects
		// similar to: [ { name:'ptcolumnval' visible:true },... ]
		getOrder: function(noCache){
				var columnOrder = [];

				if(this.columnOrder && (noCache === undefined || noCache !== true))
					return this.columnOrder;

				this.masterRow.children('[data-ptcolumn]').each(function(i,el){
					var $t = $(this);
					columnOrder.push({ 
							name: $t.data('ptcolumn'), 
							visible: $t.is(':visible'),
							fixed: $t.is("[data-ptcolumnfixed='true']")
						});
				});

				return columnOrder;
			},
		
		// if a column name is a string find the numeric index
		getIndex: function(columnName){
				if(/^\d+$/.test(columnName))
					return columnName;

				return this.masterRow.children("[data-ptcolumn='"+ columnName +"']").index();
			},

		// move a column from index a to index b
		moveColumn: function(fromIndex, toIndex, moveController){
				fromIndex = this.getIndex(fromIndex);
				toIndex = this.getIndex(toIndex);
				moveController = (moveController == undefined)? true : moveController;

				var beforee = new $.Event('beforeMoveColumn');
				this.table.trigger(beforee, [fromIndex, toIndex]);
				
				if(!beforee.isDefaultPrevented()){
					if(fromIndex !== toIndex){
						var rows = this.table[0].getElementsByTagName('tr'),
							where = toIndex < this.controllerChildren.length-1? 'insertBefore':'appendChild';

						// move table columns in each row..					
						for(var i=0,l=rows.length; i<l; i++)
							rows[i][where](rows[i].children[fromIndex], rows[i].children[toIndex]);

						// move the controller item
						if(this.controller != this.masterRow && moveController){
							var children = this.controller[0].children;
							this.controller[0][where](children[fromIndex], children[toIndex]);
						}

						this.columnOrder = this.getOrder(true);
						if(this.settings.persistant)
							this.save(this.columnOrder);
					}
					this.table.trigger('afterMoveColumn', [fromIndex, toIndex]);
				}
				
				return this.table;
			},

		// show the column, index may be the ptcolumn name or numeric index
		showColumn: function(index){
				index = this.getIndex(index);

				var beforee = new $.Event('beforeShowColumn');
				this.table.trigger(beforee, [index]);
				
				if(!beforee.isDefaultPrevented()){

					// show the column...
					var rows = this.table[0].getElementsByTagName('tr');
					for(var i=0,l=rows.length; i<l; i++)
						rows[i].children[index].style.display = 'table-cell';

					this.controller.children(':eq('+index+')').data('ptcolumnvisible',true).attr('data-ptcolumnvisible',true);

					this.columnOrder = this.getOrder(true);
					if(this.settings.persistant)
						this.save(this.columnOrder);

					this.table.trigger('afterShowColumn', [index]);
				}

				return this.table;
			},

		// hide the column, index may be the ptcolumn name or numeric index
		hideColumn: function(index){
				index = this.getIndex(index);

				var beforee = new $.Event('beforeHideColumn');
				this.table.trigger(beforee, [index]);
				
				if(!beforee.isDefaultPrevented()){

					// show the column...
					var rows = this.table[0].getElementsByTagName('tr');
					for(var i=0,l=rows.length; i<l; i++)
						rows[i].children[index].style.display = 'none';

					this.controller.children(':eq('+index+')').data('ptcolumnvisible',false).attr('data-ptcolumnvisible',false);
					
					this.columnOrder = this.getOrder(true);
					if(this.settings.persistant)
						this.save(this.columnOrder);

					this.table.trigger('afterHideColumn', [index]);
				}

				return this.table;
			},

		// set a column to be fixed position
		fixedColumn: function(index){
				if($.inArray(index, this.settings.fixedColumns) < 0)
					this.settings.fixedColumns.push(index);
				index = this.getIndex(index);

				this.table.find('tr :nth-child('+ (index+1) +')').data('ptfixed',true).attr('data-ptfixed',true).addClass('ptfixed');
				this.columnOrder = this.getOrder(true);
			},

		// release a column that is fixed
		releaseColumn: function(index){
				if($.inArray(index, this.settings.fixedColumns) < 0)
					this.settings.fixedColumns.splice(index,1);

				this.table.find('tr :nth-child('+ (index+1) +')').data('ptfixed',false).attr('data-ptfixed',false).removeClass('ptfixed');
				this.columnOrder = this.getOrder(true);
			},

		// set a row to be fixed position
		fixedRow: function(index){
				if($.inArray(index, this.settings.fixedRows) < 0)
					this.settings.fixedRows.push(index);

				$(this.table[0].getElementsByTagName('tr')[index]).children().data('ptfixed',true).attr('data-ptfixed',true).addClass('ptfixed');
			},

		// release a row that is fixed
		releaseRow: function(index){
				if($.inArray(index, this.settings.fixedRows) < 0)
					this.settings.fixedRows.splice(index,1);

				$(this.table[0].getElementsByTagName('tr')[index]).data('ptfixed',false).attr('data-ptfixed',false).removeClass('ptfixed');
			},

		// internal method to handle scroll event start
		_scrollStart: function(){
				var $t = this.scrollingParent,
					clen = this.settings.fixedColumns.length,
					rlen = this.settings.fixedRows.length,
					rows = this.table[0].getElementsByTagName('tr'),
					rowsLen = rows.length,
					parentPos = this.scrollingParent.css('position');

				if((clen > 0 || rlen > 0) && parentPos != 'absolute' && parentPos != 'relative')
					this.scrollingParent.css('position','relative');

				// adjust columns
				if(clen > 0){
					for(var i=0; i<clen; i++){
						var index = this.getIndex(this.settings.fixedColumns[i]);
						for(var j=0; j<rowsLen; j++){
							var child = rows[j].children[index];
							child.style.position = 'static';
							
							// the following works in all browsers, but hase a zIndex and styling complex
							//var $child = $(rows[j].children[index]),
							//	$innerChild = $child.children('div.ptfixed');
							//if($innerChild.length == 0)
							//	$innerChild = $child.wrapInner('<div class="ptfixed" />').children();
							//$innerChild.css({ position:'relative', left:0, zIndex: 999999 });
						}
					}
				}
				
				// adjust rows
				if(rlen > 0){
					for(var i=0; i<rlen; i++){
						var row = rows[this.settings.fixedRows[i]];
						for(var j=0;j<row.children.length; j++){
							var child = row.children[j];
							child.style.position = 'static';
							
							//var $child = $(row.children[j]),
							//	$innerChild = $child.children('div.ptfixed');
							//if($innerChild.length == 0)
							//	$innerChild = $child.wrapInner('<div class="ptfixed" />').children();
							//$innerChild.css({ position:'relative', top:0, zIndex: 9999999-j });
						}
					}
				}
			},

		// internal method to handle scroll event end
		_scrollEnd: function(){
				var $t = this.scrollingParent,
					x = $t.scrollLeft(),
					y = $t.scrollTop(),
					clen = this.settings.fixedColumns.length,
					rlen = this.settings.fixedRows.length,
					rows = this.table[0].getElementsByTagName('tr'),
					rowsLen = rows.length,
					parentPos = this.scrollingParent.css('position');

				if((clen > 0 || rlen > 0) && parentPos != 'absolute' && parentPos != 'relative')
					this.scrollingParent.css('position','relative');

				// adjust columns
				if(clen > 0){
					for(var i=0; i<clen; i++){
						var index = this.getIndex(this.settings.fixedColumns[i]);
						for(var j=0; j<rowsLen; j++){
							var child = rows[j].children[index];
							child.style.position = 'relative';
							child.style.left = x +'px';
							child.style.zIndex = 999999;
							
							//var $child = $(rows[j].children[index]);
							//$child.children('div.ptfixed').css({ left: x });

						}
					}
				}
				
				// adjust rows
				if(rlen > 0){
					for(var i=0; i<rlen; i++){
						var row = rows[this.settings.fixedRows[i]];
						for(var j=0;j<row.children.length; j++){
							var child = row.children[j];
							child.style.position = 'relative';
							child.style.top = y +'px';
							child.style.zIndex = 9999999-j;
							
							//var $child = $(row.children[j]);
							//$child.children('div.ptfixed').css({ top: y });
						}
					}
				}
			}

	});


	//########################################################################
	// extend jquery
	//########################################################################
	$.fn.extend({

		powertable: function(o) {
			o = o || {};
			var tmp_args = Array.prototype.slice.call(arguments);

			if (typeof(o) == 'string'){
				if(o.substr(0,3) == 'get'){
					var inst = $(this[0]).data('powertable');
					return inst.getOrder();
				}
				else{
					return this.each(function() {
						var inst = $(this).data('powertable');
						inst[o].apply(inst, tmp_args.slice(1));
					});
				}
			}
			else{
				return this.each(function() {
					var $t = $(this);
					$t.data('powertable', new Powertable($t, o) );
				});
			}
		}
	});

})(jQuery);