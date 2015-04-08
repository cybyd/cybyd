-- phpMyAdmin SQL Dump
-- version 4.3.13
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 06, 2015 at 08:37 PM
-- Server version: 5.5.41-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- 
-- Table structure for table `{$db_prefix}bannedip`
-- 

CREATE TABLE `{$db_prefix}bannedip` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` int(11) NOT NULL default '0',
  `addedby` int(10) unsigned NOT NULL default '0',
  `comment` varchar(255) NOT NULL default '',
  `first` bigint(11) unsigned default NULL,
  `last` bigint(11) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `first_last` (`first`,`last`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}bannedip`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}blocks`
-- 

CREATE TABLE IF NOT EXISTS `{$db_prefix}blocks` (
  `blockid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL DEFAULT '',
  `position` char(1) NOT NULL DEFAULT '',
  `sortid` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `cache` enum('yes','no') NOT NULL,
  `minclassview` int(11) NOT NULL DEFAULT '0',
  `maxclassview` int(11) NOT NULL DEFAULT '8',
  PRIMARY KEY (`blockid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `{$db_prefix}blocks`
--

INSERT INTO `{$db_prefix}blocks` (`blockid`, `content`, `position`, `sortid`, `status`, `title`, `cache`, `minclassview`, `maxclassview`) VALUES
(1, 'menu', 'r', 5, 0, 'BLOCK_MENU', 'no', 3, 8),
(2, 'clock', 'r', 2, 0, 'BLOCK_CLOCK', 'no', 3, 8),
(3, 'forum', 'r', 3, 1, 'BLOCK_FORUM', 'no', 3, 8),
(4, 'lastmember', 'l', 1, 1, 'BLOCK_LASTMEMBER', 'no', 3, 8),
(6, 'trackerinfo', 'l', 3, 0, 'BLOCK_INFO', 'no', 3, 8),
(7, 'user', 'r', 4, 0, 'BLOCK_USER', 'no', 3, 8),
(8, 'online', 'b', 1, 0, 'BLOCK_ONLINE', 'no', 3, 8),
(10, 'toptorrents', 'c', 6, 1, 'BLOCK_TOPTORRENTS', 'no', 3, 8),
(11, 'lasttorrents', 'c', 5, 1, 'BLOCK_LASTTORRENTS', 'no', 3, 8),
(12, 'news', 'c', 2, 0, 'BLOCK_NEWS', 'no', 3, 8),
(13, 'mainmenu', 'e', 1, 0, 'BLOCK_MENU', 'no', 1, 8),
(14, 'maintrackertoolbar', 't', 2, 1, 'BLOCK_MAINTRACKERTOOLBAR', 'no', 3, 8),
(15, 'mainusertoolbar', 't', 2, 1, 'BLOCK_MAINUSERTOOLBAR', 'no', 3, 8),
(16, 'serverload', 'c', 7, 0, 'BLOCK_SERVERLOAD', 'no', 8, 8),
(17, 'poller', 'l', 2, 1, 'BLOCK_POLL', 'no', 3, 8),
(18, 'seedwanted', 'c', 4, 1, 'BLOCK_SEEDWANTED', 'no', 3, 8),
(19, 'paypal', 'r', 1, 1, 'BLOCK_PAYPAL', 'no', 3, 8),
(20, 'ajax_shoutbox', 'c', 3, 1, 'BLOCK_SHOUTBOX', 'no', 3, 8),
(21, 'dropdownmenu', 'd', 1, 0, 'BLOCK_DDMENU', 'no', 1, 8),
(22, 'header', 't', 1, 1, 'BLOCK_HEADER', 'no', 1, 8),
(24, 'login', 'c', 1, 1, 'BLOCK_LOGIN', 'no', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `{$db_prefix}bonus`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}bonus` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `points` decimal(4,1) NOT NULL DEFAULT '0.0',
  `traffic` bigint(20) unsigned NOT NULL DEFAULT '0',
  `gb` int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{$db_prefix}bonus`
--

INSERT INTO `{$db_prefix}bonus` (`id`, `name`, `points`, `traffic`, `gb`) VALUES
(NULL, '1', 30.0, 1073741824, 1),
(NULL, '2', 50.0, 2147483648, 2),
(NULL, '3', 100.0, 5368709120, 5);


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}categories`
-- 

CREATE TABLE `{$db_prefix}categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `sub` int(10) NOT NULL default '0',
  `sort_index` int(10) unsigned NOT NULL default '0',
  `image` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}categories`
-- 

INSERT INTO `{$db_prefix}categories` (`id`, `name`, `sub`, `sort_index`, `image`) VALUES
(7, 'Apps Win', 0, 1010, 'windows.png'),
(6, 'Books', 0, 110, 'books.png'),
(5, 'Anime', 0, 90, 'anime_new.png'),
(4, 'Other', 0, 1000, 'utilities2.png'),
(3, 'Games', 0, 40, 'games.png'),
(2, 'Music', 0, 20, 'music.png'),
(1, 'Movies', 0, 10, 'mov1es.png'),
(8, 'Apps Linux', 0, 1020, 'linux.png'),
(9, 'Apps Mac', 0, 1030, 'mac.png'),
(11, 'DVD-R', 1, 0, 'movies.png'),
(12, 'Adult', 0, 6969, 'adult.png');

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}chat`
-- 

CREATE TABLE `{$db_prefix}chat` (
  `id` mediumint(9) NOT NULL auto_increment,
  `uid` mediumint(9) NOT NULL,
  `time` int(10) NOT NULL default '0',
  `name` tinytext NOT NULL,
  `text` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}chat`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}comments`
-- 

CREATE TABLE `{$db_prefix}comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `ori_text` text NOT NULL,
  `user` varchar(20) NOT NULL default '',
  `info_hash` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `info_hash` (`info_hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}comments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}countries`
-- 

CREATE TABLE `{$db_prefix}countries` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `flagpic` varchar(50) default NULL,
  `domain` char(3) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}countries`
-- 

INSERT INTO `{$db_prefix}countries` (`id`, `name`, `flagpic`, `domain`) VALUES
(1, 'Sweden', 'se.png', 'SE'),
(2, 'United States of America', 'us.png', 'US'),
(3, 'American Samoa', 'as.png', 'AS'),
(4, 'Finland', 'fi.png', 'FI'),
(5, 'Canada', 'ca.png', 'CA'),
(6, 'France', 'fr.png', 'FR'),
(7, 'Germany', 'de.png', 'DE'),
(8, 'China', 'cn.png', 'CN'),
(9, 'Italy', 'it.png', 'IT'),
(10, 'Denmark', 'dk.png', 'DK'),
(11, 'Norway', 'no.png', 'NO'),
(12, 'United Kingdom', 'gb.png', 'GB'),
(13, 'Ireland', 'ie.png', 'IE'),
(14, 'Poland', 'pl.png', 'PL'),
(15, 'Netherlands', 'nl.png', 'NL'),
(16, 'Belgium', 'be.png', 'BE'),
(17, 'Japan', 'jp.png', 'JP'),
(18, 'Brazil', 'br.png', 'BR'),
(19, 'Argentina', 'ar.png', 'AR'),
(20, 'Australia', 'au.png', 'AU'),
(21, 'New Zealand', 'nz.png', 'NZ'),
(22, 'United Arab Emirates', 'ae.png', 'AE'),
(23, 'Spain', 'es.png', 'ES'),
(24, 'Portugal', 'pt.png', 'PT'),
(25, 'Mexico', 'mx.png', 'MX'),
(26, 'Singapore', 'sg.png', 'SG'),
(27, 'Anguilla', 'ai.png', 'AI'),
(28, 'Armenia', 'am.png', 'AM'),
(29, 'South Africa', 'za.png', 'ZA'),
(30, 'South Korea', 'kr.png', 'KR'),
(31, 'Jamaica', 'jm.png', 'JM'),
(32, 'Luxembourg', 'lu.png', 'LU'),
(33, 'Hong Kong', 'hk.png', 'HK'),
(34, 'Belize', 'bz.png', 'BZ'),
(35, 'Algeria', 'dz.png', 'DZ'),
(36, 'Angola', 'ao.png', 'AO'),
(37, 'Austria', 'at.png', 'AT'),
(38, 'Aruba', 'aw.png', 'AW'),
(39, 'Samoa', 'ws.png', 'WS'),
(40, 'Malaysia', 'my.png', 'MY'),
(41, 'Dominican Republic', 'do.png', 'DO'),
(42, 'Greece', 'gr.png', 'GR'),
(43, 'Guatemala', 'gt.png', 'GT'),
(44, 'Israel', 'il.png', 'IL'),
(45, 'Pakistan', 'pk.png', 'PK'),
(46, 'Czech Republic', 'cz.png', 'CZ'),
(47, 'Serbia and Montenegro', 'cs.png', 'CS'),
(48, 'Seychelles', 'sc.png', 'SC'),
(49, 'Taiwan', 'tw.png', 'TW'),
(50, 'Puerto Rico', 'pr.png', 'PR'),
(51, 'Chile', 'cl.png', 'CL'),
(52, 'Cuba', 'cu.png', 'CU'),
(53, 'Congo', 'cg.png', 'CG'),
(54, 'Afghanistan', 'af.png', 'AF'),
(55, 'Turkey', 'tr.png', 'TR'),
(56, 'Uzbekistan', 'uz.png', 'UZ'),
(57, 'Switzerland', 'ch.png', 'CH'),
(58, 'Kiribati', 'ki.gif', 'KI'),
(59, 'Philippines', 'ph.png', 'PH'),
(60, 'Burkina Faso', 'bf.png', 'BF'),
(61, 'Nigeria', 'ng.png', 'NG'),
(62, 'Iceland', 'is.png', 'IS'),
(63, 'Nauru', 'nr.png', 'NR'),
(64, 'Slovenia', 'si.png', 'SI'),
(65, 'Albania', 'al.png', 'AL'),
(66, 'Turkmenistan', 'tm.png', 'TM'),
(67, 'Bosnia and Herzegovina', 'ba.png', 'BA'),
(68, 'Andorra', 'ad.png', 'AD'),
(69, 'Lithuania', 'lt.png', 'LT'),
(70, 'India', 'in.png', 'IN'),
(71, 'Netherlands Antilles', 'an.png', 'AN'),
(72, 'Ukraine', 'ua.png', 'UA'),
(73, 'Venezuela', 've.png', 'VE'),
(74, 'Hungary', 'hu.png', 'HU'),
(75, 'Romania', 'ro.png', 'RO'),
(76, 'Vanuatu', 'vu.png', 'VU'),
(77, 'Viet Nam', 'vn.png', 'VN'),
(78, 'Trinidad & Tobago', 'tt.png', 'TT'),
(79, 'Honduras', 'hn.png', 'HN'),
(80, 'Kyrgyzstan', 'kg.png', 'KG'),
(81, 'Ecuador', 'ec.png', 'EC'),
(82, 'Bahamas', 'bs.png', 'BS'),
(83, 'Peru', 'pe.png', 'PE'),
(84, 'Cambodia', 'kh.png', 'KH'),
(85, 'Barbados', 'bb.png', 'BB'),
(86, 'Bangladesh', 'bd.png', 'BD'),
(87, 'Laos', 'la.png', 'LA'),
(88, 'Uruguay', 'uy.png', 'UY'),
(89, 'Antigua Barbuda', 'ag.png', 'AG'),
(90, 'Paraguay', 'py.png', 'PY'),
(91, 'Antarctica', 'aq.png', 'AQ'),
(92, 'Russian Federation', 'ru.png', 'RU'),
(93, 'Thailand', 'th.png', 'TH'),
(94, 'Senegal', 'sn.png', 'SN'),
(95, 'Togo', 'tg.png', 'TG'),
(96, 'North Korea', 'kp.png', 'KP'),
(97, 'Croatia', 'hr.png', 'HR'),
(98, 'Estonia', 'ee.png', 'EE'),
(99, 'Colombia', 'co.png', 'CO'),
(100, 'unknown', 'unknown.gif', 'AA'),
(101, 'Organization', 'org.png', 'ORG'),
(102, 'Aland Islands', 'ax.png', 'AX'),
(103, 'Azerbaijan', 'az.png', 'AZ'),
(104, 'Bulgaria', 'bg.png', 'BG'),
(105, 'Bahrain', 'bh.png', 'BH'),
(106, 'Burundi', 'bi.png', 'BI'),
(107, 'Benin', 'bj.png', 'BJ'),
(108, 'Bermuda', 'bm.png', 'BM'),
(109, 'Brunei Darussalam', 'bn.png', 'BN'),
(110, 'Bolivia', 'bo.png', 'BO'),
(111, 'Bhutan', 'bt.png', 'BT'),
(112, 'Bouvet Island', 'bv.png', 'BV'),
(113, 'Botswana', 'bw.png', 'BW'),
(114, 'Belarus', 'by.png', 'BY'),
(115, 'Cocos (Keeling) Islands', 'cc.png', 'CC'),
(116, 'Congo, the Democratic Republic of the', 'cd.png', 'CD'),
(117, 'Central African Republic', 'cf.png', 'CF'),
(118, 'Ivory Coast', 'ci.png', 'CI'),
(119, 'Cook Islands', 'ck.png', 'CK'),
(120, 'Cameroon', 'cm.png', 'CM'),
(121, 'Costa Rica', 'cr.png', 'CR'),
(122, 'Cape Verde', 'cv.png', 'CV'),
(123, 'Christmas Island', 'cx.png', 'CX'),
(124, 'Cyprus', 'cy.png', 'CY'),
(125, 'Djibouti', 'dj.png', 'DJ'),
(126, 'Dominica', 'dm.png', 'DM'),
(127, 'Egypt', 'eg.png', 'EG'),
(128, 'Western Sahara', 'eh.png', 'EH'),
(129, 'Eritrea', 'er.png', 'ER'),
(130, 'Ethiopia', 'et.png', 'ET'),
(131, 'Fiji', 'fj.png', 'FJ'),
(132, 'Falkland Islands (Malvinas)', 'fk.png', 'FK'),
(133, 'Micronesia, Federated States of', 'fm.png', 'FM'),
(134, 'Faroe Islands', 'fo.png', 'FO'),
(135, 'Gabon', 'ga.png', 'GA'),
(136, 'Grenada', 'gd.png', 'GD'),
(137, 'Georgia', 'ge.png', 'GE'),
(138, 'French Guiana', 'gf.png', 'GF'),
(139, 'Guernsey', 'gg.png', 'GG'),
(140, 'Ghana', 'gh.png', 'GH'),
(141, 'Gibraltar', 'gi.png', 'GI'),
(142, 'Greenland', 'gl.png', 'GL'),
(143, 'Gambia', 'gm.png', 'GM'),
(144, 'Guinea', 'gn.png', 'GN'),
(145, 'Guadeloupe', 'gp.png', 'GP'),
(146, 'Equatorial Guinea', 'gq.png', 'GQ'),
(147, 'South Georgia and the South Sandwich Islands', 'gs.png', 'GS'),
(148, 'Guam', 'gu.png', 'GU'),
(149, 'Guinea-Bissau', 'gw.png', 'GW'),
(150, 'Guyana', 'gy.png', 'GY'),
(151, 'Heard Island and McDonald Islands', 'hm.png', 'HM'),
(152, 'Haiti', 'ht.png', 'HT'),
(153, 'Indonesia', 'id.png', 'ID'),
(154, 'Isle of Man', 'im.png', 'IM'),
(155, 'British Indian Ocean Territory', 'io.png', 'IO'),
(156, 'Jersey', 'je.png', 'JE'),
(157, 'Jordan', 'jo.png', 'JO'),
(158, 'Kenya', 'ke.png', 'KE'),
(159, 'Comoros', 'km.png', 'KM'),
(160, 'Saint Kitts and Nevis', 'kn.png', 'KN'),
(161, 'Kuwait', 'kw.png', 'KW'),
(162, 'Cayman Islands', 'ky.png', 'KY'),
(163, 'Kazahstan', 'kz.png', 'KZ'),
(164, 'Lebanon', 'lb.png', 'LB'),
(165, 'Saint Lucia', 'lc.png', 'LC'),
(166, 'Liechtenstein', 'li.png', 'LI'),
(167, 'Sri Lanka', 'lk.png', 'LK'),
(168, 'Liberia', 'lr.png', 'LR'),
(169, 'Lesotho', 'ls.png', 'LS'),
(170, 'Latvia', 'lv.png', 'LV'),
(171, 'Libyan Arab Jamahiriya', 'ly.png', 'LY'),
(172, 'Morocco', 'ma.png', 'MA'),
(173, 'Monaco', 'mc.png', 'MC'),
(174, 'Moldova, Republic of', 'md.png', 'MD'),
(175, 'Madagascar', 'mg.png', 'MG'),
(176, 'Marshall Islands', 'mh.png', 'MH'),
(177, 'Macedonia, the former Yugoslav Republic of', 'mk.png', 'MK'),
(178, 'Mali', 'ml.png', 'ML'),
(179, 'Myanmar', 'mm.png', 'MM'),
(180, 'Mongolia', 'mn.png', 'MN'),
(181, 'Macao', 'mo.png', 'MO'),
(182, 'Northern Mariana Islands', 'mp.png', 'MP'),
(183, 'Martinique', 'mq.png', 'MQ'),
(184, 'Mauritania', 'mr.png', 'MR'),
(185, 'Montserrat', 'ms.png', 'MS'),
(186, 'Malta', 'mt.png', 'MT'),
(187, 'Mauritius', 'mu.png', 'MU'),
(188, 'Maldives', 'mv.png', 'MV'),
(189, 'Malawi', 'mw.png', 'MW'),
(190, 'Mozambique', 'mz.png', 'MZ'),
(191, 'Namibia', 'na.png', 'NA'),
(192, 'New Caledonia', 'nc.png', 'NC'),
(193, 'Niger', 'ne.png', 'NE'),
(194, 'Norfolk Island', 'nf.png', 'NF'),
(195, 'Nicaragua', 'ni.png', 'NI'),
(196, 'Nepal', 'np.png', 'NP'),
(197, 'Niue', 'nu.png', 'NU'),
(198, 'Oman', 'om.png', 'OM'),
(199, 'Panama', 'pa.png', 'PA'),
(200, 'French Polynesia', 'pf.png', 'PF'),
(201, 'Papua New Guinea', 'pg.png', 'PG'),
(202, 'Saint Pierre and Miquelon', 'pm.png', 'PM'),
(203, 'Pitcairn', 'pn.png', 'PN'),
(204, 'Palestinian Territory, Occupied', 'ps.png', 'PS'),
(205, 'Palau', 'pw.png', 'PW'),
(206, 'Qatar', 'qa.png', 'QA'),
(207, 'Reunion', 're.png', 'RE'),
(208, 'Rwanda', 'rw.png', 'RW'),
(209, 'Saudi Arabia', 'sa.png', 'SA'),
(210, 'Solomon Islands', 'sb.png', 'SB'),
(211, 'Sudan', 'sd.png', 'SD'),
(212, 'Saint Helena', 'sh.png', 'SH'),
(213, 'Svalbard and Jan Mayen', 'sj.png', 'SJ'),
(214, 'Slovakia', 'sk.png', 'SK'),
(215, 'Sierra Leone', 'sl.png', 'SL'),
(216, 'San Marino', 'sm.png', 'SM'),
(217, 'Somalia', 'so.png', 'SO'),
(218, 'Suriname', 'sr.png', 'SR'),
(219, 'Sao Tome and Principe', 'st.png', 'ST'),
(220, 'El Salvador', 'sv.png', 'SV'),
(221, 'Syrian Arab Republic', 'sy.png', 'SY'),
(222, 'Swaziland', 'sz.png', 'SZ'),
(223, 'Turks and Caicos Islands', 'tc.png', 'TC'),
(224, 'Chad', 'td.png', 'TD'),
(225, 'French Southern Territories', 'tf.png', 'TF'),
(226, 'Tajikistan', 'tj.png', 'TJ'),
(227, 'Tokelau', 'tk.png', 'TK'),
(228, 'Timor-Leste', 'tl.png', 'TL'),
(229, 'Tunisia', 'tn.png', 'TN'),
(230, 'Tonga', 'to.png', 'TO'),
(231, 'Tuvalu', 'tv.png', 'TV'),
(232, 'Tanzania, United Republic of', 'tz.png', 'TZ'),
(233, 'Uganda', 'ug.png', 'UG'),
(234, 'United States Minor Outlying Islands', 'um.png', 'UM'),
(235, 'Holy See (Vatican City State)', 'va.png', 'VA'),
(236, 'Saint Vincent and the Grenadines', 'vc.png', 'VC'),
(237, 'Virgin Islands, British', 'vg.png', 'VG'),
(238, 'Wallis and Futuna', 'wf.png', 'WF'),
(239, 'Yemen', 'ye.png', 'YE'),
(240, 'Mayotte', 'yt.png', 'YT'),
(241, 'Zambia', 'zm.png', 'ZM'),
(242, 'Zimbabwe', 'zw.png', 'ZW'),
(243, 'Iraq', 'iq.png', 'IQ'),
(244, 'Iran, Islamic Republic of', 'ir.png', 'IR');


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}files`
-- 

