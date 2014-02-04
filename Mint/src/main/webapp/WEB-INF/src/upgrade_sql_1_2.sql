-- adding publication support to the database

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


alter table mapping add column target_schema text;

delete from mapping where mapping_id = 1;
insert into mapping( mapping_id, creation_date, name, organization_id, json, shared, finished ) values
 ( 1, '2009-01-01', 'LidoToLido', 1, null, false, false);
alter table data_upload add column schema_name text;
