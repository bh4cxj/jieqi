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
class Jieqicity extends JieqiObjectData
{
    //构建函数
    function Jieqicity()
    {
        $this->JieqiObjectData();
        $this->initVar('cityid', JIEQI_TYPE_INT, 0, '序号', false, 6);
        $this->initVar('country', JIEQI_TYPE_TXTBOX, '', '类别名称', true, 100);
        $this->initVar('province', JIEQI_TYPE_TXTBOX, '', '排序', true, 6);
		$this->initVar('city', JIEQI_TYPE_TXTBOX, '', '类别名称', true, 100);
		$this->initVar('area', JIEQI_TYPE_TXTBOX, '', '类别名称', true, 100);
		$this->initVar('postcode', JIEQI_TYPE_TXTBOX, '', '类别名称', true, 100);
		$this->initVar('areacode', JIEQI_TYPE_TXTBOX, '', '类别名称', true, 100);
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqicityHandler extends JieqiObjectHandler
{
	function JieqicityHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='city';
	    $this->autoid='cityid';	
	    $this->dbname=JIEQI_MODULE_NAME.'_city';
	}
}
?>