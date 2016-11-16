<?php
//奥创手机支付相关参数

$jieqiPayset['mocoo']['payid']='000000';  //商户编号

$jieqiPayset['mocoo']['paykey']='xxxxxxxx';  //默认的私钥值，更改私钥后要修改这里

$jieqiPayset['mocoo']['payurl']='';  //提交到对方的网址

$jieqiPayset['mocoo']['payreturn']='http://www.domain.com/modules/pay/mocooreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['mocoo']['paylimit']=array('100'=>'1');

$jieqiPayset['mocoo']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['mocoo']['paysilver']='0';  //0 表示冲值成金币 1表示银币


$jieqiPayset['mocoo']['emoney']='100';  //扣取的钱（分）

$jieqiPayset['mocoo']['egold']='100';  //默认虚拟货币

$jieqiPayset['mocoo']['addvars']=array();  //附加参数
?>