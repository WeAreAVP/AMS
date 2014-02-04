mappingHandler = new JSONMappingHandler(mapping);
template = new JSONMappingHandler(mapping.getJSONObject("template"));

// set schema id
schemaId = template.getHandlersForPath("/lido/lidoRecID");
schemaId = schemaId.get(0);

schemaId.addConstantMapping(Config.get("mint.title") + ":000000");
schemaId.setFixed(true);
schemaIdType = schemaId.getAttribute("@lido:type");
schemaIdType.addConstantMapping(Config.get("mint.title"));
schemaIdType.setFixed(true);

descriptiveMetadata = template.getChild("lido:descriptiveMetadata");
descriptiveMetadata.setMandatory(true);
descriptiveMetadata.getAttribute("@xml:lang").setMandatory(true);

administrativeMetadata = template.getChild("lido:administrativeMetadata");
administrativeMetadata.setMandatory(true);
administrativeMetadata.getAttribute("@xml:lang").setMandatory(true);

// create europeana classification
objectClassificationWrap = mappingHandler.getGroupHandler("objectClassificationWrap");
europeanaClassification = objectClassificationWrap.duplicatePath("/objectClassificationWrap/classificationWrap/classification");
europeanaClassification.setLabel("classification (europeana)");
europeanaType = europeanaClassification.getAttribute("@lido:type");
europeanaType.addConstantMapping("europeana:type");
europeanaTerm = europeanaClassification.getChild("lido:term")
europeanaTerm.addEnumeration("IMAGE");
europeanaTerm.addEnumeration("SOUND");
europeanaTerm.addEnumeration("TEXT");
europeanaTerm.addEnumeration("VIDEO");
//europeanaTerm.addEnumeration("3D");
europeanaTerm.setMandatory(true);

// europeana record source
recordWrap = mappingHandler.getGroupHandler("recordWrap");
recordInfoLink = recordWrap.getChild("lido:recordInfoSet").getChild("lido:recordInfoLink");
recordInfoLink.setMandatory(true);
originalRecordSource = recordWrap.getChild("lido:recordSource");
recordSource = recordWrap.duplicatePath("/recordWrap/recordSource");
recordSource.setLabel("recordSource (europeana)");
recordSourceType = recordSource.getAttribute("@lido:type");
recordSourceType.addConstantMapping("europeana:dataProvider");
recordSourceType.setFixed(true);
recordSourceAppellation = recordSource.getChild("lido:legalBodyName");
recordSourceAppellation.setMandatory(true);
originalRecordSource.setString(JSONMappingHandler.ELEMENT_MINOCCURS, "0");

// create master & thumb resource, resource rights
resourceWrap = mappingHandler.getGroupHandler("resourceWrap");
resource = resourceWrap.duplicatePath("/resourceWrap/resourceSet");
resource.setLabel("resourceSet (europeana)");

master = resource.duplicatePath("/resourceSet/resourceRepresentation");
master.setLabel("resourceRepresentation (master)");
master.setRemovable(true);
thumb = resource.duplicatePath("/resourceSet/resourceRepresentation");
thumb.setLabel("resourceRepresentation (thumb)");
thumb.setRemovable(true);
rights = resource.getChild("lido:rightsResource");
rights.setLabel("rightsResource (europeana)");
rights.setMandatory(true);

linkResource = master.getChild("lido:linkResource");
linkResource.setLabel("linkResource (master)");
linkType = master.getAttribute("@lido:type");
linkType.addConstantMapping("image_master");
linkType.setFixed(true);

linkResource = thumb.getChild("lido:linkResource");
linkResource.setLabel("linkResource (thumb)");
linkType = thumb.getAttribute("@lido:type");
linkType.addConstantMapping("image_thumb");
linkType.setFixed(true);

rightsType = rights.getChild("lido:rightsType");
rightsType.setLabel("rightsType (europeana)");
rightsType = rightsType.getChild("lido:term");
rightsType.setLabel("term (europeana)");
rightsType.setMandatory(true);
rightsType.addEnumeration("http://www.europeana.eu/rights/rr-f/");
rightsType.addEnumeration("http://www.europeana.eu/rights/rr-p/");
rightsType.addEnumeration("http://www.europeana.eu/rights/rr-r/");
rightsType.addEnumeration("http://www.europeana.eu/rights/unknown/");
rightsType.addEnumeration("http://creativecommons.org/licenses/publicdomain/mark/");
rightsType.addEnumeration("http://creativecommons.org/licenses/publicdomain/zero/");
rightsType.addEnumeration("http://creativecommons.org/licenses/by/");
rightsType.addEnumeration("http://creativecommons.org/licenses/by-sa/");
rightsType.addEnumeration("http://creativecommons.org/licenses/by-nc/");
rightsType.addEnumeration("http://creativecommons.org/licenses/by-nc-sa/");
rightsType.addEnumeration("http://creativecommons.org/licenses/by-nd/");
rightsType.addEnumeration("http://creativecommons.org/licenses/by-nc-nd/");

// rights work set
recordRights = recordWrap.duplicatePath("/recordWrap/recordRights");
recordRights.setLabel("recordRights (europeana)");
rightsType = recordRights.getChild("lido:rightsType").getChild("lido:term");
rightsType.addEnumeration("CC0");
rightsType.addEnumeration("CC0 (no descriptions)");
rightsType.addEnumeration("CC0 (mandatory only)");
