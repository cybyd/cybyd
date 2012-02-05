--
-- Table structure for table `blocks`
--

CREATE TABLE blocks (
  blockid int(11) unsigned NOT NULL auto_increment,
  content varchar(255) NOT NULL default '',
  position char(1) NOT NULL default '',
  sortid int(11) unsigned NOT NULL default '0',
  status tinyint(3) unsigned default NULL,
  PRIMARY KEY  (blockid)
) TYPE=MyISAM;

--
-- Dumping data for table `blocks`
--

INSERT INTO blocks VALUES (1,'menu','l',1,1);
INSERT INTO blocks VALUES (2,'clock','r',2,1);
INSERT INTO blocks VALUES (3,'forum','l',2,1);
INSERT INTO blocks VALUES (4,'lastmember','l',3,1);
INSERT INTO blocks VALUES (6,'trackerinfo','r',1,1);
INSERT INTO blocks VALUES (7,'user','r',3,1);
INSERT INTO blocks VALUES (8,'online','l',4,1);
INSERT INTO blocks VALUES (9,'shoutbox','c',1,1);
INSERT INTO blocks VALUES (10,'toptorrents','c',4,1);
INSERT INTO blocks VALUES (11,'lasttorrents','c',3,1);
INSERT INTO blocks VALUES (12,'news','c',2,1);
INSERT INTO blocks VALUES (13,'mainmenu','t',2,1);
INSERT INTO blocks VALUES (14,'maintrackertoolbar','t',2,1);
INSERT INTO blocks VALUES (15,'mainusertoolbar','t',3,1);
INSERT INTO blocks VALUES (16,'serverload','c',8,1);
INSERT INTO blocks VALUES (17,'poll','r',10,1);

--
-- Table structure for table `categories`
--

ALTER TABLE `categories` ADD `sub` INT( 10 ) DEFAULT '0' NOT NULL AFTER `name` ;


--
-- Dumping data for table `categories`
--

INSERT INTO categories VALUES (11,'DVD-R',1,0,'movies.png');
INSERT INTO categories VALUES (12,'Mvcd',1,23333,'film.jpg');


--
-- Dumping data for table `countries`
--

UPDATE `countries` SET `name` = 'unknown',`flagpic` = 'unknown.gif' WHERE `id` = '100' LIMIT 1 ;

--
-- Table structure for table `poll_voters`
--

CREATE TABLE poll_voters (
  vid int(10) NOT NULL auto_increment,
  ip varchar(16) NOT NULL default '',
  votedate int(10) NOT NULL default '0',
  pid mediumint(8) NOT NULL default '0',
  memberid varchar(32) default NULL,
  PRIMARY KEY  (vid)
) TYPE=MyISAM;

--
-- Table structure for table `polls`
--

CREATE TABLE polls (
  pid mediumint(8) NOT NULL auto_increment,
  startdate int(10) default NULL,
  choices text,
  starter_id mediumint(8) NOT NULL default '0',
  votes smallint(5) NOT NULL default '0',
  poll_question varchar(255) default NULL,
  status enum('true','false') NOT NULL default 'false',
  PRIMARY KEY  (pid)
) TYPE=MyISAM;



--
-- Table structure for table `users_level`
--

ALTER TABLE `users_level` ADD `WT` INT( 11 ) DEFAULT '0' NOT NULL ;