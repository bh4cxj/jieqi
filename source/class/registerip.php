<?php
/**
 * 数据表类(jieqi_system_registerip - 注册用户IP记录表)
 *
 * 数据表类(jieqi_system_registerip - 注册用户IP记录表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: registerip.php 312 2008-12-29 05:30:54Z juny $
 */

jieqi_includedb();
//用户管理日志
class JieqiRegisterip extends JieqiObjectData
{ 
    //构建函数
    function JieqiRegisterip()
    {
        $this->JieqiObjectData();
        $this->initVar('ip', JIEQI_TYPE_TXTBOX, 0, '注册人IP', false, 15);
        $this->initVar('regtime', JIEQI_TYPE_INT, 0, '注册时间', false, 11);
        $this->initVar('count', JIEQI_TYPE_INT, 0, '计数', false, 6);
    }
}


//------------------------------------------------------------------------
//------------------------------------------------------------------------

//用户句柄
class JieqiRegisteripHandler extends JieqiObjectHandler
{
    function JieqiRegisteripHandler($db='')
    {
        $this->JieqiObjectHandler($db);
        $this->basename='registerip';
        $this->autoid='';
        $this->dbname='system_registerip';
    }
}
?>