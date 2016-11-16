<?php
//123bill支付相关参数

$jieqiPayset['123bill']['payid']='123456';  //商户编号

$jieqiPayset['123bill']['paykey']='******';   //密钥值


$jieqiPayset['123bill']['payurl']='http://test.118pay.cn/PhonePay.aspx';//提交到对方的网址

$jieqiPayset['123bill']['payreturn']='http://localhost/jie/jieqicms/modules/pay/123billreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)


//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['123bill']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');
//支付增加积分
//$jieqiPayset['123bill']['payscore']=array('1000'=>'100', '2000'=>'200', '3000'=>'300', '5000'=>'500', '10000'=>'1000');

$jieqiPayset['123bill']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['123bill']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['123bill']['currency']='1';  //货币类型 1为人民币 3为神州行卡


$jieqiPayset['123bill']['op'] = '1';  //请求类型（1-支付）

$jieqiPayset['123bill']['objid'] = '88888';  //交易商品编码



$jieqiPayset['123bill']['prikeyfile'] = '123bill_prikey.xml';  //私有密钥文件名，必须放在网站的 /configs/pay/ 目录下

$jieqiPayset['123bill']['pubkeyfile'] = '123bill_pubkey.xml';  //共有有密钥文件名，必须放在网站的 /configs/pay/ 目录下


$jieqiPayset['123bill']['addvars']=array();  //附加参数
?>