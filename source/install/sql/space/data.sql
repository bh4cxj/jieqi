-- phpMyAdmin SQL Dump
-- version 2.10.2-rc1
-- http://www.phpmyadmin.net
-- 
-- 主机: localhost
-- 生成日期: 2009 年 03 月 05 日 17:25
-- 服务器版本: 5.0.45
-- PHP 版本: 5.1.4

-- 
-- 数据库: `jieqi16`
-- 

-- 
-- 导出表中的数据 `jieqi_system_configs`
-- 

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'space', 'attachdir', '附件保存目录', 'attachment', '', 0, 1, '', 40000, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'space', 'attachtype', '允许上传的附件类型', '*.jpg;*.jpeg;*.gif;*.png;*.bmp', '多个附件用分号格开', 0, 1, '', 40000, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'space', 'attachwater', '图片附件添加水印及位置', '0', '本功能需要 GD 库支持才能使用，对 JPG/PNG/GIF 格式的上传图片有效', 0, 7, 'a:11:{i:0;s:8:"不加水印";i:1;s:8:"顶部居左";i:2;s:8:"顶部居中";i:3;s:8:"顶部居右";i:4;s:8:"中部居左";i:5;s:8:"中部居中";i:6;s:8:"中部居右";i:7;s:8:"底部居左";i:8;s:8:"底部居中";i:9;s:8:"底部居右";i:10;s:8:"随机位置";}', 40000, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'space', 'attachwimage', '附件水印图片文件', 'watermark.jpg', '允许 JPG/PNG/GIF 格式，默认只需填文件名，放在 \r\n\r\nmodules/space/templates/images 目录下', 0, 1, '', 40000, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'space', 'attachwquality', 'jpeg图片质量', '95', '范围为 0～100 的整数，数值越大结果图片效果越好，但尺寸也越大。', 0, 3, '', 40000, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'space', 'attachwtrans', '水印图片与原图片的融合度', '30', '范围为 1～100 的整数，数值越大水印图片透明度越低。', 0, 3, '', 40000, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'space', 'default_brief', '空间默认简介', '%s 的空间欢迎你', '默认的内容显示格式', 0, 0, '', 0, '');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'space', 'default_theme', '默认模板', 'first', '用户默认模板', 0, 0, '', 0, '');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'space', 'default_title', '默认空间标题　', '%s 的空间', '默认的标题显示格式', 0, 0, '', 0, '');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'space', 'maximagesize', '图片附件最大允许几MB', '2', '', 0, 1, '', 40000, '附件设置');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'space', 'review_num', '每页显示评论数', '10', '', 0, 1, '', 0, '');

-- 
-- 导出表中的数据 `jieqi_system_modules`
-- 

INSERT INTO `jieqi_system_modules` (`mid`, `name`, `caption`, `description`, `version`, `vtype`, `lastupdate`, `weight`, `publish`, `modtype`) VALUES (0, 'space', '个人空间', '', 100, '', 0, 0, 1, 0);

