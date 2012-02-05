DROP TABLE `language`, `users_level`;
CREATE TABLE users_level (
  id int(10) NOT NULL auto_increment,
  id_level int(11) NOT NULL default '0',
  level varchar(50) NOT NULL default '',
  view_torrents enum('yes','no') NOT NULL default 'yes',
  edit_torrents enum('yes','no') NOT NULL default 'no',
  delete_torrents enum('yes','no') NOT NULL default 'no',
  view_users enum('yes','no') NOT NULL default 'yes',
  edit_users enum('yes','no') NOT NULL default 'no',
  delete_users enum('yes','no') NOT NULL default 'no',
  view_news enum('yes','no') NOT NULL default 'yes',
  edit_news enum('yes','no') NOT NULL default 'no',
  delete_news enum('yes','no') NOT NULL default 'no',
  can_upload enum('yes','no') NOT NULL default 'no',
  can_download enum('yes','no') NOT NULL default 'yes',
  view_forum enum('yes','no') NOT NULL default 'yes',
  edit_forum enum('yes','no') NOT NULL default 'yes',
  delete_forum enum('yes','no') NOT NULL default 'no',
  predef_level enum('guest','validating','member','uploader','vip','moderator','admin','owner') NOT NULL default 'guest',
  can_be_deleted enum('yes','no') NOT NULL default 'yes',
  admin_access enum('yes','no') NOT NULL default 'no',
  prefixcolor varchar(40) NOT NULL default '',
  suffixcolor varchar(40) NOT NULL default '',
  UNIQUE KEY base (id)
) TYPE=MyISAM;

--
-- Dumping data for table `users_level`
--

INSERT INTO users_level VALUES (1,1,'guest','no','no','no','no','no','no','no','no','no','no','no','yes','no','no','guest','no','no','','');
INSERT INTO users_level VALUES (2,2,'validating','no','no','no','no','no','no','yes','no','no','no','no','yes','no','no','validating','no','no','','');
INSERT INTO users_level VALUES (3,3,'Members','yes','no','no','yes','no','no','yes','no','no','no','yes','yes','no','no','member','no','no','<span style=\\\'color:#000000\\\'>','</span>');
INSERT INTO users_level VALUES (6,6,'Moderator','yes','yes','no','yes','no','no','yes','yes','no','yes','yes','yes','yes','no','moderator','no','no','<span style=\\\'color: #428D67\\\'>','</span>');
INSERT INTO users_level VALUES (7,7,'Administrator','yes','yes','yes','yes','yes','yes','yes','yes','yes','yes','yes','yes','yes','yes','admin','no','no','<span style=\\\'color:#FF8000\\\'>','</span>');
INSERT INTO users_level VALUES (8,8,'Owner','yes','yes','yes','yes','yes','yes','yes','yes','yes','yes','yes','yes','yes','yes','owner','no','yes','','');
INSERT INTO users_level VALUES (4,4,'Uploader','yes','no','no','yes','no','no','yes','no','no','yes','no','yes','no','no','uploader','yes','no','','');
INSERT INTO users_level VALUES (5,5,'V.I.P.','yes','no','no','yes','no','no','yes','no','no','yes','yes','yes','no','no','vip','yes','no','','');


--
-- Table structure for table `language`
--

CREATE TABLE language (
  id int(10) NOT NULL auto_increment,
  language varchar(20) NOT NULL default '',
  language_url varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table `language`
--

INSERT INTO language VALUES (3,'Français','language/french.php');
INSERT INTO language VALUES (2,'Italian','language/italian.php');
INSERT INTO language VALUES (1,'English','language/english.php');
INSERT INTO language VALUES (4,'Deutsch','language/deutsch.php');
INSERT INTO language VALUES (5,'Dutch','language/dutch.php');
INSERT INTO language VALUES (6,'Polish','language/polish.php');
INSERT INTO language VALUES (7,'Spanish','language/spanish.php');
INSERT INTO language VALUES (8,'Turkish','language/turkish.php');
INSERT INTO language VALUES (9,'Portuguese','language/portuguese.php');
INSERT INTO language VALUES (10,'Serbian','language/serbian.php');
INSERT INTO language VALUES (11,'Bulgarian','language/bulgarian.php');
INSERT INTO language VALUES (12,'Finnish','language/finnish.php');
INSERT INTO language VALUES (13,'Russian','language/russian.php');
INSERT INTO language VALUES (14,'Thai','language/thai.php');
INSERT INTO language VALUES (15,'Czech','language/czech.php');



ALTER TABLE `users` CHANGE `lip` `lip` BIGINT( 11 ) DEFAULT '0';
UPDATE `users` SET `id_level` = '8', `avatar` = NULL WHERE `id_level` =5 ;
UPDATE `countries` SET `name` = 'unknown', `flagpic` = 'unknown.gif' WHERE `name` = 'unknow';
INSERT INTO countries VALUES (101,'Org','org.gif','ORG');
