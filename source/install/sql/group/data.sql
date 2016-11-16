-- 
-- 数据库: `jieqi16`
-- 

-- 
-- 导出表中的数据 `jieqi_system_configs`
-- 

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'attachtype', '允许上传的附件类型', 'gif jpg jpeg png bmp swf zip rar txt', '多个附件用空格格开', 0, 1, '', 30200, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'attachwater', '图片附件添加水印及位置', '0', '本功能需要 GD 库支持才能使用，对 JPG/PNG/GIF 格式的上传图片有效', 0, 7, 'a:11:{i:0;s:8:"不加水印";i:1;s:8:"顶部居左";i:2;s:8:"顶部居中";i:3;s:8:"顶部居右";i:4;s:8:"中部居左";i:5;s:8:"中部居中";i:6;s:8:"中部居右";i:7;s:8:"底部居左";i:8;s:8:"底部居中";i:9;s:8:"底部居右";i:10;s:8:"随机位置";}', 36010, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'attachwimage', '附件水印图片文件', 'watermark.jpg', '允许 JPG/PNG/GIF 格式，默认只需填文件名，放在 modules/group/images 目录下', 0, 1, '', 36020, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'attachwquality', 'jpeg图片质量', '90', '范围为 0～100 的整数，数值越大结果图片效果越好，但尺寸也越大。', 0, 3, '', 36040, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'attachwtrans', '水印图片与原图片的融合度', '30', '范围为 1～100 的整数，数值越大水印图片透明度越低。', 0, 3, '', 36030, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'group', '是否开放注册', '0', '开放圈子申请', 0, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 30000, '文件參數');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'maxattachnum', '一次发帖最多附件数', '5', '设成 0 就表示禁止附件上传', 0, 3, '', 30300, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'maxfilesize', '文件附件最大允许几K', '1000', '', 0, 3, '', 30500, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'maximagesize', '图片附件最大允许几K', '1000', '', 0, 3, '', 30400, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'minpostsize', '帖子最少字节数', '0', '0表示无限制', 0, 3, '', 10800, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'minposttime', '两次发贴最少间隔时间', '0', '0表示无限制', 0, 3, '', 10900, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'postnum', '每页显示几个回复', '10', '', 0, 3, '', 10200, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'quotelength', '回复时的最大引用长度', '200', '', 0, 3, '', 10300, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'scoregoodtopic', '主题精华积分', '5', '', 0, 3, '', 20300, '积分设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'scorereply', '发表回复积分', '1', '', 0, 3, '', 20200, '积分设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'scoretopic', '发表主题积分', '2', '', 0, 3, '', 20100, '积分设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'textwatermark', '文字水印', '', '指的是隐藏在阅读页面，的一些文字。其中<{$randtext}>将被替换成一组随机字符', 0, 2, '', 11300, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'group', 'topicnum', '每页显示几个主题', '30', '', 0, 3, '', 0, '显示控制');

-- 
-- 导出表中的数据 `jieqi_system_power`
-- 

INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'group', 'creategroup', '申请圈子', '指定会员组申请圈子权限', 'a:1:{i:0;s:1:"4";}');

-- 
-- 导出表中的数据 `jieqi_group_gcat`
-- 

INSERT INTO `jieqi_group_gcat` (`gcatid`, `gcatname`, `gcatorder`) VALUES (0, '默认分类', 1);

-- 
-- 导出表中的数据 `jieqi_group_membergroup`
-- 

