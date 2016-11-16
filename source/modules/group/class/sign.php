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
//group sign

class Jieqisign extends JieqiObjectData
{
    //构建函数
    function Jieqisign()
    {
        $this->JieqiObjectData();
        $this->initVar('signid', JIEQI_TYPE_INT, 0, '序号', false, 6);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqisignHandler extends JieqiObjectHandler
{
	function JieqisignHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='sign';
	    $this->autoid='signid';	
	    $this->dbname=JIEQI_MODULE_NAME.'_sign';
	}
}
?>