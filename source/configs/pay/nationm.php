<?php
//梦联支付相关参数

$jieqiPayset['nationm']['payid']='123456';  //商户编号

$jieqiPayset['nationm']['sendkey']='******';  //发送密钥值

$jieqiPayset['nationm']['receivekey']='CD57EAAB8EF5383BED825CEA48AD468723AB5A57546DDB1F51D26C82BD92DFF3767B9B919E08F9E8';  //接收密钥值

$jieqiPayset['nationm']['goodsid']='egold';  //商品编号，好像不能是中文

$jieqiPayset['nationm']['payurl']='http://www.nationm.com.cn/Test/IbankPayRequest_ssl.jsp';  //提交到对方的网址

$jieqiPayset['nationm']['payreturn']='http://www.domain.com/modules/pay/nationmreturn.php';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['nationm']['paylimit']=array('1000'=>'10', '2000'=>'20', '3000'=>'30', '5000'=>'50', '10000'=>'100');

$jieqiPayset['nationm']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['nationm']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['nationm']['countNumber']='1';  //商品数量

$jieqiPayset['nationm']['currentType']='000';  //币种 000:人民币010:美元020:港币

$jieqiPayset['nationm']['paysilver']='1';  //信息发送类型

$jieqiPayset['nationm']['addvars']=array();  //附加参数
?>