CREATE TABLE IF NOT EXISTS `{$db_prefix}files` (
  `info_hash` varchar(40) NOT NULL DEFAULT '',
  `filename` varchar(250) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `screen1` varchar(255) NOT NULL DEFAULT '',
  `screen2` varchar(255) NOT NULL DEFAULT '',
  `screen3` varchar(255) NOT NULL DEFAULT '',
  `gold` enum('0','1','2') NOT NULL DEFAULT '0',
  `info` varchar(250) NOT NULL DEFAULT '',
  `data` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `size` bigint(20) NOT NULL DEFAULT '0',
  `comment` text,
  `category` int(10) unsigned NOT NULL DEFAULT '6',
  `external` enum('yes','no') NOT NULL DEFAULT 'no',
  `announce_url` varchar(100) NOT NULL DEFAULT '',
  `uploader` int(10) NOT NULL DEFAULT '1',
  `lastupdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `anonymous` enum('true','false') NOT NULL DEFAULT 'false',
  `lastsuccess` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dlbytes` bigint(20) unsigned NOT NULL DEFAULT '0',
  `seeds` int(10) unsigned NOT NULL DEFAULT '0',
  `leechers` int(10) unsigned NOT NULL DEFAULT '0',
  `finished` int(10) unsigned NOT NULL DEFAULT '0',
  `lastcycle` int(10) unsigned NOT NULL DEFAULT '0',
  `lastSpeedCycle` int(10) unsigned NOT NULL DEFAULT '0',
  `speed` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bin_hash` blob NOT NULL,
  PRIMARY KEY (`info_hash`),
  KEY `filename` (`filename`),
  KEY `category` (`category`),
  KEY `uploader` (`uploader`),
  KEY `bin_hash` (`bin_hash`(20))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `{$db_prefix}files`
--


-- --------------------------------------------------------

--
-- Table structure for table `{$db_prefix}files_thanks`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}files_thanks` (
  `infohash` char(40) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  KEY `infohash` (`infohash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `{$db_prefix}files_thanks`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}forums`
-- 

CREATE TABLE IF NOT EXISTS `{$db_prefix}forums` (
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `description` varchar(200) DEFAULT NULL,
  `minclassread` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `minclasswrite` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `postcount` int(10) unsigned NOT NULL DEFAULT '0',
  `topiccount` int(10) unsigned NOT NULL DEFAULT '0',
  `minclasscreate` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `id_parent` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `id_parent` (`id_parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{$db_prefix}forums`
--


-- --------------------------------------------------------

--
-- Table structure for table `{$db_prefix}gold`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}gold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) NOT NULL DEFAULT '4',
  `gold_picture` varchar(255) NOT NULL DEFAULT 'gold.gif',
  `silver_picture` varchar(255) NOT NULL DEFAULT 'silver.gif',
  `active` enum('-1','0','1') NOT NULL DEFAULT '1',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `gold_description` text NOT NULL,
  `silver_description` text NOT NULL,
  `classic_description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{$db_prefix}gold`
--

INSERT INTO `{$db_prefix}gold` (`id`, `level`, `gold_picture`, `silver_picture`, `active`, `date`, `gold_description`, `silver_description`, `classic_description`) VALUES
(1, 4, 'free.png', 'silver.gif', '1', CURDATE(), 'Gold torrent description', 'Silver torrent description', 'Classic torrent description');

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}hacks`
-- 

CREATE TABLE `{$db_prefix}hacks` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) NOT NULL,
  `version` varchar(10) NOT NULL,
  `author` varchar(100) NOT NULL,
  `added` int(11) NOT NULL,
  `folder` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}hacks`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}history`
-- 

CREATE TABLE `{$db_prefix}history` (
  `uid` int(10) default NULL,
  `infohash` varchar(40) NOT NULL default '',
  `date` int(10) default NULL,
  `uploaded` bigint(20) NOT NULL default '0',
  `downloaded` bigint(20) NOT NULL default '0',
  `active` enum('yes','no') NOT NULL default 'no',
  `agent` varchar(30) NOT NULL default '',
  UNIQUE KEY `uid` (`uid`,`infohash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}history`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}language`
