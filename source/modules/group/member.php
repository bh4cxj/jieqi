<?php
/**
 * 圈子会员列表
 *
 * 圈子会员列表
 * 
 * 调用模板：/modules/group/templates/member.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
jieqi_checklogin();
jieqi_loadlang('member',JIEQI_MODULE_NAME);
if(!($gid = intval($_REQUEST['g'])) ){
	header("Location: ".JIEQI_URL);
}

$uid = intval($_REQUEST['uid'] );
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
jieqi_getconfigs('group', 'memberblock', 'jieqiBlocks');
//是否使用徽章
if(JIEQI_USE_BADGE){
//读取徽章配置
jieqi_getconfigs('badge','configs');
	include_once(JIEQI_ROOT_PATH.'/modules/badge/include/badgefunction.php');
	//判断徽章是否存在
	include_once(JIEQI_ROOT_PATH.'/modules/badge/class/badge.php');
	$badge_handler =& JieqiBadgeHandler::getInstance('JieqiBadgeHandler'); 
	$criteria=new CriteriaCompo(new Criteria('btypeid', '2010'));
	$criteria->add( new Criteria('linkid',$gid) );
	$badge_handler->queryObjects($criteria);
	$badge=$badge_handler->getObject();
	if(is_object($badge)){
		$jieqiTpl->assign($have_bade,1);
		$badge_id=$badge->getVar('badgeid','n');
		$badge_name=$badge->getVar('caption','n');
		$badge_maxnum=$badge->getVar('maxnum','n');
		$badge_usenum=$badge->getVar('usenum','n');
		$badge_linkid=$badge->getVar('linkid','n');
		$jieqiTpl->assign('badge_id',$badge_id);
		$jieqiTpl->assign('caption',$caption);
		$jieqiTpl->assign('maxnum',$maxnum);
		$jieqiTpl->assign('usenum',$usenum);
		$badge_is_exists = 1;
	}else{
	    $badge_is_exists = 0;
		$jieqiTpl->assign('have_badge',0);
	}
}
$awardrows=array();
$jieqi_image_type=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/member.php');
$member_handler = JieqimemberHandler::getInstance('JieqimemberHandler');
jieqi_includedb();
$member_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');


include_once(JIEQI_ROOT_PATH.'/class/users.php');
jieqi_getconfigs('system', 'honors'); //头衔
//头像参数
if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');

//每页显示条目
$onepage = 9;
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;


//管理操作
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
setpower($gid);
$criteria = new CriteriaCompo(new Criteria('gid',$gid ) );
$criteria->add(new Criteria('uid',$uid ) );
$criteria->add(new Criteria('creater','1','!=') );
if($allowmanmember){
	if ($_REQUEST['out'] ) {
	    if($guid==$uid) jieqi_printfail($jieqiLang['g']['out_failure']);
		if($member_handler->delete($criteria) ) { 
			include_once("./include/functions.php");
			user_group($uid,$gid,'delete');
			update_ginfo('gmembers=gmembers-1',$gid);
			jieqi_jumppage("?g=$gid&page=$_REQUEST[page]",LANG_DO_SUCCESS,$jieqiLang['g']['operate_success']);
			/* */
		}
	}
	if(JIEQI_USE_BADGE){
		if($_REQUEST['gbadge'] && is_object($badge) ){
			if(!$badge_is_exists || ($badge_maxnum>0 && $badge_usenum>=$badge_maxnum)) jieqi_printfail(sprintf($jieqiLang['g']['badge_all_usage'], $badge_maxnum));
			//授予徽章处理
			$errtext='';
			if( !$uid ) $errtext .= $jieqiLang['badge']['need_userid_name'].'<br />';
			if(empty($errtext)) {
				//检查用户是否存在
				include_once(JIEQI_ROOT_PATH.'/class/users.php');
				$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
				$isuser=false;
				$user=$users_handler->get($uid);
				if(is_object($user)){
					$isuser=true;
				}else{
					jieqi_printfail($jieqiLang['badge']['confer_user_notexists']);
				}
				$confer_userid=$user->getVar('uid', 'n');
				$confer_username=$user->getVar('uname', 'n');
	
				//判断授予记录是否已经存在
				include_once(JIEQI_ROOT_PATH.'/modules/badge/class/award.php');
				$award_handler =& JieqiAwardHandler::getInstance('JieqiAwardHandler');
				$criteria=new CriteriaCompo(new Criteria('toid', $confer_userid));
				$criteria->add(new Criteria('badgeid', $badge_id));
				$award_handler->queryObjects($criteria);
				$award=$award_handler->getObject();
				if(is_object($award)) jieqi_printfail($jieqiLang['g']['award_already_exists']);
				
				//增加授予记录
				$newAward = $award_handler->create();
				$newAward->setVar('addtime', JIEQI_NOW_TIME);
				$newAward->setVar('fromid', $_SESSION['jieqiUserId']);
				$newAward->setVar('fromname', $_SESSION['jieqiUserName']);
				$newAward->setVar('toid', $confer_userid);
				$newAward->setVar('toname', $confer_username);
				$newAward->setVar('badgeid', $badge_id);
				if (!$award_handler->insert($newAward)) jieqi_printfail($jieqiLang['g']['award_add_failure']);
				else {
					$badge->setVar('usenum', $badge->getVar('usenum','n')+1);
					$badge_handler->insert($badge);
					//更新用户徽章信息，用户徽章最多保留前面几个，存在otherinfo字段
					include_once(JIEQI_ROOT_PATH.'/modules/badge/include/badgefunction.php');
					upuserbadge($confer_userid);
					
					//jieqi_jumppage(JIEQI_URL.'/modules/badge/admin/badgeaward.php?userid='.$confer_userid, LANG_DO_SUCCESS, $jieqiLang['badge']['award_add_success']);
					jieqi_jumppage("?g=$gid&page=$_REQUEST[page]",LANG_DO_SUCCESS,$jieqiLang['g']['give_badge_success']);
				}
			}else{
				jieqi_printfail($errtext);
			}
		
		}
		if($_REQUEST['bbadge']){
			jieqi_includedb();
			$award_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
			//处理撤销徽章
			if($uid){
				$criteria=new CriteriaCompo();
				$criteria->setTables(jieqi_dbprefix('badge_award').' a LEFT JOIN '.jieqi_dbprefix('badge_badge').' b ON a.badgeid=b.badgeid');
				$criteria->add(new Criteria('b.btypeid',2010));
				$criteria->add(new Criteria('b.linkid',$gid));
				$criteria->add(new Criteria('a.toid',$uid) );
				$criteria->setLimit(1);
				$award_query->queryObjects($criteria);
				if($award = $award_query->getObject()){
					//删除授予记录
					$sql="DELETE FROM ".jieqi_dbprefix('badge_award'." WHERE awardid=".intval($award->getVar('awardid', 'n')));
					$award_query->execute($sql);
					//徽章取回后授予数量减一
					$sql="UPDATE ".jieqi_dbprefix('badge_badge'." SET usenum=usenum-1 WHERE badgeid=".intval($award->getVar('badgeid', 'n')));
					$award_query->execute($sql);
					//更新用户徽章信息，用户徽章最多保留前面几个，存在otherinfo字段
					include_once(JIEQI_ROOT_PATH.'/modules/badge/include/badgefunction.php');
					upuserbadge($uid);
				}else{
				}
			}
			jieqi_jumppage("?g=$gid&page=$_REQUEST[page]",LANG_DO_SUCCESS,$jieqiLang['g']['operate_success']);
		}
	}
}


