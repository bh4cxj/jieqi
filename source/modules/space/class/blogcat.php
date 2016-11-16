<?php 
// $Id: post.php 2004-02-21 $
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------
jieqi_includedb();
class JieqiSpaceBlogCat extends JieqiObjectData
{
    //构建函数
    function JieqiSpaceBlogCat()
    {
        $this->JieqiObjectData();
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiSpaceBlogCatHandler extends JieqiObjectHandler
{
	function JieqiSpaceBlogCatHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='SpaceBlogCat';
	    $this->autoid='id';	
	    $this->dbname='space_blogcat';
	}
}

?>
