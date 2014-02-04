<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
	xmlns:europeana="http://www.europeana.eu/schemas/ese/"
                    xmlns:dc="http://purl.org/dc/elements/1.1/"
                    xmlns:dcterms="http://purl.org/dc/terms/"
                    xmlns:dcmitype="http://purl.org/dc/dcmitype/"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
<xsl:output method="xhtml" indent="yes" 
    doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
    doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" />



  <xsl:template match="/europeana:metadata/europeana:record">
  

  

<xsl:variable name="objclass" select="concat('object-type ',europeana:type)"/>


<xsl:variable name="doctitle">
<xsl:choose>
<xsl:when test="dc:title[@xml:lang='en']"><xsl:value-of select="dc:title[@xml:lang='en']"></xsl:value-of></xsl:when>
<xsl:otherwise><xsl:value-of select="dc:title"></xsl:value-of></xsl:otherwise>
</xsl:choose>
</xsl:variable>


<xsl:variable name="firstdesc">
<xsl:choose>
<xsl:when test="dc:description[@xml:lang='en']"><xsl:value-of select="dc:description[@xml:lang='en']"></xsl:value-of></xsl:when>
<xsl:otherwise>
 <xsl:if test="dc:description[1]">		
<xsl:value-of select="dc:description[1]"></xsl:value-of></xsl:if></xsl:otherwise>
</xsl:choose>
</xsl:variable>



<xsl:variable name="docdesc">
	<xsl:variable name="endesc">
				<xsl:if test="dc:description[@xml:lang='en']">
				<xsl:for-each select="dc:description[@xml:lang='en']">
				<xsl:value-of select="concat(.,'&lt;br/&gt;')"></xsl:value-of>
				</xsl:for-each>
				</xsl:if>
	</xsl:variable>
	<xsl:if test="string-length($endesc)&gt;0"><xsl:value-of select="$endesc"/>
				
				<xsl:for-each select="dc:description[not(@xml:lang='en')]">
				<xsl:value-of select="concat(.,'&lt;br/&gt;')"></xsl:value-of>
				</xsl:for-each>
			
	</xsl:if>
	
	<xsl:if  test="string-length($endesc)=0">
	    <xsl:if test="dc:description[1]">
		<xsl:value-of select="concat(dc:description[1],'&lt;br/&gt;')"/>
		</xsl:if>
		<xsl:for-each select="dc:description">
		<xsl:if test="position() &gt; 1"><xsl:value-of select="concat(.,'&lt;br/&gt;')"></xsl:value-of></xsl:if>
		</xsl:for-each>
		
	</xsl:if>
</xsl:variable>





<xsl:variable name="itemtitle">
<xsl:choose>
<xsl:when test="string-length($doctitle)&gt;0"><xsl:value-of select="$doctitle"></xsl:value-of></xsl:when>
<xsl:otherwise><xsl:value-of select="$firstdesc"></xsl:value-of></xsl:otherwise>
</xsl:choose>
</xsl:variable>



<xsl:variable name="imtitle">
<xsl:if test="$itemtitle"><xsl:value-of select="$itemtitle"></xsl:value-of></xsl:if>

<xsl:for-each select="dc:creator">
		<xsl:value-of select="concat(.,' | ')"/>
</xsl:for-each>
</xsl:variable>


