-- generally foreign key constraints are going to be managed in the
-- application. So the ON DELETE case in the constraint should actually
-- never have to be executed...

-- generally sequences start with 1000
-- data below is meant to be test data

-- terms with no parents are considered list names

create language plpgsql;
create sequence seq_users_id start with 1002;
create table users (
	users_id int  primary key,
 	login text unique,
	job_role text,
    first_name text,
    last_name text,
  	email text,
	work_telephone text,
  	md5_password text,
	organization_id int,
	password_expires date,
	account_created date,
	active_account boolean,
	rights int
);

-- Super user has 31 lower bits set. I didnt want to touch the sign bit, scared of complications --
insert into users( users_id, first_name, last_name, email, login, md5_password, active_account, rights , account_created ) values
 ( 1000, 'MINT', 'Admin', 'someone@somwhere.com', 'admin', md5( 'admin' || 'admin' ), true, ~(1<<31), '2009-01-01' );

create sequence seq_organization_id start with 1001;
create table organization (
	organization_id int primary key,
	original_name text,
	english_name text,
	short_name text,
	address text,
	description text,
	country text,
	url_pattern text,
    primary_contact_user_id int references users on delete set null,
	-- one of content provider, aggregator, national contact, reviewer --
	-- doesn't do much --
	-- museum, library, archive .. --
	org_type text,
	parental_organization_id int references organization 
);

-- circular user and organization foreign key
ALTER TABLE users ADD FOREIGN KEY (organization_id) REFERENCES organization on delete set null;

insert into organization(organization_id, english_name,  parental_organization_id , country ) values
 ( 1, 'Default', null, 'Default' );
update users set organization_id = 1 where users_id in ( 1000, 1001 );

create sequence seq_mapping_id start with 1000;
create table mapping (
	mapping_id int primary key,
	creation_date timestamp, 
	name text,
	organization_id int references organization,
	target_schema_id int,
	shared boolean not null,
	finished boolean not null,
	json text
);

 

-- dummy object to own xml nodes and xpaths
create sequence seq_xml_object_id start with 1000;
create table xml_object(
	xml_object_id int primary key
);


-- store all the xpaths and count them per data upload --
create sequence seq_xpath_summary_id start with 1000;
create table xpath_summary (
	xpath_summary_id int primary key,
	xml_object_id int references xml_object on delete cascade,
	xpath text,
	count bigint,
	name text,
	uri text,
	uri_prefix text,
	optional boolean,
	multiple boolean,
	description text,
	parent_summary_id bigint references xpath_summary on delete set null
);


create sequence seq_blob_wrap_id start with 1000;
create table blob_wrap (
	blob_wrap_id bigint primary key,
	data oid
);

create sequence seq_data_upload_id start with 1001;
create table data_upload (
	data_upload_id int primary key,

	-- I guess time of last status change goes here 
	upload_date timestamp,
	organization_id int references organization,
	uploader_id int references users,

	-- only for zips its bigger than 1 --
	no_of_files int not null default -1,

	-- for OAI reps here goes the URL --
    source_url text,

	-- http uploads should provide this --
	original_filename text,

	-- xml, excel and other later if we provide --	
	structural_format text,

	-- was it an http upload ? --
    http_upload boolean not null default false,

	-- if admin upload is true, files reach the server somehow (email.. jikes)
	-- and the admin performs the upload
	admin_upload boolean not null default false,

	-- was the original data zipped? --
	zipped_upload boolean not null default false,

	-- oai harvests might need to be revisited
	oai_harvest boolean not null default false,

	-- how many bytes were uploaded ? --
	-- for a zip file this will be pretty much the same as the db --
	-- blob size --
	upload_size bigint not null default -1,

	-- once parsed store here how many nodes were created --
	node_count bigint not null default -1,

	-- predefined status (hardcoded) 0-OK -1-ERROR others ..
	status int not null default -2,
	message text,

	-- what xml belongs to this upload
	xml_object_id int references xml_object on delete set null,

	-- which xpath marks the item level (optional)
	item_xpath_id bigint references xpath_summary on delete set null,

	-- and inside the item, what label should we use for display
	item_label_xpath_id bigint references xpath_summary on delete set null,

	-- oai token
	resumption_token text,

	-- optional json mapping object
	mapping_id int references mapping on delete set null,

	-- xsl stored here, although only for one mapping
	-- this seems to be the new goal, restrict to one output format

	xsl text,

	-- optional if the upload happens in a known schema that the system might
	-- handle automatically (like lido, museumdat etc)
	schema_name text,
	xml_schema_id int,
	-- zipped content of whatever was uploaded in whatever way --
	-- moved ot a different table because of locking / updateing problems
	blob_wrap_id bigint references blob_wrap 	
);


-- track a transformation and record the result in the database
create sequence seq_transformation_id start with 1000;
create table transformation (
	transformation_id int primary key,
	data_upload_id int references data_upload on delete cascade,
	mapping_id int references mapping,
	users_id int references users,
	status_code int not null default -2,
	status_message text,
	begin_transform timestamp,
	end_transform timestamp,
	output_xml_object_id int references xml_object,
	json_mapping text,
	report text,
	blob_wrap_id int references blob_wrap
);

