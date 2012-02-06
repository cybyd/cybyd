-- Torrent Image Upload by Real_ptr / start
ALTER TABLE `{$db_prefix}files` ADD `image` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `url`,
ADD `screen1` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `image`,
ADD `screen2` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `screen1`,
ADD `screen3` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `screen2`;

--

INSERT INTO `{$db_prefix}settings` ( `key` , `value` ) VALUES ('imageon', 'true');
INSERT INTO `{$db_prefix}settings` ( `key` , `value` ) VALUES ('uploaddir', 'cybyd_img/');
INSERT INTO `{$db_prefix}settings` ( `key` , `value` ) VALUES ('file_limit', '15');
INSERT INTO `{$db_prefix}settings` ( `key` , `value` ) VALUES ('screenon', 'true');
-- Torrent Image Upload by Real_ptr / end
