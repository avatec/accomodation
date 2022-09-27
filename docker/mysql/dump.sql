-- MariaDB dump 10.19  Distrib 10.9.2-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: localhost    Database: accomodation
-- ------------------------------------------------------
-- Server version	10.9.2-MariaDB-1:10.9.2+maria~ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acc_admins`
--

DROP TABLE IF EXISTS `acc_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_admins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `state` enum('FALSE','TRUE') NOT NULL,
  `create_date` datetime NOT NULL,
  `last_login_date` datetime DEFAULT NULL,
  `error_login_date` datetime DEFAULT NULL,
  `error_login_ip` varchar(30) DEFAULT NULL,
  `access` text DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_admins`
--

LOCK TABLES `acc_admins` WRITE;
/*!40000 ALTER TABLE `acc_admins` DISABLE KEYS */;
INSERT INTO `acc_admins` VALUES
(1,'admin','$2y$10$DTDSXRLvUr/9VKOezkqDmOjqqvw.paSRkiJrBJMcwtEqGHN73GHK.','Administrator','biuro@avatec.pl',1,'TRUE','2022-09-27 19:16:54',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `acc_admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_admins_tokens`
--

DROP TABLE IF EXISTS `acc_admins_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_admins_tokens` (
  `uid` int(11) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `token` varchar(128) NOT NULL,
  `expire` varchar(128) NOT NULL,
  KEY `uid` (`uid`),
  KEY `token` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_admins_tokens`
--

