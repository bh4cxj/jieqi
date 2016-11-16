<?php
/**
 * 数据表类(jieqi_pay_balance - 支付结算)
 *
 * 数据表类(jieqi_pay_balance - 支付结算)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: balance.php 234 2008-11-28 01:53:06Z juny $
 */

jieqi_includedb();
//结算记录
class JieqiBalance extends JieqiObjectData
{
    function JieqiBalance()
    {
        $this->JieqiObject();
        $this->initVar('balid', JIEQI_TYPE_INT, 0, '序号', false);
        $this->initVar('baltime', JIEQI_TYPE_INT, 0, '操作时间', false);
        $this->initVar('fromid', JIEQI_TYPE_INT, 0, '管理员序号', false);
        $this->initVar('fromname', JIEQI_TYPE_TXTBOX, '', '管理员',false, 30);
        $this->initVar('toid', JIEQI_TYPE_INT, 0, '结算人序号', false);
        $this->initVar('toname', JIEQI_TYPE_TXTBOX, '', '结算人',false, 30);
        $this->initVar('baltype', JIEQI_TYPE_TXTAREA, '', '结算方式', false);
        $this->initVar('ballog', JIEQI_TYPE_TXTAREA, '', '结算说明', false);
        $this->initVar('balegold', JIEQI_TYPE_INT, 0, '结算虚拟货币', false);
        $this->initVar('moneytype', JIEQI_TYPE_INT, 0, '货币类型', false);
        $this->initVar('balmoney', JIEQI_TYPE_INT, 0, '结算金额', false);
        $this->initVar('balflag', JIEQI_TYPE_INT, 0, '结算标志', false);
    }
}


//用户组句柄
class JieqiBalanceHandler extends JieqiObjectHandler
{
	function JieqiBalanceHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='balance';
	    $this->autoid='balid';	
	    $this->dbname='pay_balance';
	}
}


?>