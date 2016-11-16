<?php
//15173支付相关参数

$jieqiPayset['15173']['payid']='123456';  //商户编号

$jieqiPayset['15173']['paykey']='******';  //密钥值

$jieqiPayset['15173']['payurl']='http://pay.15173.com/Pay_gate.aspx';  //提交到对方的网址

$jieqiPayset['15173']['payreturn']='http://localhost/jieqi150/modules/pay/15173return.php';  //接收返回的地址 (www.domain.com 是指你的网址)

$jieqiPayset['15173']['paynotify']='http://localhost/jieqi150/modules/pay/15173notify.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['15173']['paylimit']=array('100'=>'1', '1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');
//支付增加积分
//$jieqiPayset['15173']['payscore']=array('1000'=>'100', '2000'=>'200', '3000'=>'300', '5000'=>'500', '10000'=>'1000');

$jieqiPayset['15173']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['15173']['paytype']='d';  //a 神州行卡支付 b 俊网一卡通支付 c 盛大卡支付 d 征途游戏卡支付 i 声讯电话支付 n 搜狐一卡通支付 o 5173充值卡支付 q 久游一卡通支付 r 腾讯Q币卡支付 f 银行卡在线支付 g 财富通帐户支付

$jieqiPayset['15173']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['15173']['currency']='1';  //货币类型 1为人民币 3为神州行卡

$jieqiPayset['15173']['pemail']='';  //订货人email

$jieqiPayset['15173']['merchant_param']='';  //商户需要传递的信息，如收货人地址

$jieqiPayset['15173']['isSupportDES']='2';  //是否安全校验,1不校验 2为必校验,推荐

$jieqiPayset['15173']['pid_15173account']='';  //代理/合作伙伴商户编号

$jieqiPayset['15173']['addvars']=array();  //附加参数
?>