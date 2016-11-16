<?php 
/**
 * 盛大卡支付-返回状态
 *
 * 盛大卡支付-返回状态 (http://www.snda.com.cn)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: sndacardshow.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'sndacard');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_loadlang('snda', JIEQI_MODULE_NAME);
switch($_REQUEST['retcode']){
	case 1:
		jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['pay']['buy_egold_success'], jieqi_htmlstr($_REQUEST['buyname']), JIEQI_EGOLD_NAME, jieqi_htmlstr($_REQUEST['egold'])));
		break;
	case -1002:
		jieqi_printfail($jieqiLang['pay']['errmsg_card_password']);
		break;
	case -1003:
		jieqi_printfail($jieqiLang['pay']['errmsg_card_used']);
		break;
	case -2004:
		jieqi_printfail($jieqiLang['pay']['errmsg_card_cant']);
		break;
	case -1:
		jieqi_printfail($jieqiLang['pay']['errmsg_syatem']);
		break;
	case -101:
		jieqi_printfail($jieqiLang['pay']['errmsg_customer']);
		break;
	case -102:
		jieqi_printfail($jieqiLang['pay']['errmsg_checkcode']);
		break;
	case -103:
		jieqi_printfail($jieqiLang['pay']['errmsg_no_paylog']);
		break;
	case -104:
		jieqi_printfail($jieqiLang['pay']['errmsg_save_paylog']);
		break;
	default:
		jieqi_printfail($jieqiLang['pay']['errmsg_unknow']);
}
?>