package gr.ntua.ivml.mint.xsd;

import java.io.File;
import java.io.InputStream;
import java.io.Reader;
import java.math.BigInteger;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

import net.sf.json.JSONArray;
import net.sf.json.JSONObject;

import org.apache.log4j.Logger;
import org.xml.sax.Attributes;
import org.xml.sax.ContentHandler;
import org.xml.sax.EntityResolver;
import org.xml.sax.ErrorHandler;
import org.xml.sax.Locator;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException;

import com.sun.xml.xsom.XSAttributeDecl;
import com.sun.xml.xsom.XSAttributeUse;
import com.sun.xml.xsom.XSComplexType;
import com.sun.xml.xsom.XSContentType;
import com.sun.xml.xsom.XSElementDecl;
import com.sun.xml.xsom.XSFacet;
import com.sun.xml.xsom.XSModelGroup;
import com.sun.xml.xsom.XSParticle;
import com.sun.xml.xsom.XSRestrictionSimpleType;
import com.sun.xml.xsom.XSSchema;
import com.sun.xml.xsom.XSSchemaSet;
import com.sun.xml.xsom.XSSimpleType;
import com.sun.xml.xsom.XSTerm;
import com.sun.xml.xsom.XSType;
import com.sun.xml.xsom.XmlString;
import com.sun.xml.xsom.parser.AnnotationContext;
import com.sun.xml.xsom.parser.AnnotationParser;
import com.sun.xml.xsom.parser.XSOMParser;
import com.sun.xml.xsom.util.DomAnnotationParserFactory;

public class XSDParser {
	private static final Logger log = Logger.getLogger(XSDParser.class);

	private XSOMParser parser = new XSOMParser();
	// private XSSchema schema = null;
	private XSSchemaSet schemaSet = null;
	HashMap<String, String> namespaces = new HashMap<String, String>();

	private int elementid = 0;

	private String generateUniqueId() {
		elementid++;
		return "" + elementid;
	}

	public XSDParser(String xsd) {
		this.initXSSchema(xsd);
	}

	public XSDParser(InputStream is) {
		this.initXSSchema(is);
	}

	public XSDParser(Reader reader) {
		this.initXSSchema(reader);
	}

	public Map<String, String> getNamespaces() {
		return this.namespaces;
	}

	public void setNamespaces(HashMap<String, String> map) {
		this.namespaces = map;
	}

	private void initParser() {
		log.debug("initParser");
		this.parser.setAnnotationParser(new DomAnnotationParserFactory() {
			public AnnotationParser create() {
				return new AnnotationParser() {
					final StringBuffer content = new StringBuffer();

					public ContentHandler getContentHandler(
							AnnotationContext context,
							String parentElementName,
							ErrorHandler errorHandler,
							EntityResolver entityResolver) {
						return new ContentHandler() {
							public void characters(char[] ch, int start,
									int length) throws SAXException {
								content.append(ch, start, length);
							}

							public void endDocument() throws SAXException {
							}

							public void endElement(String uri,
									String localName, String name)
									throws SAXException {
							}

							public void endPrefixMapping(String prefix)
									throws SAXException {
							}

							public void ignorableWhitespace(char[] ch,
									int start, int length) throws SAXException {
							}

							public void processingInstruction(String target,
									String data) throws SAXException {
							}

							public void setDocumentLocator(Locator locator) {
							}

							public void skippedEntity(String name)
									throws SAXException {
							}

							public void startDocument() throws SAXException {
							}

							public void startElement(String uri,
									String localName, String name,
									Attributes atts) throws SAXException {
							}

							public void startPrefixMapping(String prefix,
									String uri) throws SAXException {
							}
						};
					}

					@Override
					public Object getResult(Object existing) {
						return content.toString();
					}
				};
			}
		});

		// System.out.println(schemaFileName + ":");

		if (this.parser == null) {
			log.error("schema parser is null!");
		} else {
			ErrorHandler errorHandler = new ErrorHandler() {

				@Override
				public void error(SAXParseException arg0) throws SAXException {
					log.error("error: " + arg0.getMessage());

				}

				@Override
				public void fatalError(SAXParseException arg0)
						throws SAXException {
					log.error("fatal: " + arg0.getMessage());
				}

				@Override
				public void warning(SAXParseException arg0) throws SAXException {
					log.error("warning: " + arg0.getMessage());
				}
			};
			this.parser.setErrorHandler(errorHandler);
		}
	}

