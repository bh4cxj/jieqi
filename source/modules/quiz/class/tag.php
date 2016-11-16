<?php 
// $Id: article.php 2004-02-21 $
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------
jieqi_includedb();

class JieqiTag extends JieqiObjectData
{
	//构建函数
	function JieqiTag()
	{
		$this->JieqiObjectData();//引入基类
		$this->initVar('tagid', JIEQI_TYPE_INT, 0, '序号', true,11);
		$this->initVar('tagname', JIEQI_TYPE_TXTBOX, 0, 'tag名称', false,50);
		$this->initVar('tagcontent', JIEQI_TYPE_TXTBOX, 0, 'tag文章关联', false, 11);
		$this->initVar('tagtype', JIEQI_TYPE_TXTBOX, 0, 'tag类别', false, 11);
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiTagHandler extends JieqiObjectHandler
{
	function JieqiTagHandler($db='')
	{
		$this->JieqiObjectHandler($db);//实例化数据库对象
		$this->basename='tag';
		$this->autoid='tagid';
		$this->dbname='quiz_tag';
	}
}

?>