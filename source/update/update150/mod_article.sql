CREATE TABLE `jieqi_article_replies` (
  `replyid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `reviewid` int(11) unsigned NOT NULL default '0',
  `istopic` tinyint(1) NOT NULL default '0',
  `articleid` int(11) unsigned NOT NULL default '0',
  `posterid` int(11) NOT NULL default '0',
  `poster` varchar(30) NOT NULL default '',
  `posttime` int(11) NOT NULL default '0',
  `posterip` varchar(25) NOT NULL default '',
  `subject` varchar(60) NOT NULL default '',
  `posttext` mediumtext NOT NULL,
  `size` int(11) NOT NULL default '0',
  PRIMARY KEY  (`replyid`),
  KEY `articleid` (`articleid`),
  KEY `reviewid` (`reviewid`,`posttime`)
) TYPE=MyISAM;

CREATE TABLE `jieqi_article_reviews` (
  `reviewid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `articleid` int(11) unsigned NOT NULL default '0',
  `title` varchar(60) NOT NULL default '',
  `posterid` int(11) NOT NULL default '0',
  `poster` varchar(30) NOT NULL default '',
  `posttime` int(11) NOT NULL default '0',
  `replytime` int(11) NOT NULL default '0',
  `views` mediumint(8) unsigned NOT NULL default '0',
  `replies` mediumint(8) unsigned NOT NULL default '0',
  `islock` tinyint(1) NOT NULL default '0',
  `istop` int(11) NOT NULL default '0',
  `isgood` tinyint(1) NOT NULL default '0',
  `topictype` tinyint(1) NOT NULL default '0',
  `lastinfo` varchar(255) NOT NULL default '',
  `size` int(11) NOT NULL default '0',
  PRIMARY KEY  (`reviewid`),
  KEY `articleid` (`articleid`,`istop`,`replytime`)
) TYPE=MyISAM;

ALTER TABLE `jieqi_article_article` ADD `vipvotetime` INT( 11 ) NOT NULL DEFAULT '0' AFTER `allvote`; 
ALTER TABLE `jieqi_article_article` ADD `vipvotenow` INT( 11 ) NOT NULL DEFAULT '0' AFTER `vipvotetime`;
ALTER TABLE `jieqi_article_article` ADD `vipvotepreview` INT( 11 ) NOT NULL DEFAULT '0' AFTER `vipvotenow`;

ALTER TABLE `jieqi_article_bookcase` ADD `classid` SMALLINT( 3 ) NOT NULL DEFAULT '0' AFTER `articlename` ;

ALTER TABLE `jieqi_article_bookcase` DROP INDEX `userid`;
ALTER TABLE `jieqi_article_bookcase` ADD INDEX `userid` ( `userid` , `classid` ) ;

UPDATE `jieqi_system_modules` SET version=150 WHERE name='article';

UPDATE `jieqi_system_blocks` SET filename='block_reviewslist', classname='BlockArticleReviewslist', template='block_reviewslist.html' WHERE modname='article' AND classname='BlockArticleReviewlist';

UPDATE `jieqi_system_configs` SET cvalue = CONCAT(cvalue, '/modules/article') WHERE modname = 'article' AND cname = 'dynamicurl' AND cvalue != '' AND cvalue NOT LIKE '%/modules/article';

UPDATE `jieqi_system_configs` SET cvalue = CONCAT(cvalue, '/modules/article') WHERE modname = 'article' AND cname = 'staticurl' AND cvalue != '' AND cvalue NOT LIKE '%/modules/article';

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'maketxtfull', '是否生成TXT全文', '1', '', 0, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 21100, '文件参数');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'txtfulldir', 'TXT全文目录', 'txtfull', '', 0, 1, '', 21120, '文件参数');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'txtfullurl', '访问TXT全文的URL', '', '用相对路径的话此处留空，否则用完整url，最后不带斜杠', 0, 1, '', 21140, '文件参数');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'txtfullfile', 'TXT全文文件后缀', '.txt', '', 0, 1, '', 21180, '文件参数');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'makeumd', '是否生成UMD电子书', '0', '', 0, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 21200, '文件参数');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'umddir', 'UMD文件目录', 'umd', '', 0, 1, '', 21220, '文件参数');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'umdurl', '访问UMD文件的URL', '', '用相对路径的话此处留空，否则用完整url，最后不带斜杠', 0, 1, '', 21240, '文件参数');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'umdfile', 'UMD文件后缀', '.umd', '', 0, 1, '', 21280, '文件参数');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'makejar', '是否生成JAR电子书', '0', '', 0, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 21300, '文件参数');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'jardir', 'JAR文件目录', 'jar', '', 0, 1, '', 21320, '文件参数');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'jarurl', '访问JAR文件的URL', '', '用相对路径的话此处留空，否则用完整url，最后不带斜杠', 0, 1, '', 21340, '文件参数');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'jarfile', 'JAR文件后缀', '.jar', '', 0, 1, '', 21380, '文件参数');


INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'scorezipdown', '下载ZIP文件扣积分', '0', '', 0, 3, '', 30650, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'scoreumddown', '下载UMD文件扣积分', '0', '', 0, 3, '', 30670, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'scorejardown', '下载JAR文件扣积分', '0', '', 0, 3, '', 30680, '积分设置');

UPDATE `jieqi_system_configs` SET cname='scoretxtfulldown' WHERE modname='article' AND cname='scoretxtdown';

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'scoretxtfulldown', '下载TXT全文扣积分', '0', '', 0, 3, '', 30660, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'vipvotes', 'vip用户默认月票数', '1', '', 0, 3, '', 31100, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'vipvegold', '当月虚拟币消费超过多少增加一个月票', '1000', '', 0, 3, '', 31200, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'scorevipvote', '用户投月票增加积分', '5', '', 0, 3, '', 31300, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'maxmarkclass', '书架最多类别数', '5', '用户书架最多允许分几个类别，设置成0表示不允许分类。', 0, 3, '', 13660, '显示控制');