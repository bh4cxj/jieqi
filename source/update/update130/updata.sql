DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='JIEQI_USE_SUBDIR';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='JIEQI_FORCE_COMPILE';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='JIEQI_COMPILE_CHECK';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='JIEQI_ROOT_PATH';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='payclub';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='ipayid';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='ipaykey';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='ipayurl';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='xpayid';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='xpaykey';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='xpayurl';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='paylogpnum';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='egoldtransrate';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='creditransrate';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='scoretransrate';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='JIEQI_DEFAULT_CHARSET';
DELETE FROM `jieqi_system_configs` WHERE modname='system' AND cname='JIEQI_HEAD';

DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='toptimenum';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='lastupdatenum';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='authorupdatenum';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='masterupdatenum';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='postdatenum';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='newreviewnum';

UPDATE jieqi_system_configs SET cdescription='单位为“分”' WHERE modname='system' AND cname='JIEQI_SESSION_EXPRIE';
UPDATE `jieqi_system_configs` SET cvalue='1', options='a:3:{i:0;s:10:"不显示错误";i:1;s:8:"显示错误";i:2;s:14:"显示错误和提示";}' WHERE  modname='system' AND cname='JIEQI_ERROR_MODE';
UPDATE jieqi_system_configs SET ctitle='session保持时间', cvalue='0',cdescription='单位为“秒”,设成“0”表示用系统默认参数' WHERE modname='system' AND cname='JIEQI_SESSION_EXPRIE';
UPDATE `jieqi_system_configs` SET options='a:3:{i:1;s:8:"网站开放";i:0;s:8:"网站关闭";i:2;s:18:"开放但禁止登录发表";}' WHERE modname='system' AND cname='JIEQI_IS_OPEN';
UPDATE `jieqi_system_configs` SET options='a:6:{s:3:"gbk";s:3:"gbk";s:6:"gb2312";s:6:"gb2312";s:4:"utf8";s:4:"utf8";s:4:"big5";s:4:"big5";s:6:"latin1";s:6:"latin1";s:7:"default";s:7:"default";}' WHERE modname='system' AND cname='JIEQI_DB_CHARSET';

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'visitstatnum', '文章点击统计基数', '1', '即用户访问一篇文章算几个点击，设置成 0 的话不进行点击统计', 0, 3, '', 15200, '显示控制');
INSERT INTO `jieqi_system_configs` (`modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES ('system', 'JIEQI_CUSTOM_INCLUDE', '附加载入用户自定义程序', '0', '如果开启本功能，用户可以为每个页面设置一段附加的PHP程序。这种做法容易和系统程序冲突，请斟酌使用！', 1, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 31850, '显示控制');
INSERT INTO `jieqi_system_configs` (`modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES ('system', 'JIEQI_PROXY_DENIED', '网站是否允许代理访问', '1', '', 1, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 30230, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_SILVER_USAGE', '是否系统银币功能', '0', '冲值时候可选充成金币或银币，后台结算可分开，但对用户是统一使用的', 1, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 32440, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_DENY_RELOGIN', '禁止同一帐号多人登陆', '0', '', 1, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 31320, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_DB_CHARSET', '数据库连接编码', 'gbk', '', 1, 7, 'a:6:{s:3:"gbk";s:3:"gbk";s:6:"gb2312";s:6:"gb2312";s:4:"utf8";s:4:"utf8";s:4:"big5";s:4:"big5";s:6:"latin1";s:6:"latin1";s:7:"default";s:7:"default";}', 20120, '数据库设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_LICENSE_KEY', '网站授权注册码', '', '', 1, 2, '', 11100, '网站基本信息');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'adclickscore', '每次点击广告积分', '1', '需要登陆用户点击有效', 0, 3, '', 20300, '积分设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'maxadclick', '每天最多有效广告点击次数', '5', '超过此次数点击广告将不计分', 0, 3, '', 20400, '积分设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_MAX_PAGES', '列表最大页数', '0', '0表示不限制页数', 1, 3, '', 32800, '显示控制');


INSERT INTO `jieqi_system_power` (`modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES ('system', 'adminblock', '管理区块', '', '');
INSERT INTO `jieqi_system_power` (`modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES ('system', 'sendmessage', '向其他会员发送短信', '', '');

UPDATE `jieqi_system_blocks` SET `contenttype` = '1' WHERE `modname` = 'system' AND  `classname` = 'BlockSystemUserlist';


INSERT INTO `jieqi_system_honors` (`honorid`, `caption`, `minscore`, `maxscore`, `setting`, `honortype`) VALUES (1, '新手上路', -9999999, 50, '', 0);
INSERT INTO `jieqi_system_honors` (`honorid`, `caption`, `minscore`, `maxscore`, `setting`, `honortype`) VALUES (2, '普通会员', 50, 200, '', 0);
INSERT INTO `jieqi_system_honors` (`honorid`, `caption`, `minscore`, `maxscore`, `setting`, `honortype`) VALUES (3, '中级会员', 200, 500, '', 0);
INSERT INTO `jieqi_system_honors` (`honorid`, `caption`, `minscore`, `maxscore`, `setting`, `honortype`) VALUES (4, '高级会员', 500, 1000, '', 0);
INSERT INTO `jieqi_system_honors` (`honorid`, `caption`, `minscore`, `maxscore`, `setting`, `honortype`) VALUES (5, '金牌会员', 1000, 3000, '', 0);
INSERT INTO `jieqi_system_honors` (`honorid`, `caption`, `minscore`, `maxscore`, `setting`, `honortype`) VALUES (6, '本站元老', 3000, 9999999, '', 0);

INSERT INTO `jieqi_system_right` (`rid`, `modname`, `rname`, `rtitle`, `rdescription`, `rhonors`) VALUES (1, 'system', 'maxfriends', '最大好友数', '', '');
INSERT INTO `jieqi_system_right` (`rid`, `modname`, `rname`, `rtitle`, `rdescription`, `rhonors`) VALUES (2, 'system', 'maxmessages', '信箱最多消息数', '', '');
INSERT INTO `jieqi_system_right` (`rid`, `modname`, `rname`, `rtitle`, `rdescription`, `rhonors`) VALUES (3, 'article', 'maxbookmarks', '书架最大收藏量', '', '');
INSERT INTO `jieqi_system_right` (`rid`, `modname`, `rname`, `rtitle`, `rdescription`, `rhonors`) VALUES (4, 'article', 'dayvotes', '每天允许推荐次数', '', '');


DELETE FROM `jieqi_system_modules` WHERE name='system';
UPDATE `jieqi_system_modules` SET caption='小说连载', version=130 WHERE name='article';
UPDATE `jieqi_system_modules` SET caption='论坛', version=130 WHERE name='forum';
UPDATE `jieqi_system_modules` SET caption='在线电子书', version=110 WHERE name='obook';


UPDATE `jieqi_system_configs` SET catname='显示控制' WHERE modname='article' AND cname='writergroup';
INSERT INTO `jieqi_system_configs` (`modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES ('article', 'attachdir', '附件保存目录', 'attachment', '', 0, 1, '', 40100, '附件设置');
INSERT INTO `jieqi_system_configs` (`modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES ('article', 'attachtype', '允许上传的附件类型', 'gif jpg jpeg png bmp', '多个附件用空格格开', 0, 1, '', 40200, '附件设置');
INSERT INTO `jieqi_system_configs` (`modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES ('article', 'maxattachnum', '一次发文最多附件数', '5', '设成 0 就表示禁止附件上传', 0, 3, '', 40300, '附件设置');
INSERT INTO `jieqi_system_configs` (`modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES ('article', 'maximagesize', '图片附件最大允许几K', '1000', '', 0, 3, '', 40400, '附件设置');
INSERT INTO `jieqi_system_configs` (`modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES ('article', 'maxfilesize', '文件附件最大允许几K', '1000', '', 0, 3, '', 40500, '附件设置');
INSERT INTO `jieqi_system_configs` (`modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES ('article', 'attachurl', '访问附件的URL', '', '附件用相对路径的话此处留空，否则用完整url，最后不带斜杠', 0, 1, '', 40120, '附件设置');
INSERT INTO `jieqi_system_configs` (`modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES ('article', 'samearticlename', '文章标题是否允许重复', '0', '', 0, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 15100, '显示控制');
INSERT INTO `jieqi_system_configs` (`modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES ('article', 'fakeprefix', '伪静态页面目录前缀', '', '用户如果设置了目录前缀，伪静态页面将在根目录下使用该前缀生成目录。这样可以减少伪静态页面的目录深度，但是会增加根目录下的目录数。', 0, 1, '', 21980, '文件参数');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'attachwater', '图片附件添加水印及位置', '0', '本功能需要 GD 库支持才能使用，对 JPG/PNG/GIF 格式的上传图片有效', 0, 7, 'a:11:{i:0;s:8:"不加水印";i:1;s:8:"顶部居左";i:2;s:8:"顶部居中";i:3;s:8:"顶部居右";i:4;s:8:"中部居左";i:5;s:8:"中部居中";i:6;s:8:"中部居右";i:7;s:8:"底部居左";i:8;s:8:"底部居中";i:9;s:8:"底部居右";i:10;s:8:"随机位置";}', 32810, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'attachwimage', '附件水印图片文件', 'watermark.gif', '允许 JPG/PNG/GIF 格式，默认只需填文件名，放在 modules/article/images 目录下', 0, 1, '', 32820, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'attachwtrans', '水印图片与原图片的融合度', '30', '范围为 1～100 的整数，数值越大水印图片透明度越低。', 0, 3, '', 32830, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'attachwquality', 'jpeg图片质量', '90', '范围为 0～100 的整数，数值越大结果图片效果越好，但尺寸也越大。', 0, 3, '', 32840, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'searchtype', '搜索匹配方式', '0', '', 0, 7, 'a:3:{i:0;s:8:"模糊匹配";i:1;s:10:"半模糊匹配";i:2;s:8:"完整匹配";}', 14150, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'toppagenum', '排行榜一页显示几行', '30', '', 0, 3, '', 12320, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'topcachenum', '排行榜缓存几个页面', '10', '', 0, 3, '', 12350, '显示控制');



UPDATE jieqi_system_power SET pname='manageallarticle' WHERE modname='article' AND pname='manageallartiale';
INSERT INTO `jieqi_system_power` (`modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES ('article', 'articleupattach', '发文允许上传附件', '', '');
INSERT INTO `jieqi_system_power` (`modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES ('article', 'reviewupattach', '书评允许上传附件', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'article', 'viewuplog', '查看更新记录', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'article', 'newreview', '发表书评', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'article', 'articlemodify', '修改文章统计', '', '');

DELETE FROM `jieqi_system_blocks` WHERE modname='article' AND classname='BlockArticleCommend';
INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '文章封面推荐', 'article', 'block_commend', 'BlockArticleCommend', 0, '封面推荐', '&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义模板和参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块默认模板文件为“block_commend.html”，在/modules/article/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设置留空表示使用默认模板。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置推荐的文章序号作为参数，不同参数之间用英文“|”分隔。比如： “123|234|456|678” 表示本区块调用这四个序号文章信息显示', '', '', 'block_commend.html', 0, 1, 23100, 0, 0, 0, 0, 2);




INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'forum', 'attachwater', '图片附件添加水印及位置', '0', '本功能需要 GD 库支持才能使用，对 JPG/PNG/GIF 格式的上传图片有效', 0, 7, 'a:11:{i:0;s:8:"不加水印";i:1;s:8:"顶部居左";i:2;s:8:"顶部居中";i:3;s:8:"顶部居右";i:4;s:8:"中部居左";i:5;s:8:"中部居中";i:6;s:8:"中部居右";i:7;s:8:"底部居左";i:8;s:8:"底部居中";i:9;s:8:"底部居右";i:10;s:8:"随机位置";}', 36010, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'forum', 'attachwimage', '附件水印图片文件', 'watermark.gif', '允许 JPG/PNG/GIF 格式，默认只需填文件名，放在 modules/article/images 目录下', 0, 1, '', 36020, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'forum', 'attachwtrans', '水印图片与原图片的融合度', '30', '范围为 1～100 的整数，数值越大水印图片透明度越低。', 0, 3, '', 36030, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'forum', 'attachwquality', 'jpeg图片质量', '90', '范围为 0～100 的整数，数值越大结果图片效果越好，但尺寸也越大。', 0, 3, '', 36040, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'forum', 'searchtype', '搜索匹配方式', '0', '', 0, 7, 'a:3:{i:0;s:8:"模糊匹配";i:1;s:10:"半模糊匹配";i:2;s:8:"完整匹配";}', 10950, '显示控制');

UPDATE `jieqi_system_blocks` SET contenttype=4 WHERE modname='article' AND classname='BlockArticleArticlelist';
UPDATE `jieqi_system_configs` SET ctitle='用户列表每页显示数' WHERE modname='system' AND cname='userpnum';