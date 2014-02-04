<?xml version="1.0" encoding="UTF-8"?>
<!--

  XSL Transform to convert LIDO XML data, according to http://www.lido-schema.org/schema/v1.0/lido-v1.0.xsd, 
	into ESE XML, according to http://www.europeana.eu/schemas/ese/ESE-V3.4.xsd

  By Regine Stein, Deutsches Dokumentationszentrum für Kunstgeschichte - Bildarchiv Foto Marburg, Philipps-Universität Marburg
  Provided for Linked Heritage project, 2011-11-17. 

 Mandatory-only: Only ESE v3.4 mandatory elements are transformed. 

 See actual transforms in lido-v1.0-to-ese-v3.4-templates-v1.xsl
-->
<xsl:stylesheet version="2.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:ns0="http://www.lido-schema.org" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:fn="http://www.w3.org/2005/xpath-functions" 
	xmlns:xml="http://www.w3.org/XML/1998/namespace" 
	xmlns:lido="http://www.lido-schema.org" 
    xmlns:europeana="http://www.europeana.eu/schemas/ese/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:dcterms="http://purl.org/dc/terms/"
	exclude-result-prefixes="lido xs fn">

	<xsl:import href="lido-v1.0-to-ese-v3.4-templates-v1.xsl" />

	<xsl:output method="xml" encoding="UTF-8" indent="yes"/>

	
	<xsl:template match="/">
		<europeana:metadata 
			xmlns:europeana="http://www.europeana.eu/schemas/ese/" 
			xmlns:dcmitype="http://purl.org/dc/dcmitype/" 
			xmlns:dc="http://purl.org/dc/elements/1.1/" 
			xmlns:dcterms="http://purl.org/dc/terms/">
			<xsl:attribute name="xsi:schemaLocation" namespace="http://www.w3.org/2001/XMLSchema-instance" select="'http://www.europeana.eu/schemas/ese/ http://www.europeana.eu/schemas/ese/ESE-V3.4.xsd'"/>
			<xsl:for-each select=".//lido:lido">
				
				<xsl:choose>

				<!-- multipleResources: create ESE record for EACH resource -->
				<xsl:when test="contains(lido:administrativeMetadata[1]/lido:recordWrap/lido:recordType/lido:term[1], '/multipleResources')">
				<xsl:for-each select="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet[count(not(lido:resourceRepresentation/lido:linkResource='')) &gt; 0]">
					<europeana:record>

					<xsl:for-each select="../../../lido:descriptiveMetadata">
						<xsl:call-template name="descriptiveMetadata-mandatory-only" />
					</xsl:for-each>
					
<!-- Europeana elements in requested order --> 
					<xsl:choose>
						<xsl:when test="lido:resourceRepresentation[@lido:type='image_thumb']">
							<europeana:object>
								<xsl:value-of select="lido:resourceRepresentation[@lido:type='image_thumb']/lido:linkResource[1]" />
							</europeana:object>
						</xsl:when>
						<xsl:otherwise>
						<xsl:for-each select="lido:resourceRepresentation[string-length(lido:linkResource)&gt;0]">
							<xsl:if test="position() = 1">
								<europeana:object>
									<xsl:value-of select="lido:linkResource" />
								</europeana:object>
							</xsl:if>
						</xsl:for-each>
						</xsl:otherwise>
					</xsl:choose>

					<europeana:provider><xsl:value-of select="$provider" /></europeana:provider>

					<europeana:type>
						<xsl:value-of select="../../../lido:descriptiveMetadata[1]/lido:objectClassificationWrap/lido:classificationWrap/lido:classification[@lido:type = 'europeana:type'][1]/lido:term[string-length(.)&gt;0][1]" />
					</europeana:type>

					<europeana:rights>
						<xsl:choose>
							<xsl:when test="lido:rightsResource/lido:rightsType/lido:term[@lido:pref='preferred'][starts-with(., 'http://www.europeana.eu/rights') or starts-with(., 'http://creativecommons.org')]">
								<xsl:value-of select="lido:rightsResource/lido:rightsType/lido:term[@lido:pref='preferred']" />
							</xsl:when>
							<xsl:otherwise>http://www.europeana.eu/rights/unknown/</xsl:otherwise>
						</xsl:choose>
					</europeana:rights>

					<europeana:dataProvider>
					<xsl:choose>
						<xsl:when test="../../lido:recordWrap/lido:recordSource[@lido:type='europeana:dataProvider']">
							<xsl:value-of select="../../lido:recordWrap/lido:recordSource[@lido:type='europeana:dataProvider']/lido:legalBodyName/lido:appellationValue[string-length(.)&gt;0][1]" />
						</xsl:when>
						<xsl:otherwise>
						<xsl:for-each select="../../lido:recordWrap/lido:recordSource/lido:legalBodyName/lido:appellationValue[string-length(.)&gt;0][1]">
							<xsl:value-of select="." />
							<xsl:if test="position()!=last()">
								<xsl:text> / </xsl:text>
							</xsl:if>
						</xsl:for-each>
						</xsl:otherwise>
					</xsl:choose>
					</europeana:dataProvider>

					<xsl:for-each select="../../lido:recordWrap/lido:recordInfoSet/lido:recordInfoLink[string-length(.)&gt;0]">
						<xsl:if test="position() = 1">
							<europeana:isShownAt>
								<xsl:value-of select="." />
							</europeana:isShownAt>
						</xsl:if>
					</xsl:for-each>

					<xsl:choose>
						<xsl:when test="lido:resourceRepresentation[@lido:type='image_master']">
							<europeana:isShownBy>
								<xsl:value-of select="lido:resourceRepresentation[@lido:type='image_master']/lido:linkResource[1]" />
							</europeana:isShownBy>
						</xsl:when>
						<xsl:when test="not(../../lido:recordWrap/lido:recordInfoSet/lido:recordInfoLink)">
							<xsl:for-each select="lido:resourceRepresentation[string-length(lido:linkResource)&gt;0]">
							<xsl:if test="position() = 1">
								<europeana:isShownBy>
									<xsl:value-of select="lido:linkResource" />
								</europeana:isShownBy>
							</xsl:if>
							</xsl:for-each>
						</xsl:when>
					</xsl:choose>
				</europeana:record>											
								</xsl:for-each>
					</xsl:when>

					<xsl:otherwise>

				<europeana:record>

					<xsl:for-each select="lido:descriptiveMetadata">
						<xsl:call-template name="descriptiveMetadata-mandatory-only" />
					</xsl:for-each>

