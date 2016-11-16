<?php
//ztgame支付相关参数

$jieqiPayset['ztgame']['payid']='123456';  //商户编号

$jieqiPayset['ztgame']['paykey']='******';  //密钥值

$jieqiPayset['ztgame']['payurl']='http://pay.15173.com/Pay_gate.aspx';  //提交到对方的网址

$jieqiPayset['ztgame']['payreturn']='http://www6.2100book.com/modules/pay/15173return.php';  //接收返回的地址 (www.domain.com 是指你的网址)

$jieqiPayset['ztgame']['paynotify']='http://www6.2100book.com/modules/pay/15173notify.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['ztgame']['paylimit']=array('280'=>'5', '680'=>'10', '2150'=>'30','4400'=>'60', '1400'=>'20', '3600'=>'50', '7300'=>'100', '22100'=>'300');
//支付增加积分
$jieqiPayset['ztgame']['payscore']=array('280'=>'50', '680'=>'100', '2150'=>'300','4400'=>'600', '1400'=>'200', '3600'=>'500', '7300'=>'1000', '22100'=>'3000');

$jieqiPayset['ztgame']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['ztgame']['paytype']='d';  //a 神州行卡支付 b 俊网一卡通支付 c 盛大卡支付 d 征途游戏卡支付 i 声讯电话支付 n 搜狐一卡通支付 o 5173充值卡支付 q 久游一卡通支付 r 腾讯Q币卡支付 f 银行卡在线支付 g 财富通帐户支付

$jieqiPayset['ztgame']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['ztgame']['currency']='1';  //货币类型 1为人民币 3为神州行卡

$jieqiPayset['ztgame']['pemail']='';  //订货人email

$jieqiPayset['ztgame']['merchant_param']='';  //商户需要传递的信息，如收货人地址

$jieqiPayset['ztgame']['isSupportDES']='2';  //是否安全校验,1不校验 2为必校验,推荐

$jieqiPayset['ztgame']['pid_ztgameaccount']='';  //代理/合作伙伴商户编号

$jieqiPayset['ztgame']['addvars']=array();  //附加参数
?>