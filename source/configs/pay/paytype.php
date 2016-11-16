<?php
//在线支付的类型设定

$jieqiPaytype['manual'] = array('name' => '人工操作', 'shortname' => '人工', 'description'=>'', 'url' => '', 'link'=>'buyegold.php?t=manual', 'publish' => 1);

$jieqiPaytype['bank'] = array('name' => '银行汇款', 'shortname' => '银行', 'description'=>'', 'url' => '', 'link'=>'buyegold.php?t=remitpay', 'publish' => 1);

$jieqiPaytype['post'] = array('name' => '邮局汇款', 'shortname' => '邮局', 'description'=>'', 'url' => '', 'link'=>'buyegold.php?t=remitpay', 'publish' => 1);

$jieqiPaytype['123bill'] = array('name' => '易充电话支付', 'shortname' => '易充电话', 'description'=>'', 'url' => 'http://www.118pay.cn', 'link'=>'buyegold.php?t=123billpay', 'publish' => 1);

$jieqiPaytype['15173'] = array('name' => '15173支付', 'shortname' => '15173', 'description'=>'', 'url' => 'http://www.15173.com', 'link'=>'buyegold.php?t=15173pay', 'publish' => 1);

$jieqiPaytype['qbcard'] = array('name' => 'QQ币卡支付', 'shortname' => 'Q币卡', 'description'=>'', 'url' => 'http://www.15173.com', 'link'=>'buyegold.php?t=qbcardpay', 'publish' => 1);

$jieqiPaytype['ztgame'] = array('name' => '征途游戏卡', 'shortname' => '征途卡', 'description'=>'', 'url' => 'http://www.15173.com', 'link'=>'buyegold.php?t=ztgamepay', 'publish' => 1);

$jieqiPaytype['19pay'] = array('name' => '19PAY捷迅支付', 'shortname' => '19pay', 'description'=>'', 'url' => 'http://www.19pay.com', 'link'=>'buyegold.php?t=19paypay', 'publish' => 1);

$jieqiPaytype['95cool'] = array('name' => '酷币支付', 'shortname' => '酷币', 'description'=>'', 'url' => 'http://pay.95cool.com/coolbi/jsp/index.jsp', 'link'=>'buyegold.php?t=95coolpay', 'publish' => 1);

$jieqiPaytype['99bill'] = array('name' => '快钱在线支付', 'shortname' => '快钱在线', 'description'=>'', 'url' => 'http://www.99bill.com', 'link'=>'buyegold.php?t=99billpay', 'publish' => 1);

$jieqiPaytype['99card'] = array('name' => '快钱神州行卡支付', 'shortname' => '快钱神州行', 'description'=>'', 'url' => 'https://www.99bill.com', 'link'=>'buyegold.php?t=99cardpay', 'publish' => 1);

$jieqiPaytype['99billv2'] = array('name' => '快钱在线支付v2', 'shortname' => '快钱在线v2', 'description'=>'', 'url' => 'https://www.99bill.com', 'link'=>'buyegold.php?t=99billv2pay', 'publish' => 1);

$jieqiPaytype['99card'] = array('name' => '快钱神州行卡支付v2', 'shortname' => '快钱神州行v2', 'description'=>'', 'url' => 'https://www.99bill.com', 'link'=>'buyegold.php?t=99cardv2pay', 'publish' => 1);

$jieqiPaytype['9zfee'] = array('name' => '九州网联116电话支付', 'shortname' => '116电话', 'description'=>'', 'url' => 'http://www.116.com.cn', 'link'=>'buyegold.php?t=9zfeepay', 'publish' => 1);

$jieqiPaytype['alipay'] = array('name' => '支付宝支付', 'shortname' => '支付宝', 'description'=>'', 'url' => 'http://www.alipay.com', 'link'=>'buyegold.php?t=alipaypay', 'publish' => 1);

$jieqiPaytype['chinabank'] = array('name' => '网银支付', 'shortname' => '网银', 'description'=>'', 'url' => 'http://www.chinabank.com.cn', 'link'=>'buyegold.php?t=chinabankpay', 'publish' => 1);

$jieqiPaytype['ipay'] = array('name' => 'ipay支付', 'shortname' => 'ipay', 'description'=>'', 'url' => 'http://www.ipay.cn', 'link'=>'buyegold.php?t=ipaypay', 'publish' => 1);

