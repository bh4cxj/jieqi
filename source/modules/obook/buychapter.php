<?php 
/**
 * 购买章节
 *
 * 购买章节，扣除相应的虚拟币
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: buychapter.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
//<!--jieqi insert check code-->
jieqi_checklogin();
if(empty($_REQUEST['cid']) || !is_numeric($_REQUEST['cid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('buy', JIEQI_MODULE_NAME);
include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
$ochapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');
$ochapter = $ochapter_handler->get($_REQUEST['cid']);
if(!is_object($ochapter) || $ochapter->getVar('display') != 0) jieqi_printfail($jieqiLang['obook']['not_in_sale']);

jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];

include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$users=$users_handler->get($_SESSION['jieqiUserId']);
if(!is_object($users)) jieqi_printfail($jieqiLang['obook']['need_user_login']);

$obookname=$ochapter->getVar('obookname');
$chaptername=$ochapter->getVar('chaptername');
$saleprice=$ochapter->getVar('saleprice', 'n');
$useregold=$users->getVar('egold', 'n');
$useresilver=$users->getVar('esilver', 'n');
$useremoney=$useregold+$useresilver;
if($useremoney < $saleprice) jieqi_printfail(sprintf($jieqiLang['obook']['chapter_money_notenough'], $obookname, $chaptername, $saleprice.' '.JIEQI_EGOLD_NAME, $useremoney.' '.JIEQI_EGOLD_NAME, $jieqiModules['pay']['url'].'/buyegold.php'));

if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'show';

switch ( $_REQUEST['action'] ) {
	case 'buy':
	//$encpass=$users_handler->encryptPass(trim($_POST['password']));
	//if($encpass != $users->getVar('pass')) jieqi_printfail('对不起，密码错误，请注意大小写是否正确！');
	//重新检查是否已经购买，买过的提示不用重新购买
	include_once($jieqiModules['obook']['path'].'/class/obuyinfo.php');
	$buyinfo_handler =& JieqiObuyinfoHandler::getInstance('JieqiObuyinfoHandler');
	$criteria=new CriteriaCompo(new Criteria('ochapterid', $_REQUEST['cid']));
	$criteria->add(new Criteria('userid', $_SESSION['jieqiUserId']));
	$criteria->setLimit(1);
	$buyinfo_handler->queryObjects($criteria);
	unset($criteria);
	$buyinfo=$buyinfo_handler->getObject();
	if(is_object($buyinfo)){
		jieqi_printfail(sprintf($jieqiLang['obook']['chapter_has_buyed'], $obookname, $chaptername, $obook_static_url.'/reader.php?cid='.$_REQUEST['cid']));
	}
	
	if($useregold >= $saleprice) $pricetype=0;
	else $pricetype=1;
	//增加销售记录
	include_once($jieqiModules['obook']['path'].'/class/osale.php');
	$osale_handler =& JieqiOsaleHandler::getInstance('JieqiOsaleHandler');
	$osale=$osale_handler->create();
	$osale->setVar('siteid', JIEQI_SITE_ID);
	$osale->setVar('buytime', JIEQI_NOW_TIME);
	$osale->setVar('accountid', $users->getVar('uid', 'n'));
	$osale->setVar('account', $users->getVar('uname', 'n'));
	$osale->setVar('obookid', $ochapter->getVar('obookid', 'n'));
	$osale->setVar('ochapterid', $ochapter->getVar('ochapterid', 'n'));
	$osale->setVar('obookname', $ochapter->getVar('obookname', 'n'));
	$osale->setVar('chaptername', $ochapter->getVar('chaptername', 'n'));
	$osale->setVar('saleprice', $saleprice);
	$osale->setVar('pricetype', $pricetype);
	$osale->setVar('paytype', 0);
	$osale->setVar('payflag', 0);
	$osale->setVar('paynote', '');
	$osale->setVar('state', 0);
	$osale->setVar('flag', 0);
	$ret=$osale_handler->insert($osale);
	if(!$ret) jieqi_printfail($jieqiLang['obook']['add_osale_faliure']);
	//增加订阅记录
	//include_once($jieqiModules['obook']['path'].'/class/obuyinfo.php');
	//$buyinfo_handler =& JieqiObuyinfoHandler::getInstance('JieqiObuyinfoHandler');
	$buyinfo=$buyinfo_handler->create();
	$buyinfo->setVar('siteid', JIEQI_SITE_ID);
	$buyinfo->setVar('osaleid', $osale->getVar('osaleid', 'n'));
	$buyinfo->setVar('buytime', JIEQI_NOW_TIME);
	$buyinfo->setVar('userid', $users->getVar('uid', 'n'));
	$buyinfo->setVar('username', $users->getVar('uname', 'n'));
	$buyinfo->setVar('obookid', $ochapter->getVar('obookid', 'n'));
	$buyinfo->setVar('ochapterid', $ochapter->getVar('ochapterid', 'n'));
	$buyinfo->setVar('obookname', $ochapter->getVar('obookname', 'n'));
	$buyinfo->setVar('chaptername', $ochapter->getVar('chaptername', 'n'));
	$buyinfo->setVar('lastread', 0);
	$buyinfo->setVar('readnum', 0);
	$buyinfo->setVar('state', 0);
	$buyinfo->setVar('flag', 0);
	$ret=$buyinfo_handler->insert($buyinfo);
	if(!$ret) jieqi_printfail($jieqiLang['obook']['add_buyinfo_failure']);
	//改变章节销售状态
	$lastsale=$ochapter->getVar('lastsale', 'n');
	$lastdate=date('Y-m-d', $lastsale);
	$nowdate=date('Y-m-d',  JIEQI_NOW_TIME);
	$nowweek=date('w', JIEQI_NOW_TIME);
	$addnum=1;

	if($nowdate==$lastdate){
		$daysale=$ochapter->getVar('daysale','n')+$addnum;
		$weeksale=$ochapter->getVar('weeksale', 'n')+$addnum;
		$monthsale=$ochapter->getVar('monthsale', 'n')+$addnum;
		
	}else{
		$daysale=$addnum;
		if($nowweek==1){
			$weeksale=$addnum;
		}else{
			$weeksale=$ochapter->getVar('weeksale', 'n')+$addnum;
		}
		if(substr($nowdate,0,7)==substr($lastdate,0,7)){
			$monthsale=$ochapter->getVar('monthsale', 'n')+$addnum;
		}else{
			$monthsale=$addnum;
		}
	}
	$allsale=$ochapter->getVar('allsale', 'n')+$addnum;
	$normalsale=$ochapter->getVar('normalsale', 'n')+$addnum;
	$totalsale=$ochapter->getVar('totalsale', 'n')+$addnum;

	$ochapter->setVar('lastsale', JIEQI_NOW_TIME);
	$ochapter->setVar('daysale', $daysale);
	$ochapter->setVar('weeksale', $weeksale);
	$ochapter->setVar('monthsale', $monthsale);
	$ochapter->setVar('allsale', $allsale);
	$ochapter->setVar('normalsale', $normalsale);
	$ochapter->setVar('totalsale', $totalsale);
	if($pricetype==1) $ochapter->setVar('sumesilver', $ochapter->getVar('sumesilver', 'n')+$saleprice);
	else $ochapter->setVar('sumegold', $ochapter->getVar('sumegold', 'n')+$saleprice);
	$ochapter_handler->insert($ochapter);

	//改变电子书信息里面的销售状态
	include_once($jieqiModules['obook']['path'].'/class/obook.php');
	$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
	$obook = $obook_handler->get($ochapter->getVar('obookid', 'n'));
	if(is_object($obook)){
		$lastsale=$obook->getVar('lastsale', 'n');
		$lastdate=date('Y-m-d', $lastsale);
		$nowdate=date('Y-m-d',  JIEQI_NOW_TIME);
		$nowweek=date('w', JIEQI_NOW_TIME);
		$addnum=1;

		if($nowdate==$lastdate){
			$daysale=$obook->getVar('daysale','n')+$addnum;
			$weeksale=$obook->getVar('weeksale', 'n')+$addnum;
			$monthsale=$obook->getVar('monthsale', 'n')+$addnum;
		}else{
			$daysale=$addnum;
			if($nowweek==1){
				$weeksale=$addnum;
			}else{
				$weeksale=$obook->getVar('weeksale', 'n')+$addnum;
			}
			if(substr($nowdate,0,7)==substr($lastdate,0,7)){
				$monthsale=$obook->getVar('monthsale', 'n')+$addnum;
			}else{
				$monthsale=$addnum;
			}
		}
		$allsale=$obook->getVar('allsale', 'n')+$addnum;
		$normalsale=$obook->getVar('normalsale', 'n')+$addnum;
		$totalsale=$obook->getVar('totalsale', 'n')+$addnum;

		$obook->setVar('lastsale', JIEQI_NOW_TIME);
		$obook->setVar('daysale', $daysale);
		$obook->setVar('weeksale', $weeksale);
		$obook->setVar('monthsale', $monthsale);
		$obook->setVar('allsale', $allsale);
		$obook->setVar('normalsale', $normalsale);
		$obook->setVar('totalsale', $totalsale);
		if($pricetype==1) $obook->setVar('sumesilver', $obook->getVar('sumesilver', 'n')+$saleprice);
		else $obook->setVar('sumegold', $obook->getVar('sumegold', 'n')+$saleprice);
		$obook_handler->insert($obook);
	}
	//扣除虚拟货币
	$users_handler->payout($users->getVar('uid', 'n'), $saleprice);
	header('Location: '.$obook_static_url.'/reader.php?cid='.$_REQUEST['cid']);
	break;
	case 'show':
	default:
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign('obook_static_url',$obook_static_url);
	$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
	$jieqiTpl->assign('cid',$_REQUEST['cid']);
	$jieqiTpl->assign('url_buychapter',$obook_dynamic_url.'/buychapter.php');
	$jieqiTpl->assign('url_obookinfo',$obook_dynamic_url.'/obookinfo.php?id='.$ochapter->getVar('obookid', 'n'));
	$jieqiTpl->assign('url_buyegold',$jieqiModules['pay']['url'].'/buyegold.php');
	$jieqiTpl->assign('obookname',$obookname);
	$jieqiTpl->assign('chaptername',$chaptername);
	$jieqiTpl->assign('saleprice',$saleprice);
	$jieqiTpl->assign('useregold',$useregold);
	$jieqiTpl->assign('useresilver',$useresilver);
	$jieqiTpl->assign('useremoney',$useremoney);
	$jieqiTpl->assign('username',$users->getVar('uname'));
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/buychapter.html';
	include_once(JIEQI_ROOT_PATH.'/footer.php');
	break;
}
?>