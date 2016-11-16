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
class JieqiProvince extends JieqiObjectData
{
    //构建函数
    function JieqiProvince()
    {
        $this->JieqiObjectData();
        $this->initVar('provinceid', JIEQI_TYPE_INT, 0, '序号', false, 6);
        $this->initVar('province', JIEQI_TYPE_TXTBOX, '', '类别名称', true, 100);
        $this->initVar('country', JIEQI_TYPE_TXTBOX, '', '排序', false, 6);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiProvinceHandler extends JieqiObjectHandler
{
	function JieqiProvinceHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='Province';
	    $this->autoid='provinceid';	
	    $this->dbname=JIEQI_MODULE_NAME.'_province';
	}
}
?>