<?php 
// $Id: post.php 2004-02-21 $
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------
jieqi_includedb();
class Jieqialbum extends JieqiObjectData
{
    //构建函数
    function Jieqialbum()
    {
        $this->JieqiObjectData();
        $this->initVar('albumid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('albumorder', JIEQI_TYPE_INT, 0, '是否主题', false, 1);
        $this->initVar('posterid', JIEQI_TYPE_INT, 0, '发表者序号', false, 11);
        $this->initVar('poster', JIEQI_TYPE_TXTBOX, '', '发表者', false, 30);
        $this->initVar('albumname', JIEQI_TYPE_TXTBOX, '', '主题', false, 60);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqialbumHandler extends JieqiObjectHandler
{
	function JieqialbumHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='album';
	    $this->autoid='albumid';	
	    $this->dbname='group_album';
	}
}

?>