<%@ page import="gr.ntua.ivml.mint.util.Config" %>
<jsp:useBean id="theContent" class="java.lang.String" scope="request" ></jsp:useBean>

<%     
     try {
    	 		  gr.ntua.ivml.mint.xml.FullBean fullDoc=gr.ntua.ivml.mint.xml.ESEToFullBean.getFullBean(theContent);

    	 		  String[] arrsubj = (String[]) concatAll(fullDoc.getDcSubject(),fullDoc.getDctermsTemporal(), fullDoc.getDctermsSpatial(), fullDoc.getDcCoverage());
	          String[] formatArr = (String[]) concatAll(fullDoc.getDcFormat(), fullDoc.getDctermsExtent(), fullDoc.getDctermsMedium());                   
	          String[] sourceArr = fullDoc.getDcSource();
	          String[] indentifierArr = fullDoc.getDcIdentifier();
	          String[] typeArr = fullDoc.getDcType();
	          String[] publisherArr = fullDoc.getDcPublisher();
	          String[] provenanceArr = fullDoc.getDctermsProvenance();
	          String[] relationsArr = (String[]) concatAll(fullDoc.getDcRelation() , fullDoc.getDctermsReferences() , fullDoc.getDctermsIsReferencedBy()
              , fullDoc.getDctermsIsReplacedBy() , fullDoc.getDctermsIsRequiredBy() , fullDoc.getDctermsIsPartOf() , 
              fullDoc.getDctermsHasPart() , fullDoc.getDctermsReplaces() , fullDoc.getDctermsRequires()
              , fullDoc.getDctermsIsVersionOf() , fullDoc.getDctermsHasVersion()
              , fullDoc.getDctermsConformsTo() , fullDoc.getDctermsHasFormat());
	          String[] moreArr =(String[]) concatAll(indentifierArr , publisherArr , provenanceArr , arrsubj , typeArr , relationsArr);
	          
	          %>

			  <%String tl=""; 
			        if(!(fullDoc.getDcTitle().length==0)){ 
			        	tl= fullDoc.getDcTitle()[0];
			        }
			        else if(!(fullDoc.getDctermsAlternative().length==0)){ 
			        	tl= fullDoc.getDctermsAlternative()[0];
			        }
			        else if(!(fullDoc.getDcDescription().length==0)){ 
			        	if(fullDoc.getDcDescription()[0].length()>50)
			        	   tl= fullDoc.getDcDescription()[0].substring(0,50)+"...";
			        	else{
			        		tl= fullDoc.getDcDescription()[0];
			        	}
			        }
			        %>
	         <div id="wrapper">
				<table id="multi" border="0" cellspacing="10" cellpadding="10" summary="results - item detail">
				<tr>
				<td>
				<div style="width:200px; max-height: 400px; overflow-x:hidden; overflow-y:hidden; scrolling: none; text-align:center; ">
		        <%String imageRef="";
		          String imageRefHtml="";
		          //image link
		        if(!(fullDoc.getEuropeanaisShownBy().length==0)){
			        	if(fullDoc.getEuropeanaisShownBy().length==1)
			        	  imageRefHtml= fullDoc.getEuropeanaisShownBy()[0];
			        	else if(fullDoc.getEuropeanaisShownBy().length==2 && fullDoc.getEuropeanaisShownBy()[1].trim().length()>0)
			        		imageRefHtml= fullDoc.getEuropeanaisShownBy()[1];	
			        	else{imageRefHtml= fullDoc.getEuropeanaisShownBy()[0];}
			        	
			        }
		        else if(fullDoc.getEuropeanaObject()!=null && fullDoc.getEuropeanaObject().length>0){
		        	 imageRefHtml= fullDoc.getEuropeanaObject()[0];
		        }
			    if(fullDoc.getEuropeanaObject()!=null && fullDoc.getEuropeanaObject().length>0){
			        	 imageRef= fullDoc.getEuropeanaObject()[0];
			        }
			    else if(!(fullDoc.getEuropeanaisShownBy().length==0)){
		        	if(fullDoc.getEuropeanaisShownBy().length==1)
			        	  imageRef= fullDoc.getEuropeanaisShownBy()[0];
			        	else if(fullDoc.getEuropeanaisShownBy().length==2 && fullDoc.getEuropeanaisShownBy()[1].trim().length()>0)
			        		imageRef= fullDoc.getEuropeanaisShownBy()[1];	
			        	else{imageRef= fullDoc.getEuropeanaisShownBy()[0];}
			        	
			     }
			  
			     if(imageRef.length()>0){%>
                       
                       <a href="<%=imageRefHtml%>"
                          target="_blank"
                          alt="ViewInOriginalContext"
                          > <img src="<%=imageRef%>"
                                 alt="Image title: <%=tl%>"
                                 id="imgview"
                                 onload="checkSize(this.height,this);"
                                 onerror="showDefaultLarge(this,'<%=fullDoc.getEuropeanaType().toUpperCase() %>')"
                                 alt="ViewInOriginalContext"
                             />
                        
                    </a>
                    <%}else{ 
                    	 %>
                           <a href="#"
                          target="_blank"
                          alt="ViewInOriginalContext"
                          >
                             <img src="unknown"
                                 alt="Image title: <%=tl%>"
                                 id="imgview"
                                 onerror="showDefaultLarge(this,'<%=fullDoc.getEuropeanaType().toUpperCase() %>')"
                                  alt="ViewInOriginalContext"
                             />
                        
                    </a>
                     <%} %>
                </div>
				</td>
				<td>

                <div id="item-detail" class="grid_6 omega">
                <h2 class="<%=fullDoc.getEuropeanaType().toUpperCase() %>">
			      
			        <%=tl %>
			     </h2>
                
                        <%String[] titleArr = (String[]) concatAll(fullDoc.getDcTitle(),fullDoc.getDctermsAlternative());%>
                        <%if(titleArr!=null && titleArr.length>0){ %>
                            <p><strong>Title:</strong>
                                <%=printArray(titleArr,"<br />")+"<br />"%>
                            </p>
                         <%} %>

                      <% String[] dateArr = (String[]) concatAll(fullDoc.getDcDate(), fullDoc.getDctermsCreated(),fullDoc.getDctermsIssued());%>
                        <%if(dateArr!=null && dateArr.length>0){ %>
                            <p><strong>Date:</strong>
                                <%=printArray(dateArr,";&#160;") %>
                            </p>
                        <%} %>
                        <%String[] creatorArr = (String[]) concatAll(fullDoc.getDcCreator() , fullDoc.getDcContributor()); %>
                        <%if(creatorArr!=null && creatorArr.length>0){%>
                            <p><strong>Creator:</strong>
                                <%= printArray(creatorArr,";&#160;")  %>
                            </p>
                        <%} %>
                        <%String[] descriptionArr = fullDoc.getDcDescription();  %>
                        <%if(descriptionArr!=null && descriptionArr.length>0){ %>
                            <p><strong>Description:</strong>
                                <%= printArray(descriptionArr,"<br/>")+ "<br/>" %>
                            </p>
                        <%} %>
                        <%String[] languageArr = fullDoc.getDcLanguage(); %>
                        <%if(languageArr!=null && languageArr.length>0){ %>
                            <p><strong>Language:</strong>
                                <%=printArray(languageArr,";&#160;") %>
                            </p>
                        <%} %>
                         <%if(formatArr!=null && formatArr.length>0){%>
                            <p><strong>Format: </strong>
                                <%=printArray(formatArr,";&#160;")%>
                            </p>
                        <%} %>
                        <% if(sourceArr!=null && sourceArr.length>0){ %>
                            <p><strong>Source:</strong>
                                <%=printArray(sourceArr,"<br/>") +"<br/>"%>
                            </p>
                        <%} %>
                        <%String[] rightsArr = fullDoc.getDcRights(); %>
                        <%if(rightsArr!=null && rightsArr.length>0){ %>
                            <p><strong>Rights:</strong>
                                <%=printArray(rightsArr,";&#160;")%>
                            </p>
                        <%} %>
                            <p><strong>Provider:</strong>
                             <%= Config.get("mint.title") %> Project
                            </p>
                    <% if(moreArr!=null && moreArr.length>0){ %>
                    <p id="morelink">
                        <a
                            href="#"
                            class="fg-green"
                            onclick="toggleObject('moremetadata');toggleObject('lesslink');toggleObject('morelink');return false;"
                            alt="More"
                            title="More"
                        >
                         More
                        </a>
                    </p>

                    <p id="lesslink" style="display:none;">
                        <a
                            href="#"
                            class="fg-green"
                            onclick="toggleObject('lesslink');toggleObject('morelink');toggleObject('moremetadata'); return false;"
                            alt="Less"
                            title="Less"
                        >
                            Less
                        </a>
                    </p>
                    <div class="clearfix"></div>
                    <div id="moremetadata" style="display:none  ">
                        <%if(indentifierArr!=null && indentifierArr.length>0){ %>
                            <p><strong>Identifier:</strong>
                            <%=printArray(indentifierArr,";&#160;")  %>
                            </p>
                        <% }%>
                         <%if (publisherArr!=null && publisherArr.length>0){ %>
                            <p><strong>Publisher:</strong>
                            <%=printArray(publisherArr,";&#160;")%>
                            </p>
                        <%} %>
                        <%if(provenanceArr!=null && provenanceArr.length>0){%>
                            <p><strong>Provenance:</strong>
                             <%=printArray(provenanceArr,";&#160;") %>
                            </p>
                        <%}%>
                        <%if (arrsubj!=null && arrsubj.length>0) {%>
                        <p>
                            <strong>Subject:</strong>
                            <%=printArray(arrsubj,";&#160;") %>
                            
                        </p>
                        <%} %>
                        <%if (typeArr!=null && typeArr.length>0) {%>
                        <p>
                            <strong>Type:</strong>
                            <%=printArray(typeArr,";&#160;") %>
                            
                        </p>
                        <%} %>
                        <%if (relationsArr!=null && relationsArr.length>0) {%>
                        <p>
                            <strong>Relation:</strong>
                            <%=printArray(relationsArr,";&#160;") %>
                            
                        </p>
                        <%} %>
                        
                    </div>
                <% }%>
                    
                   <p class="view-orig-green">

                        <% String UrlRef = "#";%>
                        <% if(!(fullDoc.getEuropeanaisShownAt().length==0)){ 
					        	UrlRef= printArray(fullDoc.getEuropeanaisShownAt(),"");
					        }
					        else if(!(fullDoc.getEuropeanaisShownBy().length==0)){ 
					        	UrlRef= printArray(fullDoc.getEuropeanaisShownBy(),"");
					        }
                        %>
                     
                        <a
                            href="<%=UrlRef %>"
                            target="_blank"
                            alt="ViewInOriginalContext"
                            title="ViewInOriginalContext"
                        >
                            View In Original Context
                        </a>

                    </p>
                </div>
               
    </td>
</tr>
</table>
    
     </div>
<% } catch(Exception e) {
	System.out.println("Exception in eseview.jsp:"+e.getMessage());
} %>
<%!public static <T> T[] concatAll(T[] first, T[]... rest){
  int totalLength = first.length;
  for (T[] array : rest) {
    totalLength += array.length;
  }
  T[] result = java.util.Arrays.copyOf(first, totalLength);
  int offset = first.length;
  for (T[] array : rest) {
    System.arraycopy(array, 0, result, offset, array.length);
    offset += array.length;
  }
  return result;
}
%>
<%!public String printArray(String[] arr, String separator){
	String res="";
    for(int j=0; j<arr.length; j++){
      if(j>0){res+=separator;}
	  res+=arr[j];
	   
    }
    return res;   
}
%>        
	 