-- 

CREATE TABLE `{$db_prefix}language` (
  `id` int(10) NOT NULL auto_increment,
  `language` varchar(20) NOT NULL default '',
  `language_url` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}language`
-- 

INSERT INTO `{$db_prefix}language` (`id`, `language`, `language_url`) VALUES
(1, 'English', 'language/english'),
(2, 'Romanian', 'language/romanian'),
(3, 'Polish', 'language/polish'),
(4, 'Srpsko-Hrvatski', 'language/serbocroatian'),
(5, 'Dutch', 'language/dutch'),
(6, 'Italiano', 'language/italian'),
(7, 'Russian','language/russian'),
(8, 'German','language/german'),
(9, 'Hungarian','language/hungarian'),
(10, 'Français', 'language/french'),
(11, 'Finnish','language/finnish'),
(12, 'Vietnamese','language/vietnamese'),
(13, 'Greek','language/greek'),
(14, 'Bulgarian','language/bulgarian'),
(15, 'Spanish','language/spanish'),
(16, 'Portuguese-BR','language/portuguese-BR'),
(17, 'Portuguese-PT','language/portuguese-PT'),
(18, 'Swedish','language/swedish'),
(19, 'Arabic','language/arabic'),
(20, 'Danish','language/danish'),
(21, 'Chinese-Simplified','language/chinese'),
(22, 'Bengali','language/bangla');

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}logs`
-- 

