<?php 
/**
 * 生成充值点卡
 *
 * 生成充值点卡
 * 
 * 调用模板：/modules/pay/templates/admin/makepaycard.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: makepaycard.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_USE_GZIP','0');
define('JIEQI_MODULE_NAME', 'pay');
require_once('../../../global.php');
//检查权限
jieqi_checklogin();
if($jieqiUsersStatus != JIEQI_GROUP_ADMIN) jieqi_printfail(LANG_NEED_ADMIN);
@set_time_limit(3600);
@session_write_close();
jieqi_loadlang('paycard', JIEQI_MODULE_NAME);
if($_POST['action']=='makepaycard'){
	$errtext='';
	//检查批号
	if(strlen($_POST['batchno'])==0) $_POST['batchno']='';
	else $_POST['batchno']=trim($_POST['batchno']);
	//起始序号
	if(!is_numeric($_POST['startid'])) $errtext .= $jieqiLang['pay']['paycard_startid_neednum'].'<br />';
	else $_POST['startid']=intval($_POST['startid']);
	//结束序号
	if(!is_numeric($_POST['endid'])) $errtext .= $jieqiLang['pay']['paycard_endid_neednum'].'<br />';
	else $_POST['endid']=intval($_POST['endid']);
	//序号大小
	if($_POST['endid'] < $_POST['startid']) $errtext .= $jieqiLang['pay']['endid_less_startid'].'<br />';
	//卡号长度
	$_POST['cardlen']=intval($_POST['cardlen']);
	if($_POST['cardlen']<=0 && $_POST['cardlen']>30) $errtext .= $jieqiLang['pay']['card_len_limit'].'<br />';
	//密码长度
	$_POST['passlen']=intval($_POST['passlen']);
	if($_POST['passlen']<=0 && $_POST['passlen']>30) $errtext .= $jieqiLang['pay']['pass_len_limit'].'<br />';
	//冲值金额
	if(!is_numeric($_POST['payemoney'])) $errtext .= $jieqiLang['pay']['paycard_payemoney_neednum'].'<br />';
	else $_POST['payemoney']=intval($_POST['payemoney']);

	if(empty($errtext)) {
		//开始生成卡号
		switch($_POST['passtype']){
			case 3:
				$randmode=3;
				break;
			case 2:
				$randmode=2;
				break;
			case 1:
			default:
				$randmode=1;
				break;
		}
		$maxmakenum=$_POST['endid']-$_POST['startid']+1;
		echo $jieqiLang['pay']['start_make_paycard'];
		ob_flush();
		flush();
		include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
		include_once($jieqiModules['pay']['path'].'/class/paycard.php');
		$paycard_handler =& JieqiPaycardHandler::getInstance('JieqiPaycardHandler');
		$formatlen=$_POST['cardlen'] - strlen($_POST['batchno']);
		if($formatlen < strlen($_POST['endid'])) $formatlen=strlen($_POST['endid']);
		for($i=$_POST['startid']; $i<=$_POST['endid']; $i++){
			$cardno=$_POST['batchno'].sprintf('%0'.$formatlen.'d', $i);
			$cardpass=jieqi_randstr($_POST['passlen'], $randmode);

			$newpaycard = $paycard_handler->create();
			$newpaycard->setVar('batchno', $_POST['batchno']);
			$newpaycard->setVar('cardno', $cardno);
			$newpaycard->setVar('cardpass', $cardpass);
			$newpaycard->setVar('cardtype', 0);
			$newpaycard->setVar('payemoney', $_POST['payemoney']);
			$newpaycard->setVar('emoneytype', 0);
			$newpaycard->setVar('ispay', 0);
			$newpaycard->setVar('paytime', 0);
			$newpaycard->setVar('payuid', 0);
			$newpaycard->setVar('payname', '');
			$newpaycard->setVar('note', '');
			$newpaycard->setVar('flag', 0);

			if (!$paycard_handler->insert($newpaycard)){
				echo sprintf($jieqiLang['pay']['paycard_add_failure'], $_POST['startid'], $i - 1, $i - $_POST['startid']);
				ob_flush();
				flush();
				exit;
			}else {			
				echo sprintf($jieqiLang['pay']['card_list_format'], $cardno, $cardpass);
				ob_flush();
				flush();
			}
		}
	}else{
		jieqi_printfail($errtext);
	}
	//全部生成完成
	echo sprintf($jieqiLang['pay']['paycard_make_success'], $maxmakenum);
	exit;
}else{
	include_once(JIEQI_ROOT_PATH.'/admin/header.php');
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/admin/makepaycard.html';
	include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
}
?>