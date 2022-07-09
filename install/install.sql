SET time_zone = "+00:00";

DROP TABLE IF EXISTS acc_advertising;
CREATE TABLE acc_advertising (
  `id` int(11) UNSIGNED NOT NULL,
  `priority` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` enum('TEXT','IMAGE') COLLATE utf8_unicode_ci NOT NULL,
  `place` enum('MAIN','OBJECT','SEARCH','BLOCK','PAGE') COLLATE utf8_unicode_ci NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date DEFAULT NULL,
  `html` text COLLATE utf8_unicode_ci,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` int(11) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_advertising ADD PRIMARY KEY (`id`), ADD KEY `type` (`type`), ADD KEY `place` (`place`), ADD KEY `state` (`state`);
ALTER TABLE acc_advertising MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS acc_cities;
CREATE TABLE `acc_cities` (
  `id` int(11) UNSIGNED NOT NULL,
  `state_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_cities ADD PRIMARY KEY (`id`), ADD KEY `state_id` (`state_id`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE acc_cities MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS acc_config;
CREATE TABLE acc_config (
  id int(11) UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  value text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_config ADD PRIMARY KEY (id);
ALTER TABLE acc_config MODIFY id int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO acc_config VALUES(null, 'default_email', '');
INSERT INTO acc_config VALUES(null, 'service_name', '');
INSERT INTO acc_config VALUES(null, 'service_address', '');
INSERT INTO acc_config VALUES(null, 'service_postcode', '');
INSERT INTO acc_config VALUES(null, 'service_city', '');
INSERT INTO acc_config VALUES(null, 'service_pin', '');
INSERT INTO acc_config VALUES(null, 'service_regon', '');
INSERT INTO acc_config VALUES(null, 'service_phone_1', '');
INSERT INTO acc_config VALUES(null, 'service_phone_2', '');
INSERT INTO acc_config VALUES(null, 'service_fax', '');
INSERT INTO acc_config VALUES(null, 'social_facebook', '');
INSERT INTO acc_config VALUES(null, 'social_twitter', '');
INSERT INTO acc_config VALUES(null, 'social_linkedin', '');
INSERT INTO acc_config VALUES(null, 'social_youtube', '');
INSERT INTO acc_config VALUES(null, 'social_pinterest', '');
INSERT INTO acc_config VALUES(null, 'social_google_plus', '');
INSERT INTO acc_config VALUES(null, 'social_instagram', '');
INSERT INTO acc_config VALUES(null, 'facebook_app_id', '');
INSERT INTO acc_config VALUES(null, 'facebook_secret', '');
INSERT INTO acc_config VALUES(null, 'smtp', 'FALSE');
INSERT INTO acc_config VALUES(null, 'smtp_auth', 'FALSE');
INSERT INTO acc_config VALUES(null, 'smtp_ssl', 'FALSE');
INSERT INTO acc_config VALUES(null, 'smtp_from', '');
INSERT INTO acc_config VALUES(null, 'smtp_email', '');
INSERT INTO acc_config VALUES(null, 'smtp_host', '');
INSERT INTO acc_config VALUES(null, 'smtp_username', '');
INSERT INTO acc_config VALUES(null, 'smtp_password', '');
INSERT INTO acc_config VALUES(null, 'smtp_port', '');
INSERT INTO acc_config VALUES(null, 'smtp_html', 'TRUE');
INSERT INTO acc_config VALUES(null, 'service_blocked', 'FALSE');
INSERT INTO acc_config VALUES(null, 'service_blocked_text', 'Przepraszamy, ale trwają prace administracyjne. Serwis niebawem powróci.');
INSERT INTO acc_config VALUES(null, 'google_stats', '');
INSERT INTO acc_config VALUES(null, 'google_tools', '');
INSERT INTO acc_config VALUES(null, 'google_api_key', '');
INSERT INTO acc_config VALUES(null, 'google_recaptcha_sitekey', '');
INSERT INTO acc_config VALUES(null, 'google_recaptcha_secretkey', '');
INSERT INTO acc_config VALUES(null, 'service_meta_title', 'Avatec Accomodation Skrypt Bazy Noclegowej');
INSERT INTO acc_config VALUES(null, 'service_meta_description', '');
INSERT INTO acc_config VALUES(null, 'service_meta_keywords', '');
INSERT INTO acc_config VALUES(null, 'service_krs', '');
INSERT INTO acc_config VALUES(null, 'service_address_2', '');
INSERT INTO acc_config VALUES(null, 'service_postcode_2', '');
INSERT INTO acc_config VALUES(null, 'service_city_2', '');
INSERT INTO acc_config VALUES(null, 'service_fund', '');
INSERT INTO acc_config VALUES(null, 'bank_name', '');
INSERT INTO acc_config VALUES(null, 'bank_account', '');
INSERT INTO acc_config VALUES(null, 'announcement_photo_width', '1680');
INSERT INTO acc_config VALUES(null, 'announcement_photo_height', '1280');
INSERT INTO acc_config VALUES(null, 'announcement_photo_quality', '90');
INSERT INTO acc_config VALUES(null, 'announcement_thumb_width', '768');
INSERT INTO acc_config VALUES(null, 'announcement_thumb_height', '768');
INSERT INTO acc_config VALUES(null, 'announcement_max_photos', '10');
INSERT INTO acc_config VALUES(null, 'announcement_video', 'TRUE');
INSERT INTO acc_config VALUES(null, 'announcement_max_videos', '10');
INSERT INTO acc_config VALUES(null, 'announcement_email', 'TRUE');
INSERT INTO acc_config VALUES(null, 'announcement_create', 'TRUE');
INSERT INTO acc_config VALUES(null, 'announcement_comments', 'TRUE');
INSERT INTO acc_config VALUES(null, 'announcement_navigate', 'TRUE');
INSERT INTO acc_config VALUES(null, 'announcement_moderate' , 'FALSE');
INSERT INTO acc_config VALUES(null, 'announcement_search_perpage' , '20');
INSERT INTO acc_config VALUES(null, 'announcement_pay_as_view', 'FALSE');
INSERT INTO acc_config VALUES(null, 'payments_module', 'dotpay');
INSERT INTO acc_config VALUES(null, 'dotpay_id', '');
INSERT INTO acc_config VALUES(null, 'dotpay_pin', '');
INSERT INTO acc_config VALUES(null, 'dotpay_pinfo', '');
INSERT INTO acc_config VALUES(null, 'dotpay_pemail', '');
INSERT INTO acc_config VALUES(null, 'dotpay_ip', '');
INSERT INTO acc_config VALUES(null, 'dotpay_testmode' , 'TRUE');
INSERT INTO acc_config VALUES(null, 'payment_create_logs', 'TRUE');
INSERT INTO acc_config VALUES(null, 'show_slider_main', 'TRUE');
INSERT INTO acc_config VALUES(null, 'show_shortcuts_main', 'TRUE');
INSERT INTO acc_config VALUES(null, 'show_partners_main', 'TRUE');
INSERT INTO acc_config VALUES(null, 'show_special_main', 'TRUE');
INSERT INTO acc_config VALUES(null, 'show_news_main', 'TRUE');
INSERT INTO acc_config VALUES(null, 'vat', '23.00');
INSERT INTO acc_config VALUES(null, 'website_logo', 'logo.png');
# INSERT INTO acc_config VALUES(null, 'rules_register', 'Udzielam zgody na otrzymywanie na podany przeze mnie adres email informacji handlowych, o których mowa w art. 10 ustawy z dn. 18.07.2002 o Świadczeniu usług drogą elektroniczną, przesyłanych przez [nazwa firmy] lub inne podmioty korzystające z bazy danych [nazwa firmy] na podstawie aktualnych umów.');
INSERT INTO acc_config VALUES(null, 'promoted_main_type', 'SLIDER');
INSERT INTO acc_config VALUES(null, 'promoted_main_amount', '6');
INSERT INTO acc_config VALUES(null, 'newsletter_sender_name' , 'Baza Noclegowa');
INSERT INTO acc_config VALUES(null, 'newsletter_sender_email' , 'newsletter@accomodation.local');
INSERT INTO acc_config VALUES(null, 'newsletter_frequency' , '1');
INSERT INTO acc_config VALUES(null, 'newsletter_popup' , 'FALSE');

INSERT INTO acc_config VALUES(null, 'p24_pos_id' , '');
INSERT INTO acc_config VALUES(null, 'p24_order_key' , '');
INSERT INTO acc_config VALUES(null, 'p24_crc_key' , '');
INSERT INTO acc_config VALUES(null, 'p24_testmode' , 'FALSE');
INSERT INTO acc_config VALUES(null, 'p24_ip' , '');
INSERT INTO acc_config VALUES(null, 'invoice_sign_name' , 'Imię i nazwisko osoby wystawiającej fv');
INSERT INTO acc_config VALUES(null, 'smsgateway_login' , '');
INSERT INTO acc_config VALUES(null, 'smsgateway_password' , '');
INSERT INTO acc_config VALUES(null, 'smsgateway_device_id' , '');
INSERT INTO acc_config VALUES(null, 'admin_language' , 'PL');
INSERT INTO acc_config VALUES(null, 'announcement_region', 'FALSE');
INSERT INTO acc_config VALUES(null, 'announcement_default_country_state', 'TRUE');
INSERT INTO acc_config VALUES(null, 'announcement_default_country', '');
INSERT INTO acc_config VALUES(null, 'announcement_default_state_state', 'TRUE');
INSERT INTO acc_config VALUES(null, 'announcement_default_state', '');
INSERT INTO acc_config VALUES(null, 'announcement_default_city_state', 'TRUE');
INSERT INTO acc_config VALUES(null, 'announcement_default_city', '');
INSERT INTO acc_config VALUES(null, 'announcement_default_postcode_state', 'TRUE');
INSERT INTO acc_config VALUES(null, 'announcement_default_postcode', '');
INSERT INTO acc_config VALUES(null, 'social_img', '');
INSERT INTO acc_config VALUES(null, 'rules_rodo_1', 'Zgadzam się na przetwarzanie moich danych osobowych w celach marketingowych przez [nazwa-firmy]');
INSERT INTO acc_config VALUES(null, 'rules_rodo_2', 'Chcę otrzymywać drogą elektroniczną informacje handlowe w rozumieniu ustawy z dnia 18 lipca 2002 r. o świadczeniu usług drogą elektroniczną (Dz. U. z 2013 r. poz 1422) pochodzące od [nazwa-firmy]');
INSERT INTO acc_config VALUES(null, 'rules_rodo_3', 'Zgadzam się na wykorzystywanie telekomunikacyjnych urządzeń końcowych i automatycznych systemów wywołujących dla celów marketingu bezpośredniego w rozumieniu przepisów ustawy z dnia 16 lipca 2014 r. - Prawo telekomunikacyjne (Dz. U. 2014 poz. 243) pochodzących od [nazwa-firmy]');
INSERT INTO acc_config VALUES(null, 'rules_rodo_4', 'Zgadzam się na udostępnienie moich danych osobowych w celach marketingowych podmiotom współpracującym z [nazwa-firmy]');

DROP TABLE IF EXISTS `acc_content_en`;
CREATE TABLE `acc_content_en` (
  `id` int(11) UNSIGNED NOT NULL,
  `section` text COLLATE utf8_polish_ci DEFAULT NULL,
  `parent` int(11) UNSIGNED NOT NULL,
  `priority` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `text` text COLLATE utf8_polish_ci NOT NULL,
  `component` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `redirect` int(11) UNSIGNED NOT NULL,
  `status` enum('TRUE','FALSE') COLLATE utf8_polish_ci NOT NULL,
  `visibility` enum('TRUE','FALSE') COLLATE utf8_polish_ci NOT NULL,
  `editable` enum('TRUE','FALSE') COLLATE utf8_polish_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `meta_keys` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `meta_desc` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `meta_index` enum('TRUE','FALSE') COLLATE utf8_polish_ci NOT NULL,
  `meta_follow` enum('TRUE','FALSE') COLLATE utf8_polish_ci NOT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8_polish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

ALTER TABLE `acc_content_en` ADD PRIMARY KEY (`id`), ADD KEY `parent` (`parent`), ADD KEY `priority` (`priority`), ADD KEY `rewrite` (`rewrite`), ADD KEY `visibility` (`visibility`);
ALTER TABLE `acc_content_en` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

INSERT INTO `acc_content_en` VALUES (1, '1;2;', 0, 1, 'Home', '', 'home', '', NULL, 0, 'FALSE', 'TRUE', 'FALSE', 'Start', '', '', 'TRUE', 'TRUE', 'TRUE');
INSERT INTO `acc_content_en` VALUES (2, '1;', 0, 2, 'Accomodation', '', 'noclegi', '', 'objects/search', 0, 'FALSE', 'TRUE', 'FALSE', 'Search for best Accomodation', '', '', 'TRUE', 'TRUE', 'FALSE');
INSERT INTO `acc_content_en` VALUES (3, '1;2;', 0, 3, 'Offer for the owners', '', 'oferta', 'Treść oferty dla właścicieli', NULL, 0, 'FALSE', 'TRUE', 'TRUE', 'Oferta', '', '', 'TRUE', 'TRUE', 'FALSE');
INSERT INTO `acc_content_en` VALUES (4, '1;2;', 0, 5, 'Contact', '', 'kontakt', '', 'contact', 0, 'FALSE', 'TRUE', 'FALSE', 'Kontakt', '', '', 'TRUE', 'TRUE', 'FALSE');
INSERT INTO `acc_content_en` VALUES (5, '2;', 0, 6, 'Privacy policy', '', 'privacy-policy', '', NULL, 0, 'FALSE', 'TRUE', 'TRUE', 'Privacy policy', '', '', 'TRUE', 'TRUE', 'FALSE');
INSERT INTO `acc_content_en` VALUES (6, '2;', 0, 4, 'Rules', '', 'regulamin', 'Treść regulaminu', NULL, 0, 'FALSE', 'TRUE', 'TRUE', 'Regulamin', '', '', 'TRUE', 'FALSE', 'FALSE');
INSERT INTO `acc_content_en` VALUES (7, '1;', 0, 7, 'News', '', 'news', '', 'news', 0, 'FALSE', 'TRUE', 'FALSE', 'News', '', '', 'TRUE', 'TRUE', 'FALSE');

DROP TABLE IF EXISTS `acc_content_pl`;
CREATE TABLE `acc_content_pl` (
  `id` int(11) UNSIGNED NOT NULL,
  `section` text COLLATE utf8_polish_ci DEFAULT NULL,
  `parent` int(11) UNSIGNED NOT NULL,
  `priority` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `text` text COLLATE utf8_polish_ci NOT NULL,
  `component` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `redirect` int(11) UNSIGNED NOT NULL,
  `status` enum('TRUE','FALSE') COLLATE utf8_polish_ci NOT NULL,
  `visibility` enum('TRUE','FALSE') COLLATE utf8_polish_ci NOT NULL,
  `editable` enum('TRUE','FALSE') COLLATE utf8_polish_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `meta_keys` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `meta_desc` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `meta_index` enum('TRUE','FALSE') COLLATE utf8_polish_ci NOT NULL,
  `meta_follow` enum('TRUE','FALSE') COLLATE utf8_polish_ci NOT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8_polish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

ALTER TABLE `acc_content_pl` ADD PRIMARY KEY (`id`), ADD KEY `parent` (`parent`), ADD KEY `priority` (`priority`), ADD KEY `rewrite` (`rewrite`), ADD KEY `visibility` (`visibility`);
ALTER TABLE `acc_content_pl` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

INSERT INTO `acc_content_pl` VALUES (1, '1;2;', 0, 1, 'Start', '', 'start', '', NULL, 0, 'FALSE', 'TRUE', 'FALSE', 'DEMO - Baza Noclegowa Skrypt PHP Avatec - Start', '', '', 'TRUE', 'TRUE', 'TRUE');
INSERT INTO `acc_content_pl` VALUES (2, '1;', 0, 2, 'Noclegi', '', 'noclegi', '', 'objects/search', 0, 'FALSE', 'TRUE', 'FALSE', 'DEMO - Baza Noclegowa Skrypt PHP Avatec - Noclegi', '', '', 'FALSE', 'FALSE', 'FALSE');
INSERT INTO `acc_content_pl` VALUES (3, '1;2;', 0, 3, 'Oferta dla właścicieli', '', 'oferta', 'Treść oferty dla właścicieli', NULL, 0, 'FALSE', 'TRUE', 'TRUE', 'DEMO - Baza Noclegowa Skrypt PHP Avatec - Oferta', '', '', 'TRUE', 'TRUE', 'FALSE');
INSERT INTO `acc_content_pl` VALUES (4, '1;2;', 0, 6, 'Kontakt', '', 'kontakt', '', 'contact', 0, 'FALSE', 'TRUE', 'FALSE', 'Kontakt', '', '', 'TRUE', 'TRUE', 'FALSE');
INSERT INTO `acc_content_pl` VALUES (5, '2;', 0, 5, 'Polityka prywatności', '', 'polityka-prywatnosci', '', NULL, 0, 'FALSE', 'TRUE', 'TRUE', 'Polityka prywatności', '', '', 'TRUE', 'TRUE', 'FALSE');
INSERT INTO `acc_content_pl` VALUES (6, '2;', 0, 4, 'Regulamin', '', 'regulamin', 'Treść regulaminu', NULL, 0, 'FALSE', 'TRUE', 'TRUE', 'DEMO - Baza Noclegowa Skrypt PHP Avatec - Regulamin', '', '', 'TRUE', 'TRUE', 'FALSE');
INSERT INTO `acc_content_pl` VALUES (7, '1;', 0, 5, 'Aktualności', '', 'news', '', 'news/list', 0, 'FALSE', 'TRUE', 'FALSE', 'Aktualności', '', '', 'TRUE', 'TRUE', 'FALSE');

DROP TABLE IF EXISTS `acc_content_sections_pl`;
CREATE TABLE `acc_content_sections_pl` (
  `id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `acc_content_sections_pl` VALUES(1, 1, 'Menu górne', 'menu-gorne');
INSERT INTO `acc_content_sections_pl` VALUES(2, 2, 'Stopka', 'stopka');

ALTER TABLE `acc_content_sections_pl` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_content_sections_pl` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

DROP TABLE IF EXISTS `acc_content_sections_en`;
CREATE TABLE `acc_content_sections_en` (
  `id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `acc_content_sections_en` VALUES(1, 1, 'Menu górne', 'menu-gorne');
INSERT INTO `acc_content_sections_en` VALUES(2, 2, 'Stopka', 'stopka');

ALTER TABLE `acc_content_sections_en` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_content_sections_en` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

DROP TABLE IF EXISTS `acc_country_en`;
CREATE TABLE `acc_country_en` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(3) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_country_en` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_country_en` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

INSERT INTO acc_country_en VALUES(1, 'Afghanistan', 'afghanistan', 'AF');
INSERT INTO acc_country_en VALUES(2, 'Albania', 'albania', 'AL');
INSERT INTO acc_country_en VALUES(3, 'Algeria', 'algeria', 'DZ');
INSERT INTO acc_country_en VALUES(4, 'Andorra', 'andorra', 'AD');
INSERT INTO acc_country_en VALUES(5, 'Angola', 'angola', 'AO');
INSERT INTO acc_country_en VALUES(6, 'Anguilla', 'anguilla', 'AI');
INSERT INTO acc_country_en VALUES(7, 'Antarctica', 'antarctica', 'AQ');
INSERT INTO acc_country_en VALUES(8, 'Antigua and Barbuda', 'antigua-and-barbuda', 'AG');
INSERT INTO acc_country_en VALUES(9, 'Netherlands Antilles', 'netherlands-antilles', 'AN');
INSERT INTO acc_country_en VALUES(10, 'Saudi Arabia', 'saudi-arabia', 'SA');
INSERT INTO acc_country_en VALUES(11, 'Argentyna', 'argentyna', 'AR');
INSERT INTO acc_country_en VALUES(12, 'Armenia', 'armenia', 'AM');
INSERT INTO acc_country_en VALUES(13, 'Aruba', 'aruba', 'AW');
INSERT INTO acc_country_en VALUES(14, 'Australia', 'australia', 'AU');
INSERT INTO acc_country_en VALUES(15, 'Austria', 'austria', 'AT');
INSERT INTO acc_country_en VALUES(16, 'Palestinian Authority', 'palestinian-authority', 'PS');
INSERT INTO acc_country_en VALUES(17, 'Azerbaijan', 'azerbaijan', 'AZ');
INSERT INTO acc_country_en VALUES(18, 'Bahamas', 'bahamas', 'BS');
INSERT INTO acc_country_en VALUES(19, 'Bahrain', 'bahrain', 'BH');
INSERT INTO acc_country_en VALUES(20, 'Bangladesh', 'bangladesh', 'BD');
INSERT INTO acc_country_en VALUES(21, 'Barbados', 'barbados', 'BB');
INSERT INTO acc_country_en VALUES(22, 'Belgium', 'belgium', 'BE');
INSERT INTO acc_country_en VALUES(23, 'Belize', 'belize', 'BZ');
INSERT INTO acc_country_en VALUES(24, 'Benin', 'benin', 'BJ');
INSERT INTO acc_country_en VALUES(25, 'Bermudas', 'bermudas', 'BM');
INSERT INTO acc_country_en VALUES(26, 'Bhutan', 'bhutan', 'BT');
INSERT INTO acc_country_en VALUES(27, 'Belarus', 'belarus', 'BY');
INSERT INTO acc_country_en VALUES(28, 'Burma', 'burma', 'MM');
INSERT INTO acc_country_en VALUES(29, 'Bolivia', 'bolivia', 'BO');
INSERT INTO acc_country_en VALUES(30, 'Bosnia and Herzegovina', 'bosnia-and-herzegovina', 'BA');
INSERT INTO acc_country_en VALUES(31, 'Botswana', 'botswana', 'BW');
INSERT INTO acc_country_en VALUES(32, 'Brazil', 'brazil', 'BR');
INSERT INTO acc_country_en VALUES(33, 'Brunei', 'brunei', 'BN');
INSERT INTO acc_country_en VALUES(34, 'British Indian Ocean Territory', 'british-indian-ocean-territory', 'IO');
INSERT INTO acc_country_en VALUES(35, 'British Virgin Islands', 'british-virgin-islands', 'VG');
INSERT INTO acc_country_en VALUES(36, 'Bulgaria', 'bulgaria', 'BG');
INSERT INTO acc_country_en VALUES(37, 'Burkina Faso', 'burkina-faso', 'BF');
INSERT INTO acc_country_en VALUES(38, 'Burundi', 'burundi', 'BI');
INSERT INTO acc_country_en VALUES(39, 'Chile', 'chile', 'CL');
INSERT INTO acc_country_en VALUES(40, 'China', 'china', 'CN');
INSERT INTO acc_country_en VALUES(41, 'Croatia', 'croatia', 'HR');
INSERT INTO acc_country_en VALUES(42, 'Cyprus (country)', 'cyprus-country', 'CY');
INSERT INTO acc_country_en VALUES(43, 'Afterdamp', 'afterdamp', 'TD');
INSERT INTO acc_country_en VALUES(44, 'Montenegro', 'montenegro', 'ME');
INSERT INTO acc_country_en VALUES(45, 'Czech Republic', 'czech-republic', 'CZ');
INSERT INTO acc_country_en VALUES(46, 'Minor Outlying Islands United States', 'minor-outlying-islands-united-states', 'UM');
INSERT INTO acc_country_en VALUES(47, 'Denmark', 'denmark', 'DK');
INSERT INTO acc_country_en VALUES(48, 'democratic republic of Kongo', 'democratic-republic-of-kongo', 'CD');
INSERT INTO acc_country_en VALUES(49, 'Dominican Republic', 'dominican-republic', 'DO');
INSERT INTO acc_country_en VALUES(50, 'Dominica (state)', 'dominica-state', 'DM');
INSERT INTO acc_country_en VALUES(51, 'Djibouti', 'djibouti', 'DJ');
INSERT INTO acc_country_en VALUES(52, 'Egypt', 'egypt', 'EG');
INSERT INTO acc_country_en VALUES(53, 'Ecuador', 'ecuador', 'EC');
INSERT INTO acc_country_en VALUES(54, 'Eritrea', 'eritrea', 'ER');
INSERT INTO acc_country_en VALUES(55, 'Estonia', 'estonia', 'EE');
INSERT INTO acc_country_en VALUES(56, 'Ethiopia', 'ethiopia', 'ET');
INSERT INTO acc_country_en VALUES(57, 'Falkland Islands (territory)', 'falkland-islands-territory', 'FK');
INSERT INTO acc_country_en VALUES(58, 'Fiji', 'fiji', 'FJ');
INSERT INTO acc_country_en VALUES(59, 'Philippines', 'philippines', 'PH');
INSERT INTO acc_country_en VALUES(60, 'Finland', 'finland', 'FI');
INSERT INTO acc_country_en VALUES(61, 'France', 'france', 'FR');
INSERT INTO acc_country_en VALUES(62, 'French Southern and Antarctic Territories', 'french-southern-and-antarctic-territories', 'TF');
INSERT INTO acc_country_en VALUES(63, 'Gabon', 'gabon', 'GA');
INSERT INTO acc_country_en VALUES(64, 'Gambia', 'gambia', 'GM');
INSERT INTO acc_country_en VALUES(65, 'Ghana', 'ghana', 'GH');
INSERT INTO acc_country_en VALUES(66, 'Gibraltar', 'gibraltar', 'GI');
INSERT INTO acc_country_en VALUES(67, 'Greece', 'greece', 'GR');
INSERT INTO acc_country_en VALUES(68, 'Grenada', 'grenada', 'GD');
INSERT INTO acc_country_en VALUES(69, 'Greenland', 'greenland', 'GL');
INSERT INTO acc_country_en VALUES(70, 'Georgia', 'georgia', 'GE');
INSERT INTO acc_country_en VALUES(71, 'Guam', 'guam', 'GU');
INSERT INTO acc_country_en VALUES(72, 'Guernsey', 'guernsey', 'GG');
INSERT INTO acc_country_en VALUES(73, 'Guiana', 'guiana', 'GY');
INSERT INTO acc_country_en VALUES(74, 'French Guiana', 'french-guiana', 'GF');
INSERT INTO acc_country_en VALUES(75, 'Guadeloupe', 'guadeloupe', 'GP');
INSERT INTO acc_country_en VALUES(76, 'Guatemala', 'guatemala', 'GT');
INSERT INTO acc_country_en VALUES(77, 'Guinea', 'guinea', 'GN');
INSERT INTO acc_country_en VALUES(78, 'Guinea-Bissau', 'guinea-bissau', 'GW');
INSERT INTO acc_country_en VALUES(79, 'Equatorial Guinea', 'equatorial-guinea', 'GQ');
INSERT INTO acc_country_en VALUES(80, 'Haiti (State)', 'haiti-state', 'HT');
INSERT INTO acc_country_en VALUES(81, 'Spain', 'spain', 'ES');
INSERT INTO acc_country_en VALUES(82, 'Netherlands', 'netherlands', 'NL');
INSERT INTO acc_country_en VALUES(83, 'Honduras', 'honduras', 'HN');
INSERT INTO acc_country_en VALUES(84, 'Hong Kong', 'hong-kong', 'HK');
INSERT INTO acc_country_en VALUES(85, 'India', 'india', 'IN');
INSERT INTO acc_country_en VALUES(86, 'Indonesia', 'indonesia', 'ID');
INSERT INTO acc_country_en VALUES(87, 'Iraq', 'iraq', 'IQ');
INSERT INTO acc_country_en VALUES(88, 'Iran', 'iran', 'IR');
INSERT INTO acc_country_en VALUES(89, 'Ireland', 'ireland', 'IE');
INSERT INTO acc_country_en VALUES(90, 'Iceland', 'iceland', 'IS');
INSERT INTO acc_country_en VALUES(91, 'Israel', 'israel', 'IL');
INSERT INTO acc_country_en VALUES(92, 'Jamaica', 'jamaica', 'JM');
INSERT INTO acc_country_en VALUES(93, 'Jan Mayen (island)', 'jan-mayen-island', 'SJ');
INSERT INTO acc_country_en VALUES(94, 'Japan', 'japan', 'JP');
INSERT INTO acc_country_en VALUES(95, 'Yemen', 'yemen', 'YE');
INSERT INTO acc_country_en VALUES(96, 'Jersey', 'jersey', 'JE');
INSERT INTO acc_country_en VALUES(97, 'Jordan', 'jordan', 'JO');
INSERT INTO acc_country_en VALUES(98, 'Cayman (Islands)', 'cayman-islands', 'KY');
INSERT INTO acc_country_en VALUES(99, 'Cambodia', 'cambodia', 'KH');
INSERT INTO acc_country_en VALUES(100, 'Cameroon', 'cameroon', 'CM');
INSERT INTO acc_country_en VALUES(101, 'Canada', 'canada', 'CA');
INSERT INTO acc_country_en VALUES(102, 'Runny nose', 'runny-nose', 'QA');
INSERT INTO acc_country_en VALUES(103, 'Kazakhstan', 'kazakhstan', 'KZ');
INSERT INTO acc_country_en VALUES(104, 'Kenya', 'kenya', 'KE');
INSERT INTO acc_country_en VALUES(105, 'Kyrgyzstan', 'kyrgyzstan', 'KG');
INSERT INTO acc_country_en VALUES(106, 'Kiribati', 'kiribati', 'KI');
INSERT INTO acc_country_en VALUES(107, 'Columbia', 'columbia', 'CO');
INSERT INTO acc_country_en VALUES(108, 'Comoros', 'comoros', 'KM');
INSERT INTO acc_country_en VALUES(109, 'Kongo', 'kongo', 'CG');
INSERT INTO acc_country_en VALUES(110, 'South Korea', 'south-korea', 'KR');
INSERT INTO acc_country_en VALUES(111, 'North Korea', 'north-korea', 'KP');
INSERT INTO acc_country_en VALUES(112, 'Costa Rica', 'costa-rica', 'CR');
INSERT INTO acc_country_en VALUES(113, 'Cuba', 'cuba', 'CU');
INSERT INTO acc_country_en VALUES(114, 'Kuwait', 'kuwait', 'KW');
INSERT INTO acc_country_en VALUES(115, 'Laos', 'laos', 'LA');
INSERT INTO acc_country_en VALUES(116, 'lesotho', 'lesotho', 'LS');
INSERT INTO acc_country_en VALUES(117, 'Lebanon', 'lebanon', 'LB');
INSERT INTO acc_country_en VALUES(118, 'Livery', 'livery', 'LR');
INSERT INTO acc_country_en VALUES(119, 'Libya', 'libya', 'LY');
INSERT INTO acc_country_en VALUES(120, 'Liechtenstein', 'liechtenstein', 'LI');
INSERT INTO acc_country_en VALUES(121, 'Lithuania', 'lithuania', 'LT');
INSERT INTO acc_country_en VALUES(122, 'Luxembourg', 'luxembourg', 'LU');
INSERT INTO acc_country_en VALUES(123, 'Latvia', 'latvia', 'LV');
INSERT INTO acc_country_en VALUES(124, 'Macedonia', 'macedonia', 'MK');
INSERT INTO acc_country_en VALUES(125, 'Madagascar', 'madagascar', 'MG');
INSERT INTO acc_country_en VALUES(126, 'Mayotte', 'mayotte', 'YT');
INSERT INTO acc_country_en VALUES(127, 'Macau', 'macau', 'MO');
INSERT INTO acc_country_en VALUES(128, 'Malawi', 'malawi', 'MW');
INSERT INTO acc_country_en VALUES(129, 'Maldives', 'maldives', 'MV');
INSERT INTO acc_country_en VALUES(130, 'Malaysia', 'malaysia', 'MY');
INSERT INTO acc_country_en VALUES(131, 'Mali', 'mali', 'ML');
INSERT INTO acc_country_en VALUES(132, 'Malta', 'malta', 'MT');
INSERT INTO acc_country_en VALUES(133, 'Northern Mariana Islands', 'northern-mariana-islands', 'MP');
INSERT INTO acc_country_en VALUES(134, 'Morocco', 'morocco', 'MA');
INSERT INTO acc_country_en VALUES(135, 'Martinique', 'martinique', 'MQ');
INSERT INTO acc_country_en VALUES(136, 'Mauretania', 'mauretania', 'MR');
INSERT INTO acc_country_en VALUES(137, 'Mauritius', 'mauritius', 'MU');
INSERT INTO acc_country_en VALUES(138, 'Mexico', 'mexico', 'MX');
INSERT INTO acc_country_en VALUES(139, 'Micronesia (State)', 'micronesia-state', 'FM');
INSERT INTO acc_country_en VALUES(140, 'Moldova', 'moldova', 'MD');
INSERT INTO acc_country_en VALUES(141, 'Monaco', 'monaco', 'MC');
INSERT INTO acc_country_en VALUES(142, 'Mongolia', 'mongolia', 'MN');
INSERT INTO acc_country_en VALUES(143, 'Montserrat (Island)', 'montserrat-island', 'MS');
INSERT INTO acc_country_en VALUES(144, 'Mozambique', 'mozambique', 'MZ');
INSERT INTO acc_country_en VALUES(145, 'Namibia', 'namibia', 'NA');
INSERT INTO acc_country_en VALUES(146, 'Nauru', 'nauru', 'NR');
INSERT INTO acc_country_en VALUES(147, 'Nepal', 'nepal', 'NP');
INSERT INTO acc_country_en VALUES(148, 'Germany', 'germany', 'DE');
INSERT INTO acc_country_en VALUES(149, 'Nigeria', 'nigeria', 'NG');
INSERT INTO acc_country_en VALUES(150, 'Niger', 'niger', 'NE');
INSERT INTO acc_country_en VALUES(151, 'Nicaragua', 'nicaragua', 'NI');
INSERT INTO acc_country_en VALUES(152, 'Niue', 'niue', 'NU');
INSERT INTO acc_country_en VALUES(153, 'Norfolk (territory)', 'norfolk-territory', 'NF');
INSERT INTO acc_country_en VALUES(154, 'Norway', 'norway', 'NO');
INSERT INTO acc_country_en VALUES(155, 'New Caledonia', 'new-caledonia', 'NC');
INSERT INTO acc_country_en VALUES(156, 'New Zealand', 'new-zealand', 'NZ');
INSERT INTO acc_country_en VALUES(157, 'Oman (country)', 'oman-country', 'OM');
INSERT INTO acc_country_en VALUES(158, 'Pakistan', 'pakistan', 'PK');
INSERT INTO acc_country_en VALUES(159, 'Palau', 'palau', 'PW');
INSERT INTO acc_country_en VALUES(160, 'Panama', 'panama', 'PA');
INSERT INTO acc_country_en VALUES(161, 'Papua New Guinea', 'papua-new-guinea', 'PG');
INSERT INTO acc_country_en VALUES(162, 'Paraguay', 'paraguay', 'PY');
INSERT INTO acc_country_en VALUES(163, 'Peru', 'peru', 'PE');
INSERT INTO acc_country_en VALUES(164, 'Pitcairn', 'pitcairn', 'PN');
INSERT INTO acc_country_en VALUES(165, 'French Polynesia', 'french-polynesia', 'PF');
INSERT INTO acc_country_en VALUES(166, 'Poland', 'poland', 'PL');
INSERT INTO acc_country_en VALUES(167, 'Puerto Rico', 'puerto-rico', 'PR');
INSERT INTO acc_country_en VALUES(168, 'Portugal', 'portugal', 'PT');
INSERT INTO acc_country_en VALUES(169, 'Republic of China', 'republic-of-china', 'TW');
INSERT INTO acc_country_en VALUES(170, 'South Africa', 'south-africa', 'ZA');
INSERT INTO acc_country_en VALUES(171, 'Central African Republic', 'central-african-republic', 'CF');
INSERT INTO acc_country_en VALUES(172, 'Cape Verde', 'cape-verde', 'CV');
INSERT INTO acc_country_en VALUES(173, 'Reunion', 'reunion', 'RE');
INSERT INTO acc_country_en VALUES(174, 'Russia', 'russia', 'RU');
INSERT INTO acc_country_en VALUES(175, 'Romania', 'romania', 'RO');
INSERT INTO acc_country_en VALUES(176, 'Rwanda', 'rwanda', 'RW');
INSERT INTO acc_country_en VALUES(177, 'Western Sahara', 'western-sahara', 'EH');
INSERT INTO acc_country_en VALUES(178, 'Saint-Barthélemy', 'saint-barth%C3%A9lemy', 'BL');
INSERT INTO acc_country_en VALUES(179, 'Saint-Martin', 'saint-martin', 'MF');
INSERT INTO acc_country_en VALUES(180, 'Saint Pierre and Miquelon', 'saint-pierre-and-miquelon', 'PM');
INSERT INTO acc_country_en VALUES(181, 'Saint Kitts and Nevis', 'saint-kitts-and-nevis', 'KN');
INSERT INTO acc_country_en VALUES(182, 'Saint Lucia', 'saint-lucia', 'LC');
INSERT INTO acc_country_en VALUES(183, 'Saint Vincent and the Grenadines', 'saint-vincent-and-the-grenadines', 'VC');
INSERT INTO acc_country_en VALUES(184, 'El Salvador', 'el-salvador', 'SV');
INSERT INTO acc_country_en VALUES(185, 'Samoa', 'samoa', 'WS');
INSERT INTO acc_country_en VALUES(186, 'American Samoa', 'american-samoa', 'AS');
INSERT INTO acc_country_en VALUES(187, 'South Sandwich Islands', 'south-sandwich-islands', 'GS');
INSERT INTO acc_country_en VALUES(188, 'San Marino', 'san-marino', 'SM');
INSERT INTO acc_country_en VALUES(189, 'Senegal', 'senegal', 'SN');
INSERT INTO acc_country_en VALUES(190, 'Serbia', 'serbia', 'RS');
INSERT INTO acc_country_en VALUES(191, 'Seychelles', 'seychelles', 'SC');
INSERT INTO acc_country_en VALUES(192, 'Sierra Leone', 'sierra-leone', 'SL');
INSERT INTO acc_country_en VALUES(193, 'Singapore', 'singapore', 'SG');
INSERT INTO acc_country_en VALUES(194, 'Slovakia', 'slovakia', 'SK');
INSERT INTO acc_country_en VALUES(195, 'Slovenia', 'slovenia', 'SI');
INSERT INTO acc_country_en VALUES(196, 'Somalia', 'somalia', 'SO');
INSERT INTO acc_country_en VALUES(197, 'Sri Lanka', 'sri-lanka', 'LK');
INSERT INTO acc_country_en VALUES(198, 'United States', 'united-states', 'US');
INSERT INTO acc_country_en VALUES(199, 'Swaziland', 'swaziland', 'SZ');
INSERT INTO acc_country_en VALUES(200, 'Sudan', 'sudan', 'SD');
INSERT INTO acc_country_en VALUES(201, 'Suriname', 'suriname', 'SR');
INSERT INTO acc_country_en VALUES(202, 'Syria', 'syria', 'SY');
INSERT INTO acc_country_en VALUES(203, 'Switzerland', 'switzerland', 'CH');
INSERT INTO acc_country_en VALUES(204, 'Sweden', 'sweden', 'SE');
INSERT INTO acc_country_en VALUES(205, 'Saint Helena (colony)', 'saint-helena-colony', 'SH');
INSERT INTO acc_country_en VALUES(206, 'Tajikistan', 'tajikistan', 'TJ');
INSERT INTO acc_country_en VALUES(207, 'Thailand', 'thailand', 'TH');
INSERT INTO acc_country_en VALUES(208, 'Tanzania', 'tanzania', 'TZ');
INSERT INTO acc_country_en VALUES(209, 'East Timor', 'east-timor', 'TL');
INSERT INTO acc_country_en VALUES(210, 'Togo', 'togo', 'TG');
INSERT INTO acc_country_en VALUES(211, 'Tokelau', 'tokelau', 'TK');
INSERT INTO acc_country_en VALUES(212, 'Tonga', 'tonga', 'TO');
INSERT INTO acc_country_en VALUES(213, 'Trinidad and Tobago', 'trinidad-and-tobago', 'TT');
INSERT INTO acc_country_en VALUES(214, 'Tunisia', 'tunisia', 'TN');
INSERT INTO acc_country_en VALUES(215, 'Turkey', 'turkey', 'TR');
INSERT INTO acc_country_en VALUES(216, 'Turkmenistan', 'turkmenistan', 'TM');
INSERT INTO acc_country_en VALUES(217, 'Turks and Caicos', 'turks-and-caicos', 'TC');
INSERT INTO acc_country_en VALUES(218, 'Tuvalu', 'tuvalu', 'TV');
INSERT INTO acc_country_en VALUES(219, 'Uganda', 'uganda', 'UG');
INSERT INTO acc_country_en VALUES(220, 'Ukraine', 'ukraine', 'UA');
INSERT INTO acc_country_en VALUES(221, 'Uruguay', 'uruguay', 'UY');
INSERT INTO acc_country_en VALUES(222, 'Uzbekistan', 'uzbekistan', 'UZ');
INSERT INTO acc_country_en VALUES(223, 'Vanuatu', 'vanuatu', 'VU');
INSERT INTO acc_country_en VALUES(224, 'Wallis and Futuna', 'wallis-and-futuna', 'WF');
INSERT INTO acc_country_en VALUES(225, 'Vatican', 'vatican', 'VA');
INSERT INTO acc_country_en VALUES(226, 'Venezuela', 'venezuela', 'VE');
INSERT INTO acc_country_en VALUES(227, 'Hungary', 'hungary', 'HU');
INSERT INTO acc_country_en VALUES(228, 'Great Britain', 'great-britain', 'GB');
INSERT INTO acc_country_en VALUES(229, 'Vietnam', 'vietnam', 'VN');
INSERT INTO acc_country_en VALUES(230, 'Italy', 'italy', 'IT');
INSERT INTO acc_country_en VALUES(231, 'Ivory Coast', 'ivory-coast', 'CI');
INSERT INTO acc_country_en VALUES(232, 'Bouvet Island', 'bouvet-island', 'BV');
INSERT INTO acc_country_en VALUES(233, 'Christmas Island', 'christmas-island', 'CX');
INSERT INTO acc_country_en VALUES(234, 'Isle of Man', 'isle-of-man', 'IM');
INSERT INTO acc_country_en VALUES(235, 'Aland Islands', 'aland-islands', 'AX');
INSERT INTO acc_country_en VALUES(236, 'Cook Islands', 'cook-islands', 'CK');
INSERT INTO acc_country_en VALUES(237, 'US Virgin Islands', 'us-virgin-islands', 'VI');
INSERT INTO acc_country_en VALUES(238, 'Heard Island and McDonald', 'heard-island-and-mcdonald', 'HM');
INSERT INTO acc_country_en VALUES(239, 'Cocos Islands', 'cocos-islands', 'CC');
INSERT INTO acc_country_en VALUES(240, 'Marshall Islands', 'marshall-islands', 'MH');
INSERT INTO acc_country_en VALUES(241, 'Faroe Islands', 'faroe-islands', 'FO');
INSERT INTO acc_country_en VALUES(242, 'Solomon Islands', 'solomon-islands', 'SB');
INSERT INTO acc_country_en VALUES(243, 'Sao Tome and Principe', 'sao-tome-and-principe', 'ST');
INSERT INTO acc_country_en VALUES(244, 'Zambia', 'zambia', 'ZM');
INSERT INTO acc_country_en VALUES(245, 'Zimbabwe', 'zimbabwe', 'ZW');
INSERT INTO acc_country_en VALUES(246, 'United Arab Emirates', 'united-arab-emirates', 'AE');

DROP TABLE IF EXISTS `acc_country_pl`;
CREATE TABLE `acc_country_pl` (
  `id` tinyint(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `code` varchar(5) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

ALTER TABLE `acc_country_pl` ADD PRIMARY KEY (`id`), ADD KEY `code` (`code`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_country_pl` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

INSERT INTO acc_country_pl VALUES(1, 'Afganistan', 'afganistan', 'AF');
INSERT INTO acc_country_pl VALUES(2, 'Albania', 'albania', 'AL');
INSERT INTO acc_country_pl VALUES(3, 'Algieria', 'algieria', 'DZ');
INSERT INTO acc_country_pl VALUES(4, 'Andora', 'andora', 'AD');
INSERT INTO acc_country_pl VALUES(5, 'Angola', 'angola', 'AO');
INSERT INTO acc_country_pl VALUES(6, 'Anguilla', 'anguilla', 'AI');
INSERT INTO acc_country_pl VALUES(7, 'Antarktyda', 'antarktyda', 'AQ');
INSERT INTO acc_country_pl VALUES(8, 'Antigua i Barbuda', 'antigua-i-barbuda', 'AG');
INSERT INTO acc_country_pl VALUES(9, 'Antyle Holenderskie', 'antyle-holenderskie', 'AN');
INSERT INTO acc_country_pl VALUES(10, 'Arabia Saudyjska', 'arabia-saudyjska', 'SA');
INSERT INTO acc_country_pl VALUES(11, 'Argentyna', 'argentyna', 'AR');
INSERT INTO acc_country_pl VALUES(12, 'Armenia', 'armenia', 'AM');
INSERT INTO acc_country_pl VALUES(13, 'Aruba', 'aruba', 'AW');
INSERT INTO acc_country_pl VALUES(14, 'Australia', 'australia', 'AU');
INSERT INTO acc_country_pl VALUES(15, 'Austria', 'austria', 'AT');
INSERT INTO acc_country_pl VALUES(16, 'Autonomia Palestyńska', 'autonomia-palestynska', 'PS');
INSERT INTO acc_country_pl VALUES(17, 'Azerbejdżan', 'azerbejdzan', 'AZ');
INSERT INTO acc_country_pl VALUES(18, 'Bahamy', 'bahamy', 'BS');
INSERT INTO acc_country_pl VALUES(19, 'Bahrajn', 'bahrajn', 'BH');
INSERT INTO acc_country_pl VALUES(20, 'Bangladesz', 'bangladesz', 'BD');
INSERT INTO acc_country_pl VALUES(21, 'Barbados', 'barbados', 'BB');
INSERT INTO acc_country_pl VALUES(22, 'Belgia', 'belgia', 'BE');
INSERT INTO acc_country_pl VALUES(23, 'Belize', 'belize', 'BZ');
INSERT INTO acc_country_pl VALUES(24, 'Benin', 'benin', 'BJ');
INSERT INTO acc_country_pl VALUES(25, 'Bermudy', 'bermudy', 'BM');
INSERT INTO acc_country_pl VALUES(26, 'Bhutan', 'bhutan', 'BT');
INSERT INTO acc_country_pl VALUES(27, 'Białoruś', 'bialorus', 'BY');
INSERT INTO acc_country_pl VALUES(28, 'Birma', 'birma', 'MM');
INSERT INTO acc_country_pl VALUES(29, 'Boliwia', 'boliwia', 'BO');
INSERT INTO acc_country_pl VALUES(30, 'Bośnia i Hercegowina', 'bosnia-i-hercegowina', 'BA');
INSERT INTO acc_country_pl VALUES(31, 'Botswana', 'botswana', 'BW');
INSERT INTO acc_country_pl VALUES(32, 'Brazylia', 'brazylia', 'BR');
INSERT INTO acc_country_pl VALUES(33, 'Brunei', 'brunei', 'BN');
INSERT INTO acc_country_pl VALUES(34, 'Brytyjskie Terytorium Oceanu Indyjskiego', 'brytyjskie-terytorium-oceanu-indyjskiego', 'IO');
INSERT INTO acc_country_pl VALUES(35, 'Brytyjskie Wyspy Dziewicze', 'brytyjskie-wyspy-dziewicze', 'VG');
INSERT INTO acc_country_pl VALUES(36, 'Bułgaria', 'bulgaria', 'BG');
INSERT INTO acc_country_pl VALUES(37, 'Burkina Faso', 'burkina-faso', 'BF');
INSERT INTO acc_country_pl VALUES(38, 'Burundi', 'burundi', 'BI');
INSERT INTO acc_country_pl VALUES(39, 'Chile', 'chile', 'CL');
INSERT INTO acc_country_pl VALUES(40, 'Chińska Republika Ludowa', 'chinska-republika-ludowa', 'CN');
INSERT INTO acc_country_pl VALUES(41, 'Chorwacja', 'chorwacja', 'HR');
INSERT INTO acc_country_pl VALUES(42, 'Cypr (państwo)', 'cypr-panstwo', 'CY');
INSERT INTO acc_country_pl VALUES(43, 'Czad', 'czad', 'TD');
INSERT INTO acc_country_pl VALUES(44, 'Czarnogóra', 'czarnogora', 'ME');
INSERT INTO acc_country_pl VALUES(45, 'Czechy', 'czechy', 'CZ');
INSERT INTO acc_country_pl VALUES(46, 'Dalekie Wyspy Mniejsze Stanów Zjednoczonych', 'dalekie-wyspy-mniejsze-stanow-zjednoczonych', 'UM');
INSERT INTO acc_country_pl VALUES(47, 'Dania', 'dania', 'DK');
INSERT INTO acc_country_pl VALUES(48, 'Demokratyczna Republika Konga', 'demokratyczna-republika-konga', 'CD');
INSERT INTO acc_country_pl VALUES(49, 'Dominikana', 'dominikana', 'DO');
INSERT INTO acc_country_pl VALUES(50, 'Dominika (państwo)', 'dominika-panstwo', 'DM');
INSERT INTO acc_country_pl VALUES(51, 'Dżibuti', 'dzibuti', 'DJ');
INSERT INTO acc_country_pl VALUES(52, 'Egipt', 'egipt', 'EG');
INSERT INTO acc_country_pl VALUES(53, 'Ekwador', 'ekwador', 'EC');
INSERT INTO acc_country_pl VALUES(54, 'Erytrea', 'erytrea', 'ER');
INSERT INTO acc_country_pl VALUES(55, 'Estonia', 'estonia', 'EE');
INSERT INTO acc_country_pl VALUES(56, 'Etiopia', 'etiopia', 'ET');
INSERT INTO acc_country_pl VALUES(57, 'Falklandy (terytorium)', 'falklandy-terytorium', 'FK');
INSERT INTO acc_country_pl VALUES(58, 'Fidżi', 'fidzi', 'FJ');
INSERT INTO acc_country_pl VALUES(59, 'Filipiny', 'filipiny', 'PH');
INSERT INTO acc_country_pl VALUES(60, 'Finlandia', 'finlandia', 'FI');
INSERT INTO acc_country_pl VALUES(61, 'Francja', 'francja', 'FR');
INSERT INTO acc_country_pl VALUES(62, 'Francuskie Terytoria Południowe i Antarktyczne', 'francuskie-terytoria-poludniowe-i-antarktyczne', 'TF');
INSERT INTO acc_country_pl VALUES(63, 'Gabon', 'gabon', 'GA');
INSERT INTO acc_country_pl VALUES(64, 'Gambia', 'gambia', 'GM');
INSERT INTO acc_country_pl VALUES(65, 'Ghana', 'ghana', 'GH');
INSERT INTO acc_country_pl VALUES(66, 'Gibraltar', 'gibraltar', 'GI');
INSERT INTO acc_country_pl VALUES(67, 'Grecja', 'grecja', 'GR');
INSERT INTO acc_country_pl VALUES(68, 'Grenada', 'grenada', 'GD');
INSERT INTO acc_country_pl VALUES(69, 'Grenlandia', 'grenlandia', 'GL');
INSERT INTO acc_country_pl VALUES(70, 'Gruzja', 'gruzja', 'GE');
INSERT INTO acc_country_pl VALUES(71, 'Guam', 'guam', 'GU');
INSERT INTO acc_country_pl VALUES(72, 'Guernsey', 'guernsey', 'GG');
INSERT INTO acc_country_pl VALUES(73, 'Gujana', 'gujana', 'GY');
INSERT INTO acc_country_pl VALUES(74, 'Gujana Francuska', 'gujana-francuska', 'GF');
INSERT INTO acc_country_pl VALUES(75, 'Gwadelupa', 'gwadelupa', 'GP');
INSERT INTO acc_country_pl VALUES(76, 'Gwatemala', 'gwatemala', 'GT');
INSERT INTO acc_country_pl VALUES(77, 'Gwinea', 'gwinea', 'GN');
INSERT INTO acc_country_pl VALUES(78, 'Gwinea Bissau', 'gwinea-bissau', 'GW');
INSERT INTO acc_country_pl VALUES(79, 'Gwinea Równikowa', 'gwinea-rownikowa', 'GQ');
INSERT INTO acc_country_pl VALUES(80, 'Haiti (państwo)', 'haiti-panstwo', 'HT');
INSERT INTO acc_country_pl VALUES(81, 'Hiszpania', 'hiszpania', 'ES');
INSERT INTO acc_country_pl VALUES(82, 'Holandia', 'holandia', 'NL');
INSERT INTO acc_country_pl VALUES(83, 'Honduras', 'honduras', 'HN');
INSERT INTO acc_country_pl VALUES(84, 'Hongkong', 'hongkong', 'HK');
INSERT INTO acc_country_pl VALUES(85, 'Indie', 'indie', 'IN');
INSERT INTO acc_country_pl VALUES(86, 'Indonezja', 'indonezja', 'ID');
INSERT INTO acc_country_pl VALUES(87, 'Irak', 'irak', 'IQ');
INSERT INTO acc_country_pl VALUES(88, 'Iran', 'iran', 'IR');
INSERT INTO acc_country_pl VALUES(89, 'Irlandia', 'irlandia', 'IE');
INSERT INTO acc_country_pl VALUES(90, 'Islandia', 'islandia', 'IS');
INSERT INTO acc_country_pl VALUES(91, 'Izrael', 'izrael', 'IL');
INSERT INTO acc_country_pl VALUES(92, 'Jamajka', 'jamajka', 'JM');
INSERT INTO acc_country_pl VALUES(93, 'Jan Mayen (wyspa)', 'jan-mayen-wyspa', 'SJ');
INSERT INTO acc_country_pl VALUES(94, 'Japonia', 'japonia', 'JP');
INSERT INTO acc_country_pl VALUES(95, 'Jemen', 'jemen', 'YE');
INSERT INTO acc_country_pl VALUES(96, 'Jersey', 'jersey', 'JE');
INSERT INTO acc_country_pl VALUES(97, 'Jordania', 'jordania', 'JO');
INSERT INTO acc_country_pl VALUES(98, 'Kajmany (wyspy)', 'kajmany-wyspy', 'KY');
INSERT INTO acc_country_pl VALUES(99, 'Kambodża', 'kambodza', 'KH');
INSERT INTO acc_country_pl VALUES(100, 'Kamerun', 'kamerun', 'CM');
INSERT INTO acc_country_pl VALUES(101, 'Kanada', 'kanada', 'CA');
INSERT INTO acc_country_pl VALUES(102, 'Katar', 'katar', 'QA');
INSERT INTO acc_country_pl VALUES(103, 'Kazachstan', 'kazachstan', 'KZ');
INSERT INTO acc_country_pl VALUES(104, 'Kenia', 'kenia', 'KE');
INSERT INTO acc_country_pl VALUES(105, 'Kirgistan', 'kirgistan', 'KG');
INSERT INTO acc_country_pl VALUES(106, 'Kiribati', 'kiribati', 'KI');
INSERT INTO acc_country_pl VALUES(107, 'Kolumbia', 'kolumbia', 'CO');
INSERT INTO acc_country_pl VALUES(108, 'Komory', 'komory', 'KM');
INSERT INTO acc_country_pl VALUES(109, 'Kongo', 'kongo', 'CG');
INSERT INTO acc_country_pl VALUES(110, 'Korea Południowa', 'korea-poludniowa', 'KR');
INSERT INTO acc_country_pl VALUES(111, 'Korea Północna', 'korea-polnocna', 'KP');
INSERT INTO acc_country_pl VALUES(112, 'Kostaryka', 'kostaryka', 'CR');
INSERT INTO acc_country_pl VALUES(113, 'Kuba', 'kuba', 'CU');
INSERT INTO acc_country_pl VALUES(114, 'Kuwejt', 'kuwejt', 'KW');
INSERT INTO acc_country_pl VALUES(115, 'Laos', 'laos', 'LA');
INSERT INTO acc_country_pl VALUES(116, 'Lesotho', 'lesotho', 'LS');
INSERT INTO acc_country_pl VALUES(117, 'Liban', 'liban', 'LB');
INSERT INTO acc_country_pl VALUES(118, 'Liberia', 'liberia', 'LR');
INSERT INTO acc_country_pl VALUES(119, 'Libia', 'libia', 'LY');
INSERT INTO acc_country_pl VALUES(120, 'Liechtenstein', 'liechtenstein', 'LI');
INSERT INTO acc_country_pl VALUES(121, 'Litwa', 'litwa', 'LT');
INSERT INTO acc_country_pl VALUES(122, 'Luksemburg', 'luksemburg', 'LU');
INSERT INTO acc_country_pl VALUES(123, 'Łotwa', 'lotwa', 'LV');
INSERT INTO acc_country_pl VALUES(124, 'Macedonia', 'macedonia', 'MK');
INSERT INTO acc_country_pl VALUES(125, 'Madagaskar', 'madagaskar', 'MG');
INSERT INTO acc_country_pl VALUES(126, 'Majotta', 'majotta', 'YT');
INSERT INTO acc_country_pl VALUES(127, 'Makau', 'makau', 'MO');
INSERT INTO acc_country_pl VALUES(128, 'Malawi', 'malawi', 'MW');
INSERT INTO acc_country_pl VALUES(129, 'Malediwy', 'malediwy', 'MV');
INSERT INTO acc_country_pl VALUES(130, 'Malezja', 'malezja', 'MY');
INSERT INTO acc_country_pl VALUES(131, 'Mali', 'mali', 'ML');
INSERT INTO acc_country_pl VALUES(132, 'Malta', 'malta', 'MT');
INSERT INTO acc_country_pl VALUES(133, 'Mariany Północne', 'mariany-polnocne', 'MP');
INSERT INTO acc_country_pl VALUES(134, 'Maroko', 'maroko', 'MA');
INSERT INTO acc_country_pl VALUES(135, 'Martynika', 'martynika', 'MQ');
INSERT INTO acc_country_pl VALUES(136, 'Mauretania', 'mauretania', 'MR');
INSERT INTO acc_country_pl VALUES(137, 'Mauritius', 'mauritius', 'MU');
INSERT INTO acc_country_pl VALUES(138, 'Meksyk', 'meksyk', 'MX');
INSERT INTO acc_country_pl VALUES(139, 'Mikronezja (państwo)', 'mikronezja-panstwo', 'FM');
INSERT INTO acc_country_pl VALUES(140, 'Mołdawia', 'moldawia', 'MD');
INSERT INTO acc_country_pl VALUES(141, 'Monako', 'monako', 'MC');
INSERT INTO acc_country_pl VALUES(142, 'Mongolia', 'mongolia', 'MN');
INSERT INTO acc_country_pl VALUES(143, 'Montserrat (wyspa)', 'montserrat-wyspa', 'MS');
INSERT INTO acc_country_pl VALUES(144, 'Mozambik', 'mozambik', 'MZ');
INSERT INTO acc_country_pl VALUES(145, 'Namibia', 'namibia', 'NA');
INSERT INTO acc_country_pl VALUES(146, 'Nauru', 'nauru', 'NR');
INSERT INTO acc_country_pl VALUES(147, 'Nepal', 'nepal', 'NP');
INSERT INTO acc_country_pl VALUES(148, 'Niemcy', 'niemcy', 'DE');
INSERT INTO acc_country_pl VALUES(149, 'Nigeria', 'nigeria', 'NG');
INSERT INTO acc_country_pl VALUES(150, 'Niger', 'niger', 'NE');
INSERT INTO acc_country_pl VALUES(151, 'Nikaragua', 'nikaragua', 'NI');
INSERT INTO acc_country_pl VALUES(152, 'Niue', 'niue', 'NU');
INSERT INTO acc_country_pl VALUES(153, 'Norfolk (terytorium)', 'norfolk-terytorium', 'NF');
INSERT INTO acc_country_pl VALUES(154, 'Norwegia', 'norwegia', 'NO');
INSERT INTO acc_country_pl VALUES(155, 'Nowa Kaledonia', 'nowa-kaledonia', 'NC');
INSERT INTO acc_country_pl VALUES(156, 'Nowa Zelandia', 'nowa-zelandia', 'NZ');
INSERT INTO acc_country_pl VALUES(157, 'Oman (państwo)', 'oman-panstwo', 'OM');
INSERT INTO acc_country_pl VALUES(158, 'Pakistan', 'pakistan', 'PK');
INSERT INTO acc_country_pl VALUES(159, 'Palau', 'palau', 'PW');
INSERT INTO acc_country_pl VALUES(160, 'Panama', 'panama', 'PA');
INSERT INTO acc_country_pl VALUES(161, 'Papua-Nowa Gwinea', 'papua-nowa-gwinea', 'PG');
INSERT INTO acc_country_pl VALUES(162, 'Paragwaj', 'paragwaj', 'PY');
INSERT INTO acc_country_pl VALUES(163, 'Peru', 'peru', 'PE');
INSERT INTO acc_country_pl VALUES(164, 'Pitcairn', 'pitcairn', 'PN');
INSERT INTO acc_country_pl VALUES(165, 'Polinezja Francuska', 'polinezja-francuska', 'PF');
INSERT INTO acc_country_pl VALUES(166, 'Polska', 'polska', 'PL');
INSERT INTO acc_country_pl VALUES(167, 'Portoryko', 'portoryko', 'PR');
INSERT INTO acc_country_pl VALUES(168, 'Portugalia', 'portugalia', 'PT');
INSERT INTO acc_country_pl VALUES(169, 'Republika Chińska', 'republika-chinska', 'TW');
INSERT INTO acc_country_pl VALUES(170, 'Republika Południowej Afryki', 'republika-poludniowej-afryki', 'ZA');
INSERT INTO acc_country_pl VALUES(171, 'Republika Środkowoafrykańska', 'republika-srodkowoafrykanska', 'CF');
INSERT INTO acc_country_pl VALUES(172, 'Republika Zielonego Przylądka', 'republika-zielonego-przyladka', 'CV');
INSERT INTO acc_country_pl VALUES(173, 'Reunion', 'reunion', 'RE');
INSERT INTO acc_country_pl VALUES(174, 'Rosja', 'rosja', 'RU');
INSERT INTO acc_country_pl VALUES(175, 'Rumunia', 'rumunia', 'RO');
INSERT INTO acc_country_pl VALUES(176, 'Rwanda', 'rwanda', 'RW');
INSERT INTO acc_country_pl VALUES(177, 'Sahara Zachodnia', 'sahara-zachodnia', 'EH');
INSERT INTO acc_country_pl VALUES(178, 'Saint-Barthélemy', 'saint-barth%C3%A9lemy', 'BL');
INSERT INTO acc_country_pl VALUES(179, 'Saint-Martin', 'saint-martin', 'MF');
INSERT INTO acc_country_pl VALUES(180, 'Saint-Pierre i Miquelon', 'saint-pierre-i-miquelon', 'PM');
INSERT INTO acc_country_pl VALUES(181, 'Saint Kitts i Nevis', 'saint-kitts-i-nevis', 'KN');
INSERT INTO acc_country_pl VALUES(182, 'Saint Lucia', 'saint-lucia', 'LC');
INSERT INTO acc_country_pl VALUES(183, 'Saint Vincent i Grenadyny', 'saint-vincent-i-grenadyny', 'VC');
INSERT INTO acc_country_pl VALUES(184, 'Salwador', 'salwador', 'SV');
INSERT INTO acc_country_pl VALUES(185, 'Samoa', 'samoa', 'WS');
INSERT INTO acc_country_pl VALUES(186, 'Samoa Amerykańskie', 'samoa-amerykanskie', 'AS');
INSERT INTO acc_country_pl VALUES(187, 'Sandwich Południowy', 'sandwich-poludniowy', 'GS');
INSERT INTO acc_country_pl VALUES(188, 'San Marino', 'san-marino', 'SM');
INSERT INTO acc_country_pl VALUES(189, 'Senegal', 'senegal', 'SN');
INSERT INTO acc_country_pl VALUES(190, 'Serbia', 'serbia', 'RS');
INSERT INTO acc_country_pl VALUES(191, 'Seszele', 'seszele', 'SC');
INSERT INTO acc_country_pl VALUES(192, 'Sierra Leone', 'sierra-leone', 'SL');
INSERT INTO acc_country_pl VALUES(193, 'Singapur', 'singapur', 'SG');
INSERT INTO acc_country_pl VALUES(194, 'Słowacja', 'slowacja', 'SK');
INSERT INTO acc_country_pl VALUES(195, 'Słowenia', 'slowenia', 'SI');
INSERT INTO acc_country_pl VALUES(196, 'Somalia', 'somalia', 'SO');
INSERT INTO acc_country_pl VALUES(197, 'Sri Lanka', 'sri-lanka', 'LK');
INSERT INTO acc_country_pl VALUES(198, 'Stany Zjednoczone', 'stany-zjednoczone', 'US');
INSERT INTO acc_country_pl VALUES(199, 'Suazi', 'suazi', 'SZ');
INSERT INTO acc_country_pl VALUES(200, 'Sudan', 'sudan', 'SD');
INSERT INTO acc_country_pl VALUES(201, 'Surinam', 'surinam', 'SR');
INSERT INTO acc_country_pl VALUES(202, 'Syria', 'syria', 'SY');
INSERT INTO acc_country_pl VALUES(203, 'Szwajcaria', 'szwajcaria', 'CH');
INSERT INTO acc_country_pl VALUES(204, 'Szwecja', 'szwecja', 'SE');
INSERT INTO acc_country_pl VALUES(205, 'Święta Helena (kolonia)', 'swieta-helena-kolonia', 'SH');
INSERT INTO acc_country_pl VALUES(206, 'Tadżykistan', 'tadzykistan', 'TJ');
INSERT INTO acc_country_pl VALUES(207, 'Tajlandia', 'tajlandia', 'TH');
INSERT INTO acc_country_pl VALUES(208, 'Tanzania', 'tanzania', 'TZ');
INSERT INTO acc_country_pl VALUES(209, 'Timor Wschodni', 'timor-wschodni', 'TL');
INSERT INTO acc_country_pl VALUES(210, 'Togo', 'togo', 'TG');
INSERT INTO acc_country_pl VALUES(211, 'Tokelau', 'tokelau', 'TK');
INSERT INTO acc_country_pl VALUES(212, 'Tonga', 'tonga', 'TO');
INSERT INTO acc_country_pl VALUES(213, 'Trynidad i Tobago', 'trynidad-i-tobago', 'TT');
INSERT INTO acc_country_pl VALUES(214, 'Tunezja', 'tunezja', 'TN');
INSERT INTO acc_country_pl VALUES(215, 'Turcja', 'turcja', 'TR');
INSERT INTO acc_country_pl VALUES(216, 'Turkmenistan', 'turkmenistan', 'TM');
INSERT INTO acc_country_pl VALUES(217, 'Turks i Caicos', 'turks-i-caicos', 'TC');
INSERT INTO acc_country_pl VALUES(218, 'Tuvalu', 'tuvalu', 'TV');
INSERT INTO acc_country_pl VALUES(219, 'Uganda', 'uganda', 'UG');
INSERT INTO acc_country_pl VALUES(220, 'Ukraina', 'ukraina', 'UA');
INSERT INTO acc_country_pl VALUES(221, 'Urugwaj', 'urugwaj', 'UY');
INSERT INTO acc_country_pl VALUES(222, 'Uzbekistan', 'uzbekistan', 'UZ');
INSERT INTO acc_country_pl VALUES(223, 'Vanuatu', 'vanuatu', 'VU');
INSERT INTO acc_country_pl VALUES(224, 'Wallis i Futuna', 'wallis-i-futuna', 'WF');
INSERT INTO acc_country_pl VALUES(225, 'Watykan', 'watykan', 'VA');
INSERT INTO acc_country_pl VALUES(226, 'Wenezuela', 'wenezuela', 'VE');
INSERT INTO acc_country_pl VALUES(227, 'Węgry', 'wegry', 'HU');
INSERT INTO acc_country_pl VALUES(228, 'Wielka Brytania', 'wielka-brytania', 'GB');
INSERT INTO acc_country_pl VALUES(229, 'Wietnam', 'wietnam', 'VN');
INSERT INTO acc_country_pl VALUES(230, 'Włochy', 'wlochy', 'IT');
INSERT INTO acc_country_pl VALUES(231, 'Wybrzeże Kości Słoniowej', 'wybrzeze-kosci-sloniowej', 'CI');
INSERT INTO acc_country_pl VALUES(232, 'Wyspa Bouveta', 'wyspa-bouveta', 'BV');
INSERT INTO acc_country_pl VALUES(233, 'Wyspa Bożego Narodzenia', 'wyspa-bozego-narodzenia', 'CX');
INSERT INTO acc_country_pl VALUES(234, 'Wyspa Man', 'wyspa-man', 'IM');
INSERT INTO acc_country_pl VALUES(235, 'Wyspy Alandzkie', 'wyspy-alandzkie', 'AX');
INSERT INTO acc_country_pl VALUES(236, 'Wyspy Cooka', 'wyspy-cooka', 'CK');
INSERT INTO acc_country_pl VALUES(237, 'Wyspy Dziewicze Stanów Zjednoczonych', 'wyspy-dziewicze-stanow-zjednoczonych', 'VI');
INSERT INTO acc_country_pl VALUES(238, 'Wyspy Heard i McDonalda', 'wyspy-heard-i-mcdonalda', 'HM');
INSERT INTO acc_country_pl VALUES(239, 'Wyspy Kokosowe', 'wyspy-kokosowe', 'CC');
INSERT INTO acc_country_pl VALUES(240, 'Wyspy Marshalla', 'wyspy-marshalla', 'MH');
INSERT INTO acc_country_pl VALUES(241, 'Wyspy Owcze', 'wyspy-owcze', 'FO');
INSERT INTO acc_country_pl VALUES(242, 'Wyspy Salomona', 'wyspy-salomona', 'SB');
INSERT INTO acc_country_pl VALUES(243, 'Wyspy Świętego Tomasza i Książęca', 'wyspy-swietego-tomasza-i-ksiazeca', 'ST');
INSERT INTO acc_country_pl VALUES(244, 'Zambia', 'zambia', 'ZM');
INSERT INTO acc_country_pl VALUES(245, 'Zimbabwe', 'zimbabwe', 'ZW');
INSERT INTO acc_country_pl VALUES(246, 'Zjednoczone Emiraty Arabskie', 'zjednoczone-emiraty-arabskie', 'AE');

DROP TABLE IF EXISTS `acc_distance_en`;
CREATE TABLE `acc_distance_en` (
  `id` tinyint(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

ALTER TABLE `acc_distance_en` ADD PRIMARY KEY (`id`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_distance_en` MODIFY `id` tinyint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

INSERT INTO acc_distance_en VALUES(1, 'Beach', 'beach');
INSERT INTO acc_distance_en VALUES(2, 'Lake', 'lake');
INSERT INTO acc_distance_en VALUES(3, 'Post office', 'post-office');
INSERT INTO acc_distance_en VALUES(4, 'Shop', 'shop');
INSERT INTO acc_distance_en VALUES(5, 'Centrum', 'centrum');
INSERT INTO acc_distance_en VALUES(6, 'Mountain trails', 'mountain-trails');
INSERT INTO acc_distance_en VALUES(7, 'Bus STOP', 'bus-stop');
INSERT INTO acc_distance_en VALUES(8, 'Train station', 'train-station');
INSERT INTO acc_distance_en VALUES(9, 'TAXI', 'taxi');
INSERT INTO acc_distance_en VALUES(10, 'Forest', 'forest');
INSERT INTO acc_distance_en VALUES(11, 'Restaurant', 'restaurant');
INSERT INTO acc_distance_en VALUES(12, 'Disco', 'disco');

DROP TABLE IF EXISTS `acc_distance_pl`;
CREATE TABLE `acc_distance_pl` (
  `id` tinyint(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


ALTER TABLE `acc_distance_pl` ADD PRIMARY KEY (`id`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_distance_pl` MODIFY `id` tinyint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

INSERT INTO acc_distance_pl VALUES(1, 'Plaża', 'plaza');
INSERT INTO acc_distance_pl VALUES(2, 'Jezioro', 'jezioro');
INSERT INTO acc_distance_pl VALUES(3, 'Poczta', 'poczta');
INSERT INTO acc_distance_pl VALUES(4, 'Sklep', 'sklep');
INSERT INTO acc_distance_pl VALUES(5, 'Centrum', 'centrum');
INSERT INTO acc_distance_pl VALUES(6, 'Szlaki górskie', 'szlaki-gorskie');
INSERT INTO acc_distance_pl VALUES(7, 'Przystanek autobusowy', 'przystanek-autobusowy');
INSERT INTO acc_distance_pl VALUES(8, 'Stacja kolejowa', 'stacja-kolejowa');
INSERT INTO acc_distance_pl VALUES(9, 'Postój TAXI', 'postoj-taxi');
INSERT INTO acc_distance_pl VALUES(10, 'Las', 'las');
INSERT INTO acc_distance_pl VALUES(11, 'Restauracja', 'restauracja');
INSERT INTO acc_distance_pl VALUES(12, 'Dyskoteka', 'dyskoteka');

DROP TABLE IF EXISTS `acc_dotpay_sms`;
CREATE TABLE `acc_dotpay_sms` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `object_id` int(11) UNSIGNED NOT NULL,
  `special_id` int(11) UNSIGNED NULL,
  `pid` int(11) UNSIGNED NOT NULL,
  `create_date` datetime NOT NULL,
  `text` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `valid` enum('TRUE','FALSE') COLLATE utf8_polish_ci NOT NULL,
  `expire_date` datetime DEFAULT NULL,
  `code` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

ALTER TABLE `acc_dotpay_sms` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_dotpay_sms` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_emails_en;
CREATE TABLE acc_emails_en (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `value` longtext COLLATE utf8_polish_ci NOT NULL,
  `description` longtext COLLATE utf8_polish_ci NOT NULL,
  `type` enum('HTML','PLAIN') COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

ALTER TABLE acc_emails_en ADD PRIMARY KEY (`id`), ADD KEY `name` (`name`);
ALTER TABLE acc_emails_en MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

INSERT INTO acc_emails_en VALUES(1, 'user-password-change', 'Witaj [login],&amp;lt;br /&amp;gt;\n&amp;lt;br /&amp;gt;\nHasło do Twojego konta zostało zmienione pomyślnie.&amp;lt;br /&amp;gt;\nOd teraz logowanie do panelu będzie wymagało podania nowego adresu e-mail.\nNowe hasło: [password]', 'Treść wiadomości e-mail wysyłana po zmianie hasła do konta.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasło do konta', 'HTML');
INSERT INTO acc_emails_en VALUES(2, 'user-new-account', 'Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nDziękujemy za założenie konta w naszym serwisie. Aby się zalogować prosimy najpierw kliknąć w poniższy link w celu aktywacji konta:&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[activate-url]&amp;quot;&amp;gt;[activate-url]&amp;lt;/a&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nPo aktywowaniu konta logowanie będzie aktywne. Poniżej dane oraz link bezpośredni do strony logowania.&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Login:&amp;amp;nbsp;&amp;lt;/strong&amp;gt;[login]&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Hasło:&amp;lt;/strong&amp;gt; [password]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Logowanie dostępne z adresu:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;login-url]&amp;quot;&amp;gt;[login-url]&amp;lt;/a&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;u&amp;gt;Pamiętaj, aby w ramach bezpieczeństwa&amp;amp;nbsp;zmienić&amp;amp;nbsp;podane hasło na nowe już przy pierwszym logowaniu !&amp;lt;/u&amp;gt;', 'Treść wiadomości e-mail wysyłana po utworzeniu nowego konta użytkownika przez formularz rejestracji na stronie&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasło do konta&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[activate-url]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia link aktywacyjny&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia link do logowania do panelu', 'HTML');
INSERT INTO acc_emails_en VALUES(3, 'request-photos', '&amp;lt;p&amp;gt;Witaj,&amp;lt;br /&amp;gt;\r\nUżytkownik naszego serwisu prosi Ciebie o dodanie zdjęć do oferty:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[object-link]&amp;quot;&amp;gt;[object-link]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Pamiętaj, że oferta jest skuteczniejsza jeżeli zawiera realne zdjęcia co znacząco zwiększa ilość potencjalnych klientów&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;h5&amp;gt;Jak dodać zdjęcia&amp;lt;/h5&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Aby dodać zdjęcia, zaloguj się na swoim profilu w naszym serwisie podając swój login i hasło, a następnie przy wybranej ofercie kliknij na przycisk zdjęcia.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Logowanie do serwisu: &amp;lt;a href=&amp;quot;[login-url]&amp;quot;&amp;gt;[login-url]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Pozdrawiamy&amp;lt;/p&amp;gt;\r\n', 'Treść wysyłana gdy ktoś kliknie na stronie obiektu przycisk&amp;amp;nbsp;&amp;lt;strong&amp;gt;poproś o dodanie zdjęć.&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-name]&amp;lt;/strong&amp;gt; - wstawia nazwę obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-link]&amp;lt;/strong&amp;gt; - wstawia link do obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;lt;/strong&amp;gt; - wstaiwa adres url do zalogowania się', 'HTML');
INSERT INTO acc_emails_en VALUES(4, 'object-contact-msg', '&amp;lt;p&amp;gt;Witaj,&amp;lt;br /&amp;gt;\r\nZostało wysłane do Ciebie zapytanie dotyczące Twojej oferty:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[object-link]&amp;quot;&amp;gt;[object-link]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;table&amp;gt;\r\n	&amp;lt;tbody&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Nadawca:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[name]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Adres e-mail:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[email]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Telefon:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[phone]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n	&amp;lt;/tbody&amp;gt;\r\n&amp;lt;/table&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;&amp;lt;b&amp;gt;Treść wiadomości:&amp;lt;/b&amp;gt;&amp;lt;br /&amp;gt;\r\n[text]&amp;lt;/p&amp;gt;\r\n', 'Treść wiadomości wysyłana po uzupełnieniu formularza kontaktowego obiektu.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia nazwę obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-link]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia link do obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[name]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia imię i nazwisko nadawcy podane w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia adres e-mail nadawcy podany w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[phone]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia numer telefonu podany w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[text]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wprowadza treść podaną w formularzu', 'HTML');
INSERT INTO acc_emails_en VALUES(5, 'admin-new-account', 'Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nAdministrator utworzył nowe konto&amp;amp;nbsp;na które możesz się zalogować używając poniższych danych:&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Login:&amp;amp;nbsp;&amp;lt;/strong&amp;gt;[login]&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Hasło:&amp;lt;/strong&amp;gt; [password]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Logowanie dostępne z adresu:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[login-url]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;u&amp;gt;Pamiętaj, aby w ramach bezpieczeństwa&amp;amp;nbsp;zmienić&amp;amp;nbsp;podane hasło na nowe już przy pierwszym logowaniu !&amp;lt;/u&amp;gt;', 'Treść wiadomości e-mail wysyłana po utworzeniu nowego konta użytkownika.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasło do konta', 'HTML');
INSERT INTO acc_emails_en VALUES(6, 'admin-password-change', 'Witaj [login],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nHasło do Twojego konta zostało zmienione pomyślnie.&amp;lt;br /&amp;gt;\r\nOd teraz logowanie do panelu będzie wymagało podania nowego adresu e-mail.\r\nNowe hasło: [password]', 'Treść wiadomości e-mail wysyłana po zmianie hasła do konta.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasło do konta', 'HTML');
INSERT INTO acc_emails_en VALUES(7, 'user-activated-account', 'Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nTwoje konto jest już aktywne. Możesz się zalogować, podając swoje dane w formualrzu na stronie:&amp;lt;br /&amp;gt;\r\n[login-url]', 'Treść wiadomości e-mail wysyłana po aktywacji konta&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia adres do strony logowania użytkownika', 'HTML');
INSERT INTO acc_emails_en VALUES(8, 'daily-view-expire', '&amp;lt;p&amp;gt;Odnów wyświetlanie obiektów w naszym serwisie:&amp;lt;/p&amp;gt;\r\n\r\n[objects]\r\n\r\n&amp;lt;br/&amp;gt;\r\n&amp;lt;p&amp;gt;Aby Twoje obiekty dalej wyświetlały się w naszym serwisie - zaloguj się na swoje konto, a następnie wybierz opcję &amp;lt;b&amp;gt;wykup&amp;lt;/b&amp;gt; wyświetlanie.&amp;lt;br/&amp;gt;Możesz się zalogować do systemu &amp;lt;a class=&amp;quot;btn&amp;quot; href=&amp;quot;[app_url]/panel/login&amp;quot;&amp;gt;przejdź do strony logowania&amp;lt;/a&amp;gt;', 'Treść wiadomośc e-maili wysyłana w przypadku wygasania ważności wyświetlania obiektu.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, których informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu', 'HTML');
INSERT INTO acc_emails_en VALUES(9, 'daily-main-expire', '<p>Odnów promocję na stronie głównej w naszym serwisie:</p>\r\n\r\n[objects]\r\n\r\n<br/>\r\n<p>Aby Twoje obiekty dalej były promowane na stronie głównej w naszym serwisie - zaloguj się na swoje konto, a następnie wybierz opcję <b>wykup</b> promocję na stronie głównej.<br/>Możesz się zalogować do systemu <a class="btn" href="[app_url]/panel/login">przejdź do strony logowania</a></p>', 'Treść wiadomośc e-maili wysyłana w przypadku wygasania ważności promocji na stronie głównej wygasa.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, których informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu', 'HTML');
INSERT INTO acc_emails_en VALUES(10, 'daily-search-expire', '<p>Odnów wyróżnienie w wynikach wyszukiwania w naszym serwisie:</p>\r\n\r\n[objects]\r\n\r\n<br/>\r\n<p>Aby Twoje obiekty dalej były wyróżnione w wynikach wyszukiwania w naszym serwisie - zaloguj się na swoje konto, a następnie wybierz opcję <b>wykup</b> wyróżnienie w wynikach wyszukiwania.<br/>Możesz się zalogować do systemu <a class="btn" href="[app_url]/panel/login">przejdź do strony logowania</a></p>', 'Treść wiadomośc e-maili wysyłana w przypadku wygasania ważności wyróżnienia w wynikach wyszukiwania wygasa.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, których informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu', 'HTML');


DROP TABLE IF EXISTS acc_emails_pl;
CREATE TABLE acc_emails_pl (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `value` longtext COLLATE utf8_polish_ci NOT NULL,
  `description` longtext COLLATE utf8_polish_ci NOT NULL,
  `type` enum('HTML','PLAIN') COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


ALTER TABLE acc_emails_pl ADD PRIMARY KEY (`id`), ADD KEY `name` (`name`);
ALTER TABLE acc_emails_pl MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

INSERT INTO acc_emails_pl VALUES(1, 'user-password-change', 'Witaj [login],&amp;lt;br /&amp;gt;\n&amp;lt;br /&amp;gt;\nHasło do Twojego konta zostało zmienione pomyślnie.&amp;lt;br /&amp;gt;\nOd teraz logowanie do panelu będzie wymagało podania nowego adresu e-mail.\nNowe hasło: [password]', 'Treść wiadomości e-mail wysyłana po zmianie hasła do konta.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasło do konta', 'HTML');
INSERT INTO acc_emails_pl VALUES(2, 'user-new-account', 'Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nDziękujemy za założenie konta w naszym serwisie. Aby się zalogować prosimy najpierw kliknąć w poniższy link w celu aktywacji konta:&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[activate-url]&amp;quot;&amp;gt;[activate-url]&amp;lt;/a&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nPo aktywowaniu konta logowanie będzie aktywne. Poniżej dane oraz link bezpośredni do strony logowania.&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Login:&amp;amp;nbsp;&amp;lt;/strong&amp;gt;[login]&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Hasło:&amp;lt;/strong&amp;gt; [password]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Logowanie dostępne z adresu:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[login-url]&amp;quot;&amp;gt;[login-url]&amp;lt;/a&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;u&amp;gt;Pamiętaj, aby w ramach bezpieczeństwa&amp;amp;nbsp;zmienić&amp;amp;nbsp;podane hasło na nowe już przy pierwszym logowaniu !&amp;lt;/u&amp;gt;', 'Treść wiadomości e-mail wysyłana po utworzeniu nowego konta użytkownika przez formularz rejestracji na stronie&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasło do konta&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[activate-url]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia link aktywacyjny&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia link do logowania do panelu', 'HTML');
INSERT INTO acc_emails_pl VALUES(3, 'request-photos', '&amp;lt;p&amp;gt;Witaj,&amp;lt;br /&amp;gt;\r\nUżytkownik naszego serwisu prosi Ciebie o dodanie zdjęć do oferty:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[object-link]&amp;quot;&amp;gt;[object-link]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Pamiętaj, że oferta jest skuteczniejsza jeżeli zawiera realne zdjęcia co znacząco zwiększa ilość potencjalnych klientów&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;h5&amp;gt;Jak dodać zdjęcia&amp;lt;/h5&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Aby dodać zdjęcia, zaloguj się na swoim profilu w naszym serwisie podając swój login i hasło, a następnie przy wybranej ofercie kliknij na przycisk zdjęcia.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Logowanie do serwisu: &amp;lt;a href=&amp;quot;[login-url]&amp;quot;&amp;gt;[login-url]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Pozdrawiamy&amp;lt;/p&amp;gt;\r\n', 'Treść wysyłana gdy ktoś kliknie na stronie obiektu przycisk&amp;amp;nbsp;&amp;lt;strong&amp;gt;poproś o dodanie zdjęć.&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-name]&amp;lt;/strong&amp;gt; - wstawia nazwę obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-link]&amp;lt;/strong&amp;gt; - wstawia link do obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;lt;/strong&amp;gt; - wstaiwa adres url do zalogowania się', 'HTML');
INSERT INTO acc_emails_pl VALUES(4, 'object-contact-msg', '&amp;lt;p&amp;gt;Witaj,&amp;lt;br /&amp;gt;\r\nZostało wysłane do Ciebie zapytanie dotyczące Twojej oferty:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;lt;br /&amp;gt;\r\n&amp;lt;a href=&amp;quot;[object-link]&amp;quot;&amp;gt;[object-link]&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;table&amp;gt;\r\n	&amp;lt;tbody&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Nadawca:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[name]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Adres e-mail:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[email]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n		&amp;lt;tr&amp;gt;\r\n			&amp;lt;td&amp;gt;Telefon:&amp;lt;/td&amp;gt;\r\n			&amp;lt;td&amp;gt;[phone]&amp;lt;/td&amp;gt;\r\n		&amp;lt;/tr&amp;gt;\r\n	&amp;lt;/tbody&amp;gt;\r\n&amp;lt;/table&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;&amp;lt;b&amp;gt;Treść wiadomości:&amp;lt;/b&amp;gt;&amp;lt;br /&amp;gt;\r\n[text]&amp;lt;/p&amp;gt;\r\n', 'Treść wiadomości wysyłana po uzupełnieniu formularza kontaktowego obiektu.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;br /&amp;gt;\r\n[object-name]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia nazwę obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[object-link]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia link do obiektu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[name]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia imię i nazwisko nadawcy podane w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wstawia adres e-mail nadawcy podany w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[phone]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia numer telefonu podany w formularzu&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[text]&amp;lt;/strong&amp;gt;&amp;amp;nbsp;- wprowadza treść podaną w formularzu', 'HTML');
INSERT INTO acc_emails_pl VALUES(5, 'admin-new-account', 'Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nAdministrator utworzył nowe konto&amp;amp;nbsp;na które możesz się zalogować używając poniższych danych:&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Login:&amp;amp;nbsp;&amp;lt;/strong&amp;gt;[login]&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Hasło:&amp;lt;/strong&amp;gt; [password]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Logowanie dostępne z adresu:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[login-url]&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;u&amp;gt;Pamiętaj, aby w ramach bezpieczeństwa&amp;amp;nbsp;zmienić&amp;amp;nbsp;podane hasło na nowe już przy pierwszym logowaniu !&amp;lt;/u&amp;gt;', 'Treść wiadomości e-mail wysyłana po utworzeniu nowego konta użytkownika.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasło do konta', 'HTML');
INSERT INTO acc_emails_pl VALUES(6, 'admin-password-change', 'Witaj [login],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nHasło do Twojego konta zostało zmienione pomyślnie.&amp;lt;br /&amp;gt;\r\nOd teraz logowanie do panelu będzie wymagało podania nowego adresu e-mail.\r\nNowe hasło: [password]', 'Treść wiadomości e-mail wysyłana po zmianie hasła do konta.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia login użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[email]&amp;lt;/strong&amp;gt; - wstawia adres e-mail użytkownika&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[password]&amp;lt;/strong&amp;gt; - wstawia hasło do konta', 'HTML');
INSERT INTO acc_emails_pl VALUES(7, 'user-activated-account', 'Witaj [name],&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\nTwoje konto jest już aktywne. Możesz się zalogować, podając swoje dane w formualrzu na stronie:&amp;lt;br /&amp;gt;\r\n[login-url]', 'Treść wiadomości e-mail wysyłana po aktywacji konta&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;[login-url]&amp;amp;nbsp;&amp;lt;/strong&amp;gt;- wstawia adres do strony logowania użytkownika', 'HTML');
INSERT INTO acc_emails_pl VALUES(8, 'daily-view-expire', '&amp;lt;p&amp;gt;Odnów wyświetlanie obiektów w naszym serwisie:&amp;lt;/p&amp;gt;\r\n\r\n[objects]\r\n\r\n&amp;lt;br/&amp;gt;\r\n&amp;lt;p&amp;gt;Aby Twoje obiekty dalej wyświetlały się w naszym serwisie - zaloguj się na swoje konto, a następnie wybierz opcję &amp;lt;b&amp;gt;wykup&amp;lt;/b&amp;gt; wyświetlanie.&amp;lt;br/&amp;gt;Możesz się zalogować do systemu &amp;lt;a class=&amp;quot;btn&amp;quot; href=&amp;quot;[app_url]/panel/login&amp;quot;&amp;gt;przejdź do strony logowania&amp;lt;/a&amp;gt;', 'Treść wiadomośc e-maili wysyłana w przypadku wygasania ważności wyświetlania obiektu.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, których informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu', 'HTML');
INSERT INTO acc_emails_pl VALUES(9, 'daily-main-expire', '<p>Odnów promocję na stronie głównej w naszym serwisie:</p>\r\n\r\n[objects]\r\n\r\n<br/>\r\n<p>Aby Twoje obiekty dalej były promowane na stronie głównej w naszym serwisie - zaloguj się na swoje konto, a następnie wybierz opcję <b>wykup</b> promocję na stronie głównej.<br/>Możesz się zalogować do systemu <a class="btn" href="[app_url]/panel/login">przejdź do strony logowania</a></p>', 'Treść wiadomośc e-maili wysyłana w przypadku wygasania ważności promocji na stronie głównej wygasa.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, których informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu', 'HTML');
INSERT INTO acc_emails_pl VALUES(10, 'daily-search-expire', '<p>Odnów wyróżnienie w wynikach wyszukiwania w naszym serwisie:</p>\r\n\r\n[objects]\r\n\r\n<br/>\r\n<p>Aby Twoje obiekty dalej były wyróżnione w wynikach wyszukiwania w naszym serwisie - zaloguj się na swoje konto, a następnie wybierz opcję <b>wykup</b> wyróżnienie w wynikach wyszukiwania.<br/>Możesz się zalogować do systemu <a class="btn" href="[app_url]/panel/login">przejdź do strony logowania</a></p>', 'Treść wiadomośc e-maili wysyłana w przypadku wygasania ważności wyróżnienia w wynikach wyszukiwania wygasa.&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Legenda:&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\n[objects] - wstawia obiekty, których informacja dotyczy&amp;lt;br /&amp;gt;\r\n[app_url] - wstawia adres url serwisu', 'HTML');

DROP TABLE IF EXISTS `acc_equipment_en`;
CREATE TABLE `acc_equipment_en` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_equipment_en` ADD PRIMARY KEY (`id`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_equipment_en` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

INSERT INTO acc_equipment_en VALUES(1, 'The kitchenette', 'the-kitchenette');
INSERT INTO acc_equipment_en VALUES(2, 'Balcony', 'balcony');
INSERT INTO acc_equipment_en VALUES(3, 'Teapot', 'teapot');
INSERT INTO acc_equipment_en VALUES(4, 'Grill', 'grill');
INSERT INTO acc_equipment_en VALUES(5, 'Fridge', 'fridge');
INSERT INTO acc_equipment_en VALUES(6, 'Washer', 'washer');
INSERT INTO acc_equipment_en VALUES(7, 'Iron', 'iron');
INSERT INTO acc_equipment_en VALUES(8, 'TV', 'tv');
INSERT INTO acc_equipment_en VALUES(9, 'Radio', 'radio');
INSERT INTO acc_equipment_en VALUES(10, 'Internet WiFi', 'internet-wifi');
INSERT INTO acc_equipment_en VALUES(11, 'Internet LAN', 'internet-lan');

DROP TABLE IF EXISTS `acc_equipment_pl`;
CREATE TABLE `acc_equipment_pl` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_equipment_pl` ADD PRIMARY KEY (`id`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_equipment_pl` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

INSERT INTO acc_equipment_pl VALUES(1, 'Aneks kuchenny', 'aneks-kuchenny');
INSERT INTO acc_equipment_pl VALUES(2, 'Balkon', 'balkon');
INSERT INTO acc_equipment_pl VALUES(3, 'Czajnik', 'czajnik');
INSERT INTO acc_equipment_pl VALUES(4, 'Grill', 'grill');
INSERT INTO acc_equipment_pl VALUES(5, 'Lodówka', 'lodowka');
INSERT INTO acc_equipment_pl VALUES(6, 'Pralka', 'pralka');
INSERT INTO acc_equipment_pl VALUES(7, 'Żelazko', 'zelazko');
INSERT INTO acc_equipment_pl VALUES(8, 'Telewizor', 'telewizor');
INSERT INTO acc_equipment_pl VALUES(9, 'Radio', 'radio');
INSERT INTO acc_equipment_pl VALUES(10, 'Internet WiFi', 'internet-wifi');
INSERT INTO acc_equipment_pl VALUES(11, 'Internet LAN', 'internet-lan');

DROP TABLE IF EXISTS `acc_improvement_en`;
CREATE TABLE `acc_improvement_en` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_improvement_en` ADD PRIMARY KEY (`id`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_improvement_en` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

INSERT INTO acc_improvement_en VALUES(1, 'Playground', 'playground');
INSERT INTO acc_improvement_en VALUES(2, 'Arbor', 'arbor');
INSERT INTO acc_improvement_en VALUES(3, 'Swing', 'swing');
INSERT INTO acc_improvement_en VALUES(4, 'Pool', 'pool');
INSERT INTO acc_improvement_en VALUES(5, 'Sauna', 'sauna');
INSERT INTO acc_improvement_en VALUES(6, 'Jacuzzi', 'jacuzzi');
INSERT INTO acc_improvement_en VALUES(7, 'SPA', 'spa');
INSERT INTO acc_improvement_en VALUES(8, 'Restaurant', 'restaurant');
INSERT INTO acc_improvement_en VALUES(9, 'Dining Room', 'dining-room');
INSERT INTO acc_improvement_en VALUES(10, 'Tennis Court', 'tennis-court');
INSERT INTO acc_improvement_en VALUES(11, 'Fire place', 'fire-place');
INSERT INTO acc_improvement_en VALUES(12, 'Grill', 'grill');
INSERT INTO acc_improvement_en VALUES(13, 'Parking', 'parking');
INSERT INTO acc_improvement_en VALUES(14, 'Elevator', 'elevator');
INSERT INTO acc_improvement_en VALUES(15, 'Bicycle Rental', 'bicycle-rental');
INSERT INTO acc_improvement_en VALUES(16, 'Rental of beach equipment', 'rental-of-beach-equipment');
INSERT INTO acc_improvement_en VALUES(17, 'Ski equipment hire', 'ski-equipment-hire');

DROP TABLE IF EXISTS `acc_improvement_pl`;
CREATE TABLE `acc_improvement_pl` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_improvement_pl` ADD PRIMARY KEY (`id`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_improvement_pl` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

INSERT INTO acc_improvement_pl VALUES(1, 'Plac zabaw dla dzieci', 'plac-zabaw-dla-dzieci');
INSERT INTO acc_improvement_pl VALUES(2, 'Altanka', 'altanka');
INSERT INTO acc_improvement_pl VALUES(3, 'Huśtawka', 'hustawka');
INSERT INTO acc_improvement_pl VALUES(4, 'Basen', 'basen');
INSERT INTO acc_improvement_pl VALUES(5, 'Sauna', 'sauna');
INSERT INTO acc_improvement_pl VALUES(6, 'Jacuzzi', 'jacuzzi');
INSERT INTO acc_improvement_pl VALUES(7, 'SPA', 'spa');
INSERT INTO acc_improvement_pl VALUES(8, 'Restauracja', 'restauracja');
INSERT INTO acc_improvement_pl VALUES(9, 'Jadalnia', 'jadalnia');
INSERT INTO acc_improvement_pl VALUES(10, 'Kort tenisowy', 'kort-tenisowy');
INSERT INTO acc_improvement_pl VALUES(11, 'Miejsce na ognisko', 'miejsce-na-ognisko');
INSERT INTO acc_improvement_pl VALUES(12, 'Miejsce na grill', 'miejsce-na-grill');
INSERT INTO acc_improvement_pl VALUES(13, 'Parking', 'parking');
INSERT INTO acc_improvement_pl VALUES(14, 'Winda', 'winda');
INSERT INTO acc_improvement_pl VALUES(15, 'Wypożyczalnia rowerów', 'wypozyczalnia-rowerow');
INSERT INTO acc_improvement_pl VALUES(16, 'Wypożyczalnia sprzętu plażowego', 'wypozyczalnia-sprzetu-plazowego');
INSERT INTO acc_improvement_pl VALUES(17, 'Wypożyczania sprzętu narciarskiego', 'wypozyczania-sprzetu-narciarskiego');

DROP TABLE IF EXISTS `acc_languages_en`;
CREATE TABLE `acc_languages_en` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_languages_en` ADD PRIMARY KEY (`id`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_languages_en` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `acc_languages_pl`;
CREATE TABLE `acc_languages_pl` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_languages_pl` ADD PRIMARY KEY (`id`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_languages_pl` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `acc_locations_en`;
CREATE TABLE `acc_locations_en` (
  `id` int(11) unsigned NOT NULL,
  `show_main` enum('TRUE','FALSE') COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_locations_en` ADD PRIMARY KEY (`id`), ADD KEY `show_main` (`show_main`);
ALTER TABLE `acc_locations_en` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

INSERT INTO acc_locations_en VALUES(1, 'TRUE', 'Mountain', 'mountain', 'gory.jpg');
INSERT INTO acc_locations_en VALUES(2, 'TRUE', 'Forest', 'forest', 'las.jpg');
INSERT INTO acc_locations_en VALUES(3, 'TRUE', 'Lake', 'Lake', 'jezioro.jpg');
INSERT INTO acc_locations_en VALUES(4, 'TRUE', 'Sea', 'sea', 'morze.jpg');
INSERT INTO acc_locations_en VALUES(5, 'FALSE', 'Country', 'country', NULL);
INSERT INTO acc_locations_en VALUES(6, 'FALSE', 'City', 'city', NULL);
INSERT INTO acc_locations_en VALUES(7, 'FALSE', 'River', 'river', NULL);

DROP TABLE IF EXISTS `acc_locations_pl`;
CREATE TABLE `acc_locations_pl` (
  `id` int(11) unsigned NOT NULL,
  `show_main` enum('TRUE','FALSE') COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_locations_pl` ADD PRIMARY KEY (`id`), ADD KEY `show_main` (`show_main`);
ALTER TABLE `acc_locations_pl` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

INSERT INTO acc_locations_pl VALUES(1, 'TRUE', 'W górach', 'w-gorach', 'gory.jpg');
INSERT INTO acc_locations_pl VALUES(2, 'TRUE', 'W lesie', 'w-lesie', 'las.jpg');
INSERT INTO acc_locations_pl VALUES(3, 'TRUE', 'Nad jeziorem', 'nad-jeziorem', 'jezioro.jpg');
INSERT INTO acc_locations_pl VALUES(4, 'TRUE', 'Nad morzem', 'nad-morzem', 'morze.jpg');
INSERT INTO acc_locations_pl VALUES(5, 'FALSE', 'Na wsi', 'na-wsi', NULL);
INSERT INTO acc_locations_pl VALUES(6, 'FALSE', 'W mieście', 'w-miescie', NULL);
INSERT INTO acc_locations_pl VALUES(7, 'FALSE', 'Nad rzeką', 'nad-rzeka', NULL);

DROP TABLE IF EXISTS `acc_news_en`;
CREATE TABLE `acc_news_en` (
  `id` int(11) NOT NULL,
  `topic` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `preface` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` INT(11) UNSIGNED NOT NULL,
  `main` ENUM("FALSE","TRUE") NOT NULL,
  `create_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `acc_news_en` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_news_en` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `acc_news_pl`;
CREATE TABLE `acc_news_pl` (
  `id` int(11) NOT NULL,
  `topic` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `preface` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` INT(11) UNSIGNED NOT NULL,
  `main` ENUM("FALSE","TRUE") NOT NULL,
  `create_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `acc_news_pl` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_news_pl` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `acc_news_category_pl`;
CREATE TABLE `acc_news_category_pl` (
  `id` int(11) UNSIGNED NOT NULL,
  `priority` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `name_rw` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_news_category_pl` ADD PRIMARY KEY (`id`), ADD KEY `priority` (`priority`), ADD KEY `parent` (`parent`);
ALTER TABLE `acc_news_category_pl` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

DROP TABLE IF EXISTS `acc_news_category_en`;
CREATE TABLE `acc_news_category_en` (
  `id` int(11) UNSIGNED NOT NULL,
  `priority` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `name_rw` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_news_category_en` ADD PRIMARY KEY (`id`), ADD KEY `priority` (`priority`), ADD KEY `parent` (`parent`);
ALTER TABLE `acc_news_category_en` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

DROP TABLE IF EXISTS `acc_news_gallery_pl`;
CREATE TABLE acc_news_gallery_pl (
  id int(11) UNSIGNED NOT NULL,
  news_id int(11) UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  priority int(11) NOT NULL,
  photo varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  create_date date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE acc_news_gallery_pl ADD PRIMARY KEY (id);
ALTER TABLE acc_news_gallery_pl MODIFY id int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `acc_news_gallery_en`;
CREATE TABLE acc_news_gallery_en (
  id int(11) UNSIGNED NOT NULL,
  news_id int(11) UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  priority int(11) NOT NULL,
  photo varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  create_date date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_news_gallery_en ADD PRIMARY KEY (id);
ALTER TABLE acc_news_gallery_en MODIFY id int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `acc_objects`;
CREATE TABLE `acc_objects` (
  `id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8_unicode_ci NOT NULL,
  `long_description` text COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postcode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city_rw` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` int(11) unsigned DEFAULT NULL,
  `country` int(11) unsigned DEFAULT NULL,
  `district` int(11) unsigned DEFAULT NULL,
  `type` int(11) unsigned NOT NULL,
  `location` int(11) unsigned NOT NULL,
  `distance` text COLLATE utf8_unicode_ci NOT NULL,
  `improvements` text COLLATE utf8_unicode_ci NOT NULL,
  `map_lat` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `map_lng` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `map_zoom` smallint(10) NOT NULL,
  `phone` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `www` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `view_expire` date DEFAULT NULL,
  `search_expire` date DEFAULT NULL,
  `main_expire` date DEFAULT NULL,
  `create_date` date NOT NULL,
  `update_date` date DEFAULT NULL,
  `status` enum('PENDING','DISABLED','ACTIVE') COLLATE utf8_unicode_ci NOT NULL,
  `plus` int(11) unsigned DEFAULT NULL,
  `minus` int(11) unsigned DEFAULT NULL,
  `booking` enum('FALSE','TRUE') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `acc_objects` ADD PRIMARY KEY (`id`), ADD KEY `uid` (`uid`), ADD KEY `state` (`state`), ADD KEY `country` (`country`), ADD KEY `type` (`type`), ADD KEY `view_expire` (`view_expire`), ADD KEY `search_expire` (`search_expire`), ADD KEY `main_expire` (`main_expire`), ADD KEY `status` (`status`);
ALTER TABLE `acc_objects` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `acc_objects_comments`;
CREATE TABLE `acc_objects_comments` (
  `id` int(11) unsigned NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `text_corrected` text COLLATE utf8_unicode_ci,
  `create_date` date NOT NULL,
  `rank` smallint(1) NOT NULL,
  `status` enum('PENDING','ACTIVE','MARK-TO-DELETE','DISABLED') COLLATE utf8_unicode_ci NOT NULL,
  `helpful` int(11) unsigned NOT NULL,
  `unhelpful` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_objects_comments` ADD PRIMARY KEY (`id`), ADD KEY `object_id` (`object_id`), ADD KEY `uid` (`uid`), ADD KEY `status` (`status`);
ALTER TABLE `acc_objects_comments` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `acc_objects_photos`;
CREATE TABLE `acc_objects_photos` (
  `id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `priority` int(11) unsigned NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
  `create_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_objects_photos` ADD PRIMARY KEY (`id`), ADD KEY `uid` (`uid`), ADD KEY `object_id` (`object_id`), ADD KEY `main` (`main`);
ALTER TABLE `acc_objects_photos` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `acc_objects_videos`;
CREATE TABLE `acc_objects_videos` (
  `id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `create_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_objects_videos` ADD PRIMARY KEY (`id`), ADD KEY `object_id` (`object_id`), ADD KEY `uid` (`uid`);
ALTER TABLE `acc_objects_videos` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_partner;
CREATE TABLE acc_partner (
  id int(11) unsigned NOT NULL,
  name varchar(255) NOT NULL,
  link varchar(255) DEFAULT NULL,
  priority int(11) unsigned NOT NULL,
  photo varchar(255) NOT NULL,
  create_date date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_partner` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_partner` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_payment_history;
CREATE TABLE acc_payment_history (
  id int(11) unsigned NOT NULL,
  create_date datetime NOT NULL,
  object_id int(11) unsigned NOT NULL,
  special_id int(11) unsigned NULL,
  promotion_id int(11) unsigned NOT NULL,
  type enum('ONLINE','SMS') NOT NULL,
  status enum('NEW','CONFIRM','REFUSED','CANCEL') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_payment_history ADD PRIMARY KEY (`id`), ADD KEY `object_id` (`object_id`), ADD KEY `promotion_id` (`promotion_id`);
ALTER TABLE acc_payment_history MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_promotion;
CREATE TABLE acc_promotion (
  id int(11) unsigned NOT NULL,
  what enum('VIEW','SEARCH','MAIN','SPECIAL') NOT NULL,
  name varchar(200) NOT NULL,
  description varchar(200) DEFAULT NULL,
  days smallint(11) unsigned NOT NULL,
  type set('ONLINE','SMS') NOT NULL,
  amount_online double(10,2) NOT NULL,
  amount_sms double(10,2) DEFAULT NULL,
  sms_number mediumint(10) unsigned DEFAULT NULL,
  sms_text varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_promotion ADD PRIMARY KEY (`id`), ADD KEY `what` (`what`), ADD KEY `type` (`type`);
ALTER TABLE acc_promotion MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_rooms;
CREATE TABLE acc_rooms (
  `id` int(11) unsigned NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `persons` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `amount` double(10,2) NOT NULL,
  `amount_type` TINYINT(1) NOT NULL,
  `equipment` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_rooms ADD PRIMARY KEY (`id`), ADD KEY `object_id` (`object_id`);
ALTER TABLE acc_rooms MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_rooms_photos;
CREATE TABLE acc_rooms_photos (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL,
  `room_id` int(11) UNSIGNED NOT NULL,
  `priority` INT(11) UNSIGNED NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `main` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
  `create_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_rooms_photos` ADD PRIMARY KEY (`id`), ADD KEY `uid` (`uid`), ADD KEY `room_id` (`room_id`);
ALTER TABLE `acc_rooms_photos` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_slider;
CREATE TABLE acc_slider (
  id int(11) unsigned NOT NULL,
  name varchar(255) NOT NULL,
  text text  COLLATE utf8_unicode_ci NOT NULL,
  link varchar(255) DEFAULT NULL,
  priority int(11) unsigned NOT NULL,
  photo varchar(255) NOT NULL,
  create_date date NOT NULL,
  display_start date DEFAULT NULL,
  display_end date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO acc_slider VALUES(1, 'Zima', '', '', 1, '1476383110.jpg', '2016-10-02', '2000-01-02', '2000-03-21');
INSERT INTO acc_slider VALUES(2, 'Wiosna', '', '', 2, '1476383137.jpg', '2016-10-02', '2000-03-21', '2000-05-31');
INSERT INTO acc_slider VALUES(3, 'Lato', '', '', 5, '1476383162.jpg', '2016-10-02', '2000-06-01', '2000-09-30');
INSERT INTO acc_slider VALUES(4, 'Jesień', '', '', 6, '1476383199.jpg', '2016-10-02', '2000-10-01', '2000-12-17');

ALTER TABLE acc_slider ADD PRIMARY KEY (`id`), ADD KEY `priority` (`priority`);
ALTER TABLE acc_slider MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_states_en;
CREATE TABLE acc_states_en (
  id int(11) unsigned NOT NULL,
  country int(11) unsigned NOT NULL,
  name varchar(200) NOT NULL,
  rewrite varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_states_en` ADD PRIMARY KEY (`id`), ADD KEY `country` (`country`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_states_en` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

INSERT INTO acc_states_en VALUES(1, 1, 'Dolnośląskie', 'dolnoslaskie');
INSERT INTO acc_states_en VALUES(2, 1, 'Kujawsko-Pomorskie', 'kujawsko-pomorskie');
INSERT INTO acc_states_en VALUES(3, 1, 'Lubelskie', 'lubelskie');
INSERT INTO acc_states_en VALUES(4, 1, 'Lubuskie', 'lubuskie');
INSERT INTO acc_states_en VALUES(5, 1, 'Łódzkie', 'lodzkie');
INSERT INTO acc_states_en VALUES(6, 1, 'Małopolskie', 'malopolskie');
INSERT INTO acc_states_en VALUES(7, 1, 'Mazowieckie', 'mazowieckie');
INSERT INTO acc_states_en VALUES(8, 1, 'Opolskie', 'opolskie');
INSERT INTO acc_states_en VALUES(9, 1, 'Podkarpackie', 'podkarpackie');
INSERT INTO acc_states_en VALUES(10, 1, 'Podlaskie', 'podlaskie');
INSERT INTO acc_states_en VALUES(11, 1, 'Pomorskie', 'pomorskie');
INSERT INTO acc_states_en VALUES(12, 1, 'Śląskie', 'slaskie');
INSERT INTO acc_states_en VALUES(13, 1, 'Świętokrzyskie', 'swietokrzyskie');
INSERT INTO acc_states_en VALUES(14, 1, 'Warmińsko-mazurskie', 'warminsko-mazurskie');
INSERT INTO acc_states_en VALUES(15, 1, 'Wielkopolskie', 'wielkopolskie');
INSERT INTO acc_states_en VALUES(16, 1, 'Zachodniopomorskie', 'zachodniopomorskie');

DROP TABLE IF EXISTS acc_states_pl;
CREATE TABLE acc_states_pl (
  id int(11) unsigned NOT NULL,
  country int(11) unsigned NOT NULL,
  name varchar(200) NOT NULL,
  rewrite varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_states_pl` ADD PRIMARY KEY (`id`), ADD KEY `country` (`country`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_states_pl` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

INSERT INTO acc_states_pl VALUES(1, 1, 'Dolnośląskie', 'dolnoslaskie');
INSERT INTO acc_states_pl VALUES(2, 1, 'Kujawsko-Pomorskie', 'kujawsko-pomorskie');
INSERT INTO acc_states_pl VALUES(3, 1, 'Lubelskie', 'lubelskie');
INSERT INTO acc_states_pl VALUES(4, 1, 'Lubuskie', 'lubuskie');
INSERT INTO acc_states_pl VALUES(5, 1, 'Łódzkie', 'lodzkie');
INSERT INTO acc_states_pl VALUES(6, 1, 'Małopolskie', 'malopolskie');
INSERT INTO acc_states_pl VALUES(7, 1, 'Mazowieckie', 'mazowieckie');
INSERT INTO acc_states_pl VALUES(8, 1, 'Opolskie', 'opolskie');
INSERT INTO acc_states_pl VALUES(9, 1, 'Podkarpackie', 'podkarpackie');
INSERT INTO acc_states_pl VALUES(10, 1, 'Podlaskie', 'podlaskie');
INSERT INTO acc_states_pl VALUES(11, 1, 'Pomorskie', 'pomorskie');
INSERT INTO acc_states_pl VALUES(12, 1, 'Śląskie', 'slaskie');
INSERT INTO acc_states_pl VALUES(13, 1, 'Świętokrzyskie', 'swietokrzyskie');
INSERT INTO acc_states_pl VALUES(14, 1, 'Warmińsko-mazurskie', 'warminsko-mazurskie');
INSERT INTO acc_states_pl VALUES(15, 1, 'Wielkopolskie', 'wielkopolskie');
INSERT INTO acc_states_pl VALUES(16, 1, 'Zachodniopomorskie', 'zachodniopomorskie');

DROP TABLE IF EXISTS `acc_texts_en`;
CREATE TABLE `acc_texts_en` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_texts_en` ADD PRIMARY KEY (`id`), ADD KEY `name` (`name`);
ALTER TABLE `acc_texts_en` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

INSERT INTO acc_texts_en VALUES(1, 'bottom-about', 'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum.', 'Treść wspomagająca pozycjonowanie widoczna w stopce');
INSERT INTO acc_texts_en VALUES(2, 'comments-list', '<p class="lead">W tym miejscu wyświetlane są opinie dodane przez użytkowników tego serwisu. Jako <b>użytkownik premium</b> masz możliwość ich edycji, oraz oznaczania do usunięcia. Prosimy o korzystanie z przycisku <b>oznacz do usunięcia</b> z rozwagą. O usunięciu negatywnego komentarza <u>decyduje moderator serwisu</u>.</p>', 'Treść widoczna na podglądzie komentarzy w panelu użytkownika');
INSERT INTO acc_texts_en VALUES(3, 'user-no-account', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget bibendum nisi, quis maximus urna. Fusce eget nulla euismod lectus faucibus consequat quis quis eros.', 'Treść widoczna na stronie logowania pod nagłówkiem:&amp;amp;nbsp;&amp;lt;strong&amp;gt;Nie mam jeszcze konta&amp;lt;/strong&amp;gt;');
INSERT INTO acc_texts_en VALUES(4, 'user-password-reset', 'Uzupełnij poniższy formularz, aby zmienić hasło do swojego konta\n', 'Treść widoczna na stronie odzyskiwania hasła');
INSERT INTO acc_texts_en VALUES(5, 'user-register-personal-data-protection-1', '&lt;p&gt;&lt;small&gt;Udostępniane dane są chronione zgodne z Ustawą o ochronie danych osobowych. [nazwa-firmy]. (z siedzibą w [firma-adres]) jest administratorem bazy danych osobowych. Udostępniający ma prawo do wglądu, zmiany i usunięcia danych osobowych z bazy [nazwa-firmy]. Udostępnianie danych jest dobrowolne.&lt;/small&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;small&gt;Oświadczam, że zostałem poinformowany o przysługujących mi prawach i wyrażam zgodę na przechowywanie i przetwarzanie w tym również dla celów marketingowych, przez [nazwa-firmy]. lub inny podmiot związany umowa z [nazwa-firmy]. moich danych osobowych.&lt;/small&gt;&lt;/p&gt;\r\n', 'Treść widoczna na podstronie rejestracji nowego konta: Ochrona danych osobowych');
INSERT INTO acc_texts_en VALUES(6, 'user-register-succesfully', '<p class="lead">Wysłaliśmy wiadomość e-mail na podany podczas rejestracji przez Ciebie adres e-mail. Sprawdź skrzynkę i potwierdź autentyczność wprowadzonych danych klikając w znajdujący się w wiadomości link aktywacyjny.<br/><br/><span class="text-danger">W przypadku, jeżeli wiadomość nie dotarła do Ciebie w czasie dłuższym niż 10 min, kliknij na opcję zaloguj, wprowadź dane podane podczas rejestracji, a wiadomość e-mail aktywacyjny zostanie wysłany do Ciebie ponownie.</span></p>', 'Treść widoczna po uzupełnieniu formularza rejestracji konta');
INSERT INTO acc_texts_en VALUES(7, 'user-password-reset-succesfully', '<h2 class="title">Hasło zostało pomyślnie zmienione</h2>\r\n<p class="lead">Na Twój adres e-mail wysłaliśmy wiadomość z nowym hasłem, dzięki któremu możesz zalogować się do tego serwisu.</span></p>', 'Treść widoczna po uzupełnieniu formularza odzyskiwania hasła');
INSERT INTO acc_texts_en VALUES(8, 'newsletter-subscribe-confirm', '<p>Your e-mail has been successfully confirmed</p>', 'Treść widoczna na stronie po kliknięciu w link aktywacji newslettera');
INSERT INTO acc_texts_en VALUES(9, 'newsletter-subscribe-error', '<p>E-mail has already been activated</p>', 'Treść widoczna na stronie po kliknięciu w link aktywacji newslettera w momencie, gdy adres e-mail nie istnieje lub gdy został on wcześniej aktywowany.');
INSERT INTO acc_texts_en VALUES(10, 'newsletter-unsubscribe', '<p>Your e-mail has been successfully unsubscribed from the newsletter</p>', 'Treść widoczna na stronie po wypisaniu się z newslettera');
INSERT INTO acc_texts_en VALUES (11, 'room-add-text', '<p class="alert alert-warning">If in a room the recommended number of people is for example 3, however, there is the possibility of accommodation of an additional person, please put such information in the room description.</p>', 'Treść widoczna nad formularzem dodawania / edycji pokoju');
INSERT INTO acc_texts_en VALUES (NULL, 'user-register-personal-data-protection-2', '&lt;p&gt;&lt;small&gt;Udostępniane dane są chronione zgodne z Ustawą o ochronie danych osobowych. [firma-adres] jest administratorem bazy danych osobowych. Udostępniający ma prawo do wglądu, zmiany i usunięcia danych osobowych z bazy [nazwa firmy]. Udostępnianie danych jest dobrowolne.&lt;/small&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;small&gt;Oświadczam, że zostałem poinformowany o przysługujących mi prawach i wyrażam zgodę na przechowywanie i przetwarzanie w tym również dla celów marketingowych, przez [nazwa-firmy]. lub inny podmiot związany umowa z [nazwa-firmy]. moich danych osobowych.&lt;/small&gt;&lt;/p&gt;\r\n', 'Treść widoczna na stronie rejestracji nowego konta pod zgodami. Z natury jest to regułka prawna.');

DROP TABLE IF EXISTS `acc_texts_pl`;
CREATE TABLE `acc_texts_pl` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_texts_pl` ADD PRIMARY KEY (`id`), ADD KEY `name` (`name`);
ALTER TABLE `acc_texts_pl` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

INSERT INTO acc_texts_pl VALUES(1, 'bottom-about', 'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum.', 'Treść wspomagająca pozycjonowanie widoczna w stopce');
INSERT INTO acc_texts_pl VALUES(2, 'comments-list', '<p class="lead">W tym miejscu wyświetlane są opinie dodane przez użytkowników tego serwisu. Jako <b>użytkownik premium</b> masz możliwość ich edycji, oraz oznaczania do usunięcia. Prosimy o korzystanie z przycisku <b>oznacz do usunięcia</b> z rozwagą. O usunięciu negatywnego komentarza <u>decyduje moderator serwisu</u>.</p>', 'Treść widoczna na podglądzie komentarzy w panelu użytkownika');
INSERT INTO acc_texts_pl VALUES(3, 'user-no-account', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget bibendum nisi, quis maximus urna. Fusce eget nulla euismod lectus faucibus consequat quis quis eros.', 'Treść widoczna na stronie logowania pod nagłówkiem:&amp;amp;nbsp;&amp;lt;strong&amp;gt;Nie mam jeszcze konta&amp;lt;/strong&amp;gt;');
INSERT INTO acc_texts_pl VALUES(4, 'user-password-reset', 'Uzupełnij poniższy formularz, aby zmienić hasło do swojego konta\n', 'Treść widoczna na stronie odzyskiwania hasła');
INSERT INTO acc_texts_pl VALUES(5, 'user-register-personal-data-protection', '&lt;p&gt;&lt;small&gt;Udostępniane dane są chronione zgodne z Ustawą o ochronie danych osobowych. [nazwa-firmy]. (z siedzibą w [firma adres]) jest administratorem bazy danych osobowych. Udostępniający ma prawo do wglądu, zmiany i usunięcia danych osobowych z bazy [nazwa-firmy]. Udostępnianie danych jest dobrowolne.&lt;/small&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;small&gt;Oświadczam, że zostałem poinformowany o przysługujących mi prawach i wyrażam zgodę na przechowywanie i przetwarzanie w tym również dla celów marketingowych, przez [nazwa-firmy]. lub inny podmiot związany umowa z [nazwa-firmy]. moich danych osobowych.&lt;/small&gt;&lt;/p&gt;\r\n', 'Treść widoczna na podstronie rejestracji nowego konta: Ochrona danych osobowych');
INSERT INTO acc_texts_pl VALUES(6, 'user-register-succesfully', '<p class="lead">Wysłaliśmy wiadomość e-mail na podany podczas rejestracji przez Ciebie adres e-mail. Sprawdź skrzynkę i potwierdź autentyczność wprowadzonych danych klikając w znajdujący się w wiadomości link aktywacyjny.<br/><br/><span class="text-danger">W przypadku, jeżeli wiadomość nie dotarła do Ciebie w czasie dłuższym niż 10 min, kliknij na opcję zaloguj, wprowadź dane podane podczas rejestracji, a wiadomość e-mail aktywacyjny zostanie wysłany do Ciebie ponownie.</span></p>', 'Treść widoczna po uzupełnieniu formularza rejestracji konta');
INSERT INTO acc_texts_pl VALUES(7, 'user-password-reset-succesfully', '<h2 class="title">Hasło zostało pomyślnie zmienione</h2>\r\n<p class="lead">Na Twój adres e-mail wysłaliśmy wiadomość z nowym hasłem, dzięki któremu możesz zalogować się do tego serwisu.</span></p>', 'Treść widoczna po uzupełnieniu formularza odzyskiwania hasła');
INSERT INTO acc_texts_pl VALUES(8, 'newsletter-subscribe-confirm', '<p>Twój adres e-mail został pomyślnie potwierdzony</p>', 'Treść widoczna na stronie po kliknięciu w link aktywacji newslettera');
INSERT INTO acc_texts_pl VALUES(9, 'newsletter-subscribe-error', '<p>Adres e-mail został już wcześniej aktywowany</p>', 'Treść widoczna na stronie po kliknięciu w link aktywacji newslettera w momencie, gdy adres e-mail nie istnieje lub gdy został on wcześniej aktywowany.');
INSERT INTO acc_texts_pl VALUES (10, 'newsletter-unsubscribe', '<p>Twój adres e-mail został pomyślnie wypisany z newslettera</p>', 'Treść widoczna na stronie po wypisaniu się z newslettera');
INSERT INTO acc_texts_pl VALUES (11, 'room-add-text', '<p class="alert alert-warning">Jeżeli w danym pokoju zalecana ilość osób wynosi np. 3 jednak istnieje możliwość zakwaterowania dodatkowej osoby, należy umieścić taką informację w opisie pokoju..</p>', 'Treść widoczna nad formularzem dodawania / edycji pokoju');
INSERT INTO acc_texts_pl VALUES (NULL, 'user-register-personal-data-protection-2', '&lt;p&gt;&lt;small&gt;Udostępniane dane są chronione zgodne z Ustawą o ochronie danych osobowych. [firma-adres] jest administratorem bazy danych osobowych. Udostępniający ma prawo do wglądu, zmiany i usunięcia danych osobowych z bazy [nazwa firmy]. Udostępnianie danych jest dobrowolne.&lt;/small&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;small&gt;Oświadczam, że zostałem poinformowany o przysługujących mi prawach i wyrażam zgodę na przechowywanie i przetwarzanie w tym również dla celów marketingowych, przez [nazwa-firmy]. lub inny podmiot związany umowa z [nazwa-firmy]. moich danych osobowych.&lt;/small&gt;&lt;/p&gt;\r\n', 'Treść widoczna na stronie rejestracji nowego konta pod zgodami. Z natury jest to regułka prawna.');

DROP TABLE IF EXISTS `acc_types_en`;
CREATE TABLE `acc_types_en` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_types_en` ADD PRIMARY KEY (`id`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_types_en` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

INSERT INTO acc_types_en VALUES(1, 'Agritourism', 'agritourism');
INSERT INTO acc_types_en VALUES(2, 'Apartment', 'apartment');
INSERT INTO acc_types_en VALUES(3, 'Camping', 'camping');
INSERT INTO acc_types_en VALUES(4, 'Cottage', 'cottage');
INSERT INTO acc_types_en VALUES(5, 'Hotel', 'hotel');
INSERT INTO acc_types_en VALUES(6, 'Private flat', 'private-flat');
INSERT INTO acc_types_en VALUES(7, 'Motel', 'motel');
INSERT INTO acc_types_en VALUES(8, 'Resort', 'resort');
INSERT INTO acc_types_en VALUES(9, 'Guesthouse', 'guesthouse');
INSERT INTO acc_types_en VALUES(10, 'Campsite', 'campsite');
INSERT INTO acc_types_en VALUES(11, 'Sanatorium', 'sanatorium');
INSERT INTO acc_types_en VALUES(12, 'Hostel', 'hostel');
INSERT INTO acc_types_en VALUES(13, 'Villa', 'villa');

DROP TABLE IF EXISTS `acc_types_pl`;
CREATE TABLE `acc_types_pl` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_types_pl` ADD PRIMARY KEY (`id`), ADD KEY `rewrite` (`rewrite`);
ALTER TABLE `acc_types_pl` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

INSERT INTO acc_types_pl VALUES(1, 'Agroturystyka', 'agroturystyka');
INSERT INTO acc_types_pl VALUES(2, 'Apartament', 'apartament');
INSERT INTO acc_types_pl VALUES(3, 'Camping', 'camping');
INSERT INTO acc_types_pl VALUES(4, 'Domek', 'domek');
INSERT INTO acc_types_pl VALUES(5, 'Hotel', 'hotel');
INSERT INTO acc_types_pl VALUES(6, 'Kwatera prywatna', 'kwatera-prywatna');
INSERT INTO acc_types_pl VALUES(7, 'Motel', 'motel');
INSERT INTO acc_types_pl VALUES(8, 'Ośrodek wczasowy', 'osrodek-wczasowy');
INSERT INTO acc_types_pl VALUES(9, 'Pensjonat', 'pensjonat');
INSERT INTO acc_types_pl VALUES(10, 'Pole namiotowe', 'pole-namiotowe');
INSERT INTO acc_types_pl VALUES(11, 'Sanatorium', 'sanatorium');
INSERT INTO acc_types_pl VALUES(12, 'Schronisko', 'schronisko');
INSERT INTO acc_types_pl VALUES(13, 'Willa', 'willa');

DROP TABLE IF EXISTS `acc_users`;
CREATE TABLE `acc_users` (
  `id` int(11) unsigned NOT NULL,
  `fb_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pass` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('USER','MOD','ADMIN','SUPPORT') COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
  `user_type` enum('USER','OWNER','DEVELOPER','AGENCY','SELECT') COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_account` enum('PRIVATE','COMPANY') COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_pin` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_account` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postcode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rules` text COLLATE utf8_unicode_ci NOT NULL,
  `last_login_date` datetime DEFAULT NULL,
  `error_login_date` datetime DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL,
  `commission` float DEFAULT NULL,
  `access` text COLLATE utf8_unicode_ci,
  `icon` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_users` ADD PRIMARY KEY (`id`),ADD KEY `login` (`login`), ADD KEY `email` (`email`), ADD KEY `status` (`status`);
ALTER TABLE `acc_users` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `acc_messages`;
CREATE TABLE `acc_messages` (
  `id` int(11) unsigned NOT NULL,
  `send_date` date DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('MANUAL','AUTOMATIC') COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('PENDING','SENDING','FINISH') COLLATE utf8_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  `last_edit_date` datetime DEFAULT NULL,
  `uid` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Przygotowane mailingi';

DROP TABLE IF EXISTS `acc_messages_outbox`;
CREATE TABLE `acc_messages_outbox` (
  `id` int(11) unsigned NOT NULL,
  `msg_id` int(11) unsigned NOT NULL,
  `email` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('PENDING','SENT','ERROR') COLLATE utf8_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL,
  `sent_date` datetime DEFAULT NULL,
  `readed` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Skrzynka nadawcza';

DROP TABLE IF EXISTS `acc_newsletter_emails`;
CREATE TABLE `acc_newsletter_emails` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('PENDING','CONFIRM') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PENDING',
  `confirm_date` date DEFAULT NULL,
  `confirm_ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` enum('import','added') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'added',
  `create_date` date NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `acc_messages` ADD PRIMARY KEY (`id`), ADD KEY `send_date` (`send_date`);
ALTER TABLE `acc_messages_outbox` ADD PRIMARY KEY (`id`);

ALTER TABLE `acc_newsletter_emails` ADD UNIQUE KEY `ident` (`id`), ADD UNIQUE KEY `email` (`email`), ADD KEY `status` (`status`), ADD KEY `source` (`source`);
ALTER TABLE `acc_messages` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `acc_messages_outbox` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `acc_newsletter_emails` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_p24;
CREATE TABLE acc_p24 (
  `id` int(11) UNSIGNED NOT NULL,
  `control` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `p24_merchant_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `p24_pos_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `p24_session_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `p24_amount` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `p24_currency` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `p24_order_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('PENDING','CONFIRM') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_p24 ADD PRIMARY KEY (`id`);
ALTER TABLE acc_p24 MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_special_en;
CREATE TABLE acc_special_en (
  `id` int(11) UNSIGNED NOT NULL,
  `show_main` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` LONGTEXT NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS acc_special_order;
CREATE TABLE acc_special_order (
  `id` int(11) UNSIGNED NOT NULL,
  `special_id` int(11) UNSIGNED NOT NULL,
  `object_id` int(11) UNSIGNED NOT NULL,
  `create_date` date NOT NULL,
  `expire_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS acc_special_pl;
CREATE TABLE acc_special_pl (
  `id` int(11) UNSIGNED NOT NULL,
  `show_main` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` LONGTEXT NOT NULL,
  `rewrite` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_special_en ADD PRIMARY KEY (`id`);
ALTER TABLE acc_special_order ADD PRIMARY KEY (`id`);
ALTER TABLE acc_special_pl ADD PRIMARY KEY (`id`);
ALTER TABLE acc_special_en MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE acc_special_order MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE acc_special_pl MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_texts_sms_pl;
CREATE TABLE acc_texts_sms_pl (
  `id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_texts_sms_pl ADD PRIMARY KEY (`id`);
ALTER TABLE acc_texts_sms_pl MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_texts_sms_en;
CREATE TABLE acc_texts_sms_en (
  `id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_texts_sms_en ADD PRIMARY KEY (`id`);
ALTER TABLE acc_texts_sms_en MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_business_invoice;
CREATE TABLE acc_business_invoice (
  id int(11) UNSIGNED NOT NULL,
  contrahent_id int(11) NOT NULL,
  create_date date NOT NULL,
  sell_date date NOT NULL,
  payment_date date NOT NULL,
  payment_label varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  place varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  items longtext COLLATE utf8_unicode_ci NOT NULL,
  invoice_number varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  payment enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
  payment_create_date date DEFAULT NULL,
  payment_amount varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  notice text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_business_invoice ADD PRIMARY KEY (id);
ALTER TABLE acc_business_invoice MODIFY id int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS acc_business_invoice_proforma;
CREATE TABLE acc_business_invoice_proforma (
  `id` int(11) UNSIGNED NOT NULL,
  `contrahent_id` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `sell_date` date NOT NULL,
  `payment_date` date NOT NULL,
  `payment_label` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `place` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `items` longtext COLLATE utf8_unicode_ci NOT NULL,
  `invoice_number` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `payment` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
  `payment_create_date` date DEFAULT NULL,
  `payment_amount` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notice` text COLLATE utf8_unicode_ci NOT NULL,
  `cancel` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_business_invoice_proforma ADD PRIMARY KEY (`id`);
ALTER TABLE acc_business_invoice_proforma MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

DROP TABLE IF EXISTS acc_business_contrahent;
CREATE TABLE acc_business_contrahent (
  id int(11) UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  address varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  postcode varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  city varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  country varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  person_name varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  person_phone varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  person_email varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  pin varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  notice text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE acc_business_contrahent ADD PRIMARY KEY (id);
ALTER TABLE acc_business_contrahent MODIFY id int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
