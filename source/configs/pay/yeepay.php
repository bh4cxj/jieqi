<?php
//易宝yeepay支付相关参数

$jieqiPayset['yeepay']['payid']='123456';  //商户编号

$jieqiPayset['yeepay']['paykey']='******';  //密钥值

$jieqiPayset['yeepay']['payurl']='https://www.yeepay.com/app-merchant-proxy/node';  //提交到对方的网址

$jieqiPayset['yeepay']['payreturn']='http://www.domain.com/modules/pay/yeepayreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['yeepay']['paylimit']=array('100'=>'1','1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');
//支付增加积分
//$jieqiPayset['yeepay']['payscore']=array('1000'=>'100', '2000'=>'200', '3000'=>'300', '5000'=>'500', '10000'=>'1000');

$jieqiPayset['yeepay']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['yeepay']['paysilver']='0';  //0 表示冲值成金币 1表示银币



$jieqiPayset['yeepay']['addressFlag']='0';  //需要填写送货信息 0：不需要  1:需要

$jieqiPayset['yeepay']['messageType']='Buy';  //业务类型

$jieqiPayset['yeepay']['cur']='CNY';  //货币单位

$jieqiPayset['yeepay']['productId']='';  //商品名

$jieqiPayset['yeepay']['productDesc']='';  //商品描述

$jieqiPayset['yeepay']['productCat']='';  //商品种类

$jieqiPayset['yeepay']['sMctProperties']='';  //附加参数

$jieqiPayset['yeepay']['frpId']='';  //附加参数

$jieqiPayset['yeepay']['needResponse']='0';  //是否需要应答机制，默认或"0"为不需要,"1"为需要

$jieqiPayset['yeepay']['payfrom']=array(
'1000000-NET'   => '易宝会员支付',
'SZX'           => '神州行支付卡',
'ABC-NET'       => '中国农业银行',
'BCCB-NET'      => '北京银行',
'BOCO-NET'      => '交通银行',
'CCB-NET'       => '建设银行',
'CIB-NET'       => '兴业银行',
'CMBCHINA-NET'  => '招商银行',
'CMBCHINA-PHONE'=> '招行电话银行',
'CMBC-NET'      => '中国民生银行总行',
'CMBC-PHONE'    => '民生电话银行',
'ICBC-NET'      => '中国工商银行',
'JUNNET-NET'    => '骏网一卡通(需要特别开通才可使用)',
'LIANHUAOKCARD-NET'=>'联华OK 卡(需要特别开通才可使用)',
'POST-NET'      => '中国邮政(需要特别开通才可使用)',
'SDB-NET'       => '深圳发展银行',
'SHTEL-NET'     => '电信聚信卡(需要特别开通才可使用)',
'SPDB-NET'      => '上海浦东发展银行',
'TONGCARD-NET'  => '积分支付(通卡)(需要特别开通才可使用)'
);

$jieqiPayset['yeepay']['paytype']=array(
'1000000-NET'   => 'yeepay',
'SZX'           => 'yeepay-szx',
'ABC-NET'       => 'yeepay-bank',
'BCCB-NET'      => 'yeepay-bank',
'BOCO-NET'      => 'yeepay-bank',
'CCB-NET'       => 'yeepay-bank',
'CIB-NET'       => 'yeepay-bank',
'CMBCHINA-NET'  => 'yeepay-bank',
'CMBCHINA-PHONE'=> 'yeepay-bank',
'CMBC-NET'      => 'yeepay-bank',
'CMBC-PHONE'    => 'yeepay-bank',
'ICBC-NET'      => 'yeepay-bank',
'JUNNET-NET'    => 'yeepay-other',
'LIANHUAOKCARD-NET'=>'yeepay-other',
'POST-NET'      => 'yeepay-other',
'SDB-NET'       => 'yeepay-bank',
'SHTEL-NET'     => 'yeepay-other',
'SPDB-NET'      => 'yeepay-bank',
'TONGCARD-NET'  => 'yeepay-other'
);

$jieqiPayset['yeepay']['addvars']=array();  //附加参数

/*
易宝默认支付 /modules/pay/buyegold.php?t=yeepaypay
对应模板 /modules/pay/templates/yeepaypay.html

易宝神州行 /modules/pay/buyegold.php?t=yeeszxpay
对应模板 /modules/pay/templates/yeeszxpay.html

paytype.php 是总的支付种类配置，增加易宝支付的话，在原有配置基础上加上以下内容

$jieqiPaytype['yeepay'] = array('name' => '易宝会员支付', 'shortname' => '易宝会员', 'description'=>'', 'url' => 'http://www.yeepay.com');

$jieqiPaytype['yeepay-szx'] = array('name' => '易宝神州行卡支付', 'shortname' => '易宝神州行', 'description'=>'', 'url' => 'http://www.yeepay.com');

$jieqiPaytype['yeepay-bank'] = array('name' => '易宝银行卡支付', 'shortname' => '易宝银行卡', 'description'=>'', 'url' => 'http://www.yeepay.com');

$jieqiPaytype['yeepay-other'] = array('name' => '易宝其他支付', 'shortname' => '易宝其他', 'description'=>'', 'url' => 'http://www.yeepay.com');
*/

?>