/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.37-0ubuntu0.14.04.1 : Database - historieta
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `admins` */

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(40) COLLATE utf8_bin NOT NULL,
  `password` varchar(32) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `admins` */

insert  into `admins`(`id`,`user`,`password`,`last_login`,`email`) values (1,'admin','a8f5f167f44f4964e6c998dee827110c',NULL,NULL),(3,'blog','a8f5f167f44f4964e6c998dee827110c',NULL,'blog@gbl.com');

/*Table structure for table `admins_permisos` */

DROP TABLE IF EXISTS `admins_permisos`;

CREATE TABLE `admins_permisos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idAdmin` int(11) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `admins_permisos` */

insert  into `admins_permisos`(`id`,`idAdmin`,`title`) values (2,3,'Entrada');

/*Table structure for table `autores` */

DROP TABLE IF EXISTS `autores`;

CREATE TABLE `autores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) DEFAULT NULL,
  `texto` text,
  `weight` int(11) DEFAULT NULL,
  `bio` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `autores` */

insert  into `autores`(`id`,`title`,`texto`,`weight`,`bio`) values (1,'Quino','<ul><li>Creador de mafalda</li><li>Nació en Mendoza en 1932. Se dedicó al dibujo desde pequeño, guiado por su tío Joaquin Téjon, dibujante profesional<br></li></ul>',0,'<h2>El título<br></h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sodales dictum nulla et pharetra. Sed aliquam rutrum leo nec tincidunt. <a target=\"_blank\" data-cke-saved-href=\"http://www.google.com\" href=\"http://www.google.com\">Donec in molestie</a> lorem. Nulla porttitor sem eget nulla pellentesque placerat. Vestibulum ut massa vel augue tincidunt luctus. Vestibulum ut purus eleifend nibh vel, <a data-cke-saved-href=\"mapa\" href=\"mapa\">molestie</a> augue. <i>Vestibulum facilisis turpis nibh, nec scelerisque urna hendrerit quis</i>. <strong>Fusce</strong> sit amet magna ac nisi ultrices eleifend in commodo sem.</p><h3>Subtítulo</h3><p>Curabitur rhoncus nibh elementum, varius arcu ut, eleifend tortor. Quisque id orci vestibulum, congue augue id, aliquet enim. Phasellus tincidunt tincidunt auctor. Ut euismod tortor eget auctor mollis. Ut et auctor est. Fusce imperdiet purus erat, ac semper diam tincidunt sit amet. Nullam diam libero, porttitor ac eleifend sed, ullamcorper eu mi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p><h3>Otro Subtítulo</h3><p>Aliquam aliquam nulla ac imperdiet pharetra. Nullam tristique, magna et laoreet adipiscing, dolor nulla sollicitudin turpis, pretium posuere justo metus ornare erat. Duis tortor odio, vestibulum volutpat turpis eleifend, commodo rhoncus nunc. Morbi dapibus lectus augue, in volutpat odio commodo in.</p>'),(3,'Garc&iacute;a Ferr&eacute;','',3,''),(4,'Sendra','',2,'');

/*Table structure for table `clean_urls` */

DROP TABLE IF EXISTS `clean_urls`;

CREATE TABLE `clean_urls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table` varchar(64) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `node_id` int(10) unsigned NOT NULL,
  `url` tinytext CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `language` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `table` (`table`,`node_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `clean_urls` */

insert  into `clean_urls`(`id`,`table`,`node_id`,`url`,`language`) values (17,'galerias',1,'galeria/fotos',NULL),(5,'galerias',2,'galeria/wallpapers',NULL),(15,'autores',1,'autores/quino',NULL),(13,'autores',3,'autores/garcia-ferre',NULL),(14,'autores',4,'autores/sendra',NULL),(18,'entradas',1,'autores/quino/prueba',NULL),(21,'entradas',7,'autores/quino/otra-prueba',NULL),(24,'personajes',1,'galeria/mafalda',NULL),(25,'personajes',4,'galeria/clemente',NULL),(26,'personajes',5,'galeria/catalina',NULL);

/*Table structure for table `configuracion` */

DROP TABLE IF EXISTS `configuracion`;

