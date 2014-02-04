var sel = null;
var tel = null;
var del = null;
var cel = null;


DDSend = function(id, sGroup, config) {

    if (id) {
        // bind this drag drop object to the
        // drag source object
        this.init(id, sGroup, config);
        this.initFrame();
    }

    var el = this.getEl();
    var dragEl = this.getDragEl();

    if(el != null) {
        var dragClass = el.getAttribute("class");
        if(dragClass == "mappingTarget") {
            return false;
        }
    } if(dragEl != null) {
        var s = dragEl.style;
        s.border = "1px dashed black";
        s.backgroundColor = "#f6f5e5";
        s.padding = "10px";
    }
};

// extend proxy so we don't move the whole object around
DDSend.prototype = new YAHOO.util.DDProxy();

// DEBUG: sel & tel for dd source & target
DDSend.prototype.onDragDrop = function(e, id) {
    var sourceEl = this.getEl();
    if(id != undefined) {
        var targetEl = YAHOO.util.Dom.get(id);
        targetEl.style.border = "1px solid transparent";
        if(id.match("^clause")=="clause") {
        		setClauseXPathMapping(sourceEl, targetEl);       
        } else {
        		setXPathMapping(sourceEl, targetEl);
        }
    }
    
}

DDSend.prototype.startDrag = function(x, y) {
    var dragEl = this.getDragEl();
    var clickEl = this.getEl();
    
    del = dragEl;
    cel = clickEl;

    var dragClass = clickEl.getAttribute("class");
    if(dragClass.indexOf("mapping_value") > -1) {
        return false;
    }

    var content = "";
    var xpath = clickEl.getAttribute("xpath");
    if(xpath != undefined) {
        if(xpath.length < 50) {
            content = xpath;
        } else {
            content = xpath.substring(0, 20) + " ... " + xpath.substring(xpath.length - 25);
        }
        
        dragEl.className = clickEl.className;
        dragEl.innerHTML = content;
        dragEl.style.width = "auto";
        dragEl.style.fontSize = "75%"
    } else {
        return false;
    }
};

DDSend.prototype.onDragEnter = function(e, id) {
    var el;
    if(id != undefined) {
        if ("string" == typeof id) {
            el = YAHOO.util.DDM.getElement(id);
        } else {
            el = YAHOO.util.DDM.getBestMatch(id).getEl();
        }
        
        if(el.className.indexOf("mapping_value") > -1) {
            var sourceEl = this.getDragEl();
            sourceEl.style.backgroundColor = "#e5ffe5";
            //el.style.border = "1px solid red";
            el.style.backgroundColor = "#a3ffa3";
        }
    }
};

DDSend.prototype.onDragOut = function(e, id) {
    var el;
    if(id != undefined) {
        if ("string" == typeof id) {
            el = YAHOO.util.DDM.getElement(id);
        } else {
            el = YAHOO.util.DDM.getBestMatch(id).getEl();
        }
        
        if(el.className.indexOf("mapping_value") > -1) {
            var sourceEl = this.getDragEl();
            sourceEl.style.backgroundColor = "#f6f5e5";
            //el.style.border = "1px solid transparent";
        	el.style.backgroundColor = "";
        }
    }
}

DDSend.prototype.endDrag = function(e) {
   // override so source object doesn't move when we are done
}