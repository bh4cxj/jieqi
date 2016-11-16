<?php 
/**
 * 奥创手机支付-返回处理
 *
 * 奥创手机支付-返回处理
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: mocooreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'mocoo');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$logflag = 0; //是否记录日志
if($logflag){
	ob_start();
	print_r($_REQUEST);
	$recvdata=ob_get_contents();
	ob_end_clean();
	jieqi_writefile(JIEQI_ROOT_PATH.'/cache/mocoorecv.txt',$recvdata,'ab');
}
//检查变量
if(!isset($_REQUEST['mobile']) || !isset($_REQUEST['fee']) || !isset($_REQUEST['useid']) || !isset($_REQUEST['id']) || !isset($_REQUEST['pwd'])) exit;
//检查密钥
if($_REQUEST['pwd'] != $jieqiPayset[JIEQI_PAY_TYPE]['paykey']) exit;

include_once($jieqiModules['pay']['path'].'/class/paylog.php');
$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
$paylog= $paylog_handler->create();
$paylog->setVar('siteid', JIEQI_SITE_ID);
$paylog->setVar('buytime', JIEQI_NOW_TIME);
$paylog->setVar('rettime', 0);
$paylog->setVar('buyid', 0);
$paylog->setVar('buyname', '');
$paylog->setVar('buyinfo', $_REQUEST['mobile']);
$paylog->setVar('moneytype', $jieqiPayset[JIEQI_PAY_TYPE]['moneytype']);
$paylog->setVar('money', $jieqiPayset[JIEQI_PAY_TYPE]['emoney']);
$paylog->setVar('egoldtype', $jieqiPayset[JIEQI_PAY_TYPE]['paysilver']);
$paylog->setVar('egold', $jieqiPayset[JIEQI_PAY_TYPE]['egold']);
$paylog->setVar('paytype', JIEQI_PAY_TYPE);
$paylog->setVar('retserialno', '');
$paylog->setVar('retaccount', $_REQUEST['id']);
$paylog->setVar('retinfo', $_REQUEST['id']);
$paylog->setVar('masterid', 0);
$paylog->setVar('mastername', '');
$paylog->setVar('masterinfo', '');
$paylog->setVar('note', $_REQUEST['useid']);
$paylog->setVar('payflag', 0);
$paylog_handler->insert($paylog);
echo 'ok';
$serialno=$paylog->getVar('payid', 'n');

?>