CREATE TABLE `configuracion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `configuracion` */

/*Table structure for table `entradas` */

DROP TABLE IF EXISTS `entradas`;

CREATE TABLE `entradas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idAutor` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `cuerpo` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `entradas` */

insert  into `entradas`(`id`,`idAutor`,`fecha`,`title`,`cuerpo`) values (1,1,'2014-05-15','Prueba','<p>Vestibulum at nisi sed arcu tristique ultricies. Integer sed auctor magna, id vulputate magna. Pellentesqasdasdue porta lectus quis pretium consequat. Vestibulum aliquam pharetra nisi, nec cursus purus. Etiam el</p><p><img alt=\"\" data-cke-saved-src=\"http://localhost/newHistorieta/uploads/Don-Nicola.png\" src=\"http://localhost/newHistorieta/uploads/Don-Nicola.png\" style=\"border-style:solid; border-width:0px; height:181px; margin-left:2px; margin-right:2px; width:151px\"></p><p>Nullam venenatis neque non ultricies semper. Cras semper mollis massa, et vulputate tellus sagittis a. Curabitur pharetra, quam eget gravida adipiscing,</p><h3>Subtítulo</h3><p>ipsum quam condimentuwm nibh, eu venenatis leo velit id risus. Vivamus a varius nisi. Maecenas semper id diam ac elementum.<br></p>'),(7,1,'2014-05-19','Otra prueba','<p>asdasdasdasdasdas</p>');

/*Table structure for table `files` */

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `node_id` int(10) unsigned DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` tinytext COLLATE utf8_bin,
  `flag` tinyint(10) NOT NULL DEFAULT '0',
  `weight` int(11) NOT NULL DEFAULT '0',
  `language` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `files` */

insert  into `files`(`id`,`table`,`node_id`,`filename`,`description`,`flag`,`weight`,`language`) values (4,'galerias',1,'IMG_0086.jpg',NULL,0,2,NULL),(5,'galerias',1,'IMG_0258.jpg',NULL,0,3,NULL),(6,'galerias',1,'IMG_0260.jpg',NULL,0,5,NULL),(7,'galerias',1,'IMG_0261.jpg',NULL,0,6,NULL),(8,'galerias',1,'IMG_0263.jpg',NULL,0,7,NULL),(9,'galerias',1,'IMG_0262.jpg',NULL,0,0,NULL),(10,'galerias',1,'IMG_0270.jpg',NULL,0,8,NULL),(11,'galerias',1,'IMG_0275.jpg',NULL,0,9,NULL),(12,'galerias',1,'IMG_0303.jpg',NULL,0,1,NULL),(13,'galerias',1,'IMG_0277.jpg',NULL,0,4,NULL),(14,'galerias',2,'cachorra_wp.jpg',NULL,0,0,NULL),(15,'galerias',2,'clemente_wp.jpg',NULL,0,1,NULL),(16,'galerias',2,'divito_wp.jpg',NULL,0,2,NULL),(17,'galerias',2,'donfulgencio_wp.jpg',NULL,0,3,NULL),(18,'galerias',2,'gaturro_wp.jpg',NULL,0,4,NULL),(19,'galerias',2,'gaturroynovia_wp.jpg',NULL,0,5,NULL),(20,'galerias',2,'locochavez_wp.jpg',NULL,0,7,NULL),(21,'galerias',2,'isidoroycachorra_wp.jpg',NULL,0,6,NULL),(22,'galerias',2,'matias_wp.jpg',NULL,0,8,NULL),(23,'galerias',2,'patoruzu_wp.jpg',NULL,0,9,NULL),(24,'autores',1,'quino.png',NULL,0,0,NULL),(25,'autores',1,'mafaldayquino.jpg',NULL,1,1,NULL),(26,'personajes',1,'mafalda_autores.png',NULL,0,0,NULL),(27,'personajes',4,'clemente.png',NULL,0,0,NULL),(28,'personajes',1,'mafalda.png',NULL,1,1,NULL),(29,'personajes',4,'clemente90.png',NULL,1,1,NULL),(30,'personajes',5,'catalina.png',NULL,0,0,NULL),(38,'personajes',4,'IMG_026288.jpg',NULL,2,4,NULL),(39,'personajes',4,'IMG_026129.jpg',NULL,2,2,NULL),(37,'personajes',4,'IMG_026330.jpg',NULL,2,3,NULL);

/*Table structure for table `files_thumbs` */

DROP TABLE IF EXISTS `files_thumbs`;

CREATE TABLE `files_thumbs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idFile` int(10) unsigned NOT NULL,
  `filename` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=179 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `files_thumbs` */

