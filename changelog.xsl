<xsl:stylesheet
	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
<xsl:param name="timestamp" />
<xsl:param name="author" />
	
<xsl:template match="/">

<xsl:value-of select="$timestamp"/>
<xsl:text> </xsl:text>
<xsl:value-of select="$author"/>
<xsl:text disable-output-escaping="yes"><![CDATA[  <youremail@mail.com>  ]]>
	</xsl:text>

<xsl:for-each select="status/target/entry">
	
	<xsl:if test="string(wc-status/@item) != 'unversioned'">
	* <xsl:value-of select="@path" />: </xsl:if>
	
</xsl:for-each>
</xsl:template>

</xsl:stylesheet>
