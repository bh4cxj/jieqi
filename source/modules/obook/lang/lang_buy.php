<?php
/**
 * 语言包-购买电子书
 *
 * 语言包-购买电子书
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: lang_buy.php 231 2008-11-27 08:46:26Z juny $
 */

$jieqiLang['obook']['buy']=1; //表示本语言包已经包含

$jieqiLang['obook']['obook_not_exists']='对不起，该电子书不存在！';
//buychapter.php
$jieqiLang['obook']['not_in_sale']='对不起，该章节不存在或者已经下架！';
$jieqiLang['obook']['need_user_login']='对不起，请先登陆！';
$jieqiLang['obook']['chapter_money_notenough']='对不起，您的帐户余额不足！<br /><br />购买《%s - %s》需要 %s，您目前尚余 %s。<br /><br /><a href="%s">点击这里进行帐户充值</a>';
$jieqiLang['obook']['chapter_has_buyed']='电子书《%s - %s》您已经购买过，无需重复购买！<br /><br /><a href="%s">点击这里进入阅读</a>';
$jieqiLang['obook']['add_osale_faliure']='增加销售记录失败，请联系管理员！';
$jieqiLang['obook']['add_buyinfo_failure']='增加订阅记录失败，请联系管理员！';
//chapterbuylog.php
$jieqiLang['obook']['noper_manage_obook']='对不起，您无权管理本电子书！';
//buyobook.php
$jieqiLang['obook']['noselect_sale_ochapter']='对不起，您没有选择任何销售中的电子书！';
$jieqiLang['obook']['chapters_money_notenough']='对不起，您的帐户余额不足！<br /><br />您一共购买 %s 个章节，需要 %s，您目前尚余 %s。<br /><br /><a href="%s">点击这里进行帐户充值</a>';
$jieqiLang['obook']['batch_buy_success']='恭喜您，电子书订阅成功，请选择具体章节进行阅读！';
?>