<?php
//九州网联116电话支付相关参数

$jieqiPayset['9zfee']['payid']='123456';  //商户编号

$jieqiPayset['9zfee']['paykey']='******';  //密钥值

$jieqiPayset['9zfee']['payurl']='http://9zfee.116.com.cn:8080/chnkids/ResCPSvlt';  //提交到对方的网址

$jieqiPayset['9zfee']['payreturn']='http://www.domain.com/modules/pay/9zfeereturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['9zfee']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');

$jieqiPayset['9zfee']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['9zfee']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['9zfee']['prodid']='-1';  //商品编号

$jieqiPayset['9zfee']['phone']='11697995';  //电话号码

$jieqiPayset['9zfee']['pay_id']='1';  //支付方式码  数字，值为1，总长1个字符。

$jieqiPayset['9zfee']['valid_time']='-1';  //订单有效期 数字，总长最大5位。(单位为小时)订单的有效期，过期订单作废，不能支付；如为-1, 则表示为永久订单，适用于提供永久卡号的cp（例如卡号与cp用户一一对应）,为-3,则表示为包月订单,- 4为包季订单，-5 为包年订单。

$jieqiPayset['9zfee']['addvars']=array();  //附加参数
?>