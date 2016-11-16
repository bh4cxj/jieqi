<?php
//网银支付相关参数

$jieqiPayset['chinabank']['payid']='12345678';  //商户编号

$jieqiPayset['chinabank']['paykey']='xxxxxxxx';  //密钥值

$jieqiPayset['chinabank']['foreignpayid']='12345678';  //外卡商户编号

$jieqiPayset['chinabank']['foreignpaykey']='xxxxxxxx';  //外卡密钥值

$jieqiPayset['chinabank']['payurl']='https://pay3.chinabank.com.cn/PayGate';  //提交到对方的网址

$jieqiPayset['chinabank']['payreturn']='http://www.domain.com/modules/pay/chinabankreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

$jieqiPayset['chinabank']['paycheck']='http://www.domain.com/modules/pay/chinabankcheck.php';  //后台返回信息校验地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['chinabank']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');

$jieqiPayset['chinabank']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['chinabank']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['chinabank']['style']='0';  //网关模式0(普通列表)，1(银行列表中带外卡)

$jieqiPayset['chinabank']['remark1']='0';  //备注字段1

$jieqiPayset['chinabank']['remark2']='0';  //备注字段2

$jieqiPayset['chinabank']['addvars']=array();  //附加参数
?>