<?php 
/**
 * 电子书书架
 *
 * 电子书书架
 * 
 * 调用模板：/modules/obook/templates/obookcase.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obookcase.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
jieqi_checklogin();
jieqi_loadlang('bookcase', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/header.php');
jieqi_getconfigs('obook', 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
$jieqiTpl->assign('obook_static_url',$obook_static_url);
$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);

if(!empty($_REQUEST['delid'])){
    include_once($jieqiModules['obook']['path'].'/class/obookcase.php');
    $obookcase_handler =& JieqiObookcaseHandler::getInstance('JieqiObookcaseHandler');	
    $obookcase=$obookcase_handler->get($_REQUEST['delid']);
    if(is_object($obookcase)){
        if($obookcase->getVar('userid')==$_SESSION['jieqiUserId']){
            include_once($jieqiModules['obook']['path'].'/class/obook.php');
	        $obook_handler =& JieqiobookHandler::getInstance('JieqiobookHandler');
	        $obook_handler->db->query('UPDATE '.jieqi_dbprefix('obook_obook').' SET goodnum=goodnum-1 WHERE obookid='.$obookcase->getVar('obookid', 'n'));
	        $obookcase_handler->delete($_REQUEST['delid']);	
        }	
    }
    unset($obookcase);
}
//最大收藏数
$maxnum=$jieqiConfigs['obook']['bookcasenum'];

$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');
jieqi_includedb();
$obookcase_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria=new CriteriaCompo(new Criteria('c.userid', $_SESSION['jieqiUserId']));
$criteria->setTables(jieqi_dbprefix('obook_obookcase').' c LEFT JOIN '.jieqi_dbprefix('obook_obook').' a ON c.obookid=a.obookid');
$criteria->setFields('c.*, a.obookid, a.articleid, a.lastupdate, a.obookname, a.lastchapterid, a.lastchapter');
$criteria->setSort('a.lastupdate');
$criteria->setOrder('DESC');
$obookcase_query->queryObjects($criteria);
unset($criteria);
$obookcaserows=array();
$k=0;
while($v = $obookcase_query->getObject()){
	$obookcaserows[$k]['ocaseid']=$v->getVar('ocaseid');
	$obookcaserows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('oocaseid').'">';
	$tmpvar=$v->getVar('obookname');
	if(!empty($tmpvar)) {
		$obookcaserows[$k]['url_obookinfo']=$obook_dynamic_url.'/readbookcase.php?oid='.$v->getVar('obookid').'&bid='.$v->getVar('ocaseid');
		$obookcaserows[$k]['url_index']=$obookcaserows[$k]['url_obookinfo'].'&indexflag=1';
		$obookcaserows[$k]['obookname']=$v->getVar('obookname');
	}else{
		$obookcaserows[$k]['url_obookinfo']='#';
		$obookcaserows[$k]['url_index']='#';
		$obookcaserows[$k]['obookname']=$jieqiLang['obook']['obookmark_has_deleted'];
	}
	
	if($v->getVar('lastchapter')==''){
        $obookcaserows[$k]['lastchapter']='';
        $obookcaserows[$k]['url_lastchapter']='#';
    }else{
        $obookcaserows[$k]['lastchapter']=$v->getVar('lastchapter');  
        $obookcaserows[$k]['url_lastchapter']=$obook_dynamic_url.'/readbookcase.php?oid='.$v->getVar('obookid').'&bid='.$v->getVar('ocaseid').'&cid='.$v->getVar('lastchapterid');  
    }
    if($v->getVar('lastupdate')>$v->getVar('lastvisit')) $obookcaserows[$k]['hasnew']=1;
    else $obookcaserows[$k]['hasnew']=0;
    
    if($v->getVar('chaptername')==''){
        $obookcaserows[$k]['obookmark']='';
        $obookcaserows[$k]['url_obookmark']='#';
    }else{
    	$obookcaserows[$k]['obookmark']=$v->getVar('chaptername');
    	$obookcaserows[$k]['url_obookmark']=$obook_dynamic_url.'/readbookcase.php?oid='.$v->getVar('obookid').'&bid='.$v->getVar('ocaseid').'&cid='.$v->getVar('ochapterid');
    }
	$obookcaserows[$k]['lastupdate']=date(JIEQI_DATE_FORMAT, $v->getVar('lastupdate'));
	$obookcaserows[$k]['url_delete']=jieqi_addurlvars(array('delid'=>$v->getVar('ocaseid')));
	$k++;
}
$jieqiTpl->assign('bookcaserows', $obookcaserows);
$jieqiTpl->assign('maxbookcase', $maxnum);
$jieqiTpl->assign('nowbookcase', count($obookcaserows));
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/obookcase.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>