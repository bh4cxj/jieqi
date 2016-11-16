-- phpMyAdmin SQL Dump
-- version 2.10.2-rc1
-- http://www.phpmyadmin.net
-- 
-- 主机: localhost
-- 生成日期: 2009 年 02 月 12 日 17:57
-- 服务器版本: 5.0.45
-- PHP 版本: 5.1.4

-- 
-- 数据库: `jieqi16`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_group_admingroup`
-- 

CREATE TABLE `jieqi_group_admingroup` (
  `admingid` smallint(6) unsigned NOT NULL default '0',
  `allowmanalbum` tinyint(1) NOT NULL default '0' COMMENT '管理相册',
  `allowmanparty` tinyint(1) NOT NULL default '0' COMMENT '管理活动',
  `allowmantopic` tinyint(1) NOT NULL default '0' COMMENT '管理话题',
  `allowmanmember` tinyint(1) NOT NULL default '0' COMMENT '管理成员',
  `allowmanbasic` tinyint(1) NOT NULL default '0' COMMENT '基本设置'
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_group_album`
-- 

CREATE TABLE `jieqi_group_album` (
  `albumid` int(11) unsigned NOT NULL auto_increment COMMENT '编号',
  `albumname` varchar(80) NOT NULL COMMENT '相册名称',
  `albumorder` int(11) NOT NULL default '0' COMMENT '排序',
  `lastuptime` int(11) NOT NULL default '0' COMMENT '最后更新时间',
  `lastphotoid` int(11) NOT NULL default '0' COMMENT '最后一张照片ID',
  `nums` int(11) unsigned NOT NULL default '0' COMMENT '照片数量',
  `intro` varchar(40) NOT NULL COMMENT '简介',
  `hits` int(11) unsigned NOT NULL default '0' COMMENT '点击数',
  `needexp` int(11) unsigned NOT NULL default '0' COMMENT '需要经验值',
  `gid` int(11) NOT NULL default '0' COMMENT '工会ID',
  `poster` char(30) NOT NULL COMMENT '发表者',
  `posterid` int(11) NOT NULL default '0' COMMENT '主题',
  `defaultflag` tinyint(1) NOT NULL default '0' COMMENT '默认相删标识',
  `lastpostfix` char(30) NOT NULL COMMENT '最后一张照片扩展名',
  PRIMARY KEY  (`albumid`)
) TYPE=MyISAM;

-- 
-- 表的结构 `jieqi_group_attachs`
-- 

