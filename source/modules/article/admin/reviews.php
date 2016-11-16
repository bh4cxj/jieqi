<?php 
/**
 * 后台书评管理
 *
 * 显示最近书评列表
 * 
 * 调用模板：/modules/article/templates/admin/reviews.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: reviews.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['article']['manageallreview'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
include_once($jieqiModules['article']['path'].'/class/reviews.php');
$reviews_handler =& JieqiReviewsHandler::getInstance('JieqiReviewsHandler');

$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
//处理置顶、加精、删除
if(isset($_REQUEST['action']) && !empty($_REQUEST['rid'])){
	$actreview=$reviews_handler->get($_REQUEST['rid']);
	if(is_object($actreview)){
		$criteria=new CriteriaCompo(new Criteria('topicid', $_REQUEST['rid']));
		switch($_REQUEST['action']){
			case 'top':
			$reviews_handler->updatefields(array('istop'=>1), $criteria);
			break;
			case 'untop':
			$reviews_handler->updatefields(array('istop'=>0), $criteria);
			break;
			case 'good':
			$reviews_handler->updatefields(array('isgood'=>1), $criteria);
			//精华积分
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			if($actreview->getVar('userid') == $_SESSION['jieqiUserId']){
				$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scoregoodreview'], true);
			}else{
				$users_handler->changeScore($actreview->getVar('userid'), $jieqiConfigs['article']['scoregoodreview'], true);
			}
			break;
			case 'normal':
			if($actreview->getVar('isgood')==1){
				$reviews_handler->updatefields(array('isgood'=>0), $criteria);
				//精华积分
				include_once(JIEQI_ROOT_PATH.'/class/users.php');
				$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
				if($actreview->getVar('userid') == $_SESSION['jieqiUserId']){
					$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scoregoodreview'], false);
				}else{
					$users_handler->changeScore($actreview->getVar('userid'), $jieqiConfigs['article']['scoregoodreview'], false);
				}
			}

			break;
			case 'del':
			$reviews_handler->delete($criteria);
			$query->execute("DELETE FROM ".jieqi_dbprefix('article_replies')." WHERE topicid=".intval($_REQUEST['rid']));
			//删除书评减少积分
			/*
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			if($actreview->getVar('userid') == $_SESSION['jieqiUserId']){
				$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scorereview'], false);
			}else{
				$users_handler->changeScore($actreview->getVar('userid'), $jieqiConfigs['article']['scorereview'], false);
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
		$sql='DELETE FROM '.jieqi_dbprefix('article_reviews').' WHERE topicid IN ('.$where.')';
		$query->execute($sql);
		$sql='DELETE FROM '.jieqi_dbprefix('article_replies').' WHERE topicid IN ('.$where.')';
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
			$where.=$reviews_handler->autoid.'='.$v;
		}
	}
	if(!empty($where)){
		$sql='SELECT topicid, userid FROM '.jieqi_dbprefix('article_review').' WHERE '.$where;
		$res=$reviews_handler->db->query($sql);
		while($actreview = $reviews_handler->getObject($res)){
		//删除书评减少积分
			if($actreview->getVar('userid') == $_SESSION['jieqiUserId']){
				$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scorereview'], false);
			}else{
				$users_handler->changeScore($actreview->getVar('userid'), $jieqiConfigs['article']['scorereview'], false);
			}
		}
		$sql='DELETE FROM '.jieqi_dbprefix('article_review').' WHERE '.$where;
		$reviews_handler->db->query($sql);
	}
*/
}
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
$jieqiTpl->assign('url_review', $article_dynamic_url.'/admin/reviews.php');
$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');

$criteria=new CriteriaCompo();
$criteria->setFields("r.*,a.articlename");
$criteria->setTables(jieqi_dbprefix('article_reviews')." AS r LEFT JOIN ".jieqi_dbprefix('article_article')." AS a ON r.ownerid=a.articleid");
if(!empty($_REQUEST['keyword'])){
	$_REQUEST['keyword']=trim($_REQUEST['keyword']);
	if($_REQUEST['keytype']==1) $criteria->add(new Criteria('r.poster', $_REQUEST['keyword'],'='));
	else $criteria->add(new Criteria('a.articlename', $_REQUEST['keyword'],'='));
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
$criteria->setLimit($jieqiConfigs['article']['reviewnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['article']['reviewnum']);
$query->queryObjects($criteria);
$reviewrows=array();
$k=0;
while($v = $query->getObject()){
	$start=3;
	if($v->getVar('istop')==1) {
		$reviewrows[$k]['istop']=1;
		$start+=4;
	}else{
		$reviewrows[$k]['istop']=0;
	}
	if($v->getVar('isgood')==1) {
		$reviewrows[$k]['isgood']=1;
		$start+=4;
	}else{
		$reviewrows[$k]['isgood']=0;
	}
	$reviewrows[$k]['topicid']=$v->getVar('topicid');
	$reviewrows[$k]['posttime']=$v->getVar('posttime');
	$reviewrows[$k]['posterid']=$v->getVar('posterid');
	$reviewrows[$k]['poster']=$v->getVar('poster');
	$reviewrows[$k]['title']=$v->getVar('title');
	$reviewrows[$k]['views']=$v->getVar('views');
	$reviewrows[$k]['replies']=$v->getVar('replies');
	
	$reviewrows[$k]['url_top']=jieqi_addurlvars(array('action'=>'top', 'rid'=>$v->getVar('topicid')));
	$reviewrows[$k]['url_untop']=jieqi_addurlvars(array('action'=>'untop', 'rid'=>$v->getVar('topicid')));
	$reviewrows[$k]['url_good']=jieqi_addurlvars(array('action'=>'good', 'rid'=>$v->getVar('topicid')));
	$reviewrows[$k]['url_normal']=jieqi_addurlvars(array('action'=>'normal', 'rid'=>$v->getVar('topicid')));
	$reviewrows[$k]['url_delete']=jieqi_addurlvars(array('action'=>'del', 'rid'=>$v->getVar('topicid')));
	$reviewrows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('topicid').'">';
	$reviewrows[$k]['ownerid']=$v->getVar('ownerid');
	$reviewrows[$k]['articlename']=$v->getVar('articlename');
	$reviewrows[$k]['url_articleinfo']=jieqi_geturl('article', 'article', $v->getVar('ownerid'), 'info');

	$k++;
}
$jieqiTpl->assign_by_ref('reviewrows', $reviewrows);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($query->getCount($criteria),$jieqiConfigs['article']['reviewnum'],$_REQUEST['page']);
$jumppage->setlink('', true, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/reviews.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>