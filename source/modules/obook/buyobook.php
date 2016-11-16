<?php 
/**
 * 显示购买章节界面
 *
 * 显示购买章节界面
 * 
 * 调用模板：/modules/obook/templates/buyobook.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: buyobook.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
//jieqi_checklogin();
if(empty($_REQUEST['oid']) || !is_numeric($_REQUEST['oid']) || empty($_REQUEST['checkid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
//检查登录状态跳转
if($jieqiUsersGroup==JIEQI_GROUP_GUEST){
	header('Location: '.JIEQI_USER_URL.'/login.php?jumpurl='.urlencode(dirname(jieqi_addurlvars(array(),false,false)).'/obookinfo.php?id='.$_REQUEST['oid']));
}
$ochapterary=array();
if(is_array($_REQUEST['checkid'])){
	foreach($_REQUEST['checkid'] as $v){
		$v=intval($v);
		if($v>0) $ochapterary[]=$v;
	}
}else{
	$v=intval($_REQUEST['checkid']);
	if($v>0) $ochapterary[]=$v;
}
if(empty($ochapterary)) jieqi_printfail(LANG_ERROR_PARAMETER);

jieqi_loadlang('buy', JIEQI_MODULE_NAME);
jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$sql="SELECT ochapterid FROM ".jieqi_dbprefix('obook_obuyinfo')." WHERE userid=".intval($_SESSION['jieqiUserId'])." AND ochapterid IN (".implode(',',$ochapterary).")";
$query->execute($sql);
$buyary=array();
while($obuyinfo = $query->getRow()) {
	$buyary[]=$obuyinfo['ochapterid'];
}

$sql="SELECT * FROM ".jieqi_dbprefix('obook_ochapter')." WHERE ochapterid IN (".implode(',',$ochapterary).") AND display=0 ORDER BY ochapterid ASC";
$query->execute($sql);
$ochapterrows=array();
$chapternum=0;
$saleprice=0;
while($ochapter = $query->getRow()) {
	if(!in_array($ochapter['ochapterid'], $buyary)){
		if($chapternum==0 && $_REQUEST['oid'] != $ochapter['obookid']) $_REQUEST['oid'] = $ochapter['obookid'];
		$ochapterrows[$chapternum]['ochapterid']=$ochapter['ochapterid'];
		$ochapterrows[$chapternum]['obookid']=$ochapter['obookid'];
		$ochapterrows[$chapternum]['postdate']=$ochapter['postdate'];
		$ochapterrows[$chapternum]['lastupdate']=$ochapter['lastupdate'];
		$ochapterrows[$chapternum]['obookname']=$ochapter['obookname'];
		$ochapterrows[$chapternum]['chaptername']=$ochapter['chaptername'];
		$ochapterrows[$chapternum]['size']=$ochapter['size'];
		$ochapterrows[$chapternum]['saleprice']=$ochapter['saleprice'];
		$ochapterrows[$chapternum]['lastsale']=$ochapter['lastsale'];

		$chapternum++;
		$saleprice+=$ochapter['saleprice'];
	}
}
if($chapternum==0) jieqi_printfail($jieqiLang['obook']['noselect_sale_ochapter']);



jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];

include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$users=$users_handler->get($_SESSION['jieqiUserId']);
if(!is_object($users)) jieqi_printfail($jieqiLang['obook']['need_user_login']);

$useregold=$users->getVar('egold', 'n');
$useresilver=$users->getVar('esilver', 'n');
$useremoney=$useregold+$useresilver;
if($useremoney < $saleprice) jieqi_printfail(sprintf($jieqiLang['obook']['chapters_money_notenough'], $chapternum, $saleprice.' '.JIEQI_EGOLD_NAME, $useremoney.' '.JIEQI_EGOLD_NAME, $jieqiModules['pay']['url'].'/buyegold.php'));

if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'show';

switch ( $_REQUEST['action'] ) {
	case 'buy':
		//$encpass=$users_handler->encryptPass(trim($_POST['password']));
		//if($encpass != $users->getVar('pass')) jieqi_printfail('对不起，密码错误，请注意大小写是否正确！');
		if($useregold >= $saleprice) $pricetype=0;
		else $pricetype=1;
		include_once($jieqiModules['obook']['path'].'/class/osale.php');
		$osale_handler =& JieqiOsaleHandler::getInstance('JieqiOsaleHandler');
		include_once($jieqiModules['obook']['path'].'/class/obuyinfo.php');
		$buyinfo_handler =& JieqiObuyinfoHandler::getInstance('JieqiObuyinfoHandler');
		foreach($ochapterrows as $v){
			//增加销售记录
			$osale=$osale_handler->create();
			$osale->setVar('siteid', JIEQI_SITE_ID);
			$osale->setVar('buytime', JIEQI_NOW_TIME);
			$osale->setVar('accountid', $users->getVar('uid', 'n'));
			$osale->setVar('account', $users->getVar('uname', 'n'));
			$osale->setVar('obookid', $v['obookid']);
			$osale->setVar('ochapterid', $v['ochapterid']);
			$osale->setVar('obookname', $v['obookname']);
			$osale->setVar('chaptername', $v['chaptername']);
			$osale->setVar('saleprice', $v['saleprice']);
			$osale->setVar('pricetype', $pricetype);
			$osale->setVar('paytype', 0);
			$osale->setVar('payflag', 0);
			$osale->setVar('paynote', '');
			$osale->setVar('state', 0);
			$osale->setVar('flag', 0);
			$ret=$osale_handler->insert($osale);
			if(!$ret) jieqi_printfail($jieqiLang['obook']['add_osale_faliure']);
			//增加订阅记录
			$buyinfo=$buyinfo_handler->create();
			$buyinfo->setVar('siteid', JIEQI_SITE_ID);
			$buyinfo->setVar('osaleid', $osale->getVar('osaleid', 'n'));
			$buyinfo->setVar('buytime', JIEQI_NOW_TIME);
			$buyinfo->setVar('userid', $users->getVar('uid', 'n'));
			$buyinfo->setVar('username', $users->getVar('uname', 'n'));
			$buyinfo->setVar('obookid', $v['obookid']);
			$buyinfo->setVar('ochapterid', $v['ochapterid']);
			$buyinfo->setVar('obookname', $v['obookname']);
			$buyinfo->setVar('chaptername', $v['chaptername']);
			$buyinfo->setVar('lastread', 0);
			$buyinfo->setVar('readnum', 0);
			$buyinfo->setVar('state', 0);
			$buyinfo->setVar('flag', 0);
			$ret=$buyinfo_handler->insert($buyinfo);
			if(!$ret) jieqi_printfail($jieqiLang['obook']['add_buyinfo_failure']);
			//改变章节销售状态
			$lastsale=$v['lastsale'];
			$lastdate=date('Y-m-d', $lastsale);
			$nowdate=date('Y-m-d',  JIEQI_NOW_TIME);
			$nowweek=date('w', JIEQI_NOW_TIME);
			$addnum=1;
			$sql='';
			if($nowdate==$lastdate){
				$sql.='daysale=daysale+'.$addnum.', weeksale=weeksale+'.$addnum.', monthsale=monthsale+'.$addnum;
			}else{
				$sql.='daysale='.$addnum;
				if($nowweek==1){
					$sql.=', weeksale='.$addnum;
				}else{
					$sql.=', weeksale=weeksale+'.$addnum;
				}
				if(substr($nowdate,0,7)==substr($lastdate,0,7)){
					$sql.=', monthsale=monthsale+'.$addnum;
				}else{
					$sql.=', monthsale='.$addnum;
				}
			}
			$sql.=', allsale=allsale+'.$addnum.', normalsale=normalsale+'.$addnum.', totalsale=totalsale+'.$addnum.', lastsale='.JIEQI_NOW_TIME;
			if($pricetype==1) $sql.=', sumesilver=sumesilver+'.$v['saleprice'];
			else $sql.=', sumegold=sumegold+'.$v['saleprice'];
			$sql="UPDATE ".jieqi_dbprefix('obook_ochapter')." SET ".$sql." WHERE ochapterid=".$v['ochapterid'];
			$query->execute($sql);
		}

		//改变电子书信息里面的销售状态
		include_once($jieqiModules['obook']['path'].'/class/obook.php');
		$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
		$obook = $obook_handler->get($_REQUEST['oid']);
		if(is_object($obook)){
			$lastsale=$obook->getVar('lastsale', 'n');
			$lastdate=date('Y-m-d', $lastsale);
			$nowdate=date('Y-m-d',  JIEQI_NOW_TIME);
			$nowweek=date('w', JIEQI_NOW_TIME);
			$addnum=$chapternum;

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
		jieqi_jumppage($obook_static_url.'/obookinfo.php?id='.$_REQUEST['oid'], LANG_DO_SUCCESS, $jieqiLang['obook']['batch_buy_success']);
		//header('Location: '.$obook_static_url.'/obookinfo.php?id='.$_REQUEST['oid']);
		break;
	case 'show':
	default:
		include_once(JIEQI_ROOT_PATH.'/header.php');
		$jieqiTpl->assign('obook_static_url',$obook_static_url);
		$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
		$jieqiTpl->assign('oid',$_REQUEST['oid']);
		$jieqiTpl->assign('url_buyobook',$obook_dynamic_url.'/buyobook.php');
		$jieqiTpl->assign('url_obookinfo',$obook_dynamic_url.'/obookinfo.php?id='.$_REQUEST['oid']);
		$jieqiTpl->assign('url_buyegold',$jieqiModules['pay']['url'].'/buyegold.php');
		$jieqiTpl->assign('saleprice',$saleprice);
		$jieqiTpl->assign('useregold',$useregold);
		$jieqiTpl->assign('useresilver',$useresilver);
		$jieqiTpl->assign('useremoney',$useremoney);
		$jieqiTpl->assign('username',$users->getVar('uname'));

		$jieqiTpl->assign_by_ref('ochapterrows', $ochapterrows);
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/buyobook.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}
?>