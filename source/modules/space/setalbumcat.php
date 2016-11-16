<?php
define('JIEQI_MODULE_NAME','space');
require_once('../../global.php');
include_once($jieqiModules['space']['path'].'/spaceheader.php');
jieqi_loadlang('setblogcat',JIEQI_MODULE_NAME);

//check power
jieqi_checklogin();
if(!$space_hoster){
	jieqi_printfail($jieqiLang['space']['not_your_space']);
}
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
			$criteria->add(new Criteria('`default`',1,'!=') );
			$blog_cat_handler->queryObjects($criteria);
			$v = $blog_cat_handler->getObject();
			if(!empty($v) ){
				$num=$v->getVar('num');
				$blog_cat_handler->delete($criteria);
				unset($criteria);
				$criteria = new CriteriaCompo(new Criteria('uid',$uid) );
				$criteria->add( new Criteria('`default`',1) );
				$blog_cat_handler->updatefields("`num`=`num`+$num",$criteria);
			}
			include_once($jieqiModules['space']['path'].'/class/blog.php');
			$blog_handler =& JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');
			$criteria = new CriteriaCompo(new Criteria('`cat_id`',$tmpstr,'in') );
			$criteria->add(new Criteria('`uid`',$uid) );
			space_get_blog_cat();
			$blog_handler->updatefields('`cat_id`='.$default_id,$criteria);
			unset($tmpstr);
		}
		//edit old cat
		if($_REQUEST['old_name']){
			foreach($_REQUEST['old_name'] as $k=>$v){
				$criteria = new CriteriaCompo(new Criteria('`uid`',$uid) );
				$criteria->add(new Criteria('`id`',intval($_REQUEST['old_id'][$k]) ) );
				$criteria->add(new Criteria('`default`',1,'!=') );
				$blog_cat_handler->updatefields(array('`order`'=>intval($_REQUEST['old_order'][$k]),'`name`'=>$_REQUEST['old_name'][$k]), $criteria);
				unset($criteria);
			}
		}
		//add new cat
		if($_REQUEST['new_name']){
			foreach($_REQUEST['new_name'] as $k=>$v ) {
				if(!empty($v) ){
					$newspacecat = $blog_cat_handler->create();
					$newspacecat->setVar('`name`',$v);
					$newspacecat->setVar('`default`',0);
					$newspacecat->setVar('`order`',intval($_REQUEST['new_order'][$k]) );
					$newspacecat->setVar('`uid`',$uid);
					$blog_cat_handler->insert($newspacecat);
				}
			}
		}
		space_make_blog_cat();
		jieqi_jumppage($jieqiModules['space']['url'].'/setblogcat.php?uid='.$uid,$jieqiLang['space']['edit_finish'],$jieqiLang['space']['edit_article_sort_finish'] );
		break;
	default:
		$jieqiTpl->assign('id',intval($_REQUEST['id']) );
		include_once($jieqiModules['space']['path'].'/spaceheader.php');
		space_get_blog_cat();
		$jieqiTpl->assign('blog_cats',$blog_cats);
		$jieqiTpl->assign('new_line',count($blog_cats)+2 );
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/setblogcat.html';
		include_once($jieqiModules['space']['path'].'/spacefooter.php');
		break;
}

?>