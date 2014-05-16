use historieta;

create table trivia (
tri_codigo int not null primary key AUTO_INCREMENT,
tri_nombre varchar(200) null,
tri_correo varchar(200) null,
tri_fecha datetime null,
tri_puntaje int null);

create table fotos (
fot_codigo int not null primary key AUTO_INCREMENT,
fot_archivo varchar(200) null,
fot_estado varchar(50) null,
fot_fecha datetime null,
fot_nombre varchar(200) null,
fot_correo varchar(200) null );

create table invita_trivia (
itr_codigo int not null primary key AUTO_INCREMENT,
tri_codigo int null,
itr_nombre varchar(200) null,
itr_correo varchar(200) null,
itr_fecha datetime null );