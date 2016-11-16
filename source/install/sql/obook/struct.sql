-- --------------------------------------------------------

--
-- 表的结构 `jieqi_obook_obook`
--

DROP TABLE IF EXISTS `jieqi_obook_obook`;
CREATE TABLE `jieqi_obook_obook` (
  `obookid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `postdate` int(11) unsigned NOT NULL default '0',
  `lastupdate` int(11) unsigned NOT NULL default '0',
  `obookname` varchar(100) binary NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `articleid` int(11) unsigned NOT NULL default '0',
  `initial` char(1) NOT NULL default '',
  `sortid` smallint(6) unsigned NOT NULL default '0',
  `intro` text NOT NULL,
  `notice` text NOT NULL,
  `setting` text NOT NULL,
  `lastvolumeid` int(11) unsigned NOT NULL default '0',
  `lastvolume` varchar(255) NOT NULL default '',
  `lastchapterid` int(11) unsigned NOT NULL default '0',
  `lastchapter` varchar(255) NOT NULL default '',
  `chapters` smallint(6) unsigned NOT NULL default '0',
  `size` int(11) unsigned NOT NULL default '0',
  `authorid` int(11) unsigned NOT NULL default '0',
  `author` varchar(50) binary NOT NULL default '',
  `aintro` text NOT NULL,
  `agentid` int(11) unsigned NOT NULL default '0',
  `agent` varchar(50) binary NOT NULL default '',
  `posterid` int(11) unsigned NOT NULL default '0',
  `poster` varchar(50) binary NOT NULL default '',
  `publishid` int(11) unsigned NOT NULL default '0',
  `tbookinfo` text NOT NULL,
  `toptime` int(11) unsigned NOT NULL default '0',
  `goodnum` int(11) unsigned NOT NULL default '0',
  `badnum` int(11) unsigned NOT NULL default '0',
  `fullflag` tinyint(1) unsigned NOT NULL default '0',
  `imgflag` tinyint(3) unsigned NOT NULL default '0',
  `saleprice` int(11) unsigned NOT NULL default '0',
  `vipprice` int(11) unsigned NOT NULL default '0',
  `sumegold` int(11) unsigned NOT NULL default '0',
  `sumesilver` int(11) unsigned NOT NULL default '0',
  `normalsale` int(11) unsigned NOT NULL default '0',
  `vipsale` int(11) unsigned NOT NULL default '0',
  `freesale` int(11) unsigned NOT NULL default '0',
  `bespsale` int(11) unsigned NOT NULL default '0',
  `totalsale` int(11) unsigned NOT NULL default '0',
  `daysale` int(11) unsigned NOT NULL default '0',
  `weeksale` int(11) unsigned NOT NULL default '0',
  `monthsale` int(11) unsigned NOT NULL default '0',
  `allsale` int(11) unsigned NOT NULL default '0',
  `lastsale` int(11) unsigned NOT NULL default '0',
  `canvip` tinyint(1) unsigned NOT NULL default '0',
  `canfree` tinyint(1) unsigned NOT NULL default '0',
  `canbesp` tinyint(1) unsigned NOT NULL default '0',
  `hasebook` tinyint(1) unsigned NOT NULL default '0',
  `hastbook` tinyint(1) unsigned NOT NULL default '0',
  `state` tinyint(1) unsigned NOT NULL default '0',
  `flag` tinyint(1) unsigned NOT NULL default '0',
  `display` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`obookid`),
  KEY `articleid` (`articleid`),
  KEY `obookname` (`obookname`),
  KEY `display` (`display`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_obook_obookcase`
--

DROP TABLE IF EXISTS `jieqi_obook_obookcase`;
CREATE TABLE `jieqi_obook_obookcase` (
  `ocaseid` int(11) unsigned NOT NULL auto_increment,
  `obookid` int(11) unsigned NOT NULL default '0',
  `articleid` int(11) unsigned NOT NULL default '0',
  `obookname` varchar(255) binary NOT NULL default '',
  `userid` int(11) unsigned NOT NULL default '0',
  `username` varchar(30) binary NOT NULL default '',
  `ochapterid` int(11) unsigned NOT NULL default '0',
  `chaptername` varchar(255) binary NOT NULL default '',
  `chapterorder` smallint(6) unsigned NOT NULL default '0',
  `joindate` int(11) unsigned NOT NULL default '0',
  `lastvisit` int(11) unsigned NOT NULL default '0',
  `flag` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ocaseid`),
  KEY `obookid` (`obookid`),
  KEY `articleid` (`articleid`),
  KEY `userid` (`userid`),
  KEY `chapterid` (`ochapterid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_obook_obuyinfo`
--

DROP TABLE IF EXISTS `jieqi_obook_obuyinfo`;
CREATE TABLE `jieqi_obook_obuyinfo` (
  `obuyinfoid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `osaleid` int(11) unsigned NOT NULL default '0',
  `buytime` int(11) unsigned NOT NULL default '0',
  `userid` int(11) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `obookid` int(11) unsigned NOT NULL default '0',
  `ochapterid` int(11) unsigned NOT NULL default '0',
  `obookname` varchar(100) binary NOT NULL default '',
  `chaptername` varchar(100) NOT NULL default '',
  `lastread` int(11) unsigned NOT NULL default '0',
  `readnum` int(11) unsigned NOT NULL default '0',
  `checkcode` varchar(10) NOT NULL default '',
  `state` tinyint(1) unsigned NOT NULL default '0',
  `flag` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`obuyinfoid`),
  KEY `osaleid` (`osaleid`),
  KEY `userid` (`userid`),
  KEY `obookid` (`obookid`),
  KEY `ochapterid` (`ochapterid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_obook_ochapter`
--

DROP TABLE IF EXISTS `jieqi_obook_ochapter`;
CREATE TABLE `jieqi_obook_ochapter` (
  `ochapterid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `obookid` int(11) unsigned NOT NULL default '0',
  `postdate` int(11) unsigned NOT NULL default '0',
  `lastupdate` int(11) unsigned NOT NULL default '0',
  `buytime` int(11) unsigned NOT NULL default '0',
  `version` smallint(3) unsigned NOT NULL default '0',
  `obookname` varchar(100) binary NOT NULL default '',
  `chaptername` varchar(100) NOT NULL default '',
  `chaptertype` tinyint(1) unsigned NOT NULL default '0',
  `chapterorder` smallint(6) unsigned NOT NULL default '0',
  `volumeid` int(11) unsigned NOT NULL default '0',
  `ointro` text NOT NULL,
  `size` int(11) unsigned NOT NULL default '0',
  `posterid` int(11) unsigned NOT NULL default '0',
  `poster` varchar(50) binary NOT NULL default '',
  `toptime` int(11) unsigned NOT NULL default '0',
  `picflag` tinyint(3) unsigned NOT NULL default '0',
  `saleprice` int(11) unsigned NOT NULL default '0',
  `vipprice` int(11) unsigned NOT NULL default '0',
  `sumegold` int(11) unsigned NOT NULL default '0',
  `sumesilver` int(11) unsigned NOT NULL default '0',
  `normalsale` int(11) unsigned NOT NULL default '0',
  `vipsale` int(11) unsigned NOT NULL default '0',
  `freesale` int(11) unsigned NOT NULL default '0',
  `bespsale` int(11) unsigned NOT NULL default '0',
  `totalsale` int(11) unsigned NOT NULL default '0',
  `daysale` int(11) unsigned NOT NULL default '0',
  `weeksale` int(11) unsigned NOT NULL default '0',
  `monthsale` int(11) unsigned NOT NULL default '0',
  `allsale` int(11) unsigned NOT NULL default '0',
  `lastsale` int(11) unsigned NOT NULL default '0',
  `canvip` tinyint(1) unsigned NOT NULL default '0',
  `canfree` tinyint(1) unsigned NOT NULL default '0',
  `canbesp` tinyint(1) unsigned NOT NULL default '0',
  `state` tinyint(1) unsigned NOT NULL default '0',
  `flag` tinyint(1) unsigned NOT NULL default '0',
  `display` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ochapterid`),
  KEY `obookid` (`obookid`),
  KEY `display` (`display`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_obook_ocontent`
--

DROP TABLE IF EXISTS `jieqi_obook_ocontent`;
CREATE TABLE `jieqi_obook_ocontent` (
  `ocontentid` int(11) unsigned NOT NULL auto_increment,
  `ochapterid` int(11) unsigned NOT NULL default '0',
  `ocontent` mediumtext NOT NULL,
  PRIMARY KEY  (`ocontentid`),
  UNIQUE KEY `ochapterid` (`ochapterid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_obook_osale`
--

DROP TABLE IF EXISTS `jieqi_obook_osale`;
CREATE TABLE `jieqi_obook_osale` (
  `osaleid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `buytime` int(11) unsigned NOT NULL default '0',
  `accountid` int(11) unsigned NOT NULL default '0',
  `account` varchar(30) NOT NULL default '',
  `obookid` int(11) unsigned NOT NULL default '0',
  `ochapterid` int(11) unsigned NOT NULL default '0',
  `obookname` varchar(100) binary NOT NULL default '',
  `chaptername` varchar(100) NOT NULL default '',
  `saleprice` int(11) unsigned NOT NULL default '0',
  `pricetype` tinyint(1) NOT NULL default '0',
  `paytype` tinyint(1) NOT NULL default '0',
  `payflag` tinyint(1) NOT NULL default '0',
  `paynote` varchar(255) NOT NULL default '',
  `state` tinyint(1) unsigned NOT NULL default '0',
  `flag` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`osaleid`),
  KEY `accountid` (`accountid`),
  KEY `account` (`account`),
  KEY `obookid` (`obookid`),
  KEY `ochapterid` (`ochapterid`),
  KEY `payflag` (`payflag`)
) TYPE=MyISAM;
