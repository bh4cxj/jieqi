<?php 
/**
 * 发表新帖
 *
 * 发表新帖
 * 
 * 调用模板：/modules/forum/templates/newpost.html
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: newpost.php 329 2009-02-07 01:21:38Z juny $
 */

define('JIEQI_MODULE_NAME', 'forum');
require_once('../../global.php');
//检查参数
if(empty($_REQUEST['fid']) || !is_numeric($_REQUEST['fid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
//有 $_REQUEST['tid'] 的是回复帖子，没有的是发布新帖
if(isset($_REQUEST['tid'])) $_REQUEST['tid']=intval($_REQUEST['tid']);
//载入语言包
jieqi_loadlang('post', JIEQI_MODULE_NAME);
//查询论坛是否存在，用户后面的判断权限
include_once($jieqiModules['forum']['path'].'/class/forums.php');
$forums_handler=JieqiForumsHandler::getInstance('JieqiForumsHandler');
$forum=$forums_handler->get($_REQUEST['fid']);
if(!$forum) jieqi_printfail($jieqiLang['forum']['forum_not_exists']);
//检查访问权限
include_once($jieqiModules['forum']['path'].'/include/funforum.php');
if(isset($_REQUEST['tid']) && !empty($_REQUEST['tid'])){
	if(!jieqi_forum_checkpower($forum, 'authreply', true)) jieqi_printfail($jieqiLang['forum']['noper_reply_post']);
}else{
	if(!jieqi_forum_checkpower($forum, 'authpost', true)) jieqi_printfail($jieqiLang['forum']['noper_new_post']);
}
//检查上传权限
$authupload = jieqi_forum_checkpower($forum, 'authupload', true);
//论坛类型 $forum_type=0 是普通论坛 1 是公告论坛
$forum_type=intval($forum->getVar('forumtype', 'n'));
//载入配置参数
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'show';

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
		$post_set = array('module'=>JIEQI_MODULE_NAME, 'ownerid'=>intval($_REQUEST['fid']), 'topicid'=>intval($_REQUEST['tid']), 'postid'=>0, 'posttime'=>JIEQI_NOW_TIME, 'topictitle'=>&$_POST['topictitle'], 'posttext'=>&$_POST['posttext'], 'attachment'=>'', 'emptytitle'=>false, 'isnew'=>true, 'istopic'=>$istopic, 'istop'=>$istop, 'sname'=>'jieqiForumPostTime', 'attachfile'=>&$_FILES['attachfile'], 'oldattach'=>'', 'checkcode'=>$_POST['checkcode']);
		jieqi_post_checkvar($post_set, $jieqiConfigs['forum'], $check_errors);
		//检查提交的附件
		$attachary=array(); //附件信息保存数组
		if($authupload) jieqi_post_checkattach($post_set, $jieqiConfigs['forum'], $check_errors, $attachary);
		$attachnum = count($attachary);
		
		if(empty($check_errors)){
			include_once($jieqiModules['forum']['path'].'/class/forumtopics.php');
			$topic_handler =& JieqiForumtopicsHandler::getInstance('JieqiForumtopicsHandler');
			if(empty($_REQUEST['tid'])){
				//发表新主题
				$newTopic = $topic_handler->create();
				//主题表实例赋值
				jieqi_topic_newset($post_set, $newTopic);
				if (!$topic_handler->insert($newTopic)) jieqi_printfail($jieqiLang['forum']['post_topic_failure']);
				$_REQUEST['tid']=$newTopic->getVar('topicid', 'n');
				$post_set['topicid'] = $_REQUEST['tid'];
			}else{
				//发表回复
				$topic = $topic_handler->get($_REQUEST['tid']);
				if(!$topic) jieqi_printfail($jieqiLang['forum']['topic_not_exists']);
				//主题实例赋值
				if(!jieqi_topic_upreply($topic)) jieqi_printfail($jieqiLang['system']['post_topic_locked']);
				$topic_handler->insert($topic);
			}

			//附件入库
			if($attachnum>0){
				include_once($jieqiModules['forum']['path'].'/class/forumattachs.php');
				$attachs_handler =& JieqiForumattachsHandler::getInstance('JieqiForumattachsHandler');
				//附件保存到数据库
				jieqi_post_attachdb($post_set, $attachary, $attachs_handler);
				$post_set['attachment'] = serialize($attachary); //附件信息序列化
			}

			include_once($jieqiModules['forum']['path'].'/class/forumposts.php');
			$post_handler =& JieqiForumpostsHandler::getInstance('JieqiForumpostsHandler');
			$newPost = $post_handler->create();
			//帖子内容赋值
			jieqi_post_newset($post_set, $newPost);
			if (!$post_handler->insert($newPost)) jieqi_printfail($jieqiLang['forum']['post_faliure']);
			else {
				$postid=$newPost->getVar('postid', 'n');
				$post_set['postid'] = $postid;
				$post_set['posttime'] = JIEQI_NOW_TIME;
				
				//发表后更新论坛帖子数和最后发表信息
				jieqi_forum_upnewpost($_REQUEST['fid'], array('time'=>JIEQI_NOW_TIME, 'uid'=>intval($_SESSION['jieqiUserId']), 'uname'=>strval($_SESSION['jieqiUserName']), 'tid'=>$_REQUEST['tid'], 'istopic'=>$istopic));
				//发表主题或回复积分
				include_once(JIEQI_ROOT_PATH.'/class/users.php');
				$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
				if(!empty($istopic)){
					if(!empty($jieqiConfigs['forum']['scoretopic'])) $users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['forum']['scoretopic'], true);
				}else{
					if(!empty($jieqiConfigs['forum']['scorereply'])) $users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['forum']['scorereply'], true);
				}

				//处理上传文件
				//有附件上传，检查路径是否存在
				if($attachnum>0){
					//更新附件数据库
					$attachs_handler->db->query("UPDATE ".jieqi_dbprefix('forum_attachs')." SET postid=".$postid." WHERE topicid=".$_REQUEST['tid']." AND postid=0");
					//保存附件文件
					jieqi_post_attachfile($post_set, $attachary, $jieqiConfigs['forum']);
				}
				//发表全区公告主题时候更新公告缓存
				if($forum_type==1 && $istopic==1) jieqi_forum_uptoptopic();
				jieqi_jumppage($jieqiModules['forum']['url'].'/showtopic.php?tid='.$newPost->getVar('topicid').'&page=last', LANG_DO_SUCCESS, $jieqiLang['forum']['post_success']);
			}
		}else{
			jieqi_printfail(implode('<br />', $check_errors));
		}
		break;
	case 'show':
	default:
		//显示提交框
		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$jieqiTpl->assign('authupload', $authupload);
		$jieqiTpl->assign('forumid', $forum->getVar('forumid'));
		$jieqiTpl->assign('forumname', $forum->getVar('forumname'));

		if(empty($_REQUEST['tid'])){
			$forumtitle=$jieqiLang['forum']['post_new'];
			$tmpvar=true;
		}else{
			$forumtitle=$jieqiLang['forum']['post_reply'];
			$tmpvar=false;
			include_once($jieqiModules['forum']['path'].'/class/forumtopics.php');
			$topic_handler =& JieqiForumtopicsHandler::getInstance('JieqiForumtopicsHandler');
			$topic = $topic_handler->get($_REQUEST['tid']);
			if(!$topic) jieqi_printfail($jieqiLang['forum']['topic_not_exists']);

			$jieqiTpl->assign('topicid', $topic->getVar('topicid'));
			$jieqiTpl->assign('topictitle', $topic->getVar('title'));
		}
		include_once($jieqiModules['forum']['path'].'/class/forumposts.php');
		$post_handler =& JieqiForumpostsHandler::getInstance('JieqiForumpostsHandler');
		$quote='';
		if(isset($_REQUEST['pid']) && !empty($_REQUEST['pid'])){
			$post = $post_handler->get($_REQUEST['pid']);
			$tmpstr = $post->getVar('posttext','e');
			$i=strpos($tmpstr, '[/quote]');
			if($i != false) $tmpstr=substr($tmpstr, $i+8);
			if(is_object($post)) $quote='[quote]'.jieqi_substr($tmpstr, 0, $jieqiConfigs['forum']['quotelength']).'[/quote]';
		}
		$post_form = new JieqiThemeForm($forumtitle, 'frmpost', $jieqiModules['forum']['url'].'/newpost.php');
		$post_form->setExtra('enctype="multipart/form-data"');
		$post_form->addElement(new JieqiFormText($jieqiLang['forum']['table_forumtopics_topictitle'], 'topictitle', 60, 60), $tmpvar);
		$post_form->addElement(new JieqiFormDhtmlTextArea($jieqiLang['forum']['table_forumposts_posttext'], 'posttext', $quote, 12, 60), true);
		//是否显示验证码
		if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
		$jieqiTpl->assign('postcheckcode', $jieqiConfigs['system']['postcheckcode']);
		if($jieqiConfigs['system']['postcheckcode'] > 0){
			if(!isset($jieqiLang['system']['post'])) jieqi_loadlang('post', 'system');
			$checkcode = new JieqiFormText($jieqiLang['system']['post_checkcode_label'], 'checkcode', 8, 8);
			$checkcode->setDescription(sprintf($jieqiLang['system']['post_checkcode_code'], JIEQI_URL, JIEQI_URL));
			$post_form->addElement($checkcode, true);
		}
		if($authupload && is_numeric($jieqiConfigs['forum']['maxattachnum']) && $jieqiConfigs['forum']['maxattachnum']>0){
			$post_form->addElement(new JieqiFormLabel($jieqiLang['forum']['attach_limit'], $jieqiLang['forum']['attach_filetype'].$jieqiConfigs['forum']['attachtype'].', '.$jieqiLang['forum']['attach_image_max'].$jieqiConfigs['forum']['maximagesize'].'K, '.$jieqiLang['forum']['attach_file_max'].$jieqiConfigs['forum']['maxfilesize'].'K'));
			$maxfilenum=intval($jieqiConfigs['forum']['maxattachnum']);
			for($i=1; $i<=$maxfilenum; $i++){
				$post_form->addElement(new JieqiFormFile($jieqiLang['forum']['post_attach'].$i, 'attachfile[]', 60));
			}
		}
		$post_form->addElement(new JieqiFormHidden('fid', $_REQUEST['fid']));
		if(!empty($_REQUEST['tid'])) $post_form->addElement(new JieqiFormHidden('tid', $_REQUEST['tid']));
		$post_form->addElement(new JieqiFormHidden('action', 'newpost'));
		$post_form->addElement(new JieqiFormButton('&nbsp;', 'btnpost', $jieqiLang['forum']['post_button'], 'submit'));

		$jieqiTpl->assign('postform', $post_form->render(JIEQI_FORM_MAX));
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['forum']['path'].'/templates/newpost.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}

?>