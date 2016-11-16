<?php 
// $Id: article.php 2004-02-21 $
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------
jieqi_includedb();

class JieqiProblems extends JieqiObjectData
{
	//构建函数
	function JieqiProblems()
	{
		$this->JieqiObjectData();//引入基类
		$this->initVar('quizid', JIEQI_TYPE_INT, 0, '序号', true,11);
		$this->initVar('tags', JIEQI_TYPE_TXTBOX, 0, 'tag标签', false,50);
		$this->initVar('typeid', JIEQI_TYPE_INT, 0, '类别', false, 11);
		$this->initVar('title', JIEQI_TYPE_TXTBOX, 0, '标题', false, 20);
		$this->initVar('content', JIEQI_TYPE_TXTBOX, 0, '内容', false, null);
		$this->initVar('username', JIEQI_TYPE_TXTBOX, 0, '用户名', false, 11);
		$this->initVar('score', JIEQI_TYPE_INT, 0, '悬赏分', false, 5);
		$this->initVar('addtime', JIEQI_TYPE_TXTBOX, 0, '添加时间', false, 11);
		$this->initVar('overtime', JIEQI_TYPE_TXTBOX, 0, '结束时间', false, 11);
		$this->initVar('typez', JIEQI_TYPE_INT, 0, '状态', false, 3);
		$this->initVar('answer', JIEQI_TYPE_INT, 0, '最佳答案', false, 11);
		$this->initVar('readz', JIEQI_TYPE_INT, 0, '浏览次数', false, 8);
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiProblemsHandler extends JieqiObjectHandler
{
	function JieqiProblemsHandler($db='')
	{
		$this->JieqiObjectHandler($db);//实例化数据库对象
		$this->basename='problems';
		$this->autoid='quizid';
		$this->dbname='quiz_problems';
	}
}

?>