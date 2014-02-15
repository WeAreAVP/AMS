
function ajaxSimple(action, params, successFunction ) {
    YAHOO.util.Connect.asyncRequest('POST', action,
        { success: successFunction,
            
          failure: function(o){},
              
          argument: null
    }, params );
}