CREATE TABLE `{$db_prefix}logs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` int(10) default NULL,
  `txt` text,
  `type` varchar(10) NOT NULL default 'add',
  `user` varchar(40) default NULL,
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}logs`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}messages`
-- 

CREATE TABLE IF NOT EXISTS `{$db_prefix}messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(10) unsigned NOT NULL DEFAULT '0',
  `receiver` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(10) DEFAULT NULL,
  `subject` varchar(50) NOT NULL DEFAULT '',
  `msg` text,
  `readed` enum('yes','no') NOT NULL DEFAULT 'no',
  `deletedBySender` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`),
  KEY `sender` (`sender`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{$db_prefix}messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `{$db_prefix}modules`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}modules` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `activated` enum('yes','no') NOT NULL DEFAULT 'yes',
  `type` enum('staff','misc','torrent','style') NOT NULL DEFAULT 'misc',
  `changed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{$db_prefix}modules`
--

INSERT INTO `{$db_prefix}modules` (`id`, `name`, `activated`, `type`, `changed`, `created`) VALUES
(NULL, 'seedbonus', 'yes', 'misc', NOW(), NOW());

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}news`
-- 

CREATE TABLE `{$db_prefix}news` (
  `id` int(11) NOT NULL auto_increment,
  `news` blob NOT NULL,
  `user_id` int(10) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `title` varchar(40) NOT NULL default '',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `{$db_prefix}news`
-- 

INSERT INTO `{$db_prefix}news` (`id`, `news`, `user_id`, `date`, `title`) VALUES 
(1, 0x496620796f752063616e20726561642074686973207468656e20796f75722073657420757020776173206120737563636573732e0d0a596f752077696c6c2077616e7420746f2064656c657465207468697320706f73742e200d0a546563686e6963616c20737570706f72742063616e20626520666f756e64206f6e2074686520786274697420666f72756d73205b75726c5d687474703a2f2f7777772e6274697465616d2e6f72672f736d662f5b2f75726c5d, 2, NOW(), 'Welcome ;)');

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}online`
-- 

CREATE TABLE `{$db_prefix}online` (
  `session_id` varchar(40) NOT NULL,
  `user_id` int(10) NOT NULL,
  `user_ip` varchar(15) NOT NULL,
  `location` varchar(20) NOT NULL,
  `lastaction` int(10) NOT NULL,
  `user_name` varchar(40) NOT NULL,
  `user_group` varchar(50) NOT NULL,
  `prefixcolor` varchar(200) NOT NULL,
  `suffixcolor` varchar(200) NOT NULL,
  PRIMARY KEY  (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}online`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}peers`
-- 

CREATE TABLE `{$db_prefix}peers` (
  `infohash` varchar(40) NOT NULL default '',
  `peer_id` varchar(40) NOT NULL default '',
  `bytes` bigint(20) NOT NULL default '0',
  `ip` varchar(50) NOT NULL default 'error.x',
  `port` smallint(5) unsigned NOT NULL default '0',
  `status` enum('leecher','seeder') NOT NULL default 'leecher',
  `lastupdate` int(10) unsigned NOT NULL default '0',
  `sequence` int(10) unsigned NOT NULL auto_increment,
  `natuser` enum('N','Y') NOT NULL default 'N',
  `client` varchar(60) NOT NULL default '',
  `dns` varchar(100) NOT NULL default '',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `pid` varchar(32) default NULL,
  `with_peerid` varchar(101) NOT NULL default '',
  `without_peerid` varchar(40) NOT NULL default '',
  `compact` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`infohash`,`peer_id`),
  UNIQUE KEY `sequence` (`sequence`),
  KEY `pid` (`pid`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}peers`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}poll_voters`
-- 

CREATE TABLE `{$db_prefix}poll_voters` (
  `vid` int(10) NOT NULL auto_increment,
  `ip` varchar(16) NOT NULL default '',
  `votedate` int(10) NOT NULL default '0',
  `pid` mediumint(8) NOT NULL default '0',
  `memberid` varchar(32) default NULL,
  PRIMARY KEY  (`vid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}poll_voters`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}poller`
