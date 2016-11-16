<?php
/**
 * 数据表类(jieqi_system_userlog - 用户管理日志表)
 *
 * 数据表类(jieqi_system_userlog - 用户管理日志表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: userlog.php 312 2008-12-29 05:30:54Z juny $
 */

jieqi_includedb();
//用户管理日志
class JieqiUserlog extends JieqiObjectData
{ 
    //构建函数
    function JieqiUserlog()
    {
        $this->JieqiObjectData();
        $this->initVar('logid', JIEQI_TYPE_INT, 0, '日志序号', false, 11);
        $this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
        $this->initVar('logtime', JIEQI_TYPE_INT, 0, '操作时间', false, 11);
        $this->initVar('fromid', JIEQI_TYPE_INT, 0, '操作者id', false, 11);
        $this->initVar('fromname', JIEQI_TYPE_TXTBOX, '', '操作者', false, 30);
        $this->initVar('toid', JIEQI_TYPE_INT, 0, '影响者id', false, 11);
        $this->initVar('toname', JIEQI_TYPE_TXTBOX, '', '影响者', false, 30);
        $this->initVar('reason', JIEQI_TYPE_TXTAREA, '', '修改原因', false, NULL);
        $this->initVar('chginfo', JIEQI_TYPE_TXTAREA, '', '修改描述', false, NULL);
        $this->initVar('chglog', JIEQI_TYPE_TXTAREA, '', '修改记录', false, NULL);
        $this->initVar('isdel', JIEQI_TYPE_INT, 0, '是否删除', false, 1);
        $this->initVar('userlog', JIEQI_TYPE_TXTAREA, '', '用户资料备份', false, NULL);
    }
}


//------------------------------------------------------------------------
//------------------------------------------------------------------------

//用户句柄
class JieqiUserlogHandler extends JieqiObjectHandler
{
    function JieqiUserlogHandler($db='')
    {
        $this->JieqiObjectHandler($db);
        $this->basename='userlog';
        $this->autoid='logid';
        $this->dbname='system_userlog';
    }
}
?>