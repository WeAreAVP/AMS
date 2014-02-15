<?xml version="1.0" encoding="UTF-8"?>
<!--

  XSL Transform to convert LIDO XML data, according to http://www.lido-schema.org/schema/v1.0/lido-v1.0.xsd, 
	into ESE XML, according to http://www.europeana.eu/schemas/ese/ESE-V3.3.xsd

  By Regine Stein, Deutsches Dokumentationszentrum für Kunstgeschichte - Bildarchiv Foto Marburg, Philipps-Universität Marburg
  Provided for ATHENA project, 2011-02-27. 

  Note: Handling of language variants is as follows:
	For all LIDO elements that map to a DC element any language variant available is transformed to ESE, each qualified by xml:lang attribute, thereby 
		providing a mechanism to control search and display for different languages.

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
	<xsl:output method="xml" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<europeana:metadata 
			xmlns:europeana="http://www.europeana.eu/schemas/ese/" 
			xmlns:dcmitype="http://purl.org/dc/dcmitype/" 
			xmlns:dc="http://purl.org/dc/elements/1.1/" 
			xmlns:dcterms="http://purl.org/dc/terms/">
			<xsl:attribute name="xsi:schemaLocation" namespace="http://www.w3.org/2001/XMLSchema-instance" select="'http://www.europeana.eu/schemas/ese/ http://www.europeana.eu/schemas/ese/ESE-V3.3.xsd'"/>
			<xsl:for-each select=".//lido:lido">
				
				<xsl:choose>

					<xsl:when test="contains(lido:administrativeMetadata[1]/lido:recordWrap/lido:recordType/lido:term[1], '/multipleResources') 
						and 						lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet/lido:resourceRepresentation[@lido:type='image_thumb'][string-length(lido:linkResource)&gt;0]">

						<xsl:for-each select="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet/lido:resourceRepresentation[@lido:type='image_thumb'][string-length(lido:linkResource)&gt;0]">

							<!-- multipleResources: create ESE record for each image_thumb resource -->
				<europeana:record>

					<xsl:for-each select="../../../../lido:descriptiveMetadata">
						<xsl:call-template name="descriptiveMetadata" />
					</xsl:for-each>

					<!-- specific resource view information -->
					<xsl:for-each select="..">
						<xsl:call-template name="resourceView" />
					</xsl:for-each>

					<xsl:for-each select="../../..">
						<xsl:call-template name="work" />
						<xsl:call-template name="record" />
					</xsl:for-each>
					<xsl:for-each select="..">
						<xsl:call-template name="resource" />
					</xsl:for-each>
					
					<xsl:for-each select="../../../..//lido:term[@lido:addedSearchTerm = 'yes'][string-length(.)&gt;0]
						| ../../../..//lido:appellationValue[(@lido:pref = 'alternate')][string-length(.)&gt;0]
						| ../../../..//lido:legalBodyName[not(position() = 1)]/lido:appellationValue[string-length(.)&gt;0]
						| ../../../..//lido:partOfPlace//lido:appellationValue[string-length(.)&gt;0]
						| ../../../..//lido:placeClassification/lido:term[string-length(.)&gt;0]
						">
							<europeana:unstored>
								<xsl:value-of select="." />
							</europeana:unstored>
					</xsl:for-each>

<!-- Europeana elements in requested order --> 

					<europeana:object>
						<xsl:value-of select="lido:linkResource" />
					</europeana:object>

					<europeana:provider>ATHENA project</europeana:provider>

					<europeana:type>
						<!-- no default value for europeana:type as decided at Ljubjlana plenary -->
						<!--xsl:choose>
							<xsl:when test="../../../../lido:descriptiveMetadata/lido:objectClassificationWrap/lido:classificationWrap/lido:classification[@lido:type = 'europeana:type']/lido:term">
							<xsl:value-of select="../../../../lido:descriptiveMetadata/lido:objectClassificationWrap/lido:classificationWrap/lido:classification[@lido:type = 'europeana:type']/lido:term[position() = 1]" />
							</xsl:when>
							<xsl:otherwise>IMAGE</xsl:otherwise>
						</xsl:choose-->
						<xsl:value-of select="../../../../lido:descriptiveMetadata[1]/lido:objectClassificationWrap/lido:classificationWrap/lido:classification[@lido:type = 'europeana:type'][1]/lido:term[string-length(.)&gt;0][1]" />
					</europeana:type>

					<europeana:dataProvider>
					<xsl:choose>
						<xsl:when test="../../../lido:recordWrap/lido:recordSource[@lido:type='europeana:dataProvider']">
							<xsl:value-of select="../../../lido:recordWrap/lido:recordSource[@lido:type='europeana:dataProvider']/lido:legalBodyName/lido:appellationValue[string-length(.)&gt;0][1]" />
						</xsl:when>
						<xsl:when test="../../../lido:recordWrap/lido:recordSource/lido:legalBodyName/lido:appellationValue[@lido:label='europeana:dataProvider']">
							<xsl:value-of select="../../../lido:recordWrap/lido:recordSource/lido:legalBodyName/lido:appellationValue[@lido:label='europeana:dataProvider'][string-length(.)&gt;0][1]" />
						</xsl:when>
						<xsl:otherwise>
						<xsl:for-each select="../../../lido:recordWrap/lido:recordSource/lido:legalBodyName/lido:appellationValue[string-length(.)&gt;0][1]">
							<xsl:value-of select="." />
							<xsl:if test="position()!=last()">
								<xsl:text> / </xsl:text>
							</xsl:if>
						</xsl:for-each>
						</xsl:otherwise>
					</xsl:choose>
					</europeana:dataProvider>

					<xsl:for-each select="../../../lido:recordWrap/lido:recordInfoSet/lido:recordInfoLink[string-length(.)&gt;0]">
						<xsl:if test="position() = 1">
							<europeana:isShownAt>
								<xsl:value-of select="." />
							</europeana:isShownAt>
						</xsl:if>
					</xsl:for-each>

					<xsl:if test="not(../../../lido:recordWrap/lido:recordInfoSet/lido:recordInfoLink)">
						<europeana:isShownBy>
							<xsl:value-of select="lido:linkResource" />
						</europeana:isShownBy>
					</xsl:if>

				</europeana:record>
								</xsl:for-each>
							</xsl:when>

							<!-- multipleResources: create ESE record for EACH resource -->
							<xsl:when test="contains(lido:administrativeMetadata[1]/lido:recordWrap/lido:recordType/lido:term[1], '/multipleResources')">
								<xsl:for-each select="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet[count(not(lido:resourceRepresentation/lido:linkResource='')) &gt; 0]">
				<europeana:record>

					<xsl:for-each select="../../../lido:descriptiveMetadata">
						<xsl:call-template name="descriptiveMetadata" />
					</xsl:for-each>
					
					<!-- specific resource view information -->
					<xsl:call-template name="resourceView" />

					<xsl:for-each select="../..">
						<xsl:call-template name="work" />
						<xsl:call-template name="record" />
					</xsl:for-each>
					<xsl:call-template name="resource" />
					
					<xsl:for-each select="../../..//lido:term[@lido:addedSearchTerm = 'yes'][string-length(.)&gt;0]
						| ../../..//lido:appellationValue[@lido:pref = 'alternate'][string-length(.)&gt;0]
						| ../../..//lido:legalBodyName[not(position() = 1)]/lido:appellationValue[string-length(.)&gt;0]
						| ../../..//lido:partOfPlace//lido:appellationValue[string-length(.)&gt;0]
						| ../../..//lido:placeClassification/lido:term[string-length(.)&gt;0]
						">
							<europeana:unstored>
								<xsl:value-of select="." />
							</europeana:unstored>
					</xsl:for-each>

