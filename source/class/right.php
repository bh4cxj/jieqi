<?php
/**
 * 数据表类(jieqi_system_right - 用户权利信息表)
 *
 * 数据表类(jieqi_system_right - 用户权利信息表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: right.php 312 2008-12-29 05:30:54Z juny $
 */

jieqi_includedb();
//区块类
class JieqiRight extends JieqiObjectData
{

    //构建函数
    function JieqiRight()
    {
        $this->initVar('rid', JIEQI_TYPE_INT, 0, '序号', false, 8);
        $this->initVar('modname', JIEQI_TYPE_TXTBOX, '', '模块名称', true, 50);
        $this->initVar('rname', JIEQI_TYPE_TXTBOX, '', '权利名称', true, 50);
        $this->initVar('rtitle', JIEQI_TYPE_TXTBOX, '', '权利标题', false, 50);
        $this->initVar('rdescription', JIEQI_TYPE_TXTAREA, '', '权利描述', false, NULL);
        $this->initVar('rhonors', JIEQI_TYPE_TXTAREA, '', '权利描述', false, NULL);
    }
	
}


//------------------------------------------------------------------------
//------------------------------------------------------------------------

//区块句柄
class JieqiRightHandler extends JieqiObjectHandler
{
	function JieqiRightHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='right';
	    $this->autoid='bid';	
	    $this->dbname='system_right';
	}
	
	function getSavedVars($modname)
	{
	    global $jieqiRight;
	    $criteria=new CriteriaCompo(new Criteria('modname',$modname,'='));
	    $criteria->setSort('rid');
	    $criteria->setOrder('ASC');
	    $this->queryObjects($criteria);
	    while($v = $this->getObject()){
	        $jieqiRight[$modname][$v->getVar('rname','n')]=array('caption'=>$v->getVar('rtitle'), 'honors'=>unserialize($v->getVar('rhonors','n')), 'rescription'=>$v->getVar('rdescription'));	        
	    }
	    
	}
}
?>