<?php 
/**
 * 数据表类(jieqi_obook_osale - 电子书销售记录)
 *
 * 数据表类(jieqi_obook_osale - 电子书销售记录)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: osale.php 300 2008-12-26 04:36:06Z juny $
 */

jieqi_includedb();

class JieqiOsale extends JieqiObjectData
{
    //构建函数
    function JieqiOsale()
    {
        $this->JieqiObjectData();   
        $this->initVar('osaleid', JIEQI_TYPE_INT, 0, '订单序号', false, 11);
        $this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
        $this->initVar('buytime', JIEQI_TYPE_INT, 0, '购买日期', false, 11);
        $this->initVar('accountid', JIEQI_TYPE_INT, 0, '帐号id', false, 11);
        $this->initVar('account', JIEQI_TYPE_TXTBOX, '', '帐号名称', false, 30);
        $this->initVar('obookid', JIEQI_TYPE_INT, 0, '电子书序号', false, 11);
        $this->initVar('ochapterid', JIEQI_TYPE_INT, 0, '章节序号', false, 11);
        $this->initVar('obookname', JIEQI_TYPE_TXTBOX, '', '电子书名', true, 100);
        $this->initVar('chaptername', JIEQI_TYPE_TXTBOX, '', '章节名', true, 100);
        $this->initVar('saleprice', JIEQI_TYPE_INT, 0, '销售价格', false, 11);
        $this->initVar('pricetype', JIEQI_TYPE_INT, 0, '价格类型', false, 1);  
        $this->initVar('paytype', JIEQI_TYPE_INT, 0, '支付方式', false, 1);  
        $this->initVar('payflag', JIEQI_TYPE_INT, 0, '支付标志', false, 1);  
        $this->initVar('paynote', JIEQI_TYPE_TXTAREA, '', '备注', false, NULL);  
        $this->initVar('state', JIEQI_TYPE_INT, 0, '状态', false, 1);
        $this->initVar('flag', JIEQI_TYPE_INT, 0, '标志', false, 1);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiOsaleHandler extends JieqiObjectHandler
{
	
	function JieqiOsaleHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='osale';
	    $this->autoid='osaleid';	
	    $this->dbname='obook_osale';
	}
}

?>