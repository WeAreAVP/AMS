var publicationPanel = new YAHOO.widget.Panel("publication",
	{ width: "800px",
	  height: "420px",
	  fixedcenter: true,
	  constraintoviewport: true,
	  close: true,
	  draggable: false,
	  zindex: 4,
	  modal: true,
	  visible: false
	}
);

publicationPanel.setHeader("Publish imports");
publicationPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
publicationPanel.hideEvent.subscribe(publicationPanelClose); 

var publicationPanelOrgId = null;

var publicationPanelUserId = -1;
var publImportStart=0;

function publicationPanelClose() {
   ajaxImportsPanel(publImportStart, 5, publicationPanelUserId, publicationPanelOrgId);
}



function ajaxPublicationRequest(orgId,userId,start) {
	publicationPanelOrgId = orgId;  
    publicationPanelUserId = userId;
    publImportStart=start;
	publicationPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
    
	//publicationPanel.render(document.body);
	publicationPanel.show();
	
    YAHOO.util.Connect.asyncRequest('POST', 'Publish_input.action',
        {
            success: function(o) {
                
    	        publicationPanel.setBody(o.responseText);
    	       
    	        val1=document.getElementById("trans").innerHTML;
    	        val2=document.getElementById("publ").innerHTML;
    			initLists(val1,val2);	   	        
    	     },
            
            failure: function(o) {
            	publicationPanel.setBody("<h1>Error</h1>");
            }
        }, "orgId=" + orgId);
    
   
}



function ajaxPublish(orgId,pub,unpub) {
	publicationPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
	publicationPanel.show();
		
    YAHOO.util.Connect.asyncRequest('POST', 'Publish.action',
        {
            success: function(o) {
    			    publicationPanel.hide();
    			    //publicationPanel.close();
    			   //  ajaxImportsPanel(publImportStart, 5, publicationPanelUserId, publicationPanelOrgId);
    	        	
                    
            },
            
            failure: function(o) {
            	publicationPanel.setBody("<h1>Error</h1>");
            }
        },"orgId=" + orgId+"&pubarray="+pub+"&unpubarray="+unpub);
    
   
}



function calcList(listname) {
 			var Dom = YAHOO.util.Dom;
 			//alert(listname);
            var ul=Dom.get(listname);
            var items = ul.getElementsByTagName("li");
            var out = "";
            for (i=0;i<items.length;i=i+1) {
            if(i>0){out+=",";}
               var itemids=items[i].getElementsByTagName("span");
               out += itemids[0].innerHTML;
            }
            return out;
        };

function initLists(val1,val2) {

var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var DDM = YAHOO.util.DragDropMgr;

//////////////////////////////////////////////////////////////////////////////
// example app
//////////////////////////////////////////////////////////////////////////////
YAHOO.example.DDApp = {
    init: function() {

        var rows=3,cols=2,i,j;
        for (i=1;i<cols+1;i=i+1) {
            new YAHOO.util.DDTarget("ul"+i);
            if(i==1){
             for (j=1;j<val1+1;j=j+1) {
                new YAHOO.example.DDList("li" + i + "_" + j);
            }
            }else{
	            for (j=1;j<val2+1;j=j+1) {
	                new YAHOO.example.DDList("li" + i + "_" + j);
	            }
            }
        }

    
        //Event.on("donebutton", "click", this.showOrder);
    },

    showOrder: function() {
        var parseList = function(ul, title) {
            var items = ul.getElementsByTagName("li");
            var out = title + ": ";
            for (i=0;i<items.length;i=i+1) {
            if(i>0){out+=",";}
               var itemids=items[i].getElementsByTagName("span");
               out += itemids[0].innerHTML;
            }
            return out;
        };

        var ul1=Dom.get("ul1"), ul2=Dom.get("ul2");
        //alert(parseList(ul1, "unpubid") + "\n" + parseList(ul2, "pubid"));

    }  
};

//////////////////////////////////////////////////////////////////////////////
// custom drag and drop implementation
//////////////////////////////////////////////////////////////////////////////

YAHOO.example.DDList = function(id, sGroup, config) {

    YAHOO.example.DDList.superclass.constructor.call(this, id, sGroup, config);

    this.logger = this.logger || YAHOO;
    var el = this.getDragEl();
    Dom.setStyle(el, "opacity", 0.67); // The proxy is slightly transparent

    this.goingUp = false;
    this.lastY = 0;
};

YAHOO.extend(YAHOO.example.DDList, YAHOO.util.DDProxy, {

    startDrag: function(x, y) {
        this.logger.log(this.id + " startDrag");

        // make the proxy look like the source element
        var dragEl = this.getDragEl();
        var clickEl = this.getEl();
        Dom.setStyle(clickEl, "visibility", "hidden");

        dragEl.innerHTML = clickEl.innerHTML;

        Dom.setStyle(dragEl, "color", Dom.getStyle(clickEl, "color"));
        Dom.setStyle(dragEl, "backgroundColor", Dom.getStyle(clickEl, "backgroundColor"));
        Dom.setStyle(dragEl, "border", "2px solid gray");
    },

    endDrag: function(e) {

        var srcEl = this.getEl();
        var proxy = this.getDragEl();

        // Show the proxy element and animate it to the src element's location
        Dom.setStyle(proxy, "visibility", "");
        var a = new YAHOO.util.Motion( 
            proxy, { 
                points: { 
                    to: Dom.getXY(srcEl)
                }
            }, 
            0.2, 
            YAHOO.util.Easing.easeOut 
        )
        var proxyid = proxy.id;
        var thisid = this.id;

        // Hide the proxy and show the source element when finished with the animation
        a.onComplete.subscribe(function() {
                Dom.setStyle(proxyid, "visibility", "hidden");
                Dom.setStyle(thisid, "visibility", "");
            });
        a.animate();
    },

    onDragDrop: function(e, id) {

        // If there is one drop interaction, the li was dropped either on the list,
        // or it was dropped on the current location of the source element.
        if (DDM.interactionInfo.drop.length === 1) {

            // The position of the cursor at the time of the drop (YAHOO.util.Point)
            var pt = DDM.interactionInfo.point; 

            // The region occupied by the source element at the time of the drop
            var region = DDM.interactionInfo.sourceRegion; 

            // Check to see if we are over the source element's location.  We will
            // append to the bottom of the list once we are sure it was a drop in
            // the negative space (the area of the list without any list items)
            if (!region.intersect(pt)) {
                var destEl = Dom.get(id);
                var destDD = DDM.getDDById(id);
                destEl.appendChild(this.getEl());
                destDD.isEmpty = false;
                DDM.refreshCache();
            }

        }
    },

    onDrag: function(e) {

        // Keep track of the direction of the drag for use during onDragOver
        var y = Event.getPageY(e);

        if (y < this.lastY) {
            this.goingUp = true;
        } else if (y > this.lastY) {
            this.goingUp = false;
        }

        this.lastY = y;
    },

    onDragOver: function(e, id) {
    
        var srcEl = this.getEl();
        var destEl = Dom.get(id);

        // We are only concerned with list items, we ignore the dragover
        // notifications for the list.
        if (destEl.nodeName.toLowerCase() == "li") {
            var orig_p = srcEl.parentNode;
            var p = destEl.parentNode;

            if (this.goingUp) {
                p.insertBefore(srcEl, destEl); // insert above
            } else {
                p.insertBefore(srcEl, destEl.nextSibling); // insert below
            }

            DDM.refreshCache();
        }
    }
});

Event.onDOMReady(YAHOO.example.DDApp.init, YAHOO.example.DDApp, true);

};



