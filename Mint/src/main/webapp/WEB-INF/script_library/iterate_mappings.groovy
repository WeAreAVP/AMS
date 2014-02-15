import java.util.*;
import net.sf.json.*;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.mapping.*;

// iterate mappings

mappings = DB.getMappingDAO().findAllOrderOrg();

for(mapping in mappings) {
    json = mapping.getJsonString();
    object = JSONSerializer.toJSON(json);
}
