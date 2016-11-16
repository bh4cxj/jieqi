<?php
//sms支付相关参数

$jieqiPayset['tomorrow']['payid']='000000';  //商户编号

$jieqiPayset['tomorrow']['paykey']='xxxxxxxx';  //默认的私钥值，更改私钥后要修改这里

$jieqiPayset['tomorrow']['payurl']='http://218.206.80.202:8181/web/httpMt';  //提交到对方的网址
$jieqiPayset['tomorrow']['payurl']='http://220.194.61.249:8181/web/httpMt';  //提交到对方的网址


$jieqiPayset['tomorrow']['payreturn']='http://www.domain.com/modules/pay/tomorrowreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
//不用这个参数
//$jieqiPayset['tomorrow']['paylimit']=array('100'=>'1');  

$jieqiPayset['tomorrow']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['tomorrow']['paysilver']='0';  //0 表示冲值成金币 1表示银币

//定制参数
$jieqiPayset['tomorrow']['TOUSER']='';  //发给SP的用户名【对方需要校验】
$jieqiPayset['tomorrow']['TOPASS']='';  //发给SP的密码【对方需要校验】
$jieqiPayset['tomorrow']['MOUSEID']='';  //合作伙伴ID
$jieqiPayset['tomorrow']['MOUSEPACKAGEID']='1';  //分帐成员组ID亦称老鼠包ID
$jieqiPayset['tomorrow']['ISIMBALANCE']='0';  //是否均衡
$jieqiPayset['tomorrow']['ATTIME']='';  //定时下发时间
$jieqiPayset['tomorrow']['MTTYPE']='2';  //MT类型
$jieqiPayset['tomorrow']['MSGFORMAT']='15';  //消息编码格式0：ASCII3：写卡操作 4：二进制 8：UCS2 15：GB码汉字
$jieqiPayset['tomorrow']['REMARK']='';  //备注

//不同短信代码下面两个参数不同
//serviceid   - 服务ID【必须填写】
//smtypeid    - MT业务信息类型【必须填写】
//egold       - 充值的虚拟币
//emoney      - 实际扣的金额(单位：分)
//startmsg    - 手机发过来的信息起始标记
//message     - 网站发给手机的提示信息(<{$serialno}> 交易序号，<{$randpass}> 充值密码 <{$egold}> 虚拟币)

$jieqiPayset['tomorrow']['paytype'][]=array('serviceid'=>'', 'smtypeid'=>'', 'egold'=>'100', 'emoney'=>'100', 'startmsg'=>'', 'message'=>'交易序号：<{$serialno}>，冲值密码：<{$randpass}>，资费：1元。请登陆网站完成冲值！');


/*
//下面参数目前不用
//如果设置了，以设置为准，否则原样返回
$jieqiPayset['tomorrow']['CLASSID']='';  //本地接收的URL
$jieqiPayset['tomorrow']['CITYID']='';  //城市ID
$jieqiPayset['tomorrow']['PROVINCEID']='';  //省份ID

//发过来就有，必须原样返回
$jieqiPayset['tomorrow']['GATEWAYID']='';  //网关ID（原样接收返回）
$jieqiPayset['tomorrow']['SPNUMBER']='';  //特服号
$jieqiPayset['tomorrow']['LINKID']='';  //点播业务使用的LinkID，非点播类业务的MT 流程不使用该字段。

*/

//其他参数
$jieqiPayset['tomorrow']['passlen']='8';  //随机密码长度

$jieqiPayset['tomorrow']['passtype']='3';  //随机密码类型 1-数字，2-小写字母, 3-数字和小写字母

$jieqiPayset['tomorrow']['addvars']=array();  //附加参数

//手机短信支付需要屏蔽的手机号，下面是一个php数组
//如 $jieqiPayset['tomorrow']['denyphone']=array('13600000000', '13700000000', '13800000000');
$jieqiPayset['tomorrow']['denyphone']=array();
?>