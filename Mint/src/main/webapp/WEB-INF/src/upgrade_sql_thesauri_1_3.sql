
create sequence seq_thesauri_id start with 1000;

create table thesauri (
    thesauri_id int,
    title text,
    description text,
    contact text,
    url text,
    upload_date timestamp,
    organization_id int,
    blob_id int,
    filename text,
    content_type text,
    primary key(thesauri_id),
    foreign key(organization_id) references organization(organization_id) on update cascade on delete set null
);


create sequence seq_thesauri_assign_id start with 1000;

create table thesauri_assign (
    assign_id int,
    xpath int,
    thesaurus_id int,
    assign_date timestamp,
    user_id int,
    data_upload_id int,
    primary key(assign_id),
    foreign key(thesaurus_id) references thesauri(thesauri_id) on delete cascade,
    foreign key(user_id) references users(users_id) on delete set null,
    foreign key(data_upload_id) references data_upload(data_upload_id) on delete set null,
    foreign key(xpath) references xpath_summary(xpath_summary_id) on delete cascade
);