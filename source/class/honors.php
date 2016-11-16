<?php
/**
 * 数据表类(jieqi_system_honors - 用户头衔表)
 *
 * 数据表类(jieqi_system_honors - 用户头衔表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: honors.php 312 2008-12-29 05:30:54Z juny $
 */

jieqi_includedb();
//用户组
class JieqiHonors extends JieqiObjectData
{
    function JieqiHonors()
    {
        $this->JieqiObject();
        $this->initVar('honorid', JIEQI_TYPE_INT, 0, '序号', false, 5);
        $this->initVar('caption', JIEQI_TYPE_TXTBOX, '', '头衔名称',true, 50);
        $this->initVar('minscore', JIEQI_TYPE_INT, 0, '最小积分', false, 11);
        $this->initVar('maxscore', JIEQI_TYPE_INT, 0, '最大积分', false, 11);
        $this->initVar('setting', JIEQI_TYPE_TXTAREA, '', '设置', false, NULL);
        $this->initVar('honortype', JIEQI_TYPE_INT, 0, '类型', false, 1);
    }
}

//用户组句柄
class JieqiHonorsHandler extends JieqiObjectHandler
{
	function JieqiHonorsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='honors';
	    $this->autoid='honorid';	
	    $this->dbname='system_honors';
	}
}

?>