INSERT INTO `jieqi_group_membergroup` (`membergid`, `admingid`, `membergtype`, `membergtitle`, `allowpostmessage`, `allowpostparty`, `allowreplyparty`, `allowsignparty`, `allowposttopic`, `allowreplytopic`, `allowpostpic`) VALUES (0, 1, 'system', '圈主', 1, 1, 1, 1, 1, 1, 1);
INSERT INTO `jieqi_group_membergroup` (`membergid`, `admingid`, `membergtype`, `membergtitle`, `allowpostmessage`, `allowpostparty`, `allowreplyparty`, `allowsignparty`, `allowposttopic`, `allowreplytopic`, `allowpostpic`) VALUES (0, 2, 'system', '副圈主', 1, 1, 1, 1, 1, 1, 1);
INSERT INTO `jieqi_group_membergroup` (`membergid`, `admingid`, `membergtype`, `membergtitle`, `allowpostmessage`, `allowpostparty`, `allowreplyparty`, `allowsignparty`, `allowposttopic`, `allowreplytopic`, `allowpostpic`) VALUES (0, 0, 'default', '资深', 1, 1, 1, 1, 1, 1, 1);
INSERT INTO `jieqi_group_membergroup` (`membergid`, `admingid`, `membergtype`, `membergtitle`, `allowpostmessage`, `allowpostparty`, `allowreplyparty`, `allowsignparty`, `allowposttopic`, `allowreplytopic`, `allowpostpic`) VALUES (0, 0, 'member', '普通', 1, 1, 1, 1, 1, 1, 1);


-- 
-- 导出表中的数据 `jieqi_group_admingroup`
-- 

INSERT INTO `jieqi_group_admingroup` (`admingid`, `allowmanalbum`, `allowmanparty`, `allowmantopic`, `allowmanmember`, `allowmanbasic`) VALUES (1, 1, 1, 1, 1, 1);
INSERT INTO `jieqi_group_admingroup` (`admingid`, `allowmanalbum`, `allowmanparty`, `allowmantopic`, `allowmanmember`, `allowmanbasic`) VALUES (2, 1, 1, 1, 1, 1);

-- 
-- 导出表中的数据 `jieqi_system_modules`
-- 

INSERT INTO `jieqi_system_modules` (`mid`, `name`, `caption`, `description`, `version`, `vtype`, `lastupdate`, `weight`, `publish`, `modtype`) VALUES (0, 'group', '圈子交友', '', 100, '', 0, 0, 1, 0);

-- 
-- 导出表中的数据 `jieqi_system_blocks`
-- 

INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '圈子分类', 'group', 'block_groupcat', 'Blockgroupcat', 0, '圈子分类', '    本区块允许用户自定义模板和参数，并且不同的设置可以保存成不同的区块。\r\n    区块默认模板文件为“block_groupcat.html”，在/modules/group/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设置留空表示使用默认模板。', '', '', 'block_groupcat.html', 0, 4, 91100, 0, 0, 0, 3, 1);
INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '我的圈子区块', 'group', 'block_mygroup', 'Blockmygroup', 0, '我的圈子', '    本区块允许用户自定义模板和参数，并且不同的设置可以保存成不同的区块。\r\n    区块默认模板文件为“block_mygroup.html”，在/modules/group/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设置留空表示使用默认模板。\r\n    区块允许设置一个参数listnum 显示多少条记录', '', '10', 'block_mygroup.html', 0, 4, 91200, 0, 0, 0, 2, 1);
INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '圈子排行榜区块', 'group', 'block_grouplist', 'Blockgrouplist', 4, '圈子排行榜', '    本区块允许用户自定义模板和参数，并且不同的设置可以保存成不同的区块。\r\n    区块默认模板文件为“block_grouplist.html”，在/modules/group/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设置留空表示使用默认模板。\r\n    区块允许设置三个参数，不同参数之间用英文逗号分隔“,”。\r\n    参数一是排行方式（默认按圈子编号），允许以下几种设置：1、“gid” - 按编号ID；2、“topicnum” - 按话题数量；3、“gmembers” - 按话题数量；4、“gtime” - 按时间\r\n    参数二是显示行数，使用整数（默认 10）\r\n    参数六是指显示顺序（默认 0 表示按从大到小排序），1 表示从小到大排序。', '', 'gmembers,10,0', 'block_grouplist.html', 0, 4, 91300, 0, 0, 0, 3, 1);

