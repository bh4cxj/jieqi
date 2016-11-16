<?php
/**
 * 数据表类(jieqi_pay_paylog - 充值记录)
 *
 * 数据表类(jieqi_pay_paylog - 充值记录)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: paylog.php 234 2008-11-28 01:53:06Z juny $
 */

jieqi_includedb();
//支付日志
class JieqiPaylog extends JieqiObjectData
{
    function JieqiPaylog()
    {
        $this->JieqiObject();
        $this->initVar('payid', JIEQI_TYPE_INT, 0, '序号', false);
        $this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
        $this->initVar('buytime', JIEQI_TYPE_INT, 0, '购买时间', false);
        $this->initVar('rettime', JIEQI_TYPE_INT, 0, '返回时间', false);
        $this->initVar('buyid', JIEQI_TYPE_INT, 0, '购买者序号', false);
        $this->initVar('buyname', JIEQI_TYPE_TXTBOX, '', '购买人',false, 30);
        $this->initVar('buyinfo', JIEQI_TYPE_TXTAREA, '', '购买人信息', false);
        $this->initVar('moneytype', JIEQI_TYPE_INT, 0, '金额类型', false);
        $this->initVar('money', JIEQI_TYPE_INT, 0, '金额', false);
        $this->initVar('egoldtype', JIEQI_TYPE_INT, 0, '虚拟货币类型', false);
        $this->initVar('egold', JIEQI_TYPE_INT, 0, '虚拟货币', false);
        $this->initVar('paytype', JIEQI_TYPE_TXTBOX, '', '支付类型', false);
        $this->initVar('retserialno', JIEQI_TYPE_TXTAREA, '', '返回流水号', false);
        $this->initVar('retaccount', JIEQI_TYPE_TXTAREA, '', '返回账号', false);
        $this->initVar('retinfo', JIEQI_TYPE_TXTAREA, '', '返回信息', false);
        $this->initVar('masterid', JIEQI_TYPE_INT, 0, '管理员序号', false);
        $this->initVar('mastername', JIEQI_TYPE_TXTBOX, '', '管理员',false, 30);
        $this->initVar('masterinfo', JIEQI_TYPE_TXTAREA, '', '管理员信息', false);
        $this->initVar('note', JIEQI_TYPE_TXTAREA, '', '备注', false);
        $this->initVar('payflag', JIEQI_TYPE_INT, 0, '支付标志', false);
    }
}


//用户组句柄
class JieqiPaylogHandler extends JieqiObjectHandler
{
	function JieqiPaylogHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='paylog';
	    $this->autoid='payid';	
	    $this->dbname='pay_paylog';
	}
}


?>