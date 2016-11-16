<?php
/**
 * 发表帖子
 *
 * 发表帖子表单
 * 
 * 调用模板：/modules/group/templates/post.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');

jieqi_loadlang('post',JIEQI_MODULE_NAME);
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');
jieqi_loadlang('post','group');
jieqi_getconfigs('group', 'configs');
$_REQUEST[topicid] = $_REQUEST[tid];
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
include_once(JIEQI_ROOT_PATH."/modules/".JIEQI_MODULE_NAME.'/include/functions.php');
jieqi_checklogin();
setpower($gid);
if(!$allowposttopic) {
	jieqi_printfail($jieqiLang['g']['no_right_post_topic']);
}
$special = intval($_REQUEST['special'] );
$jieqiTpl->assign('special',$special);

if($topicid = intval($_REQUEST['topicid']) ){
	$jieqiTpl->assign("post_class",$jieqiLang['g']['post_reply']);
	$jieqiTpl->assign("content_lang",$jieqiLang['g']['content'] );
} elseif ( $special == 1 ) {
	$jieqiTpl->assign('post_class',$jieqiLang['g']['post_poll']);
	$jieqiTpl->assign("content_lang",$jieqiLang['g']['backdrop']);
} else {
	$jieqiTpl->assign("post_class",$jieqiLang['g']['post_new_topic']);
	$jieqiTpl->assign("content_lang",$jieqiLang['g']['content']);
}


switch ($_REQUEST['action']){
	//发帖提交处理
	case 'newpost':
		//载入通用的发帖处理函数库
		include_once(JIEQI_ROOT_PATH.'/include/funpost.php');
		//校验错误信息数组
		$check_errors = array(); 
		//检查和过滤提交变量
		$istopic = empty($_REQUEST['tid']) ? 1 : 0; //是否发表新主题标志
		$istop = ($forum_type == 1) ? 2 : 0; //是否全区置顶
		$post_set = array('module'=>JIEQI_MODULE_NAME, 'ownerid'=>intval($gid), 'topicid'=>intval($_REQUEST['tid']), 'postid'=>0, 'posttime'=>JIEQI_NOW_TIME, 'topictitle'=>&$_POST['topictitle'], 'posttext'=>&$_POST['posttext'], 'attachment'=>'', 'emptytitle'=>false, 'isnew'=>true, 'istopic'=>$istopic, 'istop'=>$istop, 'sname'=>'jieqiForumPostTime', 'attachfile'=>&$_FILES['attachfile'], 'oldattach'=>'', 'checkcode'=>$_POST['checkcode']);
		jieqi_post_checkvar($post_set, $jieqiConfigs['group'], $check_errors);
		//检查提交的附件
		$attachary=array(); //附件信息保存数组
		//if($authupload) jieqi_post_checkattach($post_set, $jieqiConfigs['group'], $check_errors, $attachary);
		jieqi_post_checkattach($post_set, $jieqiConfigs['group'], $check_errors, $attachary);
		$attachnum = count($attachary);
		
		if(empty($check_errors)){
			include_once($jieqiModules['group']['path'].'/class/grouptopics.php');
			$topic_handler =& JieqiGrouptopicsHandler::getInstance('JieqigrouptopicsHandler');
			if(empty($_REQUEST['tid'])){
				//发表新主题
				$newTopic = $topic_handler->create();
				//主题表实例赋值
				jieqi_topic_newset($post_set, $newTopic);
				if (!$topic_handler->insert($newTopic)) jieqi_printfail($jieqiLang['group']['post_topic_failure']);
				$group_handler->updatefields('topicnum=topicnum+1',$criteria);
				$_REQUEST['tid']=$newTopic->getVar('topicid', 'n');
				$post_set['topicid'] = $_REQUEST['tid'];
			}else{
				//发表回复
				$topic = $topic_handler->get($_REQUEST['tid']);
				if(!$topic) jieqi_printfail($jieqiLang['group']['topic_not_exists']);
				//主题实例赋值
				if(!jieqi_topic_upreply($topic)) jieqi_printfail($jieqiLang['system']['post_topic_locked']);
				$topic_handler->insert($topic);
				$group_handler->updatefields('gtopics=gtopics+1',$criteria);
			}
			//附件入库
			if($attachnum>0){
				include_once($jieqiModules['group']['path'].'/class/groupattachs.php');
				$attachs_handler =& JieqiGroupattachsHandler::getInstance('JieqiGroupattachsHandler');
				//附件保存到数据库
				jieqi_post_attachdb($post_set, $attachary, $attachs_handler);
				$post_set['attachment'] = serialize($attachary); //附件信息序列化
			}

			include_once($jieqiModules['group']['path'].'/class/groupposts.php');
			$post_handler =& JieqiGrouppostHandler::getInstance('JieqiGrouppostHandler');
			$newPost = $post_handler->create();
			//帖子内容赋值
			jieqi_post_newset($post_set, $newPost);
			if (!$post_handler->insert($newPost)) jieqi_printfail($jieqiLang['group']['post_faliure']);
			else {
				$postid=$newPost->getVar('postid', 'n');
				$post_set['postid'] = $postid;
				$post_set['posttime'] = JIEQI_NOW_TIME;
				
				//发表后更新论坛帖子数和最后发表信息
				//jieqi_forum_upnewpost($gid, array('time'=>JIEQI_NOW_TIME, 'uid'=>intval($_SESSION['jieqiUserId']), 'uname'=>strval($_SESSION['jieqiUserName']), 'tid'=>$_REQUEST['tid'], 'istopic'=>$istopic));
				//发表主题或回复积分
				include_once(JIEQI_ROOT_PATH.'/class/users.php');
				$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
				if(!empty($istopic)){
					if(!empty($jieqiConfigs['group']['scoretopic'])) $users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['group']['scoretopic'], true);
				}else{
					if(!empty($jieqiConfigs['group']['scorereply'])) $users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['group']['scorereply'], true);
				}

				//处理上传文件
				//有附件上传，检查路径是否存在
				if($attachnum>0){
					//更新附件数据库
					$attachs_handler->db->query("UPDATE ".jieqi_dbprefix('group_attachs')." SET postid=".$postid." WHERE topicid=".$_REQUEST['tid']." AND postid=0");
					//保存附件文件
					$jieqiConfigs['group']['attachdir'] = $groupUserfile['attachdir'];
					jieqi_post_attachfile($post_set, $attachary, $jieqiConfigs['group']);
				}
				//发表全区公告主题时候更新公告缓存
				//if($forum_type==1 && $istopic==1) jieqi_forum_uptoptopic();
				jieqi_jumppage($jieqiModules['group']['url'].'/topic.php?g='.$gid.'&tid='.$newPost->getVar('topicid').'&page=last', LANG_DO_SUCCESS, $jieqiLang['group']['post_success']);
			}
		}else{
			jieqi_printfail(implode('<br />', $check_errors));
		}
		break;
	case 'post':
	default:
		//include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		if(empty($_REQUEST['tid'])){
			$tmpstr=$jieqiLang['group']['post_new'];
			$tmpvar=true;
			$jieqiTpl->assign('forumguide',' &gt; <a href="'.JIEQI_URL.'/modules/group/topiclist.php?</a>');
		}else{
			$tmpstr=$jieqiLang['group']['post_reply'];
			$tmpvar=false;
			include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/grouptopics.php');	
			$topic_handler = JieqigrouptopicsHandler::getInstance('JieqigrouptopicsHandler');
			$topic = $topic_handler->get($_REQUEST['tid']);
			if(!$topic) jieqi_printfail($jieqiLang['group']['topic_not_exists']);
			//	$jieqiTpl->assign('forumguide',' &gt; <a href="'.JIEQI_URL.'/modules/forum/topiclist.php?fid='.$forum->getVar('gid').'">'.$forum->getVar('forumname').'</a> &gt; <a href="'.JIEQI_URL.'/modules/forum/showtopic.php?tid='.$topic->getVar('topicid').'&lpage=1">'.$topic->getVar('topictitle').'</a>');
		} 
			include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/groupposts.php');
			$post_handler = JieqiGrouppostHandler::getInstance('JieqigrouppostHandler');
			$quote='';
			if(isset($_REQUEST['pid']) && !empty($_REQUEST['pid'])){
				$post = $post_handler->get($_REQUEST['pid']);
				$tmpstr=$post->getVar('posttext','e');
				$i=strpos($tmpstr, '[/quote]');
				if($i != false) $tmpstr=substr($tmpstr, $i+8);
				if(is_object($post)) $quote='[quote]'.jieqi_substr($tmpstr, 0, $jieqiConfigs['group']['quotelength']).'[/quote]';
			}
			$post_form = new JieqiThemeForm($tmpstr, 'frmpost', JIEQI_URL.'/modules/group/post.php?g='.$gid);
			$post_form->setExtra('enctype="multipart/form-data"');
			$post_form->addElement(new JieqiFormText($jieqiLang['group']['table_forumtopics_topictitle'], 'topictitle', 60, 60), $tmpvar);
			$post_form->addElement(new JieqiFormDhtmlTextArea($jieqiLang['group']['table_forumposts_posttext'], 'posttext', $quote, 12, 60), true);

			if( is_numeric($jieqiConfigs['group']['maxattachnum']) && $jieqiConfigs['group']['maxattachnum']>0){
				$post_form->addElement(new JieqiFormLabel($jieqiLang['group']['attach_limit'], $jieqiLang['group']['attach_filetype'].$jieqiConfigs['group']['attachtype'].', '.$jieqiLang['group']['attach_image_max'].$jieqiConfigs['group']['maximagesize'].'K, '.$jieqiLang['group']['attach_file_max'].$jieqiConfigs['group']['maxfilesize'].'K'));
				$maxfilenum=intval($jieqiConfigs['group']['maxattachnum']);
				for($i=1; $i<=$maxfilenum; $i++){
					$post_form->addElement(new JieqiFormFile($jieqiLang['group']['post_attach'].$i, 'attachfile[]', 60));
				}
			}
		//是否显示验证码
		if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
		$jieqiTpl->assign('postcheckcode', $jieqiConfigs['system']['postcheckcode']);
		if($jieqiConfigs['system']['postcheckcode'] > 0){
			if(!isset($jieqiLang['system']['post'])) jieqi_loadlang('post', 'system');
			$checkcode = new JieqiFormText($jieqiLang['system']['post_checkcode_label'], 'checkcode', 8, 8);
			$checkcode->setDescription(sprintf($jieqiLang['system']['post_checkcode_code'], JIEQI_URL, JIEQI_URL));
			$post_form->addElement($checkcode, true);
		}			
			$post_form->addElement(new JieqiFormHidden('fid', $_REQUEST['fid']));
			if(!empty($_REQUEST['tid'])) $post_form->addElement(new JieqiFormHidden('tid', $_REQUEST['tid']));
			$post_form->addElement(new JieqiFormHidden('action', 'newpost'));
			$post_form->addElement(new JieqiFormButton('&nbsp;', 'btnpost', $jieqiLang['group']['post_button'], 'submit'));

			$jieqiTpl->assign('postform', $post_form->render(JIEQI_FORM_MAX));
			$jieqiTpl->setCaching(0);
			$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/post.html';			
			require_once($jieqiModules['group']['path'].'/groupfooter.php');
			break;
}
?>