<xsl:variable name="rightsimage">
<xsl:if test="contains(europeana:rights, 'europeana.eu')">
<xsl:choose>
<xsl:when test="contains(europeana:rights, '/rr-f')">
<xsl:value-of select="'css/esecss/images/rights/eu_free_access.jpg'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/rr-p')">
<xsl:value-of select="'css/esecss/images/rights/eu_paid_access.jpg'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/rr-r')">
<xsl:value-of select="'css/esecss/images/rights/eu_restricted_access.jpg'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/unknown')">
<xsl:value-of select="'css/esecss/images/rights/eu_unknown.jpg'"/></xsl:when></xsl:choose>
</xsl:if> 
<xsl:if test="contains(europeana:rights, 'creativecommons.org')">
<xsl:choose>
<xsl:when test="contains(europeana:rights, 'publicdomain/mark')">
<xsl:value-of select="'css/esecss/images/rights/noc.png'"/></xsl:when>
<xsl:when test="contains(europeana:rights, 'publicdomain/zero')">
<xsl:value-of select="'css/esecss/images/rights/cc0.png'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by/')">
<xsl:value-of select="'css/esecss/images/rights/cc-by.png'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by-sa/')">
<xsl:value-of select="'css/esecss/images/rights/cc-by-sa.png'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by-nc/')">
<xsl:value-of select="'css/esecss/images/rights/cc-by-nc.png'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by-nd/')">
<xsl:value-of select="'css/esecss/images/rights/cc-by-nd.png'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by-nc-nd/')">
<xsl:value-of select="'css/esecss/images/rights/cc-by-nc-nd.png'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by-nc-sa/')">
<xsl:value-of select="'css/esecss/images/rights/cc-by-nc-sa.png'"/></xsl:when>
</xsl:choose>
</xsl:if>
</xsl:variable>
  
<xsl:variable name="rightstitle">
<xsl:if test="contains(europeana:rights, '/europeana')">
<xsl:choose>
<xsl:when test="contains(europeana:rights, '/rr-f')">
<xsl:value-of select="'Rights Reserved - Free access'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/rr-p')">
<xsl:value-of select="'Rights Reserved - Paid access'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/rr-r')">
<xsl:value-of select="'Rights Reserved - Restricted access'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/unknown')">
<xsl:value-of select="'Unknown'"/></xsl:when>
</xsl:choose>
</xsl:if> 
<xsl:if test="contains(europeana:rights, 'creativecommons.org')">
<xsl:choose>
<xsl:when test="contains(europeana:rights, 'publicdomain/mark')">
<xsl:value-of select="'Public domain marked'"/></xsl:when>
<xsl:when test="contains(europeana:rights, 'publicdomain/zero')">
<xsl:value-of select="'CC Zero'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by/')">
<xsl:value-of select="'CC BY'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by-sa/')">
<xsl:value-of select="'CC BY SA'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by-nc/')">
<xsl:value-of select="'CC BY NC'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by-nd/')">
<xsl:value-of select="'CC BY ND'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by-nc-nd/')">
<xsl:value-of select="'CC BY NC ND'"/></xsl:when>
<xsl:when test="contains(europeana:rights, '/by-nc-sa/')">
<xsl:value-of select="'CC BY NC SA'"/></xsl:when>
</xsl:choose>
</xsl:if>

</xsl:variable>




