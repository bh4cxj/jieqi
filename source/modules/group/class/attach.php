<?php
//  ------------------------------------------------------------------------ 
//                                杰奇网络                                     
//                    Copyright (c) 2004 jieqi.com                         
//                       <http://www.jieqi.com/>                           
//  ------------------------------------------------------------------------
//  设计：徐风(juny)
//  邮箱: 377653@qq.com
//  ------------------------------------------------------------------------
jieqi_includedb();
//论坛附件
class Jieqiattach extends JieqiObjectData
{
	//构建函数
	function Jieqiattach()
	{       
		$this->JieqiObjectData();
		$this->initVar('attachid', JIEQI_TYPE_INT, 0, '附件序号', false, 11);
		$this->initVar('topicid', JIEQI_TYPE_INT, 0, '主题序号', false, 11);
		$this->initVar('postid', JIEQI_TYPE_INT, 0, '帖子序号', false, 11);
		$this->initVar('name', JIEQI_TYPE_TXTBOX, '', '附件名称', true, 80);
		$this->initVar('class', JIEQI_TYPE_TXTBOX, '', '附件类型', true, 30);
		$this->initVar('postfix', JIEQI_TYPE_TXTBOX, '', '附件后缀', true, 30);
		$this->initVar('size', JIEQI_TYPE_INT, 0, '文件大小', false, 11);
		$this->initVar('hits', JIEQI_TYPE_INT, 0, '点击数', false, 11);
		$this->initVar('needexp', JIEQI_TYPE_INT, 0, '需要经验值', false, 11);
		$this->initVar('uptime', JIEQI_TYPE_INT, 0, '上传时间', false, 11);
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiattachHandler extends JieqiObjectHandler
{
	function JieqiattachHandler($db='')
	{
		$this->JieqiObjectHandler($db);
		$this->basename='attach';
		$this->autoid='attachid';	
		$this->dbname='group_attach';
	}
}
?>