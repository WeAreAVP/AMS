import org.apache.commons.io.CopyUtils

// Which orgs have idle transformations?

def  orgs = [: ];

for( tr in DB.getTransformationDAO().findAll() ) {
  if( tr.statusCode == tr.ERROR ) {
	name = tr.dataUpload.organization.englishName
	if( orgs[name] ) orgs[name] += 1
        else  orgs[ name ] = 1  
  }
}

orgs.each{ println it.key+" " + it.value }

""
