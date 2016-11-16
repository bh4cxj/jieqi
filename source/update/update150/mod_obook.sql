UPDATE `jieqi_system_modules` SET version=130 WHERE name='obook';

UPDATE `jieqi_system_configs` SET cvalue = CONCAT(cvalue, '/modules/obook') WHERE modname = 'obook' AND cname = 'dynamicurl' AND cvalue != '' AND cvalue NOT LIKE '%/modules/obook';

UPDATE `jieqi_system_configs` SET cvalue = CONCAT(cvalue, '/modules/obook') WHERE modname = 'obook' AND cname = 'staticurl' AND cvalue != '' AND cvalue NOT LIKE '%/modules/obook';

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'obook', 'obookreadhead', '阅读文字头部附加内容', '', '阅读一个电子书章节时候，内容头部和尾部可以附加一些预先设置的内容，比如网站名称、版权声明等。', 0, 2, '', 32100, '阅读设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'obook', 'obookreadfoot', '阅读文字尾部附加内容', '', '阅读一个电子书章节时候，内容头部和尾部可以附加一些预先设置的内容，比如网站名称、版权声明等。', 0, 2, '', 32200, '阅读设置');


INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'obook', 'jpegquality', 'jpeg图片质量', '90', '范围为 0～100 的整数，数值越大结果图片效果越好，但尺寸也越大，本参数仅生成jpeg格式图片时有效。', 0, 3, '', 30550, '阅读设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'obook', 'obookwater', '是否加图片水印及水印位置', '0', '本功能需要 GD 库支持才能使用，对 JPG/PNG/GIF 格式的图片有效', 0, 7, 'a:11:{i:0;s:8:"不加水印";i:1;s:8:"顶部居左";i:2;s:8:"顶部居中";i:3;s:8:"顶部居右";i:4;s:8:"中部居左";i:5;s:8:"中部居中";i:6;s:8:"中部居右";i:7;s:8:"底部居左";i:8;s:8:"底部居中";i:9;s:8:"底部居右";i:10;s:8:"随机位置";}', 31500, '阅读设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'obook', 'obookwimage', '水印图片文件', 'watermark.gif', '允许 JPG/PNG/GIF 格式，默认只需填文件名，放在 modules/obook/images 目录下', 0, 1, '', 31600, '阅读设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'obook', 'obookwtrans', '水印图片与原图片的融合度', '30', '范围为 1～100 的整数，数值越大水印图片透明度越低。', 0, 3, '', 31700, '阅读设置');


INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'obook', 'obkwatertext', '是否加文字水印及水印位置', '0', '本功能需要 GD 库支持才能使用，对 JPG/PNG/GIF 格式的图片有效', 0, 7, 'a:3:{i:0;s:8:"不加水印";i:1;s:8:"图片四角";i:2;s:8:"背景平铺";}', 31050, '阅读设置');