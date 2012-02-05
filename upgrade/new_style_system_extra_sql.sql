
ALTER TABLE `xbtit_style` ADD `style_type` TINYINT(1) NOT NULL DEFAULT '1';

UPDATE `xbtit_style` SET `style_type`=3 WHERE `style_url` IN('style/xbtit_default','style/mintgreen','style/darklair','style/thehive','style/frosted','style/holiday-spirit');