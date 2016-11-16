<?php
/**
 * 处理圈子活动报名
 *
 * 处理圈子活动报名
 * 
 * 调用模板：/modules/group/templates/signstat.html
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
jieqi_loadlang('signstat',JIEQI_MODULE_NAME);

$gid = intval($_REQUEST['g']);

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
setpower($gid);
$pid = intval($_REQUEST['pid']);


include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/sign.php');
$sign_handler = JieqisignHandler::getInstance('JieqisignHandler');
$criteria_signed = new CriteriaCompo(new Criteria('pid',$pid) );
$criteria_signed->setSort('signflag desc,signid');
$criteria_signed->setOrder('desc');

$sign_handler->queryObjects($criteria_signed);
$signs = array();
$k = 0;
while($v = $sign_handler->getObject() ) {
	$signs[$k]['signid'] = $v->getVar('signid');
	$signs[$k]['uname']  = $v->getVar('uname');
	if($alloweditparty){
		$signs[$k]['uname'] .= "(".$v->getVar('linkway').")";
	}

	$signs[$k]['signtime'] = date("Y-m-d H:i:s",$v->getVar('signtime') );
	$signs[$k]['signflag'] = $v->getVar('signflag');
	$signs[$k]['nums'] = $v->getVar('nums');
	$k++;
}
$jieqiTpl->assign('signs',$signs);


include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/party.php');
$party_handler = JieqipartyHandler::getInstance('JieqipartyHandler');
$criteria_party = new CriteriaCompo(gid,$gid );
$criteria_party->add(new Criteria(pid,$pid) );

if($_REQUEST['mansigns'] ) {
	if($allowmanparty){
		jieqi_includedb();
		$mansigns = '(0';
		foreach($_REQUEST['mansigns'] as $v){
			$mansigns .= ', '.intval($v);
		}
		$mansigns.=')';
		$handler= JieqiQueryHandler::getInstance('JieqiQueryHandler') ;	
		if($_REQUEST['deletebu'] ){
			$sql = "delete  from ".jieqi_dbprefix('group_sign')." where signid in $mansigns";
			$handler->execute($sql);
		}
		if($_REQUEST['passbu'] ) {
			$sql = "update ".jieqi_dbprefix('group_sign')." set signflag=1 where signid in $mansigns";
			$handler->execute($sql);	
		}

		//更新统计数据
		$query_handler = JieqiQueryHandler::getInstance('JieqiQueryHandler');

		$criteria = new CriteriaCompo(new Criteria('pid',$pid) );
		$criteria->setTables("jieqi_group_sign");

	
		//all nums
		$criteria->setFields("sum(nums) as nums ");
		$query_handler->queryObjects($criteria);
		$v = $query_handler->getObject();
		
		//pass nums
		$nums = intval($v->getVar('nums') );
		$criteria->add(new Criteria('signflag','1') );
		$criteria->setFields("sum(nums) as passnums ");
		$query_handler->queryObjects($criteria);
		$v = $query_handler->getObject();

		$passnums = intval($v->getVar('passnums') );
		$party_handler->updatefields("pnums=$nums, passnums='$passnums' ",$criteria_party);
		jieqi_jumppage("./signstat.php?g=$gid&pid=$pid",LANG_DO_SUCCESS,$jieqiLang['g']['ok_audit']);
	} else {
		echo "<script language='javascript'>alert('".$jieqiLang['g']['no_audit_right']."');history.back(1)</script>";
	}
}



//party
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
$jieqiTpl->assign('passnums',$party->getVar('passnums') );
$jieqiTpl->assign('pmaxnums',$party->getVar('pmaxnums') );
$jieqiTpl->assign('pcontent',$party->getVar('pcontent') );
$jieqiTpl->assign('pplace',$party->getVar('pplace') );
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/signstat.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>