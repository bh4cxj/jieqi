<?php
// $Id: topic.php 2004-03-04 $
//  ------------------------------------------------------------------------ 
//                                杰奇网络                                     
//                    Copyright (c) 2004 jieqi.com                         
//                       <http://www.jieqi.com/>                           
//  ------------------------------------------------------------------------
//  设计：徐风(juny)
//  邮箱: 377653@qq.com
//  ------------------------------------------------------------------------
jieqi_includedb();
//论坛版块
class Jieqitopic extends JieqiObjectData
{
    //构建函数
    function Jieqitopic()
    {       
        $this->JieqiObjectData();
        $this->initVar('topicid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('topicsubject', JIEQI_TYPE_TXTBOX, '', '标题', true, 60);
        $this->initVar('iconid', JIEQI_TYPE_INT, 0, '图标', false, 3);
        $this->initVar('topicposterid', JIEQI_TYPE_INT, 0, '发表者序号', false, 11);
        $this->initVar('topicposter', JIEQI_TYPE_TXTBOX, '', '发表人', false, 30);
        $this->initVar('topictime', JIEQI_TYPE_INT, 0, '发表时间', false, 11);
        $this->initVar('lasttime', JIEQI_TYPE_INT, 0, '回复时间', false, 11);
        $this->initVar('topicviews', JIEQI_TYPE_INT, 0, '点击数', false, 8);
        $this->initVar('topicreplies', JIEQI_TYPE_INT, 0, '回复数', false, 8);
        $this->initVar('topiclock', JIEQI_TYPE_INT, 0, '锁定标志', false, 1);
        $this->initVar('topictop', JIEQI_TYPE_INT, 0, '置顶标志', false, 1);
        $this->initVar('topicgood', JIEQI_TYPE_INT, 0, '精华标志', false, 1);
        $this->initVar('topictype', JIEQI_TYPE_INT, 0, '主题类型', false, 1);
        $this->initVar('topiclastinfo', JIEQI_TYPE_TXTBOX, '', '最后更新', false, 255);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqitopicHandler extends JieqiObjectHandler
{
	function JieqitopicHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='topic';
	    $this->autoid='topicid';	
	    $this->dbname='group_topic';
	}
}
?>