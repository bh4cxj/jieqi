CREATE TABLE `jieqi_system_report` (
  `reportid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `reporttime` int(11) unsigned NOT NULL default '0',
  `reportuid` int(11) unsigned NOT NULL default '0',
  `reportname` varchar(30) binary NOT NULL default '',
  `authtime` int(11) unsigned NOT NULL default '0',
  `authuid` int(11) unsigned NOT NULL default '0',
  `authname` varchar(30) binary NOT NULL default '',
  `reporttitle` varchar(100) NOT NULL default '',
  `reporttext` mediumtext NOT NULL,
  `reportsize` int(11) unsigned NOT NULL default '0',
  `reportfield` varchar(250) NOT NULL default '',
  `authnote` text NOT NULL,
  `reportsort` smallint(6) unsigned NOT NULL default '0',
  `reporttype` smallint(6) unsigned NOT NULL default '0',
  `authflag` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`reportid`),
  KEY (`reportsort`),
  KEY (`reporttype`),
  KEY (`reportname`),
  KEY (`authname`)
) TYPE=MyISAM;

CREATE TABLE `jieqi_system_userlink` (
  `ulid` int(11) unsigned NOT NULL auto_increment,
  `ultitle` varchar(60) NOT NULL default '',
  `ulurl` varchar(100) NOT NULL default '',
  `ulinfo` varchar(255) NOT NULL default '',
  `userid` int(11) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `score` tinyint(1) NOT NULL default '0',
  `weight`  smallint(6) NOT NULL default '0',
  `toptime` int(11) NOT NULL default '0',
  `addtime` int(11) NOT NULL default '0',
  `allvisit` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ulid`),
  KEY (`userid`, `toptime`)
) TYPE=MyISAM;

DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='JIEQI_SYSTEM_MENU';

DELETE FROM `jieqi_system_blocks` WHERE modname='system' AND filename='block_userstatus';

UPDATE `jieqi_system_blocks` SET contenttype=4 WHERE modname='system' AND filename='block_login';
UPDATE `jieqi_system_blocks` SET publish=3 WHERE modname='system' AND filename='block_login' AND publish=1;
UPDATE `jieqi_system_blocks` SET contenttype=4 WHERE modname='system' AND filename='block_userlist';


INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES(0, '用户推荐区块', 'system', 'block_usercommend', 'BlockSystemUsercommend', 0, '用户推荐', '&nbsp;&nbsp;&nbsp;&nbsp;本区块根据设置的参数显示对应ID的用户<br>&nbsp;&nbsp;&nbsp;&nbsp;默认一个参数，设置推荐的用户ID，多个ID用“|”分割，比如 12|34|56', '', '', 'block_usercommend.html', 0, 4, 11250, 0, 0, 0, 0, 2);

INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES(0, '用户友情连接', 'system', 'block_userlink', 'BlockSystemUserlink', 1, '用户友情连接', '&nbsp;&nbsp;&nbsp;&nbsp;本区块显示某一用户自添加的友情连接<br>&nbsp;&nbsp;&nbsp;&nbsp;允许设置五个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是排序字段，允许设置成“toptime”-置顶时间，或者“addtime”-添加时间<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是显示几条记录，默认10<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是排序方式：0-从大到小，1-从小到大<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是显示哪个用户的友情链接，允许设置成“self”-当前用户，“uid”-url参数里面uid值对应的用户，“0”-所有用户，设置成大于0的一个整数，表示指定这个uid的用户<br>&nbsp;&nbsp;&nbsp;&nbsp;参数五是内容过滤，0-都显示，1-显示置顶的链接，2-显示非置顶链接', '', 'toptime,10,0,uid,0', 'block_userlink.html', 0, 4, 11300, 0, 0, 0, 0, 1);

INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES(0, '用户好友列表', 'system', 'block_ufriends', 'BlockSystemUfriends', 1, '用户好友列表', '&nbsp;&nbsp;&nbsp;&nbsp;本区块显示某一用户好友列表<br>&nbsp;&nbsp;&nbsp;&nbsp;允许设置四个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是排序字段，允许设置成“friendsid”-好友记录ID，或者“adddate”-添加时间<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是显示几条记录，默认10<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是排序方式：0-从大到小，1-从小到大<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是显示哪个用户的好友列表，允许设置成“self”-当前用户，“uid”-url参数里面uid值对应的用户，“0”-所有用户，设置成大于0的一个整数，表示指定这个uid的用户', '', 'friendsid,10,0,uid', 'block_ufriends.html', 0, 4, 11400, 0, 0, 0, 0, 1);


INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES(0, '用户资料', 'system', 'block_uinfo', 'BlockSystemUinfo', 0, '用户资料', '&nbsp;&nbsp;&nbsp;&nbsp;本区块显示某一用户资料<br>&nbsp;&nbsp;&nbsp;&nbsp;允许设置一个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是显示哪个用户的资料，允许设置成“self”-当前用户，“uid”-url参数里面uid值对应的用户，设置成大于0的一个整数，表示指定这个uid的用户', '', 'uid', 'block_uinfo.html', 0, 4, 11500, 0, 0, 0, 0, 1);


INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES(0, '用户会客室主题', 'system', 'block_utopics', 'BlockSystemUptopics', 6, '会客室主题', '&nbsp;&nbsp;&nbsp;&nbsp;本区块显示某一用户自添加的友情连接<br>&nbsp;&nbsp;&nbsp;&nbsp;允许设置七个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是排序字段，允许设置成“topicid”-主题序号，“posttime”-发表时间，“replytime”-最后回复时间，“views”-点击数，“replies”-回复数间<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是显示几条记录，默认10<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是排序方式：0-从大到小，1-从小到大<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是显示哪个用户的友情链接，允许设置成“self”-当前用户，“uid”-url参数里面uid值对应的用户，“0”-所有用户，设置成大于0的一个整数，表示指定这个uid的用户<br>&nbsp;&nbsp;&nbsp;&nbsp;参数五是否置顶贴，0-都显示，1-显示置顶，2-显示非置顶<br>&nbsp;&nbsp;&nbsp;&nbsp;参数六是否精华贴，0-都显示，1-显示精华，2-显示非精华<br>&nbsp;&nbsp;&nbsp;&nbsp;参数七是否锁定贴，0-都显示，1-显示锁定，2-显示非锁定', '', 'topicid,10,0,uid,0,0,0', 'block_uptopics.html', 0, 4, 11600, 0, 0, 0, 0, 1);

INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES(0, '网页内容调用区块', 'system', 'block_fileget', 'BlockSystemFileget', 0, '', '&nbsp;&nbsp;&nbsp;&nbsp;本区块是通过URL获取网页内容作为自己的区块内容。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置三个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是访问的URL(必须设置)，如 http://www.domain.com/block.php?id=1<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是缓存时间（单位是秒），本参数可以留空或者设置成0，表示使用系统默认缓存时间。设置成-1表示不用缓存，设置大于0的整数表示自定义缓存时间。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是指获取的网页内容编码，留空表示和系统默认编码相同。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均表示使用默认值。例子： “http://www.domain.com/block.php?id=1,1800,utf-8” 表示获取这个网址内容，缓存半个小时，内容编码是utf-8', '', '', '', 0, 4, 12500, 0, 0, 0, 0, 1);

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'avatarcut', '是否要求裁剪头像图片', '1', '裁剪图片需要GD库支持', 0, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 13250, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'avatarurl', '会员头像访问URL', '', '对应保存目录的url，最后不带斜杠，如果留空则系统自动判断', 0, 1, '', 13150, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'system', 'JIEQI_AJAX_PAGE', '是否使用AJAX翻页', '0', '', 1, 9, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 32460, '显示控制');

INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'system', 'adminreport', '管理用户报告', '', '');

UPDATE `jieqi_system_configs` SET ctype=5, cdescription='留空表示不修改密码' WHERE modname='system' AND cname='JIEQI_DB_PASS';

UPDATE `jieqi_system_configs` SET ctype=9 WHERE ctype=7 AND options='a:2:{i:1;s:2:"是";i:0;s:2:"否";}';

DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='JIEQI_BLOCKCACHE_DIR';
UPDATE `jieqi_system_configs` SET cdescription='支持三种写法：1、只填目录名（指网站跟目录下的子目录名），如： cahce ；2、使用完整路径，如： D:/web/cache ；3、使用Memcached，格式为 memcached://服务地址:端口号，如：memcached://127.0.0.1:11211' WHERE modname='system' AND cname='JIEQI_CACHE_DIR';

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'posttitlemax', '发帖标题最多几个字节', '60', '跟数据结构中的字段长度有关，一般不用修改', 0, 3, '', 40100, '内容检查设置');

UPDATE `jieqi_system_configs` SET modname='system', cname='postintervaltime', ctitle='两次发贴最少间隔时间', cdescription='单位是秒，设置成 0 表示无时间间隔限制', catorder=40200, catname='内容检查设置' WHERE modname='forum' AND cname='minposttime';

UPDATE `jieqi_system_configs` SET modname='system', cname='postdenywords', ctitle='禁止发表的词语', cvalue=REPLACE(cvalue, ' ', '\n'), cdescription='替换规则每条一行，写法为：“from=to”表示内容“from”将被替换成“to”。也可以只写“from”，这样内容中的“from”会替换成“**”，相当于隐藏关键词效果。', catorder=40300, catname='内容检查设置' WHERE modname='forum' AND cname='badpostwords';

UPDATE `jieqi_system_configs` SET modname='system', cname='postreplacewords', ctitle='发帖内容替换', cvalue=REPLACE(cvalue, ' ', '\n'), cdescription='可以设置多个替换规则，每个一行。', catorder=40400, catname='内容检查设置' WHERE modname='forum' AND cname='hidepostwords';

UPDATE `jieqi_system_configs` SET modname='system', cname='postminsize', ctitle='发帖内容最少字节数', cdescription='0 表示不限制', catorder=40500, catname='内容检查设置' WHERE modname='forum' AND cname='minpostsize';

