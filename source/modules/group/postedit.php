<?php
/**
 * 修改圈子贴子
 *
 * 修改圈子贴子
 * 
 * 调用模板：/modules/group/templates/postedit.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');
jieqi_loadlang('post','group');
jieqi_getconfigs('group', 'configs');
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
//检查权限
include_once("include/functions.php");
setpower($gid);


if(!$allowmantopic){
	jieqi_printfail($jieqiLang['group']['noper_delete_post']);
}
//检查参数
if(empty($_REQUEST['pid']) || !is_numeric($_REQUEST['pid'])) jieqi_printfail(LANG_ERROR_PARAMETER);

//查询帖子是否存在
jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria=new CriteriaCompo(new Criteria('p.postid', $_REQUEST['pid']));
$criteria->setTables(jieqi_dbprefix('group_posts').' p LEFT JOIN '.jieqi_dbprefix('group_group').' f ON p.ownerid=f.gid');
$query->queryObjects($criteria);
$post=$query->getObject();
unset($criteria);
if(!$post) jieqi_printfail($jieqiLang['group']['post_not_exists']);

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
		$post_set = array('module'=>JIEQI_MODULE_NAME, 'ownerid'=>intval($post->getVar('ownerid')), 'topicid'=>intval($post->getVar('topicid')), 'postid'=>intval($_REQUEST['pid']), 'posttime'=>intval($post->getVar('posttime','n')), 'topictitle'=>&$_POST['topictitle'], 'posttext'=>&$_POST['posttext'], 'attachment'=>$post->getVar('attachment','n'), 'emptytitle'=>false, 'isnew'=>false, 'istopic'=>$istopic, 'istop'=>$istop, 'sname'=>'jieqiForumPostTime', 'attachfile'=>&$_FILES['attachfile'], 'oldattach'=>&$_POST['oldattach']);
		jieqi_post_checkvar($post_set, $jieqiConfigs['group'], $check_errors);

		//检查提交的附件
		$attachary=array(); //附件信息保存数组
		if($authupload) jieqi_post_checkattach($post_set, $jieqiConfigs['group'], $check_errors, $attachary);
		$attachnum = count($attachary);

		if(empty($check_errors)) {
			//处理旧附件
			$jieqiConfigs['group']['attachdir'] = $groupUserfile['attachdir'];
			include_once($jieqiModules['group']['path'].'/class/groupattachs.php');
			$attachs_handler =& JieqiGroupattachsHandler::getInstance('JieqiGroupattachsHandler');
			$attacholds = jieqi_post_attachold($post_set, $jieqiConfigs['group'], $attachs_handler);
			//新附件入库
			if($attachnum>0){
				include_once($jieqiModules['group']['path'].'/class/groupattachs.php');
				if(!is_object($attachs_handler)) $attachs_handler =& JieqiGroupattachsHandler::getInstance('JieqiGroupattachsHandler');
				//附件保存到数据库
				jieqi_post_attachdb($post_set, $attachary, $attachs_handler);
				//保存附件文件
				jieqi_post_attachfile($post_set, $attachary, $jieqiConfigs['group']);
			}

			//新老附件合并
			foreach($attachary as $val)	$attacholds[]=$val;
			//附件信息序列化
			$post_set['attachment']=serialize($attacholds); 
			//更新帖子表
			if(!jieqi_post_upedit($post_set, jieqi_dbprefix('group_posts'))) jieqi_printfail($jieqiLang['group']['edit_post_failure']);		
			//如果是主题则更新主题表
			if ($post->getVar('istopic')==1){
				if(!jieqi_topic_upedit($post_set, jieqi_dbprefix('group_topics'))) jieqi_printfail($jieqiLang['group']['edit_post_failure']);
			}
			jieqi_jumppage($jieqiModules['group']['url'].'/topic.php?g='.$gid.'&tid='.$post->getVar('topicid'), LANG_DO_SUCCESS, $jieqiLang['group']['edit_post_success']);
		}else{
			jieqi_printfail(implode('<br />', $check_errors));
		}
		break;
	case 'show':
	default:
		//显示提交框
		include_once(JIEQI_ROOT_PATH.'/header.php');

		include_once($jieqiModules['group']['path'].'/class/grouptopics.php');
		$topic_handler =& JieqiGrouptopicsHandler::getInstance('JieqiGrouptopicsHandler');
		$topic = $topic_handler->get($post->getVar('topicid', 'n'));
		if(!$topic) jieqi_printfail($jieqiLang['group']['topic_not_exists']);
		$jieqiTpl->assign('forumid', $post->getVar('forumid'));
		$jieqiTpl->assign('forumname', $post->getVar('forumname'));
		$jieqiTpl->assign('topicid', $topic->getVar('topicid'));

		$jieqiTpl->assign('topictitle', $topic->getVar('title'));

		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		if($post->getVar('istopic')==1) $tmpvar=true;
		else $tmpvar=false;
		$post_form = new JieqiThemeForm($jieqiLang['group']['post_edit'], 'postedit', $jieqiModules['group']['url'].'/postedit.php');
		$post_form->setExtra('enctype="multipart/form-data"');
		$post_form->addElement(new JieqiFormText($jieqiLang['group']['table_forumtopics_topictitle'], 'topictitle', 60, 60, $post->getVar('subject', 'e')),$tmpvar);
		$post_form->addElement(new JieqiFormDhtmlTextArea($jieqiLang['group']['table_forumposts_posttext'], 'posttext',  $post->getVar('posttext', 'e'), 12, 60), true);

		$tmpvar=$post->getVar('attachment','n');
		$attachnum=0;
		if(!empty($tmpvar)){
			$attachary=unserialize($tmpvar);
			if(!is_array($attachary)) $attachary=array();
			$attachnum=count($attachary);
			if($attachnum>0){
				foreach($attachary as $val) $selectattach[]=$val['attachid'];
				$attachelement=new JieqiFormCheckBox($jieqiLang['group']['now_attach'], 'oldattach', $selectattach);
				$attachelement->setIntro($jieqiLang['group']['attach_edit_note']);
				foreach($attachary as $key=>$val)
				$attachelement->addOption($val['attachid'], jieqi_htmlstr($val['name']).'&nbsp;&nbsp;');
				$post_form->addElement($attachelement, false);
			}
		}

		if($authupload && is_numeric($jieqiConfigs['group']['maxattachnum']) && $jieqiConfigs['group']['maxattachnum']>0){
			$post_form->addElement(new JieqiFormLabel($jieqiLang['group']['attach_limit'], $jieqiLang['group']['attach_filetype'].$jieqiConfigs['group']['attachtype'].', '.$jieqiLang['group']['attach_image_max'].$jieqiConfigs['group']['maximagesize'].'K, '.$jieqiLang['group']['attach_file_max'].$jieqiConfigs['group']['maxfilesize'].'K'));
			$maxfilenum=intval($jieqiConfigs['group']['maxattachnum']);
			for($i=1; $i<=$maxfilenum; $i++){
				$post_form->addElement(new JieqiFormFile($jieqiLang['group']['post_attach'].$i, 'attachfile[]', 60));
			}
		}


		$post_form->addElement(new JieqiFormHidden('pid', $_REQUEST['pid']));
		$post_form->addElement(new JieqiFormHidden('g', $gid));
		$post_form->addElement(new JieqiFormHidden('action', 'update'));
		$post_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['group']['edit_post_button'], 'submit'));

		$jieqiTpl->assign('postform', $post_form->render(JIEQI_FORM_MAX));
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/postedit.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}


?>