<?php
//vnetone.com声讯支付相关参数 网盈一号通

$jieqiPayset['vnetone']['payid']='123456';  //商户编号

$jieqiPayset['vnetone']['paykey']='******';  //默认的私钥值，更改私钥后要修改这里

$jieqiPayset['vnetone']['payurl']='http://s2.vnetone.com/Default.aspx';  //提交到对方的网址

$jieqiPayset['vnetone']['payreturn']='http://www.yingxj.com/jie/jieqicms/modules/pay/vnetonereturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['vnetone']['paylimit']=array('200'=>'2', '500'=>'5', '1000'=>'10', '1500'=>'15', '2000'=>'20');
//支付增加积分
//$jieqiPayset['vnetone']['payscore']=array('200'=>'20', '500'=>'50', '1000'=>'100', '1500'=>'150', '2000'=>'200');

$jieqiPayset['vnetone']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['vnetone']['paysilver']='0';  //0 表示冲值成金币 1表示银币


$jieqiPayset['vnetone']['errreturn']='http://www.yingxj.com/jie/jieqicms/modules/pay/vnetonereturn.php';  //接收错误返回的地址 (www.domain.com 是指你的网址)

$jieqiPayset['vnetone']['version']='vpay1001';  //盈华讯方接口版本 现在版本是vpay1001

$jieqiPayset['vnetone']['agentself']='';  //用户自己定义16个字符以内 可以为空


$jieqiPayset['vnetone']['addvars']=array();  //附加参数
?>