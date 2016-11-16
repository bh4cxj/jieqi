<?php 
/**
 * 批量清理文章章节
 *
 * 批量清理文章章节
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: batchclean.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_USE_GZIP','0');
define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//管理别人文章权限
jieqi_checkpower($jieqiPower['article']['delallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

if(isset($_REQUEST['action']) && $_REQUEST['action']=='clean'){
	jieqi_includedb();
	$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	$where = '';
	$badparm = false;
	

	//判断条件一
	if(is_numeric($_POST['startid']) && is_numeric($_POST['stopid'])){
		$_POST['startid'] = intval($_POST['startid']);
		$_POST['stopid'] = intval($_POST['stopid']);
		if($where != '') $where .= " AND";
		if($_POST['stopid'] >= $_POST['startid']){
			$where .= " articleid >= ".$_POST['startid']." AND articleid <= ".$_POST['stopid'];
		}else{
			$where .= " articleid >= ".$_POST['stopid']." AND articleid <= ".$_POST['startid'];
		}
	}elseif(!empty($_POST['startid']) || !empty($_POST['stopid'])){
		$badparm = true;
	}

	//判断条件二
	if(!empty($_POST['upday']) && is_numeric($_POST['upday'])){
		if($where != '') $where .= " AND";
		if($_POST['upflag'] == 1){
			$where .= " lastupdate >= ".(JIEQI_NOW_TIME - (floatval($_POST['upday']) * 3600 * 24));
		}else{
			$where .= " lastupdate < ".(JIEQI_NOW_TIME - (floatval($_POST['upday']) * 3600 * 24));
		}
	}elseif(!empty($_POST['upday'])){
		$badparm = true;
	}

	//判断条件三
	if(!empty($_POST['visitnum']) && is_numeric($_POST['visitnum'])){
		$_POST['visitnum'] = intval($_POST['visitnum']);
		$fieldary = array('allvisit', 'monthvisit', 'weekvisit', 'allvote', 'monthvote', 'weekvote');
		if(in_array($_POST['visittype'], $fieldary)){
			if($where != '') $where .= " AND";
			if($_POST['visitflag'] == 1){
				$where .= " ".$_POST['visittype']." > ".$_POST['visitnum'];
			}else{
				$where .= " ".$_POST['visittype']." < ".$_POST['visitnum'];
			}
		}
	}elseif(!empty($_POST['visitnum'])){
		$badparm = true;
	}

	//判断条件四
	if($_POST['authorflag'] == 1){
		if($where != '') $where .= " AND";
		$where .= " authorid > 0";
	}elseif($_POST['authorflag'] == 2){
		if($where != '') $where .= " AND";
		$where .= " authorid = 0";
	}

	//判断条件五
	if(!empty($_POST['articles'])){
		if($_POST['idname'] == 0){
			//按照文章id
			$_POST['articles'] = trim($_POST['articles']);
			$aidary = explode(',', $_POST['articles']);
			$aidstr = '';
			foreach ($aidary as $aid){
				$aid = intval(trim($aid));
				if($aid){
					if($aidstr != '') $aidstr .= ',';
					$aidstr .= $aid;
				}
			}
			if($aidstr != ''){
				if($where != '') $where .= " AND";
				$where .= " articleid IN (".$aidstr.")";
			}else{
				$badparm = true;
			}
		}else{
			//按照文章名
			$_POST['articles'] = trim($_POST['articles']);
			$anameary = explode("\n", $_POST['articles']);
			$anamestr = '';
			foreach ($anameary as $aname){
				$aname = trim($aname);
				if(!empty($aname)){
					if($anamestr != '') $anamestr .= ',';
					$anamestr .= "'".jieqi_dbslashes($aname)."'";
				}
			}
			if($anamestr != ''){
				if($where != '') $where .= " AND";
				$where .= " articlename IN (".$anamestr.")";
			}else{
				$badparm = true;
			}
		}
	}

	//处理类型
	if(!in_array($_POST['operate'], array('delarticle', 'delchapter', 'delattach'))) $badparm = true;
	
	

	if($badparm) jieqi_printfail($jieqiLang['article']['clean_bad_parm']);

	//开始处理
	@set_time_limit(0);
	@session_write_close();
	header('Content-Type:text/html; charset='.JIEQI_CHAR_SET);
	header("Cache-Control:no-cache");
	echo '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              ';
	echo sprintf($jieqiLang['article']['clean_show_start'], $jieqiLang['article']['clean_operate_'.$_POST['operate']]);
	ob_flush();
	flush();
	if($where == '') $where = '1';
	$sql = "SELECT articleid FROM ".jieqi_dbprefix('article_article')." WHERE ".$where;
	$res = $query->execute($sql);
	$resnum = $query->db->getRowsNum($res);
	echo sprintf($jieqiLang['article']['clean_show_num'], $resnum);
	ob_flush();
	flush();
	include_once($jieqiModules['article']['path'].'/include/operatefunction.php');

	if(!in_array($_POST['operate'], array('delarticle', 'delchapter', 'delattach'))) $badparm = true;


	$criteria = new CriteriaCompo(new Criteria('attachment', '', '!='));
	while($row = $query->getRow()){
		if($_POST['operate'] == 'delarticle'){
			$ret = jieqi_article_delete($row['articleid'], false);
		}elseif($_POST['operate'] == 'delchapter'){
			$ret = jieqi_article_clean($row['articleid'], false);
		}elseif($_POST['operate'] == 'delattach'){
			$ret = jieqi_article_delchapter($row['articleid'], $criteria, false);
		}
		if(is_object($ret)){
			echo sprintf($jieqiLang['article']['clean_article_doing'], $ret->getVar('articlename'), $ret->getVar('articleid'));
			ob_flush();
			flush();
		}
	}

	echo $jieqiLang['article']['clean_all_success'];
	ob_flush();
	flush();
}else{
	include_once(JIEQI_ROOT_PATH.'/admin/header.php');
	$jieqiTpl->assign('article_static_url',$article_static_url);
	$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

	$jieqiTpl->assign('url_batchclean', $article_static_url.'/admin/batchclean.php?do=submit');
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/batchclean.html';
	include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
}

?>