<%-- 
    Document   : elementGraph
    Created on : 08-Oct-2009, 23:40:10
    Author     : achristaki
--%>
<%@page import="java.util.Iterator"%>
<%@page import="gr.ntua.ivml.mint.xml.Statistics" %>
<%@page import="gr.ntua.ivml.mint.persistent.DataUpload" %>
<%@page import="gr.ntua.ivml.mint.db.DB" %>
<%@page import="java.util.Map" %>
<%@page contentType="text/plain" pageEncoding="UTF-8"%>
        <%
	    out.clear();
	    response.setContentType("text/plain; charset=UTF-8");

      	String uploadId = request.getParameter("uploadId");
	    DataUpload dataUpload = DB.getDataUploadDAO().findById(Long.parseLong(uploadId), false);
	    Statistics stats = dataUpload.getXmlObject().getStats();
            String elem = request.getParameter("fieldName");
            Map<String, Integer> res = stats.getElementValues(elem);
            Iterator<String> itr = res.keySet().iterator();
            int cardinality = res.keySet().size();
            
            if(cardinality > 50){
            	out.print("document.getElementById(\"graphs\").innerHTML = \" \"</b></big>");
            	//out.print("<big><b>Chart not available!</b></big>");
            }else{
            out.print("new Proto.Chart($('graphs'),[");
            int counter = 1;
            while(itr.hasNext()){
                String key = itr.next();
                int value = res.get(key);
                out.print("{ data: [["+counter+","+value+"]],label:\""+key+"\"}");
                if(itr.hasNext()){
                    out.print(",");
                }
            }
            out.print("],{pies: {show: true, autoScale: true},legend: {show: true}});");
        	}
        %>
