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
//blog分类
class Jieqigcat extends JieqiObjectData
{
    //构建函数
    function Jieqigcat()
    {
        $this->JieqiObjectData();
        $this->initVar('gcatid', JIEQI_TYPE_INT, 0, '序号', false, 6);
        $this->initVar('gcatname', JIEQI_TYPE_TXTBOX, '', '类别名称', true, 100);
        $this->initVar('gcatorder', JIEQI_TYPE_INT, 0, '排序', false, 6);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqigcatHandler extends JieqiObjectHandler
{
	function JieqigcatHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='gcat';
	    $this->autoid='gcatid';	
	    $this->dbname=JIEQI_MODULE_NAME.'_gcat';
	}
}
?>