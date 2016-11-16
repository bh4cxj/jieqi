INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'pay', 'paylogpnum', '在线支付记录每页显示数', '50', '', 0, 3, '', 10700, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'pay', 'egoldtransrate', '虚拟货币转换比率', '100', '百分比，请填写0~100的数字，0表示不允许转换', 0, 3, '', 11100, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'pay', 'creditransrate', '销售积分转换比率', '10', '百分比，请填写0~100的数字，0表示不允许转换', 0, 3, '', 11200, '显示控制');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES(0, 'pay', 'scoretransrate', '网站积分转换比率', '0', '百分比，请填写0~100的数字，0表示不允许转换', 0, 3, '', 11300, '显示控制');


INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'pay', 'adminpaylog', '管理充值记录', '', '');


INSERT INTO `jieqi_system_modules` (`mid`, `name`, `caption`, `description`, `version`, `lastupdate`, `weight`, `publish`, `modtype`) VALUES (0, 'pay', '在线充值', '虚拟币充值', 140, 0, 0, 1, 0);
