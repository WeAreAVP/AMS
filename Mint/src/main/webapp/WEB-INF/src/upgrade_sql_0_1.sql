-- upgrade life server to newest schema, migrate data as far as possible
-- new columns in mapping


alter table mapping add column shared boolean not null default false;
alter table mapping add column finished boolean not null default false;

alter table transformation add column output_xml_object_id int references xml_object;
alter table transformation add column json_mapping text;

-- some magic on the xml_node_master
-- long queries !!
update xml_node_master set parent_node_id = null where parent_node_id = 0;


-- delete from xml_node_master where node_id = 0;
-- wild guess:
delete from xml_node_master where xml_node_id = 0;

update xml_node_master set size = 1 where size = 0;


create table global_namespaces (
	uri text,
	prefix text
);

-- execute a script 
-- goto athena/Script and paste the "create the prefixes for existing db"  scriptlet into the textbox
-- find it in scriptlets.groovy


