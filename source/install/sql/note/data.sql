-- 
-- 导出表中的数据 `jieqi_system_blocks`
-- 

INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '最新留言列表', 'note', 'block_noteupdatelist', 'BlockNoteUpdateList', 1, '最新留言', '&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置两个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是显示留言条数，使用整数（默认 10）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是留言标题显示字数，使用整数（默认 16）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均表示使用默认值。例子： “20,16” 表示显示最新留言前20位的列表。', '', '10,16', 'block_noteupdatelist.html', 0, 1, 30100, 0, 0, 0, 0, 1);

-- 
-- 导出表中的数据 `jieqi_system_configs`
-- 

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'note', 'notemanagepnum', '留言管理页留言列表条数', '10', '', 0, 3, '', 10100, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'note', 'notemanageword', '留言管理页留言标题字数', '40', '', 0, 3, '', 10200, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'note', 'maxnoteword', '留言最多允许字数', '255', '', 0, 3, '', 10300, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'note', 'minnotetime', '两次留言最少时间间隔', '30', '单位：秒', 0, 3, '', 20100, '时间控制');

-- 
-- 导出表中的数据 `jieqi_system_modules`
-- 

INSERT INTO `jieqi_system_modules` (`mid`, `name`, `caption`, `description`, `version`, `vtype`, `lastupdate`, `weight`, `publish`, `modtype`) VALUES (0, 'note', '访客留言', '与本站结合的留言发布', 110, '', 0, 0, 1, 0);

-- 
-- 导出表中的数据 `jieqi_system_power`
-- 

INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'note', 'adminconfig', '管理参数设置', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'note', 'adminpower', '管理权限设置', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'note', 'notelist', '查看留言列表', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'note', 'noteshow', '查看留言内容', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'note', 'noteadd', '留言发布', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'note', 'notedel', '留言删除', '', '');

