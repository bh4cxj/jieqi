
--
-- 表的结构 `jieqi_info_attachs`
--

CREATE TABLE `jieqi_info_attachs` (
  `attachid` mediumint(8) NOT NULL auto_increment,
  `siteid` smallint(6) NOT NULL,
  `topicid` mediumint(8) NOT NULL,
  `postid` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `desc` varchar(100) NOT NULL,
  `class` varchar(30) NOT NULL,
  `postfix` varchar(30) NOT NULL,
  `size` int(10) NOT NULL,
  `hits` mediumint(8) NOT NULL,
  `needperm` int(10) NOT NULL,
  `needscore` int(10) NOT NULL,
  `needexp` int(10) NOT NULL,
  `needprice` int(10) NOT NULL,
  `uptime` varchar(11) NOT NULL,
  `uid` int(10) NOT NULL,
  `remote` tinyint(3) NOT NULL,
  PRIMARY KEY  (`attachid`),
  KEY `topicid` (`topicid`,`postid`,`uid`)
)  TYPE=MyISAM;


--
-- 表的结构 `jieqi_info_column`
--

CREATE TABLE `jieqi_info_column` (
  `id` int(11) NOT NULL auto_increment,
  `ordid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `fid` int(11) NOT NULL,
  `faid` varchar(11) NOT NULL,
  `num` smallint(6) NOT NULL,
  `mx` varchar(20) NOT NULL,
  `tmp_list` varchar(50) NOT NULL,
  `tmp_content` varchar(50) NOT NULL,
  `tmp_lmsg` varchar(50) NOT NULL,
  `user_add` tinyint(1) NOT NULL,
  `user_edit` tinyint(1) NOT NULL,
  `user_del` tinyint(1) NOT NULL,
  `use_area` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

--
-- 表的结构 `jieqi_info_mx`
--

CREATE TABLE `jieqi_info_mx` (
  `mid` smallint(6) NOT NULL auto_increment,
  `mname` varchar(20) NOT NULL,
  `mtable` varchar(50) NOT NULL,
  `mtype` double NOT NULL,
  PRIMARY KEY  (`mid`)
)  TYPE=MyISAM;

--
-- 表的结构 `jieqi_info_newfiled`
--

CREATE TABLE `jieqi_info_newfiled` (
  `fid` int(11) NOT NULL auto_increment,
  `mid` int(11) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `fcon` varchar(20) NOT NULL,
  `ftype` varchar(10) NOT NULL,
  `fvalue` varchar(50) NOT NULL,
  `flong` varchar(3) NOT NULL,
  `fres` mediumtext NOT NULL,
  `fround` tinyint(1) NOT NULL,
  `fhtml` mediumtext NOT NULL,
  PRIMARY KEY  (`fid`),
  UNIQUE KEY `fname` (`fname`)
)  TYPE=MyISAM;


--
-- 表的结构 `jieqi_info_posts`
--

CREATE TABLE `jieqi_info_posts` (
  `postid` int(10) NOT NULL auto_increment,
  `siteid` smallint(6) NOT NULL default '0',
  `topicid` int(10) NOT NULL default '0',
  `istopic` tinyint(1) NOT NULL default '0',
  `replypid` int(10) NOT NULL default '0',
  `ownerid` int(10) NOT NULL default '0',
  `posterid` int(10) NOT NULL default '0',
  `poster` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `posterip` varchar(25) NOT NULL default '',
  `iconid` tinyint(3) NOT NULL default '0',
  `attachment` text NOT NULL,
  `subject` varchar(80) NOT NULL default '',
  `posttext` mediumtext NOT NULL,
  `size` int(10) NOT NULL default '0',
  PRIMARY KEY  (`postid`),
  KEY `ownerid` (`ownerid`),
  KEY `ptopicid` (`topicid`,`posttime`)
)  TYPE=MyISAM;

--
-- 表的结构 `jieqi_info_table`
--

CREATE TABLE `jieqi_info_table` (
  `id` int(11) NOT NULL auto_increment,
  `sid` int(11) NOT NULL,
  `cid` int(9) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` mediumtext NOT NULL,
  `imgs` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
)  TYPE=MyISAM;

--
-- 表的结构 `jieqi_info_topics`
--

CREATE TABLE `jieqi_info_topics` (
  `topicid` mediumint(8) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `ownerid` int(10) unsigned NOT NULL default '0',
  `title` varchar(80) NOT NULL default '',
  `posterid` int(10) NOT NULL default '0',
  `poster` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `replyerid` int(10) NOT NULL default '0',
  `replyer` varchar(30) NOT NULL default '',
  `replytime` int(10) NOT NULL default '0',
  `views` mediumint(8) unsigned NOT NULL default '0',
  `replies` mediumint(8) unsigned NOT NULL default '0',
  `islock` tinyint(1) NOT NULL default '0',
  `istop` int(11) NOT NULL default '0',
  `isgood` tinyint(1) NOT NULL default '0',
  `rate` tinyint(1) NOT NULL default '0',
  `attachment` tinyint(1) NOT NULL default '0',
  `needperm` int(10) unsigned NOT NULL default '0',
  `needscore` int(10) unsigned NOT NULL default '0',
  `needexp` int(10) unsigned NOT NULL default '0',
  `needprice` int(10) unsigned NOT NULL default '0',
  `sortid` tinyint(3) NOT NULL default '0',
  `iconid` tinyint(3) NOT NULL default '0',
  `typeid` tinyint(3) NOT NULL default '0',
  `lastinfo` varchar(250) NOT NULL default '',
  `linkurl` varchar(100) NOT NULL default '',
  `size` int(11) NOT NULL default '0',
  PRIMARY KEY  (`topicid`),
  KEY `ownerid` (`ownerid`,`istop`,`replytime`)
)  TYPE=MyISAM;