<?php 
// $Id: article.php 2004-02-21 $
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------
jieqi_includedb();
class JieqiSpaceBlog extends JieqiObjectData
{
    //构建函数
    function JieqiSpaceBlog()
    {
        $this->JieqiObjectData();
        $this->initVar('id', JIEQI_TYPE_INT, 0, '序号', false, 11);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiSpaceBlogHandler extends JieqiObjectHandler
{
	function JieqiSpaceBlogHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='SpaceBlog';
	    $this->autoid='id';	
	    $this->dbname='space_blog';
	}
}

?>
