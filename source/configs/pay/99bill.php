<?php
//快钱99bill支付相关参数

$jieqiPayset['99bill']['payid']='123456';  //商户编号

$jieqiPayset['99bill']['paykey']='******';  //密钥值

$jieqiPayset['99bill']['payurl']='https://www.99bill.com/webapp/receiveMerchantInfoAction.do';  //提交到对方的网址

$jieqiPayset['99bill']['payreturn']='http://www.domain.com/modules/pay/99billreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['99bill']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');
//支付增加积分
//$jieqiPayset['99bill']['payscore']=array('1000'=>'100', '2000'=>'200', '3000'=>'300', '5000'=>'500', '10000'=>'1000');

$jieqiPayset['99bill']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['99bill']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['99bill']['currency']='1';  //货币类型 1为人民币 3为神州行卡

$jieqiPayset['99bill']['pemail']='';  //订货人email

$jieqiPayset['99bill']['merchant_param']='';  //商户需要传递的信息，如收货人地址

$jieqiPayset['99bill']['isSupportDES']='2';  //是否安全校验,1不校验 2为必校验,推荐

$jieqiPayset['99bill']['pid_99billaccount']='';  //代理/合作伙伴商户编号

$jieqiPayset['99bill']['addvars']=array();  //附加参数
?>