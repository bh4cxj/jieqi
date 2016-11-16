<?php
/**
 * 易宝支付-公共函数
 *
 * 易宝支付-公共函数 （ http://www.yeepay.com）
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: yeepaycommon.php 300 2008-12-26 04:36:06Z juny $
 */

function hmac ($key, $data)
{
// RFC 2104 HMAC implementation for php.
// Creates an md5 HMAC.
// Eliminates the need to install mhash to compute a HMAC
// Hacked by Lance Rushing(NOTE: Hacked means written)

//需要配置环境支持iconv，否则中文参数不能正常处理
if(function_exists('iconv')){
	$key = iconv("GB2312","UTF-8",$key);
	$data = iconv("GB2312","UTF-8",$data);
}else{
	include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
	$key = jieqi_gb2utf8($key);
	$data = jieqi_gb2utf8($data);
}

$b = 64; // byte length for md5
if (strlen($key) > $b) {
$key = pack("H*",md5($key));
}
$key = str_pad($key, $b, chr(0x00));
$ipad = str_pad('', $b, chr(0x36));
$opad = str_pad('', $b, chr(0x5c));
$k_ipad = $key ^ $ipad ;
$k_opad = $key ^ $opad;

return md5($k_opad . pack("H*",md5($k_ipad . $data)));
}

function getReqHmacString($orderId,$amount,$cur,$productId,$productCat,$productDesc,$sMctProperties,$frpId,$needResponse)
{
//	$args = func_get_args();
//	print_r($args);
  
  global $nodeAuthorizationURL;
  global $messageType; 
  global $addressFlag;
  global $merchantId;
  global $merchantCallbackURL;
  global $keyValue;
      
//echo 	$nodeAuthorizationURL.' | '.$messageType.' | '.$addressFlag.' | '.$merchantId;
  #进行加密串处理，一定按照下列顺序进行
  #取得加密前的字符串
  #加入消息类型
  $sbOld="";
  $sbOld = $sbOld.$messageType;
  #加入商家ID
  $sbOld = $sbOld.$merchantId;
  #加入定单号ID
  $sbOld = $sbOld.$orderId;     
  #加入金额
  $sbOld = $sbOld.$amount;
  #加入货币单位
  $sbOld = $sbOld.$cur;
  #加入产品ID
  $sbOld = $sbOld.$productId;
  #加入产品分类
  $sbOld = $sbOld.$productCat;
  #加入产品描述
  $sbOld = $sbOld.$productDesc;
  #加入商家回报URL
  $sbOld = $sbOld.$merchantCallbackURL;
  #加入送货地址标识
  $sbOld = $sbOld.$addressFlag;
  #加入商家扩展信息
  $sbOld = $sbOld.$sMctProperties;
  #加入银行ID选择
  $sbOld = $sbOld.$frpId;
  #加入是否需要回调机制
  $sbOld = $sbOld.$needResponse;
	 	
  
  return hmac($keyValue,$sbOld);
  
  
} 

function getCallbackHmacString($sCmd,$sErrorCode,$sTrxId,$orderId,$amount,$cur,$productId,$userId,$MP,$bType){
//    echo "<hr>";
//	$args = func_get_args();
//	print_r($args);
  
    global $keyValue;
    global $merchantId;
//  echo "密钥和商家ID: ".$keyValue.' | '.$merchantId;
	#取得加密前的字符串
	$sbOld = "";
	#加入商家ID
	$sbOld = $sbOld.$merchantId;
	#加入消息类型
	$sbOld = $sbOld.$sCmd;
	#加入业务返回码
	$sbOld = $sbOld.$sErrorCode;
	#加入交易ID
	$sbOld = $sbOld.$sTrxId;
	#加入交易金额
	$sbOld = $sbOld.$amount;
	#加入货币单位
	$sbOld = $sbOld.$cur;
	#加入产品Id
	$sbOld = $sbOld.$productId;
	#加入订单ID
	$sbOld = $sbOld.$orderId;
	#加入用户ID
	$sbOld = $sbOld.$userId;
	#加入商家扩展信息
	$sbOld = $sbOld.$MP;
	#加入交易结果返回类型
	$sbOld = $sbOld.$bType;

    return hmac($keyValue,$sbOld);
	
}

#取得返回串中的所有参数
function getCallBackValue(&$sCmd,&$sErrorCode,&$sTrxId,&$amount,&$cur,&$productId,&$orderId,&$userId,&$MP,&$bType,&$svrHmac){  
	$sCmd = $_REQUEST['r0_Cmd'];
	$sErrorCode = $_REQUEST['r1_Code'];
	$sTrxId = $_REQUEST['r2_TrxId'];
	$amount = $_REQUEST['r3_Amt'];
	$cur = $_REQUEST['r4_Cur'];
	$productId = $_REQUEST['r5_Pid'];
	$orderId = $_REQUEST['r6_Order'];
	$userId = $_REQUEST['r7_Uid'];
	$MP = $_REQUEST['r8_MP'];
	$bType = $_REQUEST['r9_BType']; 
	$svrHmac = $_REQUEST['hmac'];
	return NULL;
}

function CheckHmac($sCmd,$sErrorCode,$sTrxId,$orderId,$amount,$cur,$productId,$userId,$MP,$bType,$svrHmac){
//    $args = func_get_args();
//	print_r($args);
//echo "<br>";
//echo $svrHmac."<br>";
//echo getCallbackHmacString($sCmd,$sErrorCode,$sTrxId,$orderId,$amount,$cur,$productId,$userId,$MP,$bType);
	if($svrHmac==getCallbackHmacString($sCmd,$sErrorCode,$sTrxId,$orderId,$amount,$cur,$productId,$userId,$MP,$bType))
		return true;
	else
		return false;
}

?> 