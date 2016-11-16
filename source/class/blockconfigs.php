<?php
/**
 * 数据表类(jieqi_system_blockconfigs - 系统区块配置文件参数表)
 *
 * 数据表类(jieqi_system_blockconfigs - 系统区块配置文件参数表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: manageblocks 312 2008-12-29 05:30:54Z juny $
 */

jieqi_includedb();
//配置类
class JieqiBlockconfigs extends JieqiObjectData
{

    //构建函数
    function JieqiBlockconfigs()
    {
        $this->initVar('id', JIEQI_TYPE_INT, 0, '序号', false, 8);
        $this->initVar('modules', JIEQI_TYPE_TXTBOX, '', '所属模块', true, 50);
        $this->initVar('name', JIEQI_TYPE_TXTBOX, '', '配置文件说明', true, 50);
        $this->initVar('file', JIEQI_TYPE_TXTBOX, '', '文件名称', false, 50);
    }
	
}


//------------------------------------------------------------------------
//------------------------------------------------------------------------

//区块句柄
class JieqiBlockconfigsHandler extends JieqiObjectHandler
{
	function JieqiBlockconfigsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='blockconfigs';
	    $this->autoid='id';	
	    $this->dbname='system_blockconfigs';
	}
	
}
?>