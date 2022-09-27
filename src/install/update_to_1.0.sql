ALTER TABLE  `acc_users` ADD  `bank_account` VARCHAR( 100 ) NULL AFTER  `company_pin`;
ALTER TABLE  `acc_users` ADD  `commission` FLOAT NULL AFTER  `update_date`;
ALTER TABLE  `acc_rooms_photos` CHANGE  `object_id`  `room_id` INT( 11 ) UNSIGNED NOT NULL;
ALTER TABLE  `acc_rooms_photos` ADD  `main` ENUM(  "FALSE",  "TRUE" ) NOT NULL AFTER  `file`;
ALTER TABLE  `acc_objects` ADD  `booking` ENUM(  "FALSE",  "TRUE" ) NOT NULL AFTER  `minus`;
ALTER TABLE  `acc_cities` ADD  `photo` VARCHAR( 200 ) NULL AFTER  `rewrite`;

Wersja 1.1:
ALTER TABLE  `acc_special_pl` ADD  `description` LONGTEXT NOT NULL AFTER  `name`;
ALTER TABLE  `acc_special_en` ADD  `description` LONGTEXT NOT NULL AFTER  `name`;
ALTER TABLE `acc_cities` ADD `description` LONGTEXT NOT NULL AFTER `name`;