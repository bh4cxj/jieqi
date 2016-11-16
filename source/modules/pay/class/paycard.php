<?php
/**
 * 数据表类(jieqi_pay_paycard - 支付结算)
 *
 * 数据表类(jieqi_pay_paycard - 支付结算)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: paycard.php 234 2008-11-28 01:53:06Z juny $
 */

jieqi_includedb();
//点卡表
class JieqiPaycard extends JieqiObjectData
{

    //构建函数
    function JieqiPaycard()
    {
        $this->JieqiObjectData();
        $this->initVar('cardid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('batchno', JIEQI_TYPE_TXTBOX, '', '批号', false, 30);
        $this->initVar('cardno', JIEQI_TYPE_TXTBOX, '', '卡号', true, 30);
        $this->initVar('cardpass', JIEQI_TYPE_TXTBOX, '', '密码', false, 30);
        $this->initVar('cardtype', JIEQI_TYPE_INT, 0, '卡类型', false, 1);
        $this->initVar('payemoney', JIEQI_TYPE_INT, 0, '冲值虚拟货币量', false, 11);
        $this->initVar('emoneytype', JIEQI_TYPE_INT, 0, '虚拟货币类型', false, 1);
        $this->initVar('ispay', JIEQI_TYPE_INT, 0, '是否已使用', false, 1);
        $this->initVar('paytime', JIEQI_TYPE_INT, 0, '使用时间', false, 11);
        $this->initVar('payuid', JIEQI_TYPE_INT, 0, '使用人ID', false, 11);
        $this->initVar('payname', JIEQI_TYPE_TXTBOX, 0, '使用人名称', false, 30);
        $this->initVar('note', JIEQI_TYPE_TXTBOX, '', '备注', false, 255);
        $this->initVar('flag', JIEQI_TYPE_INT, 0, '标志', false, 1);
    }
    

}


//------------------------------------------------------------------------
//------------------------------------------------------------------------

//点卡句柄
class JieqiPaycardHandler extends JieqiObjectHandler
{
	function JieqiPaycardHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='paycard';
	    $this->autoid='cardid';	
	    $this->dbname='pay_paycard';
	}	
}
?>