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
//相册附件
class JieqiAttachs extends JieqiObjectData
{
    //构建函数
    function JieqiAttachs()
    {       
        $this->JieqiObjectData();
        $this->initVar('attachid', JIEQI_TYPE_INT, 0, '附件序号', false, 11);
		$this->initVar('catid', JIEQI_TYPE_INT, 0, '相册编号', false, 11);
		$this->initVar('uid', JIEQI_TYPE_INT, 0, '用户编号', false, 11);
		$this->initVar('name', JIEQI_TYPE_TXTBOX, '', '附件名称', true, 80);
        $this->initVar('class', JIEQI_TYPE_TXTBOX, '', '附件类型', true, 30);
		$this->initVar('postfix', JIEQI_TYPE_TXTBOX, '', '附件后缀', true, 30);
		$this->initVar('filebak', JIEQI_TYPE_TXTBOX, '', '附件说明', true, 50);
		$this->initVar('size', JIEQI_TYPE_INT, 0, '文件大小', false, 11);
		$this->initVar('url', JIEQI_TYPE_TXTBOX, '', '绝对路径', true, 100);
		$this->initVar('isdefault', JIEQI_TYPE_INT, 0, '是否默认封面', false, 11);
		$this->initVar('uptime', JIEQI_TYPE_INT, 0, '上传时间', false, 11);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiAttachsHandler extends JieqiObjectHandler
{
	function JieqiAttachsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='attachs';
	    $this->autoid='attachid';	
	    $this->dbname='space_attachs';
	}
}
?>