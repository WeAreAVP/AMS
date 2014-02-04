// The .bind method from Prototype.js 
if (!Function.prototype.bind) { // check if native implementation available
  Function.prototype.bind = function(){ 
    var fn = this, args = Array.prototype.slice.call(arguments),
        object = args.shift(); 
    return function(){ 
      return fn.apply(object, 
        args.concat(Array.prototype.slice.call(arguments))); 
    }; 
  };
}

function ValueBrowser(container_id, id) {
	this.page = 1;
	this.id = id;
	this.width = "100%";
	this.height = "200px";
	this.callback = undefined;
	this.showCount = false;

	if(this.maxItems == undefined) this.maxItems = 10;
	if(this.server == undefined) this.server = "ValueList";

	this.element = YAHOO.util.Dom.get(container_id);
	this.element.innerHTML = this.getValueBrowserLayout();
	this.container = YAHOO.util.Dom.get("container_" + this.getValueBrowserId());
	this.registerEvents();

    this.loadPage(this.page);
}

ValueBrowser.prototype.getStart = function () {
	return (((this.page - 1) * this.maxItems));
}

ValueBrowser.prototype.loadPage = function (page) {
	this.page = page;
	
	YAHOO.util.Connect.asyncRequest('POST', this.server, {
		object: this,
		success : function(o) {
			try {
				response = YAHOO.lang.JSON.parse(o.responseText);
				this.object.populate(response);
			} catch (e) {
				alert("Error: Could not load page\n" + e.name
						+ ":" + e.message);
			}
		},

		failure : function(o) {
			alert("page load failed");
		},

		argument : null
	}, "xpathHolderId=" + this.id + "&start=" + this.getStart() + "&max=" + this.maxItems);
}

ValueBrowser.prototype.getValueBrowserId = function() {
	return "valueBrowser" + this.id;
}

ValueBrowser.prototype.getValueBrowserLayout = function () {
	var str = "";
	
	str += "<div style='width: 100%; padding: 5px' id='container_" + this.getValueBrowserId() + "'>";
	str += "</div>";
	str += "<div>";
	str += "<span style='float: left'><a id='previous_" + this.getValueBrowserId() + "' href='#'>Previous</a></span>";
	str += "<span style='float: right'><a id='next_" + this.getValueBrowserId() + "' href='#'>Next</a></span>";
	str += "</div>";
	
	return str;
}

ValueBrowser.prototype.getValueBrowserTable = function (values) {
	var str = "";
	
	str += "<table id='" + this.getValueBrowserId() + "'>";	
	for(var i in values) {
		str += "<tr>";
		str += "<td>" + values[i].value + "</td>";
		if(this.showCount) str += "<td>" + values[i].count + "</td>"; 
		str += "</tr>";
	}
	str += "</table>";
	
	return str;
}

ValueBrowser.prototype.populate = function (response) {
	this.container.innerHTML = this.getValueBrowserTable(response.values);

	var columns;
	var source;
   	
	source = new YAHOO.util.DataSource(YAHOO.util.Dom.get(this.getValueBrowserId()));
    source.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE;

    if(this.showCount) {
		columns = [ {key:"Value",label:"Value",sortable:false,width: "300px"},
		            {key:"Count",label:"Count",sortable:true,width: "300px"} ];
		source.responseSchema = { fields: [{key:"Value"}, {key:"Count"}] };
	} else {
		columns = [ {key:"Value",label:"Value",sortable:false,width: "300px"} ];
		source.responseSchema = { fields: [{key:"Value"}] };
	}
    
    this.table = new YAHOO.widget.ScrollingDataTable("container_" + this.getValueBrowserId(),  columns, source, {
    		caption:"Available values for current element.",
    		width: this.width,
    		height: this.height
    	});
    
	if(this.callback != undefined) {
		this.table.unsubscribeAll("cellClickEvent");
		this.table.subscribe("cellClickEvent", this.callback);
	}
}

ValueBrowser.prototype.nextPage = function () {
	this.loadPage(this.page + 1);
}

ValueBrowser.prototype.previousPage = function () {
	if(this.page < 2) this.page = 2;
	this.loadPage(this.page - 1);
}

ValueBrowser.prototype.registerEvents = function () {
	this.nextLink = YAHOO.util.Dom.get("next_" + this.getValueBrowserId());
	this.nextLink.onclick = this.nextPage.bind(this);
	this.previousLink = YAHOO.util.Dom.get("previous_" + this.getValueBrowserId());
	this.previousLink.onclick = this.previousPage.bind(this);
}

ValueBrowser.prototype.setSelectCallback = function(f) {
	this.callback = f;
	if(this.table != undefined) {
		this.table.unsubscribeAll("cellClickEvent");
		if(this.callback != undefined) {
			this.table.subscribe("cellClickEvent", this.callback);
		}
	}
}