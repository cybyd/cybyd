alter table users
change column lip lip bigint(11) unsigned null default '0',
add `pid` CHAR(32) NOT NULL,
add `flag` tinyint(1) unsigned not null default '0',
ADD `topicsperpage` TINYINT( 3 ) UNSIGNED DEFAULT '0' NOT NULL ,
ADD `postsperpage` TINYINT( 3 ) UNSIGNED DEFAULT '0' NOT NULL ,
ADD `torrentsperpage` TINYINT( 3 ) UNSIGNED DEFAULT '0' NOT NULL ;

ALTER TABLE `users_level` 
ADD `prefixcolor` VARCHAR( 40 ) NOT NULL ,
ADD `suffixcolor` VARCHAR( 40 ) NOT NULL ;


create table ratings
(
  infohash char(40) not null
,  userid int(10) unsigned not null default '1'
,  rating tinyint(3) unsigned not null default '0'
,  added int(10) unsigned not null default '0'
) TYPE=MyISAM;

CREATE TABLE `bannedip` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` int(11) NOT NULL default '0',
  `addedby` int(10) unsigned NOT NULL default '0',
  `comment` varchar(255) NOT NULL default '',
  `first` bigint(11) unsigned default NULL,
  `last` bigint(11) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `first_last` (`first`,`last`)
) TYPE=MyISAM;

