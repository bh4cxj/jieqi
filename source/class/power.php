<?php
/**
 * 数据表类(jieqi_system_power - 权限信息表)
 *
 * 数据表类(jieqi_system_power - 权限信息表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: power.php 312 2008-12-29 05:30:54Z juny $
 */

jieqi_includedb();
//区块类
class JieqiPower extends JieqiObjectData
{

    //构建函数
    function JieqiPower()
    {
        $this->initVar('pid', JIEQI_TYPE_INT, 0, '序号', false, 8);
        $this->initVar('modname', JIEQI_TYPE_TXTBOX, '', '模块名称', true, 50);
        $this->initVar('pname', JIEQI_TYPE_TXTBOX, '', '权限名称', true, 50);
        $this->initVar('ptitle', JIEQI_TYPE_TXTBOX, '', '权限标题', false, 50);
        $this->initVar('pdescription', JIEQI_TYPE_TXTAREA, '', '权限描述', false, NULL);
        $this->initVar('pgroups', JIEQI_TYPE_TXTAREA, '', '权限描述', false, NULL);
    }
	
}


//------------------------------------------------------------------------
//------------------------------------------------------------------------

//区块句柄
class JieqiPowerHandler extends JieqiObjectHandler
{
	function JieqiPowerHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='power';
	    $this->autoid='pid';	
	    $this->dbname='system_power';
	}
	
	function getSavedVars($modname)
	{
	    global $jieqiPower;
	    $criteria=new CriteriaCompo(new Criteria('modname',$modname,'='));
	    $criteria->setSort('pid');
	    $criteria->setOrder('ASC');
	    $this->queryObjects($criteria);
	    while($v = $this->getObject()){
	        $jieqiPower[$modname][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
	    }
	    
	}
}
?>