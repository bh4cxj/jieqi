<?php 
/**
 * 作家草稿箱
 *
 * 显示草稿列表
 * 
 * 调用模板：/modules/article/templates/draft.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: draft.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
jieqi_getconfigs('article', 'power');
jieqi_checkpower($jieqiPower['article']['newdraft'], $jieqiUsersStatus, $jieqiUsersGroup, false);
jieqi_getconfigs('article', 'configs');
include_once(JIEQI_ROOT_PATH.'/header.php');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');
include_once($jieqiModules['article']['path'].'/class/draft.php');
$draft_handler=& JieqiDraftHandler::getInstance('JieqiDraftHandler');
if(!empty($_REQUEST['delid'])){
	$criteria=new CriteriaCompo(new Criteria('draftid', $_REQUEST['delid']));
    $criteria->add(new Criteria('posterid', $_SESSION['jieqiUserId']));
	$draft_handler->delete($criteria);
	unset($criteria);
}
$criteria=new CriteriaCompo(new Criteria('posterid', $_SESSION['jieqiUserId']));
$criteria->setSort('draftid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['article']['draftnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['article']['pagenum']);
$draft_handler->queryObjects($criteria);
$draftrows=array();
$k=0;
while($v = $draft_handler->getObject()){
	$draftrows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('draftid').'">';
	$draftrows[$k]['articleid']=$v->getVar('articleid');
	$draftrows[$k]['articlename']=$v->getVar('articlename');
	$draftrows[$k]['draftid']=$v->getVar('draftid');
	$draftrows[$k]['draftname']=$v->getVar('draftname');
	$draftrows[$k]['url_delete']=jieqi_addurlvars(array('delid'=>$v->getVar('draftid')));
	$k++;
}

$jieqiTpl->assign_by_ref('draftrows', $draftrows);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');

$jumppage = new JieqiPage($draft_handler->getCount($criteria),$jieqiConfigs['article']['draftnum'],$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->assign('authorarea', 1);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/draft.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>