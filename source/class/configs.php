<?php
/**
 * 数据表类(jieqi_system_configs - 系统配置参数表)
 *
 * 数据表类(jieqi_system_configs - 系统配置参数表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: configs.php 312 2008-12-29 05:30:54Z juny $
 */

jieqi_includedb();
//配置类
class JieqiConfigs extends JieqiObjectData
{

    //构建函数
    function JieqiConfigs()
    {
        $this->initVar('cid', JIEQI_TYPE_INT, 0, '序号', false, 8);
        $this->initVar('modname', JIEQI_TYPE_TXTBOX, '', '模块名称', true, 50);
        $this->initVar('cname', JIEQI_TYPE_TXTBOX, '', '配置名称', true, 50);
        $this->initVar('ctitle', JIEQI_TYPE_TXTBOX, '', '配置标题', false, 50);
        $this->initVar('cvalue', JIEQI_TYPE_TXTAREA, '', '配置值', false, NULL);
        $this->initVar('cdescription', JIEQI_TYPE_TXTAREA, '', '配置描述', false, NULL);
        $this->initVar('cdefine', JIEQI_TYPE_INT, 0, '是否定义', false, 1);
        $this->initVar('ctype', JIEQI_TYPE_INT, 0, '变量类型', false, 1);
        $this->initVar('options', JIEQI_TYPE_TXTAREA, '', '可用选项', false, NULL);
    }
	
}


//------------------------------------------------------------------------
//------------------------------------------------------------------------

//区块句柄
class JieqiConfigsHandler extends JieqiObjectHandler
{
	function JieqiConfigsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='configs';
	    $this->autoid='cid';	
	    $this->dbname='system_configs';
	}
	
}
?>