function ValueMappings() {
	this.xpath = null;
	this.container = null;
	this.mappings = null;
}

ValueMappings.prototype.ajaxUrl = "ValueMapping";

ValueMappings.prototype.render = function (containerId) {
	this.title = $("<div>").addClass("value-mapping-title");

	this.input = $("<input type='text'>").attr("id", "value-mapping-input");
	this.output = $("<input type='text'>").attr("id", "value-mapping-output");
	this.add = $("<input type='submit'>").attr("id", "value-mapping-add").attr("value", "Add Mapping").button();
	this.add.click(this.submitMapping.bind(this));

	this.addvalue = $("<div>");
	this.addvalue.append(this.input);
	this.addvalue.append(this.output);
	this.addvalue.append(this.add);
	
	this.values = $("<div>").addClass("value-mapping-values");
	
	this.container = $("#" + containerId);
	this.container.empty();
	this.container.addClass("value-mappings");
	this.container.append(this.title);
	this.container.append(this.addvalue);
	this.container.append(this.values);
	this.refresh();
}

ValueMappings.prototype.load = function(xpath) {
	this.xpath = xpath;
	
	$.ajax({
		url: this.ajaxUrl,
		context: this,
		data: {
			command: "getMappings",
			key: xpath
		},
		success: function(r) {
			var response = this.parseResponse(r);
			if(response) {
				this.mappings = response;
				this.refresh();
			}
		}
	});	
}

ValueMappings.prototype.refresh = function() {
	if(this.container == null) return;
	
	this.title.html(this.xpath);
	
	if(this.mappings == null) {
	} else {
		var table = $("<table>");
		
		for(var i in this.mappings) {
			table.append(this.mappingRow(i));
		}
		
		this.values.empty();
		this.values.append(table);
	}
}

ValueMappings.prototype.mappingRow = function(key) {
	var value = this.mappings[key];
	var remove = $("<img>").attr("key", key).attr("src", "images/close.png").addClass("value-mapping-remove").click(function(event) {
		var key = event.target.attributes.key.value;
		this.removeMapping(key);
	}.bind(this));
	
	var row = $("<tr>");	
	row.append($("<td>").append(remove));
	row.append($("<td>").html(key));
	row.append($("<td>").html(value));
	return row;
}

ValueMappings.prototype.submitMapping = function() {
	var xpath = this.xpath;
	var input = this.input.val();
	var output = this.output.val();
	
	if(this.xpath == undefined) return;

	$.ajax({
		url: this.ajaxUrl,
		context: this,
		data: {
			command: "addMapping",
			key: xpath,
			input: input,
			output: output
		},
		success: function(r) {
			var response = this.parseResponse(r);
			if(response) {
				this.mappings = response;
				this.refresh();
			}
		}
	});
}

ValueMappings.prototype.removeMapping = function(key) {
	var xpath = this.xpath;
	var input = key;
	
	if(this.xpath == undefined) return;

	$.ajax({
		url: this.ajaxUrl,
		context: this,
		data: {
			command: "removeMapping",
			key: xpath,
			input: input
		},
		success: function(r) {
			var response = this.parseResponse(r);
			if(response) {
				this.mappings = response;
				this.refresh();
			}
		}
	});
}

ValueMappings.prototype.parseResponse = function(r) {
	var response = r;
	if(response.error != undefined) {
		alert(response.error);
		
		return null;
	}
	
	return response;
}