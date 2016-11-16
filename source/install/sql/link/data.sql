-- 
-- 导出表中的数据 `jieqi_system_modules`
-- 

INSERT INTO `jieqi_system_modules` (`mid`, `name`, `caption`, `description`, `version`, `vtype`, `lastupdate`, `weight`, `publish`, `modtype`) VALUES (0, 'link', '友情链接', '管理本站的友情链接', 110, '', 0, 0, 1, 0);

INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES(0, '友情链接', 'link', 'block_linklist', 'BlockLinkLinklist', 8, '友情链接', '&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义模板和参数，并且不同的设置可以保存成不同的区块\r\n\r\n。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块默认模板文件为“block_linklist.html”，\r\n\r\n在/modules/link/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设\r\n\r\n置留空表示使用默认模板。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置四个参数，不同参数之间用英文\r\n\r\n逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是显示行数，使用整数（默认 10）\r\n\r\n<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是指按什么类型显示友情链接 0表示文字链接(默认)，1图片链接，2所有\r\n\r\n<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是指如何排序 0表示按排序字段+时间排序(默认), 1排序字段\r\n\r\n，2表示时间<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是指文字友情链接最大显示长度，必须是整数（默认 \r\n\r\n64 ，单位是字节，相当于 32 个汉字）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均\r\n\r\n表示使用默认值。例子： “10,0,1,64” 表示显示10条友情链接。', '', '10,2,0,64', 'block_linklist.html', 0, 1, 22110, 0, 0, 0, 3, 1);
