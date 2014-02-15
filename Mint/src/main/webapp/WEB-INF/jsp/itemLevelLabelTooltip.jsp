<%@page import="java.util.HashMap"%>
<%@page import="net.sf.json.*"%>
<%@page import="gr.ntua.ivml.mint.mapping.*"%>

<%
	String command = request.getParameter("command");
	out.clear();
	response.setContentType("text/plain; charset=UTF-8");
	
	if(command != null) {
		MappingManager mappings=new MappingManager();
		String input = request.getParameter("upload");
		mappings.setInputSchema(input);
		mappings.setDataUploadId(input);
		

		if(command.equals("getTooltip")) {
			String element = request.getParameter("element");
			element = element.replace("_2_", "_1_");
				if(element != null) {
				String tooltip = "";
				
				try {
					tooltip = mappings.getItemLevelElementTooltip(element);
				} catch(Exception e) {
					tooltip = "Error loading data...";
					e.printStackTrace();
				}
								
				out.println(new JSONObject()
					.element("tooltip", tooltip)
					.element("element", element)
				);
			} else {
				out.println(new JSONObject().element("error", "ajax command tooltip: no element"));
			}
		}
		
		
	} else {
		out.println(new JSONObject().element("error", "error: no command"));
	}
%>
