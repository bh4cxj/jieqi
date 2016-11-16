<?php
/**
 * 数据表类(jieqi_group_attachs - 帖子附件表)
 *
 * 数据表类(jieqi_group_attachs - 帖子附件表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: lee $
 * @version    $Id: groupattachs.php 329 2009-02-12 01:21:38Z lee $
 */

jieqi_includedb();
include_once(JIEQI_ROOT_PATH.'/class/attachs.php');
//工会话题附件
class JieqiGroupattachs extends JieqiAttachs
{
    //构建函数
    function JieqiGroupattachs()
    {       
        $this->JieqiAttachs();
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiGroupattachsHandler extends JieqiObjectHandler
{
	function JieqiGroupattachsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='groupattachs';
	    $this->autoid='attachid';	
	    $this->dbname='group_attachs';
	}
}
?>