CREATE TABLE `jieqi_article_articlelog` (
  `logid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `logtime` int(11) unsigned NOT NULL default '0',
  `userid` int(11) unsigned NOT NULL default '0',
  `username` varchar(30) binary NOT NULL default '',
  `articleid` int(11) unsigned NOT NULL default '0',
  `articlename` varchar(255) binary NOT NULL default '',
  `chapterid` int(11) unsigned NOT NULL default '0',
  `chaptername` varchar(255) NOT NULL default '',
  `reason` text NOT NULL,
  `chginfo` text NOT NULL,
  `chglog` text NOT NULL,
  `ischapter` tinyint(1) unsigned NOT NULL default '0',
  `isdel` tinyint(1) unsigned NOT NULL default '0',
  `databak` mediumtext NOT NULL,
  PRIMARY KEY  (`logid`),
  KEY `userid` (`userid`),
  KEY `ischapter` (`ischapter`),
  KEY `isdel` (`isdel`)
) TYPE=MyISAM;

CREATE TABLE `jieqi_article_applywriter` (
  `applyid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `applytime` int(11) unsigned NOT NULL default '0',
  `applyuid` int(11) unsigned NOT NULL default '0',
  `applyname` varchar(30) binary NOT NULL default '',
  `penname` varchar(30) binary NOT NULL default '',
  `authtime` int(11) unsigned NOT NULL default '0',
  `authuid` int(11) unsigned NOT NULL default '0',
  `authname` varchar(30) binary NOT NULL default '',
  `applytitle` varchar(100) NOT NULL default '',
  `applytext` mediumtext NOT NULL,
  `applysize` int(11) unsigned NOT NULL default '0',
  `authnote` text NOT NULL,
  `applyflag` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`applyid`),
  KEY `applyflag` (`applyflag`),
  KEY `applyename` (`applyname`),
  KEY `authname` (`authname`)
) TYPE=MyISAM;

CREATE TABLE `jieqi_article_avote` (
  `voteid` int(11) unsigned NOT NULL auto_increment,
  `articleid` int(11) unsigned NOT NULL default '0',
  `posterid` int(11) NOT NULL default '0',
  `poster` varchar(30) NOT NULL default '',
  `posttime` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `item1` varchar(100) NOT NULL default '',
  `item2` varchar(100) NOT NULL default '',
  `item3` varchar(100) NOT NULL default '',
  `item4` varchar(100) NOT NULL default '',
  `item5` varchar(100) NOT NULL default '',
  `item6` varchar(100) NOT NULL default '',
  `item7` varchar(100) NOT NULL default '',
  `item8` varchar(100) NOT NULL default '',
  `item9` varchar(100) NOT NULL default '',
  `item10` varchar(100) NOT NULL default '',
  `useitem` tinyint(2) NOT NULL default '0',
  `description` text NOT NULL,
  `ispublish` tinyint(1) NOT NULL default '0',
  `mulselect` tinyint(1) NOT NULL default '0',
  `timelimit` tinyint(1) NOT NULL default '0',
  `needlogin` tinyint(1) NOT NULL default '0',
  `starttime` int(11) NOT NULL default '0',
  `endtime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`voteid`),
  KEY `articleid` (`articleid`,`ispublish`)
) TYPE=MyISAM;

CREATE TABLE `jieqi_article_avstat` (
  `statid` int(11) unsigned NOT NULL auto_increment,
  `voteid` int(11) unsigned NOT NULL default '0',
  `statall` int(11) unsigned NOT NULL default '0',
  `stat1` int(11) unsigned NOT NULL default '0',
  `stat2` int(11) unsigned NOT NULL default '0',
  `stat3` int(11) unsigned NOT NULL default '0',
  `stat4` int(11) unsigned NOT NULL default '0',
  `stat5` int(11) unsigned NOT NULL default '0',
  `stat6` int(11) unsigned NOT NULL default '0',
  `stat7` int(11) unsigned NOT NULL default '0',
  `stat8` int(11) unsigned NOT NULL default '0',
  `stat9` int(11) unsigned NOT NULL default '0',
  `stat10` int(11) unsigned NOT NULL default '0',
  `canstat` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`statid`),
  KEY `voteid` (`voteid`,`canstat`)
) TYPE=MyISAM;


UPDATE `jieqi_system_configs` SET cname='maxbookmarks' WHERE modname='article' AND cname='bookcasenum';
UPDATE `jieqi_system_configs` SET cname='dayvotes' WHERE modname='article' AND cname='pollnum';
UPDATE `jieqi_system_configs` SET cdescription='指的是隐藏在阅读页面的一些文字，其中<{$randtext}>将被替换成一组随机字符。例如：“<span style="display:none">版权所有：<{$randtext}>杰奇网络</span>”（style="display:none" 是指默认不可见，但是页面上全选复制时候会包含本部分内容）' WHERE modname='article' AND cname='textwatermark';

DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='badreviewwords';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='badarticlewords';

UPDATE `jieqi_system_blocks` SET contenttype=4 WHERE modname='article' AND classname='BlockArticleArticlelist';


INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'pageimagecode', '阅读页面图片显示代码', '<div class="divimage"><img src="<{$imageurl}>" border="0" class="imagecontent"></div>', '文章生成阅读页面时候，显示图片附件的html代码。其中<{$imageurl}>将被替换成实际图片地址', 0, 2, '', 13860, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'txtarticlehead', 'TXT全文头部附加内容', '', '生成TXT全文下载，内容头部和尾部可以附加一些预想设置的内容，比如本站名字地址。', 0, 2, '', 13870, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'txtarticlefoot', 'TXT全文尾部附加内容', '', '生成TXT全文下载，内容头部和尾部可以附加一些预想设置的内容，比如本站名字地址。', 0, 2, '', 13880, '显示控制');


INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'checkappwriter', '申请作者是否需要审核', '1', '需要审核时会员提交申请，管理员审核。不需要审核则用户点申请，直接成为作者', 0, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 14900, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'scoreuservote', '每次推荐增加积分', '0', '', 0, 3, '', 30610, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'uservotescore', '超过推荐次数后继续推荐每次减少积分', '0', '如果设置成 0 则不允许超过次数后继续推荐', 0, 3, '', 30620, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'voteminsize', '多少字数以上的文章才允许推荐', '0', '如果设置成 0 则表示不限制字数', 0, 3, '', 30630, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'addcasescore', '超过书架收藏量后继续收藏每本减少积分', '0', '如果设置成 0 则不允许超过收藏量限制', 0, 3, '', 30640, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'articlevote', '文章是否允许发起投票', '0', '本项设置是否允许投票和最大允许一个投票选项', 0, 7, 'a:10:{i:0;s:10:"不允许投票";i:2;s:11:"最大2个选项";i:3;s:11:"最大3个选项";i:4;s:11:"最大4个选项";i:5;s:11:"最大5个选项";i:6;s:11:"最大6个选项";i:7;s:11:"最大7个选项";i:8;s:11:"最大8个选项";i:9;s:11:"最大9个选项";i:10;s:12:"最大10个选项";}', 14950, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'scoretxtdown', '下载TXT全文扣积分', '0', '', 0, 3, '', 30660, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'eachlinknum', '每篇文章允许互换链接数', '0', '即一篇文章可以在信息页面设置几本站内的书作为推荐，设为0表示开启本功能', 0, 3, '', 15300, '显示控制');

INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'article', 'setwriter', '审核会员申请成为作者', '', '');