<!-- Europeana elements in requested order --> 
						<xsl:for-each select="lido:resourceRepresentation[string-length(lido:linkResource)&gt;0]">
							<xsl:if test="position() = 1">
								<europeana:object>
									<xsl:value-of select="lido:linkResource" />
								</europeana:object>
							</xsl:if>
						</xsl:for-each>

					<europeana:provider>ATHENA project</europeana:provider>

					<europeana:type>
						<xsl:value-of select="../../../lido:descriptiveMetadata[1]/lido:objectClassificationWrap/lido:classificationWrap/lido:classification[@lido:type = 'europeana:type'][1]/lido:term[string-length(.)&gt;0][1]" />
					</europeana:type>

					<europeana:dataProvider>
					<xsl:choose>
						<xsl:when test="../../lido:recordWrap/lido:recordSource[@lido:type='europeana:dataProvider']">
							<xsl:value-of select="../../lido:recordWrap/lido:recordSource[@lido:type='europeana:dataProvider']/lido:legalBodyName/lido:appellationValue[string-length(.)&gt;0][1]" />
						</xsl:when>
						<xsl:when test="../../lido:recordWrap/lido:recordSource/lido:legalBodyName/lido:appellationValue[@lido:label='europeana:dataProvider']">
							<xsl:value-of select="../../lido:recordWrap/lido:recordSource/lido:legalBodyName/lido:appellationValue[@lido:label='europeana:dataProvider'][string-length(.)&gt;0][1]" />
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

					<xsl:if test="not(../../lido:recordWrap/lido:recordInfoSet/lido:recordInfoLink)">
						<xsl:for-each select="lido:resourceRepresentation[string-length(lido:linkResource)&gt;0]">
							<xsl:if test="position() = 1">
								<europeana:isShownBy>
									<xsl:value-of select="lido:linkResource" />
								</europeana:isShownBy>
							</xsl:if>
						</xsl:for-each>
					</xsl:if>

				</europeana:record>											
								</xsl:for-each>
					</xsl:when>

					<xsl:otherwise>

				<europeana:record>

					<xsl:for-each select="lido:descriptiveMetadata">
						<xsl:call-template name="descriptiveMetadata" />
					</xsl:for-each>

					<xsl:for-each select="lido:administrativeMetadata">
						<xsl:call-template name="work" />
						<xsl:call-template name="record" />
					</xsl:for-each>
					<xsl:for-each select="lido:administrativeMetadata">
						<xsl:for-each select="lido:resourceWrap/lido:resourceSet[count(not(lido:resourceRepresentation/lido:linkResource='')) &gt; 0][1]">
							<xsl:call-template name="resource" />
							<xsl:call-template name="resourceView" />
						</xsl:for-each>
					</xsl:for-each>
					
					<xsl:for-each select=".//lido:term[@lido:addedSearchTerm = 'yes'][string-length(.)&gt;0]
						| .//lido:appellationValue[@lido:pref = 'alternate'][string-length(.)&gt;0]
						| .//lido:legalBodyName[not(position() = 1)]/lido:appellationValue[string-length(.)&gt;0]
						| .//lido:partOfPlace//lido:appellationValue[string-length(.)&gt;0]
						| .//lido:placeClassification/lido:term[string-length(.)&gt;0]
						">
							<europeana:unstored>
								<xsl:value-of select="." />
							</europeana:unstored>
					</xsl:for-each>

