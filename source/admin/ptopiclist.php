<?php 
/**
 * 个人会客室帖子管理
 *
 * 个人会客室帖子管理
 * 
 * 调用模板：/templates/admin/ptopiclist.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ptopiclist.php 328 2009-02-06 09:24:29Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['system']['manageallparlor'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');

include_once(JIEQI_ROOT_PATH.'/class/ptopics.php');
$ptopics_handler =& JieqiPtopicsHandler::getInstance('JieqiPtopicsHandler');

$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
//处理置顶、加精、删除
if(isset($_REQUEST['action']) && !empty($_REQUEST['tid'])){
	$actptopic=$ptopics_handler->get($_REQUEST['tid']);
	if(is_object($actptopic)){
		$criteria=new CriteriaCompo(new Criteria('topicid', $_REQUEST['tid']));
		switch($_REQUEST['action']){
			case 'top':
			$ptopics_handler->updatefields(array('istop'=>1), $criteria);
			break;
			case 'untop':
			$ptopics_handler->updatefields(array('istop'=>0), $criteria);
			break;
			case 'good':
			$ptopics_handler->updatefields(array('isgood'=>1), $criteria);
			break;
			case 'normal':
			$ptopics_handler->updatefields(array('isgood'=>0), $criteria);
			case 'del':
			$ptopics_handler->delete($criteria);
			$query->execute("DELETE FROM ".jieqi_dbprefix('system_pposts')." WHERE topicid=".intval($_REQUEST['tid']));
			//删除书评减少积分
			/*
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			if($actptopic->getVar('userid') == $_SESSION['jieqiUserId']){
				$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['system']['scorereview'], false);
			}else{
				$users_handler->changeScore($actptopic->getVar('userid'), $jieqiConfigs['system']['scorereview'], false);
			}
			*/
			break;
		}
		unset($criteria);
	}
}elseif(isset($_REQUEST['batchdel']) && $_REQUEST['batchdel']==1 && is_array($_REQUEST['checkid']) && count($_REQUEST['checkid'])>0){
	//批量删除
	$where='';
	foreach($_REQUEST['checkid'] as $v){
		if(is_numeric($v)){
			$v=intval($v);
			if(!empty($where)) $where.=', ';
			$where.= $v;
		}
	}
	if(!empty($where)){
		$sql='DELETE FROM '.jieqi_dbprefix('system_ptopics').' WHERE topicid IN ('.$where.')';
		$query->execute($sql);
		$sql='DELETE FROM '.jieqi_dbprefix('system_pposts').' WHERE topicid IN ('.$where.')';
		$query->execute($sql);
	}
/*
	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	$where='';
	foreach($_REQUEST['checkid'] as $v){
		if(is_numeric($v)){
			$v=intval($v);
			if(!empty($where)) $where.=' OR ';
			$where.=$ptopics_handler->autoid.'='.$v;
		}
	}
	if(!empty($where)){
		$sql='SELECT topicid, userid FROM '.jieqi_dbprefix('article_review').' WHERE '.$where;
		$res=$ptopics_handler->db->query($sql);
		while($actptopic = $ptopics_handler->getObject($res)){
		//删除书评减少积分
			if($actptopic->getVar('userid') == $_SESSION['jieqiUserId']){
				$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['system']['scorereview'], false);
			}else{
				$users_handler->changeScore($actptopic->getVar('userid'), $jieqiConfigs['system']['scorereview'], false);
			}
		}
		$sql='DELETE FROM '.jieqi_dbprefix('article_review').' WHERE '.$where;
		$ptopics_handler->db->query($sql);
	}
*/
}
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$criteria=new CriteriaCompo();
$criteria->setFields("t.*,u.uname,u.name");
$criteria->setTables(jieqi_dbprefix('system_ptopics')." AS t LEFT JOIN ".jieqi_dbprefix('system_users')." AS u ON t.ownerid=u.uid");
if(!empty($_REQUEST['keyword'])){
	$_REQUEST['keyword']=trim($_REQUEST['keyword']);
	if($_REQUEST['keytype']==1) $criteria->add(new Criteria('t.poster', $_REQUEST['keyword'],'='));
	elseif($_REQUEST['keytype']==2) $criteria->add(new Criteria('t.title', '%'.$_REQUEST['keyword'].'%','like'));
	else $criteria->add(new Criteria('u.uname', $_REQUEST['keyword'],'='));
}

if(isset($_REQUEST['type']) && $_REQUEST['type']=='good'){
	//精华书评
	$criteria->add(new Criteria('isgood', 1));
}else{
	$_REQUEST['type']='all';
}

//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$criteria->setSort('topicid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['system']['ptopicpnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['system']['ptopicpnum']);
$query->queryObjects($criteria);
$ptopicrows=array();
$k=0;
while($v = $query->getObject()){
	$start=3;
	if($v->getVar('istop')==1) {
		$ptopicrows[$k]['istop']=1;
		$start+=4;
	}else{
		$ptopicrows[$k]['istop']=0;
	}
	if($v->getVar('isgood')==1) {
		$ptopicrows[$k]['isgood']=1;
		$start+=4;
	}else{
		$ptopicrows[$k]['isgood']=0;
	}
	$ptopicrows[$k]['topicid']=$v->getVar('topicid');
	$ptopicrows[$k]['posttime']=$v->getVar('posttime');
	$ptopicrows[$k]['posterid']=$v->getVar('posterid');
	$ptopicrows[$k]['poster']=$v->getVar('poster');
	$ptopicrows[$k]['title']=$v->getVar('title');
	$ptopicrows[$k]['views']=$v->getVar('views');
	$ptopicrows[$k]['replies']=$v->getVar('replies');
	
	$ptopicrows[$k]['url_top']=jieqi_addurlvars(array('action'=>'top', 'rid'=>$v->getVar('topicid')));
	$ptopicrows[$k]['url_untop']=jieqi_addurlvars(array('action'=>'untop', 'rid'=>$v->getVar('topicid')));
	$ptopicrows[$k]['url_good']=jieqi_addurlvars(array('action'=>'good', 'rid'=>$v->getVar('topicid')));
	$ptopicrows[$k]['url_normal']=jieqi_addurlvars(array('action'=>'normal', 'rid'=>$v->getVar('topicid')));
	$ptopicrows[$k]['url_delete']=jieqi_addurlvars(array('action'=>'del', 'rid'=>$v->getVar('topicid')));
	$ptopicrows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('topicid').'">';
	$ptopicrows[$k]['articleid']=$v->getVar('articleid');
	$ptopicrows[$k]['articlename']=$v->getVar('articlename');
	$ptopicrows[$k]['ownerid']=$v->getVar('ownerid');
	$ptopicrows[$k]['ownername']=strlen($v->getVar('name'))==0 ? $v->getVar('uname') : $v->getVar('name');
	$k++;
}
$jieqiTpl->assign_by_ref('ptopicrows', $ptopicrows);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($query->getCount($criteria),$jieqiConfigs['system']['ptopicpnum'],$_REQUEST['page']);
$jumppage->setlink('', true, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/ptopiclist.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>