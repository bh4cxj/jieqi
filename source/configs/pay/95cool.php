<?php
//酷币95cool支付相关参数

$jieqiPayset['95cool']['payid']='123456';  //商户编号

$jieqiPayset['95cool']['paykey']='******';  //密钥值

$jieqiPayset['95cool']['payurl']='http://pay.95cool.com/coolbi/ordergate_zy.jsp';  //提交到对方的网址

$jieqiPayset['95cool']['payreturn']='http://www.domain.com/modules/pay/95coolreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['95cool']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');

$jieqiPayset['95cool']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['95cool']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['95cool']['addvars']=array();  //附加参数
?>