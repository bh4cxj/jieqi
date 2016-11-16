CREATE TABLE `jieqi_system_promotions` (
  `ip` char(15) NOT NULL default '',                        
  `uid` int(11) NOT NULL default '0',                       
  `username` varchar(30) NOT NULL default '',               
  PRIMARY KEY  (`ip`)
) TYPE=MyISAM;

CREATE TABLE jieqi_system_registerip (
  ip char(15) NOT NULL default '',
  regtime int(11) unsigned NOT NULL default '0',
  count smallint(6) NOT NULL default '0',
  KEY (ip)
) TYPE=MyISAM;

ALTER TABLE `jieqi_system_users` ADD `weekscore` INT( 11 ) NOT NULL DEFAULT '0' AFTER `monthscore`;
ALTER TABLE `jieqi_system_users` ADD `dayscore` INT( 11 ) NOT NULL DEFAULT '0' AFTER `weekscore`;
ALTER TABLE `jieqi_system_users` ADD `lastscore` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `dayscore`;
ALTER TABLE `jieqi_system_users` CHANGE `petid` `workid` TINYINT( 3 ) NOT NULL DEFAULT '0';

ALTER TABLE `jieqi_system_honors` CHANGE `caption` `caption` VARCHAR( 250 ) NOT NULL;


UPDATE `jieqi_system_configs` SET cname='maxfriends' WHERE modname='system' AND cname='maxfriendsnum';

UPDATE `jieqi_system_configs` SET cname='maxdaymsg' WHERE modname='system' AND cname='msgdaylimit';

UPDATE `jieqi_system_configs` SET ctitle='用户列表每页显示数' WHERE modname='system' AND cname='userpnum';

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'friendspnum', '好友列表每页显示数', '50', '', 0, 3, '', 11200, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'maxfriends', '最多好友数量', '50', '', 0, 3, '', 11300, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_COOKIE_DOMAIN', 'cookie的有效域名', '', '当适用多个子域名时候为了cookie同步，建议这里设置成主域名，如abc.com。留空则使用系统默认值', 1, 1, '', 31820, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_MAIN_SERVER', '主服务器网址', '', '在使用多服务器情况下设置主服务器网址（如：http://www.domain.com），后面不带斜线，单服务器可以留空', 1, 1, '', 10520, '网站基本信息');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_USER_ENTRY', '用户入口服务器网址', '', '在使用多服务器情况下设置用户注册、登录、退出等功能的服务器网址（如：http://www.domain.com），后面不带斜线，单服务器可以留空', 1, 1, '', 10530, '网站基本信息');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_PROMOTION_VISIT', '访问推广增加贡献值', '0', '访问者通过用户提供的推广链接(如 index.php?fromuid=1)访问网站，推广人所得的贡献值。设置为 0 表示不启用本功能。', 1, 3, '', 33100, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_PROMOTION_REGISTER', '注册推广增加贡献值', '0', '访问者通过用户提供的推广链接(如 index.php?fromuid=1)访问网站并注册为会员，推广人所得的贡献值。设置为 0 表示不启用本功能。', 1, 3, '', 33200, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'maxdaymsg', '每天允许发消息数', '0', '设置成 0 表示不限制每天发短信数量', 0, 3, '', 11050, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'sendmsgscore', '超过每天发消息数后继续发送一条减少积分', '0', '如果设置成 0 则不允许超额发送短信', 0, 3, '', 20250, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'regtimelimit', '同一IP几个小时内禁止重复注册', '0', '设置成 0 表示不限制', 0, 3, '', 12100, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'usernamelimit', '注册用户名限制', '0', '', 0, 7, 'a:2:{i:0;s:20:"允许中英文及数字组合";i:1;s:20:"仅允许英文和数字组合";}', 12200, '显示控制');

INSERT INTO `jieqi_system_right` (`rid`, `modname`, `rname`, `rtitle`, `rdescription`, `rhonors`) VALUES (0, 'system', 'maxdaymsg', '每天允许发消息数', '', '');