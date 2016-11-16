<?php
//sndacard支付相关参数

$jieqiPayset['sndacard']['payid']='123456';  //商户编号

$jieqiPayset['sndacard']['paykey']='******';  //密钥值

$jieqiPayset['sndacard']['payurl']='http://61.172.247.108/PayNet/CardPay.aspx';  //提交到对方的网址
//http://61.172.247.108/PayNet/CardPay.aspx  测试
//http://pay.16288.com/CardPay.aspx 正式

$jieqiPayset['sndacard']['payreturn']='http://www.domain.com/modules/pay/sndacardreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['sndacard']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');

$jieqiPayset['sndacard']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['sndacard']['paysilver']='0';  //0 表示冲值成金币 1表示银币


$jieqiPayset['sndacard']['bizcode']='03';  //03：游戏点卡 04：银行支付 01：盛大Esales支付

$jieqiPayset['sndacard']['callbacktype']='01';  //返回地址样式 01：URL  02：Web Service  03：Socket

$jieqiPayset['sndacard']['ex1']='';  //备注1

$jieqiPayset['sndacard']['ex2']='';  //备注2

$jieqiPayset['sndacard']['signurl']='http://localhost:8080/shandasign.jsp';  //签名url

$jieqiPayset['sndacard']['verifyurl']='http://localhost:8080/shandaverify.jsp';  //验证url

$jieqiPayset['sndacard']['checkstr']='cwjsignwithshanda';  //签名校验

$jieqiPayset['sndacard']['showurl']='http://www.domain.com/modules/pay/sndacardshow.php';  //显示交易是否成功的是指

$jieqiPayset['sndacard']['addvars']=array();  //附加参数
?>