<?php
/**
 * 数据表类(jieqi_forum_attachs - 帖子附件表)
 *
 * 数据表类(jieqi_forum_attachs - 帖子附件表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: forumattachs.php 329 2009-02-07 01:21:38Z juny $
 */

jieqi_includedb();
include_once(JIEQI_ROOT_PATH.'/class/attachs.php');
//论坛附件
class JieqiForumattachs extends JieqiAttachs
{
    //构建函数
    function JieqiForumattachs()
    {       
        $this->JieqiAttachs();
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiForumattachsHandler extends JieqiObjectHandler
{
	function JieqiForumattachsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='forumattachs';
	    $this->autoid='attachid';	
	    $this->dbname='forum_attachs';
	}
}
?>