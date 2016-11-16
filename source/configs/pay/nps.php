<?php
//nps支付相关参数

$jieqiPayset['nps']['payid']='123456';  //商户编号

$jieqiPayset['nps']['paykey']='******';  //密钥值

$jieqiPayset['nps']['payurl']='https://payment.nps.cn/PHPReceiveMerchantAction.do';  //提交到对方的网址

$jieqiPayset['nps']['payreturn']='http://www.domain.com/modules/pay/npsreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['nps']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');

$jieqiPayset['nps']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['nps']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['nps']['MOCurrency']='1';  //1 人民币

$jieqiPayset['nps']['M_Language']='1';  //1 中文

$jieqiPayset['nps']['S_Name']='';  //默认消费者姓名

$jieqiPayset['nps']['S_Address']='';  //默认消费者住址

$jieqiPayset['nps']['_PostCode']='';  //邮编

$jieqiPayset['nps']['S_Telephone']='';  //电话

$jieqiPayset['nps']['S_Email']='';  //Email

$jieqiPayset['nps']['R_Name']='';  //收货人姓名

$jieqiPayset['nps']['R_Address']='';  //收货地址

$jieqiPayset['nps']['R_PostCode']='';  //收货邮编

$jieqiPayset['nps']['R_Telephone']='';  //收货电话

$jieqiPayset['nps']['R_Email']='';  //收货Email

$jieqiPayset['nps']['MOComment']='';  //备注

$jieqiPayset['nps']['State']='0';  //支付状态

$jieqiPayset['nps']['addvars']=array();  //附加参数
?>