<div id="ese_content">
	<ul id="navigation" class="navigation notranslate">
	<li></li>
	</ul>
		<div id="additional-info" class="sidebar">
		<div>
		  <a><xsl:attribute name="href">
		  <xsl:choose>
		  <xsl:when test="string-length(europeana:isShownBy)&gt; 0"><xsl:value-of select="europeana:isShownBy"/></xsl:when>
		  <xsl:otherwise><xsl:value-of select="europeana:isShownAt"/></xsl:otherwise>
	      </xsl:choose>
	      </xsl:attribute>
		  <xsl:attribute name="class">item-metadata image</xsl:attribute> 
		     <img style="max-width:200px">
		     <xsl:choose>
		    <xsl:when test="europeana:object"> 
		   <xsl:attribute name="src"><xsl:value-of select="europeana:object"/></xsl:attribute></xsl:when>
		   <xsl:otherwise> <xsl:attribute name="src"><xsl:value-of select="europeana:isShownBy"/></xsl:attribute></xsl:otherwise>
		   </xsl:choose>
		   <xsl:if test="dc:creator">
			<xsl:attribute name="xml:lang"><xsl:value-of select="@xml:lang" /></xsl:attribute>
			</xsl:if>
		   <xsl:attribute name="title"><xsl:value-of select="$imtitle"/></xsl:attribute>
		    <xsl:attribute name="target">_blank</xsl:attribute> 
		     </img>
		 </a></div>
   
      	<div><xsl:attribute name="class"><xsl:value-of select="lower-case($objclass)"/></xsl:attribute>&#160;</div>
      	<xsl:if test="string-length($rightsimage)&gt;0">
      	 <a><xsl:attribute name="href"><xsl:value-of select="europeana:rights"/></xsl:attribute>
      	    <xsl:attribute name="class">item-metadata</xsl:attribute>
		     <img>
		       <xsl:attribute name="src"><xsl:value-of select="$rightsimage"/></xsl:attribute>
		       <xsl:attribute name="title"><xsl:value-of select="$rightstitle"/></xsl:attribute>
		       <xsl:attribute name="target">_blank</xsl:attribute> 
		     </img>
		 </a>
      	</xsl:if>
		<div ><xsl:attribute name="class">cclear</xsl:attribute>View item at</div>
		<a style="color:#0075FF;"><xsl:attribute name="href"><xsl:value-of select="europeana:isShownAt"/></xsl:attribute>
	       <xsl:attribute name="class">underline external item-metadata</xsl:attribute> 
		   <xsl:attribute name="title"><xsl:value-of select="europeana:dataProvider"/></xsl:attribute>
		    <xsl:attribute name="target">_blank</xsl:attribute><xsl:value-of select="europeana:dataProvider"/></a>
		
		
	
		
		
		<div class="item-metadata">
		<span class="bold">Rights:&#160;</span>
		<span class="translate"><xsl:value-of select="dc:rights"/></span>
		</div>
		<div class="item-metadata">
		<span class="bold notranslate">Identifier:&#160;</span>
		<span class="notranslate"><xsl:value-of select="dc:identifier"/></span>
				</div>
		<div class="item-metadata">
		<span class="bold notranslate">Format:&#160;</span>
		<span class="translate"><xsl:value-of select="dc:format"/>&#160;<xsl:value-of select="dcterms:extent"/>&#160;<xsl:value-of select="dcterms:medium"/></span>
		
		</div>
		<xsl:if test="dc:language">
		<div class="item-metadata">
		<span class="bold notranslate">Language:&#160;</span>
		<span class="translate"><xsl:value-of select="dc:language"/></span>
		
		</div></xsl:if>
		<div class="item-metadata">
		<span class="bold notranslate">Source:&#160;</span>
		
	    <xsl:for-each select="dc:source">
				<xsl:value-of select="."/>&#160;|
			
		</xsl:for-each>
		
	
		</div>
		<xsl:if test="dcterms:provenance">
		<div class="item-metadata">
		<span class="bold notranslate">Provenance:&#160;</span>
		
	    <xsl:for-each select="dcterms:provenance">
				<xsl:value-of select="."/>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
			
		</xsl:for-each>
		
	   
		</div>
		 </xsl:if>
		
		</div>
	
	
	    <div id="excerpt">
			<div id="item-details">
			<h6><xsl:value-of select="$itemtitle"/>&#160;</h6>
			 <xsl:if test="dcterms:alternative">
			<div class="item-metadata">
			<span class="bold notranslate">Alternative title:&#160;</span>
				<xsl:value-of select="dcterms:alternative"/>
			</div>
			</xsl:if>
			 <xsl:if test="dc:creator">
			<div class="item-metadata">
			<span class="bold notranslate">Creator:&#160;</span>
			<h2>
			<xsl:for-each select="dc:creator">
				<a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
			</xsl:for-each>
			</h2>
			</div>
			</xsl:if>
			
			
			 <xsl:if test="dc:contributor">
			<div class="item-metadata">
			<span class="bold notranslate">Contributor:&#160;</span>
			<xsl:for-each select="dc:contributor">
				<a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
				</xsl:for-each>
			</div>
			</xsl:if>
			
			
			 <xsl:if test="dc:publisher">
			<div class="item-metadata">
			<span class="bold notranslate">Publisher:&#160;</span>
			<xsl:for-each select="dc:publisher">
				<a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
				</xsl:for-each>
			</div>
			</xsl:if>
			
			 <xsl:if test="dc:date or dcterms:issued or dcterms:created or dcterms:temporal or dc:coverage">
			<div class="item-metadata">
			<span class="bold notranslate">Date:&#160;</span>
			
			<xsl:for-each select="dc:date">
				<a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
			</xsl:for-each>
			<xsl:for-each select="dcterms:created">
		   <a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
		    </xsl:for-each>
		      <xsl:for-each select="dcterms:issued">
		     <a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
		    </xsl:for-each>
		      <xsl:for-each select="dcterms:temporal">
		  <a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
		    </xsl:for-each>
		      <xsl:for-each select="dc:coverage">
		    <a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
		    </xsl:for-each>
			</div>
			</xsl:if>
			
		
			<xsl:if test="dc:coverage or dcterms:spatial">
			<div class="item-metadata">
			<span class="bold notranslate">Geographical coverage:&#160;</span>
			<xsl:for-each select="dcterms:spatial">
				<a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
			</xsl:for-each>
			<xsl:for-each select="dc:coverage">
				<a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
			</xsl:for-each>
			</div>
			</xsl:if>
			
			<xsl:if test="dc:type">
			<div class="item-metadata">
			<span class="bold notranslate">Type:&#160;</span>
			
			<xsl:for-each select="dc:type">
				<a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
			</xsl:for-each>
			
			</div>
			</xsl:if>
			
			<xsl:variable name="mrelation"><xsl:value-of select="string-join((dc:relation,dcterms:isVersionOf,dcterms:hasVersion,dcterms:isReplacedBy,dcterms:replaces,dcterms:isRequiredBy,dcterms:requires,dcterms:isPartOf,dcterms:hasPart,dcterms:isReferencedBy,dcterms:references,dcterms:isFormatOf,dcterms:hasFormat,dcterms:conformsTo),'')"></xsl:value-of></xsl:variable>
			  <xsl:if test="dc:relation or dcterms:isVersionOf or dcterms:hasVersion or dcterms:isReplacedBy or dcterms:replaces or dcterms:isRequiredBy or dcterms:requires or dcterms:isPartOf or dcterms:hasPart or dcterms:isReferencedBy or dcterms:references or dcterms:isFormatOf or dcterms:hasFormat or dcterms:conformsTo">
			  
			<div class="item-metadata">
			<span class="bold notranslate">Relation:&#160;</span>
			
					<a href="#"><xsl:value-of select="$mrelation"/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
			</div>
			</xsl:if>
            
		
			
            
             <xsl:if test="dc:subject">
			<div class="item-metadata">
			<span class="bold notranslate">Subject:&#160;</span>
			<xsl:for-each select="dc:subject">
					<a href="#"><xsl:value-of select="."/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
			</xsl:for-each>
			</div>
			</xsl:if>
            
            
            <xsl:if test="string-length($docdesc)&gt;0">
			<div id="ddes" class="item-description" style="display: block;margin-bottom:10px;">
			<span class="bold notranslate">Description:&#160;</span>
			
			<xsl:if test="string-length($docdesc)&lt;240">
					<xsl:value-of disable-output-escaping="yes" select="$docdesc"/>
					
			</xsl:if>
			
			<xsl:if test="string-length($docdesc)&gt;240">
			
					<xsl:value-of disable-output-escaping="yes" select="substring($docdesc,0,240)"/><span id="ellipsis">...</span>
					<span id="restd" style="display:none"><xsl:value-of disable-output-escaping="yes" select="substring($docdesc,240)"/></span>
						
			<div>	<a class="more toggle-menu-icn" id="togglel" href="javascript:show()">See more</a></div>
			
			</xsl:if>
						
				
				</div>
				
				
			
			</xsl:if>
            
            
            <xsl:if test="europeana:dataprovider">
			<div class="item-metadata">
			<span class="bold notranslate">Data Provider:&#160;</span>
					<a href="#"><xsl:value-of select="europeana:dataprovider"/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
			</div>
			</xsl:if>
			
			         
            <xsl:if test="europeana:provider or europeana:country">
			<div class="item-metadata">
			
			
			<span class="bold notranslate">Provider:&#160;</span>
			
					<a href="#"><xsl:value-of select="europeana:provider"/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a>
		    
		    <xsl:if test="europeana:country"><a href="#"><xsl:value-of select="europeana:country"/></a>&#160;|<a class="external-services toggle-menu-icn">&#160;</a></xsl:if>
			</div>
			</xsl:if>
			
			
			
			</div>
		</div>	

		</div>

  </xsl:template>
</xsl:stylesheet>
