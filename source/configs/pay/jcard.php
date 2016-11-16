<?php
//jcard支付相关参数

$jieqiPayset['jcard']['payid']='123456';  //商户编号

$jieqiPayset['jcard']['paykey']='******';  //密钥值

$jieqiPayset['jcard']['payurl']='http://pay.15173.com/Pay_gate.aspx';  //提交到对方的网址

$jieqiPayset['jcard']['payreturn']='http://www6.2100book.com/modules/pay/15173return.php';  //接收返回的地址 (www.domain.com 是指你的网址)

$jieqiPayset['jcard']['paynotify']='http://www6.2100book.com/modules/pay/15173notify.php';  //接收返回的地址 (www.domain.com 是指你的网址)
/*
5元     380点世纪币
6元     420点世纪币
10元    790点世纪币
15元    1200点世纪币
30元    2450点世纪币
50元    4100点世纪币
100元   8250点世纪币
*/
//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['jcard']['paylimit']=array('380'=>'5', '420'=>'6', '790'=>'10','1200'=>'15', '2450'=>'30', '4100'=>'50', '8250'=>'100');
//支付增加积分
$jieqiPayset['jcard']['payscore']=array('380'=>'50', '420'=>'60', '790'=>'100','1200'=>'150', '2450'=>'300', '4100'=>'500', '8250'=>'1000');

$jieqiPayset['jcard']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['jcard']['paytype']='b';  //a 神州行卡支付 b 俊网一卡通支付 c 盛大卡支付 d 征途游戏卡支付 i 声讯电话支付 n 搜狐一卡通支付 o 5173充值卡支付 q 久游一卡通支付 r 腾讯Q币卡支付 f 银行卡在线支付 g 财富通帐户支付

$jieqiPayset['jcard']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['jcard']['currency']='1';  //货币类型 1为人民币 3为神州行卡

$jieqiPayset['jcard']['pemail']='';  //订货人email

$jieqiPayset['jcard']['merchant_param']='';  //商户需要传递的信息，如收货人地址

$jieqiPayset['jcard']['isSupportDES']='2';  //是否安全校验,1不校验 2为必校验,推荐

$jieqiPayset['jcard']['pid_jcardaccount']='';  //代理/合作伙伴商户编号

$jieqiPayset['jcard']['addvars']=array();  //附加参数
?>