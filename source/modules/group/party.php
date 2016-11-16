<?php
/**
 * 显示圈子活动
 *
 * 显示圈子活动
 * 
 * 调用模板：/modules/group/templates/party.html
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
jieqi_loadlang('party',JIEQI_MODULE_NAME);

$gid = intval($_REQUEST['g']);
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
setpower($gid);

$pid = intval($_REQUEST['pid']);

$bahref = "?g=$gid&pid=$pid&page=$_REQUEST[page]";

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/party.php');
$party_handler = JieqipartyHandler::getInstance('JieqipartyHandler');
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/partyreply.php');
$partyreply_handler = JieqipartyreplyHandler::getInstance('JieqipartyreplyHandler');
$criteria_replies = new CriteriaCompo(new  Criteria('gid',$gid) );
$criteria_replies->add(new Criteria('pid',$pid) ) ;


//party
$criteria_party = new CriteriaCompo(gid,$gid );
$criteria_party->add(new Criteria(pid,$pid) );
$party_handler->queryObjects($criteria_party);
$party = $party_handler->getObject();
if(empty($party) ){
	jieqi_printfail($jieqiLang['g']['party_not_exist']);
}

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


//reply

//分页
$count = $partyreply_handler->getCount($criteria_replies);
$onepage = 10;
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page']) ) $_REQUEST['page']=1;
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($count,$onepage,$_REQUEST['page'] );
$jieqiTpl->assign('jumppage',$jumppage->whole_num_bar() );

$criteria_replies->setSort("rid");
$criteria_replies->setOrder('asc');
$criteria_replies->setStart($onepage*($_REQUEST['page']-1) );
$criteria_replies->setLimit($onepage);
$replies = array();
$k = 0;
$partyreply_handler->queryObjects($criteria_replies);
while($reply = $partyreply_handler->getObject() ) {
	$replies[$k]['rcontent'] = $reply->getVar('rcontent');
	$replies[$k]['rtime'] = date ("Y-m-d H:i:s",$reply->getVar('rtime') );
	$replies[$k]['uname'] = $reply->getVar('uname');
	if( $allowdeltopic ){
		$replies[$k]['delete'] = "<a href='?g=$gid&pid=$pid&page=$_REQUEST[page]&delrid=".$reply->getVar('rid')."'>".$jieqiLang['g']['del']."</a>";
	}	
	$k++;
}


$jieqiTpl->assign('replies',$replies);



$man_party = "";
if( $allowstickparty ) {
	$man_party .= "&nbsp;<a href='?g=$gid&pid=$pid&page=$_REQUEST[page]&stickparty=1'>".$jieqiLang['g']['setTop_slash_unsetTop']."</a>";
	if($_REQUEST['stickparty'] ){
		$ptop = !($party->getVar('ptop') );
		$party_handler->updatefields("ptop='$ptop' ",$criteria_party);
		jieqi_jumppage($bahref,'',$jieqiLang['g']['operate_success']);
	}
}
if( $alloweditparty ) {
	$man_party .= "&nbsp;<a href='editparty.php?g=$gid&pid=$pid&page=$_REQUEST[page]&editparty=1'>".$jieqiLang['g']['edit']."</a>";
}
if( $allowdelparty ) {
	$man_party .= "&nbsp;<a href='?g=$gid&pid=$pid&page=$_REQUEST[page]&delparty=1'>".$jieqiLang['g']['del']."</a>";
	if ( $_REQUEST['delparty'] ) {
		$party_handler->delete($criteria_party);
	 	$partyreply_handler->delete($criteria_replies);		
		include_once("./include/functions.php");
		update_ginfo('gparties=gparties-1',$gid);
		jieqi_jumppage("./parties.php?g=$gid",'','',$jieqiLang['g']['del_success']);
	}
}
//顺便加上报名链接
$man_party .= "&nbsp;<a href='sign.php?g=$gid&pid=$pid&page=$_REQUEST[page]'>".$jieqiLang['g']['sign_up']."</a>";
$man_party .= "&nbsp;<a href='sign.php?g=$gid&pid=$pid&page=$_REQUEST[page]&out=1'>".$jieqiLang['g']['exit']."</a>";
$man_party .= "&nbsp;<a href='signstat.php?g=$gid&pid=$pid'>".$jieqiLang['g']['statistics']."</a>";
$jieqiTpl->assign('man_party',$man_party);



//delte reply
if ($rid = intval($_REQUEST['delrid'] ) ){
	if( $allowdeltopic ) {
		$party_handler->updatefields('replies=replies-1',$criteria_party);
		$partyreply_handler->delete($rid);	
		jieqi_jumppage("?g=$gid&pid=$pid&page=$_REQUEST[page] ",LANG_DO_SUCCESS,$jieqiLang['g']['del_reply_success']);
	}
}



//add reply
$submit_href = "?g=$gid&pid=$pid&page=$_REQUEST[page]&doreply=1";
$jieqiTpl->assign('submit_href',$submit_href);
if( $_REQUEST['doreply'] ){
	if ($allowreplyparty) {
		$partyreply = $partyreply_handler->create();
		$partyreply->setVar('pid',$pid);
		$partyreply->setVar('uid',$_SESSION['jieqiUserId'] );
		$partyreply->setVar('uname',$_SESSION['jieqiUserName'] );
		$partyreply->setVar('gid',$gid);
		$partyreply->setVar('rtime',time() );
		$partyreply->setVar('rcontent',$_REQUEST['rcontent'] );
		if( $partyreply_handler->insert($partyreply) ){
			$party_handler->updatefields('replies=replies+1',$criteria_party);
			jieqi_jumppage("?g=$gid&pid=$pid&page=$_REQUEST[page]",LANG_DO_SUCCESS,$jieqiLang['g']['reply_success']);	
		}
	} else {
		 echo "<script language='javascript'>alert('".$jieqiLang['g']['no_right_comment']."');history.go(-1)</script>";
		 exit;
	}	
}

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/party.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>