LOCK TABLES `acc_admins_tokens` WRITE;
/*!40000 ALTER TABLE `acc_admins_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_admins_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_advertising`
--

DROP TABLE IF EXISTS `acc_advertising`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_advertising` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `priority` int(11) unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `type` enum('TEXT','IMAGE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `place` enum('MAIN','OBJECT','SEARCH','BLOCK','PAGE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date DEFAULT NULL,
  `html` text COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `state` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `place` (`place`),
  KEY `state` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_advertising`
--

LOCK TABLES `acc_advertising` WRITE;
/*!40000 ALTER TABLE `acc_advertising` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_advertising` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_attractions`
--

DROP TABLE IF EXISTS `acc_attractions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_attractions` (
  `attractions_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `visibility` tinyint(1) unsigned NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `edit_date` datetime DEFAULT NULL,
  `photo` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`attractions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_attractions`
--

LOCK TABLES `acc_attractions` WRITE;
/*!40000 ALTER TABLE `acc_attractions` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_attractions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_attractions_category`
--

DROP TABLE IF EXISTS `acc_attractions_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_attractions_category` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `visibility` tinyint(1) unsigned NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_date` datetime DEFAULT NULL,
  `photo` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_attractions_category`
--

LOCK TABLES `acc_attractions_category` WRITE;
/*!40000 ALTER TABLE `acc_attractions_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_attractions_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_attractions_category_i18`
--

DROP TABLE IF EXISTS `acc_attractions_category_i18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_attractions_category_i18` (
  `language_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `language` varchar(4) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `meta_title` varchar(250) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_index` tinyint(1) NOT NULL,
  `meta_follow` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`),
  KEY `category_id` (`category_id`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_attractions_category_i18`
--

LOCK TABLES `acc_attractions_category_i18` WRITE;
/*!40000 ALTER TABLE `acc_attractions_category_i18` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_attractions_category_i18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_attractions_i18`
--

DROP TABLE IF EXISTS `acc_attractions_i18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_attractions_i18` (
  `language_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `attractions_id` int(11) unsigned NOT NULL,
  `language` varchar(4) NOT NULL,
  `name` varchar(200) NOT NULL,
  `address` varchar(250) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `city` varchar(250) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `www` varchar(250) DEFAULT NULL,
  `open_hours` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`language_id`),
  KEY `attractions_id` (`attractions_id`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_attractions_i18`
--

LOCK TABLES `acc_attractions_i18` WRITE;
/*!40000 ALTER TABLE `acc_attractions_i18` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_attractions_i18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_attractions_photos`
--

DROP TABLE IF EXISTS `acc_attractions_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_attractions_photos` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `attractions_id` int(11) NOT NULL,
  `visibility` tinyint(1) NOT NULL,
  `priority` int(11) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `create_date` date NOT NULL,
  `edit_date` date DEFAULT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_attractions_photos`
--

LOCK TABLES `acc_attractions_photos` WRITE;
/*!40000 ALTER TABLE `acc_attractions_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_attractions_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_attractions_photos_i18`
--

DROP TABLE IF EXISTS `acc_attractions_photos_i18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_attractions_photos_i18` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `language` varchar(10) NOT NULL,
  `alt` varchar(200) NOT NULL,
  PRIMARY KEY (`language_id`),
  KEY `attractions_id` (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_attractions_photos_i18`
--

LOCK TABLES `acc_attractions_photos_i18` WRITE;
/*!40000 ALTER TABLE `acc_attractions_photos_i18` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_attractions_photos_i18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_business_contrahent`
--

DROP TABLE IF EXISTS `acc_business_contrahent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_business_contrahent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `postcode` varchar(10) COLLATE utf8mb3_unicode_ci NOT NULL,
  `city` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `country` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `person_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `person_phone` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `person_email` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `pin` varchar(20) COLLATE utf8mb3_unicode_ci NOT NULL,
  `notice` text COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_business_contrahent`
--

LOCK TABLES `acc_business_contrahent` WRITE;
/*!40000 ALTER TABLE `acc_business_contrahent` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_business_contrahent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_business_invoice`
--

DROP TABLE IF EXISTS `acc_business_invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_business_invoice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `contrahent_id` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `sell_date` date NOT NULL,
  `payment_date` date NOT NULL,
  `payment_label` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `place` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `items` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `invoice_number` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `payment` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `payment_create_date` date DEFAULT NULL,
  `payment_amount` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `notice` text COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_business_invoice`
--

LOCK TABLES `acc_business_invoice` WRITE;
/*!40000 ALTER TABLE `acc_business_invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_business_invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_business_invoice_proforma`
--

DROP TABLE IF EXISTS `acc_business_invoice_proforma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_business_invoice_proforma` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `contrahent_id` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `sell_date` date NOT NULL,
  `payment_date` date NOT NULL,
  `payment_label` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `place` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `items` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `invoice_number` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `payment` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `payment_create_date` date DEFAULT NULL,
  `payment_amount` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `notice` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `cancel` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_business_invoice_proforma`
--

LOCK TABLES `acc_business_invoice_proforma` WRITE;
/*!40000 ALTER TABLE `acc_business_invoice_proforma` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_business_invoice_proforma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_cities`
--

DROP TABLE IF EXISTS `acc_cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_cities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `state_id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `photo` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_cities`
--

LOCK TABLES `acc_cities` WRITE;
/*!40000 ALTER TABLE `acc_cities` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_config`
--

DROP TABLE IF EXISTS `acc_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_config`
--

LOCK TABLES `acc_config` WRITE;
/*!40000 ALTER TABLE `acc_config` DISABLE KEYS */;
INSERT INTO `acc_config` VALUES
(1,'default_email',''),
(2,'service_name',''),
(3,'service_address',''),
(4,'service_postcode',''),
(5,'service_city',''),
(6,'service_pin',''),
(7,'service_regon',''),
(8,'service_phone_1',''),
(9,'service_phone_2',''),
(10,'service_fax',''),
(11,'social_facebook',''),
(12,'social_twitter',''),
(13,'social_linkedin',''),
(14,'social_youtube',''),
(15,'social_pinterest',''),
(16,'social_google_plus',''),
(17,'social_instagram',''),
(18,'facebook_app_id',''),
(19,'facebook_secret',''),
(20,'smtp','FALSE'),
(21,'smtp_auth','FALSE'),
(22,'smtp_ssl','FALSE'),
(23,'smtp_from',''),
(24,'smtp_email',''),
(25,'smtp_host',''),
(26,'smtp_username',''),
(27,'smtp_password',''),
(28,'smtp_port',''),
(29,'smtp_html','TRUE'),
(30,'service_blocked','FALSE'),
(31,'service_blocked_text','Przepraszamy, ale trwajÄ… prace administracyjne. Serwis niebawem powrÃ³ci.'),
(32,'google_stats',''),
(33,'google_tools',''),
(34,'google_api_key',''),
(35,'google_recaptcha_sitekey',''),
(36,'google_recaptcha_secretkey',''),
(37,'service_meta_title','Avatec Accomodation Skrypt Bazy Noclegowej'),
(38,'service_meta_description',''),
(39,'service_meta_keywords',''),
(40,'service_krs',''),
(41,'service_address_2',''),
(42,'service_postcode_2',''),
(43,'service_city_2',''),
(44,'service_fund',''),
(45,'bank_name',''),
(46,'bank_account',''),
(47,'announcement_photo_width','1680'),
(48,'announcement_photo_height','1280'),
(49,'announcement_photo_quality','90'),
(50,'announcement_thumb_width','768'),
(51,'announcement_thumb_height','768'),
(52,'announcement_max_photos','10'),
(53,'announcement_video','TRUE'),
(54,'announcement_max_videos','10'),
(55,'announcement_email','TRUE'),
(56,'announcement_create','TRUE'),
(57,'announcement_comments','TRUE'),
(58,'announcement_navigate','TRUE'),
(59,'announcement_moderate','FALSE'),
(60,'announcement_search_perpage','20'),
(61,'announcement_pay_as_view','FALSE'),
(62,'payments_module','dotpay'),
(63,'dotpay_id',''),
(64,'dotpay_pin',''),
(65,'dotpay_pinfo',''),
(66,'dotpay_pemail',''),
(67,'dotpay_ip',''),
(68,'dotpay_testmode','TRUE'),
(69,'payment_create_logs','TRUE'),
(70,'show_slider_main','TRUE'),
(71,'show_shortcuts_main','TRUE'),
(72,'show_partners_main','TRUE'),
(73,'show_special_main','TRUE'),
(74,'show_news_main','TRUE'),
(75,'vat','23.00'),
(76,'website_logo','logo.png'),
(77,'promoted_main_type','SLIDER'),
(78,'promoted_main_amount','6'),
(79,'newsletter_sender_name','Baza Noclegowa'),
(80,'newsletter_sender_email','newsletter@accomodation.local'),
(81,'newsletter_frequency','1'),
(82,'newsletter_popup','FALSE'),
(83,'p24_pos_id',''),
(84,'p24_order_key',''),
(85,'p24_crc_key',''),
(86,'p24_testmode','FALSE'),
(87,'p24_ip',''),
(88,'invoice_sign_name','ImiÄ™ i nazwisko osoby wystawiajÄ…cej fv'),
(89,'smsgateway_login',''),
(90,'smsgateway_password',''),
(91,'smsgateway_device_id',''),
(92,'admin_language','PL'),
(93,'announcement_region','FALSE'),
(94,'announcement_default_country_state','TRUE'),
(95,'announcement_default_country',''),
(96,'announcement_default_state_state','TRUE'),
(97,'announcement_default_state',''),
(98,'announcement_default_city_state','TRUE'),
(99,'announcement_default_city',''),
(100,'announcement_default_postcode_state','TRUE'),
(101,'announcement_default_postcode',''),
(102,'social_img',''),
(103,'rules_rodo_1','Zgadzam siÄ™ na przetwarzanie moich danych osobowych w celach marketingowych przez [nazwa-firmy]'),
(104,'rules_rodo_2','ChcÄ™ otrzymywaÄ‡ drogÄ… elektronicznÄ… informacje handlowe w rozumieniu ustawy z dnia 18 lipca 2002 r. o Å›wiadczeniu usÅ‚ug drogÄ… elektronicznÄ… (Dz. U. z 2013 r. poz 1422) pochodzÄ…ce od [nazwa-firmy]'),
(105,'rules_rodo_3','Zgadzam siÄ™ na wykorzystywanie telekomunikacyjnych urzÄ…dzeÅ„ koÅ„cowych i automatycznych systemÃ³w wywoÅ‚ujÄ…cych dla celÃ³w marketingu bezpoÅ›redniego w rozumieniu przepisÃ³w ustawy z dnia 16 lipca 2014 r. - Prawo telekomunikacyjne (Dz. U. 2014 poz. 243) pochodzÄ…cych od [nazwa-firmy]'),
(106,'rules_rodo_4','Zgadzam siÄ™ na udostÄ™pnienie moich danych osobowych w celach marketingowych podmiotom wspÃ³Å‚pracujÄ…cym z [nazwa-firmy]');
/*!40000 ALTER TABLE `acc_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_content_en`
--

DROP TABLE IF EXISTS `acc_content_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_content_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `section` text COLLATE utf8mb3_polish_ci DEFAULT NULL,
  `parent` int(11) unsigned NOT NULL,
  `priority` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb3_polish_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  `text` text COLLATE utf8mb3_polish_ci NOT NULL,
  `component` varchar(255) COLLATE utf8mb3_polish_ci DEFAULT NULL,
  `redirect` int(11) unsigned NOT NULL,
  `status` enum('TRUE','FALSE') COLLATE utf8mb3_polish_ci NOT NULL,
  `visibility` enum('TRUE','FALSE') COLLATE utf8mb3_polish_ci NOT NULL,
  `editable` enum('TRUE','FALSE') COLLATE utf8mb3_polish_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb3_polish_ci NOT NULL,
  `meta_keys` varchar(255) COLLATE utf8mb3_polish_ci NOT NULL,
  `meta_desc` varchar(255) COLLATE utf8mb3_polish_ci NOT NULL,
  `meta_index` enum('TRUE','FALSE') COLLATE utf8mb3_polish_ci NOT NULL,
  `meta_follow` enum('TRUE','FALSE') COLLATE utf8mb3_polish_ci NOT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8mb3_polish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `priority` (`priority`),
  KEY `rewrite` (`rewrite`),
  KEY `visibility` (`visibility`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_content_en`
--

LOCK TABLES `acc_content_en` WRITE;
/*!40000 ALTER TABLE `acc_content_en` DISABLE KEYS */;
INSERT INTO `acc_content_en` VALUES
(1,'1;2;',0,1,'Home','','home','',NULL,0,'FALSE','TRUE','FALSE','Start','','','TRUE','TRUE','TRUE'),
(2,'1;',0,2,'Accomodation','','noclegi','','objects/search',0,'FALSE','TRUE','FALSE','Search for best Accomodation','','','TRUE','TRUE','FALSE'),
(3,'1;2;',0,3,'Offer for the owners','','oferta','TreÅ›Ä‡ oferty dla wÅ‚aÅ›cicieli',NULL,0,'FALSE','TRUE','TRUE','Oferta','','','TRUE','TRUE','FALSE'),
(4,'1;2;',0,5,'Contact','','kontakt','','contact',0,'FALSE','TRUE','FALSE','Kontakt','','','TRUE','TRUE','FALSE'),
(5,'2;',0,6,'Privacy policy','','privacy-policy','',NULL,0,'FALSE','TRUE','TRUE','Privacy policy','','','TRUE','TRUE','FALSE'),
(6,'2;',0,4,'Rules','','regulamin','TreÅ›Ä‡ regulaminu',NULL,0,'FALSE','TRUE','TRUE','Regulamin','','','TRUE','FALSE','FALSE'),
(7,'1;',0,7,'News','','news','','news',0,'FALSE','TRUE','FALSE','News','','','TRUE','TRUE','FALSE');
/*!40000 ALTER TABLE `acc_content_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_content_pl`
--

DROP TABLE IF EXISTS `acc_content_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_content_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `section` text COLLATE utf8mb3_polish_ci DEFAULT NULL,
  `parent` int(11) unsigned NOT NULL,
  `priority` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb3_polish_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  `text` text COLLATE utf8mb3_polish_ci NOT NULL,
  `component` varchar(255) COLLATE utf8mb3_polish_ci DEFAULT NULL,
  `redirect` int(11) unsigned NOT NULL,
  `status` enum('TRUE','FALSE') COLLATE utf8mb3_polish_ci NOT NULL,
  `visibility` enum('TRUE','FALSE') COLLATE utf8mb3_polish_ci NOT NULL,
  `editable` enum('TRUE','FALSE') COLLATE utf8mb3_polish_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb3_polish_ci NOT NULL,
  `meta_keys` varchar(255) COLLATE utf8mb3_polish_ci NOT NULL,
  `meta_desc` varchar(255) COLLATE utf8mb3_polish_ci NOT NULL,
  `meta_index` enum('TRUE','FALSE') COLLATE utf8mb3_polish_ci NOT NULL,
  `meta_follow` enum('TRUE','FALSE') COLLATE utf8mb3_polish_ci NOT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8mb3_polish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `priority` (`priority`),
  KEY `rewrite` (`rewrite`),
  KEY `visibility` (`visibility`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_content_pl`
--

LOCK TABLES `acc_content_pl` WRITE;
/*!40000 ALTER TABLE `acc_content_pl` DISABLE KEYS */;
INSERT INTO `acc_content_pl` VALUES
(1,'1;2;',0,1,'Start','','start','',NULL,0,'FALSE','TRUE','FALSE','DEMO - Baza Noclegowa Skrypt PHP Avatec - Start','','','TRUE','TRUE','TRUE'),
(2,'1;',0,2,'Noclegi','','noclegi','','objects/search',0,'FALSE','TRUE','FALSE','DEMO - Baza Noclegowa Skrypt PHP Avatec - Noclegi','','','FALSE','FALSE','FALSE'),
(3,'1;2;',0,3,'Oferta dla wÅ‚aÅ›cicieli','','oferta','TreÅ›Ä‡ oferty dla wÅ‚aÅ›cicieli',NULL,0,'FALSE','TRUE','TRUE','DEMO - Baza Noclegowa Skrypt PHP Avatec - Oferta','','','TRUE','TRUE','FALSE'),
(4,'1;2;',0,6,'Kontakt','','kontakt','','contact',0,'FALSE','TRUE','FALSE','Kontakt','','','TRUE','TRUE','FALSE'),
(5,'2;',0,5,'Polityka prywatnoÅ›ci','','polityka-prywatnosci','',NULL,0,'FALSE','TRUE','TRUE','Polityka prywatnoÅ›ci','','','TRUE','TRUE','FALSE'),
(6,'2;',0,4,'Regulamin','','regulamin','TreÅ›Ä‡ regulaminu',NULL,0,'FALSE','TRUE','TRUE','DEMO - Baza Noclegowa Skrypt PHP Avatec - Regulamin','','','TRUE','TRUE','FALSE'),
(7,'1;',0,5,'AktualnoÅ›ci','','news','','news/list',0,'FALSE','TRUE','FALSE','AktualnoÅ›ci','','','TRUE','TRUE','FALSE');
/*!40000 ALTER TABLE `acc_content_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_content_sections_en`
--

DROP TABLE IF EXISTS `acc_content_sections_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_content_sections_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `priority` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_content_sections_en`
--

LOCK TABLES `acc_content_sections_en` WRITE;
/*!40000 ALTER TABLE `acc_content_sections_en` DISABLE KEYS */;
INSERT INTO `acc_content_sections_en` VALUES
(1,1,'Menu gÃ³rne','menu-gorne'),
(2,2,'Stopka','stopka');
/*!40000 ALTER TABLE `acc_content_sections_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_content_sections_pl`
--

DROP TABLE IF EXISTS `acc_content_sections_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_content_sections_pl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `priority` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_content_sections_pl`
--

LOCK TABLES `acc_content_sections_pl` WRITE;
/*!40000 ALTER TABLE `acc_content_sections_pl` DISABLE KEYS */;
INSERT INTO `acc_content_sections_pl` VALUES
(1,1,'Menu gÃ³rne','menu-gorne'),
(2,2,'Stopka','stopka');
/*!40000 ALTER TABLE `acc_content_sections_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_country_en`
--

DROP TABLE IF EXISTS `acc_country_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_country_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `code` varchar(3) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_country_en`
--

LOCK TABLES `acc_country_en` WRITE;
/*!40000 ALTER TABLE `acc_country_en` DISABLE KEYS */;
INSERT INTO `acc_country_en` VALUES
(1,'Afghanistan','afghanistan','AF'),
(2,'Albania','albania','AL'),
(3,'Algeria','algeria','DZ'),
(4,'Andorra','andorra','AD'),
(5,'Angola','angola','AO'),
(6,'Anguilla','anguilla','AI'),
(7,'Antarctica','antarctica','AQ'),
(8,'Antigua and Barbuda','antigua-and-barbuda','AG'),
(9,'Netherlands Antilles','netherlands-antilles','AN'),
(10,'Saudi Arabia','saudi-arabia','SA'),
(11,'Argentyna','argentyna','AR'),
(12,'Armenia','armenia','AM'),
(13,'Aruba','aruba','AW'),
(14,'Australia','australia','AU'),
(15,'Austria','austria','AT'),
(16,'Palestinian Authority','palestinian-authority','PS'),
(17,'Azerbaijan','azerbaijan','AZ'),
(18,'Bahamas','bahamas','BS'),
(19,'Bahrain','bahrain','BH'),
(20,'Bangladesh','bangladesh','BD'),
(21,'Barbados','barbados','BB'),
(22,'Belgium','belgium','BE'),
(23,'Belize','belize','BZ'),
(24,'Benin','benin','BJ'),
(25,'Bermudas','bermudas','BM'),
(26,'Bhutan','bhutan','BT'),
(27,'Belarus','belarus','BY'),
(28,'Burma','burma','MM'),
(29,'Bolivia','bolivia','BO'),
(30,'Bosnia and Herzegovina','bosnia-and-herzegovina','BA'),
(31,'Botswana','botswana','BW'),
(32,'Brazil','brazil','BR'),
(33,'Brunei','brunei','BN'),
(34,'British Indian Ocean Territory','british-indian-ocean-territory','IO'),
(35,'British Virgin Islands','british-virgin-islands','VG'),
(36,'Bulgaria','bulgaria','BG'),
(37,'Burkina Faso','burkina-faso','BF'),
(38,'Burundi','burundi','BI'),
(39,'Chile','chile','CL'),
(40,'China','china','CN'),
(41,'Croatia','croatia','HR'),
(42,'Cyprus (country)','cyprus-country','CY'),
(43,'Afterdamp','afterdamp','TD'),
(44,'Montenegro','montenegro','ME'),
(45,'Czech Republic','czech-republic','CZ'),
(46,'Minor Outlying Islands United States','minor-outlying-islands-united-states','UM'),
(47,'Denmark','denmark','DK'),
(48,'democratic republic of Kongo','democratic-republic-of-kongo','CD'),
(49,'Dominican Republic','dominican-republic','DO'),
(50,'Dominica (state)','dominica-state','DM'),
(51,'Djibouti','djibouti','DJ'),
(52,'Egypt','egypt','EG'),
(53,'Ecuador','ecuador','EC'),
(54,'Eritrea','eritrea','ER'),
(55,'Estonia','estonia','EE'),
(56,'Ethiopia','ethiopia','ET'),
(57,'Falkland Islands (territory)','falkland-islands-territory','FK'),
(58,'Fiji','fiji','FJ'),
(59,'Philippines','philippines','PH'),
(60,'Finland','finland','FI'),
(61,'France','france','FR'),
(62,'French Southern and Antarctic Territories','french-southern-and-antarctic-territories','TF'),
(63,'Gabon','gabon','GA'),
(64,'Gambia','gambia','GM'),
(65,'Ghana','ghana','GH'),
(66,'Gibraltar','gibraltar','GI'),
(67,'Greece','greece','GR'),
(68,'Grenada','grenada','GD'),
(69,'Greenland','greenland','GL'),
(70,'Georgia','georgia','GE'),
(71,'Guam','guam','GU'),
(72,'Guernsey','guernsey','GG'),
(73,'Guiana','guiana','GY'),
(74,'French Guiana','french-guiana','GF'),
(75,'Guadeloupe','guadeloupe','GP'),
(76,'Guatemala','guatemala','GT'),
(77,'Guinea','guinea','GN'),
(78,'Guinea-Bissau','guinea-bissau','GW'),
(79,'Equatorial Guinea','equatorial-guinea','GQ'),
(80,'Haiti (State)','haiti-state','HT'),
(81,'Spain','spain','ES'),
(82,'Netherlands','netherlands','NL'),
(83,'Honduras','honduras','HN'),
(84,'Hong Kong','hong-kong','HK'),
(85,'India','india','IN'),
(86,'Indonesia','indonesia','ID'),
(87,'Iraq','iraq','IQ'),
(88,'Iran','iran','IR'),
(89,'Ireland','ireland','IE'),
(90,'Iceland','iceland','IS'),
(91,'Israel','israel','IL'),
(92,'Jamaica','jamaica','JM'),
(93,'Jan Mayen (island)','jan-mayen-island','SJ'),
(94,'Japan','japan','JP'),
(95,'Yemen','yemen','YE'),
(96,'Jersey','jersey','JE'),
(97,'Jordan','jordan','JO'),
(98,'Cayman (Islands)','cayman-islands','KY'),
(99,'Cambodia','cambodia','KH'),
(100,'Cameroon','cameroon','CM'),
(101,'Canada','canada','CA'),
(102,'Runny nose','runny-nose','QA'),
(103,'Kazakhstan','kazakhstan','KZ'),
(104,'Kenya','kenya','KE'),
(105,'Kyrgyzstan','kyrgyzstan','KG'),
(106,'Kiribati','kiribati','KI'),
(107,'Columbia','columbia','CO'),
(108,'Comoros','comoros','KM'),
(109,'Kongo','kongo','CG'),
(110,'South Korea','south-korea','KR'),
(111,'North Korea','north-korea','KP'),
(112,'Costa Rica','costa-rica','CR'),
(113,'Cuba','cuba','CU'),
(114,'Kuwait','kuwait','KW'),
(115,'Laos','laos','LA'),
(116,'lesotho','lesotho','LS'),
(117,'Lebanon','lebanon','LB'),
(118,'Livery','livery','LR'),
(119,'Libya','libya','LY'),
(120,'Liechtenstein','liechtenstein','LI'),
(121,'Lithuania','lithuania','LT'),
(122,'Luxembourg','luxembourg','LU'),
(123,'Latvia','latvia','LV'),
(124,'Macedonia','macedonia','MK'),
(125,'Madagascar','madagascar','MG'),
(126,'Mayotte','mayotte','YT'),
(127,'Macau','macau','MO'),
(128,'Malawi','malawi','MW'),
(129,'Maldives','maldives','MV'),
(130,'Malaysia','malaysia','MY'),
(131,'Mali','mali','ML'),
(132,'Malta','malta','MT'),
(133,'Northern Mariana Islands','northern-mariana-islands','MP'),
(134,'Morocco','morocco','MA'),
(135,'Martinique','martinique','MQ'),
(136,'Mauretania','mauretania','MR'),
(137,'Mauritius','mauritius','MU'),
(138,'Mexico','mexico','MX'),
(139,'Micronesia (State)','micronesia-state','FM'),
(140,'Moldova','moldova','MD'),
(141,'Monaco','monaco','MC'),
(142,'Mongolia','mongolia','MN'),
(143,'Montserrat (Island)','montserrat-island','MS'),
(144,'Mozambique','mozambique','MZ'),
(145,'Namibia','namibia','NA'),
(146,'Nauru','nauru','NR'),
(147,'Nepal','nepal','NP'),
(148,'Germany','germany','DE'),
(149,'Nigeria','nigeria','NG'),
(150,'Niger','niger','NE'),
(151,'Nicaragua','nicaragua','NI'),
(152,'Niue','niue','NU'),
(153,'Norfolk (territory)','norfolk-territory','NF'),
(154,'Norway','norway','NO'),
(155,'New Caledonia','new-caledonia','NC'),
(156,'New Zealand','new-zealand','NZ'),
(157,'Oman (country)','oman-country','OM'),
(158,'Pakistan','pakistan','PK'),
(159,'Palau','palau','PW'),
(160,'Panama','panama','PA'),
(161,'Papua New Guinea','papua-new-guinea','PG'),
(162,'Paraguay','paraguay','PY'),
(163,'Peru','peru','PE'),
(164,'Pitcairn','pitcairn','PN'),
(165,'French Polynesia','french-polynesia','PF'),
(166,'Poland','poland','PL'),
(167,'Puerto Rico','puerto-rico','PR'),
(168,'Portugal','portugal','PT'),
(169,'Republic of China','republic-of-china','TW'),
(170,'South Africa','south-africa','ZA'),
(171,'Central African Republic','central-african-republic','CF'),
(172,'Cape Verde','cape-verde','CV'),
(173,'Reunion','reunion','RE'),
(174,'Russia','russia','RU'),
(175,'Romania','romania','RO'),
(176,'Rwanda','rwanda','RW'),
(177,'Western Sahara','western-sahara','EH'),
(178,'Saint-BarthÃ©lemy','saint-barth%C3%A9lemy','BL'),
(179,'Saint-Martin','saint-martin','MF'),
(180,'Saint Pierre and Miquelon','saint-pierre-and-miquelon','PM'),
(181,'Saint Kitts and Nevis','saint-kitts-and-nevis','KN'),
(182,'Saint Lucia','saint-lucia','LC'),
(183,'Saint Vincent and the Grenadines','saint-vincent-and-the-grenadines','VC'),
(184,'El Salvador','el-salvador','SV'),
(185,'Samoa','samoa','WS'),
(186,'American Samoa','american-samoa','AS'),
(187,'South Sandwich Islands','south-sandwich-islands','GS'),
(188,'San Marino','san-marino','SM'),
(189,'Senegal','senegal','SN'),
(190,'Serbia','serbia','RS'),
(191,'Seychelles','seychelles','SC'),
(192,'Sierra Leone','sierra-leone','SL'),
(193,'Singapore','singapore','SG'),
(194,'Slovakia','slovakia','SK'),
(195,'Slovenia','slovenia','SI'),
(196,'Somalia','somalia','SO'),
(197,'Sri Lanka','sri-lanka','LK'),
(198,'United States','united-states','US'),
(199,'Swaziland','swaziland','SZ'),
(200,'Sudan','sudan','SD'),
(201,'Suriname','suriname','SR'),
(202,'Syria','syria','SY'),
(203,'Switzerland','switzerland','CH'),
(204,'Sweden','sweden','SE'),
(205,'Saint Helena (colony)','saint-helena-colony','SH'),
(206,'Tajikistan','tajikistan','TJ'),
(207,'Thailand','thailand','TH'),
(208,'Tanzania','tanzania','TZ'),
(209,'East Timor','east-timor','TL'),
(210,'Togo','togo','TG'),
(211,'Tokelau','tokelau','TK'),
(212,'Tonga','tonga','TO'),
(213,'Trinidad and Tobago','trinidad-and-tobago','TT'),
(214,'Tunisia','tunisia','TN'),
(215,'Turkey','turkey','TR'),
(216,'Turkmenistan','turkmenistan','TM'),
(217,'Turks and Caicos','turks-and-caicos','TC'),
(218,'Tuvalu','tuvalu','TV'),
(219,'Uganda','uganda','UG'),
(220,'Ukraine','ukraine','UA'),
(221,'Uruguay','uruguay','UY'),
(222,'Uzbekistan','uzbekistan','UZ'),
(223,'Vanuatu','vanuatu','VU'),
(224,'Wallis and Futuna','wallis-and-futuna','WF'),
(225,'Vatican','vatican','VA'),
(226,'Venezuela','venezuela','VE'),
(227,'Hungary','hungary','HU'),
(228,'Great Britain','great-britain','GB'),
(229,'Vietnam','vietnam','VN'),
(230,'Italy','italy','IT'),
(231,'Ivory Coast','ivory-coast','CI'),
(232,'Bouvet Island','bouvet-island','BV'),
(233,'Christmas Island','christmas-island','CX'),
(234,'Isle of Man','isle-of-man','IM'),
(235,'Aland Islands','aland-islands','AX'),
(236,'Cook Islands','cook-islands','CK'),
(237,'US Virgin Islands','us-virgin-islands','VI'),
(238,'Heard Island and McDonald','heard-island-and-mcdonald','HM'),
(239,'Cocos Islands','cocos-islands','CC'),
(240,'Marshall Islands','marshall-islands','MH'),
(241,'Faroe Islands','faroe-islands','FO'),
(242,'Solomon Islands','solomon-islands','SB'),
(243,'Sao Tome and Principe','sao-tome-and-principe','ST'),
(244,'Zambia','zambia','ZM'),
(245,'Zimbabwe','zimbabwe','ZW'),
(246,'United Arab Emirates','united-arab-emirates','AE');
/*!40000 ALTER TABLE `acc_country_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_country_pl`
--

DROP TABLE IF EXISTS `acc_country_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_country_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  `code` varchar(5) COLLATE utf8mb3_polish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_country_pl`
--

LOCK TABLES `acc_country_pl` WRITE;
/*!40000 ALTER TABLE `acc_country_pl` DISABLE KEYS */;
INSERT INTO `acc_country_pl` VALUES
(1,'Afganistan','afganistan','AF'),
(2,'Albania','albania','AL'),
(3,'Algieria','algieria','DZ'),
(4,'Andora','andora','AD'),
(5,'Angola','angola','AO'),
(6,'Anguilla','anguilla','AI'),
(7,'Antarktyda','antarktyda','AQ'),
(8,'Antigua i Barbuda','antigua-i-barbuda','AG'),
(9,'Antyle Holenderskie','antyle-holenderskie','AN'),
(10,'Arabia Saudyjska','arabia-saudyjska','SA'),
(11,'Argentyna','argentyna','AR'),
(12,'Armenia','armenia','AM'),
(13,'Aruba','aruba','AW'),
(14,'Australia','australia','AU'),
(15,'Austria','austria','AT'),
(16,'Autonomia PalestyÅ„ska','autonomia-palestynska','PS'),
(17,'AzerbejdÅ¼an','azerbejdzan','AZ'),
(18,'Bahamy','bahamy','BS'),
(19,'Bahrajn','bahrajn','BH'),
(20,'Bangladesz','bangladesz','BD'),
(21,'Barbados','barbados','BB'),
(22,'Belgia','belgia','BE'),
(23,'Belize','belize','BZ'),
(24,'Benin','benin','BJ'),
(25,'Bermudy','bermudy','BM'),
(26,'Bhutan','bhutan','BT'),
(27,'BiaÅ‚oruÅ›','bialorus','BY'),
(28,'Birma','birma','MM'),
(29,'Boliwia','boliwia','BO'),
(30,'BoÅ›nia i Hercegowina','bosnia-i-hercegowina','BA'),
(31,'Botswana','botswana','BW'),
(32,'Brazylia','brazylia','BR'),
(33,'Brunei','brunei','BN'),
(34,'Brytyjskie Terytorium Oceanu Indyjskiego','brytyjskie-terytorium-oceanu-indyjskiego','IO'),
(35,'Brytyjskie Wyspy Dziewicze','brytyjskie-wyspy-dziewicze','VG'),
(36,'BuÅ‚garia','bulgaria','BG'),
(37,'Burkina Faso','burkina-faso','BF'),
(38,'Burundi','burundi','BI'),
(39,'Chile','chile','CL'),
(40,'ChiÅ„ska Republika Ludowa','chinska-republika-ludowa','CN'),
(41,'Chorwacja','chorwacja','HR'),
(42,'Cypr (paÅ„stwo)','cypr-panstwo','CY'),
(43,'Czad','czad','TD'),
(44,'CzarnogÃ³ra','czarnogora','ME'),
(45,'Czechy','czechy','CZ'),
(46,'Dalekie Wyspy Mniejsze StanÃ³w Zjednoczonych','dalekie-wyspy-mniejsze-stanow-zjednoczonych','UM'),
(47,'Dania','dania','DK'),
(48,'Demokratyczna Republika Konga','demokratyczna-republika-konga','CD'),
(49,'Dominikana','dominikana','DO'),
(50,'Dominika (paÅ„stwo)','dominika-panstwo','DM'),
(51,'DÅ¼ibuti','dzibuti','DJ'),
(52,'Egipt','egipt','EG'),
(53,'Ekwador','ekwador','EC'),
(54,'Erytrea','erytrea','ER'),
(55,'Estonia','estonia','EE'),
(56,'Etiopia','etiopia','ET'),
(57,'Falklandy (terytorium)','falklandy-terytorium','FK'),
(58,'FidÅ¼i','fidzi','FJ'),
(59,'Filipiny','filipiny','PH'),
(60,'Finlandia','finlandia','FI'),
(61,'Francja','francja','FR'),
(62,'Francuskie Terytoria PoÅ‚udniowe i Antarktyczne','francuskie-terytoria-poludniowe-i-antarktyczne','TF'),
(63,'Gabon','gabon','GA'),
(64,'Gambia','gambia','GM'),
(65,'Ghana','ghana','GH'),
(66,'Gibraltar','gibraltar','GI'),
(67,'Grecja','grecja','GR'),
(68,'Grenada','grenada','GD'),
(69,'Grenlandia','grenlandia','GL'),
(70,'Gruzja','gruzja','GE'),
(71,'Guam','guam','GU'),
(72,'Guernsey','guernsey','GG'),
(73,'Gujana','gujana','GY'),
(74,'Gujana Francuska','gujana-francuska','GF'),
(75,'Gwadelupa','gwadelupa','GP'),
(76,'Gwatemala','gwatemala','GT'),
(77,'Gwinea','gwinea','GN'),
(78,'Gwinea Bissau','gwinea-bissau','GW'),
(79,'Gwinea RÃ³wnikowa','gwinea-rownikowa','GQ'),
(80,'Haiti (paÅ„stwo)','haiti-panstwo','HT'),
(81,'Hiszpania','hiszpania','ES'),
(82,'Holandia','holandia','NL'),
(83,'Honduras','honduras','HN'),
(84,'Hongkong','hongkong','HK'),
(85,'Indie','indie','IN'),
(86,'Indonezja','indonezja','ID'),
(87,'Irak','irak','IQ'),
(88,'Iran','iran','IR'),
(89,'Irlandia','irlandia','IE'),
(90,'Islandia','islandia','IS'),
(91,'Izrael','izrael','IL'),
(92,'Jamajka','jamajka','JM'),
(93,'Jan Mayen (wyspa)','jan-mayen-wyspa','SJ'),
(94,'Japonia','japonia','JP'),
(95,'Jemen','jemen','YE'),
(96,'Jersey','jersey','JE'),
(97,'Jordania','jordania','JO'),
(98,'Kajmany (wyspy)','kajmany-wyspy','KY'),
(99,'KambodÅ¼a','kambodza','KH'),
(100,'Kamerun','kamerun','CM'),
(101,'Kanada','kanada','CA'),
(102,'Katar','katar','QA'),
(103,'Kazachstan','kazachstan','KZ'),
(104,'Kenia','kenia','KE'),
(105,'Kirgistan','kirgistan','KG'),
(106,'Kiribati','kiribati','KI'),
(107,'Kolumbia','kolumbia','CO'),
(108,'Komory','komory','KM'),
(109,'Kongo','kongo','CG'),
(110,'Korea PoÅ‚udniowa','korea-poludniowa','KR'),
(111,'Korea PÃ³Å‚nocna','korea-polnocna','KP'),
(112,'Kostaryka','kostaryka','CR'),
(113,'Kuba','kuba','CU'),
(114,'Kuwejt','kuwejt','KW'),
(115,'Laos','laos','LA'),
(116,'Lesotho','lesotho','LS'),
(117,'Liban','liban','LB'),
(118,'Liberia','liberia','LR'),
(119,'Libia','libia','LY'),
(120,'Liechtenstein','liechtenstein','LI'),
(121,'Litwa','litwa','LT'),
(122,'Luksemburg','luksemburg','LU'),
(123,'Åotwa','lotwa','LV'),
(124,'Macedonia','macedonia','MK'),
(125,'Madagaskar','madagaskar','MG'),
(126,'Majotta','majotta','YT'),
(127,'Makau','makau','MO'),
(128,'Malawi','malawi','MW'),
(129,'Malediwy','malediwy','MV'),
(130,'Malezja','malezja','MY'),
(131,'Mali','mali','ML'),
(132,'Malta','malta','MT'),
(133,'Mariany PÃ³Å‚nocne','mariany-polnocne','MP'),
(134,'Maroko','maroko','MA'),
(135,'Martynika','martynika','MQ'),
(136,'Mauretania','mauretania','MR'),
(137,'Mauritius','mauritius','MU'),
(138,'Meksyk','meksyk','MX'),
(139,'Mikronezja (paÅ„stwo)','mikronezja-panstwo','FM'),
(140,'MoÅ‚dawia','moldawia','MD'),
(141,'Monako','monako','MC'),
(142,'Mongolia','mongolia','MN'),
(143,'Montserrat (wyspa)','montserrat-wyspa','MS'),
(144,'Mozambik','mozambik','MZ'),
(145,'Namibia','namibia','NA'),
(146,'Nauru','nauru','NR'),
(147,'Nepal','nepal','NP'),
(148,'Niemcy','niemcy','DE'),
(149,'Nigeria','nigeria','NG'),
(150,'Niger','niger','NE'),
(151,'Nikaragua','nikaragua','NI'),
(152,'Niue','niue','NU'),
(153,'Norfolk (terytorium)','norfolk-terytorium','NF'),
(154,'Norwegia','norwegia','NO'),
(155,'Nowa Kaledonia','nowa-kaledonia','NC'),
(156,'Nowa Zelandia','nowa-zelandia','NZ'),
(157,'Oman (paÅ„stwo)','oman-panstwo','OM'),
(158,'Pakistan','pakistan','PK'),
(159,'Palau','palau','PW'),
(160,'Panama','panama','PA'),
(161,'Papua-Nowa Gwinea','papua-nowa-gwinea','PG'),
(162,'Paragwaj','paragwaj','PY'),
(163,'Peru','peru','PE'),
(164,'Pitcairn','pitcairn','PN'),
(165,'Polinezja Francuska','polinezja-francuska','PF'),
(166,'Polska','polska','PL'),
(167,'Portoryko','portoryko','PR'),
(168,'Portugalia','portugalia','PT'),
(169,'Republika ChiÅ„ska','republika-chinska','TW'),
(170,'Republika PoÅ‚udniowej Afryki','republika-poludniowej-afryki','ZA'),
(171,'Republika ÅšrodkowoafrykaÅ„ska','republika-srodkowoafrykanska','CF'),
(172,'Republika Zielonego PrzylÄ…dka','republika-zielonego-przyladka','CV'),
(173,'Reunion','reunion','RE'),
(174,'Rosja','rosja','RU'),
(175,'Rumunia','rumunia','RO'),
(176,'Rwanda','rwanda','RW'),
(177,'Sahara Zachodnia','sahara-zachodnia','EH'),
(178,'Saint-BarthÃ©lemy','saint-barth%C3%A9lemy','BL'),
(179,'Saint-Martin','saint-martin','MF'),
(180,'Saint-Pierre i Miquelon','saint-pierre-i-miquelon','PM'),
(181,'Saint Kitts i Nevis','saint-kitts-i-nevis','KN'),
(182,'Saint Lucia','saint-lucia','LC'),
(183,'Saint Vincent i Grenadyny','saint-vincent-i-grenadyny','VC'),
(184,'Salwador','salwador','SV'),
(185,'Samoa','samoa','WS'),
(186,'Samoa AmerykaÅ„skie','samoa-amerykanskie','AS'),
(187,'Sandwich PoÅ‚udniowy','sandwich-poludniowy','GS'),
(188,'San Marino','san-marino','SM'),
(189,'Senegal','senegal','SN'),
(190,'Serbia','serbia','RS'),
(191,'Seszele','seszele','SC'),
(192,'Sierra Leone','sierra-leone','SL'),
(193,'Singapur','singapur','SG'),
(194,'SÅ‚owacja','slowacja','SK'),
(195,'SÅ‚owenia','slowenia','SI'),
(196,'Somalia','somalia','SO'),
(197,'Sri Lanka','sri-lanka','LK'),
(198,'Stany Zjednoczone','stany-zjednoczone','US'),
(199,'Suazi','suazi','SZ'),
(200,'Sudan','sudan','SD'),
(201,'Surinam','surinam','SR'),
(202,'Syria','syria','SY'),
(203,'Szwajcaria','szwajcaria','CH'),
(204,'Szwecja','szwecja','SE'),
(205,'ÅšwiÄ™ta Helena (kolonia)','swieta-helena-kolonia','SH'),
(206,'TadÅ¼ykistan','tadzykistan','TJ'),
(207,'Tajlandia','tajlandia','TH'),
(208,'Tanzania','tanzania','TZ'),
(209,'Timor Wschodni','timor-wschodni','TL'),
(210,'Togo','togo','TG'),
(211,'Tokelau','tokelau','TK'),
(212,'Tonga','tonga','TO'),
(213,'Trynidad i Tobago','trynidad-i-tobago','TT'),
(214,'Tunezja','tunezja','TN'),
(215,'Turcja','turcja','TR'),
(216,'Turkmenistan','turkmenistan','TM'),
(217,'Turks i Caicos','turks-i-caicos','TC'),
(218,'Tuvalu','tuvalu','TV'),
(219,'Uganda','uganda','UG'),
(220,'Ukraina','ukraina','UA'),
(221,'Urugwaj','urugwaj','UY'),
(222,'Uzbekistan','uzbekistan','UZ'),
(223,'Vanuatu','vanuatu','VU'),
(224,'Wallis i Futuna','wallis-i-futuna','WF'),
(225,'Watykan','watykan','VA'),
(226,'Wenezuela','wenezuela','VE'),
(227,'WÄ™gry','wegry','HU'),
(228,'Wielka Brytania','wielka-brytania','GB'),
(229,'Wietnam','wietnam','VN'),
(230,'WÅ‚ochy','wlochy','IT'),
(231,'WybrzeÅ¼e KoÅ›ci SÅ‚oniowej','wybrzeze-kosci-sloniowej','CI'),
(232,'Wyspa Bouveta','wyspa-bouveta','BV'),
(233,'Wyspa BoÅ¼ego Narodzenia','wyspa-bozego-narodzenia','CX'),
(234,'Wyspa Man','wyspa-man','IM'),
(235,'Wyspy Alandzkie','wyspy-alandzkie','AX'),
(236,'Wyspy Cooka','wyspy-cooka','CK'),
(237,'Wyspy Dziewicze StanÃ³w Zjednoczonych','wyspy-dziewicze-stanow-zjednoczonych','VI'),
(238,'Wyspy Heard i McDonalda','wyspy-heard-i-mcdonalda','HM'),
(239,'Wyspy Kokosowe','wyspy-kokosowe','CC'),
(240,'Wyspy Marshalla','wyspy-marshalla','MH'),
(241,'Wyspy Owcze','wyspy-owcze','FO'),
(242,'Wyspy Salomona','wyspy-salomona','SB'),
(243,'Wyspy ÅšwiÄ™tego Tomasza i KsiÄ…Å¼Ä™ca','wyspy-swietego-tomasza-i-ksiazeca','ST'),
(244,'Zambia','zambia','ZM'),
(245,'Zimbabwe','zimbabwe','ZW'),
(246,'Zjednoczone Emiraty Arabskie','zjednoczone-emiraty-arabskie','AE');
/*!40000 ALTER TABLE `acc_country_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_distance_en`
--

DROP TABLE IF EXISTS `acc_distance_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_distance_en` (
  `id` tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_distance_en`
--

LOCK TABLES `acc_distance_en` WRITE;
/*!40000 ALTER TABLE `acc_distance_en` DISABLE KEYS */;
INSERT INTO `acc_distance_en` VALUES
(1,'Beach','beach'),
(2,'Lake','lake'),
(3,'Post office','post-office'),
(4,'Shop','shop'),
(5,'Centrum','centrum'),
(6,'Mountain trails','mountain-trails'),
(7,'Bus STOP','bus-stop'),
(8,'Train station','train-station'),
(9,'TAXI','taxi'),
(10,'Forest','forest'),
(11,'Restaurant','restaurant'),
(12,'Disco','disco');
/*!40000 ALTER TABLE `acc_distance_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_distance_pl`
--

DROP TABLE IF EXISTS `acc_distance_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_distance_pl` (
  `id` tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_distance_pl`
--

LOCK TABLES `acc_distance_pl` WRITE;
/*!40000 ALTER TABLE `acc_distance_pl` DISABLE KEYS */;
INSERT INTO `acc_distance_pl` VALUES
(1,'PlaÅ¼a','plaza'),
(2,'Jezioro','jezioro'),
(3,'Poczta','poczta'),
(4,'Sklep','sklep'),
(5,'Centrum','centrum'),
(6,'Szlaki gÃ³rskie','szlaki-gorskie'),
(7,'Przystanek autobusowy','przystanek-autobusowy'),
(8,'Stacja kolejowa','stacja-kolejowa'),
(9,'PostÃ³j TAXI','postoj-taxi'),
(10,'Las','las'),
(11,'Restauracja','restauracja'),
(12,'Dyskoteka','dyskoteka');
/*!40000 ALTER TABLE `acc_distance_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_dotpay_sms`
--

DROP TABLE IF EXISTS `acc_dotpay_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_dotpay_sms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT 0,
  `object_id` int(11) unsigned NOT NULL,
  `special_id` int(11) unsigned DEFAULT NULL,
  `pid` int(11) unsigned NOT NULL,
  `create_date` datetime NOT NULL,
  `text` varchar(100) COLLATE utf8mb3_polish_ci NOT NULL,
  `valid` enum('TRUE','FALSE') COLLATE utf8mb3_polish_ci NOT NULL,
  `expire_date` datetime DEFAULT NULL,
  `code` varchar(100) COLLATE utf8mb3_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_dotpay_sms`
--

LOCK TABLES `acc_dotpay_sms` WRITE;
/*!40000 ALTER TABLE `acc_dotpay_sms` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_dotpay_sms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_emails_en`
--

DROP TABLE IF EXISTS `acc_emails_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_emails_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  `value` longtext COLLATE utf8mb3_polish_ci NOT NULL,
  `description` longtext COLLATE utf8mb3_polish_ci NOT NULL,
  `type` enum('HTML','PLAIN') COLLATE utf8mb3_polish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_emails_en`
--

LOCK TABLES `acc_emails_en` WRITE;
/*!40000 ALTER TABLE `acc_emails_en` DISABLE KEYS */;
INSERT INTO `acc_emails_en` VALUES
(1,'user-password-change','Witaj [login],&amp;lt;br /&amp;gt;\n&amp;lt;br /&amp;gt;\nHasÅ‚o do Twojego konta zostaÅ‚o zmienione pomyÅ›lnie.&amp;lt;br /&amp;gt;\nOd teraz logowanie do panelu bÄ™dzie wymagaÅ‚o podania nowego adresu e-mail.\nNowe hasÅ‚o: [password]','TreÅ›Ä‡ wiadomoÅ›ci e-mail wysyÅ‚ana po zmianie hasÅ‚a do konta.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasÅ‚o do konta','HTML'),
(2,'user-new-account','Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nDziÄ™kujemy za zaÅ‚oÅ¼enie konta w naszym serwisie. Aby siÄ™ zalogowaÄ‡ prosimy najpierw kliknÄ…Ä‡ w poniÅ¼szy link w celu aktywacji konta:&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[activate-url]&amp;quot;&amp;gt;[activate-url]&amp;lt;/a&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nPo aktywowaniu konta logowanie bÄ™dzie aktywne. PoniÅ¼ej dane oraz link bezpoÅ›redni do strony logowania.&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Login:&amp;amp;nbsp;&amp;lt;/strong&amp;gt;[login]&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;HasÅ‚o:&amp;lt;/strong&amp;gt; [password]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Logowanie dostÄ™pne z adresu:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;login-url]&amp;quot;&amp;gt;[login-url]&amp;lt;/a&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;u&amp;gt;PamiÄ™taj, aby w ramach bezpieczeÅ„stwa&amp;amp;nbsp;zmieniÄ‡&amp;amp;nbsp;podane hasÅ‚o na nowe juÅ¼ przy pierwszym logowaniu !&amp;lt;/u&amp;gt;','TreÅ›Ä‡ wiadomoÅ›ci e-mail wysyÅ‚ana po utworzeniu nowego konta uÅ¼ytkownika przez formularz rejestracji na stronie&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasÅ‚o do konta&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[activate-url]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia link aktywacyjny&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia link do logowania do panelu','HTML'),
(3,'request-photos','&amp;lt;p&amp;gt;Witaj,&amp;lt;br /&amp;gt;\r\nUÅ¼ytkownik naszego serwisu prosi Ciebie o dodanie zdjÄ™Ä‡ do oferty:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[object-link]&amp;quot;&amp;gt;[object-link]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;PamiÄ™taj, Å¼e oferta jest skuteczniejsza jeÅ¼eli zawiera realne zdjÄ™cia co znaczÄ…co zwiÄ™ksza iloÅ›Ä‡ potencjalnych klientÃ³w&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;h5&amp;gt;Jak dodaÄ‡ zdjÄ™cia&amp;lt;/h5&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Aby dodaÄ‡ zdjÄ™cia, zaloguj siÄ™ na swoim profilu w naszym serwisie podajÄ…c swÃ³j login i hasÅ‚o, a nastÄ™pnie przy wybranej ofercie kliknij na przycisk zdjÄ™cia.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Logowanie do serwisu: &amp;lt;a href=&amp;quot;[login-url]&amp;quot;&amp;gt;[login-url]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Pozdrawiamy&amp;lt;/p&amp;gt;\r\n','TreÅ›Ä‡ wysyÅ‚ana gdy ktoÅ› kliknie na stronie obiektu przycisk&amp;amp;nbsp;&amp;lt;strong&amp;gt;poproÅ› o dodanie zdjÄ™Ä‡.&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-name]&amp;lt;/strong&amp;gt; - wstawia nazwÄ™ obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-link]&amp;lt;/strong&amp;gt; - wstawia link do obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;lt;/strong&amp;gt; - wstaiwa adres url do zalogowania siÄ™','HTML'),
(4,'object-contact-msg','&amp;lt;p&amp;gt;Witaj,&amp;lt;br /&amp;gt;\r\nZostaÅ‚o wysÅ‚ane do Ciebie zapytanie dotyczÄ…ce Twojej oferty:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[object-link]&amp;quot;&amp;gt;[object-link]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;table&amp;gt;\r\n	&amp;lt;tbody&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Nadawca:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[name]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Adres e-mail:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[email]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Telefon:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[phone]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n	&amp;lt;/tbody&amp;gt;\r\n&amp;lt;/table&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;&amp;lt;b&amp;gt;TreÅ›Ä‡ wiadomoÅ›ci:&amp;lt;/b&amp;gt;&amp;lt;br /&amp;gt;\r\n[text]&amp;lt;/p&amp;gt;\r\n','TreÅ›Ä‡ wiadomoÅ›ci wysyÅ‚ana po uzupeÅ‚nieniu formularza kontaktowego obiektu.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia nazwÄ™ obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-link]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia link do obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[name]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia imiÄ™ i nazwisko nadawcy podane w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia adres e-mail nadawcy podany w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[phone]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia numer telefonu podany w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[text]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wprowadza treÅ›Ä‡ podanÄ… w formularzu','HTML'),
(5,'admin-new-account','Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nAdministrator utworzyÅ‚ nowe konto&amp;amp;nbsp;na ktÃ³re moÅ¼esz siÄ™ zalogowaÄ‡ uÅ¼ywajÄ…c poniÅ¼szych danych:&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Login:&amp;amp;nbsp;&amp;lt;/strong&amp;gt;[login]&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;HasÅ‚o:&amp;lt;/strong&amp;gt; [password]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Logowanie dostÄ™pne z adresu:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[login-url]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;u&amp;gt;PamiÄ™taj, aby w ramach bezpieczeÅ„stwa&amp;amp;nbsp;zmieniÄ‡&amp;amp;nbsp;podane hasÅ‚o na nowe juÅ¼ przy pierwszym logowaniu !&amp;lt;/u&amp;gt;','TreÅ›Ä‡ wiadomoÅ›ci e-mail wysyÅ‚ana po utworzeniu nowego konta uÅ¼ytkownika.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasÅ‚o do konta','HTML'),
(6,'admin-password-change','Witaj [login],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nHasÅ‚o do Twojego konta zostaÅ‚o zmienione pomyÅ›lnie.&amp;lt;br /&amp;gt;\r\nOd teraz logowanie do panelu bÄ™dzie wymagaÅ‚o podania nowego adresu e-mail.\r\nNowe hasÅ‚o: [password]','TreÅ›Ä‡ wiadomoÅ›ci e-mail wysyÅ‚ana po zmianie hasÅ‚a do konta.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasÅ‚o do konta','HTML'),
(7,'user-activated-account','Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nTwoje konto jest juÅ¼ aktywne. MoÅ¼esz siÄ™ zalogowaÄ‡, podajÄ…c swoje dane w formualrzu na stronie:&amp;lt;br /&amp;gt;\r\n[login-url]','TreÅ›Ä‡ wiadomoÅ›ci e-mail wysyÅ‚ana po aktywacji konta&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia adres do strony logowania uÅ¼ytkownika','HTML'),
(8,'daily-view-expire','&amp;lt;p&amp;gt;OdnÃ³w wyÅ›wietlanie obiektÃ³w w naszym serwisie:&amp;lt;/p&amp;gt;\r\n\r\n[objects]\r\n\r\n&amp;lt;br/&amp;gt;\r\n&amp;lt;p&amp;gt;Aby Twoje obiekty dalej wyÅ›wietlaÅ‚y siÄ™ w naszym serwisie - zaloguj siÄ™ na swoje konto, a nastÄ™pnie wybierz opcjÄ™ &amp;lt;b&amp;gt;wykup&amp;lt;/b&amp;gt; wyÅ›wietlanie.&amp;lt;br/&amp;gt;MoÅ¼esz siÄ™ zalogowaÄ‡ do systemu &amp;lt;a class=&amp;quot;btn&amp;quot; href=&amp;quot;[app_url]/panel/login&amp;quot;&amp;gt;przejdÅº do strony logowania&amp;lt;/a&amp;gt;','TreÅ›Ä‡ wiadomoÅ›c e-maili wysyÅ‚ana w przypadku wygasania waÅ¼noÅ›ci wyÅ›wietlania obiektu.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, ktÃ³rych informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu','HTML'),
(9,'daily-main-expire','<p>OdnÃ³w promocjÄ™ na stronie gÅ‚Ã³wnej w naszym serwisie:</p>\r\n\r\n[objects]\r\n\r\n<br/>\r\n<p>Aby Twoje obiekty dalej byÅ‚y promowane na stronie gÅ‚Ã³wnej w naszym serwisie - zaloguj siÄ™ na swoje konto, a nastÄ™pnie wybierz opcjÄ™ <b>wykup</b> promocjÄ™ na stronie gÅ‚Ã³wnej.<br/>MoÅ¼esz siÄ™ zalogowaÄ‡ do systemu <a class=\"btn\" href=\"[app_url]/panel/login\">przejdÅº do strony logowania</a></p>','TreÅ›Ä‡ wiadomoÅ›c e-maili wysyÅ‚ana w przypadku wygasania waÅ¼noÅ›ci promocji na stronie gÅ‚Ã³wnej wygasa.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, ktÃ³rych informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu','HTML'),
(10,'daily-search-expire','<p>OdnÃ³w wyrÃ³Å¼nienie w wynikach wyszukiwania w naszym serwisie:</p>\r\n\r\n[objects]\r\n\r\n<br/>\r\n<p>Aby Twoje obiekty dalej byÅ‚y wyrÃ³Å¼nione w wynikach wyszukiwania w naszym serwisie - zaloguj siÄ™ na swoje konto, a nastÄ™pnie wybierz opcjÄ™ <b>wykup</b> wyrÃ³Å¼nienie w wynikach wyszukiwania.<br/>MoÅ¼esz siÄ™ zalogowaÄ‡ do systemu <a class=\"btn\" href=\"[app_url]/panel/login\">przejdÅº do strony logowania</a></p>','TreÅ›Ä‡ wiadomoÅ›c e-maili wysyÅ‚ana w przypadku wygasania waÅ¼noÅ›ci wyrÃ³Å¼nienia w wynikach wyszukiwania wygasa.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, ktÃ³rych informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu','HTML');
/*!40000 ALTER TABLE `acc_emails_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_emails_pl`
--

DROP TABLE IF EXISTS `acc_emails_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_emails_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_polish_ci NOT NULL,
  `value` longtext COLLATE utf8mb3_polish_ci NOT NULL,
  `description` longtext COLLATE utf8mb3_polish_ci NOT NULL,
  `type` enum('HTML','PLAIN') COLLATE utf8mb3_polish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_emails_pl`
--

LOCK TABLES `acc_emails_pl` WRITE;
/*!40000 ALTER TABLE `acc_emails_pl` DISABLE KEYS */;
INSERT INTO `acc_emails_pl` VALUES
(1,'user-password-change','Witaj [login],&amp;lt;br /&amp;gt;\n&amp;lt;br /&amp;gt;\nHasÅ‚o do Twojego konta zostaÅ‚o zmienione pomyÅ›lnie.&amp;lt;br /&amp;gt;\nOd teraz logowanie do panelu bÄ™dzie wymagaÅ‚o podania nowego adresu e-mail.\nNowe hasÅ‚o: [password]','TreÅ›Ä‡ wiadomoÅ›ci e-mail wysyÅ‚ana po zmianie hasÅ‚a do konta.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasÅ‚o do konta','HTML'),
(2,'user-new-account','Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nDziÄ™kujemy za zaÅ‚oÅ¼enie konta w naszym serwisie. Aby siÄ™ zalogowaÄ‡ prosimy najpierw kliknÄ…Ä‡ w poniÅ¼szy link w celu aktywacji konta:&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[activate-url]&amp;quot;&amp;gt;[activate-url]&amp;lt;/a&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nPo aktywowaniu konta logowanie bÄ™dzie aktywne. PoniÅ¼ej dane oraz link bezpoÅ›redni do strony logowania.&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Login:&amp;amp;nbsp;&amp;lt;/strong&amp;gt;[login]&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;HasÅ‚o:&amp;lt;/strong&amp;gt; [password]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Logowanie dostÄ™pne z adresu:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[login-url]&amp;quot;&amp;gt;[login-url]&amp;lt;/a&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;u&amp;gt;PamiÄ™taj, aby w ramach bezpieczeÅ„stwa&amp;amp;nbsp;zmieniÄ‡&amp;amp;nbsp;podane hasÅ‚o na nowe juÅ¼ przy pierwszym logowaniu !&amp;lt;/u&amp;gt;','TreÅ›Ä‡ wiadomoÅ›ci e-mail wysyÅ‚ana po utworzeniu nowego konta uÅ¼ytkownika przez formularz rejestracji na stronie&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasÅ‚o do konta&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[activate-url]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia link aktywacyjny&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia link do logowania do panelu','HTML'),
(3,'request-photos','&amp;lt;p&amp;gt;Witaj,&amp;lt;br /&amp;gt;\r\nUÅ¼ytkownik naszego serwisu prosi Ciebie o dodanie zdjÄ™Ä‡ do oferty:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[object-link]&amp;quot;&amp;gt;[object-link]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;PamiÄ™taj, Å¼e oferta jest skuteczniejsza jeÅ¼eli zawiera realne zdjÄ™cia co znaczÄ…co zwiÄ™ksza iloÅ›Ä‡ potencjalnych klientÃ³w&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;h5&amp;gt;Jak dodaÄ‡ zdjÄ™cia&amp;lt;/h5&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Aby dodaÄ‡ zdjÄ™cia, zaloguj siÄ™ na swoim profilu w naszym serwisie podajÄ…c swÃ³j login i hasÅ‚o, a nastÄ™pnie przy wybranej ofercie kliknij na przycisk zdjÄ™cia.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Logowanie do serwisu: &amp;lt;a href=&amp;quot;[login-url]&amp;quot;&amp;gt;[login-url]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Pozdrawiamy&amp;lt;/p&amp;gt;\r\n','TreÅ›Ä‡ wysyÅ‚ana gdy ktoÅ› kliknie na stronie obiektu przycisk&amp;amp;nbsp;&amp;lt;strong&amp;gt;poproÅ› o dodanie zdjÄ™Ä‡.&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-name]&amp;lt;/strong&amp;gt; - wstawia nazwÄ™ obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-link]&amp;lt;/strong&amp;gt; - wstawia link do obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;lt;/strong&amp;gt; - wstaiwa adres url do zalogowania siÄ™','HTML'),
(4,'object-contact-msg','&amp;lt;p&amp;gt;Witaj,&amp;lt;br /&amp;gt;\r\nZostaÅ‚o wysÅ‚ane do Ciebie zapytanie dotyczÄ…ce Twojej oferty:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[object-link]&amp;quot;&amp;gt;[object-link]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;table&amp;gt;\r\n	&amp;lt;tbody&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Nadawca:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[name]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Adres e-mail:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[email]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Telefon:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[phone]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n	&amp;lt;/tbody&amp;gt;\r\n&amp;lt;/table&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;&amp;lt;b&amp;gt;TreÅ›Ä‡ wiadomoÅ›ci:&amp;lt;/b&amp;gt;&amp;lt;br /&amp;gt;\r\n[text]&amp;lt;/p&amp;gt;\r\n','TreÅ›Ä‡ wiadomoÅ›ci wysyÅ‚ana po uzupeÅ‚nieniu formularza kontaktowego obiektu.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia nazwÄ™ obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-link]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia link do obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[name]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia imiÄ™ i nazwisko nadawcy podane w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia adres e-mail nadawcy podany w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[phone]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia numer telefonu podany w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[text]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wprowadza treÅ›Ä‡ podanÄ… w formularzu','HTML'),
(5,'admin-new-account','Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nAdministrator utworzyÅ‚ nowe konto&amp;amp;nbsp;na ktÃ³re moÅ¼esz siÄ™ zalogowaÄ‡ uÅ¼ywajÄ…c poniÅ¼szych danych:&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Login:&amp;amp;nbsp;&amp;lt;/strong&amp;gt;[login]&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;HasÅ‚o:&amp;lt;/strong&amp;gt; [password]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Logowanie dostÄ™pne z adresu:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[login-url]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;u&amp;gt;PamiÄ™taj, aby w ramach bezpieczeÅ„stwa&amp;amp;nbsp;zmieniÄ‡&amp;amp;nbsp;podane hasÅ‚o na nowe juÅ¼ przy pierwszym logowaniu !&amp;lt;/u&amp;gt;','TreÅ›Ä‡ wiadomoÅ›ci e-mail wysyÅ‚ana po utworzeniu nowego konta uÅ¼ytkownika.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasÅ‚o do konta','HTML'),
(6,'admin-password-change','Witaj [login],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nHasÅ‚o do Twojego konta zostaÅ‚o zmienione pomyÅ›lnie.&amp;lt;br /&amp;gt;\r\nOd teraz logowanie do panelu bÄ™dzie wymagaÅ‚o podania nowego adresu e-mail.\r\nNowe hasÅ‚o: [password]','TreÅ›Ä‡ wiadomoÅ›ci e-mail wysyÅ‚ana po zmianie hasÅ‚a do konta.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail uÅ¼ytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasÅ‚o do konta','HTML'),
(7,'user-activated-account','Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nTwoje konto jest juÅ¼ aktywne. MoÅ¼esz siÄ™ zalogowaÄ‡, podajÄ…c swoje dane w formualrzu na stronie:&amp;lt;br /&amp;gt;\r\n[login-url]','TreÅ›Ä‡ wiadomoÅ›ci e-mail wysyÅ‚ana po aktywacji konta&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia adres do strony logowania uÅ¼ytkownika','HTML'),
(8,'daily-view-expire','&amp;lt;p&amp;gt;OdnÃ³w wyÅ›wietlanie obiektÃ³w w naszym serwisie:&amp;lt;/p&amp;gt;\r\n\r\n[objects]\r\n\r\n&amp;lt;br/&amp;gt;\r\n&amp;lt;p&amp;gt;Aby Twoje obiekty dalej wyÅ›wietlaÅ‚y siÄ™ w naszym serwisie - zaloguj siÄ™ na swoje konto, a nastÄ™pnie wybierz opcjÄ™ &amp;lt;b&amp;gt;wykup&amp;lt;/b&amp;gt; wyÅ›wietlanie.&amp;lt;br/&amp;gt;MoÅ¼esz siÄ™ zalogowaÄ‡ do systemu &amp;lt;a class=&amp;quot;btn&amp;quot; href=&amp;quot;[app_url]/panel/login&amp;quot;&amp;gt;przejdÅº do strony logowania&amp;lt;/a&amp;gt;','TreÅ›Ä‡ wiadomoÅ›c e-maili wysyÅ‚ana w przypadku wygasania waÅ¼noÅ›ci wyÅ›wietlania obiektu.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, ktÃ³rych informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu','HTML'),
(9,'daily-main-expire','<p>OdnÃ³w promocjÄ™ na stronie gÅ‚Ã³wnej w naszym serwisie:</p>\r\n\r\n[objects]\r\n\r\n<br/>\r\n<p>Aby Twoje obiekty dalej byÅ‚y promowane na stronie gÅ‚Ã³wnej w naszym serwisie - zaloguj siÄ™ na swoje konto, a nastÄ™pnie wybierz opcjÄ™ <b>wykup</b> promocjÄ™ na stronie gÅ‚Ã³wnej.<br/>MoÅ¼esz siÄ™ zalogowaÄ‡ do systemu <a class=\"btn\" href=\"[app_url]/panel/login\">przejdÅº do strony logowania</a></p>','TreÅ›Ä‡ wiadomoÅ›c e-maili wysyÅ‚ana w przypadku wygasania waÅ¼noÅ›ci promocji na stronie gÅ‚Ã³wnej wygasa.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, ktÃ³rych informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu','HTML'),
(10,'daily-search-expire','<p>OdnÃ³w wyrÃ³Å¼nienie w wynikach wyszukiwania w naszym serwisie:</p>\r\n\r\n[objects]\r\n\r\n<br/>\r\n<p>Aby Twoje obiekty dalej byÅ‚y wyrÃ³Å¼nione w wynikach wyszukiwania w naszym serwisie - zaloguj siÄ™ na swoje konto, a nastÄ™pnie wybierz opcjÄ™ <b>wykup</b> wyrÃ³Å¼nienie w wynikach wyszukiwania.<br/>MoÅ¼esz siÄ™ zalogowaÄ‡ do systemu <a class=\"btn\" href=\"[app_url]/panel/login\">przejdÅº do strony logowania</a></p>','TreÅ›Ä‡ wiadomoÅ›c e-maili wysyÅ‚ana w przypadku wygasania waÅ¼noÅ›ci wyrÃ³Å¼nienia w wynikach wyszukiwania wygasa.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, ktÃ³rych informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu','HTML');
/*!40000 ALTER TABLE `acc_emails_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_equipment_en`
--

DROP TABLE IF EXISTS `acc_equipment_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_equipment_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_equipment_en`
--

LOCK TABLES `acc_equipment_en` WRITE;
/*!40000 ALTER TABLE `acc_equipment_en` DISABLE KEYS */;
INSERT INTO `acc_equipment_en` VALUES
(1,'The kitchenette','the-kitchenette'),
(2,'Balcony','balcony'),
(3,'Teapot','teapot'),
(4,'Grill','grill'),
(5,'Fridge','fridge'),
(6,'Washer','washer'),
(7,'Iron','iron'),
(8,'TV','tv'),
(9,'Radio','radio'),
(10,'Internet WiFi','internet-wifi'),
(11,'Internet LAN','internet-lan');
/*!40000 ALTER TABLE `acc_equipment_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_equipment_pl`
--

DROP TABLE IF EXISTS `acc_equipment_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_equipment_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_equipment_pl`
--

LOCK TABLES `acc_equipment_pl` WRITE;
/*!40000 ALTER TABLE `acc_equipment_pl` DISABLE KEYS */;
INSERT INTO `acc_equipment_pl` VALUES
(1,'Aneks kuchenny','aneks-kuchenny'),
(2,'Balkon','balkon'),
(3,'Czajnik','czajnik'),
(4,'Grill','grill'),
(5,'LodÃ³wka','lodowka'),
(6,'Pralka','pralka'),
(7,'Å»elazko','zelazko'),
(8,'Telewizor','telewizor'),
(9,'Radio','radio'),
(10,'Internet WiFi','internet-wifi'),
(11,'Internet LAN','internet-lan');
/*!40000 ALTER TABLE `acc_equipment_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_improvement_en`
--

DROP TABLE IF EXISTS `acc_improvement_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_improvement_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_improvement_en`
--

LOCK TABLES `acc_improvement_en` WRITE;
/*!40000 ALTER TABLE `acc_improvement_en` DISABLE KEYS */;
INSERT INTO `acc_improvement_en` VALUES
(1,'Playground','playground'),
(2,'Arbor','arbor'),
(3,'Swing','swing'),
(4,'Pool','pool'),
(5,'Sauna','sauna'),
(6,'Jacuzzi','jacuzzi'),
(7,'SPA','spa'),
(8,'Restaurant','restaurant'),
(9,'Dining Room','dining-room'),
(10,'Tennis Court','tennis-court'),
(11,'Fire place','fire-place'),
(12,'Grill','grill'),
(13,'Parking','parking'),
(14,'Elevator','elevator'),
(15,'Bicycle Rental','bicycle-rental'),
(16,'Rental of beach equipment','rental-of-beach-equipment'),
(17,'Ski equipment hire','ski-equipment-hire');
/*!40000 ALTER TABLE `acc_improvement_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_improvement_pl`
--

DROP TABLE IF EXISTS `acc_improvement_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_improvement_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_improvement_pl`
--

LOCK TABLES `acc_improvement_pl` WRITE;
/*!40000 ALTER TABLE `acc_improvement_pl` DISABLE KEYS */;
INSERT INTO `acc_improvement_pl` VALUES
(1,'Plac zabaw dla dzieci','plac-zabaw-dla-dzieci'),
(2,'Altanka','altanka'),
(3,'HuÅ›tawka','hustawka'),
(4,'Basen','basen'),
(5,'Sauna','sauna'),
(6,'Jacuzzi','jacuzzi'),
(7,'SPA','spa'),
(8,'Restauracja','restauracja'),
(9,'Jadalnia','jadalnia'),
(10,'Kort tenisowy','kort-tenisowy'),
(11,'Miejsce na ognisko','miejsce-na-ognisko'),
(12,'Miejsce na grill','miejsce-na-grill'),
(13,'Parking','parking'),
(14,'Winda','winda'),
(15,'WypoÅ¼yczalnia rowerÃ³w','wypozyczalnia-rowerow'),
(16,'WypoÅ¼yczalnia sprzÄ™tu plaÅ¼owego','wypozyczalnia-sprzetu-plazowego'),
(17,'WypoÅ¼yczania sprzÄ™tu narciarskiego','wypozyczania-sprzetu-narciarskiego');
/*!40000 ALTER TABLE `acc_improvement_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_informant`
--

DROP TABLE IF EXISTS `acc_informant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_informant` (
  `informant_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `visibility` tinyint(1) unsigned NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `edit_date` datetime DEFAULT NULL,
  `photo` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`informant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_informant`
--

LOCK TABLES `acc_informant` WRITE;
/*!40000 ALTER TABLE `acc_informant` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_informant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_informant_category`
--

DROP TABLE IF EXISTS `acc_informant_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_informant_category` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `visibility` tinyint(1) unsigned NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_date` datetime DEFAULT NULL,
  `photo` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_informant_category`
--

LOCK TABLES `acc_informant_category` WRITE;
/*!40000 ALTER TABLE `acc_informant_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_informant_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_informant_category_i18`
--

DROP TABLE IF EXISTS `acc_informant_category_i18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_informant_category_i18` (
  `language_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `language` varchar(4) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `meta_title` varchar(250) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_index` tinyint(1) NOT NULL,
  `meta_follow` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`),
  KEY `category_id` (`category_id`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_informant_category_i18`
--

LOCK TABLES `acc_informant_category_i18` WRITE;
/*!40000 ALTER TABLE `acc_informant_category_i18` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_informant_category_i18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_informant_i18`
--

DROP TABLE IF EXISTS `acc_informant_i18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_informant_i18` (
  `language_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `informant_id` int(11) unsigned NOT NULL,
  `language` varchar(4) NOT NULL,
  `name` varchar(200) NOT NULL,
  `address` varchar(250) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `city` varchar(250) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `www` varchar(250) DEFAULT NULL,
  `open_hours` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`language_id`),
  KEY `informant_id` (`informant_id`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_informant_i18`
--

LOCK TABLES `acc_informant_i18` WRITE;
/*!40000 ALTER TABLE `acc_informant_i18` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_informant_i18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_languages_en`
--

DROP TABLE IF EXISTS `acc_languages_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_languages_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_languages_en`
--

LOCK TABLES `acc_languages_en` WRITE;
/*!40000 ALTER TABLE `acc_languages_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_languages_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_languages_pl`
--

DROP TABLE IF EXISTS `acc_languages_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_languages_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_languages_pl`
--

LOCK TABLES `acc_languages_pl` WRITE;
/*!40000 ALTER TABLE `acc_languages_pl` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_languages_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_locations_en`
--

DROP TABLE IF EXISTS `acc_locations_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_locations_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `show_main` enum('TRUE','FALSE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `show_main` (`show_main`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_locations_en`
--

LOCK TABLES `acc_locations_en` WRITE;
/*!40000 ALTER TABLE `acc_locations_en` DISABLE KEYS */;
INSERT INTO `acc_locations_en` VALUES
(1,'TRUE','Mountain','mountain','gory.jpg'),
(2,'TRUE','Forest','forest','las.jpg'),
(3,'TRUE','Lake','Lake','jezioro.jpg'),
(4,'TRUE','Sea','sea','morze.jpg'),
(5,'FALSE','Country','country',NULL),
(6,'FALSE','City','city',NULL),
(7,'FALSE','River','river',NULL);
/*!40000 ALTER TABLE `acc_locations_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_locations_pl`
--

DROP TABLE IF EXISTS `acc_locations_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_locations_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `show_main` enum('TRUE','FALSE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `show_main` (`show_main`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_locations_pl`
--

LOCK TABLES `acc_locations_pl` WRITE;
/*!40000 ALTER TABLE `acc_locations_pl` DISABLE KEYS */;
INSERT INTO `acc_locations_pl` VALUES
(1,'TRUE','W gÃ³rach','w-gorach','gory.jpg'),
(2,'TRUE','W lesie','w-lesie','las.jpg'),
(3,'TRUE','Nad jeziorem','nad-jeziorem','jezioro.jpg'),
(4,'TRUE','Nad morzem','nad-morzem','morze.jpg'),
(5,'FALSE','Na wsi','na-wsi',NULL),
(6,'FALSE','W mieÅ›cie','w-miescie',NULL),
(7,'FALSE','Nad rzekÄ…','nad-rzeka',NULL);
/*!40000 ALTER TABLE `acc_locations_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_messages`
--

DROP TABLE IF EXISTS `acc_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `send_date` date DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` enum('MANUAL','AUTOMATIC') COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` enum('PENDING','SENDING','FINISH') COLLATE utf8mb3_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  `last_edit_date` datetime DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `send_date` (`send_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Przygotowane mailingi';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_messages`
--

LOCK TABLES `acc_messages` WRITE;
/*!40000 ALTER TABLE `acc_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_messages_outbox`
--

DROP TABLE IF EXISTS `acc_messages_outbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_messages_outbox` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) unsigned NOT NULL,
  `email` varchar(250) COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` enum('PENDING','SENT','ERROR') COLLATE utf8mb3_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL,
  `sent_date` datetime DEFAULT NULL,
  `readed` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Skrzynka nadawcza';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_messages_outbox`
--

LOCK TABLES `acc_messages_outbox` WRITE;
/*!40000 ALTER TABLE `acc_messages_outbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_messages_outbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_news_category_en`
--

DROP TABLE IF EXISTS `acc_news_category_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_news_category_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `priority` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `name_rw` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priority` (`priority`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_news_category_en`
--

LOCK TABLES `acc_news_category_en` WRITE;
/*!40000 ALTER TABLE `acc_news_category_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_news_category_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_news_category_pl`
--

DROP TABLE IF EXISTS `acc_news_category_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_news_category_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `priority` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `name_rw` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priority` (`priority`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_news_category_pl`
--

LOCK TABLES `acc_news_category_pl` WRITE;
/*!40000 ALTER TABLE `acc_news_category_pl` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_news_category_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_news_en`
--

DROP TABLE IF EXISTS `acc_news_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_news_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `preface` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `category` int(11) unsigned NOT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_news_en`
--

LOCK TABLES `acc_news_en` WRITE;
/*!40000 ALTER TABLE `acc_news_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_news_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_news_gallery_en`
--

DROP TABLE IF EXISTS `acc_news_gallery_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_news_gallery_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(11) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_news_gallery_en`
--

LOCK TABLES `acc_news_gallery_en` WRITE;
/*!40000 ALTER TABLE `acc_news_gallery_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_news_gallery_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_news_gallery_pl`
--

DROP TABLE IF EXISTS `acc_news_gallery_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_news_gallery_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(11) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_news_gallery_pl`
--

LOCK TABLES `acc_news_gallery_pl` WRITE;
/*!40000 ALTER TABLE `acc_news_gallery_pl` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_news_gallery_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_news_pl`
--

DROP TABLE IF EXISTS `acc_news_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_news_pl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `preface` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `category` int(11) unsigned NOT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_news_pl`
--

LOCK TABLES `acc_news_pl` WRITE;
/*!40000 ALTER TABLE `acc_news_pl` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_news_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_newsletter_emails`
--

DROP TABLE IF EXISTS `acc_newsletter_emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_newsletter_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` enum('PENDING','CONFIRM') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'PENDING',
  `confirm_date` date DEFAULT NULL,
  `confirm_ip` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `source` enum('import','added') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'added',
  `create_date` date NOT NULL,
  UNIQUE KEY `ident` (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `source` (`source`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_newsletter_emails`
--

LOCK TABLES `acc_newsletter_emails` WRITE;
/*!40000 ALTER TABLE `acc_newsletter_emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_newsletter_emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_objects`
--

DROP TABLE IF EXISTS `acc_objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_objects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `long_description` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `postcode` varchar(10) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `city` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `city_rw` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `state` int(11) unsigned DEFAULT NULL,
  `country` int(11) unsigned DEFAULT NULL,
  `district` int(11) unsigned DEFAULT NULL,
  `type` int(11) unsigned NOT NULL,
  `location` int(11) unsigned NOT NULL,
  `distance` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `improvements` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `map_lat` varchar(40) COLLATE utf8mb3_unicode_ci NOT NULL,
  `map_lng` varchar(40) COLLATE utf8mb3_unicode_ci NOT NULL,
  `map_zoom` smallint(10) NOT NULL,
  `phone` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `www` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `view_expire` date DEFAULT NULL,
  `search_expire` date DEFAULT NULL,
  `main_expire` date DEFAULT NULL,
  `create_date` date NOT NULL,
  `update_date` date DEFAULT NULL,
  `status` enum('PENDING','DISABLED','ACTIVE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `plus` int(11) unsigned DEFAULT NULL,
  `minus` int(11) unsigned DEFAULT NULL,
  `booking` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `state` (`state`),
  KEY `country` (`country`),
  KEY `type` (`type`),
  KEY `view_expire` (`view_expire`),
  KEY `search_expire` (`search_expire`),
  KEY `main_expire` (`main_expire`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_objects`
--

LOCK TABLES `acc_objects` WRITE;
/*!40000 ALTER TABLE `acc_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_objects_comments`
--

DROP TABLE IF EXISTS `acc_objects_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_objects_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `text_corrected` text COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `create_date` date NOT NULL,
  `rank` smallint(1) NOT NULL,
  `status` enum('PENDING','ACTIVE','MARK-TO-DELETE','DISABLED') COLLATE utf8mb3_unicode_ci NOT NULL,
  `helpful` int(11) unsigned NOT NULL,
  `unhelpful` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_objects_comments`
--

LOCK TABLES `acc_objects_comments` WRITE;
/*!40000 ALTER TABLE `acc_objects_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_objects_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_objects_photos`
--

DROP TABLE IF EXISTS `acc_objects_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_objects_photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `priority` int(11) unsigned NOT NULL,
  `file` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `object_id` (`object_id`),
  KEY `main` (`main`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_objects_photos`
--

LOCK TABLES `acc_objects_photos` WRITE;
/*!40000 ALTER TABLE `acc_objects_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_objects_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_objects_videos`
--

DROP TABLE IF EXISTS `acc_objects_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_objects_videos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `link` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_objects_videos`
--

LOCK TABLES `acc_objects_videos` WRITE;
/*!40000 ALTER TABLE `acc_objects_videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_objects_videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_p24`
--

DROP TABLE IF EXISTS `acc_p24`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_p24` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `control` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `p24_merchant_id` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `p24_pos_id` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `p24_session_id` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `p24_amount` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `p24_currency` varchar(10) COLLATE utf8mb3_unicode_ci NOT NULL,
  `p24_order_id` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` enum('PENDING','CONFIRM') COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_p24`
--

LOCK TABLES `acc_p24` WRITE;
/*!40000 ALTER TABLE `acc_p24` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_p24` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_partner`
--

DROP TABLE IF EXISTS `acc_partner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_partner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `priority` int(11) unsigned NOT NULL,
  `photo` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_partner`
--

LOCK TABLES `acc_partner` WRITE;
/*!40000 ALTER TABLE `acc_partner` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_partner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_payment_history`
--

DROP TABLE IF EXISTS `acc_payment_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_payment_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `create_date` datetime NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `special_id` int(11) unsigned DEFAULT NULL,
  `promotion_id` int(11) unsigned NOT NULL,
  `type` enum('ONLINE','SMS') COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` enum('NEW','CONFIRM','REFUSED','CANCEL') COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `promotion_id` (`promotion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_payment_history`
--

LOCK TABLES `acc_payment_history` WRITE;
/*!40000 ALTER TABLE `acc_payment_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_payment_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_promotion`
--

DROP TABLE IF EXISTS `acc_promotion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_promotion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `what` enum('VIEW','SEARCH','MAIN','SPECIAL') COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `days` smallint(11) unsigned NOT NULL,
  `type` set('ONLINE','SMS') COLLATE utf8mb3_unicode_ci NOT NULL,
  `amount_online` double(10,2) NOT NULL,
  `amount_sms` double(10,2) DEFAULT NULL,
  `sms_number` mediumint(10) unsigned DEFAULT NULL,
  `sms_text` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `what` (`what`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_promotion`
--

LOCK TABLES `acc_promotion` WRITE;
/*!40000 ALTER TABLE `acc_promotion` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_promotion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_rooms`
--

DROP TABLE IF EXISTS `acc_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_rooms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `persons` varchar(10) COLLATE utf8mb3_unicode_ci NOT NULL,
  `amount` double(10,2) NOT NULL,
  `amount_type` tinyint(1) NOT NULL,
  `equipment` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_rooms`
--

LOCK TABLES `acc_rooms` WRITE;
/*!40000 ALTER TABLE `acc_rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_rooms_photos`
--

DROP TABLE IF EXISTS `acc_rooms_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_rooms_photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `room_id` int(11) unsigned NOT NULL,
  `priority` int(11) unsigned NOT NULL,
  `file` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `room_id` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_rooms_photos`
--

LOCK TABLES `acc_rooms_photos` WRITE;
/*!40000 ALTER TABLE `acc_rooms_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_rooms_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_slider`
--

DROP TABLE IF EXISTS `acc_slider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_slider` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `priority` int(11) unsigned NOT NULL,
  `photo` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  `display_start` date DEFAULT NULL,
  `display_end` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `priority` (`priority`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_slider`
--

LOCK TABLES `acc_slider` WRITE;
/*!40000 ALTER TABLE `acc_slider` DISABLE KEYS */;
INSERT INTO `acc_slider` VALUES
(1,'Zima','','',1,'1476383110.jpg','2016-10-02','2000-01-02','2000-03-21'),
(2,'Wiosna','','',2,'1476383137.jpg','2016-10-02','2000-03-21','2000-05-31'),
(3,'Lato','','',5,'1476383162.jpg','2016-10-02','2000-06-01','2000-09-30'),
(4,'JesieÅ„','','',6,'1476383199.jpg','2016-10-02','2000-10-01','2000-12-17');
/*!40000 ALTER TABLE `acc_slider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_special_en`
--

DROP TABLE IF EXISTS `acc_special_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_special_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `show_main` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_title` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_description` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_keywords` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_special_en`
--

LOCK TABLES `acc_special_en` WRITE;
/*!40000 ALTER TABLE `acc_special_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_special_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_special_order`
--

DROP TABLE IF EXISTS `acc_special_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_special_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `special_id` int(11) unsigned NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `create_date` date NOT NULL,
  `expire_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_special_order`
--

LOCK TABLES `acc_special_order` WRITE;
/*!40000 ALTER TABLE `acc_special_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_special_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_special_pl`
--

DROP TABLE IF EXISTS `acc_special_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_special_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `show_main` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_title` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_description` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_keywords` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_special_pl`
--

LOCK TABLES `acc_special_pl` WRITE;
/*!40000 ALTER TABLE `acc_special_pl` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_special_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_states_en`
--

DROP TABLE IF EXISTS `acc_states_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_states_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country` (`country`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_states_en`
--

LOCK TABLES `acc_states_en` WRITE;
/*!40000 ALTER TABLE `acc_states_en` DISABLE KEYS */;
INSERT INTO `acc_states_en` VALUES
(1,1,'DolnoÅ›lÄ…skie','dolnoslaskie'),
(2,1,'Kujawsko-Pomorskie','kujawsko-pomorskie'),
(3,1,'Lubelskie','lubelskie'),
(4,1,'Lubuskie','lubuskie'),
(5,1,'ÅÃ³dzkie','lodzkie'),
(6,1,'MaÅ‚opolskie','malopolskie'),
(7,1,'Mazowieckie','mazowieckie'),
(8,1,'Opolskie','opolskie'),
(9,1,'Podkarpackie','podkarpackie'),
(10,1,'Podlaskie','podlaskie'),
(11,1,'Pomorskie','pomorskie'),
(12,1,'ÅšlÄ…skie','slaskie'),
(13,1,'ÅšwiÄ™tokrzyskie','swietokrzyskie'),
(14,1,'WarmiÅ„sko-mazurskie','warminsko-mazurskie'),
(15,1,'Wielkopolskie','wielkopolskie'),
(16,1,'Zachodniopomorskie','zachodniopomorskie');
/*!40000 ALTER TABLE `acc_states_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_states_pl`
--

DROP TABLE IF EXISTS `acc_states_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_states_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country` (`country`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_states_pl`
--

LOCK TABLES `acc_states_pl` WRITE;
/*!40000 ALTER TABLE `acc_states_pl` DISABLE KEYS */;
INSERT INTO `acc_states_pl` VALUES
(1,1,'DolnoÅ›lÄ…skie','dolnoslaskie'),
(2,1,'Kujawsko-Pomorskie','kujawsko-pomorskie'),
(3,1,'Lubelskie','lubelskie'),
(4,1,'Lubuskie','lubuskie'),
(5,1,'ÅÃ³dzkie','lodzkie'),
(6,1,'MaÅ‚opolskie','malopolskie'),
(7,1,'Mazowieckie','mazowieckie'),
(8,1,'Opolskie','opolskie'),
(9,1,'Podkarpackie','podkarpackie'),
(10,1,'Podlaskie','podlaskie'),
(11,1,'Pomorskie','pomorskie'),
(12,1,'ÅšlÄ…skie','slaskie'),
(13,1,'ÅšwiÄ™tokrzyskie','swietokrzyskie'),
(14,1,'WarmiÅ„sko-mazurskie','warminsko-mazurskie'),
(15,1,'Wielkopolskie','wielkopolskie'),
(16,1,'Zachodniopomorskie','zachodniopomorskie');
/*!40000 ALTER TABLE `acc_states_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_texts_en`
--

DROP TABLE IF EXISTS `acc_texts_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_texts_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_texts_en`
--

LOCK TABLES `acc_texts_en` WRITE;
/*!40000 ALTER TABLE `acc_texts_en` DISABLE KEYS */;
INSERT INTO `acc_texts_en` VALUES
(1,'bottom-about','Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum.','TreÅ›Ä‡ wspomagajÄ…ca pozycjonowanie widoczna w stopce'),
(2,'comments-list','<p class=\"lead\">W tym miejscu wyÅ›wietlane sÄ… opinie dodane przez uÅ¼ytkownikÃ³w tego serwisu. Jako <b>uÅ¼ytkownik premium</b> masz moÅ¼liwoÅ›Ä‡ ich edycji, oraz oznaczania do usuniÄ™cia. Prosimy o korzystanie z przycisku <b>oznacz do usuniÄ™cia</b> z rozwagÄ…. O usuniÄ™ciu negatywnego komentarza <u>decyduje moderator serwisu</u>.</p>','TreÅ›Ä‡ widoczna na podglÄ…dzie komentarzy w panelu uÅ¼ytkownika'),
(3,'user-no-account','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget bibendum nisi, quis maximus urna. Fusce eget nulla euismod lectus faucibus consequat quis quis eros.','TreÅ›Ä‡ widoczna na stronie logowania pod nagÅ‚Ã³wkiem:&amp;amp;nbsp;&amp;lt;strong&amp;gt;Nie mam jeszcze konta&amp;lt;/strong&amp;gt;'),
(4,'user-password-reset','UzupeÅ‚nij poniÅ¼szy formularz, aby zmieniÄ‡ hasÅ‚o do swojego konta\n','TreÅ›Ä‡ widoczna na stronie odzyskiwania hasÅ‚a'),
(5,'user-register-personal-data-protection-1','&lt;p&gt;&lt;small&gt;UdostÄ™pniane dane sÄ… chronione zgodne z UstawÄ… o ochronie danych osobowych. [nazwa-firmy]. (z siedzibÄ… w [firma-adres]) jest administratorem bazy danych osobowych. UdostÄ™pniajÄ…cy ma prawo do wglÄ…du, zmiany i usuniÄ™cia danych osobowych z bazy [nazwa-firmy]. UdostÄ™pnianie danych jest dobrowolne.&lt;/small&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;small&gt;OÅ›wiadczam, Å¼e zostaÅ‚em poinformowany o przysÅ‚ugujÄ…cych mi prawach i wyraÅ¼am zgodÄ™ na przechowywanie i przetwarzanie w tym rÃ³wnieÅ¼ dla celÃ³w marketingowych, przez [nazwa-firmy]. lub inny podmiot zwiÄ…zany umowa z [nazwa-firmy]. moich danych osobowych.&lt;/small&gt;&lt;/p&gt;\r\n','TreÅ›Ä‡ widoczna na podstronie rejestracji nowego konta: Ochrona danych osobowych'),
(6,'user-register-succesfully','<p class=\"lead\">WysÅ‚aliÅ›my wiadomoÅ›Ä‡ e-mail na podany podczas rejestracji przez Ciebie adres e-mail. SprawdÅº skrzynkÄ™ i potwierdÅº autentycznoÅ›Ä‡ wprowadzonych danych klikajÄ…c w znajdujÄ…cy siÄ™ w wiadomoÅ›ci link aktywacyjny.<br/><br/><span class=\"text-danger\">W przypadku, jeÅ¼eli wiadomoÅ›Ä‡ nie dotarÅ‚a do Ciebie w czasie dÅ‚uÅ¼szym niÅ¼ 10 min, kliknij na opcjÄ™ zaloguj, wprowadÅº dane podane podczas rejestracji, a wiadomoÅ›Ä‡ e-mail aktywacyjny zostanie wysÅ‚any do Ciebie ponownie.</span></p>','TreÅ›Ä‡ widoczna po uzupeÅ‚nieniu formularza rejestracji konta'),
(7,'user-password-reset-succesfully','<h2 class=\"title\">HasÅ‚o zostaÅ‚o pomyÅ›lnie zmienione</h2>\r\n<p class=\"lead\">Na TwÃ³j adres e-mail wysÅ‚aliÅ›my wiadomoÅ›Ä‡ z nowym hasÅ‚em, dziÄ™ki ktÃ³remu moÅ¼esz zalogowaÄ‡ siÄ™ do tego serwisu.</span></p>','TreÅ›Ä‡ widoczna po uzupeÅ‚nieniu formularza odzyskiwania hasÅ‚a'),
(8,'newsletter-subscribe-confirm','<p>Your e-mail has been successfully confirmed</p>','TreÅ›Ä‡ widoczna na stronie po klikniÄ™ciu w link aktywacji newslettera'),
(9,'newsletter-subscribe-error','<p>E-mail has already been activated</p>','TreÅ›Ä‡ widoczna na stronie po klikniÄ™ciu w link aktywacji newslettera w momencie, gdy adres e-mail nie istnieje lub gdy zostaÅ‚ on wczeÅ›niej aktywowany.'),
(10,'newsletter-unsubscribe','<p>Your e-mail has been successfully unsubscribed from the newsletter</p>','TreÅ›Ä‡ widoczna na stronie po wypisaniu siÄ™ z newslettera'),
(11,'room-add-text','<p class=\"alert alert-warning\">If in a room the recommended number of people is for example 3, however, there is the possibility of accommodation of an additional person, please put such information in the room description.</p>','TreÅ›Ä‡ widoczna nad formularzem dodawania / edycji pokoju'),
(12,'user-register-personal-data-protection-2','&lt;p&gt;&lt;small&gt;UdostÄ™pniane dane sÄ… chronione zgodne z UstawÄ… o ochronie danych osobowych. [firma-adres] jest administratorem bazy danych osobowych. UdostÄ™pniajÄ…cy ma prawo do wglÄ…du, zmiany i usuniÄ™cia danych osobowych z bazy [nazwa firmy]. UdostÄ™pnianie danych jest dobrowolne.&lt;/small&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;small&gt;OÅ›wiadczam, Å¼e zostaÅ‚em poinformowany o przysÅ‚ugujÄ…cych mi prawach i wyraÅ¼am zgodÄ™ na przechowywanie i przetwarzanie w tym rÃ³wnieÅ¼ dla celÃ³w marketingowych, przez [nazwa-firmy]. lub inny podmiot zwiÄ…zany umowa z [nazwa-firmy]. moich danych osobowych.&lt;/small&gt;&lt;/p&gt;\r\n','TreÅ›Ä‡ widoczna na stronie rejestracji nowego konta pod zgodami. Z natury jest to reguÅ‚ka prawna.');
/*!40000 ALTER TABLE `acc_texts_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_texts_pl`
--

DROP TABLE IF EXISTS `acc_texts_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_texts_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_texts_pl`
--

LOCK TABLES `acc_texts_pl` WRITE;
/*!40000 ALTER TABLE `acc_texts_pl` DISABLE KEYS */;
INSERT INTO `acc_texts_pl` VALUES
(1,'bottom-about','Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum.','TreÅ›Ä‡ wspomagajÄ…ca pozycjonowanie widoczna w stopce'),
(2,'comments-list','<p class=\"lead\">W tym miejscu wyÅ›wietlane sÄ… opinie dodane przez uÅ¼ytkownikÃ³w tego serwisu. Jako <b>uÅ¼ytkownik premium</b> masz moÅ¼liwoÅ›Ä‡ ich edycji, oraz oznaczania do usuniÄ™cia. Prosimy o korzystanie z przycisku <b>oznacz do usuniÄ™cia</b> z rozwagÄ…. O usuniÄ™ciu negatywnego komentarza <u>decyduje moderator serwisu</u>.</p>','TreÅ›Ä‡ widoczna na podglÄ…dzie komentarzy w panelu uÅ¼ytkownika'),
(3,'user-no-account','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget bibendum nisi, quis maximus urna. Fusce eget nulla euismod lectus faucibus consequat quis quis eros.','TreÅ›Ä‡ widoczna na stronie logowania pod nagÅ‚Ã³wkiem:&amp;amp;nbsp;&amp;lt;strong&amp;gt;Nie mam jeszcze konta&amp;lt;/strong&amp;gt;'),
(4,'user-password-reset','UzupeÅ‚nij poniÅ¼szy formularz, aby zmieniÄ‡ hasÅ‚o do swojego konta\n','TreÅ›Ä‡ widoczna na stronie odzyskiwania hasÅ‚a'),
(5,'user-register-personal-data-protection','&lt;p&gt;&lt;small&gt;UdostÄ™pniane dane sÄ… chronione zgodne z UstawÄ… o ochronie danych osobowych. [nazwa-firmy]. (z siedzibÄ… w [firma adres]) jest administratorem bazy danych osobowych. UdostÄ™pniajÄ…cy ma prawo do wglÄ…du, zmiany i usuniÄ™cia danych osobowych z bazy [nazwa-firmy]. UdostÄ™pnianie danych jest dobrowolne.&lt;/small&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;small&gt;OÅ›wiadczam, Å¼e zostaÅ‚em poinformowany o przysÅ‚ugujÄ…cych mi prawach i wyraÅ¼am zgodÄ™ na przechowywanie i przetwarzanie w tym rÃ³wnieÅ¼ dla celÃ³w marketingowych, przez [nazwa-firmy]. lub inny podmiot zwiÄ…zany umowa z [nazwa-firmy]. moich danych osobowych.&lt;/small&gt;&lt;/p&gt;\r\n','TreÅ›Ä‡ widoczna na podstronie rejestracji nowego konta: Ochrona danych osobowych'),
(6,'user-register-succesfully','<p class=\"lead\">WysÅ‚aliÅ›my wiadomoÅ›Ä‡ e-mail na podany podczas rejestracji przez Ciebie adres e-mail. SprawdÅº skrzynkÄ™ i potwierdÅº autentycznoÅ›Ä‡ wprowadzonych danych klikajÄ…c w znajdujÄ…cy siÄ™ w wiadomoÅ›ci link aktywacyjny.<br/><br/><span class=\"text-danger\">W przypadku, jeÅ¼eli wiadomoÅ›Ä‡ nie dotarÅ‚a do Ciebie w czasie dÅ‚uÅ¼szym niÅ¼ 10 min, kliknij na opcjÄ™ zaloguj, wprowadÅº dane podane podczas rejestracji, a wiadomoÅ›Ä‡ e-mail aktywacyjny zostanie wysÅ‚any do Ciebie ponownie.</span></p>','TreÅ›Ä‡ widoczna po uzupeÅ‚nieniu formularza rejestracji konta'),
(7,'user-password-reset-succesfully','<h2 class=\"title\">HasÅ‚o zostaÅ‚o pomyÅ›lnie zmienione</h2>\r\n<p class=\"lead\">Na TwÃ³j adres e-mail wysÅ‚aliÅ›my wiadomoÅ›Ä‡ z nowym hasÅ‚em, dziÄ™ki ktÃ³remu moÅ¼esz zalogowaÄ‡ siÄ™ do tego serwisu.</span></p>','TreÅ›Ä‡ widoczna po uzupeÅ‚nieniu formularza odzyskiwania hasÅ‚a'),
(8,'newsletter-subscribe-confirm','<p>TwÃ³j adres e-mail zostaÅ‚ pomyÅ›lnie potwierdzony</p>','TreÅ›Ä‡ widoczna na stronie po klikniÄ™ciu w link aktywacji newslettera'),
(9,'newsletter-subscribe-error','<p>Adres e-mail zostaÅ‚ juÅ¼ wczeÅ›niej aktywowany</p>','TreÅ›Ä‡ widoczna na stronie po klikniÄ™ciu w link aktywacji newslettera w momencie, gdy adres e-mail nie istnieje lub gdy zostaÅ‚ on wczeÅ›niej aktywowany.'),
(10,'newsletter-unsubscribe','<p>TwÃ³j adres e-mail zostaÅ‚ pomyÅ›lnie wypisany z newslettera</p>','TreÅ›Ä‡ widoczna na stronie po wypisaniu siÄ™ z newslettera'),
(11,'room-add-text','<p class=\"alert alert-warning\">JeÅ¼eli w danym pokoju zalecana iloÅ›Ä‡ osÃ³b wynosi np. 3 jednak istnieje moÅ¼liwoÅ›Ä‡ zakwaterowania dodatkowej osoby, naleÅ¼y umieÅ›ciÄ‡ takÄ… informacjÄ™ w opisie pokoju..</p>','TreÅ›Ä‡ widoczna nad formularzem dodawania / edycji pokoju'),
(12,'user-register-personal-data-protection-2','&lt;p&gt;&lt;small&gt;UdostÄ™pniane dane sÄ… chronione zgodne z UstawÄ… o ochronie danych osobowych. [firma-adres] jest administratorem bazy danych osobowych. UdostÄ™pniajÄ…cy ma prawo do wglÄ…du, zmiany i usuniÄ™cia danych osobowych z bazy [nazwa firmy]. UdostÄ™pnianie danych jest dobrowolne.&lt;/small&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;small&gt;OÅ›wiadczam, Å¼e zostaÅ‚em poinformowany o przysÅ‚ugujÄ…cych mi prawach i wyraÅ¼am zgodÄ™ na przechowywanie i przetwarzanie w tym rÃ³wnieÅ¼ dla celÃ³w marketingowych, przez [nazwa-firmy]. lub inny podmiot zwiÄ…zany umowa z [nazwa-firmy]. moich danych osobowych.&lt;/small&gt;&lt;/p&gt;\r\n','TreÅ›Ä‡ widoczna na stronie rejestracji nowego konta pod zgodami. Z natury jest to reguÅ‚ka prawna.');
/*!40000 ALTER TABLE `acc_texts_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_texts_sms_en`
--

DROP TABLE IF EXISTS `acc_texts_sms_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_texts_sms_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_texts_sms_en`
--

LOCK TABLES `acc_texts_sms_en` WRITE;
/*!40000 ALTER TABLE `acc_texts_sms_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_texts_sms_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_texts_sms_pl`
--

DROP TABLE IF EXISTS `acc_texts_sms_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_texts_sms_pl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_texts_sms_pl`
--

LOCK TABLES `acc_texts_sms_pl` WRITE;
/*!40000 ALTER TABLE `acc_texts_sms_pl` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_texts_sms_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_types_en`
--

DROP TABLE IF EXISTS `acc_types_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_types_en` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_types_en`
--

LOCK TABLES `acc_types_en` WRITE;
/*!40000 ALTER TABLE `acc_types_en` DISABLE KEYS */;
INSERT INTO `acc_types_en` VALUES
(1,'Agritourism','agritourism'),
(2,'Apartment','apartment'),
(3,'Camping','camping'),
(4,'Cottage','cottage'),
(5,'Hotel','hotel'),
(6,'Private flat','private-flat'),
(7,'Motel','motel'),
(8,'Resort','resort'),
(9,'Guesthouse','guesthouse'),
(10,'Campsite','campsite'),
(11,'Sanatorium','sanatorium'),
(12,'Hostel','hostel'),
(13,'Villa','villa');
/*!40000 ALTER TABLE `acc_types_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_types_pl`
--

DROP TABLE IF EXISTS `acc_types_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_types_pl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rewrite` (`rewrite`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_types_pl`
--

LOCK TABLES `acc_types_pl` WRITE;
/*!40000 ALTER TABLE `acc_types_pl` DISABLE KEYS */;
INSERT INTO `acc_types_pl` VALUES
(1,'Agroturystyka','agroturystyka'),
(2,'Apartament','apartament'),
(3,'Camping','camping'),
(4,'Domek','domek'),
(5,'Hotel','hotel'),
(6,'Kwatera prywatna','kwatera-prywatna'),
(7,'Motel','motel'),
(8,'OÅ›rodek wczasowy','osrodek-wczasowy'),
(9,'Pensjonat','pensjonat'),
(10,'Pole namiotowe','pole-namiotowe'),
(11,'Sanatorium','sanatorium'),
(12,'Schronisko','schronisko'),
(13,'Willa','willa');
/*!40000 ALTER TABLE `acc_types_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acc_users`
--

DROP TABLE IF EXISTS `acc_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fb_id` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `login` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `pass` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb3_unicode_ci NOT NULL,
  `phone` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` enum('USER','MOD','ADMIN','SUPPORT') COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` enum('FALSE','TRUE') COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_type` enum('USER','OWNER','DEVELOPER','AGENCY','SELECT') COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_account` enum('PRIVATE','COMPANY') COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `company_pin` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bank_account` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `postcode` varchar(10) COLLATE utf8mb3_unicode_ci NOT NULL,
  `city` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rules` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_login_date` datetime DEFAULT NULL,
  `error_login_date` datetime DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL,
  `commission` float DEFAULT NULL,
  `access` text COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `icon` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`login`),
  KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_users`
--

LOCK TABLES `acc_users` WRITE;
/*!40000 ALTER TABLE `acc_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_system_languages`
--

DROP TABLE IF EXISTS `cms_system_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_system_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(200) NOT NULL,
  `code` varchar(5) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_system_languages`
--

LOCK TABLES `cms_system_languages` WRITE;
/*!40000 ALTER TABLE `cms_system_languages` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_system_languages` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-09-27 19:18:31
