<?php 
/**
 * 数据表类(jieqi_system_ptopics - 会客室帖子主题表)
 *
 * 数据表类(jieqi_system_ptopics - 会客室帖子主题表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ptopics.php 329 2009-02-07 01:21:38Z juny $
 */

jieqi_includedb();
include_once(JIEQI_ROOT_PATH.'/class/topics.php');
//用户会客室主题
class JieqiPtopics extends JieqiTopics
{
    //构建函数
    function JieqiPtopics()
    {
        $this->JieqiTopics();
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiPtopicsHandler extends JieqiObjectHandler
{
	function JieqiPtopicsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='ptopics';
	    $this->autoid='topicid';	
	    $this->dbname='system_ptopics';
	}
}

?>