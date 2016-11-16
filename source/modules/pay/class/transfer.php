<?php
/**
 * 数据表类(jieqi_pay_transfer - 转账记录)
 *
 * 数据表类(jieqi_pay_transfer - 转账记录)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: transfer.php 300 2008-12-26 04:36:06Z juny $
 */

jieqi_includedb();
//转帐记录
class JieqiTransfer extends JieqiObjectData
{
    function JieqiTransfer()
    {
        $this->JieqiObject();
        $this->initVar('transid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('transtime', JIEQI_TYPE_INT, 0, '操作时间', false, 11);
        $this->initVar('fromid', JIEQI_TYPE_INT, 0, '转出人序号', false, 11);
        $this->initVar('fromname', JIEQI_TYPE_TXTBOX, '', '转出人',false, 30);
        $this->initVar('toid', JIEQI_TYPE_INT, 0, '转入人序号', false, 11);
        $this->initVar('toname', JIEQI_TYPE_TXTBOX, '', '转入人',false, 30);
        $this->initVar('translog', JIEQI_TYPE_TXTAREA, '', '转帐说明', false, NULL);
        $this->initVar('transegold', JIEQI_TYPE_INT, 0, '转出金额', false, 11);
        $this->initVar('receiveegold', JIEQI_TYPE_INT, 0, '收到金额', false, 11);
        $this->initVar('mastertime', JIEQI_TYPE_INT, 0, '管理时间', false, 11);
        $this->initVar('masterid', JIEQI_TYPE_INT, 0, '管理员序号', false, 11);
        $this->initVar('mastername', JIEQI_TYPE_TXTBOX, '', '管理员',false, 30);
        $this->initVar('masterlog', JIEQI_TYPE_TXTAREA, '', '管理说明', false, NULL);
        $this->initVar('transtype', JIEQI_TYPE_INT, 0, '转帐方式', false, 1); //(1-从虚拟货币转出，2-从可赠货币转出，3-从积分转出)
        $this->initVar('errflag', JIEQI_TYPE_INT, 0, '错误标志', false, 1);
        $this->initVar('transflag', JIEQI_TYPE_INT, 0, '转帐状态', false, 1);//(0-准备转帐， 1-已经转出， 2-已经转入， 3-手工确认， 4-手工退款)
    }
}


//用户组句柄
class JieqiTransferHandler extends JieqiObjectHandler
{
	function JieqiTransferHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='transfer';
	    $this->autoid='transid';	
	    $this->dbname='pay_transfer';
	}
}


?>