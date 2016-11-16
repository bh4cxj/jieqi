<?php
//快钱99bill支付相关参数

$jieqiPayset['99cardv2']['payid']='123456';  //商户编号

$jieqiPayset['99cardv2']['paykey']='******';  //密钥值

$jieqiPayset['99cardv2']['payurl']='https://www.99bill.com/szxgateway/recvMerchantInfoAction.htm';  //提交到对方的网址

$jieqiPayset['99cardv2']['payreturn']='http://www.domain.com/modules/pay/99cardv2return.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['99cardv2']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');

$jieqiPayset['99cardv2']['inputCharset'] = '2'; //字符集,可为空。1代表UTF-8; 2代表GBK; 3代表gb2312 默认值为1

$jieqiPayset['99cardv2']['version']='v2.0';  //本代码版本号固定为v2.0

$jieqiPayset['99cardv2']['language']='1';  //1代表中文；2代表英文 默认值为1

$jieqiPayset['99cardv2']['signType']='1'; //1代表MD5签名 当前版本固定为1

$jieqiPayset['99cardv2']['payType']='00';  //只能选择00 //00：代表支持神州行卡和快钱帐户支付

$jieqiPayset['99cardv2']['fullAmountFlag']='0';  //0代表卡面额小于订单金额时返回支付结果为失败；1代表卡面额小于订单金额是返回支付结果为成功，同时订单金额和实际支付金额都为神州行卡的面额.如果商户定制神州行卡密直连时，本参数固定值为1

$jieqiPayset['99cardv2']['ext1']='';  //扩展字段1

$jieqiPayset['99cardv2']['ext2']='';  //扩展字段2

$jieqiPayset['99cardv2']['addvars']=array();  //附加参数
?>