<?php
/**
 * 数据表类(jieqi_system_userlink - 用户友情链接表)
 *
 * 数据表类(jieqi_system_userlink - 用户友情链接表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: userlink.php 301 2008-12-26 04:36:17Z juny $
 */

jieqi_includedb();
//用户管理日志
class JieqiUserlink extends JieqiObjectData
{ 
    //构建函数
    function JieqiUserlink()
    {
        $this->JieqiObjectData();
        $this->initVar('ulid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('ultitle', JIEQI_TYPE_TXTBOX, '', '网址标题', false, 60);
        $this->initVar('ulurl', JIEQI_TYPE_TXTBOX, '', '网址', false, 100);
        $this->initVar('ulinfo', JIEQI_TYPE_TXTAREA, '', '网址说明', false, NULL);
        $this->initVar('userid', JIEQI_TYPE_INT, 0, '用户ID', false, 11);
        $this->initVar('username', JIEQI_TYPE_TXTBOX, '', '用户名', false, 30);
        $this->initVar('score', JIEQI_TYPE_INT, 0, '评分', false, 1);
        $this->initVar('weight', JIEQI_TYPE_INT, 0, '等级', false, 6);
        $this->initVar('toptime', JIEQI_TYPE_INT, 0, '置顶时间', false, 11);
        $this->initVar('addtime', JIEQI_TYPE_INT, 0, '加入时间', false, 11);
        $this->initVar('allvisit', JIEQI_TYPE_INT, 0, '点击数', false, 11);
         
    }
}
//------------------------------------------------------------------------


//------------------------------------------------------------------------

//用户句柄
class JieqiUserlinkHandler extends JieqiObjectHandler
{
    function JieqiUserlinkHandler($db='')
    {
        $this->JieqiObjectHandler($db);
        $this->basename='userlink';
        $this->autoid='ulid';
        $this->dbname='system_userlink';
    }
}
?>