-- 

CREATE TABLE `{$db_prefix}poller` (
  `ID` int(11) NOT NULL auto_increment,
  `startDate` int(10) NOT NULL default '0',
  `endDate` int(10) NOT NULL default '0',
  `pollerTitle` varchar(255) default NULL,
  `starterID` mediumint(8) NOT NULL default '0',
  `active` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}poller`
-- 

INSERT INTO `{$db_prefix}poller` (`ID`, `startDate`, `endDate`, `pollerTitle`, `starterID`, `active`) VALUES
(1, UNIX_TIMESTAMP(), 0, 'How would you rate this script?', 2, 'yes');

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}poller_option`
-- 

CREATE TABLE `{$db_prefix}poller_option` (
  `ID` int(11) NOT NULL auto_increment,
  `pollerID` int(11) default NULL,
  `optionText` varchar(255) default NULL,
  `pollerOrder` int(11) default NULL,
  `defaultChecked` char(1) default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}poller_option`
-- 

INSERT INTO `{$db_prefix}poller_option` (`ID`, `pollerID`, `optionText`, `pollerOrder`, `defaultChecked`) VALUES
(1, 1, 'Excellent', 1, '1'),
(2, 1, 'Very good', 2, '0'),
(3, 1, 'Good', 3, '0'),
(4, 1, 'Fair', 3, '0'),
(5, 1, 'Poor', 4, '0');

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}poller_vote`
-- 

