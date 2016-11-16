<?php
//tenpay支付相关参数

$jieqiPayset['tenpay']['payid']='123456';  //商户编号

$jieqiPayset['tenpay']['paykey']='******';  //密钥值

$jieqiPayset['tenpay']['payurl']='https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi';  //提交到对方的网址

$jieqiPayset['tenpay']['payreturn']='http://www.domain.com/modules/pay/tenpayreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['tenpay']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');
//支付增加积分
//$jieqiPayset['tenpay']['payscore']=array('1000'=>'100', '2000'=>'200', '3000'=>'300', '5000'=>'500', '10000'=>'1000');

$jieqiPayset['tenpay']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['tenpay']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['tenpay']['payresult']='http://www.domain.com/modules/pay/tenpayresult.php?payid=%s&egold=%s&buyid=%s&buyname=%s';  //显示支付结果的地址 (www.domain.com 是指你的网址)

$jieqiPayset['tenpay']['cmdno']='1';  //业务代码, 财付通支付支付接口填  1

$jieqiPayset['tenpay']['bank_type']='0';  //银行类型:财付通支付填0

$jieqiPayset['tenpay']['fee_type']='1';  //现金支付币种，目前只支持人民币，1 - RMB人民币, 2 - USD美元, 3 - HKD港币

$jieqiPayset['tenpay']['attach']='';  //商家数据包，原样返回

$jieqiPayset['tenpay']['addvars']=array();  //附加参数
?>