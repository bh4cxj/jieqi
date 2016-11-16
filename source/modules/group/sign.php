<?php
/**
 * 显示圈子活动报名
 *
 * 显示圈子活动报名
 * 
 * 调用模板：/modules/group/templates/sign.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');
jieqi_loadlang('sign',JIEQI_MODULE_NAME);
$gid = intval($_REQUEST['g']);
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
jieqi_checklogin();
setpower($gid);
$pid = intval($_REQUEST['pid']);

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/party.php');
$party_handler = JieqipartyHandler::getInstance('JieqipartyHandler');
$criteria_party = new CriteriaCompo(gid,$gid );
$criteria_party->add(new Criteria(pid,$pid) );


include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/sign.php');
$sign_handler = JieqisignHandler::getInstance('JieqisignHandler');
$criteria_signed = new CriteriaCompo(new Criteria('pid',$pid) );
$criteria_signed->add(new Criteria('uid',$_SESSION['jieqiUserId'] ) );

$signed = $sign_handler->getCount($criteria_signed);
//处理退出
if( $_REQUEST['out'] ){
	if( $signed ){
		$sign_handler->queryObjects($criteria_signed);
		$v = $sign_handler->getObject();
		$nums = $v->getVar('nums');

		if($v->getVar('signflag') == 1){
			$passnums = $nums;
		}else {
			$passnums = 0;
		}
		$party_handler->updatefields("pnums=pnums-$nums,passnums=passnums-$passnums",$criteria_party);	
		$sign_handler->delete($criteria_signed);
		echo "<script language='javascript'>alert('".$jieqiLang['g']['success_exit']."');history.go(-1)</script>";
	} else {
		echo "<script language='javascript'>alert('".$jieqiLang['g']['have_not_sign_up']."');history.go(-1)</script>";
	}	
}

//检查是否已经报名 
if($signed){
	echo "<script language='javascript'>alert('".$jieqiLang['g']['had_signed_up']."');history.go(-1)</script>";
}


//party
if(!($party_handler->getCount($criteria_party)) ){
	jieqi_printfail($jieqiLang['g']['this_party_not_exist']);
}

$party_handler->queryObjects($criteria_party);
$party = $party_handler->getObject();
$jieqiTpl->assign('pid',$pid);
$jieqiTpl->assign('ptitle',$party->getVar('ptitle') );
$jieqiTpl->assign('puname',$party->getVar('uname') );
$jieqiTpl->assign('ptime',date('Y-m-d H:i',$party->getVar('ptime') ) );
$jieqiTpl->assign('pstart',date('Y-m-d H:i',$party->getVar('pstart') ) );
$jieqiTpl->assign('pstop',date('Y-m-d H:i',$party->getVar('pstop') ) );
$jieqiTpl->assign('pnums',$party->getVar('pnums') );
$jieqiTpl->assign('pmaxnums',$party->getVar('pmaxnums') );
$jieqiTpl->assign('pcontent',$party->getVar('pcontent') );
$jieqiTpl->assign('pplace',$party->getVar('pplace') );


//add sign 
if($nums = intval( $_REQUEST['nums'] ) ){
	if ($allowreplyparty ==1 ) {
		$sign = $sign_handler->create();
		$sign->setVar('pid',$pid);
		$sign->setVar('uid',$_SESSION['jieqiUserId'] );
		$sign->setVar('uname',$_SESSION['jieqiUserName'] );
		$sign->setVar('gid',$gid);
		$sign->setVar('signtime',time() );
		$sign->setVar('nums',$nums );
		$sign->setVar('linkway',$_POST['linkway'] );
		if( $sign_handler->insert($sign) ){
			$party_handler->updatefields("pnums=pnums+$nums",$criteria_party);
			jieqi_jumppage("./party.php?g=$gid&pid=$pid",LANG_DO_SUCCESS,$jieqiLang['g']['sign_up_success'] );	
		}
	} else {
		echo "<script language='javascript'>alert('".$jieqiLang['g']['no_sign_up_right']."');history.go(-1)</script>";
	}	
}

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/sign.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>