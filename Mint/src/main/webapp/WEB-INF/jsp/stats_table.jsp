<%@page import="gr.ntua.ivml.mint.persistent.DataUpload" %>
<%@page import="gr.ntua.ivml.mint.persistent.XpathHolder" %>
<%@page import="gr.ntua.ivml.mint.persistent.XmlObject" %>
<%@page import="gr.ntua.ivml.mint.db.DB" %>
<%@page import="java.io.IOException" %>
<%@page import="java.util.Map" %>
<%@page import="org.apache.log4j.Logger" %>


<%!

	Logger log = Logger.getLogger( "gr.ntua.ivml.athena.jsp.stats_table" );
	/**
	 * Recursively print stats for every xpath underneath the given one.
	*/
     public void xpathTableRecurse(XpathHolder xp,
			Map<Long, Object[]> stats, JspWriter out, int level ) throws IOException {

	// ignore text() nodes, they are handled from parent
		if (xp.isTextNode())
			return;
	// output uri, name, len, uniqu, freq
		out.println("<tr>");
		String name = xp.getNameWithPrefix(true);
		
		out.println("<td> " );
		// indent according to place in tree
		for( int i=0; i<level*2; i++ ) out.print("&nbsp;" );

		if(xp.getChildren().size()==1 && (xp.getChildren().get(0).isTextNode())){
			out.print("&nbsp;&nbsp;<img src='images/leaf.gif'/>&nbsp;" );
		} else if( xp.getChildren().size()>0 && name.length()>0){
		    out.print("&nbsp;<img src='css/images/foldertrans.png'/>&nbsp;" );
		} else if(xp.getChildren().size()==0 || xp.isAttributeNode() || xp.getTextNode()!=null) {
			out.print("&nbsp;&nbsp;<img src='images/leaf.gif'/>&nbsp;");
		}
		out.println( name + "</td>");

		if (xp.isAttributeNode()) {
			// attribute stuff
			Object[] nums = stats.get(xp.getDbID());
			Float avg = (Float) nums[0];
			Long count = (Long) nums[1];
			out.println("<td>" + count.longValue() + "</td> <td> "
					+ xp.getCount() + "</td> <td> " + avg.floatValue() + "</td>");
		} else {
			XpathHolder text = xp.getTextNode();
			if (text == null) {
				// parent stuff without stats
				// empty cells
				out.println( "<td> </td> <td> </td> <td> </td>");
			} else {
				// text node stuff
				Object[] nums = stats.get(text.getDbID());
				Float avg = (Float) nums[0];
				Long count = (Long) nums[1];
				out.println("<td>" + count.longValue() + "</td> <td> "
						+ xp.getCount() + "</td> <td>" + avg.floatValue() + "</td>");
			}
		}
		// some extra data
		out.print( "<td>" + xp.getDbID() + "</td>" );
		out.print( "<td>" + (xp.getParent()==null?0:xp.getParent().getDbID()) + "</td>" );
		out.println("</tr>");
		for (XpathHolder child : xp.getChildren())
			xpathTableRecurse(child, stats, out, level+1);
	}
	
	// end of recursive table building
	%>
	
	
	   <%
        	try {
        		
        		Long id = Long.parseLong( (String) request.getParameter( "xmlObjectId" ));
        		XmlObject xo = (XmlObject) DB.getXmlObjectDAO().getById( id, false);
        		log.debug( "Building table for " + id );
        %>



   	  <table id="stats_table_<%=xo.getDbID() %>" >
		<% 
		   xpathTableRecurse( xo.getRoot(), xo.getAllStats(), out, 0 ); 
		%>
	  </table>
<%
	} catch (Throwable t) {
		t.printStackTrace();
	}
%>
	  