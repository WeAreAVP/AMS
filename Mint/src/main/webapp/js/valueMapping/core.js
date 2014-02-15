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

function ValueMapping(containerId) {
	this._container = $("#" + containerId);
	this._north = $("<div>").addClass("ui-layout-north").html("North");
	this._center = $("<div>").addClass("ui-layout-center");
	this._west = $("<div>").addClass("ui-layout-west");
	this._east = $("<div>").addClass("ui-layout-east");
	this._south = $("<div>").addClass("ui-layout-south");
	
	this._west.append($("<div>").attr("id", "tree"));
	this._center.append($("<div>").attr("id", "mappings"));
	
	this._container.append(this._north);
	this._container.append(this._center);
	this._container.append(this._west);
	this._container.layout({ applyDefaultStyles: true });

	this.initMappingsContainer();
	this.initTreeContainer();
}

ValueMapping.prototype.ajaxUrl = "ValueMapping";

ValueMapping.prototype.initTreeContainer = function() {
	this.tree = new SchemaTree();
	this.tree.render("tree");
	this.tree.container.bind("select", function(event, data) {
		var xpath = data.data("xpath");
		this.mappings.load(xpath);		
	}.bind(this));
	
	$.ajax({
		url: this.ajaxUrl,
		context: this,
		data: {
			command: "schemaTree",
		},
		success: function(r) {
			var response = this.parseResponse(r);
			if(response) {
				this.tree.load(response.tree);
				this.tree.refresh();
			}
		}
	});	
}

ValueMapping.prototype.initMappingsContainer = function() {
	this.mappings = new ValueMappings();
	this.mappings.render("mappings");
}

ValueMapping.prototype.parseResponse = function(r) {
	var response = r;
	if(response.error != undefined) {
		alert(response.error);
		
		return null;
	}
	
	return response;
}
