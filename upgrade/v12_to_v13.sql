ALTER TABLE `namemap` ADD `lastsuccess` DATETIME NOT NULL default '0000-00-00 00:00:00';

CREATE TABLE IF NOT EXISTS history (
  uid int(10) default NULL,
  infohash varchar(40) NOT NULL default '',
  `date` int(10) default NULL,
  uploaded bigint(20) NOT NULL default '0',
  downloaded bigint(20) NOT NULL default '0',
  active enum('yes','no') NOT NULL default 'no',
  agent varchar(30) NOT NULL default '',
  UNIQUE KEY uid (uid,infohash)
) TYPE=MyISAM;


CREATE TABLE IF NOT EXISTS `logs` (
  id int(10) unsigned NOT NULL auto_increment,
  added int(10) default NULL,
  txt text,
  `type` varchar(10) NOT NULL default 'add',
  `user` varchar(40) default NULL,
  PRIMARY KEY  (id),
  KEY added (added)
) TYPE=MyISAM;


CREATE TABLE peers (
  infohash varchar(40) NOT NULL default '',
  peer_id varchar(40) NOT NULL default '',
  bytes bigint(20) NOT NULL default '0',
  ip varchar(50) NOT NULL default 'error.x',
  port smallint(5) unsigned NOT NULL default '0',
  `status` enum('leecher','seeder') NOT NULL default 'leecher',
  lastupdate int(10) unsigned NOT NULL default '0',
  sequence int(10) unsigned NOT NULL auto_increment,
  natuser enum('N','Y') NOT NULL default 'N',
  `client` varchar(60) NOT NULL default '',
  dns varchar(100) NOT NULL default '',
  uploaded bigint(20) unsigned NOT NULL default '0',
  downloaded bigint(20) unsigned NOT NULL default '0',
  pid varchar(32) default NULL,
  with_peerid varchar(101) NOT NULL default '',
  without_peerid varchar(40) NOT NULL default '',
  compact varchar(6) NOT NULL default '',
  PRIMARY KEY  (infohash,peer_id),
  UNIQUE KEY sequence (sequence)
) TYPE=MyISAM;



ALTER TABLE users ADD cip varchar(15) default NULL;