<?php
/**
 * 数据表类(jieqi_forum_forumcat - 论坛分类表)
 *
 * 数据表类(jieqi_forum_forumcat - 论坛分类表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: forumcat.php 253 2008-11-28 03:21:13Z juny $
 */

jieqi_includedb();
//论坛分类
class JieqiForumcat extends JieqiObjectData
{
    //构建函数
    function JieqiForumcat()
    {
        $this->JieqiObjectData();
        $this->initVar('catid', JIEQI_TYPE_INT, 0, '序号', false, 6);
        $this->initVar('cattitle', JIEQI_TYPE_TXTBOX, '', '类别名称', true, 100);
        $this->initVar('catorder', JIEQI_TYPE_INT, 0, '排序', false, 6);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiForumcatHandler extends JieqiObjectHandler
{
	function JieqiForumcatHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='forumcat';
	    $this->autoid='catid';	
	    $this->dbname='forum_forumcat';
	}
}
?>