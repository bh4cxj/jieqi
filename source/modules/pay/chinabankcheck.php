<?php 
/**
 * 网银在线-数据校验
 *
 * 网银在线-数据校验 (http://www.chinabank.com.cn)
 * 
 * 调用模板：/modules/pay/templates/chinabank.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chinabankcheck.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'chinabank');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');
$v_mid = $jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号，改成IPAY负责分配给商户的编号
$key = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];  //默认的私钥值，更改私钥后要修改这里

$v_oid        = trim($_POST['v_oid']); //商户发送的v_oid定单编号     
$v_pmode      = trim($_POST['v_pmode']);  //支付银行，例如工商银行    
$v_pstatus    = trim($_POST['v_pstatus']);  //20（表示支付成功）30（表示支付失败）    
$v_pstring    = trim($_POST['v_pstring']);  //支付成功 支付失败    
$v_amount     = trim($_POST['v_amount']); //订单实际支付金额    
$v_moneytype  = trim($_POST['v_moneytype']); //订单实际支付币种    
$remark1      = trim($_POST['remark1' ]);     
$remark2      = trim($_POST['remark2' ]);     
$v_md5str     = trim($_POST['v_md5str' ]);      

$money=intval($v_amount * 100);

//2-----------重新计算md5的值---------------------------------------------------------------------------
$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));

if ($v_md5str==$md5string)
{
	//商户系统的逻辑处理（例如判断金额，判断支付状态，更新订单状态等等）......
	echo "ok";
}else{
	echo "error";
}
?>