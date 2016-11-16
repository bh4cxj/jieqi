<?php
//qbcard支付相关参数

$jieqiPayset['qbcard']['payid']='123456';  //商户编号

$jieqiPayset['qbcard']['paykey']='******';  //密钥值

$jieqiPayset['qbcard']['payurl']='http://pay.15173.com/Pay_gate.aspx';  //提交到对方的网址

$jieqiPayset['qbcard']['payreturn']='http://www6.2100book.com/modules/pay/15173return.php';  //接收返回的地址 (www.domain.com 是指你的网址)

$jieqiPayset['qbcard']['paynotify']='http://www6.2100book.com/modules/pay/15173notify.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['qbcard']['paylimit']=array('600'=>'10', '930'=>'15', '1900'=>'30', '3850'=>'60');
//支付增加积分
$jieqiPayset['qbcard']['payscore']=array('600'=>'100', '930'=>'150', '1900'=>'300', '3850'=>'600');

$jieqiPayset['qbcard']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['qbcard']['paytype']='r';  //a 神州行卡支付 b 俊网一卡通支付 c 盛大卡支付 d 征途游戏卡支付 i 声讯电话支付 n 搜狐一卡通支付 o 5173充值卡支付 q 久游一卡通支付 r 腾讯Q币卡支付 f 银行卡在线支付 g 财富通帐户支付

$jieqiPayset['qbcard']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['qbcard']['currency']='1';  //货币类型 1为人民币 3为神州行卡

$jieqiPayset['qbcard']['pemail']='';  //订货人email

$jieqiPayset['qbcard']['merchant_param']='';  //商户需要传递的信息，如收货人地址

$jieqiPayset['qbcard']['isSupportDES']='2';  //是否安全校验,1不校验 2为必校验,推荐

$jieqiPayset['qbcard']['pid_qbcardaccount']='';  //代理/合作伙伴商户编号

$jieqiPayset['qbcard']['addvars']=array();  //附加参数
?>