	private void initXSSchema(String schemaFileName) {
		try {
			File file = new File(schemaFileName);
			this.initParser();
			this.parser.parse(file);
			this.schemaSet = this.parser.getResult();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	private void initXSSchema(InputStream is) {
		try {
			this.initParser();
			this.parser.parse(is);
			this.schemaSet = this.parser.getResult();
		} catch (Exception ex) {
			ex.printStackTrace();
		}
	}

	private void initXSSchema(Reader reader) {
		try {
			this.initParser();
			this.parser.parse(reader);
			this.schemaSet = this.parser.getResult();
		} catch (Exception ex) {
			ex.printStackTrace();
		}
	}

	public String getPrefixForNamespace(String namespace) {
		String prefix = "";
		if (this.namespaces.containsKey(namespace)) {
			prefix = this.namespaces.get(namespace);
		} else {
			if (namespace.equals("http://www.w3.org/2001/XMLSchema")
					|| namespace.equals("http://www.w3.org/XML/1998/namespace")) {
				prefix = "xml";
			} else {
				prefix = "pr" + this.namespaces.keySet().size();
				
			}

			this.namespaces.put(namespace, prefix);
		}

		return prefix;
	}

	private ArrayList<String> visitedElements = new ArrayList<String>();
	
	private String elementLabel(XSElementDecl e) {
		String label = e.getTargetNamespace() + ":" + e.getName();
		return e.getTargetNamespace() + ":" + e.getName();
	}
	
	public JSONObject getElementDescription(XSElementDecl edecl) {
		// log.debug("getElementDescription for " + edecl.getName());
		String namespace = edecl.getTargetNamespace();
		//if(namespace.length() == 0) namespace = edecl.getOwnerSchema().getTargetNamespace();

		JSONObject result = new JSONObject();
		if (edecl != null) {
			XSComplexType complexType = edecl.getType().asComplexType();
			if (complexType != null) {
				XSContentType contentType = complexType.getContentType();
				XSSimpleType simpleType = contentType.asSimpleType();
				XSParticle particle = contentType.asParticle();
				String name = edecl.getName();
				
				// get root base type
				XSType xstype = edecl.getType().getBaseType();
				while (xstype.getBaseType() != null) {
					if (xstype.getName().equals(xstype.getBaseType().getName()))
						break;
					if (xstype.getName().equalsIgnoreCase("string"))
						break;
					xstype = xstype.getBaseType();
				}
				String type = xstype.getName();
				result = result.element("name", name).element("id", "")
						.element("type", type);
				
				if(particle != null) {
// TODO: possibly problematic, min & max Occurs exist in particle before .getTerm.asElementDecl call
// consider removing
					BigInteger maxOccurs = particle.getMaxOccurs();
					BigInteger minOccurs = particle.getMinOccurs();
//					System.out.println("name: " + result.getString("name") + " " + maxOccurs + " " + minOccurs);
					result = result.element("maxOccurs", maxOccurs).element("minOccurs",
							minOccurs);
				}

				if (namespace.length() > 0) {
					result = result.element("prefix", this
							.getPrefixForNamespace(namespace));
				}

				// process attributes
				JSONArray attributes = this
						.processElementAttributes(complexType);
				result = result.element("attributes", attributes);

				// process enumerations
				if (simpleType != null) {
					JSONArray enumerations = this
							.processElementEnumerations(simpleType);
					if (enumerations.size() > 0) {
						result = result.element("enumerations", enumerations);
					}
				}

				// process children
				if (particle != null) {
					visitedElements.add(this.elementLabel(edecl));
					JSONArray elementChildren = new JSONArray();

					ArrayList<XSParticle> array = this
							.getParticleChildren(particle);
					log.debug("particle: " + edecl.getName() + " " + particle.getTerm());
					for (XSParticle p : array) {
						if (p.getTerm().isElementDecl()) {
							log.debug(p.getTerm().asElementDecl().getName() + " element decl");
							if (!visitedElements.contains(this.elementLabel(p.getTerm()
									.asElementDecl()))) {
								elementChildren.add(this
										.processChildParticle(p));
							}
						} else if (p.getTerm().isModelGroupDecl() || p.getTerm().isModelGroup()) {
							log.debug("model group");
							XSModelGroup group = null;
							
							if(p.getTerm().isModelGroupDecl()) {
								group = p.getTerm().asModelGroupDecl()
									.getModelGroup();
							} else {
								group = p.getTerm().asModelGroup();
							}
							
							XSParticle[] groupChildren = group.getChildren();
							for (XSParticle gp : groupChildren) {
								if (!visitedElements.contains(this.elementLabel(gp.getTerm()
										.asElementDecl()))) {
									elementChildren.add(this
											.processChildParticle(gp));
								}
							}
						}
					}
					
					log.debug("end particles for " + edecl.getName());

					result = result.element("children", elementChildren);
					visitedElements.remove(this.elementLabel(edecl));
				}
			} else {
				XSSimpleType simpleType = edecl.getType().asSimpleType();

				// process enumerations
				if (simpleType != null) {
					JSONArray enumerations = this
							.processElementEnumerations(simpleType);
					if (enumerations.size() > 0) {
						result = result.element("enumerations", enumerations);
					}
				}

				result = result.element("name", edecl.getName()).element(
						"type", "string")
						.element("id", this.generateUniqueId());

				if (namespace.length() > 0) {
					result = result.element("prefix", this
							.getPrefixForNamespace(namespace));
				}
			}
		} else {
			log.error(edecl + " is null!...");
		}

		result = result.element("mappings", new JSONArray());

		if (!result.has("children")
				|| result.getJSONArray("children").size() == 0) {
			if (result.getString("type").equals("anyType")) {
				result.put("type", "string");
			}
		}

		return result;
	}

	private JSONObject processChildParticle(XSParticle p) {
		JSONObject child = this.getElementDescription(p.getTerm()
				.asElementDecl());
		BigInteger maxOccurs = p.getMaxOccurs();
		BigInteger minOccurs = p.getMinOccurs();
		child = child.element("maxOccurs", maxOccurs).element("minOccurs",
				minOccurs);

		return child;
	}

	public JSONObject buildTemplate(JSONArray groups, String root) {
		Iterator<XSSchema> i = this.schemaSet.iterateSchema();
		XSElementDecl rootElementDecl = null;
		while (i.hasNext()) {
			XSSchema s = i.next();
			rootElementDecl = s.getElementDecl(root);
			if (rootElementDecl != null) {
				return buildTemplate(groups, rootElementDecl);
			}
		}

		return new JSONObject();
	}

	public JSONObject buildTemplate(JSONArray groups,
			XSElementDecl rootElementDecl) {
		JSONObject result = new JSONObject();
		String root = rootElementDecl.getName();
		String namespace = rootElementDecl.getTargetNamespace();
		//if(namespace.length() == 0) namespace = rootElementDecl.getOwnerSchema().getTargetNamespace();
		
		log.debug("init");
		result = result.element("mappings", new JSONArray()).element("id",
				"template_" + root);
		if (namespace.length() > 0) {
			String prefix = this.getPrefixForNamespace(namespace);
			result = result.element("prefix", prefix);
		}

		log.debug("check if group");
		// check if root is a group element (button)
		Iterator gi = groups.iterator();
		while (gi.hasNext()) {
			JSONObject group = (JSONObject) gi.next();
			if (root.equals(group.getString("element"))) {
				return result.element("name", root).element("type", "group");
			}
		}

		log.debug("get base type");
		XSType xstype = rootElementDecl.getType().getBaseType();
		while (xstype.getBaseType() != null) {
			if (xstype.getName().equals(xstype.getBaseType().getName()))
				break;
			if (xstype.getName().equalsIgnoreCase("string"))
				break;
			xstype = xstype.getBaseType();
		}

		visitedElements.add(this.elementLabel(rootElementDecl));
		result = result.element("name", rootElementDecl.getName()).element(
				"type", xstype.getName());
		// element types
		
		
		XSComplexType complexType = rootElementDecl.getType().asComplexType();
		XSSimpleType simpleType = rootElementDecl.getType().asSimpleType();
		
		if(complexType!=null)
		{
			log.debug("is complex type");
			XSContentType contentType = complexType.getContentType();
			XSParticle particle = contentType.asParticle();
			// process element attributes
			JSONArray attributes = this.processElementAttributes(complexType);
			result = result.element("attributes", attributes);
			
			// process children
			if (particle != null) {
				log.debug(particle == null);
				JSONArray elementChildren = new JSONArray();

				ArrayList<XSParticle> array = this.getParticleChildren(particle);
				for (XSParticle p : array) {
					if (p.getTerm().isElementDecl()) {
						JSONObject child;
						if(!visitedElements.contains(this.elementLabel(p.getTerm().asElementDecl()))) {
							child = this.buildTemplate(groups, p.getTerm()
								.asElementDecl());
							elementChildren.add(child);
						}
					} else if (p.getTerm().isModelGroupDecl()) {
						XSModelGroup group = p.getTerm().asModelGroupDecl()
								.getModelGroup();
						XSParticle[] groupChildren = group.getChildren();
						for (XSParticle gp : groupChildren) {
							String name = gp.getTerm().asElementDecl().getName();
							log.debug(name);
							JSONObject child;
							if(!visitedElements.contains(this.elementLabel(gp.getTerm().asElementDecl()))) {
								child = this.buildTemplate(groups, gp.getTerm()
									.asElementDecl());
								elementChildren.add(child);
							}
						}
					}
				}

				result = result.element("children", elementChildren);
			}
		}
		
		visitedElements.remove(this.elementLabel(rootElementDecl));
		
		// process enumerations
		if (simpleType != null) {
			log.debug("is simple type");
			JSONArray enumerations = this
					.processElementEnumerations(simpleType);
			if (enumerations.size() > 0) {
				result = result.element("enumerations", enumerations);
			}

			result = result.element("type", "string");
		}
		return result;
	}

	private JSONArray processElementAttributes(XSComplexType complexType) {
		JSONArray attributes = new JSONArray();

		/*
		 * Collection<? extends XSAttributeUse> acollection =
		 * complexType.getAttributeUses(); Iterator<? extends XSAttributeUse>
		 * aitr = acollection.iterator();
		 */

		Iterator<? extends XSAttributeUse> aitr = complexType
				.iterateAttributeUses();
		while (aitr.hasNext()) {
			XSAttributeUse attributeUse = aitr.next();
			XSAttributeDecl attributeDecl = attributeUse.getDecl();
			String namespace = attributeDecl.getTargetNamespace();
			//if(namespace.length() == 0) namespace = attributeDecl.getOwnerSchema().getTargetNamespace();
			JSONObject attribute = new JSONObject().element("name",
					"@" + attributeDecl.getName()).element("id", "").element(
					"mappings", new JSONArray());
			if (namespace.length() > 0) {
				attribute = attribute.element("prefix", this
						.getPrefixForNamespace(namespace));
			}

			// check if it has a default value and assign it
			XmlString defaultValue = attributeDecl.getDefaultValue();
			if (defaultValue != null && defaultValue.value.length() > 0) {
				attribute = attribute.element("default", defaultValue.value);
			}

			// check if it is required
			if (attributeUse.isRequired()) {
				attribute = attribute.element("minOccurs", "1");
			}

			// check for enumerations in attributes
			XSSimpleType simpleType = attributeDecl.getType();
			JSONArray enumerations = this
					.processElementEnumerations(simpleType);
			if (enumerations.size() > 0) {
				attribute = attribute.element("enumerations", enumerations);
			}

			attributes.add(attribute);
		}

		return attributes;
	}

	private JSONArray processElementEnumerations(XSSimpleType simpleType) {
		JSONArray enumerations = new JSONArray();

		XSRestrictionSimpleType restriction = simpleType.asRestriction();
		if (restriction != null) {
			Iterator<? extends XSFacet> i = restriction.getDeclaredFacets()
					.iterator();
			while (i.hasNext()) {
				XSFacet facet = i.next();
				if (facet.getName().equals(XSFacet.FACET_ENUMERATION)) {
					// log.debug("enumeration: " + facet.getValue().value);
					enumerations.add(facet.getValue().value);
				}
			}
		}

		return enumerations;
	}

	private String processElementAnnotation(XSElementDecl edecl) {
		String annotation = "";

		if (edecl.getType().getAnnotation() != null
				&& edecl.getType().getAnnotation().getAnnotation() != null) {
			annotation = (String) edecl.getType().getAnnotation()
					.getAnnotation();
			annotation += "\n";
		}

		if (edecl.getAnnotation() != null
				&& edecl.getAnnotation().getAnnotation() != null) {
			annotation += (String) edecl.getAnnotation().getAnnotation();
		}

		return annotation;
	}

	private String processAttributeAnnotation(XSAttributeDecl edecl) {
		String annotation = "";

		if (edecl.getType().getAnnotation() != null
				&& edecl.getType().getAnnotation().getAnnotation() != null) {
			annotation = (String) edecl.getType().getAnnotation()
					.getAnnotation();
			annotation += "\n";
		}

		if (edecl.getAnnotation() != null
				&& edecl.getAnnotation().getAnnotation() != null) {
			annotation += (String) edecl.getAnnotation().getAnnotation();
		}

		return annotation;
	}

	private ArrayList<XSParticle> getParticleChildren(XSParticle particle) {
		ArrayList<XSParticle> children = new ArrayList<XSParticle>();

		// process children
		if (particle != null) {
			XSTerm term = particle.getTerm();
			XSModelGroup group = null;
			
			if (term.isModelGroup()) {
				group = term.asModelGroup();
			} else if (term.isModelGroupDecl()) {
				group = term.asModelGroupDecl().getModelGroup();			
			}
			
			if(group != null) {
				XSParticle[] particles = group.getChildren();
				for (XSParticle p : particles) {
					if(p.getTerm().isElementDecl()) {
						children.add(p);
					} else {
						ArrayList<XSParticle> particleChildren = this.getParticleChildren(p);
						children.addAll(particleChildren);
					}
				}
			}
		} 

		
		return children;
	}

	JSONObject documentation = new JSONObject();

	public JSONObject buildDocumentation() {
		documentation = new JSONObject();

		Iterator<XSElementDecl> i = this.schemaSet.iterateElementDecls();
		while (i.hasNext()) {
			XSElementDecl e = i.next();
			this.buildDocumentationFor(e);
		}

		return documentation;
	}

	private void buildDocumentationFor(XSElementDecl edecl) {
		String annotation = this.processElementAnnotation(edecl);
		String name = edecl.getName();
		String namespace = edecl.getTargetNamespace();
		if(namespace.length() == 0) {
			//namespace = edecl.getOwnerSchema().getTargetNamespace();
		}
		
		String tag = name;
		if(namespace.length() != 0) {
			String prefix = this.getPrefixForNamespace(namespace);
			tag = prefix + ":" + name;
		}
		
		if (documentation.has(name)) {
			if (annotation.equals(documentation.getString(name))) {
				// System.out.println("documentation mismatch for: " + name);
				// System.out.println("new: " + annotation);
				// System.out.println("old: " + documentation.getString(name));
			}
		}

		if (annotation.length() > 0) {
			documentation.element(tag, annotation);
		}

		XSComplexType complexType = edecl.getType().asComplexType();
		if (complexType != null) {
			// proccess children
			XSContentType contentType = complexType.getContentType();
			XSSimpleType simpleType = contentType.asSimpleType();
			XSParticle particle = contentType.asParticle();
			XSType xstype = edecl.getType().getBaseType();

			while (xstype.getBaseType() != null) {
				if (xstype.getName().equals(xstype.getBaseType().getName()))
					break;
				if (xstype.getName().equalsIgnoreCase("string"))
					break;
				xstype = xstype.getBaseType();
			}

			// log.debug("name: " + edecl.getName() + " orig: " + etype +
			// " type: " + type + " namespace: " + namespace);

			if (particle != null) {
				visitedElements.add(this.elementLabel(edecl));
				// JSONArray elementChildren = new JSONArray();

				ArrayList<XSParticle> array = this
						.getParticleChildren(particle);
				for (XSParticle p : array) {
					if (p.getTerm().isElementDecl()) {
						if (!visitedElements.contains(this.elementLabel(p.getTerm()
								.asElementDecl()))) {
							XSElementDecl child = p.getTerm().asElementDecl();
							this.buildDocumentationFor(child);
						}
					} else if (p.getTerm().isModelGroupDecl()) {
						XSModelGroup group = p.getTerm().asModelGroupDecl()
								.getModelGroup();
						XSParticle[] groupChildren = group.getChildren();
						for (XSParticle gp : groupChildren) {
							if (!visitedElements.contains(this.elementLabel(gp.getTerm()
									.asElementDecl()))) {
								XSElementDecl child = gp.getTerm().asElementDecl();
								this.buildDocumentationFor(child);
							}
						}
					}
				}

				visitedElements.remove(this.elementLabel(edecl));
			}

			// process attributes
			Iterator<? extends XSAttributeUse> aitr = complexType
					.iterateAttributeUses();
			while (aitr.hasNext()) {
				XSAttributeDecl attributeDecl = aitr.next().getDecl();
				attributeDecl.getAnnotation();
				
				String nm = attributeDecl.getTargetNamespace();
				if(nm.length() == 0) {
					//nm = attributeDecl.getOwnerSchema().getTargetNamespace();
				}
				
				tag = "@" + attributeDecl.getName();
				if(nm.length() != 0) {
					String prefix = this.getPrefixForNamespace(nm);
					tag = "@" + prefix + ":" + attributeDecl.getName();
				}
				
				String value = this.processAttributeAnnotation(attributeDecl);
				if (value.length() > 0) {
					documentation.element(tag, value);
				}
			}
		}
	}

	public JSONObject getElementDescription(String element) {
		JSONObject result = null;

		Iterator<XSSchema> i = schemaSet.iterateSchema();
		while (i.hasNext()) {
			XSSchema s = i.next();
			XSElementDecl edecl = s.getElementDecl(element);
			if (edecl != null) {
//				System.out.println("found direct: " + element);
				return this.getElementDescription(edecl);
			} else {
				Map<String, XSElementDecl> map = s.getElementDecls();
				Iterator<String> k = map.keySet().iterator();
				while (k.hasNext()) {
					String key = k.next();
					edecl = map.get(key);
					result = getElementDescriptionFromParent(edecl, element);
					if (result != null) {
//						System.out.println("found in parent: " + element + " - " + edecl.getName());
						return result;
					}
				}
			}
		}

		return result;
	}

	public JSONObject getElementDescriptionFromParent(XSElementDecl parent,
			String element) {
		JSONObject result = null;

		XSComplexType complexType = parent.getType().asComplexType();
		if (complexType != null) {
			XSContentType contentType = complexType.getContentType();
			XSParticle particle = contentType.asParticle();
			if (particle != null) {
				visitedElements.add(this.elementLabel(parent));

				ArrayList<XSParticle> array = this
						.getParticleChildren(particle);
				for (XSParticle p : array) {
					if (p.getTerm().isElementDecl()) {
						String name = p.getTerm().asElementDecl().getName();
						if (!visitedElements.contains(p.getTerm().asElementDecl())) {
							if (name.equals(element)) {
								result = this.processChildParticle(p);
								return result;
							}
						}
					} else if (p.getTerm().isModelGroupDecl()) {
						XSModelGroup group = p.getTerm().asModelGroupDecl()
								.getModelGroup();
						XSParticle[] groupChildren = group.getChildren();
						for (XSParticle gp : groupChildren) {
							String name = gp.getTerm().asElementDecl()
									.getName();
							if (!visitedElements.contains(p.getTerm().asElementDecl())) {
								if (name.equals(element)) {
									result = this.processChildParticle(gp);
									return result;
								}
							}
						}
					}
				}

				visitedElements.remove(this.elementLabel(parent));
			}
		}

		return result;
	}

	public JSONObject getRootElementDescription(String element) {
		Iterator<XSSchema> i = schemaSet.iterateSchema();
		while (i.hasNext()) {
			XSSchema s = i.next();
			XSElementDecl edecl = s.getElementDecl(element);
			if (edecl != null) {
				// log.debug("found: " + edecl.getName() + " in " +
				// s.getTargetNamespace());
				JSONObject result = this.getElementDescription(edecl);
				return result;
			}
		}

		return new JSONObject().element("error", "root element " + element
				+ " not found in any schema set");
	}

	public JSONObject getAnyRootElementDescription() {
		Iterator<XSSchema> i = schemaSet.iterateSchema();
		while (i.hasNext()) {
			XSSchema s = i.next();
			Map<String, XSElementDecl> map = s.getElementDecls();
			Iterator<String> k = map.keySet().iterator();
			while (k.hasNext()) {
				String key = k.next();
				JSONObject o = this.getRootElementDescription(key);
				if (o != null)
					return o;
			}
		}

		return null;
	}
}
