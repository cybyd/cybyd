
INSERT INTO `xbtit_settings` (`key`, `value`) VALUES
('ipb_autoposter', '0');

ALTER TABLE `xbtit_users`
ADD `ipb_fid` int(10) NOT NULL default '0',
ADD INDEX (`ipb_fid`);

ALTER TABLE `xbtit_users_level`
ADD `smf_group_mirror` int(11) NOT NULL default '0',
ADD `ipb_group_mirror` int(11) NOT NULL default '0',
ADD INDEX (`smf_group_mirror`),
ADD INDEX (`ipb_group_mirror`);