CREATE TABLE `{$db_prefix}poller_vote` (
  `ID` int(11) NOT NULL auto_increment,
  `pollerID` int(11) NOT NULL default '0',
  `optionID` int(11) default NULL,
  `ipAddress` bigint(11) default '0',
  `voteDate` int(10) NOT NULL default '0',
  `memberID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}poller_vote`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}polls`
-- 

CREATE TABLE `{$db_prefix}polls` (
  `pid` mediumint(8) NOT NULL auto_increment,
  `startdate` int(10) default NULL,
  `choices` text,
  `starter_id` mediumint(8) NOT NULL default '0',
  `votes` smallint(5) NOT NULL default '0',
  `poll_question` varchar(255) default NULL,
  `status` enum('true','false') NOT NULL default 'false',
  PRIMARY KEY  (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}polls`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}posts`
-- 

CREATE TABLE `{$db_prefix}posts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `topicid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `added` int(10) default NULL,
  `body` text,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(10) default '0',
  PRIMARY KEY  (`id`),
  KEY `topicid` (`topicid`),
  KEY `userid` (`userid`),
  FULLTEXT KEY `body` (`body`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}posts`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}ratings`
-- 

CREATE TABLE `{$db_prefix}ratings` (
  `infohash` char(40) NOT NULL default '',
  `userid` int(10) unsigned NOT NULL default '1',
  `rating` tinyint(3) unsigned NOT NULL default '0',
  `added` int(10) unsigned NOT NULL default '0',
  KEY `infohash` (`infohash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}ratings`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}readposts`
-- 

CREATE TABLE `{$db_prefix}readposts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `topicid` int(10) unsigned NOT NULL default '0',
  `lastpostread` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `topicid` (`topicid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}readposts`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}settings`
-- 

