<?php 
// $Id: article.php 2004-02-21 $
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------
jieqi_includedb();

class JieqiAnswer extends JieqiObjectData
{
	//构建函数
	function JieqiAnswer()
	{
		$this->JieqiObjectData();//引入基类
		$this->initVar('answerid', JIEQI_TYPE_INT, 0, '序号', true,11);
		$this->initVar('problemid', JIEQI_TYPE_INT, 0, 'tag名称', false,11);
		$this->initVar('content', JIEQI_TYPE_TXTBOX, 0, 'tag文章关联', false, 11);
		$this->initVar('addtime', JIEQI_TYPE_TXTBOX, 0, 'tag类别', false, 11);
		$this->initVar('username', JIEQI_TYPE_TXTBOX, 0, 'tag类别', false, 11);
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiAnswerHandler extends JieqiObjectHandler
{
	function JieqiAnswerHandler($db='')
	{
		$this->JieqiObjectHandler($db);//实例化数据库对象
		$this->basename='answer';
		$this->autoid='answerid';
		$this->dbname='quiz_answer';
	}
}

?>