<!-- Europeana elements in requested order --> 

						<xsl:for-each select="lido:administrativeMetadata[1]/lido:resourceWrap/lido:resourceSet/lido:resourceRepresentation[@lido:type='image_thumb'][string-length(lido:linkResource)&gt;0]">
							<xsl:if test="position() = 1">
								<europeana:object>
									<xsl:value-of select="lido:linkResource" />
								</europeana:object>
							</xsl:if>
						</xsl:for-each>

					<europeana:provider>ATHENA project</europeana:provider>

					<europeana:type>
						<xsl:value-of select="lido:descriptiveMetadata[1]/lido:objectClassificationWrap/lido:classificationWrap/lido:classification[@lido:type = 'europeana:type'][1]/lido:term[string-length(.)&gt;0][1]" />
					</europeana:type>

					<europeana:dataProvider>
					<xsl:choose>
						<xsl:when test="lido:administrativeMetadata[1]/lido:recordWrap/lido:recordSource[@lido:type='europeana:dataProvider']">
							<xsl:value-of select="lido:administrativeMetadata[1]/lido:recordWrap/lido:recordSource[@lido:type='europeana:dataProvider']/lido:legalBodyName/lido:appellationValue[string-length(.)&gt;0][1]" />
						</xsl:when>
						<xsl:when test="lido:administrativeMetadata[1]/lido:recordWrap/lido:recordSource/lido:legalBodyName/lido:appellationValue[@lido:label='europeana:dataProvider']">
							<xsl:value-of select="lido:administrativeMetadata[1]/lido:recordWrap/lido:recordSource/lido:legalBodyName/lido:appellationValue[@lido:label='europeana:dataProvider'][string-length(.)&gt;0][1]" />
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
						<xsl:when test="lido:administrativeMetadata/lido:recordWrap/lido:recordInfoSet/lido:recordInfoLink[string-length(.)&gt;0]" />
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
	
	<xsl:template name="descriptiveMetadata">

					<xsl:variable name="desclang">
						<xsl:value-of select="@xml:lang" />
					</xsl:variable>

					<xsl:for-each select="lido:objectIdentificationWrap/lido:titleWrap/lido:titleSet/lido:appellationValue[string-length(.)&gt;0]">
					<xsl:choose>
						<xsl:when test=" @lido:pref = 'alternate'">
							<dcterms:alternative>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
								<xsl:value-of select="."/>
							</dcterms:alternative>
						</xsl:when>
						<xsl:otherwise>
							<dc:title>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
								<xsl:value-of select="."/>
							</dc:title>
						</xsl:otherwise>
					</xsl:choose>
					</xsl:for-each>

					<xsl:for-each select="lido:objectClassificationWrap/lido:objectWorkTypeWrap/lido:objectWorkType/lido:term[string-length(.)&gt;0][not(@lido:addedSearchTerm = 'yes')]">
						<dc:type>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
							<xsl:value-of select="."/>
							<xsl:if test="../@lido:type"> [<xsl:value-of select="../@lido:type" />]</xsl:if>
						</dc:type>
					</xsl:for-each>

					<xsl:for-each select="lido:objectClassificationWrap/lido:classificationWrap/lido:classification[not(contains(@lido:type, 'europeana:')) and not(contains(@lido:type, 'euroepana:'))]/lido:term[string-length(.)&gt;0][not(@lido:addedSearchTerm = 'yes')]">
						<xsl:choose>
							<xsl:when test="lower-case(../@lido:type) = 'colour'
								or lower-case(../@lido:type) = 'age'
								or lower-case(../@lido:type) = 'object-status'
								" />
							<xsl:otherwise>
								<dc:type>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
									<xsl:value-of select="."/>
									<xsl:if test="../@lido:type"> [<xsl:value-of select="../@lido:type" />]</xsl:if>
								</dc:type>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:for-each>

					<xsl:for-each select="lido:objectIdentificationWrap/lido:objectDescriptionWrap/lido:objectDescriptionSet[lido:descriptiveNoteValue/string-length(.)&gt;0]">
						<dc:description>
							<xsl:attribute name="xml:lang">
								<xsl:choose>
									<xsl:when test="lido:descriptiveNoteValue[1]/@xml:lang"><xsl:value-of select="lido:descriptiveNoteValue[1]/@xml:lang" /></xsl:when>
									<xsl:otherwise><xsl:value-of select="$desclang" /></xsl:otherwise>
								</xsl:choose>
							</xsl:attribute>
							<xsl:if test="@lido:type">
								<xsl:value-of select="concat(@lido:type, ': ')"/>
							</xsl:if>
							<xsl:for-each select="lido:descriptiveNoteValue">
								<xsl:value-of select="concat(., ' ')"/>
							</xsl:for-each>
							<xsl:if test="string-length(lido:sourceDescriptiveNote[1])&gt;0">
								<xsl:value-of select="concat(' (', lido:sourceDescriptiveNote[1], ')')" />
							</xsl:if>
							<xsl:if test="string-length(lido:descriptiveNoteID[1])&gt;0">
								<xsl:value-of select="concat(' (ID: ', lido:descriptiveNoteID[1], ')')" />
							</xsl:if>
						</dc:description>
					</xsl:for-each>

					<xsl:for-each select="lido:objectIdentificationWrap/lido:inscriptionsWrap/lido:inscriptions">
						<xsl:variable name="type">
							<xsl:choose>
								<xsl:when test="@lido:type"><xsl:value-of select="concat(@lido:type, ': ')" /></xsl:when>
								<xsl:when test="$desclang eq 'de' or $desclang eq 'deu' or $desclang eq 'ger'">Inschrift: </xsl:when>
								<xsl:otherwise>Inscription: </xsl:otherwise>
							</xsl:choose>
						</xsl:variable>
						<xsl:variable name="instrans">
							<xsl:choose>
								<xsl:when test="$desclang eq 'de' or $desclang eq 'deu' or $desclang eq 'ger'">Transkription</xsl:when>
								<xsl:otherwise>transcription</xsl:otherwise>
							</xsl:choose>
						</xsl:variable>
						<xsl:variable name="insdesc">
							<xsl:choose>
								<xsl:when test="$desclang eq 'de' or $desclang eq 'deu' or $desclang eq 'ger'">Beschreibung</xsl:when>
								<xsl:otherwise>description</xsl:otherwise>
							</xsl:choose>
						</xsl:variable>
						<xsl:for-each select="lido:inscriptionTranscription">
						<dc:description>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
							<xsl:value-of select="concat($type, ., ' [', $instrans, ']')" />
						</dc:description>
						</xsl:for-each>
						<xsl:for-each select="lido:inscriptionDescription[lido:descriptiveNoteValue/string-length(.)&gt;0]">
						<dc:description>
							<xsl:attribute name="xml:lang">
								<xsl:choose>
									<xsl:when test="lido:descriptiveNoteValue[1]/@xml:lang"><xsl:value-of select="lido:descriptiveNoteValue[1]/@xml:lang" /></xsl:when>
									<xsl:otherwise><xsl:value-of select="$desclang" /></xsl:otherwise>
								</xsl:choose>
							</xsl:attribute>
							<xsl:value-of select="$type"/>
							<xsl:for-each select="lido:descriptiveNoteValue">
								<xsl:value-of select="concat(., ' ')"/>
							</xsl:for-each>
							<xsl:if test="string-length(lido:sourceDescriptiveNote[1])&gt;0">
								<xsl:value-of select="concat(' (', lido:sourceDescriptiveNote[1], ')')" />
							</xsl:if>
							<xsl:if test="string-length(lido:descriptiveNoteID[1])&gt;0">
								<xsl:value-of select="concat(' (ID: ', lido:descriptiveNoteID[1], ')')" />
							</xsl:if>
							<xsl:value-of select="concat(' [', $insdesc, ']')" />
						</dc:description>
						</xsl:for-each>
					</xsl:for-each>

					<xsl:for-each select="lido:objectIdentificationWrap/lido:displayStateEditionWrap/*[string-length(.)&gt;0]">
						<dc:description>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
							<xsl:value-of select="concat(substring-after(name(), 'display'), ': ', .)" />
						</dc:description>
					</xsl:for-each>

					<xsl:for-each select="lido:objectIdentificationWrap/lido:repositoryWrap/lido:repositorySet[not(@lido:type='former')]/lido:workID[string-length(.)&gt;0]">
						<dc:identifier>
						   <xsl:attribute name="xml:lang"><xsl:value-of select="$desclang" /></xsl:attribute>
						   <xsl:value-of select="concat(@lido:type, ' ',.)"/>						           
						</dc:identifier>
					</xsl:for-each>

					<xsl:for-each select="lido:objectIdentificationWrap/lido:repositoryWrap/lido:repositorySet[not(.//lido:appellationValue='')]">
						<xsl:variable name="qualifier">
							<xsl:choose>
								<xsl:when test="@lido:type='former' and ($desclang eq 'de' or $desclang eq 'deu' or $desclang eq 'ger')">Frühere Aufbewahrung/Standort: </xsl:when>
								<xsl:when test="@lido:type='former'">Former Repository/Location: </xsl:when>
								<xsl:when test="$desclang eq 'de' or $desclang eq 'deu' or $desclang eq 'ger'">Aufbewahrung/Standort: </xsl:when>
								<xsl:otherwise>Repository/Location: </xsl:otherwise>
							</xsl:choose>
						</xsl:variable>
						<dc:description>
							<xsl:attribute name="xml:lang">
								<xsl:choose>
									<xsl:when test="lido:repositoryName/lido:legalBodyName[count(not(lido:appellationValue='')) &gt; 0][1]/lido:appellationValue[1]/@xml:lang"><xsl:value-of select="lido:repositoryName/lido:legalBodyName[count(not(lido:appellationValue='')) &gt; 0][1]/lido:appellationValue[1]/@xml:lang" /></xsl:when>
									<xsl:otherwise><xsl:value-of select="$desclang" /></xsl:otherwise>
								</xsl:choose>
							</xsl:attribute>
						   <xsl:value-of select="concat($qualifier, lido:repositoryName/lido:legalBodyName[count(not(lido:appellationValue='')) &gt; 0][1]/lido:appellationValue[1], ' ', lido:repositoryLocation/lido:namePlaceSet[count(not(lido:appellationValue='')) &gt; 0][1]/lido:appellationValue[1])"/>
						</dc:description>
					</xsl:for-each>

						<xsl:for-each select="lido:objectIdentificationWrap/lido:objectMeasurementsWrap/lido:objectMeasurementsSet">
							<xsl:choose>
								<xsl:when test="lido:displayObjectMeasurements[string-length(.)&gt;0]">
									<xsl:for-each select="lido:displayObjectMeasurements[string-length(.)&gt;0]">
									<dcterms:extent>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="."/>
									</dcterms:extent>
									</xsl:for-each>
								</xsl:when>
								<xsl:when test="lido:objectMeasurements">
								<xsl:for-each select="lido:objectMeasurements">
									<xsl:variable name="qualifier">
										<xsl:choose>
											<xsl:when test="lido:qualifierMeasurements[string-length(.)&gt;0]"><xsl:value-of select="concat(lido:qualifierMeasurements[string-length(.)&gt;0][1], ' ')" /></xsl:when>
											<xsl:otherwise />
										</xsl:choose>
									</xsl:variable>
									<xsl:for-each select="lido:measurementsSet[string-length(lido:measurementValue)&gt;0]">
									<dcterms:extent>
										<xsl:attribute name="xml:lang"><xsl:value-of select="$desclang" /></xsl:attribute>
										<xsl:value-of select="$qualifier" />
										<xsl:value-of select="concat(lido:measurementType, ': ', lido:measurementValue, ' ', lido:measurementUnit)"/>
										<xsl:for-each select="../lido:extentMeasurements[string-length(.)&gt;0]"><xsl:value-of select="concat(' (', ., ')')" /></xsl:for-each>
									</dcterms:extent>
									</xsl:for-each>
									<xsl:for-each select="lido:formatMeasurements[string-length(.)&gt;0]">
										<xsl:variable name="type">
											<xsl:choose>
												<xsl:when test="$desclang eq 'de' or $desclang eq 'deu' or $desclang eq 'ger'">Format</xsl:when>
												<xsl:otherwise>Format</xsl:otherwise>
											</xsl:choose>
										</xsl:variable>
										<dcterms:extent>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
											<xsl:value-of select="concat($type, ': ', $qualifier, .)"/>
											<xsl:for-each select="../lido:extentMeasurements[string-length(.)&gt;0]"><xsl:value-of select="concat(' (', ., ')')" /></xsl:for-each>
										</dcterms:extent>
									</xsl:for-each>
									<xsl:for-each select="lido:shapeMeasurements[string-length(.)&gt;0]">
										<xsl:variable name="type">
											<xsl:choose>
												<xsl:when test="$desclang eq 'de' or $desclang eq 'deu' or $desclang eq 'ger'">Form</xsl:when>
												<xsl:otherwise>Shape</xsl:otherwise>
											</xsl:choose>
										</xsl:variable>
										<dcterms:extent>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
											<xsl:value-of select="concat($type, ': ', $qualifier, .)"/>
											<xsl:for-each select="../lido:extentMeasurements[string-length(.)&gt;0]"><xsl:value-of select="concat(' (', ., ')')" /></xsl:for-each>
										</dcterms:extent>
									</xsl:for-each>
									<xsl:for-each select="lido:scaleMeasurements[string-length(.)&gt;0]">
										<xsl:variable name="type">
											<xsl:choose>
												<xsl:when test="$desclang eq 'de' or $desclang eq 'deu' or $desclang eq 'ger'">Ausmaß</xsl:when>
												<xsl:otherwise>Scale</xsl:otherwise>
											</xsl:choose>
										</xsl:variable>
										<dcterms:extent>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
											<xsl:value-of select="concat($type, ': ', $qualifier, .)"/>
											<xsl:for-each select="../lido:extentMeasurements[string-length(.)&gt;0]"><xsl:value-of select="concat(' (', ., ')')" /></xsl:for-each>
										</dcterms:extent>
									</xsl:for-each>
								</xsl:for-each>
								</xsl:when>
							</xsl:choose>
						</xsl:for-each>

					<xsl:for-each select="lido:objectClassificationWrap/lido:classificationWrap/lido:classification[lower-case(@lido:type) = 'colour'
								or lower-case(@lido:type) = 'age'
								or lower-case(@lido:type) = 'object-status']/lido:term[not(@lido:addedSearchTerm = 'yes')][string-length(.)&gt;0]">
								<dc:description>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
									<xsl:value-of select="../@lido:type" />: <xsl:value-of select="."/>
								</dc:description>
						</xsl:for-each>

					<xsl:for-each select="lido:eventWrap/lido:eventSet/lido:event">
						<xsl:variable name="eventType" select="lido:eventType/lido:term[string-length(.)&gt;0][1]"/>
						<xsl:variable name="eventTypeLC" select="lower-case($eventType)"/>
						<xsl:variable name="creation" as="xs:boolean*">
							<xsl:if test="$eventTypeLC = 'creation' 
								or $eventTypeLC = 'create'
								or $eventTypeLC = 'designing'
								or $eventTypeLC = 'planning'
								or $eventTypeLC = 'production'
								or $eventTypeLC = 'publication'
								or $eventTypeLC = 'entwurf'
								or $eventTypeLC = 'erfindung'
								or $eventTypeLC = 'herstellung'
								or $eventTypeLC = 'planung'
								or $eventTypeLC = 'publikation'
								">
								<xsl:sequence select="true()"/>
							</xsl:if>
						</xsl:variable>
						<xsl:variable name="acquisition" as="xs:boolean*">
							<xsl:if test="$eventTypeLC = 'acquisition' 
								or $eventTypeLC = 'loss'
								or $eventTypeLC = 'move'
								or $eventTypeLC = 'planning'
								or $eventTypeLC = 'provenance'
								or $eventTypeLC = 'erwerbung'
								or $eventTypeLC = 'verlust'
								or $eventTypeLC = 'ortswechsel'
								or $eventTypeLC = 'herkunft'
								">
								<xsl:sequence select="true()"/>
							</xsl:if>
						</xsl:variable>
						<xsl:variable name="exhibition" as="xs:boolean*">
							<xsl:if test="$eventTypeLC = 'exhibition' 
								or $eventTypeLC = 'ausstellung'
								">
								<xsl:sequence select="true()"/>
							</xsl:if>
						</xsl:variable>
						
						<xsl:if test="$exhibition and lido:eventName/lido:appellationValue[string-length(.)&gt;0]">
							<dcterms:provenance>
								<xsl:value-of select="concat(lido:eventType/lido:term[1], ': ', lido:eventName/lido:appellationValue[string-length(.)&gt;0][1])" />
								<xsl:if test="lido:eventID"><xsl:value-of select="concat(' (', lido:eventID, ')')" /></xsl:if>
							</dcterms:provenance>
						</xsl:if>
						
						<xsl:for-each select="lido:eventActor">
							<xsl:choose>
								<xsl:when test="$creation and lido:actorInRole">
									<xsl:for-each select="lido:actorInRole/lido:actor/lido:nameActorSet/lido:appellationValue[string-length(.)&gt;0]">
									<!-- ignoring alternative names -->
									<xsl:if test="not(@lido:pref = 'alternate')">
									<dc:creator>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="."/>
										<xsl:for-each select="../../../lido:roleActor/lido:term[not(@lido:addedSearchTerm = 'yes')][string-length(.)&gt;0]">
											<xsl:choose>
												<xsl:when test="count(not(.='')) = 1 and count(../../lido:roleActor[not(lido:term='')]) = 1"> (<xsl:value-of select="." />)</xsl:when>
												<xsl:when test="position() = 1 and ../../lido:roleActor[string-length(lido:term)&gt;0][position() = 1]"> (<xsl:value-of select="." />, </xsl:when>
												<xsl:when test="position() = last() and ../../lido:roleActor[string-length(lido:term)&gt;0][position() = last()]"><xsl:value-of select="." />)</xsl:when>
												<xsl:otherwise><xsl:value-of select="." />, </xsl:otherwise>
											</xsl:choose>
										</xsl:for-each>
										<xsl:if test="not(../../../lido:roleActor/lido:term[not(@lido:addedSearchTerm = 'yes')][string-length(.)&gt;0])"> [<xsl:value-of select="$eventType" />]</xsl:if>
									</dc:creator>
									</xsl:if>
									</xsl:for-each>
								</xsl:when>
								<xsl:when test="$creation and lido:displayActorInRole">
									<xsl:for-each select="lido:displayActorInRole[string-length(.)&gt;0]">
									<dc:creator>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="."/>
									</dc:creator>
									</xsl:for-each>
								</xsl:when>
								<xsl:when test="not($acquisition) and lido:actorInRole">
									<xsl:for-each select="lido:actorInRole/lido:actor/lido:nameActorSet/lido:appellationValue[not(@lido:pref = 'alternate')][string-length(.)&gt;0]">
									<!-- ignoring alternative names -->
									<dc:contributor>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="."/>
										<xsl:for-each select="../../../lido:roleActor/lido:term[not(@lido:addedSearchTerm = 'yes')][string-length(.)&gt;0]">
											<xsl:choose>
												<xsl:when test="count(not(.='')) = 1 and count(../../lido:roleActor[not(lido:term='')]) = 1"> (<xsl:value-of select="." />)</xsl:when>
												<xsl:when test="position() = 1 and ../../lido:roleActor[string-length(lido:term)&gt;0][position() = 1]"> (<xsl:value-of select="." />, </xsl:when>
												<xsl:when test="position() = last() and ../../lido:roleActor[string-length(lido:term)&gt;0][position() = last()]"><xsl:value-of select="." />)</xsl:when>
												<xsl:otherwise><xsl:value-of select="." />, </xsl:otherwise>
											</xsl:choose>
										</xsl:for-each>
										<xsl:if test="not(../../../lido:roleActor/lido:term[not(@lido:addedSearchTerm = 'yes')][string-length(.)&gt;0])"> [<xsl:value-of select="$eventType" />]</xsl:if>
									</dc:contributor>
									</xsl:for-each>
								</xsl:when>
								<xsl:when test="not($acquisition) and lido:displayActorInRole">
									<xsl:for-each select="lido:displayActorInRole[string-length(.)&gt;0]">
									<dc:contributor>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="concat(., ' [', $eventType, ']')"/>
									</dc:contributor>
									</xsl:for-each>
								</xsl:when>
							</xsl:choose>
						</xsl:for-each>

						<xsl:for-each select="lido:culture/lido:term[not(@lido:addedSearchTerm = 'yes')][string-length(.)&gt;0]">
							<xsl:variable name="type">
								<xsl:choose>
									<xsl:when test="$desclang eq 'de' or $desclang eq 'deu' or $desclang eq 'ger'">kultureller Kontext</xsl:when>
									<xsl:otherwise>cultural context</xsl:otherwise>
								</xsl:choose>
							</xsl:variable>
							<xsl:choose>
								<xsl:when test="$creation">
									<dc:creator>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="concat(., ' [', $type, ']', ' [', $eventType, ']')"/>
									</dc:creator>
								</xsl:when>
								<xsl:otherwise>
									<dc:contributor>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="concat(., ' [', $type, ']', ' [', $eventType, ']')"/>
									</dc:contributor>
								</xsl:otherwise>
							</xsl:choose>
						</xsl:for-each>

						<xsl:for-each select="lido:eventMethod/lido:term[not(@lido:addedSearchTerm = 'yes')][string-length(.)&gt;0]">
						<xsl:variable name="type">
							<xsl:choose>
								<xsl:when test="$desclang eq 'de' or $desclang eq 'deu' or $desclang eq 'ger'">Methode</xsl:when>
								<xsl:otherwise>method</xsl:otherwise>
							</xsl:choose>
						</xsl:variable>
							<dc:description>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
								<xsl:value-of select="concat($type, ': ', ., ' [', $eventType, ']')"/>
							</dc:description>
						</xsl:for-each>

						<xsl:for-each select="lido:eventMaterialsTech">
							<xsl:choose>
								<xsl:when test="lido:materialsTech">
									<xsl:for-each select="lido:materialsTech/lido:termMaterialsTech/lido:term[not(@lido:addedSearchTerm = 'yes')][string-length(.)&gt;0]">
									<xsl:choose>
										<xsl:when test="..[contains(lower-case(@lido:type), 'techn')]">
											<dc:description>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:value-of select="concat(../@lido:type, ': ', .)"/>
											</dc:description>
										</xsl:when>
										<xsl:when test="..[contains(lower-case(@lido:type), 'material')]">
											<dcterms:medium>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:value-of select="."/>
											</dcterms:medium>
										</xsl:when>
									</xsl:choose>
									</xsl:for-each>
								</xsl:when>
								<xsl:when test="lido:displayMaterialsTech">
									<xsl:for-each select="lido:displayMaterialsTech[string-length(.)&gt;0]">
									<dcterms:medium>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="."/>
									</dcterms:medium>
									</xsl:for-each>
								</xsl:when>
							</xsl:choose>
						</xsl:for-each>
					
						<xsl:for-each select="lido:periodName/lido:term[not(@lido:addedSearchTerm = 'yes')][string-length(.)&gt;0]">
							<xsl:choose>
								<xsl:when test="$creation">
									<dcterms:created>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="."/>
										<xsl:if test="../@lido:type"> [<xsl:value-of select="../@lido:type" />]</xsl:if>
										<xsl:value-of select="concat(' [', $eventType, ']')"/>
									</dcterms:created>
								</xsl:when>
								<xsl:otherwise>
									<dc:date>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="concat(., ' [', $eventType, ']')"/>
										<xsl:if test="../@lido:type"> [<xsl:value-of select="../@lido:type" />]</xsl:if>
									</dc:date>
								</xsl:otherwise>
							</xsl:choose>
						</xsl:for-each>
					
						<xsl:for-each select="lido:eventDate">
							<xsl:choose>
								<xsl:when test="$creation and lido:date">
									<xsl:for-each select="lido:date[string-length(lido:earliestDate)&gt;0]">
									<dcterms:created>
										<xsl:attribute name="xml:lang"><xsl:value-of select="$desclang" /></xsl:attribute>
										<xsl:choose>
											<xsl:when test="lido:earliestDate = lido:latestDate">
												<xsl:value-of select="concat(lido:earliestDate, ' [', $eventType, ']')"/>
											</xsl:when>
											<xsl:otherwise>
												<xsl:value-of select="concat(lido:earliestDate, '/', lido:latestDate, ' [', $eventType, ']')"/>
											</xsl:otherwise>
										</xsl:choose>
									</dcterms:created>
									</xsl:for-each>
								</xsl:when>
								<xsl:when test="$creation and lido:displayDate">
									<xsl:for-each select="lido:displayDate[string-length(.)&gt;0]">
									<dcterms:created>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="concat(., ' [', $eventType, ']')"/>
									</dcterms:created>
									</xsl:for-each>
								</xsl:when>
								<xsl:when test="lido:date">
									<xsl:for-each select="lido:date[string-length(lido:earliestDate)&gt;0]">
									<dc:date>
										<xsl:attribute name="xml:lang"><xsl:value-of select="$desclang" /></xsl:attribute>
										<xsl:choose>
											<xsl:when test="lido:earliestDate = lido:latestDate">
												<xsl:value-of select="concat(lido:earliestDate, ' [', $eventType, ']')"/>
											</xsl:when>
											<xsl:otherwise>
												<xsl:value-of select="concat(lido:earliestDate, '/', lido:latestDate, ' [', $eventType, ']')"/>
											</xsl:otherwise>
										</xsl:choose>
									</dc:date>
									</xsl:for-each>
								</xsl:when>
								<xsl:when test="lido:displayDate">
									<xsl:for-each select="lido:displayDate[string-length(.)&gt;0]">
									<dc:date>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="concat(., ' [', $eventType, ']')"/>
									</dc:date>
									</xsl:for-each>
								</xsl:when>
							</xsl:choose>
						</xsl:for-each>

						<xsl:for-each select="lido:eventPlace">
							<xsl:variable name="qualifier">
								<xsl:choose>
									<xsl:when test="$desclang eq 'de' or $desclang eq 'deu' or $desclang eq 'ger'"> [Ort]</xsl:when>
									<xsl:otherwise> [Place]</xsl:otherwise>
								</xsl:choose>
							</xsl:variable>
							<xsl:choose>
								<xsl:when test="not($acquisition) and lido:place">
									<xsl:for-each select="lido:place/lido:namePlaceSet/lido:appellationValue[not(@lido:pref = 'alternate')][string-length(.)&gt;0]">
									<!-- ignoring alternative names -->
										<dcterms:spatial>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
											<xsl:value-of select="concat(., $qualifier, ' [', $eventType, ']')"/>
										</dcterms:spatial>
									</xsl:for-each>
								</xsl:when>
								<xsl:when test="not($acquisition) and lido:displayPlace">
									<xsl:for-each select="lido:displayPlace[string-length(.)&gt;0]">
										<dcterms:spatial>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
											<xsl:value-of select="concat(., ' [', $eventType, ']')"/>
										</dcterms:spatial>
									</xsl:for-each>
								</xsl:when>
							</xsl:choose>
						</xsl:for-each>
					</xsl:for-each>

					<xsl:for-each select="lido:objectRelationWrap/lido:relatedWorksWrap/lido:relatedWorkSet[count(not(lido:relatedWork/lido:object/lido:objectNote='')) &gt; 0 or count(not(lido:relatedWork/lido:displayObject='')) &gt; 0]">
						<xsl:choose>
							<xsl:when test="lido:relatedWorkRelType/lido:term[1] ='part of'
								or lido:relatedWorkRelType/lido:term[1] ='Teil von'
								">
								<dcterms:isPartOf>
									<xsl:for-each select="lido:relatedWork">
									<xsl:choose>
										<xsl:when test="lido:object">
											<xsl:attribute name="xml:lang">
												<xsl:choose>
													<xsl:when test="lido:object[1]/lido:objectNote[1]/@xml:lang"><xsl:value-of select="lido:object[1]/lido:objectNote[1]/@xml:lang" /></xsl:when>
													<xsl:otherwise><xsl:value-of select="$desclang" /></xsl:otherwise>
												</xsl:choose>
											</xsl:attribute>
										<xsl:for-each select="lido:object">
											<xsl:for-each select="lido:objectNote[string-length(.)&gt;0]">
											<xsl:variable name="type">
												<xsl:choose>
													<xsl:when test="@lido:type"><xsl:value-of select="concat(@lido:type, ': ')" /></xsl:when>
													<xsl:otherwise />
												</xsl:choose>
											</xsl:variable>
											<xsl:choose>
												<xsl:when test="count(../lido:objectNote[not(.='')]) = 1">
													<xsl:value-of select="concat($type, .)" />
												</xsl:when>
												<xsl:when test="position() = 1">
													<xsl:value-of select="concat($type, ., ', ')" /></xsl:when>
												<xsl:when test="position() = last()">
													<xsl:value-of select="concat($type, .)" />
												</xsl:when>
												<xsl:otherwise><xsl:value-of select="concat($type, ., ', ')" /></xsl:otherwise>
											</xsl:choose>
											</xsl:for-each>
											<xsl:if test="lido:objectWebResource[string-length(.)&gt;0]">
												<xsl:value-of select="concat(' [', lido:objectWebResource[string-length(.)&gt;0][1], ']')" />
											</xsl:if>
										</xsl:for-each>
										</xsl:when>
										<xsl:when test="string-length(lido:displayObject) &gt; 0">
										<xsl:attribute name="xml:lang">
											<xsl:choose>
												<xsl:when test="lido:displayObject[not(.='')][1]/@xml:lang"><xsl:value-of select="lido:displayObject[not(.='')][1]/@xml:lang" /></xsl:when>
												<xsl:otherwise><xsl:value-of select="$desclang" /></xsl:otherwise>
											</xsl:choose>
										</xsl:attribute>
											<xsl:value-of select="lido:displayObject[not(.='')][1]" />
										</xsl:when>
									</xsl:choose>
									</xsl:for-each>
								</dcterms:isPartOf>
							</xsl:when>
							<xsl:when test="lido:relatedWorkRelType/lido:term[1] ='has part'
								or lido:relatedWorkRelType/lido:term[1] ='hat Teil'
								">
								<dcterms:hasPart>
									<xsl:for-each select="lido:relatedWork">
									<xsl:choose>
										<xsl:when test="lido:object">
											<xsl:attribute name="xml:lang">
												<xsl:choose>
													<xsl:when test="lido:object[1]/lido:objectNote[1]/@xml:lang"><xsl:value-of select="lido:object[1]/lido:objectNote[1]/@xml:lang" /></xsl:when>
													<xsl:otherwise><xsl:value-of select="$desclang" /></xsl:otherwise>
												</xsl:choose>
											</xsl:attribute>
										<xsl:for-each select="lido:object">
											<xsl:for-each select="lido:objectNote[string-length(.)&gt;0]">
											<xsl:variable name="type">
												<xsl:choose>
													<xsl:when test="@lido:type"><xsl:value-of select="concat(@lido:type, ': ')" /></xsl:when>
													<xsl:otherwise />
												</xsl:choose>
											</xsl:variable>
											<xsl:choose>
												<xsl:when test="count(../lido:objectNote[not(.='')]) = 1">
													<xsl:value-of select="concat($type, .)" />
												</xsl:when>
												<xsl:when test="position() = 1">
													<xsl:value-of select="concat($type, ., ', ')" /></xsl:when>
												<xsl:when test="position() = last()">
													<xsl:value-of select="concat($type, .)" />
												</xsl:when>
												<xsl:otherwise><xsl:value-of select="concat($type, ., ', ')" /></xsl:otherwise>
											</xsl:choose>
											</xsl:for-each>
											<xsl:if test="lido:objectWebResource[string-length(.)&gt;0]">
												<xsl:value-of select="concat(' [', lido:objectWebResource[string-length(.)&gt;0][1], ']')" />
											</xsl:if>
										</xsl:for-each>
										</xsl:when>
										<xsl:when test="string-length(lido:displayObject) &gt; 0">
										<xsl:attribute name="xml:lang">
											<xsl:choose>
												<xsl:when test="lido:displayObject[not(.='')][1]/@xml:lang"><xsl:value-of select="lido:displayObject[not(.='')][1]/@xml:lang" /></xsl:when>
												<xsl:otherwise><xsl:value-of select="$desclang" /></xsl:otherwise>
											</xsl:choose>
										</xsl:attribute>
											<xsl:value-of select="lido:displayObject[not(.='')][1]" />
										</xsl:when>
									</xsl:choose>
									</xsl:for-each>
								</dcterms:hasPart>
							</xsl:when>
							<xsl:when test=".//lido:objectNote[string-length(.)&gt;0] or .//lido:displayObject[string-length(.)&gt;0]">
								<xsl:variable name="reltype">
									<xsl:choose>
										<xsl:when test="lido:relatedWorkRelType/lido:term"><xsl:value-of select="concat(' [', lido:relatedWorkRelType/lido:term[1], ']')" /></xsl:when>
										<xsl:otherwise />
									</xsl:choose>
								</xsl:variable>
								<dc:relation>
									<xsl:for-each select="lido:relatedWork">
										<xsl:choose>
										<xsl:when test=".//lido:objectNote[string-length(.)&gt;0]">
											<xsl:attribute name="xml:lang">
												<xsl:choose>
													<xsl:when test="lido:object[1]/lido:objectNote[1]/@xml:lang"><xsl:value-of select="lido:object[1]/lido:objectNote[1]/@xml:lang" /></xsl:when>
													<xsl:otherwise><xsl:value-of select="$desclang" /></xsl:otherwise>
												</xsl:choose>
											</xsl:attribute>
										<xsl:for-each select="lido:object">
											<xsl:for-each select="lido:objectNote[string-length(.)&gt;0]">
											<xsl:variable name="type">
												<xsl:choose>
													<xsl:when test="@lido:type"><xsl:value-of select="concat(@lido:type, ': ')" /></xsl:when>
													<xsl:otherwise />
												</xsl:choose>
											</xsl:variable>
											<xsl:choose>
												<xsl:when test="count(../lido:objectNote[not(.='')]) = 1">
													<xsl:value-of select="concat($type, .)" />
												</xsl:when>
												<xsl:when test="position() = 1">
													<xsl:value-of select="concat($type, ., ', ')" /></xsl:when>
												<xsl:when test="position() = last()">
													<xsl:value-of select="concat($type, .)" />
												</xsl:when>
												<xsl:otherwise><xsl:value-of select="concat($type, ., ', ')" /></xsl:otherwise>
											</xsl:choose>
											</xsl:for-each>
											<xsl:value-of select="$reltype" />
											<xsl:if test="lido:objectWebResource[string-length(.)&gt;0]">
												<xsl:value-of select="concat(' [', lido:objectWebResource[string-length(.)&gt;0][1], ']')" />
											</xsl:if>
										</xsl:for-each>
							</xsl:when>
							<xsl:when test="lido:displayObject[string-length(.)&gt;0]">
										<xsl:attribute name="xml:lang">
											<xsl:choose>
												<xsl:when test="lido:displayObject[not(.='')][1]/@xml:lang"><xsl:value-of select="lido:displayObject[not(.='')][1]/@xml:lang" /></xsl:when>
												<xsl:otherwise><xsl:value-of select="$desclang" /></xsl:otherwise>
											</xsl:choose>
										</xsl:attribute>
										<xsl:value-of select="lido:displayObject[not(.='')][1]" />
										<xsl:value-of select="$reltype" />
							</xsl:when>
							</xsl:choose>
									</xsl:for-each>
								</dc:relation>
							</xsl:when>
						</xsl:choose>
					</xsl:for-each>

					<xsl:for-each select="lido:objectRelationWrap/lido:subjectWrap/lido:subjectSet">
						<xsl:choose>

							<xsl:when test="lido:subject">
								<xsl:for-each select="lido:subject">
									<xsl:variable name="extent"><xsl:value-of select="lido:extentSubject" /></xsl:variable>
									<xsl:choose>
									<xsl:when test="lido:subjectConcept[count(not(lido:term='')) &gt; 0]">
										<xsl:for-each select="lido:subjectConcept/lido:term[string-length(.)&gt;0][not(@lido:addedSearchTerm = 'yes')]">
											<dc:subject>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:value-of select="."/>
											</dc:subject>
										</xsl:for-each>
										<!-- usually ignoring addedSearchTerms / special handling Iconclass (to be checked) -->
										<xsl:if test="lido:subjectConcept/lido:conceptID[contains(@lido:source, 'Iconclass')]">
										<xsl:for-each select="
											lido:subjectConcept/lido:term[string-length(.)&gt;0][@lido:addedSearchTerm = 'yes'][1]
											">
											<dc:subject>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:value-of select="."/>
											</dc:subject>
										</xsl:for-each>
										</xsl:if>
									</xsl:when>
									</xsl:choose>
									<xsl:choose>
									<xsl:when test="lido:subjectActor/lido:actor">
										<xsl:for-each select="lido:subjectActor/lido:actor/lido:nameActorSet/lido:appellationValue[not(@lido:pref = 'alternate')][string-length(.)&gt;0]">
											<!-- ignoring alternative names -->
											<dc:subject>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:value-of select="."/>
											</dc:subject>
										</xsl:for-each>
									</xsl:when>
									<xsl:when test="lido:subjectActor/lido:displayActor">
										<xsl:for-each select="lido:subjectActor/lido:displayActor[string-length(.)&gt;0]">
											<dc:subject>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:value-of select="."/>
											</dc:subject>
										</xsl:for-each>
									</xsl:when>
									</xsl:choose>
									<xsl:choose>
									<xsl:when test="lido:subjectPlace/lido:place">
										<xsl:for-each select="lido:subjectPlace/lido:place/lido:namePlaceSet/lido:appellationValue[not(@lido:pref = 'alternate')][string-length(.)&gt;0]">
											<!-- ignoring alternative names -->
											<dc:subject>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:value-of select="."/>
											</dc:subject>
										</xsl:for-each>
									</xsl:when>
									<xsl:when test="lido:subjectPlace/lido:displayPlace">
										<xsl:for-each select="lido:subjectPlace/lido:displayPlace[string-length(.)&gt;0]">
											<dc:subject>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:value-of select="."/>
											</dc:subject>
										</xsl:for-each>
									</xsl:when>
									</xsl:choose>
									<xsl:choose>
									<xsl:when test="lido:subjectObject/lido:object">
										<xsl:for-each select="lido:subjectObject/lido:object/lido:objectNote[string-length(.)&gt;0]">
											<dc:subject>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:value-of select="."/>
											</dc:subject>
										</xsl:for-each>
									</xsl:when>
									<xsl:when test="lido:subjectObject/lido:displayObject">
										<xsl:for-each select="lido:subjectObject/lido:displayObject[string-length(.)&gt;0]">
											<dc:subject>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:value-of select="."/>
											</dc:subject>
										</xsl:for-each>
									</xsl:when>
									</xsl:choose>
									<xsl:choose>
									<xsl:when test="lido:subjectDate/lido:date">
										<xsl:for-each select="lido:subjectDate/lido:date[string-length(lido:earliestDate)&gt;0]">
											<dc:subject>
												<xsl:attribute name="xml:lang"><xsl:value-of select="$desclang" /></xsl:attribute>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:choose>
													<xsl:when test="lido:earliestDate = lido:latestDate">
														<xsl:value-of select="lido:earliestDate"/>
													</xsl:when>
													<xsl:otherwise>
														<xsl:value-of select="concat(lido:earliestDate, '-', lido:latestDate)"/>
													</xsl:otherwise>
												</xsl:choose>
											</dc:subject>
										</xsl:for-each>
									</xsl:when>
									<xsl:when test="lido:subjectDate/lido:displayDate">
										<xsl:for-each select="lido:subjectDate/lido:displayDate[string-length(.)&gt;0]">
											<dc:subject>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:value-of select="."/>
											</dc:subject>
										</xsl:for-each>
									</xsl:when>
									</xsl:choose>
									<xsl:choose>
									<xsl:when test="lido:subjectEvent/lido:event">
										<xsl:for-each select="lido:subjectEvent/lido:event[count(not(lido:eventName/lido:appellationValue='')) &gt; 0]">
											<dc:subject>
							<xsl:attribute name="xml:lang">
								<xsl:choose>
									<xsl:when test="lido:eventType/lido:term[1]/@xml:lang"><xsl:value-of select="lido:eventType/lido:term[1]/@xml:lang" /></xsl:when>
									<xsl:otherwise><xsl:value-of select="$desclang" /></xsl:otherwise>
								</xsl:choose>
							</xsl:attribute>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:value-of select="concat(lido:eventType/lido:term[1], ': ', lido:eventName/lido:appellationValue[1], ' (', lido:eventID, ')')"/>
											</dc:subject>
										</xsl:for-each>
									</xsl:when>
									<xsl:when test="lido:subjectEvent/lido:displayEvent">
										<xsl:for-each select="lido:subjectEvent/lido:displayEvent[string-length(.)&gt;0]">
											<dc:subject>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
												<xsl:if test="not($extent='')">
													<xsl:value-of select="concat($extent, ': ')"/>
												</xsl:if>
												<xsl:value-of select="."/>
											</dc:subject>
										</xsl:for-each>
									</xsl:when>
									</xsl:choose>
								</xsl:for-each>
							</xsl:when>
							<xsl:when test="lido:displaySubject">
								<xsl:for-each select="lido:displaySubject[string-length(.)&gt;0]">
									<dc:subject>
								<xsl:call-template name="langattr">
									<xsl:with-param name="desclang" select="$desclang" />
								</xsl:call-template>
										<xsl:value-of select="."/>
									</dc:subject>
								</xsl:for-each>
							</xsl:when>
						</xsl:choose>
					</xsl:for-each>
					
		</xsl:template>

	<xsl:template name="resourceView">
		<xsl:variable name="admlang">
			<xsl:choose>
				<xsl:when test="@xml:lang">
					<xsl:value-of select="@xml:lang" />
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="../../@xml:lang" />
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:for-each select="lido:resourceDescription[string-length(.)&gt;0]">
			<dc:description>
				<xsl:call-template name="langattr">
					<xsl:with-param name="desclang" select="$admlang" />
				</xsl:call-template>
				<xsl:variable name="desctype">
					<xsl:choose>
						<xsl:when test="$admlang eq 'de' or $admlang = 'deu' or $admlang = 'ger'">Fotoinhalt/Ansicht</xsl:when>
						<xsl:otherwise>Content/View Resource</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				<xsl:value-of select="concat($desctype, ': ', .)" />
			</dc:description>
		</xsl:for-each>
		<xsl:for-each select="lido:resourceDateTaken">
				<xsl:variable name="desctype">
					<xsl:choose>
						<xsl:when test="$admlang eq 'de' or $admlang = 'deu' or $admlang = 'ger'">Datierung des Fotos</xsl:when>
						<xsl:otherwise>Date Resource</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				<xsl:choose>
					<xsl:when test="lido:date">
						<xsl:for-each select="lido:date[string-length(lido:earliestDate)&gt;0]">
							<dc:description>
							<xsl:attribute name="xml:lang"><xsl:value-of select="$admlang" /></xsl:attribute>
							<xsl:choose>
								<xsl:when test="lido:earliestDate = lido:latestDate">
									<xsl:value-of select="concat($desctype, ': ', lido:earliestDate)"/>
								</xsl:when>
								<xsl:otherwise>
									<xsl:value-of select="concat($desctype, ': ', lido:earliestDate, '/', lido:latestDate)"/>
								</xsl:otherwise>
							</xsl:choose>
						</dc:description>
						</xsl:for-each>
					</xsl:when>
					<xsl:when test="lido:displayDate">
						<xsl:for-each select="lido:displayDate[string-length(.)&gt;0]">
						<dc:description>
							<xsl:call-template name="langattr">
								<xsl:with-param name="desclang" select="$admlang" />
							</xsl:call-template>
							<xsl:value-of select="concat($desctype, ': ', .)"/>
						</dc:description>
						</xsl:for-each>
					</xsl:when>
				</xsl:choose>
		</xsl:for-each>
		<xsl:for-each select="lido:resourceSource/lido:legalBodyName/lido:appellationValue[string-length(.)&gt;0]">
			<xsl:if test="lower-case(../../@lido:type) = 'photographer' or contains(lower-case(../../@lido:type), 'fotograf')">
			<dc:description>
				<xsl:call-template name="langattr">
					<xsl:with-param name="desclang" select="$admlang" />
				</xsl:call-template>
				<xsl:value-of select="concat(../../@lido:type, ': ', .)" />
			</dc:description>
			</xsl:if>
		</xsl:for-each>
	</xsl:template>

	<xsl:template name="work">
		<xsl:variable name="admlang" select="@xml:lang" />
		<xsl:choose>
			<xsl:when test="lido:rightsWorkWrap/lido:rightsWorkSet/lido:creditLine[string-length(.)&gt;0]">
				<xsl:for-each select="lido:rightsWorkWrap/lido:rightsWorkSet/lido:creditLine[string-length(.)&gt;0]">
					<!-- ignoring alternative names -->
					<dc:rights>
							<xsl:call-template name="langattr">
								<xsl:with-param name="desclang" select="$admlang" />
							</xsl:call-template>
						<xsl:value-of select="."/>
					</dc:rights>
				</xsl:for-each>
			</xsl:when>
			<xsl:when test="lido:rightsWorkWrap/lido:rightsWorkSet/lido:rightsType[string-length(lido:term)&gt;0] and not(lido:rightsWorkWrap/lido:rightsWorkSet/lido:rightsHolder/lido:legalBodyName)">
				<xsl:for-each select="lido:rightsWorkWrap/lido:rightsWorkSet/lido:rightsType[string-length(lido:term)&gt;0]">
					<dc:rights>
							<xsl:call-template name="langattr">
								<xsl:with-param name="desclang" select="$admlang" />
							</xsl:call-template>
						<xsl:value-of select="lido:term"/>
					</dc:rights>
				</xsl:for-each>
			</xsl:when>
			<xsl:when test="lido:rightsWorkWrap/lido:rightsWorkSet/lido:rightsHolder/lido:legalBodyName">
				<xsl:for-each select="lido:rightsWorkWrap/lido:rightsWorkSet/lido:rightsHolder/lido:legalBodyName[not(lido:appellationValue/@lido:pref = 'alternate')]">
						<dc:rights>
							<xsl:attribute name="xml:lang">
							<xsl:choose>
								<xsl:when test="lido:appellationValue/@xml:lang"><xsl:value-of select="lido:appellationValue/@xml:lang" /></xsl:when>
								<xsl:otherwise><xsl:value-of select="$admlang" /></xsl:otherwise>
							</xsl:choose>
							</xsl:attribute>
							<xsl:if test="../../lido:rightsType[string-length(lido:term)&gt;0]">
								<xsl:for-each select="../../lido:rightsType[string-length(lido:term)&gt;0]">
									<xsl:value-of select="lido:term[1]" />
								</xsl:for-each>
								<xsl:value-of select="': '" />
							</xsl:if>
							<xsl:value-of select="lido:appellationValue"/>
						</dc:rights>
				</xsl:for-each>
			</xsl:when>
		</xsl:choose>

	</xsl:template>

	<xsl:template name="record">
		
		<xsl:variable name="admlang" select="@xml:lang" />
		<xsl:for-each select="lido:recordWrap/lido:recordID[string-length(.)&gt;0]">
			<dc:identifier>
				<xsl:call-template name="langattr">
					<xsl:with-param name="desclang" select="$admlang" />
				</xsl:call-template>
			   <xsl:value-of select="concat(@lido:type, ' ',. , ' [Metadata]')"/>						           
			</dc:identifier>
		</xsl:for-each>

		<xsl:for-each select="lido:recordWrap/lido:recordSource[not(@lido:type = 'europeana:dataProvider')]/lido:legalBodyName/lido:appellationValue[not(@lido:label = 'europeana:dataProvider')][not(@lido:pref = 'alternate')]">
		<!-- ignoring alternative names -->
			<dc:source>
				<xsl:call-template name="langattr">
					<xsl:with-param name="desclang" select="$admlang" />
				</xsl:call-template>
				<xsl:value-of select="."/>
			</dc:source>
		</xsl:for-each>

	</xsl:template>

	<xsl:template name="resource">

		<xsl:variable name="admlang" select="../../@xml:lang" />
		<xsl:variable name="resourceSource" select="lido:resourceSource[1]/lido:legalBodyName[1]/lido:appellationValue[1]" />	 
		<xsl:for-each select="lido:resourceID[string-length(.)&gt;0]">
			<dc:identifier>
				<xsl:call-template name="langattr">
					<xsl:with-param name="desclang" select="$admlang" />
				</xsl:call-template>
			   <xsl:value-of select="concat($resourceSource, ' - ', ., ' [Resource]')"/>						           
			</dc:identifier>
		</xsl:for-each>
		
		<xsl:choose>
			<xsl:when test="lido:rightsResource/lido:creditLine[string-length(.)&gt;0]">
				<xsl:for-each select="lido:rightsResource/lido:creditLine[string-length(.)&gt;0]">
					<dc:rights>
						<xsl:call-template name="langattr">
							<xsl:with-param name="desclang" select="$admlang" />
						</xsl:call-template>
						<xsl:value-of select="concat(., ' [Resource]')"/>
					</dc:rights>
				</xsl:for-each>
			</xsl:when>
			<xsl:when test="lido:rightsResource/lido:rightsType[string-length(lido:term)&gt;0] and not(lido:rightsResource/lido:rightsHolder/lido:legalBodyName)">
				<xsl:for-each select="lido:rightsResource/lido:rightsType[string-length(lido:term)&gt;0]">
					<dc:rights>
						<xsl:call-template name="langattr">
							<xsl:with-param name="desclang" select="$admlang" />
						</xsl:call-template>
						<xsl:value-of select="concat(lido:term, ' [Resource]')"/>
					</dc:rights>
				</xsl:for-each>
			</xsl:when>
			<xsl:when test="lido:rightsResource/lido:rightsHolder/lido:legalBodyName">
				<xsl:for-each select="lido:rightsResource/lido:rightsHolder/lido:legalBodyName[not(lido:appellationValue/@lido:pref = 'alternate')]">
						<dc:rights>
							<xsl:attribute name="xml:lang">
							<xsl:choose>
								<xsl:when test="lido:appellationValue/@xml:lang"><xsl:value-of select="lido:appellationValue/@xml:lang" /></xsl:when>
								<xsl:otherwise><xsl:value-of select="$admlang" /></xsl:otherwise>
							</xsl:choose>
							</xsl:attribute>
							<xsl:if test="../../lido:rightsType[string-length(lido:term)&gt;0]">
								<xsl:for-each select="../../lido:rightsType[string-length(lido:term)&gt;0]">
									<xsl:value-of select="lido:term[1]" />
								</xsl:for-each>
								<xsl:value-of select="': '" />
							</xsl:if>
							<xsl:value-of select="lido:appellationValue"/>
							<xsl:value-of select="' [Resource]'"/>
						</dc:rights>
				</xsl:for-each>
			</xsl:when>
			<xsl:otherwise>
				<xsl:for-each select="lido:resourceSource/lido:legalBodyName/lido:appellationValue[string-length(.)&gt;0]">
					<dc:source>
						<xsl:call-template name="langattr">
							<xsl:with-param name="desclang" select="$admlang" />
						</xsl:call-template>
						<xsl:if test="@lido:type"><xsl:value-of select="concat(@lido:type, ': ')" /></xsl:if>
						<xsl:value-of select="." />
					</dc:source>
				</xsl:for-each>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template name="langattr">
		<xsl:param name="desclang" />
			<xsl:attribute name="xml:lang">
				<xsl:choose>
					<xsl:when test="@xml:lang"><xsl:value-of select="@xml:lang" /></xsl:when>
					<xsl:otherwise><xsl:value-of select="$desclang" /></xsl:otherwise>
				</xsl:choose>
			</xsl:attribute>
	</xsl:template>
	
</xsl:stylesheet>