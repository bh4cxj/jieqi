<?php
//sndagame支付相关参数(盛大游戏卡)

$jieqiPayset['sndagame']['payid']='123456';  //商户编号

$jieqiPayset['sndagame']['paykey']='******';  //密钥值

$jieqiPayset['sndagame']['payurl']='http://pay.15173.com/Pay_gate.aspx';  //提交到对方的网址

$jieqiPayset['sndagame']['payreturn']='http://www6.2100book.com/modules/pay/15173return.php';  //接收返回的地址 (www.domain.com 是指你的网址)

$jieqiPayset['sndagame']['paynotify']='http://www6.2100book.com/modules/pay/15173notify.php';  //接收返回的地址 (www.domain.com 是指你的网址)
/*
5元     380点世纪币
10元    780点世纪币
30元    2420点世纪币
45元    3660点世纪币
50元    4070点世纪币
100元   8150点世纪币
1000元  81800点世纪币*/
//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['sndagame']['paylimit']=array('380'=>'5', '780'=>'10', '2420'=>'30','3660'=>'45', '4070'=>'50', '8150'=>'100', '81800'=>'1000');
//支付增加积分
$jieqiPayset['sndagame']['payscore']=array('380'=>'50', '780'=>'100', '2420'=>'300','3660'=>'450', '4070'=>'500', '8150'=>'1000', '81800'=>'10000');

$jieqiPayset['sndagame']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['sndagame']['paytype']='c';  //a 神州行卡支付 b 俊网一卡通支付 c 盛大卡支付 d 征途游戏卡支付 i 声讯电话支付 n 搜狐一卡通支付 o 5173充值卡支付 q 久游一卡通支付 r 腾讯Q币卡支付 f 银行卡在线支付 g 财富通帐户支付

$jieqiPayset['sndagame']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['sndagame']['currency']='1';  //货币类型 1为人民币 3为神州行卡

$jieqiPayset['sndagame']['pemail']='';  //订货人email

$jieqiPayset['sndagame']['merchant_param']='';  //商户需要传递的信息，如收货人地址

$jieqiPayset['sndagame']['isSupportDES']='2';  //是否安全校验,1不校验 2为必校验,推荐

$jieqiPayset['sndagame']['pid_sndagameaccount']='';  //代理/合作伙伴商户编号

$jieqiPayset['sndagame']['addvars']=array();  //附加参数
?>