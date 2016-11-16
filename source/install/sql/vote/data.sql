-- 
-- 导出表中的数据 `jieqi_system_blocks`
-- 

INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '投票窗口', 'vote', 'block_votebox', 'BlockVoteBox', 1, '投票栏', '&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置一个参数。<br>&nbsp;&nbsp;&nbsp;&nbsp;该参数是投票话题的序号ID，使用整数（默认为0）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中留空表示调用最新投票。例子： “20” 表示调用投票话题ID为20的投票。', '', '0', 'block_votebox.html', 0, 1, 40100, 0, 0, 0, 3, 1);
INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '历史投票', 'vote', 'block_topiclist', 'BlockTopicList', 1, '历史投票', '&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置两个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是显示投票条数，使用整数（默认 5）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是投票标题显示字数，使用整数（默认 16）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均表示使用默认值。例子： “10,16” 表示显示10条历史投票列表。', '', '5,16', 'block_topiclist.html', 0, 1, 40200, 0, 0, 0, 0, 1);

-- 
-- 导出表中的数据 `jieqi_system_configs`
-- 

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'vote', 'votemanageword', '投票话题管理页标题字数', '40', '', 0, 3, '', 10100, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'vote', 'voteonshowword', '前台投票文字字数限制', '20', '', 0, 3, '', 10200, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'vote', 'votepercentdigit', '投票结果百分比小数位数', '2', '', 0, 3, '', 10300, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'vote', 'votetimelimit', '重复投票最短时间间隔', '3600', '单位：秒', 0, 3, '', 20100, '时间控制');

-- 
-- 导出表中的数据 `jieqi_system_modules`
-- 

INSERT INTO `jieqi_system_modules` (`mid`, `name`, `caption`, `description`, `version`, `vtype`, `lastupdate`, `weight`, `publish`, `modtype`) VALUES (0, 'vote', '投票调查', '与本站结合的投票系统', 110, '', 0, 0, 1, 0);

-- 
-- 导出表中的数据 `jieqi_system_power`
-- 

INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'vote', 'adminconfig', '管理参数设置', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'vote', 'adminpower', '管理权限设置', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'vote', 'votemanage', '管理投票设置', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'vote', 'votepermit', '允许投票', '', '');

