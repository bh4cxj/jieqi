<?php
/**
 * 数据表类(jieqi_system_groups - 用户组表)
 *
 * 数据表类(jieqi_system_groups - 用户组表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: groups.php 312 2008-12-29 05:30:54Z juny $
 */

jieqi_includedb();
//用户组
class JieqiGroups extends JieqiObjectData
{
    function JieqiGroups()
    {
        $this->JieqiObject();
        $this->initVar('groupid', JIEQI_TYPE_INT, 0, '序号', false, 5);
        $this->initVar('name', JIEQI_TYPE_TXTBOX, '', '用户组名称',true, 50);
        $this->initVar('description', JIEQI_TYPE_TXTAREA, '', '描述', false, NULL);
        $this->initVar('grouptype', JIEQI_TYPE_INT, 0, '类型', false, 1);
    }
}

//用户组句柄
class JieqiGroupsHandler extends JieqiObjectHandler
{
	function JieqiGroupsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='groups';
	    $this->autoid='groupid';	
	    $this->dbname='system_groups';
	}
}

?>