if($admingid == 1){
	if ($_REQUEST['mgid'] ){
	        $arrtmp = array('membergid'=>intval($_REQUEST['mgid']),'mswitch'=>1 );
		if($member_handler->updatefields($arrtmp,$criteria) ){
		   jieqi_jumppage("?g=$gid&page=$_REQUEST[page]",LANG_DO_SUCCESS,$jieqiLang['g']['operate_success']);
		}	
	}	
}



//查询当前用户
$criteria = new CriteriaCompo(new Criteria('gid',$gid) );
$criteria->setTables(jieqi_dbprefix('group_member').' m left join '.jieqi_dbprefix('system_users').' u on m.uid=u.uid '  );

if($_REQUEST['membergid']){
	$criteria->add( new Criteria('membergid',intval($_REQUEST['membergid']) ) );
}

if($_REQUEST['mswitch'] == 'n') {
	$criteria->add( new Criteria('mswitch',0 ) );
}

if($_REQUEST['mquite'] == 'y') {
	$criteria->add( new Criteria('mquite',1 ) );
}

$countmembers = $member_query->getCount($criteria);
$criteria->setStart( ($_REQUEST['page']-1)*$onepage );
$criteria->setLimit($onepage);
$member_query->queryObjects($criteria);
$members = array();
$k = 0;
include_once(JIEQI_ROOT_PATH.'/configs/'.JIEQI_MODULE_NAME.'/membergroups.php'); 
while($v = $member_query->getObject() ){
	$members[$k]['uid'] = $uid  = $v->getVar('uid');
	if( $members[$k]['uid'] ){
		$members[$k]['groupname']=$jieqiGroups[$v->getVar('groupid')];
		$members[$k]['regdate']=$v->getVar('regdate');
		$username = $v->getVar('name')?$v->getVar('name'):$v->getVar('uname');	
		$members[$k]['username']="<a href='".JIEQI_URL."/userinfo.php?id=".$v->getVar('uid')."'>".$username."</a>";
		$members[$k]['experience']=$v->getVar('experience');
		$members[$k]['score']=$v->getVar('score');
		$members[$k]['sign']=$v->getVar('sign');
		$members[$k]['avatar']=$v->getVar('avatar');
		$honorid=intval(jieqi_gethonorid($v->getVar('score'), $jieqiHonors));
		$members[$k]['honor']=isset($jieqiHonors[$honorid]['name'][intval($v->getVar('workid', 'n'))]) ? $jieqiHonors[$honorid]['name'][intval($v->getVar('workid', 'n'))] : $jieqiHonors[$honorid]['caption'];

		//头像图片
		if($members[$k]['avatar'] > 0){
			$members[$k]['avatarurl']=jieqi_uploadurl($jieqiConfigs['system']['avatardir'], '', 'system').jieqi_getsubdir($members[$k]['uid']).'/'.$members[$k]['uid'].$jieqi_image_type[$members[$k]['avatar']];
		}else{
			$members[$k]['avatarurl']='';
		}
		//徽章图片
		if(JIEQI_USE_BADGE){
			//等级徽章
			$members[$k]['groupurl']=getbadgeurl(1, $v->getVar('groupid'), 0);
			//头衔徽章
			$members[$k]['honorurl']=getbadgeurl(2, $honorid, 0);
			//自定义徽章
			$badgeary=unserialize($v->getVar('badges', 'n'));
			$members[$k]['badgerows']=array();
			if(is_array($badgeary)){
				$m=0;		
				foreach($badgeary as $badge){
				if($badge['linkid']==$gid){
					$members[$k]['badgerows'][$m]['imageurl']=getbadgeurl($badge['btypeid'], $badge['linkid'], $badge['imagetype']);
					$members[$k]['badgerows'][$m]['caption']=jieqi_htmlstr($badge['caption']);
					$members[$k]['badgerows'][$m]['linkid']=$badge['linkid'];
					$members[$k]['badgerows'][$m]['btypeid']=$badge['btypeid'];
					if(!$members[$k]['own_badge']){
						$members[$k]['own_badge'] = $badge['btypeid'] == 2010 && $badge['linkid']==$gid ?1:0;
					}
					$m++;
				}
				}
			}
		}
	}
	$members[$k]['mtime'] = date("Y-m-d H:i",$v->getVar('mtime') );
	$members[$k]['offer'] = $v->getVar('offer');
	$membergid = $v->getVar('membergid');
	$tmp = $v->getVar('mswitch');
 	if( ($v->getVar('mswitch'))!=0  ){
		$members[$k]['membergtitle'] = $membergroups[$membergid];
	} else {
		$members[$k]['membergtitle'] = $jieqiLang['g']['wait_audit'];
	}
	if($allowmanmember){
	    if($guid!=$uid){
		   $member_out = "&nbsp;<a href='?g=$gid&page=$_REQUEST[page]&uid=$uid&out=1'>".$jieqiLang['g']['please_out']."</a>";
		} else {
		   $member_out = '';
		}
		$members[$k]['man_href'] = $jieqiLang['g']['manage'].":<a href='?g=$gid&page=$_REQUEST[page]&uid=$uid&mswitch=1'>".$jieqiLang['g']['membergtitle']."</a>".$member_out."&nbsp".manager_href($uid,$v->getVar('mswitch'),$members[$k]['own_badge']);
	}
	$k++;
}