$jieqiPaytype['ips'] = array('name' => 'ips支付', 'shortname' => 'ips', 'description'=>'', 'url' => 'http://www.ips.com.cn', 'link'=>'buyegold.php?t=ipspay', 'publish' => 1);

$jieqiPaytype['ivancall'] = array('name' => '埃文声讯支付', 'shortname' => '埃文声讯', 'description'=>'', 'url' => 'http://www.ivansms.com', 'link'=>'ivancallpay.php', 'publish' => 1);

$jieqiPaytype['ivansms'] = array('name' => '埃文手机支付', 'shortname' => '埃文手机', 'description'=>'', 'url' => 'http://www.ivansms.com', 'link'=>'ivansmspay.php', 'publish' => 1);

$jieqiPaytype['mocoo'] = array('name' => '奥创手机支付', 'shortname' => '奥创手机', 'description'=>'', 'url' => 'http://www.mocoo.com', 'link'=>'mocoopay.php', 'publish' => 1);

$jieqiPaytype['nationm'] = array('name' => '梦联支付', 'shortname' => '梦联', 'description'=>'', 'url' => 'http://www.nationm.com.cn', 'link'=>'buyegold.php?t=nationmpay', 'publish' => 1);

$jieqiPaytype['nps'] = array('name' => 'nps支付', 'shortname' => 'nps', 'description'=>'', 'url' => 'http://www.nps.cn', 'link'=>'buyegold.php?t=npspay', 'publish' => 1);

$jieqiPaytype['paycard'] = array('name' => '点卡支付', 'shortname' => '点卡', 'description'=>'', 'url' => '', 'link'=>'paycardpay.php', 'publish' => 1);

$jieqiPaytype['qq'] = array('name' => 'Q币支付', 'shortname' => 'Q币', 'description'=>'', 'url' => 'http://www.qq.com', 'link'=>'buyegold.php?t=qqpay', 'publish' => 1);

$jieqiPaytype['sndacard'] = array('name' => '盛大卡支付', 'shortname' => '盛大卡', 'description'=>'', 'url' => 'http://www.snda.com.cn', 'link'=>'buyegold.php?t=sndacardpay', 'publish' => 1);

$jieqiPaytype['tenpay'] = array('name' => '腾讯财付通支付', 'shortname' => '财付通', 'description'=>'', 'url' => 'https://www.tenpay.com', 'link'=>'buyegold.php?t=tenpaypay', 'publish' => 1);

$jieqiPaytype['tomorrow'] = array('name' => '明天数码手机支付', 'shortname' => '明天数码手机', 'description'=>'', 'url' => '', 'link'=>'tomorrowpay.php', 'publish' => 1);

$jieqiPaytype['vnetone'] = array('name' => '网盈电话支付', 'shortname' => '网盈电话', 'description'=>'', 'url' => 'http://new.vnetone.com', 'link'=>'vnetonepay.php', 'publish' => 1);


$jieqiPaytype['xpay'] = array('name' => 'xpay支付', 'shortname' => 'xpay', 'description'=>'', 'url' => 'http://www.xpay.cn', 'link'=>'buyegold.php?t=xpaypay', 'publish' => 1);


$jieqiPaytype['yeepay'] = array('name' => '易宝会员支付', 'shortname' => '易宝会员', 'description'=>'', 'url' => 'http://www.yeepay.com', 'link'=>'buyegold.php?t=yeepaypay', 'publish' => 1);

$jieqiPaytype['yeepay-szx'] = array('name' => '易宝神州行卡支付', 'shortname' => '易宝神州行', 'description'=>'', 'url' => 'http://www.yeepay.com', 'link'=>'buyegold.php?t=yeepaypay', 'publish' => 1);

$jieqiPaytype['yeepay-bank'] = array('name' => '易宝银行卡支付', 'shortname' => '易宝银行卡', 'description'=>'', 'url' => 'http://www.yeepay.com', 'link'=>'buyegold.php?t=yeepaypay', 'publish' => 1);

$jieqiPaytype['yeepay-other'] = array('name' => '易宝其他支付', 'shortname' => '易宝其他', 'description'=>'', 'url' => 'http://www.yeepay.com', 'link'=>'buyegold.php?t=yeepaypay', 'publish' => 1);
?>