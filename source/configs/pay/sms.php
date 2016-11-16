<?php
//sms支付相关参数

$jieqiPayset['sms']['payid']='12345678';  //商户编号

$jieqiPayset['sms']['paykey']='xxxxxxxx';  //默认的私钥值，更改私钥后要修改这里

$jieqiPayset['sms']['payurl']='http://218.206.80.238/qyhzapi/sendsms.jsp';  //提交到对方的网址

$jieqiPayset['sms']['payreturn']='http://www.domain.com/modules/pay/smsreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['sms']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');

$jieqiPayset['sms']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['sms']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['sms']['mydest']='0';  //短信特服号

$jieqiPayset['sms']['emoney']='100';  //扣取的钱（分）

$jieqiPayset['sms']['egold']='100';  //默认虚拟货币

$jieqiPayset['sms']['ptype']='1';  //接收方手机类型。1=移动手机，2=联通手机

$jieqiPayset['sms']['sid']='1315';  //1315 小说点播阅读 1000 免费帮助

$jieqiPayset['sms']['mtype']='1';  //0=免费信息，1=按条收费短信，2=正常包月短信，3=包月话单

$jieqiPayset['sms']['fmt']='1';  //信息编码。1=GB, 2=ASCII, 3=Binary, 4=UCS2. 默认值是GB. (Binary, UCS2暂时不支持)

$jieqiPayset['sms']['uflag']='0';  //0=普通信息（默认），1=注册信息，2=注销信息。对于定制业务，当用户注册使用该服务时，uflag=1; 当用户取消使用该服务时，uflag=2;其它uflag=0。点播业务uflag=0.

$jieqiPayset['sms']['startmsg']='NA';  //发送消息起始标记

$jieqiPayset['sms']['daymsg']='10';  //每天最多发送消息数

$jieqiPayset['sms']['message']='ID：<{$userid}>，用户名：<{$username}>，<{$egold}> 点虚拟币已经入帐，请登陆并查收！(资费1元/条，交易序号：<{$serialno}>)';  //发送消息内容

$jieqiPayset['sms']['addvars']=array();  //附加参数
?>