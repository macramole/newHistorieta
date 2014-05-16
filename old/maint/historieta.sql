drop database if exists historieta;

create database historieta default charset utf8;

use historieta;

create table usuarios (
	id varchar(50) not null, 
    name varchar(200) null,
    first_name varchar(100) null,
    middle_name varchar(100) null,
    last_name varchar(100) null,
    link varchar(200) null,
    gender varchar(50) null,
    timezone varchar(20) null,
    locale varchar(20) null,
    updated_time varchar(50) null,
    
    app_ingreso datetime null,
    app_ultimo datetime null,
    
    email varchar(200) null,
    
    primary key (id)
);

create table postales (
	pos_codigo int not null auto_increment,
    pos_hash varchar(50) null,
	pos_id varchar(50) not null, 
	pos_nombre varchar(100) null,
	pos_apellido varchar(100) null,
	pos_amigo_id varchar(200) null,
	pos_amigo_nombre varchar(100) null,
	pos_amigo_apellido varchar(100) null,
	pos_postal varchar(100) null,
    pos_saludo varchar(300) null,
    pos_tipo varchar(50) null,
    primary key (pos_codigo)
);

create unique index ix_postales1 on postales(pos_hash);
create index ix_postales2 on postales(pos_amigo_id);
create index ix_postales3 on postales(pos_id);

grant usage on historieta.* to 'historieta'@'localhost';
drop user 'historieta'@'localhost';
create user 'historieta'@'localhost' identified by '34hJrhryi4';
grant all privileges on historieta.* to 'historieta'@'localhost';

create table contactos (
	con_codigo int not null auto_increment,
    con_nombre varchar(200) null,
	con_mail varchar(200) not null, 
	con_mensaje text null,
	con_fecha datetime null,
    primary key (con_codigo)
);