-- if the upload contains many files, it would be handy to have
-- for each file the rootnode, so you can find them if need be

create table xml_file_root(
	data_upload_id int references data_upload,
	filename text,

	-- which node is the rootnode for this file
	root_node_id bigint
);



-- need to get rid of the oid storage --

CREATE OR REPLACE FUNCTION on_upload_delete() RETURNS trigger
AS $on_upload_delete$
BEGIN
PERFORM lo_unlink( old.data );
RETURN null;
END;
$on_upload_delete$
LANGUAGE plpgsql
IMMUTABLE
RETURNS NULL ON NULL INPUT;

CREATE TRIGGER upload_delete_trigger
AFTER DELETE
ON blob_wrap
FOR EACH ROW EXECUTE PROCEDURE on_upload_delete();


-- xml nodes need to be droped quickly before the xml_object goes
-- or the database will need years for this operation

CREATE OR REPLACE FUNCTION xml_object_delete() RETURNS trigger
AS $$
BEGIN
	BEGIN
		EXECUTE 'drop table xml_node_' || old.xml_object_id;
	EXCEPTION WHEN undefined_table then
  		RETURN old;
  	END;
RETURN old;
END;
$$
LANGUAGE plpgsql
VOLATILE
RETURNS NULL ON NULL INPUT;



CREATE TRIGGER xml_object_delete_trigger 
BEFORE DELETE
ON xml_object
FOR EACH ROW EXECUTE PROCEDURE xml_object_delete();


create sequence seq_locks_id start with 1000;
create table locks (
	locks_id bigint primary key,
	object_id bigint,
	object_type text,
	aquired timestamp,
	user_login text,
	http_session_id text,
	name text,
	unique (object_type, object_id )
);



-- each row in xml can create (many) of those --
-- why having xml like this ?
-- this may proove completely stupid, we are not attaching anything to nodes (yet)
-- we can have 
create sequence seq_xml_node_id start with 1000 increment by 1000;
create table xml_node_master (
	xml_node_id bigint,
-- should have foreign key here, but then we need to put parent nodes
-- before child nodes, but then we cant have checksum from children
	parent_node_id bigint,

	-- No foreign key here, delays deletes endlessly
	xml_object_id int,

	-- shortcut to the xpath for this text
	xpath_summary_id int,

	-- one of text, element, attribute
	node_type smallint,
	content text,

	-- how many subnodes from here
	size bigint,
	checksum text
);

-- is read on app start
-- updated in imports
create table global_namespaces (
	uri text,
	prefix text
);

alter table data_upload add foreign key( item_xpath_id ) references xpath_summary;
create index idx_xpaths on xpath_summary( xpath );



-- an import creates new entries for given organization
-- how many of each xpaths and how many different ones
-- helps with the analysis
-- not used yet
create table stats_view (
	stats_view_id int primary key,
	organization_id int references organization,
	xpath text,
	count_node int,
	count_unique_content int,
	count_empty int,
	content_length_avg int
);

create sequence seq_publication_id start with 1000;

-- a publication is a group of transformation with some
-- postprocessing of the data
-- mostly versioning, duplicate removal, reconziliation of edit nodes
create table publication (
	publication_id int primary key,

	-- this timestamp should be looked at to find if the involved transformations
	-- have had changes since last publication
	last_process timestamp,
	item_count bigint,
	user_id int references users,
	organization_id int references organization,
	status_code int,
	status_message text,
	report text,
	blob_wrap_id int references blob_wrap,
 
	-- to find the right transformation
	target_schema text
);

-- from the data uploads the system has to find the transformation and xml_object
-- to include in the publication.
-- if the mapping changes or new edits for the transformation are available,
-- the publication can be outdated. 
create table publication_input (
	publication_id int references publication,
	data_upload_id int references data_upload
);

create sequence seq_xml_schema_id start with 1000;
create table xml_schema(
	xml_schema_id int primary key,
	name text,
	-- file path to the xsd
	-- too complicated to have the string here
	xsd text,
	item_level_path text,
	item_label_path text,
	item_id_path text,
	json_config text,
	json_template text,
	documentation text,
	created timestamp
);

ALTER TABLE mapping ADD FOREIGN KEY (target_schema_id) references xml_schema on delete set null;
ALTER TABLE data_upload ADD FOREIGN KEY (xml_schema_id) references xml_schema on delete set null;
 
create sequence seq_crosswalk_id start with 1000;
create table crosswalk(
    crosswalk_id int primary key,
    source_schema_id int references xml_schema,
    target_schema_id int references xml_schema,
    xsl text,
    json_mapping_template text,
    created timestamp
);






create table meta (
  meta_id int primary key,
  meta_key text,
  meta_value text
);

-- track schema version. Each published schema needs that
-- for each change to a published schema we need patch files that
-- upgrade the database without loosing the data (as much as that is possible) 

insert into meta( meta_id, meta_key, meta_value ) values( 1, 'schema.version', '2' );
