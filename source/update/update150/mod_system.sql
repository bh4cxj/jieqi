CREATE TABLE `jieqi_system_pposts` (
  `ppostid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `ptopicid` int(11) unsigned NOT NULL default '0',
  `istopic` tinyint(1) NOT NULL default '0',
  `ownerid` int(11) unsigned NOT NULL default '0',
  `posterid` int(11) NOT NULL default '0',
  `poster` varchar(30) NOT NULL default '',
  `posttime` int(11) NOT NULL default '0',
  `posterip` varchar(25) NOT NULL default '',
  `subject` varchar(60) NOT NULL default '',
  `posttext` mediumtext NOT NULL,
  `size` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ppostid`),
  KEY `ownerid` (`ownerid`),
  KEY `ptopicid` (`ptopicid`,`posttime`)
) TYPE=MyISAM;

CREATE TABLE `jieqi_system_ptopics` (
  `ptopicid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `ownerid` int(11) unsigned NOT NULL default '0',
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
  PRIMARY KEY  (`ptopicid`),
  KEY `ownerid` (`ownerid`,`istop`,`replytime`)
) TYPE=MyISAM;


CREATE TABLE `jieqi_system_blockconfigs` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `modules` varchar(10) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `file` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `jieqi_system_mblock` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `modules` varchar(10) NOT NULL default '',
  `name` varchar(20) NOT NULL default '',
  `file` varchar(20) NOT NULL default '',
  `classname` varchar(20) NOT NULL default '',
  `introduce` mediumtext NOT NULL,
  `vars` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;




DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='userrnum';

ALTER TABLE `jieqi_system_online` ADD `name` VARCHAR( 30 ) BINARY NOT NULL DEFAULT '' AFTER `uname` ;

DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='onlinernum';

ALTER TABLE `jieqi_system_users` CHANGE `avatar` `avatar` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `jieqi_system_users` CHANGE `otherinfo` `badges` TEXT NOT NULL DEFAULT '';


INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_AJAX_PAGE', '是否使用AJAX翻页', '0', '', 1, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 32460, '显示控制');


INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'mailtype', '邮件发送方式', '1', '', 0, 7, 'a:4:{i:0;s:14:"不发送任何邮件";i:1;s:40:"通过 PHP 函数及 UNIX sendmail 发送(推荐)";i:2;s:49:"通过 SOCKET 连接 SMTP 服务器发送(支持 ESMTP 验证)";i:3;s:60:"通过 PHP 函数 SMTP 发送 Email(仅 win32 下有效, 不支持 ESMTP)";}', 30100, '邮件设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'maildelimiter', '邮件头的分隔符', '1', '', 0, 7, 'a:2:{i:0;s:18:"使用 LF 作为分隔符";i:1;s:20:"使用 CRLF 作为分隔符";}', 30200, '邮件设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'mailserver', 'SMTP 服务器', '', '如：smtp.jieqi.com', 0, 1, '', 30300, '邮件设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'mailport', 'SMTP 端口', '25', '默认不需修改', 0, 3, '', 30400, '邮件设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'mailauth', '是否需要 AUTH LOGIN 验证', '1', '', 0, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 30500, '邮件设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'mailfrom', '发信人地址', '', '如果需要验证,必须为本服务器地址。地址写法可以是 webmaster@jieqi.com 或者 JieqiCMS <webmaster@jieqi.com>', 0, 1, '', 30500, '邮件设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'mailuser', '验证用户名', '', 'SMTP 邮件服务器用户名(如：webmaster@jieqi.com)', 0, 1, '', 30600, '邮件设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'mailpassword', '验证密码', '', 'SMTP 邮件服务器密码', 0, 1, '', 30700, '邮件设置');


INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'system', 'haveparlor', '拥有个人会客室', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'system', 'parlorpost', '允许在会客室发帖', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'system', 'manageallparlor', '管理所有会客室', '', '');


INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'ppostneedscore', '多少积分以上允许在会客室发贴', '', '', 0, 3, '', 21000, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'scoreptopic', '会客室发贴增加积分', '', '', 0, 3, '', 21100, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'minpposttime', '会客室发贴需要间隔多少秒', '0', '', 0, 3, '', 13100, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'minppostsize', '会客室发贴最少字节数', '0', '', 0, 3, '', 13200, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'maxppostsize', '会客室发贴最多字节数', '0', '', 0, 3, '', 13300, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'checkppostrubbish', '是否检查灌水', '0', '', 0, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 13400, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'hideppostwords', '会客室发贴隐藏单词', '', '', 0, 1, '', 13500, '显示控制');


INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'ptopicpnum', '会客室主题每页显示数', '20', '', 0, 3, '', 13600, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'ppostpnum', '会客室回复每页显示数', '10', '', 0, 3, '', 13700, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'avatardir', '会员头像保存目录', 'avatar', '', 0, 1, '', 13100, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'avatartype', '头像允许上传的文件类型', '.gif .jpg .jpeg .png', '多个类型用空格分开，如".gif .jpg .jpeg .png"', 0, 1, '', 13200, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'avatarsize', '头像文件不得超过几K', '20', '', 0, 3, '', 13300, '显示控制');


INSERT INTO `jieqi_system_blockconfigs` (`id` ,`modules` ,`name` ,`file`) VALUES (0 , 'system', '网站首页', 'blocks');
INSERT INTO `jieqi_system_blockconfigs` (`id` ,`modules` ,`name` ,`file`) VALUES (0 , 'system', '用户列表导航', 'memberblocks');
INSERT INTO `jieqi_system_blockconfigs` (`id` ,`modules` ,`name` ,`file`) VALUES (0 , 'system', '用户面板导航', 'userblocks');

INSERT INTO `jieqi_system_blockconfigs` (`id` ,`modules` ,`name` ,`file`) VALUES (0 , 'article', '小说作家导航', 'authorblocks');
INSERT INTO `jieqi_system_blockconfigs` (`id` ,`modules` ,`name` ,`file`) VALUES (0 , 'article', '小说列表导航', 'guideblocks');

INSERT INTO `jieqi_system_blockconfigs` (`id` ,`modules` ,`name` ,`file`) VALUES (0 , 'obook', '电子书作家导航', 'authorblocks');
INSERT INTO `jieqi_system_blockconfigs` (`id` ,`modules` ,`name` ,`file`) VALUES (0 , 'obook', '电子书列表导航', 'guideblocks');

INSERT INTO `jieqi_system_blockconfigs` (`id` ,`modules` ,`name` ,`file`) VALUES (0 , 'cartoon', '漫画作家导航', 'authorblocks');
INSERT INTO `jieqi_system_blockconfigs` (`id` ,`modules` ,`name` ,`file`) VALUES (0 , 'cartoon', '漫画列表导航', 'guideblocks');