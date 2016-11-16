<?php
/**
 * 数据表类(jieqi_group_topics - 帖子主题表)
 *
 * 数据表类(jieqi_group_topics - 帖子主题表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: lee $
 * @version    $Id: grouptopics.php 329 2009-02-07 01:21:38Z lee $
 */


jieqi_includedb();
include_once(JIEQI_ROOT_PATH.'/class/topics.php');
//工会话题版块
class JieqiGrouptopics extends JieqiTopics
{
    //构建函数
    function JieqiGrouptopics()
    {       
        $this->JieqiTopics();
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiGrouptopicsHandler extends JieqiObjectHandler
{
	function JieqiGrouptopicsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='grouptopics';
	    $this->autoid='topicid';	
	    $this->dbname='group_topics';
	}
}
?>