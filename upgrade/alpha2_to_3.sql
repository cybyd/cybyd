

alter table users
 add column  lip int(11) null default '0' after lastconnect
,  add column  downloaded bigint(20) null default '0' after lip
,  add column  uploaded bigint(20) null default '0' after downloaded
,  add column  avatar varchar(100) null after uploaded
, type=MyISAM;



drop table if exists messages;

create table messages (
   id int(10) unsigned not null auto_increment,
   sender int(10) unsigned not null default '0',
   receiver int(10) unsigned not null default '0',
   added int(10),
   subject varchar(30) not null,
   msg text,
   readed enum('yes','no') not null default 'no',
   primary key (id),
   index receiver (receiver));



create table forums (
   sort tinyint(3) unsigned not null default '0',
   id int(10) unsigned not null auto_increment,
   name varchar(60) not null,
   description varchar(200),
   minclassread tinyint(3) unsigned not null default '1',
   minclasswrite tinyint(3) unsigned not null default '1',
   postcount int(10) unsigned not null default '0',
   topiccount int(10) unsigned not null default '0',
   minclasscreate tinyint(3) unsigned not null default '1',
   primary key (id));


create table posts (
   id int(10) unsigned not null auto_increment,
   topicid int(10) unsigned not null default '0',
   userid int(10) unsigned not null default '0',
   added int(10),
   body text,
   editedby int(10) unsigned not null default '0',
   editedat int(10) default '0',
   primary key (id),
   index topicid (topicid),
   index userid (userid));


create table readposts (
   id int(10) unsigned not null auto_increment,
   userid int(10) unsigned not null default '0',
   topicid int(10) unsigned not null default '0',
   lastpostread int(10) unsigned not null default '0',
   primary key (id),
   index userid (id),
   index topicid (topicid));


create table topics (
   id int(10) unsigned not null auto_increment,
   userid int(10) unsigned not null default '0',
   subject varchar(40),
   locked enum('yes','no') not null default 'no',
   forumid int(10) unsigned not null default '0',
   lastpost int(10) unsigned not null default '0',
   sticky enum('yes','no') not null default 'no',
   views int(10) unsigned not null default '0',
   primary key (id),
   index userid (userid),
   index subject (subject),
   index lastpost (lastpost));