<!-- Europeana elements in requested order --> 

						<xsl:for-each select="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet/lido:resourceRepresentation[@lido:type='image_thumb'][string-length(lido:linkResource)&gt;0]">
							<xsl:if test="position() = 1">
								<europeana:object>
									<xsl:value-of select="lido:linkResource" />
								</europeana:object>
							</xsl:if>
						</xsl:for-each>

					<europeana:provider><xsl:value-of select="$provider" /></europeana:provider>

					<europeana:type>
						<xsl:value-of select="lido:descriptiveMetadata[1]/lido:objectClassificationWrap/lido:classificationWrap/lido:classification[@lido:type = 'europeana:type'][1]/lido:term[string-length(.)&gt;0][1]" />
					</europeana:type>

					<europeana:rights>
					<xsl:choose>
						<xsl:when test="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet[lido:resourceRepresentation[@lido:type='image_master' and string-length(lido:linkResource)&gt;0] and lido:rightsResource/lido:rightsType/lido:term[@lido:pref='preferred'][starts-with(., 'http://www.europeana.eu/rights') or starts-with(., 'http://creativecommons.org')]]">
							<xsl:value-of select="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet[lido:resourceRepresentation[@lido:type='image_master'][string-length(lido:linkResource)&gt;0]]/lido:rightsResource/lido:rightsType/lido:term[@lido:pref='preferred'][1]" />
						</xsl:when>
						<xsl:when test="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet[lido:resourceRepresentation[string-length(lido:linkResource)&gt;0] and lido:rightsResource/lido:rightsType/lido:term[@lido:pref='preferred'][starts-with(., 'http://www.europeana.eu/rights') or starts-with(., 'http://creativecommons.org')]]">
							<xsl:value-of select="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet[lido:resourceRepresentation[string-length(lido:linkResource)&gt;0]]/lido:rightsResource/lido:rightsType/lido:term[@lido:pref='preferred'][1]" />
						</xsl:when>
						<xsl:otherwise>http://www.europeana.eu/rights/unknown/</xsl:otherwise>
					</xsl:choose>
					</europeana:rights>

					<europeana:dataProvider>
					<xsl:choose>
						<xsl:when test="lido:administrativeMetadata[1]/lido:recordWrap/lido:recordSource[@lido:type='europeana:dataProvider']">
							<xsl:value-of select="lido:administrativeMetadata[1]/lido:recordWrap/lido:recordSource[@lido:type='europeana:dataProvider']/lido:legalBodyName/lido:appellationValue[string-length(.)&gt;0][1]" />
						</xsl:when>
						<xsl:otherwise>
						<xsl:for-each select="lido:administrativeMetadata[1]/lido:recordWrap/lido:recordSource/lido:legalBodyName/lido:appellationValue[string-length(.)&gt;0][1]">
							<xsl:value-of select="." />
							<xsl:if test="position()!=last()">
								<xsl:text> / </xsl:text>
							</xsl:if>
						</xsl:for-each>
						</xsl:otherwise>
					</xsl:choose>
					</europeana:dataProvider>

					<xsl:for-each select="lido:administrativeMetadata[1]/lido:recordWrap/lido:recordInfoSet/lido:recordInfoLink[string-length(.)&gt;0]">
						<xsl:if test="position() = 1">
							<europeana:isShownAt>
								<xsl:value-of select="." />
							</europeana:isShownAt>
						</xsl:if>
					</xsl:for-each>

					<xsl:choose>
						<xsl:when test="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet/lido:resourceRepresentation[@lido:type='image_master'][string-length(lido:linkResource)&gt;0]">
						<xsl:for-each select="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet/lido:resourceRepresentation[@lido:type='image_master'][string-length(lido:linkResource)&gt;0]">
							<xsl:if test="position() = 1">
								<europeana:isShownBy>
									<xsl:value-of select="lido:linkResource" />
								</europeana:isShownBy>
							</xsl:if>
						</xsl:for-each>
						</xsl:when>
						<xsl:otherwise>
							<xsl:for-each select="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet/lido:resourceRepresentation[string-length(lido:linkResource)&gt;0]">
								<xsl:if test="position() = 1">
									<europeana:isShownBy>
										<xsl:value-of select="lido:linkResource" />
									</europeana:isShownBy>
								</xsl:if>
							</xsl:for-each>
						</xsl:otherwise>
					</xsl:choose>

				</europeana:record>					
					</xsl:otherwise>
				</xsl:choose>
			</xsl:for-each>
		</europeana:metadata>
	</xsl:template>
	
	
</xsl:stylesheet>