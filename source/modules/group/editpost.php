<?php
/**
 * 修改圈子贴子
 *
 * 修改圈子贴子
 * 
 * 调用模板：/modules/group/templates/editpost.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块

//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');

jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');
jieqi_loadlang('editpost',JIEQI_MODULE_NAME);

$gid = intval($_REQUEST['g']);
if($gid == 0){
	header("Location: ".JIEQI_URL);
}

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
include_once(JIEQI_ROOT_PATH."/modules/".JIEQI_MODULE_NAME.'/include/functions.php');
setpower($gid);
$nowtime = time();
if(!$allowposttopic) {
	echo "<script language='javascript'>alert('".$jieqiLang['g']['no_right_post_topic']."');history.back(1) </script>";
	exit;
}

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/post.php');
$post_handler = JieqipostHandler::getInstance('JieqipostHandler');

//old post content
$criteria = new Criteria('postid',intval($_REQUEST['postid']) );
$post_handler->queryObjects($criteria);
$oldpost = $post_handler->getObject();
$topicid = $oldpost->getVar('topicid',n);

//judge is a special or not
if( $istopic = $oldpost->getVar('istopic') ) {
	include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/topic.php');	
	$topic_handler = JieqitopicHandler::getInstance('JieqiTopicHandler');
	$thistopic = $topic_handler->get($topicid); 
	$special = $thistopic->getVar('special');
	if($special == 1) {
		$jieqiTpl->assign('special',$special);
		include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/poll.php');
		$poll_handler = JieqipollHandler::getInstance('JieqipollHandler');
		$poll = $poll_handler->get($topicid);
		if( $poll->getVar('multiple') ){
			$jieqiTpl->assign('box_type','checkbox');
			$jieqiTpl->assign('maxchoices',$poll->getVar('maxchoices') );
		} else {
			$jieqiTpl->assign('box_type','radio');
		}
		$expiration = round( ( ($poll->getVar('expiration')-$nowtime )  )/86400 );
		$jieqiTpl->assign('expiration',$expiration);

		include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/polloption.php');
		$polloption_handler = JieqipolloptionHandler::getInstance('JieqipolloptionHandler');
		$criteria = new CriteriaCompo(new Criteria('topicid',$topicid) );
		$optionscount = $polloption_handler->getCount($criteria);
		$jieqiTpl->assign('optionscount',$optionscount);
		$criteria->setsort('displayorder');
		$polloption_handler->queryObjects($criteria);
		$options = array();
		while( $polloption = $polloption_handler->getObject()  ) {
			$options[] = array('polloptionid'=>$polloption->getVar('polloptionid'),
					'value'=>$polloption->getVar('polloption',n),	
					'displayorder'=>$polloption->getVar('displayorder')
					); 
		}
		$jieqiTpl->assign('options',$options);
		$jieqiTpl->assign('allowvote',1);
	}
}

$jieqiTpl->assign('postsubject',$oldpost->getVar('postsubject') );
$jieqiTpl->assign('posttext',$oldpost->getVar('posttext',n) );
//echo $oldpost->getVar('posttext',n);
//exit;
$oldattachsinfo = $oldpost->getVar('attachsinfo',n);
$oldattachsinfo = unserialize($oldattachsinfo);
$oldattachs_html = "";
if( !empty($oldattachsinfo) ) {
	foreach($oldattachsinfo as $key=>$value) {
		$oldattachs_html .= "<input type='checkbox' name=oldattachs[] value=$value[attachid] checked>$value[name]&nbsp;&nbsp;";
	}
}
$jieqiTpl->assign('attachs_html',$oldattachs_html);

//edit post
if($_REQUEST['newpost'] ){
	$postid = intval($_REQUEST['postid']);
	//检查标题内容
	$error = '';
	if( empty($_REQUEST['postsubject']) && $istopic ){
		$error = $jieqiLang['g']['title_is_empty']."<br>";
	}

	//检查附件
	$attachnum = 0;
	$typearry = array('jpg','jpeg','gif','png','bmp','rar','zip');
	if(!empty($_FILES['attachfile']['name']) ){
		foreach($_FILES['attachfile']['name'] as $key=>$v){
			if(!empty($v) ){
				$fname_explode_tmp = explode('.',$v); 	
				$fname_explode =array_reverse($fname_explode_tmp);
				if(!in_array(strtolower($fname_explode[0]),$typearry ) ){
					$error = $jieqiLang['g']['attach_type_not_allowed']."<br />";
				}
				if($_FILES['attachfile']['size'][$key] > 1024*1000 ) {
					$error = $jieqiLang['g']['attach'].$v.$jieqiLang['g']['too_big'] ;
				}
				if(eregi('.\(gif|jpg|jpeg|png|bmp)$',$v) ){
					$fclass = "image";
				} else {
					$fclass = "file";
				}
				if(!empty($error) ){
					unlink($_FILES['attachfile']['tmp_name'][$key] );
				} else {
					$attachnum++;
					$attachs[$attachnum] = $key;
					$attachsinfo[$attachnum] = array('name'=>$v,
							'class'=>$fclass,
							'postfix'=>$fname_explode[0],
							'size'=>$_FILES['attachfile']['size'][$key]
							);
				}
			}	
		}
	}
	if($error) jieqi_printfail($error);

	if($special == 1) {
		//各个选项
		$polloptions =  $_REQUEST['polloptions'];
		$maxchoices = intval($_REQUEST['maxchoices'] );
		$maxchoices = $maxchoices >= count($polloptions) ? count($polloptions) : $maxchoices;
		$pollarray['multiple'] = !empty( $_REQUEST['multiplepoll'] );
		$pollarray['visible'] = empty( $_REQUEST['visiblepoll'] );
		if( preg_match("/^\d*$/", trim($maxchoices) ) && preg_match("/^\d*$/", trim($_REQUEST[expiration]) ) ) {
			if( !$pollarray['multiple'] ) {
				$pollarray['maxchoices'] = 1;
			} elseif(empty($maxchoices)) {
				$pollarray['maxchoices'] = 0;
			} elseif($maxchoices == 1) {
				$pollarray['multiple'] = 0;
				$pollarray['maxchoices'] = $maxchoices;
			} else {
				$pollarray['maxchoices'] = $maxchoices;
			}
			$pollarray['expiration'] = $nowtime + 86400 * $_REQUEST['expiration'];
		} else {
			jieqi_printfail($jieqiLang['expiration_or_maxSelected_error'] );
		}
	}


	$uid = $_SESSION['jieqiUserId'];
	$uname = $_SESSION['jieqiUserName'];

	//更新话题记录
	if( $istopic == 1 ){
		$thistopic->setVar('topicsubject',$_REQUEST['postsubject'] );
		$thistopic->setVar('lasttime',$nowtime);
		$thistopic->setVar('lastposterid',$uid);
		$thistopic->setVar('lastposter',$uname);
		$topic_handler->insert($thistopic);
	} 
	include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/attach.php');
	$attach_handler = JieqiattachHandler::getInstance('JieqiattachHandler');

	//处理旧的附件信息
	if( !empty($oldattachsinfo) ) {
		$oldattachsdir = "./g/$gid/attach/".date("Ymd",$oldpost->getVar('posttime') );
		if( !isset($_REQUEST['oldattachs']) ){
			$_REQUEST['oldattachs'] = array();
		}
		foreach($oldattachsinfo as $key=>$value ) {
			if( in_array($value['attachid'],$_REQUEST['oldattachs']) ) {
				$attachsinfo[] = $value;	
			} else {
				$oldattachsdir .= "/{$postid}_{$value[attachid]}.{$value[postfix]}";
				if(file_exists($oldattachsdir) ){
					$attach_handler->delete($value[attachid] );
					unlink($oldattachsdir);
				}	
			}
		}	
	}	

	//附件记录
	if( !empty($attachs) ) {
		foreach($attachs as $key=>$valule) {
			$newattach = $attach_handler->create();	
			$newattach->setVar('gid',$gid);
			$newattach->setVar('topicid',$_REQUEST['topicid']);
			$newattach->setVar('size',$attachsinfo[$key]['size']);
			$newattach->setVar('class',$attachsinfo[$key]['fclass']);
			$newattach->setVar('postfix',$attachsinfo[$key]['postfix']);
			$newattach->setVar('uptime',$attachsinfo[$key]['uptime']);
			$newattach->setVar('postid',0);
			$attach_handler->insert($newattach);
			$attachsinfo[$key]['attachid'] = $newattach->getVar('attachid');
		}
	}
	$attachsinfo_str = serialize($attachsinfo);

	//记录post
	$newpost = $post_handler->get($postid);
	$newpost->setVar('postsubject',$_REQUEST['postsubject'] );
	$newpost->setVar('posttext',$_REQUEST['posttext'] );
	$newpost->setVar('attachsinfo',$attachsinfo_str);
	$post_handler->insert($newpost);


	//edit poll
	if(is_array($_REQUEST['polloptions']) ) {
		include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/poll.php');	
		$poll_handler = JieqipollHandler::getInstance('JieqipollHandler');
		$newpoll = $poll_handler->get($topicid);
		$newpoll->setVar('maxchoices',$pollarray['maxchoices'] );
		$newpoll->setVar('multiple',$pollarray['multiple'] );
		$newpoll->setVar('expiration',$pollarray['expiration'] );
		$newpoll->setVar('gid',$gid );
		$poll_handler->insert($newpoll);
		
		foreach($_REQUEST['polloptions'] as $key=>$value ) {
			if( empty($value) ){
				$polloption_handler->delete( intval($_REQUEST['polloptionid'][$key])  );	
			} else {
				if($id = intval($_REQUEST['polloptionid'][$key]) ){
					$criteria = new CriteriaCompo(new Criteria('topicid',$topicid) );
					$criteria->add(new Criteria('polloptionid',$id) );
					$displayorder = intval($_REQUEST['displayorder'][$key]);
					$polloption_handler->updatefields("displayorder='$displayorder',polloption='$polloptions[$key]'",$criteria);
				} else {
					$newpolloption = $polloption_handler->create();
					$newpolloption->setVar('polloption',$polloptions[$key] );
					$newpolloption->setVar('topicid',$topicid);
					$newpolloption->setVar('displayorder',intval($_REQUEST['displayorder'][$key]) );
					$polloption_handler->insert($newpolloption);
				}
			}
		}
	}

	//上传附件
	$attachdir =  JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME."/g/$gid/attach";
	$attachdir .= '/'.date('Ymd',$nowtime);
	if (!file_exists($attachdir) ) {
		jieqi_createdir($attachdir);
	}	

	if( !empty($attachs) ){
		foreach($attachs as $key=>$value){
			$targetfile = $attachdir."/{$postid}_{$attachsinfo[$key][attachid]}.{$attachsinfo[$key][postfix]}";
			move_uploaded_file($_FILES['attachfile']['tmp_name'][$value],$targetfile);
			@chmod('0644',$targetfile);
		}
	}

	jieqi_jumppage("topic.php?g=$gid&topicid=$topicid&page=$_REQUEST[page]",LANG_DO_SUCCESS,$jieqiLang['g']['edit_post_success'] );
} 

if( $special ==1 ) $content_lang=$jieqiLang['g']['backdrop'];
else $content_lang = $jieqiLang['g']['content'];
$jieqiTpl->assign('content_lang',$content_lang);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/editpost.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>