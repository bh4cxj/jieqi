<?php 
/**
 * 数据表类(jieqi_obook_obuyinfo - 电子书购买信息)
 *
 * 数据表类(jieqi_obook_obuyinfo - 电子书购买信息)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obuyinfo.php 231 2008-11-27 08:46:26Z juny $
 */

jieqi_includedb();

class JieqiObuyinfo extends JieqiObjectData
{
    //构建函数
    function JieqiObuyinfo()
    {
        $this->JieqiObjectData();    
        $this->initVar('obuyinfoid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
        $this->initVar('osaleid', JIEQI_TYPE_INT, 0, '订单序号', false, 11);
        $this->initVar('buytime', JIEQI_TYPE_INT, 0, '购买日期', false, 11);
        $this->initVar('userid', JIEQI_TYPE_INT, 0, '用户序号', false, 11);
        $this->initVar('username', JIEQI_TYPE_TXTBOX, '', '用户名称', false, 30);
        $this->initVar('obookid', JIEQI_TYPE_INT, 0, '电子书序号', false, 11);
        $this->initVar('ochapterid', JIEQI_TYPE_INT, 0, '章节序号', false, 11);
        $this->initVar('obookname', JIEQI_TYPE_TXTBOX, '', '电子书名', true, 100);
        $this->initVar('chaptername', JIEQI_TYPE_TXTBOX, '', '章节名', true, 100);
        $this->initVar('lastread', JIEQI_TYPE_INT, 0, '最后阅读', false, 11);
        $this->initVar('readnum', JIEQI_TYPE_INT, 0, '阅读次数', false, 11);
        $this->initVar('checkcode', JIEQI_TYPE_TXTBOX, '', '校验码', false, 10);
        $this->initVar('state', JIEQI_TYPE_INT, 0, '状态', false, 1);
        $this->initVar('flag', JIEQI_TYPE_INT, 0, '标志', false, 1);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiObuyinfoHandler extends JieqiObjectHandler
{
	
	function JieqiObuyinfoHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='obuyinfo';
	    $this->autoid='obuyinfoid';	
	    $this->dbname='obook_obuyinfo';
	}
}

?>