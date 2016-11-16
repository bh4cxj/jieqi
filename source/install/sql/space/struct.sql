-- phpMyAdmin SQL Dump
-- version 2.10.2-rc1
-- http://www.phpmyadmin.net
-- 
-- 主机: localhost
-- 生成日期: 2009 年 01 月 23 日 10:48
-- 服务器版本: 5.0.45
-- PHP 版本: 5.1.4

-- 
-- 数据库: `jieqi16`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_space_attachs`
-- 

CREATE TABLE `jieqi_space_attachs` (
  `attachid` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) unsigned default '0',
  `uid` int(11) default NULL,
  `name` varchar(80) default '',
  `class` varchar(30) default '',
  `postfix` varchar(30) default '',
  `size` int(11) unsigned default '0',
  `filebak` varchar(50) default NULL,
  `url` varchar(100) default NULL,
  `isdefault` smallint(3) default '0',
  `uptime` int(11) default '0',
  PRIMARY KEY  (`attachid`),
  KEY `chapterid` (`catid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_space_blog`
-- 

CREATE TABLE `jieqi_space_blog` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT '文章id',
  `cat_id` smallint(6) NOT NULL default '0' COMMENT '文章分类id',
  `title` varchar(80) NOT NULL COMMENT '文章标题',
  `content` text NOT NULL COMMENT '文章内容',
  `hit_num` int(11) unsigned NOT NULL default '0' COMMENT '点击数',
  `up_time` int(11) NOT NULL default '0' COMMENT '写文章时间',
  `review_num` int(11) NOT NULL COMMENT '评论数',
  `ar_open` smallint(1) NOT NULL default '1' COMMENT '是否公开',
  `allow_com` smallint(1) NOT NULL default '1' COMMENT '是否允许评论',
  `uid` int(11) NOT NULL default '0' COMMENT '作者id',
  `name` varchar(30) NOT NULL COMMENT '作者昵称',
  `default_cat` tinyint(4) NOT NULL default '1' COMMENT '是否默认分类',
  `ar_commend` tinyint(4) NOT NULL default '0' COMMENT '推荐文章标记(由系统admin在后台管理)',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_space_blogcat`
-- 

CREATE TABLE `jieqi_space_blogcat` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT '文章分类的id',
  `name` varchar(80) NOT NULL COMMENT '文章分类标题',
  `cat_order` tinyint(4) NOT NULL default '0' COMMENT '显示顺序',
  `num` int(11) unsigned NOT NULL default '0' COMMENT '文章数量',
  `intro` text NOT NULL COMMENT '分类介绍',
  `uid` int(11) NOT NULL default '0' COMMENT '用户id',
  `default_cat` smallint(1) NOT NULL default '0' COMMENT '是否默认分类',
  `image` varchar(100) NOT NULL default '/modules/space/templates/images/default.gif' COMMENT '封面图片',
  `attachment` text COMMENT '附件',
  `type` varchar(15) NOT NULL default 'blog' COMMENT '类型blog/image',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_space_blogreview`
-- 

CREATE TABLE `jieqi_space_blogreview` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT '自动编号,主键',
  `blog_id` int(11) NOT NULL COMMENT '文章id',
  `uid` int(11) NOT NULL COMMENT '文章发表者用户id',
  `poster_id` int(11) NOT NULL COMMENT '评论者id',
  `poster_name` varchar(50) NOT NULL COMMENT '评论者名字',
  `up_time` int(11) NOT NULL COMMENT '评论时间',
  `title` varchar(100) NOT NULL COMMENT '评论标题',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_space_space`
-- 

CREATE TABLE `jieqi_space_space` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `name` varchar(100) NOT NULL COMMENT '用户名',
  `title` varchar(20) NOT NULL COMMENT '空间简介标题',
  `cat_id` int(10) unsigned NOT NULL COMMENT '空间分类(备用)',
  `domain` varchar(20) NOT NULL COMMENT '域名(备用)',
  `brief` text NOT NULL COMMENT '空间简介',
  `theme` varchar(40) NOT NULL default 'newsportal' COMMENT '风格模板',
  `blog_num` int(11) NOT NULL default '0' COMMENT '空间文章数',
  `pic_num` int(11) NOT NULL default '0' COMMENT '空间图片数',
  `visit_num` int(11) NOT NULL COMMENT '来访次数',
  `up_time` int(11) NOT NULL COMMENT '最后更新时间',
  `sp_commend` tinyint(1) NOT NULL default '0' COMMENT '是否被推荐',
  `sp_open` tinyint(1) NOT NULL default '1' COMMENT '是否关闭'
) TYPE=MyISAM;
