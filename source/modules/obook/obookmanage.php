<?php 
/**
 * 电子书管理
 *
 * 管理一本电子书
 * 
 * 调用模板：/modules/obook/templates/obookmanage.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obookmanage.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
//<!--jieqi insert check code-->
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['id']=intval($_REQUEST['id']);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
$obook=$obook_handler->get($_REQUEST['id']);
if(!$obook) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//管理别人电子书权限
$canedit=jieqi_checkpower($jieqiPower['obook']['manageallobook'], $jieqiUsersStatus, $jieqiUsersGroup, true);
$candel=$canedit;
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改电子书
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($obook->getVar('authorid')==$tmpvar || $obook->getVar('posterid')==$tmpvar || $obook->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}
if(!$canedit) jieqi_printfail($jieqiLang['obook']['noper_manage_obook']);
//包含区块参数
jieqi_getconfigs('obook', 'authorblocks', 'jieqiBlocks');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
include_once(JIEQI_ROOT_PATH.'/header.php');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
$jieqiTpl->assign('obook_static_url',$obook_static_url);
$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);

$cols=2;
$tdwidth=floor(100/$cols);
$obook_read_url=$obook_static_url.'/reader.php?aid='.$obook->getVar('obookid');

$obook_table='<table class="grid" width="100%"><tr><td colspan="'.$cols.'" align="center" class="title">《'.$obook->getVar('obookname').'》[<a href="'.$obook_dynamic_url.'/obookinfo.php?id='.$obook->getVar('obookid').'" target="_blank">'.$jieqiLang['obook']['obook_info'].'</a>]</td></tr>';
//$obook_table.='<tr><td colspan="'.$cols.'" align="center" class="head">[<a href="'.$obook_static_url.'/newvolume.php?aid='.$obook->getVar('obookid').'">新建分卷</a>] [<a href="'.$obook_static_url.'/newchapter.php?aid='.$obook->getVar('obookid').'">增加章节</a>] [<a href="'.$obook_static_url.'/obookedit.php?id='.$obook->getVar('obookid').'">编辑电子书</a>] [<a href="javascript:if(confirm(\'确实要删除该电子书么？\')) document.location=\''.$obook_static_url.'/obookset.php?id='.$obook->getVar('obookid').'&action=delete\';">删除电子书</a>]</td></tr>';
$obook_table.='<tr><td colspan="'.$cols.'" align="center" class="head">[<a href="'.$obook_static_url.'/newchapter.php?aid='.$obook->getVar('obookid').'">'.$jieqiLang['obook']['new_chapter'].'</a>] [<a href="'.$obook_static_url.'/obookedit.php?id='.$obook->getVar('obookid').'">'.$jieqiLang['obook']['edit_obook'].'</a>] [<a href="javascript:if(confirm(\''.$jieqiLang['obook']['obook_unsale_confirm'].'\')) document.location=\''.$obook_static_url.'/obookset.php?id='.$obook->getVar('obookid').'&action=unsale\';">'.$jieqiLang['obook']['unsale_obook'].'</a>]';
if($candel || $obook->getVar('totalsale', 'n')==0) $obook_table.=' [<a href="javascript:if(confirm(\''.$jieqiLang['obook']['obook_delete_confirm'].'\')) document.location=\''.$obook_static_url.'/obookset.php?id='.$obook->getVar('obookid').'&action=delete\';">'.$jieqiLang['obook']['delete_obook'].'</a>]';
$obook_table.='</td></tr>';
include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
$chapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');
$criteria=new CriteriaCompo(new Criteria('obookid', $_REQUEST['id'], '='));
$criteria->setSort('chapterorder');
$criteria->setOrder('ASC');
$chapter_handler->queryObjects($criteria);
$i=0;
$chapterary=array();
$k=0;
while($chapter = $chapter_handler->getObject()){
	$chapterary[$k]['postdate']=$chapter->getVar('postdate', 'n');
	$chapterary[$k]['lastupdate']=$chapter->getVar('lastupdate', 'n');
	$chapterary[$k]['size']=$chapter->getVar('size', 'n');
	$chapterary[$k]['ochapterid']=$chapter->getVar('ochapterid', 'n');
	$chapterary[$k]['chapterorder']=$chapter->getVar('chapterorder', 'n');
	$chapterary[$k]['chaptertype']=$chapter->getVar('chaptertype', 'n');
	$chapterary[$k]['chaptername']=$chapter->getVar('chaptername', 'n');
	$chapterary[$k]['saleprice']=$chapter->getVar('saleprice', 'n');
	$chapterary[$k]['display']=$chapter->getVar('display', 'n');
	$k++;
	if($chapter->getVar('chaptertype')==0){
		//章节
		if($i==0) $obook_table.='<tr>';
		$obook_table.='<td width="'.$tdwidth.'%" class="odd">';
		$obook_table.='<a href="'.$obook_static_url.'/reader.php?aid='.$obook->getVar('obookid').'&cid='.$chapter->getVar('ochapterid').'" target="_blank">'.$chapter->getVar('chaptername').'</a>';
		$obook_table.=' <a href="'.$obook_static_url.'/chapteredit.php?id='.$chapter->getVar('ochapterid').'&chaptertype=0" title="'.$jieqiLang['obook']['manage_edit_tagnote'].'">'.$jieqiLang['obook']['manage_edit_tag'].'</a>';
		$chapterdisplay=$chapter->getVar('display', 'n');
		if(($chapterdisplay & 15)==0){
			$obook_table.='<a href="javascript:if(confirm(\''.$jieqiLang['obook']['chapter_unsale_confirm'].'\')) document.location=\''.$obook_static_url.'/chapterset.php?id='.$chapter->getVar('ochapterid').'&chaptertype=0&action=unsale\';" title="'.$jieqiLang['obook']['manage_unsale_tagnote'].'">'.$jieqiLang['obook']['manage_unsale_tag'].'</a>';
		}else{
			$obook_table.='<a href="javascript:if(confirm(\''.$jieqiLang['obook']['chapter_sale_confirm'].'\')) document.location=\''.$obook_static_url.'/chapterset.php?id='.$chapter->getVar('ochapterid').'&chaptertype=0&action=sale\';" title="'.$jieqiLang['obook']['manage_sale_tagnote'].'">'.$jieqiLang['obook']['manage_sale_tag'].'</a>';
		}
		if($candel || $chapter->getVar('totalsale', 'n')==0) $obook_table.='<a href="javascript:if(confirm(\''.$jieqiLang['obook']['chapter_delete_confirm'].'\')) document.location=\''.$obook_static_url.'/chapterset.php?id='.$chapter->getVar('ochapterid').'&chaptertype=0&action=delete\';" title="'.$jieqiLang['obook']['manage_delete_tagnote'].'">'.$jieqiLang['obook']['manage_delete_tag'].'</a>';
		//发布成公众章节
		$obook_table.='<a href="javascript:if(confirm(\''.$jieqiLang['obook']['chapter_publish_confirm'].'\')) document.location=\''.$obook_static_url.'/chapterset.php?id='.$chapter->getVar('ochapterid').'&chaptertype=0&action=publish\';" title="'.$jieqiLang['obook']['manage_publish_tagnote'].'">'.$jieqiLang['obook']['manage_publish_tag'].'</a>';
		
		$obook_table.='</td>';
		$i++;
	}else{
		//分卷
		if($i>0){
			for($j=$i; $j<$cols; $j++) $obook_table.='<td width="'.$tdwidth.'%" class="odd">&nbsp;</td>';
		}
		$obook_table.='<tr>';
		$obook_table.='<td colspan="'.$cols.'" class="even" align="center">'.$chapter->getVar('chaptername').' <a href="'.$obook_static_url.'/chapteredit.php?id='.$chapter->getVar('ochapterid').'&chaptertype=1">'.$jieqiLang['obook']['manage_edit_tag'].'</a>';
		$obook_table.='<a href="javascript:if(confirm(\''.$jieqiLang['obook']['volume_delete_confirm'].'\')) document.location=\''.$obook_static_url.'/chapterset.php?id='.$chapter->getVar('ochapterid').'&chaptertype=1&action=delete\';">'.$jieqiLang['obook']['manage_delete_tag'].'</a>';
		$obook_table.='</td>';
		$i=$cols;
	}
	if($i==$cols){
		$obook_table.='</tr>';
		$i=0;
	}
}
if($i>0){
	for($j=$i; $j<$cols; $j++) $obook_table.='<td width="'.$tdwidth.'%" class="odd">&nbsp;</td>';
	$obook_table.='</tr>';
}
$obook_table.='</table>';
$jieqiTpl->assign('obook_table', $obook_table);
//章节排序
include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
$sort_form = new JieqiThemeForm($jieqiLang['obook']['chapter_sort'], 'chaptersort', $obook_static_url.'/chaptersort.php');
$from_select = new JieqiFormSelect($jieqiLang['obook']['choose_chapter'],'fromid');
$to_select = new JieqiFormSelect($jieqiLang['obook']['chapter_move_to'],'toid');
$to_select->addOption('0', $jieqiLang['obook']['chapter_top_sort']);
$to_select->setDescription($jieqiLang['obook']['chapter_after_sort']);
foreach($chapterary as $k => $v){
	$key=$k+1;
	if($v['chaptertype']==1) $tmpstr='';
	else $tmpstr='|-';
	$tmpstr.=htmlspecialchars($v['chaptername'], ENT_QUOTES);
	$from_select->addOption($v['chapterorder'], $tmpstr);
	$to_select->addOption($v['chapterorder'], $tmpstr);
}
$sort_form->addElement($from_select);
$sort_form->addElement($to_select);
$sort_form->addElement(new JieqiFormHidden('aid', $_REQUEST['id']));
$sort_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['obook']['sort_confirm'], 'submit'));
$jieqiTpl->assign('sort_form', $sort_form->render(JIEQI_FORM_MIDDLE));

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/obookmanage.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');
//如果关联文章，则生成文章的vip章节目录，更新阅读目录index.html
if(count($chapterary)>0 && is_dir($jieqiModules['obook']['path'].'/')){
	$updatearticle=false;
	$articleid=$obook->getVar('articleid', 'n');
	if($articleid > 0){
		//有关联文章
		$linkfile=JIEQI_ROOT_PATH.'/files/obook/articlelink';
		if(!file_exists($linkfile)) jieqi_createdir($linkfile);
		$linkfile.=jieqi_getsubdir($articleid);
		if(!file_exists($linkfile)) jieqi_createdir($linkfile);
		$linkfile.='/'.$articleid.'.php';
		if(!empty($_REQUEST['updatelink']) || !file_exists($linkfile) || filemtime($linkfile) < $obook->getVar('lastupdate')){
			$obookinfo=array();
			$obookinfo['obookid']=$obook->getVar('obookid', 'n');
			$obookinfo['postdate']=$obook->getVar('postdate', 'n');
			$obookinfo['lastupdate']=$obook->getVar('lastupdate', 'n');
			$obookinfo['obookname']=$obook->getVar('obookname', 'n');
			$obookinfo['sortid']=$obook->getVar('sortid', 'n');
			$obookinfo['lastvolumeid']=$obook->getVar('lastvolumeid', 'n');
			$obookinfo['lastvolume']=$obook->getVar('lastvolume', 'n');
			$obookinfo['lastchapterid']=$obook->getVar('lastchapterid', 'n');
			$obookinfo['lastchapter']=$obook->getVar('lastchapter', 'n');
			$obookinfo['chapters']=$obook->getVar('chapters', 'n');
			$obookinfo['size']=$obook->getVar('size', 'n');
			$obookinfo['authorid']=$obook->getVar('authorid', 'n');
			$obookinfo['author']=$obook->getVar('author', 'n');
			$obookinfo['publishid']=$obook->getVar('publishid', 'n');
			$obookinfo['saleprice']=$obook->getVar('saleprice', 'n');
			$obookinfo['display']=$obook->getVar('display', 'n');
			$obookdata['obook']=&$obookinfo;
			$obookdata['ochapter']=&$chapterary;
			$varstring="<?php\n".jieqi_extractvars('jieqiObookdata', $obookdata)."\n?>";
			jieqi_writefile($linkfile, $varstring);
			$updatearticle=true;
		}
	}else{
		//没关联文章，如果原来有关联的话删除
		$linkfile=JIEQI_ROOT_PATH.'/files/obook/articlelink'.jieqi_getsubdir($articleid).'/'.$articleid.'.php';
		if(file_exists($linkfile)){
			jieqi_delfile($linkfile);
			$updatearticle=true;
		}
	}
	//重新生成文章的目录页(暂时用js调用)
	/*
	if($updatearticle && file_exists($jieqiModules['obook']['path'].'/class/package.php')){
		include_once($jieqiModules['obook']['path'].'/class/package.php');
		$package=new JieqiPackage($articleid);
		$package->loadOPF();
		$package->makeIndex();
	}
	*/
}
?>