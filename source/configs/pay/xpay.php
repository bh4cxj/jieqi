<?php
//xpay支付相关参数

$jieqiPayset['xpay']['payid']='12345678';  //商户编号

$jieqiPayset['xpay']['paykey']='xxxxxxxx';  //密钥值

$jieqiPayset['xpay']['payurl']='http://pay.xpay.cn/pay.aspx';  //提交到对方的网址

$jieqiPayset['xpay']['payreturn']='http://www.domain.com/modules/pay/xpayreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['xpay']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');

$jieqiPayset['xpay']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['xpay']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['xpay']['pdt']='egold';  //商品名称

$jieqiPayset['xpay']['scard']='bank,unicom,xpay,ebilling,ibank';  //开通的支付方式，可以选择一种或多种

$jieqiPayset['xpay']['actioncode']='sell';  //交易码,用于标识交易,目前支持sell

$jieqiPayset['xpay']['ver']='2.0';  //版本号,当前系统请使用2.0

$jieqiPayset['xpay']['type']='';  //商品类型，可以为空

$jieqiPayset['xpay']['lang']='gb2312';  //语言，目前只支持gb2312

$jieqiPayset['xpay']['remark1']='';  //备注信息

$jieqiPayset['xpay']['sitename']='';  //网站名称

$jieqiPayset['xpay']['siteurl']='';  //网站域名

$jieqiPayset['xpay']['addvars']=array();  //附加参数
?>