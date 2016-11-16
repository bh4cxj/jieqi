<?php
/**
 * 修改相册照片
 *
 * 修改相册照片
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/editphoto.html
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
header('Content-type:text/html;charset='.JIEQI_SYSTEM_CHARSET);
jieqi_loadlang('header',JIEQI_MODULE_NAME);
//jieqi_getconfigs('space', 'blogblocks','jieqiBlocks');
//check power
jieqi_checklogin();
if(!$space_hoster){
	exit($jieqiLang['space']['no_right']);
}

        $_REQUEST['attachid']=intval($_REQUEST['attachid']);
		$_REQUEST['catid']=intval($_REQUEST['catid']);
		
		switch ($_REQUEST['action']) {
			   case 'edit':
				   include_once($jieqiModules['space']['path'].'/class/attachs.php');
				   $attachs_handler =& JieqiAttachsHandler::getInstance('JieqiAttachsHandler');	
				   $attachsHandler=$attachs_handler->get($_REQUEST['attachid']);
				   if($_REQUEST['catid']){
				      if($_REQUEST['isdefault']) $splitsql = "isdefault='0'";
				      $attachs_handler->db->query("UPDATE ".jieqi_dbprefix('space_attachs')." SET ".$splitsql." WHERE catid=".$_REQUEST['catid']);
				   }
				   $attachsHandler->unsetNew();
    	           $attachsHandler->setVar('catid', $_REQUEST['catid']);	
				   $attachsHandler->setVar('filebak', $_REQUEST['filebak']);		
				   $attachsHandler->setVar('isdefault', $_REQUEST['isdefault']);	
				   $handle=$attachs_handler->insert($attachsHandler);
				   //更新相册的相关信息
				   $msg = 'faild';
				   if($handle){
	                  space_edit_album($attachs_handler,$_REQUEST['catid']);
				      if($_REQUEST['catid']!=$_REQUEST['oldcatid']) space_edit_album($attachs_handler,$_REQUEST['oldcatid']);
					  $msg = 'success';
				   }
				   exit($jieqiLang['space']['edit_'.$msg.'_photo'].'<script>setTimeout("top.location.reload();",600);</script>');
			   break;
			   case 'delete':
				   include_once($jieqiModules['space']['path'].'/class/attachs.php');
				   $attachs_handler =& JieqiAttachsHandler::getInstance('JieqiAttachsHandler');	
				   $attachsHandler=$attachs_handler->get($_REQUEST['attachid']);
				   if($space_hoster){
					   $criteria=new CriteriaCompo();
					   $criteria->add(new Criteria('attachid', $_REQUEST['attachid']));
					   $handle = $attachs_handler->delete($criteria);
					   //更新相册的相关信息
					   $msg = 'faild';
					   if($handle){
					      @unlink('../..'.$attachsHandler->getVar('url'));
						  @unlink('../..'.str_replace('b_','',$attachsHandler->getVar('url')));
						  space_edit_album($attachs_handler,$_REQUEST['catid']);
						  $msg = 'success';
					   }
				   }else{
				      $msg = 'xx';
				   }
				  exit($jieqiLang['space']['delete_'.$msg.'_photo'].'<script>setTimeout("top.location.reload();",600);</script>');
			   break;
		}		
//验证相册开始		
		space_get_blog_cat('image');
		if($_REQUEST['attachid']){
		   $default_cat_id = $_REQUEST['catid'];
		   if(!array_key_exists($default_cat_id,$blog_cats)) exit($jieqiLang['space']['no_this_user']);
		   $photostr = $blog_cats[$_REQUEST['catid']]['attachment'];
		   $photoarray = unserialize($photostr);
		   foreach($photoarray as $R){
		       $iarray[$R['attachid']]=$R;
		   }
		}else {
		   exit($jieqiLang['space']['no_right']);
		}
//验证结束
		$jieqiTpl->assign('image_cats',$blog_cats);
		$jieqiTpl->assign('image_more',$iarray[$_REQUEST['attachid']]);
		$jieqiTpl->assign('catid',$default_cat_id);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/editphoto.html';
		include_once($jieqiModules['space']['path'].'/spacefooter.php');

?>