<?php
//快钱99bill支付相关参数

$jieqiPayset['qq']['payid']='123456';  //商户编号

$jieqiPayset['qq']['paykey']='******';  //密钥值

$jieqiPayset['qq']['payurl']='http://cp.tenpay.com/cgi-bin/cp_pull';  //提交到对方的网址

$jieqiPayset['qq']['payreturn']='http://www.domain.com/modules/pay/qqreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['qq']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');

$jieqiPayset['qq']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['qq']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['qq']['service_id']='YW50CUIW301';  //业务代码

$jieqiPayset['qq']['user_id']='';  //业务承载号码（可选参数）

$jieqiPayset['qq']['user_type']='1';  //业务承载号码的类型，1－qq号码2－财付通帐号100－cp自己的号码

$jieqiPayset['qq']['pay_type']='1';  //支付号码类型，用户在CP的网站上选择1－q币支付2－财付通支付

$jieqiPayset['qq']['source']='1';  //请求来源1－cp网站2－财付空间portal

$jieqiPayset['qq']['from']='';  //外部请求来源（用于统计推广网站对财付空间的推广作用）

$jieqiPayset['qq']['cmd_line']=$GLOBALS['jieqiModules']['pay']['path'].'/qqrsa/phprsa';  //生成验证码程序路径

$jieqiPayset['qq']['addvars']=array();  //附加参数
?>