$jieqiTpl->assign('members',$members);

$query_href = query_href();
$jieqiTpl->assign('query_href',$query_href);


include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($countmembers,$onepage,$_REQUEST['page']);
$jieqiTpl->assign('jumppage',$jumppage->whole_bar() );
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/member.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');

function manager_href($uid,$mswitch,$own_badge) { 
	global $admingid;	
	global $gid,$guid;
	global $allowmanmember;
	global $membergid;
	global $jieqiLang;
	global $badge_is_exists,$badge_maxnum,$badge_usenum;
	if( $allowmanmember == 1) {
		$string = '';
		global $membergroups;
		if(is_array($membergroups) )  {
		  if($guid!=$uid){
			foreach($membergroups as $mgid=>$mgtitle ) {
					if($mgid == $membergid && $mswitch==1){
						$mgtitle = "<font color=red>".$mgtitle."</font>";
					}
					if($mgid != 1 ){
						$string .= "<a href='?g=$gid&page=$_REQUEST[page]&mgid=$mgid&uid=$uid'>$mgtitle</a>&nbsp;";		
					}	
			}
		  }
			if($own_badge){
				$string.="<a href='?g=$gid&page=$_REQUEST[page]&bbadge=1&uid=$uid'>".$jieqiLang['g']['back_badge']."</a>";
			}else{
	           if(!$badge_is_exists || ($badge_maxnum>0 && $badge_usenum>=$badge_maxnum)) return $string;
				$string.="<a href='?g=$gid&page=$_REQUEST[page]&gbadge=1&uid=$uid'>".$jieqiLang['g']['give_badge']."</a>";
			}
		}	
		return $string;
	} else {
		return "";
	}
}


