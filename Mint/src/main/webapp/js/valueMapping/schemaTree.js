function SchemaTree() {
	this.container = null;
	this.schema = null;
}

SchemaTree.prototype.render = function (containerId) {
	this.container = $("#" + containerId);
	this.refresh();
}
SchemaTree.prototype.load = function(schema) {
	this.schema = schema;
	this.refresh();
}

SchemaTree.prototype.refresh = function() {
	if(this.container == null) return;
	if(this.schema == null) {
		this.container.jstree({
			"plugins": ["themes"]
		});
	} else {
		var data = {
			"data": this.schema
		};

		this.container.jstree({
			core: {
				animation: 100
			},
			plugins : ["themes", "search", "json_data", "ui"],
			json_data: data,
			ui: {
				select_limit: 1,
			},
			themes: {
				theme: "apple",
				dots: false
			},
			callback: {
				onselect: function() {
					alert("me");
				}
			}
		}).bind("select_node.jstree", function(event, data) {
			this.selected = data.rslt.obj;
			this.container.trigger("select", [ this.selected ]);
		}.bind(this));
	}
}