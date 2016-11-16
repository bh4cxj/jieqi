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
//group poll

class Jieqipoll extends JieqiObjectData
{
    //构建函数
    function Jieqipoll()
    {
        $this->JieqiObjectData();
        $this->initVar('topicid', JIEQI_TYPE_INT, 0, '序号', false, 6);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqipollHandler extends JieqiObjectHandler
{
	function JieqipollHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='poll';
	    $this->autoid='topicid';	
	    $this->dbname=JIEQI_MODULE_NAME.'_poll';
	}
}
?>