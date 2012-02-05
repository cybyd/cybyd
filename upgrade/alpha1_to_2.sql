alter table users_level
 add column  admin_access enum('yes','no') not null default 'no' after can_be_deleted
, change column view_torrents view_torrents enum('yes','no') not null default 'yes'
, change column edit_torrents edit_torrents enum('yes','no') not null default 'no'
, change column delete_torrents delete_torrents enum('yes','no') not null default 'no'
, change column view_users view_users enum('yes','no') not null default 'yes'
, change column edit_users edit_users enum('yes','no') not null default 'no'
, change column delete_users delete_users enum('yes','no') not null default 'no'
, change column view_news view_news enum('yes','no') not null default 'yes'
, change column edit_news edit_news enum('yes','no') not null default 'no'
, change column delete_news delete_news enum('yes','no') not null default 'no'
, change column can_upload can_upload enum('yes','no') not null default 'no'
, change column can_download can_download enum('yes','no') not null default 'yes'
, change column view_forum view_forum enum('yes','no') not null default 'yes'
, change column edit_forum edit_forum enum('yes','no') not null default 'yes'
, change column delete_forum delete_forum enum('yes','no') not null default 'no'
, change column predef_level predef_level enum('guest','validating','member','moderator','admin') not null default 'guest'
, change column can_be_deleted can_be_deleted enum('yes','no') not null default 'yes'
, type=MyISAM

create table tasks
(
  task varchar(20) null
,  last_time int(10) not null
,  primary key (task)
)