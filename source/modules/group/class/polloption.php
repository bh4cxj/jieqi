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
//group polloption

class Jieqipolloption extends JieqiObjectData
{
    //构建函数
    function Jieqipolloption()
    {
        $this->JieqiObjectData();
        $this->initVar('polloptionid', JIEQI_TYPE_INT, 0, '序号', false, 6);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqipolloptionHandler extends JieqiObjectHandler
{
	function JieqipolloptionHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='polloption';
	    $this->autoid='polloptionid';	
	    $this->dbname=JIEQI_MODULE_NAME.'_polloption';
	}
}
?>