function query_href( ) { 	
	global $gid;
	global $membergroups;
	global $jieqiLang;
	if(is_array($membergroups) )  {
	foreach($membergroups as $mgid=>$mgtitle ) {
			$string .= "<a href='?g=$gid&membergid=$mgid'>$mgtitle</a>&nbsp;";
		}	
	}
	return $string."<a href='?g=$gid&mswitch=n'>".$jieqiLang['g']['wait_for_join']."</a>"."&nbsp;<a href='?g=$gid&mquite=y'>".$jieqiLang['g']['wait_for_out']."</a>".'';
}



function upuserbadge_2($uid){
	global $jieqiConfigs;
	$uid=intval($uid);
	$jieqiConfigs['badge']['userbadgenum']=intval($jieqiConfigs['badge']['userbadgenum']);
	if($jieqiConfigs['badge']['userbadgenum'] <= 0) return true;	
	jieqi_includedb();
	$award_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	$infoary=array();
	$criteria=new CriteriaCompo();
	$criteria->setTables(jieqi_dbprefix('badge_award').' a LEFT JOIN '.jieqi_dbprefix('badge_badge').' b ON a.badgeid=b.badgeid');
	$criteria->add(new Criteria('a.toid', $uid));
	$criteria->setSort('b.btypeid ASC, a.awardid');
	$criteria->setOrder('ASC');
	$criteria->setLimit($jieqiConfigs['badge']['userbadgenum']);
	$award_query->queryObjects($criteria);
	$k=0;
	while($award = $award_query->getObject()){
		$infoary[$k]['btypeid']=$award->getVar('btypeid');
		$infoary[$k]['linkid']=$award->getVar('linkid');
		$infoary[$k]['imagetype']=$award->getVar('imagetype');
		$infoary[$k]['caption']=$award->getVar('caption');
		$k++;
	}
	$otherinfo=serialize($infoary);
	$sql="UPDATE ".jieqi_dbprefix('system_users')." SET badges='".jieqi_dbslashes(serialize($infoary))."' WHERE uid=".$uid;
	$award_query->execute($sql);	
}
?>