CREATE TABLE `{$db_prefix}settings` (
  `key` varchar(30) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY  (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}settings`
-- 

INSERT INTO `{$db_prefix}settings` (`key`, `value`) VALUES
('name', 'CyByD'),
('url', 'http://127.0.0.1/dev'),
('announce', 'a:2:{i:0;s:30:"http://localhost/announce.php\r";i:1;s:30:"http://localhost:2710/announce";}'),
('email', 'admin@localhost'),
('torrentdir', 'torrents'),
('external', 'true'),
('gzip', 'true'),
('debug', 'true'),
('disable_dht', 'true'),
('livestat', 'true'),
('logactive', 'true'),
('loghistory', 'true'),
('p_announce', 'true'),
('p_scrape', 'false'),
('show_uploader', 'false'),
('usepopup', 'false'),
('default_language', '1'),
('default_charset', 'UTF-8'),
('default_style', '1'),
('max_users', '0'),
('max_torrents_per_page', '15'),
('sanity_update', '1800'),
('external_update', '1800'),
('max_announce', '1800'),
('min_announce', '300'),
('max_peers_per_announce', '50'),
('dynamic', 'false'),
('nat', 'false'),
('persist', 'false'),
('allow_override_ip', 'false'),
('countbyte', 'true'),
('peercaching', 'true'),
('maxpid_seeds', '3'),
('maxpid_leech', '1'),
('validation', 'user'),
('imagecode', 'true'),
('forum', ''),
('clocktype', 'false'),
('newslimit', '3'),
('forumlimit', '5'),
('last10limit', '5'),
('mostpoplimit', '5'),
('xbtt_url', 'http://localhost:2710'),
('cache_duration', '0'),
('cut_name', '0'),
('mail_type', 'php'),
('secsui_quarantine_dir', ''), 
('secsui_quarantine_search_terms', '<?php,base64_decode,base64_encode,eval(,phpinfo,fopen,fread,fwrite,file_get_contents'), 
('secsui_cookie_name', ''), 
('secsui_quarantine_pm', '2'), 
('secsui_pass_type', '1'), 
('secsui_ss', ''), 
('secsui_cookie_type', '1'), 
('secsui_cookie_exp1', '1'), 
('secsui_cookie_exp2', '3'), 
('secsui_cookie_path', ''), 
('secsui_cookie_domain', ''), 
('secsui_cookie_items', '1-0,2-0,3-0,4-0,5-0,6-0,7-0,8-0[+]0'),
('secsui_pass_min_req', '4,0,0,0,0'),
('ipb_autoposter', '0'),
('php_log_name', 'xbtit-errors'),
('php_log_path', '/full/path/to/the/web/root/include/logs'),
('php_log_lines', '5'),
('imageon', 'true'),
('uploaddir', 'cybyd_img/'),
('file_limit', '15'),
('screenon', 'true');

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}style`
-- 

CREATE TABLE `{$db_prefix}style` (
  `id` int(10) NOT NULL auto_increment,
  `style` varchar(20) NOT NULL default '',
  `style_url` varchar(100) NOT NULL default '',
  `style_type` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}style`
-- 

INSERT INTO `{$db_prefix}style` (`id`, `style`, `style_url`, `style_type`) VALUES
(1, 'xBtit Default', 'style/xbtit_default', 3),
(2, 'Mint', 'style/mintgreen', 3),
(3, 'Dark Lair', 'style/darklair', 3),
(4, 'Yellow Jacket', 'style/thehive', 3),
(5, 'Frosted', 'style/frosted', 3),
(6, 'Holiday Spirit', 'style/holiday-spirit', 3);
-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}tasks`
-- 

CREATE TABLE `{$db_prefix}tasks` (
  `task` varchar(20) NOT NULL default '',
  `last_time` int(10) NOT NULL default '0',
  PRIMARY KEY  (`task`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}tasks`
-- 

INSERT INTO `{$db_prefix}tasks` (`task`, `last_time`) VALUES
('sanity', UNIX_TIMESTAMP()),
('update', UNIX_TIMESTAMP());

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}timestamps`
-- 

CREATE TABLE `{$db_prefix}timestamps` (
  `info_hash` char(40) NOT NULL default '',
  `sequence` int(10) unsigned NOT NULL auto_increment,
  `bytes` bigint(20) unsigned NOT NULL default '0',
  `delta` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`sequence`),
  KEY `sorting` (`info_hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}timestamps`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}timezone`
-- 

CREATE TABLE `{$db_prefix}timezone` (
  `difference` varchar(4) NOT NULL default '0',
  `timezone` text NOT NULL,
  PRIMARY KEY  (`difference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}timezone`
-- 

INSERT INTO `{$db_prefix}timezone` (`difference`, `timezone`) VALUES
('-12', '(GMT - 12:00 hours) Enitwetok, Kwajalien'),
('-11', '(GMT - 11:00 hours) Midway Island, Samoa'),
('-10', '(GMT - 10:00 hours) Hawaii'),
('-9', '(GMT - 9:00 hours) Alaska'),
('-8', '(GMT - 8:00 hours) Pacific Time (US &amp; Canada)'),
('-7', '(GMT - 7:00 hours) Mountain Time (US &amp; Canada)'),
('-6', '(GMT - 6:00 hours) Central Time (US &amp; Canada), Mexico City'),
('-5', '(GMT - 5:00 hours) Eastern Time (US &amp; Canada), Bogota, Lima'),
('-4', '(GMT - 4:00 hours) Atlantic Time (Canada), Caracas, La Paz'),
('-3.5', '(GMT - 3:30 hours) Newfoundland'),
('-3', '(GMT - 3:00 hours) Brazil, Buenos Aires, Falkland Is.'),
('-2', '(GMT - 2:00 hours) Mid-Atlantic, Ascention Is., St Helena'),
('-1', '(GMT - 1:00 hours) Azores, Cape Verde Islands'),
('0', '(GMT) Casablanca, Dublin, London, Lisbon, Monrovia'),
('1', '(GMT + 1:00 hours) Amsterdam, Brussels, Copenhagen, Madrid, Paris'),
('2', '(GMT + 2:00 hours) Kaliningrad, South Africa'),
('3', '(GMT + 3:00 hours) Baghdad, Riyadh, Moscow, Nairobi'),
('3.5', '(GMT + 3:30 hours) Tehran'),
('4', '(GMT + 4:00 hours) Abu Dhabi, Baku, Muscat, Tbilisi'),
('4.5', '(GMT + 4:30 hours) Kabul'),
('5', '(GMT + 5:00 hours) Ekaterinburg, Karachi, Tashkent'),
('5.5', '(GMT + 5:30 hours) Bombay, Calcutta, Madras, New Delhi'),
('6', '(GMT + 6:00 hours) Almaty, Colomba, Dhaka'),
('7', '(GMT + 7:00 hours) Bangkok, Hanoi, Jakarta'),
('8', '(GMT + 8:00 hours) Hong Kong, Perth, Singapore, Taipei'),
('9', '(GMT + 9:00 hours) Osaka, Sapporo, Seoul, Tokyo, Yakutsk'),
('9.5', '(GMT + 9:30 hours) Adelaide, Darwin'),
('10', '(GMT + 10:00 hours) Melbourne, Papua New Guinea, Sydney'),
('11', '(GMT + 11:00 hours) Magadan, New Caledonia, Solomon Is.'),
('12', '(GMT + 12:00 hours) Auckland, Fiji, Marshall Island');

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}topics`
-- 

CREATE TABLE `{$db_prefix}topics` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `subject` varchar(40) default NULL,
  `locked` enum('yes','no') NOT NULL default 'no',
  `forumid` int(10) unsigned NOT NULL default '0',
  `lastpost` int(10) unsigned NOT NULL default '0',
  `sticky` enum('yes','no') NOT NULL default 'no',
  `views` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `subject` (`subject`),
  KEY `lastpost` (`lastpost`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}topics`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}users`
-- 

CREATE TABLE IF NOT EXISTS `{$db_prefix}users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `salt` varchar(20) NOT NULL DEFAULT '',
  `pass_type` enum('1','2','3','4','5','6','7') NOT NULL DEFAULT '1',
  `dupe_hash` varchar(20) NOT NULL DEFAULT '',
  `id_level` int(10) NOT NULL DEFAULT '1',
  `custom_title` varchar(51) NOT NULL DEFAULT 'User' COMMENT 'Prefers to be called',
  `seedbonus` decimal(12,6) NOT NULL DEFAULT '0.000000',
  `random` int(10) DEFAULT '0',
  `email` varchar(50) NOT NULL DEFAULT '',
  `language` tinyint(4) NOT NULL DEFAULT '1',
  `style` tinyint(4) NOT NULL DEFAULT '1',
  `joined` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastconnect` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lip` bigint(11) DEFAULT '0',
  `downloaded` bigint(20) DEFAULT '0',
  `uploaded` bigint(20) DEFAULT '0',
  `avatar` varchar(200) DEFAULT NULL,
  `pid` varchar(32) NOT NULL DEFAULT '',
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `topicsperpage` tinyint(3) unsigned NOT NULL DEFAULT '15',
  `postsperpage` tinyint(3) unsigned NOT NULL DEFAULT '15',
  `torrentsperpage` tinyint(3) unsigned NOT NULL DEFAULT '15',
  `cip` varchar(15) DEFAULT NULL,
  `time_offset` varchar(4) NOT NULL DEFAULT '0',
  `temp_email` varchar(50) NOT NULL DEFAULT '',
  `smf_fid` int(10) NOT NULL DEFAULT '0',
  `ipb_fid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `id_level` (`id_level`),
  KEY `pid` (`pid`),
  KEY `cip` (`cip`),
  KEY `smf_fid` (`smf_fid`),
  KEY `ipb_fid` (`ipb_fid`),
  KEY `custom_title` (`custom_title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `{$db_prefix}users`
-- 

INSERT INTO `{$db_prefix}users` (`id`, `username`, `password`, `salt`, `pass_type`, `dupe_hash`, `id_level`, `custom_title`, `seedbonus`, `random`, `email`, `language`, `style`, `joined`, `lastconnect`, `lip`, `downloaded`, `uploaded`, `avatar`, `pid`, `flag`, `topicsperpage`, `postsperpage`, `torrentsperpage`, `cip`, `time_offset`, `temp_email`, `smf_fid`, `ipb_fid`) VALUES
(NULL, 'Guest', '', '', '1', '', 1, 'User', 0.000000, 0, 'none', 1, 1, NOW(), NOW(), 0, 0, 0, NULL, '00000000000000000000000000000000', 0, 10, 10, 10, '127.0.0.2', '0', '', 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `{$db_prefix}users_level`
-- 

CREATE TABLE `{$db_prefix}users_level` (
  `id` int(10) NOT NULL auto_increment,
  `id_level` int(11) NOT NULL default '0',
  `level` varchar(50) NOT NULL default '',
  `view_torrents` enum('yes','no') NOT NULL default 'yes',
  `edit_torrents` enum('yes','no') NOT NULL default 'no',
  `delete_torrents` enum('yes','no') NOT NULL default 'no',
  `view_users` enum('yes','no') NOT NULL default 'yes',
  `edit_users` enum('yes','no') NOT NULL default 'no',
  `delete_users` enum('yes','no') NOT NULL default 'no',
  `view_news` enum('yes','no') NOT NULL default 'yes',
  `edit_news` enum('yes','no') NOT NULL default 'no',
  `delete_news` enum('yes','no') NOT NULL default 'no',
  `can_upload` enum('yes','no') NOT NULL default 'no',
  `can_download` enum('yes','no') NOT NULL default 'yes',
  `view_forum` enum('yes','no') NOT NULL default 'yes',
  `edit_forum` enum('yes','no') NOT NULL default 'yes',
  `delete_forum` enum('yes','no') NOT NULL default 'no',
  `predef_level` enum('guest','validating','member','uploader','vip','moderator','admin','owner') NOT NULL default 'guest',
  `can_be_deleted` enum('yes','no') NOT NULL default 'yes',
  `admin_access` enum('yes','no') NOT NULL default 'no',
  `prefixcolor` varchar(200) NOT NULL default '',
  `suffixcolor` varchar(200) NOT NULL default '',
  `WT` int(11) NOT NULL default '0',
  `smf_group_mirror` int(11) NOT NULL default '0',
  `ipb_group_mirror` int(11) NOT NULL default '0',
  UNIQUE KEY `base` (`id`),
  KEY `id_level` (`id_level`),
  KEY `smf_group_mirror` (`smf_group_mirror`),
  KEY `ipb_group_mirror` (`ipb_group_mirror`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `{$db_prefix}users_level`
-- 

INSERT INTO `{$db_prefix}users_level` (`id`, `id_level`, `level`, `view_torrents`, `edit_torrents`, `delete_torrents`, `view_users`, `edit_users`, `delete_users`, `view_news`, `edit_news`, `delete_news`, `can_upload`, `can_download`, `view_forum`, `edit_forum`, `delete_forum`, `predef_level`, `can_be_deleted`, `admin_access`, `prefixcolor`, `suffixcolor`, `WT`, `smf_group_mirror`, `ipb_group_mirror`) VALUES
(1, 1, 'guest', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'no', 'no', 'guest', 'no', 'no', '', '', 0, 0, 0),
(2, 2, 'validating', 'yes', 'no', 'no', 'no', 'no', 'no', 'yes', 'no', 'no', 'no', 'no', 'yes', 'no', 'no', 'validating', 'no', 'no', '', '', 0, 0, 0),
(3, 3, 'Members', 'yes', 'no', 'no', 'yes', 'no', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'no', 'no', 'member', 'no', 'no', '<span style=\'color:#000000\'>', '</span>', 0, 0, 0),
(4, 4, 'Uploader', 'yes', 'no', 'no', 'yes', 'no', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no', 'no', 'uploader', 'no', 'no', '', '', 0, 0, 0),
(5, 5, 'V.I.P.', 'yes', 'no', 'no', 'yes', 'no', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no', 'no', 'vip', 'no', 'no', '', '', 0, 0, 0),
(6, 6, 'Moderator', 'yes', 'yes', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'moderator', 'no', 'no', '<span style=\'color: #428D67\'>', '</span>', 0, 0, 0),
(7, 7, 'Administrator', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'admin', 'no', 'yes', '<span style=\'color:#FF8000\'>', '</span>', 0, 0, 0),
(8, 8, 'Owner', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'owner', 'no', 'yes', '<span style=\'color:#EE4000\'>', '</span>', 0, 0, 0);
