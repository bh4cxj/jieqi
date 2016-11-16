-- 
-- 导出表中的数据 `jieqi_system_blocks`
-- 

INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '新闻搜索', 'news', 'block_search', 'BlockNewsSearch', 1, '新闻搜索', '', '', '', '', 0, 0, 20100, 0, 0, 0, 0, 0);

INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '新闻内容列表', 'news', 'block_newslist', 'BlockNewsList', 5, '新闻中心', '&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置四个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是新闻所属一级栏目代号（“0”代表所有栏目），使用整数。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是新闻所属二级栏目代号（“0”代表一级栏目下所有二级栏目），使用整数。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是显示新闻条数，使用整数（默认 5）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是新闻标题显示字数，使用整数（默认 40）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数五是新闻内容区块缓存的时间参数，使用整数（默认 6）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均表示使用默认值。例子： “1,2,5,40” 表示显示新闻一级栏目为1，二级栏目为2的前5条的新闻列表。', '', '1,0,5,40,6', 'block_newslist.html', 0, 1, 20200, 0, 0, 0, 0, 1);

INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '最新新闻列表', 'news', 'block_newsupdatelist', 'BlockNewsUpdateList', 0, '最新新闻', '&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置两个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是显示新闻条数，使用整数（默认 10）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是新闻标题显示字数，使用整数（默认 36）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是最新新闻区块缓存的时间参数，使用整数（默认 6）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均表示使用默认值。例子： “20,36” 表示显示最新新闻前20位的新闻列表。', '', '10,36,6', 'block_newsupdatelist.html', 0, 1, 20300, 0, 0, 0, 0, 1);


-- 
-- 导出表中的数据 `jieqi_system_configs`
-- 

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'newsmanagepnum', '新闻管理页新闻列表条数', '10', '', 0, 3, '', 10100, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'newsmanageword', '新闻管理页新闻标题字数', '40', '', 0, 3, '', 10200, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'attachmanagepnum', '附件管理页附件列表条数', '10', '', 0, 3, '', 10300, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'newslistpnum', '新闻列表页新闻列表条数', '10', '', 0, 3, '', 10400, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'newslistword', '新闻列表页新闻标题字数', '40', '', 0, 3, '', 10500, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'relatenewsenable', '是否显示相关新闻', '0', '', 0, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 10600, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'relatenewslistpnum', '相关新闻列表条数', '5', '', 0, 3, '', 10700, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'relatenewslistword', '相关新闻标题字数', '40', '', 0, 3, '', 10800, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'maxkeyword', '最多允许关键字个数', '3', '', 0, 3, '', 10900, '显示控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'newslistcache', '新闻列表页缓存页数', '10', '', 0, 3, '', 20100, '缓存控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'minclicktime', '两次点击最少时间间隔', '3600', '单位为: 秒', 0, 3, '', 30100, '时间控制');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'htmlextendname', '生成静态文件扩展名', '.html', '', 0, 1, '', 40100, '静态页设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'htmlfilesenable', '是否指向静态文件', '0', '建议大流量用户打开此开关', 0, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 40200, '静态页设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'htmlfilespath', '静态文件保存目录', 'html', '', 0, 1, '', 40300, '静态页设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'imagedir', '上传附件保存目录', 'image', '', 0, 1, '', 50100, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'imagetype', '允许上传附件类型', 'gif jpg jpeg png bmp', '多个类型用空格隔开', 0, 1, '', 50200, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'news', 'maximagesize', '允许上传附件最大尺寸', '200', '单位为: K', 0, 1, '', 50300, '附件设置');

-- 
-- 导出表中的数据 `jieqi_system_modules`
-- 

INSERT INTO `jieqi_system_modules` (`mid`, `name`, `caption`, `description`, `version`, `vtype`, `lastupdate`, `weight`, `publish`, `modtype`) VALUES (0, 'news', '新闻发布', '与本站结合的新闻发布', 110, '', 0, 0, 1, 0);

-- 
-- 导出表中的数据 `jieqi_system_power`
-- 

INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'adminconfig', '管理参数设置', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'adminpower', '管理权限设置', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'managecategory', '管理新闻栏目', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'newslist', '查看新闻列表', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'newsaddback', '新闻后台发布', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'newsaddfront', '新闻前台发布', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'newsneedaudit', '新闻需要审核', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'newsedit', '新闻编辑', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'newsdel', '新闻删除', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'newsaudit', '新闻审核', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'newsputop', '新闻置顶', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'newshtml', '管理新闻静态化', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'manageattach', '管理附件', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'news', 'attachadd', '上传附件', '', '');
