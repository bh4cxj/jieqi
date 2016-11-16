<?php 
/**
 * 编辑帖子
 *
 * 编辑帖子
 * 
 * 调用模板：/modules/forum/templates/postedit.html
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: postedit.php 329 2009-02-07 01:21:38Z juny $
 */

define('JIEQI_MODULE_NAME', 'forum');
require_once('../../global.php');
//检查参数
if(empty($_REQUEST['pid']) || !is_numeric($_REQUEST['pid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
//载入语言包
jieqi_loadlang('post', JIEQI_MODULE_NAME);
//查询帖子是否存在
jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria=new CriteriaCompo(new Criteria('p.postid', $_REQUEST['pid']));
$criteria->setTables(jieqi_dbprefix('forum_forumposts').' p LEFT JOIN '.jieqi_dbprefix('forum_forums').' f ON p.ownerid=f.forumid');
$query->queryObjects($criteria);
$post=$query->getObject();
unset($criteria);
if(!$post) jieqi_printfail($jieqiLang['forum']['post_not_exists']);

//检查访问权限
include_once($jieqiModules['forum']['path'].'/include/funforum.php');
if(!jieqi_forum_checkpower($post, 'authedit', true)) jieqi_printfail($jieqiLang['forum']['noper_edit_post']);
//检查上传权限
$authupload = jieqi_forum_checkpower($post, 'authupload', true);
//获取论坛类型
$forum_type=intval($post->getVar('forumtype', 'n'));
//载入参数设置
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'show';

switch($_REQUEST['action']){
	//处理提交内容
	case 'update':
		//载入通用的发帖处理函数库
		include_once(JIEQI_ROOT_PATH.'/include/funpost.php');
		//校验错误信息数组
		$check_errors = array();
		//检查和过滤提交变量
		$istopic = intval($post->getVar('istopic')); //是否主题帖子
		$istop = ($forum_type == 1) ? 2 : 0; //是否全区置顶
		$post_set = array('module'=>JIEQI_MODULE_NAME, 'ownerid'=>intval($post->getVar('ownerid')), 'topicid'=>intval($post->getVar('topicid')), 'postid'=>intval($_REQUEST['pid']), 'posttime'=>intval($post->getVar('posttime','n')), 'topictitle'=>&$_POST['topictitle'], 'posttext'=>&$_POST['posttext'], 'attachment'=>$post->getVar('attachment','n'), 'emptytitle'=>false, 'isnew'=>false, 'istopic'=>$istopic, 'istop'=>$istop, 'sname'=>'jieqiForumPostTime', 'attachfile'=>&$_FILES['attachfile'], 'oldattach'=>&$_POST['oldattach'], 'checkcode'=>$_POST['checkcode']);
		jieqi_post_checkvar($post_set, $jieqiConfigs['forum'], $check_errors);

		//检查提交的附件
		$attachary=array(); //附件信息保存数组
		if($authupload) jieqi_post_checkattach($post_set, $jieqiConfigs['forum'], $check_errors, $attachary);
		$attachnum = count($attachary);

		if(empty($check_errors)) {
			//处理旧附件
			include_once($jieqiModules['forum']['path'].'/class/forumattachs.php');
			$attachs_handler =& JieqiForumattachsHandler::getInstance('JieqiForumattachsHandler');
			$attacholds = jieqi_post_attachold($post_set, $jieqiConfigs['forum'], $attachs_handler);
			//新附件入库
			if($attachnum>0){
				include_once($jieqiModules['forum']['path'].'/class/forumattachs.php');
				if(!is_object($attachs_handler)) $attachs_handler =& JieqiForumattachsHandler::getInstance('JieqiForumattachsHandler');
				//附件保存到数据库
				jieqi_post_attachdb($post_set, $attachary, $attachs_handler);
				//保存附件文件
				jieqi_post_attachfile($post_set, $attachary, $jieqiConfigs['forum']);
			}

			//新老附件合并
			foreach($attachary as $val)	$attacholds[]=$val;
			//附件信息序列化
			$post_set['attachment']=serialize($attacholds); 
			//更新帖子表
			if(!jieqi_post_upedit($post_set, jieqi_dbprefix('forum_forumposts'))) jieqi_printfail($jieqiLang['forum']['edit_post_failure']);		
			//如果是主题则更新主题表
			if ($post->getVar('istopic')==1){
				if(!jieqi_topic_upedit($post_set, jieqi_dbprefix('forum_forumtopics'))) jieqi_printfail($jieqiLang['forum']['edit_post_failure']);
			}
			//如果是全区公告贴，则更新公告缓存
			if ($post->getVar('istopic')==1 && $forum_type==1) jieqi_forum_uptoptopic();
			jieqi_jumppage($jieqiModules['forum']['url'].'/showtopic.php?tid='.$post->getVar('topicid'), LANG_DO_SUCCESS, $jieqiLang['forum']['edit_post_success']);
		}else{
			jieqi_printfail(implode('<br />', $check_errors));
		}
		break;
	case 'show':
	default:
		//显示提交框
		include_once(JIEQI_ROOT_PATH.'/header.php');

		include_once($jieqiModules['forum']['path'].'/class/forumtopics.php');
		$topic_handler =& JieqiForumtopicsHandler::getInstance('JieqiForumtopicsHandler');
		$topic = $topic_handler->get($post->getVar('topicid', 'n'));
		if(!$topic) jieqi_printfail($jieqiLang['forum']['topic_not_exists']);
		$jieqiTpl->assign('forumid', $post->getVar('forumid'));
		$jieqiTpl->assign('forumname', $post->getVar('forumname'));
		$jieqiTpl->assign('topicid', $topic->getVar('topicid'));
		$jieqiTpl->assign('topictitle', $topic->getVar('title'));

		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		if($post->getVar('istopic')==1) $tmpvar=true;
		else $tmpvar=false;
		$post_form = new JieqiThemeForm($jieqiLang['forum']['post_edit'], 'postedit', $jieqiModules['forum']['url'].'/postedit.php');
		$post_form->setExtra('enctype="multipart/form-data"');
		$post_form->addElement(new JieqiFormText($jieqiLang['forum']['table_forumtopics_topictitle'], 'topictitle', 60, 60, $post->getVar('subject', 'e')),$tmpvar);
		$post_form->addElement(new JieqiFormDhtmlTextArea($jieqiLang['forum']['table_forumposts_posttext'], 'posttext',  $post->getVar('posttext', 'e'), 12, 60), true);
		//是否显示验证码
		if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
		$jieqiTpl->assign('postcheckcode', $jieqiConfigs['system']['postcheckcode']);
		if($jieqiConfigs['system']['postcheckcode'] > 0){
			if(!isset($jieqiLang['system']['post'])) jieqi_loadlang('post', 'system');
			$checkcode = new JieqiFormText($jieqiLang['system']['post_checkcode_label'], 'checkcode', 8, 8);
			$checkcode->setDescription(sprintf($jieqiLang['system']['post_checkcode_code'], JIEQI_URL, JIEQI_URL));
			$post_form->addElement($checkcode, true);
		}

		$tmpvar=$post->getVar('attachment','n');
		$attachnum=0;
		if(!empty($tmpvar)){
			$attachary=unserialize($tmpvar);
			if(!is_array($attachary)) $attachary=array();
			$attachnum=count($attachary);
			if($attachnum>0){
				foreach($attachary as $val) $selectattach[]=$val['attachid'];
				$attachelement=new JieqiFormCheckBox($jieqiLang['forum']['now_attach'], 'oldattach', $selectattach);
				$attachelement->setIntro($jieqiLang['forum']['attach_edit_note']);
				foreach($attachary as $key=>$val)
				$attachelement->addOption($val['attachid'], jieqi_htmlstr($val['name']).'&nbsp;&nbsp;');
				$post_form->addElement($attachelement, false);
			}
		}

		if($authupload && is_numeric($jieqiConfigs['forum']['maxattachnum']) && $jieqiConfigs['forum']['maxattachnum']>0){
			$post_form->addElement(new JieqiFormLabel($jieqiLang['forum']['attach_limit'], $jieqiLang['forum']['attach_filetype'].$jieqiConfigs['forum']['attachtype'].', '.$jieqiLang['forum']['attach_image_max'].$jieqiConfigs['forum']['maximagesize'].'K, '.$jieqiLang['forum']['attach_file_max'].$jieqiConfigs['forum']['maxfilesize'].'K'));
			$maxfilenum=intval($jieqiConfigs['forum']['maxattachnum']);
			for($i=1; $i<=$maxfilenum; $i++){
				$post_form->addElement(new JieqiFormFile($jieqiLang['forum']['post_attach'].$i, 'attachfile[]', 60));
			}
		}


		$post_form->addElement(new JieqiFormHidden('pid', $_REQUEST['pid']));
		$post_form->addElement(new JieqiFormHidden('action', 'update'));
		$post_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['forum']['edit_post_button'], 'submit'));

		$jieqiTpl->assign('postform', $post_form->render(JIEQI_FORM_MAX));
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['forum']['path'].'/templates/postedit.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}


?>