UPDATE `jieqi_system_configs` SET modname='system', cname='postmaxsize', ctitle='发帖内容最多字节数', cdescription='0 表示不限制', catorder=40600, catname='内容检查设置' WHERE modname='forum' AND cname='maxpostsize';

UPDATE `jieqi_system_configs` SET modname='system', cname='postdenyrubbish', ctitle='禁止灌水帖子', cdescription='被怀疑是灌水的帖子将禁止发表，程序判断不一定准确，请慎用', catorder=40700, catname='内容检查设置' WHERE modname='forum' AND cname='checkpostrubbish';

DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='minpposttime';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='minppostsize';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='maxppostsize';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='checkppostrubbish';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='hideppostwords';

UPDATE jieqi_system_friends SET yourname = (SELECT name FROM jieqi_system_users WHERE jieqi_system_friends.yourid = jieqi_system_users.uid AND jieqi_system_users.name != '') WHERE yourid > 0;

UPDATE jieqi_system_friends SET myname = (SELECT name FROM jieqi_system_users WHERE jieqi_system_friends.myid = jieqi_system_users.uid AND jieqi_system_users.name != '') WHERE myid > 0;

UPDATE jieqi_system_message SET fromname = (SELECT name FROM jieqi_system_users WHERE jieqi_system_message.fromid = jieqi_system_users.uid AND jieqi_system_users.name != '') WHERE fromid > 0;

UPDATE jieqi_system_message SET toname = (SELECT name FROM jieqi_system_users WHERE jieqi_system_message.toid = jieqi_system_users.uid AND jieqi_system_users.name != '') WHERE toid > 0;


ALTER TABLE `jieqi_system_ptopics` CHANGE `ptopicid` `topicid` mediumint(8) unsigned NOT NULL auto_increment;
ALTER TABLE `jieqi_system_ptopics` CHANGE `title` `title` varchar(80) NOT NULL default '';
ALTER TABLE `jieqi_system_ptopics` ADD `replierid` int(10) NOT NULL default '0' AFTER `posttime`;
ALTER TABLE `jieqi_system_ptopics` ADD `replier` varchar(30) NOT NULL default '' AFTER `replierid`;
ALTER TABLE `jieqi_system_ptopics` ADD `rate` tinyint(1) NOT NULL default '0' AFTER `isgood`;
ALTER TABLE `jieqi_system_ptopics` ADD `attachment` tinyint(1) NOT NULL default '0' AFTER `rate`;
ALTER TABLE `jieqi_system_ptopics` ADD `needperm` int(10) unsigned NOT NULL default '0' AFTER `attachment`;
ALTER TABLE `jieqi_system_ptopics` ADD `needscore` int(10) unsigned NOT NULL default '0' AFTER `needperm`;
ALTER TABLE `jieqi_system_ptopics` ADD `needexp` int(10) unsigned NOT NULL default '0' AFTER `needscore`;
ALTER TABLE `jieqi_system_ptopics` ADD `needprice` int(10) unsigned NOT NULL default '0' AFTER `needexp`;
ALTER TABLE `jieqi_system_ptopics` CHANGE `topictype` `sortid` tinyint(3) NOT NULL default '0' AFTER `needprice`;
ALTER TABLE `jieqi_system_ptopics` ADD `iconid` tinyint(3) NOT NULL default '0' AFTER `sortid`;
ALTER TABLE `jieqi_system_ptopics` ADD `typeid` tinyint(3) NOT NULL default '0' AFTER `iconid`;
ALTER TABLE `jieqi_system_ptopics` ADD `linkurl` varchar(100) NOT NULL default '' AFTER `lastinfo`;

ALTER TABLE `jieqi_system_ptopics` ADD INDEX `posterid` (`posterid`,`replytime`);

ALTER TABLE `jieqi_system_pposts` CHANGE `ppostid` `postid` int(10) unsigned NOT NULL auto_increment;
ALTER TABLE `jieqi_system_pposts` CHANGE `ptopicid` `topicid` int(10) unsigned NOT NULL default '0';
ALTER TABLE `jieqi_system_pposts` ADD `replypid` int(10) unsigned NOT NULL default '0' AFTER `istopic`;
ALTER TABLE `jieqi_system_pposts` ADD `editorid` int(10) NOT NULL default '0' AFTER `posterip`;
ALTER TABLE `jieqi_system_pposts` ADD `editor` varchar(30) NOT NULL default '' AFTER `editorid`;
ALTER TABLE `jieqi_system_pposts` ADD `edittime` int(10) NOT NULL default '0' AFTER `editor`;
ALTER TABLE `jieqi_system_pposts` ADD `editorip` varchar(25) NOT NULL default '' AFTER `edittime`;
ALTER TABLE `jieqi_system_pposts` ADD `editnote` varchar(250) NOT NULL default '' AFTER `editorip`;
ALTER TABLE `jieqi_system_pposts` ADD `iconid` tinyint(3) NOT NULL default '0' AFTER `editnote`;
ALTER TABLE `jieqi_system_pposts` ADD `attachment` text NOT NULL AFTER `iconid`;
ALTER TABLE `jieqi_system_pposts` CHANGE `subject` `subject` varchar(80) NOT NULL default '';