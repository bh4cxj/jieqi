<?php
//ipay支付相关参数

$jieqiPayset['ipay']['payid']='12345678';  //商户编号

$jieqiPayset['ipay']['paykey']='xxxxxxxx';  //默认的私钥值，更改私钥后要修改这里

$jieqiPayset['ipay']['payurl']='http://www.ipay.cn/4.0/bank.shtml';  //提交到对方的网址

$jieqiPayset['ipay']['payreturn']='http://www.domain.com/modules/pay/ipayreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['ipay']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');

$jieqiPayset['ipay']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['ipay']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['ipay']['v_email']='';  //Email

$jieqiPayset['ipay']['v_mobile']='13888888888';  //手机号码

$jieqiPayset['ipay']['addvars']=array();  //附加参数
?>