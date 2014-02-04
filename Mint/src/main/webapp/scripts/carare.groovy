template = new JSONMappingHandler(mapping.getJSONObject("template"));
carareId = template.getHandlersForPath("/carare/@id");
carareId = carareId.get(0);

carareId.addConstantMapping("carare:000000");
carareId.setFixed(true);