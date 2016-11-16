<?php
// $Id: forumcat.php 2004-03-04 $
//  ------------------------------------------------------------------------ 
//                                杰奇网络                                     
//                    Copyright (c) 2004 jieqi.com                         
//                       <http://www.jieqi.com/>                           
//  ------------------------------------------------------------------------
//  设计：徐风(juny)
//  邮箱: 377653@qq.com
//  ------------------------------------------------------------------------
jieqi_includedb();

class Jieqimembergroup extends JieqiObjectData
{
    //构建函数
    function Jieqimembergroup()
    {
        $this->JieqiObjectData();
        $this->initVar('membergid', JIEQI_TYPE_INT, 0, '序号', false, 6);
        $this->initVar('membergtitle', JIEQI_TYPE_TXTBOX, '', '类别名称', true, 100);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqimembergroupHandler extends JieqiObjectHandler
{
	function JieqimembergroupHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='membergroup';
	    $this->autoid='membergid';	
	    $this->dbname=JIEQI_MODULE_NAME.'_membergroup';
	}
}
?>