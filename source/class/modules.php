<?php
/**
 * 数据表类(jieqi_system_modules - 模块信息表)
 *
 * 数据表类(jieqi_system_modules - 模块信息表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: modules.php 312 2008-12-29 05:30:54Z juny $
 */

jieqi_includedb();
//模块类
class JieqiModules extends JieqiObjectData
{
    //构建函数
    function JieqiModules()
    {
        $this->initVar('mid', JIEQI_TYPE_INT, 0, '序号', false, 5);
        $this->initVar('name', JIEQI_TYPE_TXTBOX, '', '模块名称', true, 50);
        $this->initVar('caption', JIEQI_TYPE_TXTBOX, '', '模块标题', false, 50);
        $this->initVar('description', JIEQI_TYPE_TXTAREA, '', '模块描述', false, NULL);
        $this->initVar('version', JIEQI_TYPE_INT, 0, '版本', false, 3);
        $this->initVar('vtype', JIEQI_TYPE_TXTBOX, '', '版本类型', false, 30);
        $this->initVar('lastupdate', JIEQI_TYPE_INT, 0, '最后更新', false, 10);
        $this->initVar('weight', JIEQI_TYPE_INT, 0, '排列顺序', false, 8);
        $this->initVar('publich', JIEQI_TYPE_INT, 0, '是否激活', false, 1);
        $this->initVar('modtype', JIEQI_TYPE_INT, 0, '模块类型', false, 1);
    }
	
}


//------------------------------------------------------------------------
//------------------------------------------------------------------------

//区块句柄
class JieqiModulesHandler extends JieqiObjectHandler
{
	function JieqiModulesHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='modules';
	    $this->autoid='mid';	
	    $this->dbname='system_modules';
	}
}
?>