<?php
//sms支付相关参数

$jieqiPayset['ivansms']['payid']='000000';  //商户编号

$jieqiPayset['ivansms']['paykey']='xxxxxxxx';  //默认的私钥值，更改私钥后要修改这里

$jieqiPayset['ivansms']['payurl']='';  //提交到对方的网址

$jieqiPayset['ivansms']['payreturn']='http://www.domain.com/modules/pay/ivansmsreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
//不用这个参数
//$jieqiPayset['ivansms']['paylimit']=array('100'=>'1');  

$jieqiPayset['ivansms']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['ivansms']['paysilver']='0';  //0 表示冲值成金币 1表示银币


//不同短信代码下面参数不同
//spnumber   - 用户手机发送的指令
//egold       - 充值的虚拟币
//emoney      - 实际扣的金额(单位：分)
//message     - 网站发给手机的提示信息(<{$serialno}> 交易序号，<{$randpass}> 充值密码 <{$egold}> 虚拟币)

$jieqiPayset['ivansms']['paytype'][]=array('spnumber'=>'', 'egold'=>'200', 'emoney'=>'200', 'message'=>'序号：<{$serialno}>，密码：<{$randpass}>，本条2元。');

//支付增加积分（多少虚拟币对应多少积分）
//$jieqiPayset['ivansms']['payscore']=array('1000'=>'100', '2000'=>'200', '3000'=>'300', '5000'=>'500', '10000'=>'1000');


//其他参数
$jieqiPayset['ivansms']['passlen']='8';  //随机密码长度

$jieqiPayset['ivansms']['passtype']='3';  //随机密码类型 1-数字，2-小写字母, 3-数字和小写字母

$jieqiPayset['ivansms']['addvars']=array();  //附加参数

//手机短信支付需要屏蔽的手机号，下面是一个php数组
//如 $jieqiPayset['ivansms']['denyphone']=array('13600000000', '13700000000', '13800000000');
$jieqiPayset['ivansms']['denyphone']=array();
?>