CREATE TABLE `jieqi_group_attachs` (
  `attachid` mediumint(8) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `topicid` mediumint(8) unsigned NOT NULL default '0',
  `postid` int(10) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  `class` varchar(30) NOT NULL default '',
  `postfix` varchar(30) NOT NULL default '',
  `size` int(10) unsigned NOT NULL default '0',
  `hits` mediumint(8) unsigned NOT NULL default '0',
  `needperm` int(10) unsigned NOT NULL default '0',
  `needscore` int(10) unsigned NOT NULL default '0',
  `needexp` int(10) unsigned NOT NULL default '0',
  `needprice` int(10) unsigned NOT NULL default '0',
  `uptime` int(10) NOT NULL default '0',
  `uid` int(10) unsigned NOT NULL default '0',
  `remote` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`attachid`),
  KEY `topicid` (`topicid`),
  KEY `postid` (`postid`,`attachid`),
  KEY `uid` (`uid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_group_gcat`
-- 

CREATE TABLE `jieqi_group_gcat` (
  `gcatid` smallint(5) unsigned NOT NULL auto_increment COMMENT '序号',
  `gcatname` varchar(20) NOT NULL COMMENT '类别名称',
  `gcatorder` smallint(6) NOT NULL default '0' COMMENT '排序',
  PRIMARY KEY  (`gcatid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_group_group`
-- 

CREATE TABLE `jieqi_group_group` (
  `gid` int(10) unsigned NOT NULL auto_increment COMMENT '编号',
  `gname` varchar(20) NOT NULL COMMENT '工会名称',
  `gcatid` int(10) unsigned NOT NULL COMMENT '类别ID',
  `gdomain` varchar(20) NOT NULL COMMENT '域名',
  `guid` int(11) unsigned NOT NULL default '0' COMMENT '创建者ID',
  `guname` varchar(15) NOT NULL default '0' COMMENT '创建者用户名',
  `gowner_name` varchar(30) NOT NULL COMMENT '创建者呢称',
  `gprovince` varchar(30) NOT NULL,
  `gcity` varchar(30) NOT NULL,
  `gpic` varchar(32) NOT NULL COMMENT 'LOGO',
  `gbrief` text NOT NULL COMMENT '简介',
  `gaudit` smallint(6) NOT NULL COMMENT '是否需要审核',
  `gtime` int(11) NOT NULL COMMENT '创建时间',
  `gtheme` varchar(40) NOT NULL COMMENT '风格模板',
  `gmembers` int(11) NOT NULL default '0' COMMENT '成员数量',
  `gparties` int(11) NOT NULL default '0' COMMENT '活动数量',
  `gpics` int(11) NOT NULL default '0' COMMENT '照片数量',
  `topicnum` int(11) NOT NULL default '0' COMMENT '话题数量',
  `gtopics` int(11) NOT NULL default '0',
  PRIMARY KEY  (`gid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_group_member`
-- 

CREATE TABLE `jieqi_group_member` (
  `mid` int(11) unsigned NOT NULL auto_increment COMMENT '编号',
  `uid` int(11) unsigned NOT NULL COMMENT '会员编号',
  `uname` char(30) NOT NULL COMMENT '用户名',
  `name` varchar(30) NOT NULL COMMENT '呢称',
  `mtime` int(11) default NULL COMMENT '加入时间',
  `offer` int(11) unsigned NOT NULL default '0',
  `gid` int(11) NOT NULL default '0' COMMENT '工会ID',
  `membergid` int(11) NOT NULL default '0' COMMENT '成员组ID',
  `mswitch` tinyint(1) NOT NULL default '0' COMMENT '是否审核通过',
  `creater` tinyint(1) NOT NULL default '0' COMMENT '是否创建者',
  PRIMARY KEY  (`mid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_group_membergroup`
-- 

CREATE TABLE `jieqi_group_membergroup` (
  `membergid` smallint(6) unsigned NOT NULL auto_increment COMMENT '编号',
  `admingid` smallint(6) unsigned NOT NULL default '0' COMMENT '组ID',
  `membergtype` enum('system','special','member','default') default 'member' COMMENT '组类型',
  `membergtitle` char(30) NOT NULL COMMENT '会员组名称',
  `allowpostmessage` tinyint(1) NOT NULL default '0' COMMENT '发送消息权限',
  `allowpostparty` tinyint(1) NOT NULL default '0' COMMENT '发表活动权限',
  `allowreplyparty` tinyint(1) NOT NULL default '0' COMMENT '回复活动权限',
  `allowsignparty` tinyint(1) NOT NULL default '0',
  `allowposttopic` tinyint(1) NOT NULL default '0' COMMENT '发表话题权限',
  `allowreplytopic` tinyint(1) NOT NULL default '0' COMMENT '回复话题权限',
  `allowpostpic` tinyint(1) NOT NULL default '0' COMMENT '上传附件权限',
  PRIMARY KEY  (`membergid`),
  KEY `membergid` (`membergid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_group_party`
-- 

CREATE TABLE `jieqi_group_party` (
  `pid` int(11) unsigned NOT NULL auto_increment COMMENT '编号',
  `ptitle` varchar(50) NOT NULL COMMENT '主题',
  `pcontent` text NOT NULL COMMENT '内容',
  `pstart` int(11) unsigned NOT NULL default '0' COMMENT '开始时间',
  `pstop` int(11) unsigned NOT NULL default '0' COMMENT '结束时间',
  `pplace` varchar(200) default NULL,
  `ptime` int(11) unsigned NOT NULL default '0' COMMENT '发表时间',
  `pmaxnums` smallint(6) unsigned NOT NULL default '0' COMMENT '限定最大人数',
  `pvisits` smallint(6) unsigned NOT NULL default '0',
  `pnums` smallint(6) unsigned NOT NULL default '0',
  `gid` int(11) unsigned NOT NULL default '0' COMMENT '工会ID',
  `gname` varchar(50) default NULL COMMENT '工会名称',
  `uid` int(11) default NULL COMMENT '发布人ID',
  `uname` varchar(50) default NULL COMMENT '发布人用户名',
  `ptop` tinyint(1) NOT NULL default '0' COMMENT '置顶',
  `replies` int(11) NOT NULL default '0' COMMENT '回复信息数',
  `passnums` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pid`),
  KEY `ptitle` (`ptitle`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_group_partyreply`
-- 

CREATE TABLE `jieqi_group_partyreply` (
  `rid` int(11) unsigned NOT NULL auto_increment COMMENT '编号',
  `pid` int(11) unsigned NOT NULL default '0' COMMENT '活动编号',
  `rtime` int(11) unsigned NOT NULL default '0' COMMENT '回复时间',
  `rcontent` text COMMENT '内容',
  `uid` int(11) NOT NULL default '0' COMMENT '回复人ID',
  `uname` char(30) default NULL COMMENT '回复人呢称',
  `gid` int(11) NOT NULL default '0' COMMENT '工会ID',
  PRIMARY KEY  (`rid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_group_photo`
-- 

CREATE TABLE `jieqi_group_photo` (
  `photoid` int(11) unsigned NOT NULL auto_increment COMMENT '照片编号',
  `albumid` int(11) NOT NULL default '0' COMMENT '相册编号',
  `name` varchar(80) NOT NULL COMMENT '相册名字',
  `intro` varchar(40) NOT NULL COMMENT '照片说明',
  `postfix` varchar(30) NOT NULL COMMENT '照片扩展',
  `size` int(11) unsigned NOT NULL default '0' COMMENT '照片大小/字节',
  `hits` int(11) unsigned NOT NULL default '0' COMMENT '点击量',
  `needexp` int(11) unsigned NOT NULL default '0' COMMENT '需要经验值',
  `uptime` int(11) NOT NULL default '0' COMMENT '上传时间',
  `gid` int(11) NOT NULL default '0' COMMENT '工会编号',
  `poster` char(30) NOT NULL COMMENT '上传人编号',
  `posterid` int(11) NOT NULL default '0' COMMENT '上传人用户名',
  PRIMARY KEY  (`photoid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_group_poll`
-- 

CREATE TABLE `jieqi_group_poll` (
  `topicid` mediumint(8) unsigned NOT NULL default '0',
  `multiple` tinyint(1) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '0',
  `maxchoices` tinyint(3) unsigned NOT NULL default '0',
  `expiration` int(10) unsigned NOT NULL default '0',
  `gid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`topicid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_group_polloption`
-- 

CREATE TABLE `jieqi_group_polloption` (
  `polloptionid` int(10) unsigned NOT NULL auto_increment,
  `topicid` mediumint(8) unsigned NOT NULL default '0',
  `votes` mediumint(8) unsigned NOT NULL default '0',
  `displayorder` tinyint(3) NOT NULL default '0',
  `polloption` varchar(80) NOT NULL default '',
  `voterids` mediumtext NOT NULL,
  `gid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`polloptionid`),
  KEY `topicid` (`topicid`,`displayorder`)
) TYPE=MyISAM;

-- 
-- 表的结构 `jieqi_group_posts`
-- 

CREATE TABLE `jieqi_group_posts` (
  `postid` int(10) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `topicid` int(10) unsigned NOT NULL default '0',
  `istopic` tinyint(1) NOT NULL default '0',
  `replypid` int(10) unsigned NOT NULL default '0',
  `ownerid` int(10) unsigned NOT NULL default '0',
  `posterid` int(10) NOT NULL default '0',
  `poster` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `posterip` varchar(25) NOT NULL default '',
  `editorid` int(10) NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) NOT NULL default '0',
  `editorip` varchar(25) NOT NULL default '',
  `editnote` varchar(250) NOT NULL default '',
  `iconid` tinyint(3) NOT NULL default '0',
  `attachment` text NOT NULL,
  `subject` varchar(80) NOT NULL default '',
  `posttext` mediumtext NOT NULL,
  `size` int(10) NOT NULL default '0',
  PRIMARY KEY  (`postid`),
  KEY `ownerid` (`ownerid`),
  KEY `ptopicid` (`topicid`,`posttime`)
) TYPE=MyISAM;

-- 
-- 表的结构 `jieqi_group_sign`
-- 

CREATE TABLE `jieqi_group_sign` (
  `signid` int(11) NOT NULL auto_increment,
  `gid` int(11) unsigned NOT NULL default '0',
  `pid` int(11) unsigned NOT NULL default '0',
  `uid` int(11) unsigned NOT NULL default '0',
  `uname` char(30) default NULL,
  `signtime` int(11) NOT NULL default '0',
  `men` int(11) unsigned NOT NULL default '0',
  `women` int(11) unsigned NOT NULL default '0',
  `nums` int(11) unsigned NOT NULL default '0',
  `linkway` char(100) default NULL,
  `signflag` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`signid`)
) TYPE=MyISAM;

-- --------------------------------------------------------


-- 
-- 表的结构 `jieqi_group_topics`
-- 

CREATE TABLE `jieqi_group_topics` (
  `topicid` mediumint(8) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `ownerid` int(10) unsigned NOT NULL default '0',
  `title` varchar(80) NOT NULL default '',
  `posterid` int(10) NOT NULL default '0',
  `poster` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `replierid` int(10) NOT NULL default '0',
  `replier` varchar(30) NOT NULL default '',
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
  KEY `ownerid` (`ownerid`,`istop`,`replytime`),
  KEY `posterid` (`posterid`,`replytime`)
) TYPE=MyISAM;
