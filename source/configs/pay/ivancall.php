<?php
//sms支付相关参数

$jieqiPayset['ivancall']['payid']='000000';  //商户编号

$jieqiPayset['ivancall']['paykey']='xxxxxxxx';  //默认的私钥值，更改私钥后要修改这里

$jieqiPayset['ivancall']['payurl']='';  //提交到对方的网址

$jieqiPayset['ivancall']['payreturn']='http://www.domain.com/modules/pay/ivancallreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)


$jieqiPayset['ivancall']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['ivancall']['paysilver']='0';  //0 表示冲值成金币 1表示银币


//语音支付专用参数

$jieqiPayset['ivancall']['egoldrate']=1; //现金和虚拟币兑换比例，现金单位是分，比如这个值是1表示一分钱换一点虚拟币，如果是 0.85，则表示100分钱换85点虚拟币

//$jieqiPayset['ivancall']['scorerate']=0.1; //购买虚拟币和增加积分的比例，0.1 表示 没购买10点虚拟币增加1点积分

//手机短信支付需要屏蔽的手机号，下面是一个php数组
//如 $jieqiPayset['ivancall']['denyphone']=array('13600000000', '13700000000', '13800000000');
$jieqiPayset['ivancall']['addvars']=array();  //附加参数


?>