insert  into `files_thumbs`(`id`,`idFile`,`filename`) values (4,4,'928406318e403a2e02b3765b4beb8abc.jpg'),(5,5,'38c459dd52e8667ef4f5f4c381f4def2.jpg'),(6,6,'987b8d1cc0229805b9a0c005620aa9e6.jpg'),(7,7,'5cde8b07654d0e4e67dba676e48fe400.jpg'),(8,8,'6cbf743adedc2eaf9d89631058c7455c.jpg'),(9,9,'9a3a86e333fc3ac59d3a85829cb7cfaf.jpg'),(10,10,'57dfc24e6c8f5cace51db263a713cbcf.jpg'),(11,11,'edd24ff69122d47cbab41c858d7cd5e8.jpg'),(12,12,'a24ce8524b35b99cca1f16526879cca3.jpg'),(13,13,'a372cf452d21fffffd483a67d53fdc08.jpg'),(14,14,'41b46924452247cda3cb2487c432f24a.jpg'),(15,15,'c92ebf91a0d5a048a89abaad32e32335.jpg'),(16,16,'6a0a08a32c0aeed649deb8d11a285131.jpg'),(17,17,'63be31e7e05ab79d6b8bc13bcbf64bcc.jpg'),(18,18,'e942697932fd048f29ce048e4ae6fca2.jpg'),(19,19,'ee34a4a4f2cb7c89ef589c034eebb758.jpg'),(20,20,'6704ee3279f3dfe656458ce190924f2f.jpg'),(21,21,'8e8553f9b7d55d90cbf224ace04b2965.jpg'),(22,22,'a306e99a3152e614b6603b11d5686c3d.jpg'),(23,23,'458dc54a08e3a96d988b61bfba3bf28a.jpg'),(24,4,'753cae5ff1079af81d8d77e171807730.jpg'),(25,5,'d521b1be1a2b475252ac892109bf60ea.jpg'),(26,6,'b26a4d4cace2b3b8216cd1456fea1c52.jpg'),(27,7,'122c5a0e44194bc7db8c16136dc22bfe.jpg'),(28,9,'b16b1cd1aa9016f672d9b59346e62d6a.jpg'),(29,8,'e8102916890cf81b1177bc18a9f0dcbd.jpg'),(30,10,'8489503bbe6b8fcdb0f270458fde6a83.jpg'),(31,11,'da65e474c148284cce3b21412559272c.jpg'),(32,13,'f126ec3f0dff38a84b84196891dbdf12.jpg'),(33,12,'4339ad9e61b454c4412b98163e6dc451.jpg'),(34,14,'8f5f0b22610ac7cdbacd1831bae01795.jpg'),(35,15,'bf5879870298ff6201037aea127a4fe8.jpg'),(36,16,'2c6ecfe5b30c2532a83686e87a91aef2.jpg'),(37,17,'52d08e01ca6f0f43d5c1f6327b3946bc.jpg'),(38,18,'0b98ce2d602ac4f9df3dd6959f84888b.jpg'),(39,19,'f05d03b8c5a034311be96754ebe550de.jpg'),(40,21,'9d65f0a40e9a622af9c4714e013524ec.jpg'),(41,20,'7dab2a94003f63c3299a737e70b7559e.jpg'),(42,22,'012276886a1e45b12a84ac9a244f2aa3.jpg'),(43,23,'1e4872b2046f11bb434d993913cd6e82.jpg'),(44,24,'af1f248a2fa44e5430b417ecf234e992.png'),(45,25,'b556a20dddaa3dcef8858fe13e80ca0a.jpg'),(46,24,'9a987c8a9ad0b250bd6d436c37de02c3.png'),(47,25,'d90f403dbe82ed2cf890d33c5535284e.jpg'),(48,26,'fa9630e4654ca1d92de6fad10f9b31f3.png'),(49,26,'4a447a22725f799c1ccc58aeb7c9c438.png'),(50,27,'2411c8419d333090d0e0630107f6a933.png'),(51,27,'ad8d28c6ab6f80614fdb07fffbd47899.png'),(52,28,'82cee1d38680a21a079f3560dfab5b2c.png'),(53,28,'772ecc8755b77d5244f9912802fbd8bc.png'),(54,29,'58f61a3a553ee838be4c0f8e6c678249.png'),(55,29,'c44f5019d1d87ab53858b92c28640ae4.png'),(56,30,'48d918acaae36492d2101e673169458a.png'),(57,30,'96c44074aeb0a4ac035be9ef87d43800.png'),(79,4,'9edf72499439795188e195a3aa2a29b6.jpg'),(80,5,'69ce46330b5c37b0e14da7bb13e1a94f.jpg'),(78,12,'6e67d4874fdd62089e15f3d4b29cfc4e.jpg'),(70,37,'1cac3dc5b1715c919b29862df7969256.jpg'),(69,39,'12319b2dddd431203d0a44fb47ba7e84.jpg'),(67,37,'eefaafbede2cde399a25044837eb8ecc.jpg'),(68,38,'29868d3c54f420001e4fa1b6164017ed.jpg'),(71,37,'1b03d34a090ea4a72d15fd24f7ec4b85.jpg'),(72,37,'4c75818fe9000120bdac662d021254e5.jpg'),(73,39,'eb42fe5e78fab25b9549bec0123d20f8.jpg'),(77,9,'78aca38e9f98866635c95fe2d6c0f043.jpg'),(76,39,'bc8daa7d186e31744000a8e9dae8fbc8.jpg'),(81,13,'a97b4f0d9abe99d64fb3501c3318149f.jpg'),(82,6,'e6a7d3504fc134013d37acadc5c738bb.jpg'),(83,7,'eb2065e81067a641be87b7b3a6efd5d9.jpg'),(84,8,'a4122fd160022062d9dce3770c9a1b49.jpg'),(85,10,'a2a7fbd3be364a0ad7046350776ab7de.jpg'),(86,11,'3b4fba5e37fa6800b01a3294329d8f31.jpg'),(87,39,'e00ad9073621ee78ac897c4755f3996c.jpg'),(88,9,'a7797d53e8f208adfc6137dd14536005.jpg'),(89,12,'58563e8e4d97a3e039f58f04c66415c0.jpg'),(90,4,'a949a8a2682665fc99f4e569c44aaff1.jpg'),(91,5,'097b9eec5b7c0ba137dd95f2bf0b5e8c.jpg'),(92,13,'51f230464f5b18ce18d364a703aabf8a.jpg'),(93,6,'791e07a89b5526c9793b51a855471f57.jpg'),(94,7,'383e8fa69d16004a3184226ecbf4ce05.jpg'),(95,8,'4bccf3c767ea0f215b815b899cc044ea.jpg'),(96,10,'d65b414f434e041e50a7a678faa6e967.jpg'),(97,11,'d5616f2f2f72ca067a2d038bb0a266de.jpg'),(98,39,'a768208756f08c1174ae2a901253e199.jpg'),(99,14,'2e1b63e30380b239b8c7372efd4553d3.jpg'),(100,15,'67a0354a38d81aac5d4304e969dd29b2.jpg'),(101,16,'4fcece40b39b89562e3d9aa79c4a44c6.jpg'),(102,17,'63312d1406751c2ee438e0a9e68e4525.jpg'),(103,18,'25ac51a5eb963c5e5af52a77616b69ae.jpg'),(104,19,'739c56f2c02b7baf9edb567e59a8296e.jpg'),(105,21,'bce783926f791eede5181450980b7143.jpg'),(106,20,'656abab26d7bcec1755bf8ad56c8698d.jpg'),(107,22,'7016011c151ea85e020cc6f5c72c8867.jpg'),(108,23,'44f9cc026cfc0ac7c2f74cda19ca3a1e.jpg'),(109,37,'40b93d23c373e822516a0bf080a9384a.jpg'),(110,38,'ad2bb0400eb67350eff23b1ea754efc8.jpg'),(111,24,'3b53489677d5d5883ea7c65114b9b078.png'),(112,26,'9df96d630bf544ace4edc4ae99b5b8a2.png'),(113,39,'633401650d2642912d3a9c7ad6fa9e29.jpg'),(114,30,'85d0ac939deaa42c12d5478001a97bb7.png'),(115,28,'d6190035fce05ecaeaed4dd2dbf381aa.png'),(116,29,'a43f2e02b38ac01d38acc8037ff2cc55.png'),(117,39,'b1c668fc6a0228893bd91d0c1d3b6b58.jpg'),(118,27,'bbd355bb654da1b1d797bd459830ee0f.png'),(119,9,'a3da45cdeecbad6bb8e43e45ae5e833b.jpg'),(120,12,'a0943ff5a740a736bf0920c0f61e93f0.jpg'),(121,4,'3569d7c26470caad11431c285d627184.jpg'),(122,5,'445c6846e248f07f443ed60ed2910663.jpg'),(123,13,'157c84710dea9e94b4dd8e2f25c09c05.jpg'),(124,6,'17a97f34b85bf27033e49be036a79dac.jpg'),(125,7,'2306e0ee13bea3a6ec82125d7513ac53.jpg'),(126,8,'4095c8f3c3e25e2337f6e4996601c0e0.jpg'),(127,10,'f5bf0620fbfcc1a5bd1e4b56e8aaf103.jpg'),(128,11,'4993cd07a1dad83182cc21e08023896f.jpg'),(129,26,'c0d0fa27c2da9aa3a851c194a8c35937.png'),(130,28,'e78c1673060f553995855e04046f78ce.png'),(131,27,'e6052d8745c72d49e0d8b386e93f8827.png'),(132,29,'445c6f0317538a7bb111cdd0261ae60a.png'),(133,39,'3332ce679cb815ffff42afc59a6baa5a.jpg'),(134,37,'207900b8043eedb81ce8a8b12bee59d3.jpg'),(135,38,'f9aca3d89a7cf2e3de68ac3eb08e52ca.jpg'),(136,30,'a5ccba0715831149e85d403c7528328d.png'),(137,39,'ffdb7747c66d5e06bb24a89f91a3915d.jpg'),(138,37,'91c02d6d38b462a8b86c95dcfd702b32.jpg'),(139,38,'c83b2b113996096c47776bf5bcff1bdc.jpg'),(140,39,'d5e4f688d6aaa9d89b47be7c06770618.jpg'),(141,37,'ffc51297b550e1303d974212fb105a06.jpg'),(142,38,'bf6e1f8098d3a4e06bc6502e71e8e02d.jpg'),(143,9,'f47ed5766cbde9a659dfb47df8f04b5f.jpg'),(144,12,'44b2701d0eef85fcff8a2124da434f68.jpg'),(145,4,'faafba10120527c52505a804db876cbc.jpg'),(146,5,'b80db1438e847cb4b5570198a85fd185.jpg'),(147,13,'86d6927e10dbb618e22844b5bd53d6bb.jpg'),(148,6,'b8a6389082656b892c9de28434e7039e.jpg'),(149,7,'7a995a1f6a36ac18b4deef40d95aaf7f.jpg'),(150,8,'1e7812bc8ce19ceb11d7cbeb330d7dbc.jpg'),(151,10,'f63055261e7a87ab77c46b0822099dd5.jpg'),(152,11,'8e473a885f6b55902e6d90c9deedd5e3.jpg'),(153,14,'6897bfa0a6f529ae65ebe14ac5b8d19a.jpg'),(154,15,'8400741b11095e807a7228b313eae11f.jpg'),(155,16,'ff6be68717f22f22c856214a23974358.jpg'),(156,17,'75c40a1bfb591537f9d7b4c91aa038d1.jpg'),(157,18,'c877a23f965fdd1f40ea9212847aebef.jpg'),(158,19,'fedde4ad016018c1f10ab5e27e22bdc9.jpg'),(159,21,'530196c47a617558e60ed25a13719b9b.jpg'),(160,20,'d088ec3e484b0d0aa89789a0002095dc.jpg'),(161,22,'a04f2537a7951a018c85bfe774bf7cac.jpg'),(162,23,'b109807fb7bf34a2cc005cef86acc600.jpg'),(171,16,'0177e5f4313759b3dc815e140d7c7499.jpg'),(172,17,'e892976ac6e78060e923612d6ce5ba68.jpg'),(173,18,'6feaa077f3b53c82e11c7ed813c04ee5.jpg'),(169,14,'fb4e4c5be86535473228fc3284167c28.jpg'),(170,15,'df6b1871e230fcfc4bf2eb0c2ecf44d4.jpg'),(174,19,'d31fcf7fba24e4bc8e530da04533c58b.jpg'),(175,21,'4b09df1b9ee83ede656e7fb8b8e1c41e.jpg'),(176,20,'0396033ed128ec34cea7409f811735dd.jpg'),(177,22,'b9d5daa9a97653656f737039255878f8.jpg'),(178,23,'095f8d979f22ea056d81d206fc1a5ebf.jpg');

/*Table structure for table `galerias` */

DROP TABLE IF EXISTS `galerias`;

CREATE TABLE `galerias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `galerias` */

insert  into `galerias`(`id`,`title`) values (1,'Fotos'),(2,'Wallpapers');

/*Table structure for table `personajes` */

DROP TABLE IF EXISTS `personajes`;

CREATE TABLE `personajes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idAutor` int(10) unsigned NOT NULL,
  `weight` int(11) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `latitud` varchar(150) DEFAULT NULL,
  `longitud` varchar(150) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `personajes` */

insert  into `personajes`(`id`,`idAutor`,`weight`,`title`,`direccion`,`latitud`,`longitud`,`descripcion`) values (1,1,0,'Mafalda','Av. Belgrano 200','-34.6123158','-58.369218899999964',''),(4,1,1,'Clemente','Av. Belgrano 300','-34.6124236','-58.370512599999984','Es un lindo personaje, le gustan las cosas simples.'),(5,1,2,'Catalina','','','','');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
