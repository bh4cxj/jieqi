<?php
//冲值卡paycard支付相关参数

$jieqiPayset['paycard']['payid']='';  //商户编号

$jieqiPayset['paycard']['paykey']='';  //密钥值

$jieqiPayset['paycard']['payurl']='';  //提交到对方的网址

$jieqiPayset['paycard']['payreturn']='';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['paycard']['paylimit']=array('70'=>'1', '150'=>'2');

$jieqiPayset['paycard']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['paycard']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['paycard']['addvars']=array();  //附加参数
?>