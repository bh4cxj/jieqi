<?php
/**
 * 数据表类(jieqi_forum_forumtopics - 帖子主题表)
 *
 * 数据表类(jieqi_forum_forumtopics - 帖子主题表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: forumtopics.php 329 2009-02-07 01:21:38Z juny $
 */


jieqi_includedb();
include_once(JIEQI_ROOT_PATH.'/class/topics.php');
//论坛版块
class JieqiForumtopics extends JieqiTopics
{
    //构建函数
    function JieqiForumtopics()
    {       
        $this->JieqiTopics();
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiForumtopicsHandler extends JieqiObjectHandler
{
	function JieqiForumtopicsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='forumtopics';
	    $this->autoid='topicid';	
	    $this->dbname='forum_forumtopics';
	}
}
?>