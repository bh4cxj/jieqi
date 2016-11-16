<?php
/**
 * 语言包-通用充值
 *
 * 语言包-通用充值
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: lang_pay.php 234 2008-11-28 01:53:06Z juny $
 */

$jieqiLang['pay']['pay']=1; //表示本语言包已经包含

$jieqiLang['pay']['need_login']='对不起，必须注册成为本站会员并登陆才能使用本功能！<br /><br /><a href="/login.php">用户登录</a> &nbsp;&nbsp; <a href="/register.php">注册新用户</a>';
$jieqiLang['pay']['buy_type_error']='对不起，您选择的购买类别不存在！';
$jieqiLang['pay']['add_paylog_error']='数据库处理失败，请与管理员联系！';
$jieqiLang['pay']['need_buy_type']='对不起，请先选择您要购买的类别！';
$jieqiLang['pay']['no_buy_record']='对不起，无此交易记录！';
$jieqiLang['pay']['save_paylog_failure']='冲值成功，保存交易记录失败！<br /><br />请检查您的帐号，如有问题请与管理员联系。';
$jieqiLang['pay']['return_checkcode_error']='对不起，信息校验错误，请与管理员联系！';
$jieqiLang['pay']['buy_egold_success']='恭喜您，%s:<br /><br />您选择购买%s：%s 点已经入帐，请检查您的帐号。<br /><br />感谢您对我们的支持！';
$jieqiLang['pay']['already_add_egold']='对不起，本次交易已经完成充值,请不要重复提交！';
$jieqiLang['pay']['add_egold_success']='给 %s 增加%s %s';
$jieqiLang['pay']['add_egold_failure']='给序号：%s，用户名：%s 增加%s %s 失败';
$jieqiLang['pay']['state_unconfirm']='未确认';
$jieqiLang['pay']['state_paysuccess']='支付成功';
$jieqiLang['pay']['state_handconfirm']='手工确认';
$jieqiLang['pay']['state_unknow']='未知状态';
$jieqiLang['pay']['paytype_unknow']='未知方式';
$jieqiLang['pay']['hand_confirm_confirm']='确实要手工确认该订单么？';
$jieqiLang['pay']['hand_confirm']='手工处理';
$jieqiLang['pay']['delete_pay_confirm']='确实要删除么';
$jieqiLang['pay']['delete_pay']='删除';
$jieqiLang['pay']['customer_id_error']='对不起，商户编号对应不上，请与管理员联系！';
$jieqiLang['pay']['pay_return_error']='对不起，交易返回失败，可能余额不足或转帐过程出错！';
$jieqiLang['pay']['card_foreign']='外币卡';
$jieqiLang['pay']['card_local']='人民币卡';
$jieqiLang['pay']['pay_failure_message']='对不起，交易失败！<br>%s';
?>