-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2009 年 01 月 16 日 08:01
-- 服务器版本: 5.0.27
-- PHP 版本: 5.2.1
--
-- 数据库: `jieqi150`
--

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_cartoon_applywriter`
--

DROP TABLE IF EXISTS `jieqi_cartoon_applywriter`;
CREATE TABLE `jieqi_cartoon_applywriter` (
  `id` int(11) NOT NULL auto_increment,
  `uname` varchar(20) NOT NULL,
  `text` text NOT NULL,
  `addtime` varchar(10) NOT NULL,
  `type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uname` (`uname`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_cartoon_bookcase`
--

DROP TABLE IF EXISTS `jieqi_cartoon_bookcase`;
CREATE TABLE `jieqi_cartoon_bookcase` (
  `caseid` int(11) unsigned NOT NULL auto_increment,
  `cartoonid` int(11) unsigned NOT NULL default '0',
  `cartoonname` varchar(50) NOT NULL default '',
  `userid` int(11) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `volumeid` int(11) unsigned NOT NULL default '0',
  `volumename` varchar(100) NOT NULL default '',
  `volumeorder` smallint(6) unsigned NOT NULL default '0',
  `page` int(11) NOT NULL default '0',
  `joindate` int(11) unsigned NOT NULL default '0',
  `lastvisit` int(11) unsigned NOT NULL default '0',
  `flag` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`caseid`),
  KEY `cartoonid` (`cartoonid`),
  KEY `userid` (`userid`),
  KEY `chapterid` (`volumeid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_cartoon_cartoon`
--

DROP TABLE IF EXISTS `jieqi_cartoon_cartoon`;
CREATE TABLE `jieqi_cartoon_cartoon` (
  `cartoonid` int(11) unsigned NOT NULL auto_increment,
  `sortid` int(11) NOT NULL default '0',
  `ftpid` int(11) unsigned NOT NULL default '0',
  `postdate` int(11) unsigned NOT NULL default '0',
  `lastupdate` int(11) unsigned NOT NULL default '0',
  `cartoonname` varchar(50) NOT NULL default '',
  `keywords` varchar(50) NOT NULL default '',
  `initial` char(1) NOT NULL default '',
  `authorid` int(11) unsigned NOT NULL default '0',
  `author` varchar(250) NOT NULL default '',
  `posterid` int(11) unsigned NOT NULL default '0',
  `poster` varchar(30) NOT NULL default '',
  `agentid` int(11) unsigned NOT NULL default '0',
  `agent` varchar(30) NOT NULL default '',
  `intro` mediumtext NOT NULL,
  `notice` mediumtext NOT NULL,
  `setting` mediumtext NOT NULL,
  `volumes` smallint(6) unsigned NOT NULL default '0',
  `images` int(11) NOT NULL default '0',
  `size` int(11) NOT NULL default '0',
  `lastvolume` varchar(100) NOT NULL default '',
  `lastvolumeid` int(11) NOT NULL default '0',
  `lastvisit` int(11) unsigned NOT NULL default '0',
  `dayvisit` int(11) unsigned NOT NULL default '0',
  `weekvisit` int(11) unsigned NOT NULL default '0',
  `monthvisit` int(11) unsigned NOT NULL default '0',
  `allvisit` int(11) unsigned NOT NULL default '0',
  `lastvote` int(11) unsigned NOT NULL default '0',
  `dayvote` int(11) unsigned NOT NULL default '0',
  `weekvote` int(11) unsigned NOT NULL default '0',
  `monthvote` int(11) unsigned NOT NULL default '0',
  `allvote` int(11) unsigned NOT NULL default '0',
  `goodnum` int(11) unsigned NOT NULL default '0',
  `badnum` int(11) unsigned NOT NULL default '0',
  `toptime` int(11) unsigned NOT NULL default '0',
  `language` tinyint(1) unsigned NOT NULL default '0',
  `color` tinyint(1) unsigned NOT NULL default '0',
  `definition` tinyint(1) unsigned NOT NULL default '0',
  `mask` tinyint(1) unsigned NOT NULL default '0',
  `package` tinyint(1) unsigned NOT NULL default '0',
  `cartoontype` tinyint(1) unsigned NOT NULL default '0',
  `permission` tinyint(1) unsigned NOT NULL default '0',
  `firstflag` tinyint(1) unsigned NOT NULL default '0',
  `fullflag` tinyint(1) unsigned NOT NULL default '0',
  `imgflag` tinyint(1) unsigned NOT NULL default '0',
  `power` tinyint(1) unsigned NOT NULL default '0',
  `display` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cartoonid`),
  KEY `cartoonname` (`cartoonname`),
  KEY `posterid` (`posterid`),
  KEY `authorid` (`authorid`),
  KEY `initial` (`initial`),
  KEY `display` (`display`),
  KEY `lastupdate` (`lastupdate`),
  KEY `author` (`author`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_cartoon_notebook`
--

DROP TABLE IF EXISTS `jieqi_cartoon_notebook`;
CREATE TABLE `jieqi_cartoon_notebook` (
  `noteid` int(11) unsigned NOT NULL auto_increment,
  `postdate` int(11) unsigned NOT NULL default '0',
  `replydate` int(11) unsigned NOT NULL default '0',
  `userid` int(11) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posterid` int(11) unsigned NOT NULL default '0',
  `postername` varchar(30) NOT NULL default '',
  `replyid` int(11) unsigned NOT NULL default '0',
  `replyname` varchar(30) NOT NULL default '',
  `notetitle` varchar(100) NOT NULL default '',
  `notetext` mediumtext NOT NULL,
  `replytext` mediumtext NOT NULL,
  `topflag` tinyint(1) unsigned NOT NULL default '0',
  `goodflag` tinyint(1) unsigned NOT NULL default '0',
  `display` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`noteid`),
  KEY `userid` (`userid`),
  KEY `posterid` (`posterid`),
  KEY `topflag` (`topflag`),
  KEY `display` (`display`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_cartoon_review`
--

DROP TABLE IF EXISTS `jieqi_cartoon_review`;
CREATE TABLE `jieqi_cartoon_review` (
  `reviewid` int(11) unsigned NOT NULL auto_increment,
  `postdate` int(11) unsigned NOT NULL default '0',
  `cartoonid` int(11) unsigned NOT NULL default '0',
  `cartoonname` varchar(50) NOT NULL default '',
  `userid` int(11) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `reviewtitle` varchar(100) NOT NULL default '',
  `reviewtext` mediumtext NOT NULL,
  `topflag` tinyint(1) unsigned NOT NULL default '0',
  `goodflag` tinyint(1) unsigned NOT NULL default '0',
  `display` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`reviewid`),
  KEY `cartoonid` (`cartoonid`),
  KEY `userid` (`userid`),
  KEY `display` (`display`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_cartoon_searchcahe`
--

DROP TABLE IF EXISTS `jieqi_cartoon_searchcahe`;
CREATE TABLE `jieqi_cartoon_searchcahe` (
  `cacheid` int(11) unsigned NOT NULL auto_increment,
  `searchtime` int(11) unsigned NOT NULL default '0',
  `hashid` varchar(32) NOT NULL default '0',
  `keywords` varchar(60) NOT NULL default '',
  `searchtype` tinyint(1) NOT NULL default '0',
  `results` int(11) unsigned NOT NULL default '0',
  `aids` text NOT NULL,
  PRIMARY KEY  (`cacheid`),
  UNIQUE KEY `hashid` (`hashid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_cartoon_volume`
--

DROP TABLE IF EXISTS `jieqi_cartoon_volume`;
CREATE TABLE `jieqi_cartoon_volume` (
  `volumeid` int(11) unsigned NOT NULL auto_increment,
  `cartoonid` int(11) unsigned NOT NULL default '0',
  `cartoonname` varchar(50) NOT NULL default '',
  `posterid` int(11) unsigned NOT NULL default '0',
  `poster` varchar(30) NOT NULL default '',
  `postdate` int(11) unsigned NOT NULL default '0',
  `lastupdate` int(11) unsigned NOT NULL default '0',
  `volumename` varchar(100) NOT NULL default '',
  `volumeorder` smallint(6) unsigned NOT NULL default '0',
  `power` tinyint(1) unsigned NOT NULL default '0',
  `display` tinyint(1) unsigned NOT NULL default '0',
  `images` int(11) NOT NULL default '0',
  `censorid` int(11) NOT NULL default '0',
  `censor` varchar(30) NOT NULL,
  `censorstatus` tinyint(1) NOT NULL default '0',
  `censorts` int(11) NOT NULL,
  `ftpid` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`volumeid`),
  KEY `cartoonid` (`cartoonid`),
  KEY `chapterorder` (`volumeorder`),
  KEY `display` (`display`),
  KEY `cartoonname` (`cartoonname`,`volumename`),
  KEY `lastupdate` (`lastupdate`)
) TYPE=MyISAM;

