<?php
/**
 * 设置相册与文章分类
 *
 * 根据参娄设置相册与文章分类
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/set'.$_REQUEST['type'].'cat.html'
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
define('JIEQI_MODULE_NAME','space');
require_once('../../global.php');
include_once($jieqiModules['space']['path'].'/spaceheader.php');
jieqi_loadlang('setblogcat',JIEQI_MODULE_NAME);

//check power
jieqi_checklogin();
if(!$space_hoster){
	jieqi_printfail($jieqiLang['space']['not_your_space']);
}
$_REQUEST['type'] = $_REQUEST['type']?$_REQUEST['type'] :'blog';
if(!in_array($_REQUEST['type'],array('blog','image'))) jieqi_printfail($jieqiLang['space']['no_right']);
jieqi_getconfigs('space', 'setblocks','jieqiBlocks');

switch ($_REQUEST[action]) {
	case 'do_edit':
		include_once($jieqiModules['space']['path'].'/class/blogcat.php');
		$blog_cat_handler = JieqiSpaceBlogCatHandler::getInstance('JieqiSpaceBlogCatHandler');
		//delete old cat
		if($_REQUEST['delete_checkbox']){
			$tmpstr = '('.implode(",",$_REQUEST['delete_checkbox']).')';
			$criteria = new CriteriaCompo(new Criteria('`id`',$tmpstr,'in') );
			$criteria->add(new Criteria('`uid`',$uid) );
			$criteria->add(new Criteria('`type`',$_REQUEST['type'],'=') );
			$criteria->add(new Criteria('`default_cat`',1,'!=') );
			$blog_cat_handler->queryObjects($criteria);
			$v = $blog_cat_handler->getObject();
			if(!empty($v) ){
				$num=$v->getVar('num');
				$blog_cat_handler->delete($criteria);
				unset($criteria);
				//$criteria = new CriteriaCompo(new Criteria('uid',$uid) );
				//$criteria->add( new Criteria('`default_cat`',1) );
				//$blog_cat_handler->updatefields("`num`=`num`+$num",$criteria);
			}
			space_get_blog_cat($_REQUEST['type']);
			if($_REQUEST['type']=='blog'){
					include_once($jieqiModules['space']['path'].'/class/blog.php');
					$blog_handler =& JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');
					$criteria = new CriteriaCompo(new Criteria('`cat_id`',$tmpstr,'in') );
					$criteria->add(new Criteria('`uid`',$uid) );
					$blog_handler->updatefields('`cat_id`='.$default_cat_id,$criteria);
			}else{
				   include_once($jieqiModules['space']['path'].'/class/attachs.php');
				    $blog_handler =& JieqiAttachsHandler::getInstance('JieqiAttachsHandler');	
				    $criteria = new CriteriaCompo(new Criteria('`catid`',$tmpstr,'in') );
					$criteria->add(new Criteria('`uid`',$uid) );
					$blog_handler->updatefields('`catid`='.$default_cat_id,$criteria);
				    space_edit_album($blog_handler,$default_cat_id);
			}
			unset($tmpstr);
		}
		//edit old cat
		if($_REQUEST['old_name']){
			foreach($_REQUEST['old_name'] as $k=>$v){
				$criteria = new CriteriaCompo(new Criteria('`uid`',$uid) );
				$criteria->add(new Criteria('`id`',intval($_REQUEST['old_id'][$k]) ) );
				$criteria->add(new Criteria('`default_cat`',1,'!=') );
				$blog_cat_handler->updatefields(array('`cat_order`'=>intval($_REQUEST['old_order'][$k]),'`name`'=>$_REQUEST['old_name'][$k]), $criteria);
				unset($criteria);
			}
		}
		//add new cat
		if($_REQUEST['new_name']){
			foreach($_REQUEST['new_name'] as $k=>$v ) {
				if(!empty($v) ){
					$newspacecat = $blog_cat_handler->create();
					$newspacecat->setVar('`name`',$v);
					$newspacecat->setVar('`default_cat`',0);
					$newspacecat->setVar('`cat_order`',intval($_REQUEST['new_order'][$k]) );
					$newspacecat->setVar('`type`',$_REQUEST['type']);
					$newspacecat->setVar('`uid`',$uid);
					$blog_cat_handler->insert($newspacecat);
				}
			}
		}
		space_make_blog_cat($_REQUEST['type']);
		jieqi_jumppage($jieqiModules['space']['url'].'/setblogcat.php?uid='.$uid.'&type='.$_REQUEST['type'],$jieqiLang['space']['edit_finish'],$jieqiLang['space']['edit_article_sort_finish'] );
		break;
	default:
		$jieqiTpl->assign('id',intval($_REQUEST['id']) );
		include_once($jieqiModules['space']['path'].'/spaceheader.php');
		space_get_blog_cat($_REQUEST['type']);
		$jieqiTpl->assign('blog_cats',$blog_cats);
		$jieqiTpl->assign('type',$_REQUEST['type']);
		$jieqiTpl->assign('new_line',count($blog_cats)+2 );
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/set'.$_REQUEST['type'].'cat.html';
		include_once($jieqiModules['space']['path'].'/spacefooter.php');
		break;
}

?>