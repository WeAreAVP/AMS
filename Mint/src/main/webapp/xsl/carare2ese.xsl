<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet exclude-result-prefixes="carare" version="2.0"
	xmlns:carare="http://www.carare.eu/carareSchema"
	xmlns:europeana="http://www.europeana.eu/schemas/ese/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:dcterms="http://purl.org/dc/terms/"
  	xmlns:xalan="http://xml.apache.org/xalan"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:template match="/">
    <europeana:metadata>
    <xsl:apply-templates select="/carare:carareWrap/carare:carare"/>
    </europeana:metadata>
  </xsl:template>
  <xsl:template match="/carare:carareWrap/carare:carare">
    <europeana:record>
      <xsl:for-each select="carare:digitalResource/carare:appellation/carare:name">
        <xsl:if test="position() = 1">
          <dc:title>
            <xsl:attribute name="xml:lang">
              <xsl:for-each select="@lang">
                <xsl:if test="position() = 1">
                  <xsl:value-of select="."/>
                </xsl:if>
              </xsl:for-each>
            </xsl:attribute>
            <xsl:value-of select="."/>
          </dc:title>
        </xsl:if>
      </xsl:for-each>
      <xsl:for-each select="carare:digitalResource/carare:description">
        <xsl:if test="position() = 1">
          <dc:description>
            <xsl:attribute name="xml:lang">
              <xsl:for-each select="@lang">
                <xsl:if test="position() = 1">
                  <xsl:value-of select="."/>
                </xsl:if>
              </xsl:for-each>
            </xsl:attribute>
            <xsl:value-of select="."/>
          </dc:description>
        </xsl:if>
      </xsl:for-each>
      <xsl:for-each select="carare:digitalResource/carare:recordInformation/carare:id">
        <xsl:if test="position() = 1">
          <dc:identifier>
            <xsl:value-of select="."/>
          </dc:identifier>
        </xsl:if>
      </xsl:for-each>
      <xsl:for-each select="carare:digitalResource/carare:recordInformation/carare:source">
        <xsl:if test="position() = 1">
          <dc:source>
            <xsl:attribute name="xml:lang">
              <xsl:for-each select="@lang">
                <xsl:if test="position() = 1">
                  <xsl:value-of select="."/>
                </xsl:if>
              </xsl:for-each>
            </xsl:attribute>
            <xsl:value-of select="."/>
          </dc:source>
        </xsl:if>
      </xsl:for-each>
      <xsl:for-each select="carare:digitalResource/carare:link">
        <xsl:if test="position() = 1">
          <europeana:object>
            <xsl:value-of select="."/>
          </europeana:object>
        </xsl:if>
      </xsl:for-each>
      <europeana:type>IMAGE</europeana:type>
      <xsl:for-each select="carare:digitalResource/carare:isShownAt">
        <xsl:if test="position() = 1">
          <europeana:isShownAt>
            <xsl:value-of select="."/>
          </europeana:isShownAt>
        </xsl:if>
      </xsl:for-each>
    </europeana:record>
  </xsl:template>
</xsl:stylesheet>
