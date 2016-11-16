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

class Jieqigroup extends JieqiObjectData
{
    //构建函数
    function Jieqigroup()
    {
        $this->JieqiObjectData();
        $this->initVar('gid', JIEQI_TYPE_INT, 0, '序号', false, 6);
        $this->initVar('gname', JIEQI_TYPE_TXTBOX, '', '类别名称', true, 100);
        $this->initVar('gbrief', JIEQI_TYPE_TXTBOX, '', '', true, 100);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqigroupHandler extends JieqiObjectHandler
{
	function JieqigroupHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='group';
	    $this->autoid='gid';	
	    $this->dbname='group_group';
	}
}
?>