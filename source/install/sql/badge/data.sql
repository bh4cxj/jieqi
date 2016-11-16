
#模块信息
INSERT INTO `jieqi_system_modules` (`mid`, `name`, `caption`, `description`, `version`, `lastupdate`, `weight`, `publish`, `modtype`) VALUES (0, 'badge', '用户徽章', '', 110, 0, 0, 1, 0);


#目前系统分配徽章类型
#1-等级徽章 2-头衔徽章 1010-荣誉徽章 2010-圈子徽章  3010-活动徽章

INSERT INTO `jieqi_badge_btype` (`btypeid`, `title`, `sysflag`) VALUES (1, '等级徽章', 1);
INSERT INTO `jieqi_badge_btype` (`btypeid`, `title`, `sysflag`) VALUES (2, '头衔徽章', 1);
INSERT INTO `jieqi_badge_btype` (`btypeid`, `title`, `sysflag`) VALUES (1010, '荣誉徽章', 2);
INSERT INTO `jieqi_badge_btype` (`btypeid`, `title`, `sysflag`) VALUES (2010, '圈子徽章', 3);
INSERT INTO `jieqi_badge_btype` (`btypeid`, `title`, `sysflag`) VALUES (3010, '活动徽章', 2);


#相关参数
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'badge', 'imagedir', '徽章图片保存目录', 'image', '', 0, 1, '', 10100, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'badge', 'sysimgtype', '系统徽章图片类型', '.gif', '只能固定设置一种图片类型', 0, 1, '', 10200, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'badge', 'imagetype', '自定义徽章图片类型', '.gif .jpg .jpeg .png', '多种类型用空格分开，如“.gif .jpg .jpeg .png”', 0, 1, '', 10300, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'badge', 'maximagesize', '徽章图片不能超过几K', '30', '', 0, 1, '', 10400, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'badge', 'defaultmaxnum', '默认徽章数量', '0', '增加新徽章时候默认的徽章数量，设成 0 表示不限制。', 0, 1, '', 10500, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'badge', 'userbadgenum', '用户信息默认显示几个徽章', '5', '超过这个数量的徽章需要在用户详细资料里面看', 0, 3, '', 11100, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'badge', 'pagenum', '每页显示徽章数量', '50', '', 0, 3, '', 11200, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'badge', 'awardpnum', '每页显示授予徽章记录数', '50', '', 0, 3, '', 11300, '显示控制');


#相关权限
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'badge', 'managesystem', '管理系统徽章', '可以修改系统默认的徽章', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'badge', 'managemodule', '管理模块徽章', '可以增加、修改和删除模块相关徽章', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'badge', 'managecustom', '管理自定义徽章', '可以增加、修改和删除自定义类型徽章', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'badge', 'awardview', '查看徽章授予记录', '', '');
