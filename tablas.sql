
create table `rol` (`id` bigint unsigned not null auto_increment primary key, `nombre` varchar(50) not null, `activo` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `usuario` (`id` bigint unsigned not null auto_increment primary key, `nombre` varchar(50) not null, `apellidos` varchar(50) not null, `correo` varchar(255) not null, `fecha_registro` date not null, `contraseña` varchar(255) not null, `nombre_usuario` varchar(255) not null, `rol_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `usuario` add constraint `usuario_rol_id_foreign` foreign key (`rol_id`) references `rol` (`id`);
alter table `usuario` add unique `usuario_correo_unique`(`correo`);
create table `documento` (`id` bigint unsigned not null auto_increment primary key, `usuario_id` int unsigned not null, `nombre` varchar(50) not null, `extension` varchar(50) not null, `url` varchar(100) not null, `tipo` varchar(4) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `documento` add constraint `documento_usuario_id_foreign` foreign key (`usuario_id`) references `usuario` (`id`);
