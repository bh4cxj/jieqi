<?php
/**
 * 帖子内容赋值相关函数
 *
 * 帖子内容赋值相关函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: topiclist.php 286 2008-12-23 03:04:17Z juny $
 */

/**
 * 传入主题实例对象，返回适合模板赋值的主题信息数组
 * 
 * @param      object      $topic 主题实例
 * @access     public
 * @return     array
 */
function jieqi_topic_vars($topic){
	$ret = array();
	$ret['siteid'] = $topic->getVar('siteid');
	$ret['topicid'] = $topic->getVar('topicid');
	$ret['title'] = $topic->getVar('title');
	$ret['topictitle'] = $topic->getVar('title');
	$ret['ownerid'] = $topic->getVar('ownerid');
	$ret['posterid'] = $topic->getVar('posterid');
	$ret['poster'] = $topic->getVar('poster');
	$ret['posttime'] = $topic->getVar('posttime');
	$ret['replierid'] = $topic->getVar('replierid');
	$ret['replier'] = $topic->getVar('replier');
	$ret['replytime'] = $topic->getVar('replytime');
	$ret['views'] = $topic->getVar('views');
	$ret['replies'] = $topic->getVar('replies');
	$ret['istop'] = $topic->getVar('istop');
	$ret['isgood'] = $topic->getVar('isgood');
	$ret['islock'] = $topic->getVar('islock');
	$ret['rate'] = $topic->getVar('rate');
	$ret['attachment'] = $topic->getVar('attachment');
	$ret['needperm'] = $topic->getVar('needperm');
	$ret['needscore'] = $topic->getVar('needscore');
	$ret['needexp'] = $topic->getVar('needexp');
	$ret['needprice'] = $topic->getVar('needprice');
	$ret['sortid'] = $topic->getVar('sortid');
	$ret['iconid'] = $topic->getVar('iconid');
	$ret['typeid'] = $topic->getVar('typeid');
	$ret['linkurl'] = $topic->getVar('linkurl');
	$ret['size'] = $topic->getVar('size');
	$tmpary=unserialize($topic->getVar('lastinfo', 'n'));
	if(is_array($tmpary)){
		$ret['replyflag']=1;
		if(empty($ret['replierid'])) $ret['replierid']=$tmpary['uid'];
		if(strlen($ret['replier'])==0) $ret['replier']=jieqi_htmlstr($tmpary['uname']);
		if(empty($ret['replytime'])) $ret['replytime']=$tmpary['time'];
	}
	return $ret;
}

/**
 * 增加主题点击数
 * 
 * @param      int         $tid 主题ID
 * @param      string      $table 主题表名
 * @access     public
 * @return     bool
 */
function jieqi_topic_addviews($tid, $table){
	global $query;
	//载入统计处理函数
	include_once(JIEQI_ROOT_PATH.'/include/funstat.php');
	return jieqi_visit_stat($tid, $table, 'views', 'topicid', $query);
}

/**
 * 传入帖子实例对象，返回适合模板赋值的帖子信息数组
 * 
 * @param      object      $post 帖子实例
 * @param      array       $configs 配置参数
 * @param      array       $addvars 附加赋值变量
 * @param      bool        $enableubb 是否允许解析UBB代码
 * @access     public
 * @return     array
 */
function jieqi_post_vars($post, $configs=array(), $addvars=array(), $enableubb=true){
	global $jieqiTxtcvt;
	global $jieqiHonors;
	global $jieqiGroups;
	global $jieqiModules;

	if(!isset($jieqiHonors)) jieqi_getconfigs('system', 'honors', 'jieqiHonors');
	if(!defined('JIEQI_SHOW_BADGE')){
		if(!empty($jieqiModules['badge']['publish']) && is_file($GLOBALS['jieqiModules']['badge']['path'].'/include/badgefunction.php')){
			include_once($jieqiModules['badge']['path'].'/include/badgefunction.php');
			define('JIEQI_SHOW_BADGE', 1);
		}else{
			define('JIEQI_SHOW_BADGE', 0);
		}
	}
	if(is_array($addvars)) $ret = $addvars;
	else $ret = array();
	$ret['postid']=$post->getVar('postid');
	$ret['siteid']=$post->getVar('siteid');
	$ret['istopic']=$post->getVar('istopic');
	$ret['replypid']=$post->getVar('replypid');
	$ret['ownerid']=$post->getVar('ownerid');
	$ret['userid']=intval($post->getVar('uid'));
	$ret['posterid']=$post->getVar('posterid');
	$ret['poster']=$ret['username'];
	$ret['posttime']=$post->getVar('posttime');
	$ret['posterip']=$post->getVar('posterip');
	$ret['editorid']=$post->getVar('editorid');
	$ret['editor']=$post->getVar('editor');
	$ret['edittime']=$post->getVar('edittime');
	$ret['editorip']=$post->getVar('editorip');
	$ret['editnote']=$post->getVar('editnote');
	$ret['iconid']=$post->getVar('iconid');
	$ret['subject']=jieqi_substr($post->getVar('subject'),0,56);
	$ret['size']=$post->getVar('size');

	$ret['attachimages']=array();
	$ret['attachfiles']=array();
	$tmpvar=$post->getVar('attachment','n');
	if(!empty($tmpvar)){
		$attachs=unserialize($tmpvar);
		foreach($attachs as $key=>$val){
			$url=jieqi_uploadurl($configs['attachdir'], $configs['attachurl'], JIEQI_MODULE_NAME).'/'.date('Ymd', $post->getVar('posttime', 'n')).'/'.$post->getVar('postid', 'n').'_'.$val['attachid'].'.'.$val['postfix'];
			if($val['class']=='image'){
				$ret['attachimages'][]=array('id'=>$val['attachid'], 'name'=>jieqi_htmlstr($val['name']), 'url'=>$url, 'posttime'=>$post->getVar('posttime', 'n'), 'postid'=>$post->getVar('postid', 'n'), 'postfix'=>$val['postfix'], 'class'=>$val['class'], 'size'=>$val['size'], 'size_k'=>ceil($val['size']/1024));
			}else{
				$ret['attachfiles'][]=array('id'=>$val['attachid'], 'name'=>jieqi_htmlstr($val['name']), 'url'=>$url, 'posttime'=>$post->getVar('posttime', 'n'), 'postid'=>$post->getVar('postid', 'n'), 'postfix'=>$val['postfix'], 'class'=>$val['class'], 'size'=>$val['size'], 'size_k'=>ceil($val['size']/1024));
			}
		}
	}

	if($enableubb){
		if(!is_object($jieqiTxtcvt)){
			include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
			$jieqiTxtcvt=TextConvert::getInstance('TextConvert');
		}
		$ret['posttext']=$jieqiTxtcvt->displayTarea($post->getVar('posttext','n'), 0, 1, 1, 1, 1, 'screen.width*0.75');
	}else{
		if(!is_object($jieqiTxtcvt)){
			include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
			$jieqiTxtcvt=TextConvert::getInstance('TextConvert');
		}
		$ret['posttext'] = jieqi_htmlstr(preg_replace(array('/\[\/?(code|url|color|font|align|email|b|i|u|d|img|quote|size)[^\[\]]*\]/is'), '', $post->getVar('posttext','n')));
		$ret['posttext'] = $jieqiTxtcvt->smile(preg_replace('/https?:\/\/[^\s\r\n\t\f<>]+/i','<a href="\\0">\\0</a>',$ret['posttext']));
	}
	//加入文字水印
	if(!empty($configs['textwatermark']) && !defined('JIEQI_WAP_PAGE')){
		$contentary=explode('<br />
<br />', $ret['posttext']);
		$ret['posttext']='';
		foreach($contentary as $v){
			if(empty($ret['posttext'])) $ret['posttext'].=$v;
			else{
				srand((double) microtime() * 1000000);
				$randstr='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$randlen=rand(10, 20);
				$randtext = '';
				$l = strlen($randstr)-1;
				for($i = 0;$i < $randlen; $i++){
					$num = rand(0, $l);
					$randtext .= $randstr[$num];
				}
				$textwatermark=str_replace('<{$randtext}>', $randtext, $configs['textwatermark']);
				$ret['posttext'].='<br />
'.$textwatermark.$v;
			}
		}
	}

	//本贴用户信息
	if($ret['userid'] > 0){
		$ret['useruname']=$post->getVar('uname');
		$ret['username']= $post->getVar('name')=='' ? $post->getVar('uname') : $post->getVar('name');
		$ret['viewemail']=$post->getVar('viewemail');
		$ret['email']=$post->getVar('email');
		$ret['qq']=$post->getVar('qq');
		$ret['msn']=$post->getVar('msn');
		$ret['groupname']=$jieqiGroups[$post->getVar('groupid')];
		$ret['regdate']=$post->getVar('regdate');
		$ret['experience']=$post->getVar('experience');
		$ret['score']=$post->getVar('score');
		$ret['sign']=$post->getVar('sign');
		$ret['avatar']=$post->getVar('avatar');
		$honorid=intval(jieqi_gethonorid($post->getVar('score'), $jieqiHonors));
		$ret['honor']=isset($jieqiHonors[$honorid]['name'][intval($post->getVar('workid', 'n'))]) ? $jieqiHonors[$honorid]['name'][intval($post->getVar('workid', 'n'))] : $jieqiHonors[$honorid]['caption'];

		//头像图片
		if($ret['avatar'] > 0){
			$tmpary = jieqi_geturl('system', 'avatar', $ret['userid'], 'a', $ret['avatar']);
			$ret['base_avatar'] = $tmpary['d'];
			$ret['url_avatar'] = $tmpary['l'];
			$ret['url_avatars'] = $tmpary['s'];
			$ret['url_avatari'] = $tmpary['i'];
		}
		//徽章图片
		if(JIEQI_SHOW_BADGE == 1){
			$checkfile = (JIEQI_LOCAL_URL == JIEQI_MAIN_URL) ? true : false;
			$checkfile = false;
			//等级徽章
			$ret['groupurl']=getbadgeurl(1, $post->getVar('groupid'), 0, $checkfile);
			//头衔徽章
			$ret['honorurl']=getbadgeurl(2, $honorid, 0, $checkfile);
			//自定义徽章
			$badgeary=unserialize($post->getVar('badges', 'n'));
			$ret['badgerows']=array();
			if(is_array($badgeary)){
				$m=0;
				foreach($badgeary as $badge){
					$ret['badgerows'][$m]['imageurl']=getbadgeurl($badge['btypeid'], $badge['linkid'], $badge['imagetype']);
					$ret['badgerows'][$m]['caption']=jieqi_htmlstr($badge['caption']);
					$m++;
				}
			}
		}
	}
	return $ret;
}

/**
 * 发帖提交后的内容校验
 * 
 * $post_set 相关参数：
 * 'module' - 程序所属模块名
 * 'ownerid' - 论坛或所有者ID
 * 'topicid' - 主题ID
 * 'postid' - 帖子ID
 * 'posttime' - 发帖时间
 * 'title' - 标题在$_POST里面的键名
 * 'content' - 内容在$_POST里面的键名
 * 'checkcode' - 验证码
 * 'attachment' - 附件信息，附件数组serialize后的字符串
 * 'emptytitle' - bool类型，主题贴是都允许不填主题，false-不允许，true-允许
 * 'isnew' - bool类型，true表示发新帖，false表示编辑帖子
 * 'istopic' - bool类型，true表示主题帖子，false表示回复帖子
 * 'istop' - bool类型，是否全区置顶帖子
 * 'sname' - string类型，发帖时间在session里面保存的变量名
 * 'attachfile' - array类型，附件上传信息变量
 * 'oldattach' - array类型，老的附件是否保留信息
 * 
 * $configs 相关参数：
 * 'minposttime' - int类型，发帖最少时间间隔，单位是秒
 * 'badpostwords' - string类型，禁止发表的词语，每个一行
 * 'checkpostrubbish' - bool类型，是否检查灌水贴
 * 'minpostsize' - int类型，帖子内容最少几个字节
 * 'maxpostsize' - int类型，帖子内容最多几个字节
 * 'hidepostwords' - string类型，发表后隐藏的词语，每个一行
 * 
 * @param      array       $post_set 发帖信息设置
 * @param      array       $configs 检查相关参数设置
 * @param      array       $check_errors 错误信息数组
 * @access     public
 * @return     bool
 */
function jieqi_post_checkvar(&$post_set, $configs, &$check_errors){
	global $jieqiLang;
	global $jieqiConfigs;
	if(!isset($jieqiLang['system']['post'])) jieqi_loadlang('post', 'system');
	if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
	if(!is_array($check_errors)) $check_errors = array();
	$num_errors = count($check_errors);
	include_once(JIEQI_ROOT_PATH.'/include/checker.php');
	$checker = new JieqiChecker();
	//提交处理
	if(isset($jieqiConfigs['system']['posttitlemax'])) $jieqiConfigs['system']['posttitlemax']= intval($jieqiConfigs['system']['posttitlemax']);
	if(empty($jieqiConfigs['system']['posttitlemax']) || $jieqiConfigs['system']['posttitlemax'] <= 10) $jieqiConfigs['system']['posttitlemax']=60;
	$post_set['topictitle'] = jieqi_substr(trim($post_set['topictitle']),0,$jieqiConfigs['system']['posttitlemax'],'...');
	//检查时间，是否允许发贴
	if(!empty($jieqiConfigs['system']['postintervaltime']) && !empty($post_set['isnew'])){
		if(!$checker->interval_time($jieqiConfigs['system']['postintervaltime'], $post_set['sname'], 'jieqiVisitTime')) $check_errors[] = sprintf($jieqiLang['system']['post_time_limit'], $jieqiConfigs['system']['postintervaltime']);
	}
	//验证码
	if($jieqiConfigs['system']['postcheckcode'] > 0){
		if($post_set['checkcode'] != $_SESSION['jieqiCheckCode']) $check_errors[] = $jieqiLang['system']['post_checkcode_error'];
	}
	//检查禁用单词
	if(!empty($jieqiConfigs['system']['postdenywords'])){
		$matchwords1 = $checker->deny_words($post_set['topictitle'], $jieqiConfigs['system']['postdenywords'], true);
		$matchwords2 = $checker->deny_words($post_set['posttext'], $jieqiConfigs['system']['postdenywords'], true);
		if(is_array($matchwords1) || is_array($matchwords2)){
			$matchwords=array();
			if(is_array($matchwords1)) $matchwords = array_merge($matchwords, $matchwords1);
			if(is_array($matchwords2)) $matchwords = array_merge($matchwords, $matchwords2);
			$check_errors[] = sprintf($jieqiLang['system']['post_words_deny'], implode(' ', jieqi_funtoarray('htmlspecialchars',$matchwords)));
		}
	}
	//检查灌水
	if(!empty($jieqiConfigs['system']['postdenyrubbish'])){
		if(!$checker->deny_rubbish($post_set['posttext'],$jieqiConfigs['system']['postdenyrubbish'])) $check_errors[] = $jieqiLang['system']['post_words_water'];
	}
	//检查标题
	if(!empty($post_set['istopic']) && $checker->is_required($post_set['topictitle'])==false){
		if($post_set['emptytitle']){
			$post_set['topictitle'] = jieqi_substr(str_replace(array("\r","\n","\t"," "),'',preg_replace('/\[[^\[\]]+\]([^\[\]]*)\[\/[^\[\]]+\]/isU','\\1',$post_set['posttext'])),0,60);
			if(strlen($post_set['emptytitle'])==0) $post_set['emptytitle']='--';
		}else{
			$check_errors[] = $jieqiLang['system']['post_need_title'];
		}
	}

	//检查内容
	if(!$checker->is_required($post_set['posttext'])) $check_errors[] = $jieqiLang['system']['post_need_content'];
	//检查最少字数
	if(!empty($jieqiConfigs['system']['postminsize']) && !$checker->str_min($post_set['posttext'], $jieqiConfigs['system']['postminsize'])) $check_errors[] = sprintf($jieqiLang['system']['post_min_content'], $jieqiConfigs['system']['postminsize']);
	//检查最多字数
	if(!empty($jieqiConfigs['system']['postmaxsize']) && !$checker->str_max($post_set['posttext'], $jieqiConfigs['system']['postmaxsize'])) $check_errors[] = sprintf($jieqiLang['system']['post_max_content'], $jieqiConfigs['system']['postmaxsize']);
	//替换单词
	if(isset($jieqiConfigs['system']['postreplacewords']) && !empty($jieqiConfigs['system']['postreplacewords'])){
		$checker->replace_words($post_set['topictitle'], $jieqiConfigs['system']['postreplacewords']);
		$checker->replace_words($post_set['posttext'], $jieqiConfigs['system']['postreplacewords']);
	}
	return (count($check_errors) > $num_errors) ? false : true;
}

/**
 * 发帖提交后的附件校验
 * 
 * @param      array       $post_set 附件信息设置
 * @param      array       $configs 检查相关参数设置
 * @param      array       $check_errors 错误信息数组
 * @param      array       $attachary 处理后的附件信息数组
 * @access     public
 * @return     void
 */
function jieqi_post_checkattach(&$post_set, $configs, &$check_errors, &$attachary){
	global $jieqiLang;
	if(!isset($jieqiLang['system']['post'])) jieqi_loadlang('post', 'system');
	$attachary=array();
	$attachnum=0;
	if(!is_array($check_errors)) $check_errors = array();
	$num_errors = count($check_errors);

	//检查上传文件
	if(is_numeric($configs['maxattachnum']) && $configs['maxattachnum']>0 && empty($check_errors)){
		$maxfilenum=intval($configs['maxattachnum']);
		$typeary=explode(' ',trim($configs['attachtype']));
		if(!empty($post_set['attachfile']['name'])){
			foreach($post_set['attachfile']['name'] as $k=>$v){
				if(!empty($v)){
					$tmpary=explode('.', $v);
					$tmpint=count($tmpary)-1;
					$tmpary[$tmpint]=strtolower(trim($tmpary[$tmpint]));
					$denyary=array('htm', 'html', 'shtml', 'php', 'asp', 'aspx', 'jsp', 'pl', 'cgi');
					if(empty($tmpary[$tmpint]) || !in_array($tmpary[$tmpint], $typeary)){
						$check_errors[] = sprintf($jieqiLang['system']['post_uptype_error'], $v);
					}elseif(in_array($tmpary[$tmpint], $denyary)){
						$check_errors[] = sprintf($jieqiLang['system']['post_uptype_safe'], $tmpary[$tmpint]);
					}
					if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$v)){
						$fclass='image';
						if($post_set['attachfile']['size'][$k] > (intval($configs['maximagesize']) * 1024)) $check_errors[] = sprintf($jieqiLang['system']['post_upsize_over'], $v, intval($configs['maximagesize']));
					}else{
						$fclass='file';
						if($post_set['attachfile']['size'][$k] > (intval($configs['maxfilesize']) * 1024)) $check_errors[] = sprintf($jieqiLang['system']['post_upsize_over'], $v, intval($configs['maxfilesize']));
					}
					$attachary[$attachnum]=array('name'=>$v, 'class'=>$fclass, 'postfix'=>$tmpary[$tmpint], 'size'=>$post_set['attachfile']['size'][$k], 'order'=>$k);
					$attachnum++;
				}
			}
		}
	}
	//验证有错误的话删除附件临时文件
	if(!empty($check_errors) && !empty($post_set['attachfile']['name'])){
		foreach($post_set['attachfile']['name'] as $k=>$v){
			jieqi_delfile($post_set['attachfile']['tmp_name'][$k]);
		}
	}
	return (count($check_errors) > $num_errors) ? false : true;
}

/**
 * 发表新主题
 * 
 * @param      array       $post_set 发帖信息设置
 * @param      object      $newTopic 主题实例
 * @access     public
 * @return     void
 */
function jieqi_topic_newset(&$post_set, &$newTopic){
	if(empty($_SESSION['jieqiUserId'])){
		$tmpuid=0;
		$tmpuname='';
	}else{
		$tmpuid=$_SESSION['jieqiUserId'];
		$tmpuname=$_SESSION['jieqiUserName'];
	}
	$newTopic->setVar('siteid', JIEQI_SITE_ID);
	$newTopic->setVar('ownerid', $post_set['ownerid']);
	$newTopic->setVar('title', $post_set['topictitle']);
	$newTopic->setVar('posterid', $tmpuid);
	$newTopic->setVar('poster', $tmpuname);
	$newTopic->setVar('posttime', JIEQI_NOW_TIME);
	$newTopic->setVar('replierid', 0);
	$newTopic->setVar('replier', $tmpuname);
	$newTopic->setVar('replytime', JIEQI_NOW_TIME);
	$newTopic->setVar('views', 0);
	$newTopic->setVar('replies', 0);
	$newTopic->setVar('islock', 0);
	if(isset($post_set['istop'])) $newTopic->setVar('istop', intval($post_set['istop']));
	else $newTopic->setVar('istop', 0);
	$newTopic->setVar('isgood', 0);
	$newTopic->setVar('rate', 0);
	$newTopic->setVar('attachment', 0);
	$newTopic->setVar('needperm', 0);
	$newTopic->setVar('needscore', 0);
	$newTopic->setVar('needexp', 0);
	$newTopic->setVar('needprice', 0);
	$newTopic->setVar('sortid', 0);
	$newTopic->setVar('iconid', 0);
	$newTopic->setVar('typeid', 0);
	$newTopic->setVar('linkurl', '');
	$newTopic->setVar('size', 0);
	//$newTopic->setVar('lastinfo', '');
	$lastinfo=serialize(array('time'=>JIEQI_NOW_TIME, 'uid'=>$tmpuid, 'uname'=>$tmpuname));
	$newTopic->setVar('lastinfo', $lastinfo);
}

/**
 * 回复主题时候更新
 * 
 * @param      object      $topic 主题实例
 * @access     public
 * @return     bool
 */
function jieqi_topic_upreply(&$topic){
	if(empty($_SESSION['jieqiUserId'])){
		$tmpuid=0;
		$tmpuname='';
	}else{
		$tmpuid=$_SESSION['jieqiUserId'];
		$tmpuname=$_SESSION['jieqiUserName'];
	}
	if($topic->getVar('islock')==1 && (empty($_SESSION['jieqiUserId']) || $_SESSION['jieqiUserId'] != $topic->getVar('posterid', 'n'))) return false;
	$topic->setVar('replierid', $tmpuid);
	$topic->setVar('replier', $tmpuname);
	$topic->setVar('replytime', JIEQI_NOW_TIME);
	$topic->setVar('replies', $topic->getVar('replies')+1);

	$lastinfo=serialize(array('time'=>JIEQI_NOW_TIME, 'uid'=>$tmpuid, 'uname'=>$tmpuname));
	$topic->setVar('lastinfo', $lastinfo);
	return true;
}

/**
 * 附件记录数据库
 * 
 * @param      array       $post_set 发帖信息设置
 * @param      array       $attachary 附件信息数组
 * @param      object      $attachs_handler 附件查询句柄
 * @access     public
 * @return     void
 */
function jieqi_post_attachdb(&$post_set, &$attachary, &$attachs_handler){
	foreach($attachary as $k=>$v){
		$newAttach = $attachs_handler->create();
		$newAttach->setVar('siteid', JIEQI_SITE_ID);
		$newAttach->setVar('topicid', $post_set['topicid']);
		if(isset($post_set['postid'])) $newAttach->setVar('postid', $post_set['postid']);
		else $newAttach->setVar('postid', 0);
		$newAttach->setVar('name', $attachary[$k]['name']);
		$newAttach->setVar('description', '');
		$newAttach->setVar('class', $attachary[$k]['class']);
		$newAttach->setVar('postfix', $attachary[$k]['postfix']);
		$newAttach->setVar('size', $attachary[$k]['size']);
		$newAttach->setVar('hits', 0);
		$newAttach->setVar('needperm', 0);
		$newAttach->setVar('needscore', 0);
		$newAttach->setVar('needexp', 0);
		$newAttach->setVar('needprice', 0);
		if(isset($post_set['posttile'])) $newAttach->setVar('uptime', $post_set['posttile']);
		else $newAttach->setVar('uptime', JIEQI_NOW_TIME);
		$newAttach->setVar('uid', intval($_SESSION['jieqiUserId']));
		$newAttach->setVar('remote',0);
		if($attachs_handler->insert($newAttach)){
			$attachid=$newAttach->getVar('attachid');
			$attachary[$k]['attachid']=$attachid;
		}else{
			$attachary[$k]['attachid']=0;
		}
	}
}

/**
 * 附件文件保存
 * 
 * @param      array       $post_set 发帖信息设置
 * @param      array       $attachary 附件信息数组
 * @param      array       $configs 配置参数
 * @access     public
 * @return     void
 */
function jieqi_post_attachfile(&$post_set, &$attachary, $configs){
	//判断是否加水印
	$make_image_water = false;
	if(function_exists('gd_info') && $configs['attachwater'] > 0 && JIEQI_MODULE_VTYPE != '' && JIEQI_MODULE_VTYPE != 'Free'){
		if(strpos($configs['attachwimage'], '/')===false && strpos($configs['attachwimage'], '\\')===false) $water_image_file = $GLOBALS['jieqiModules'][$post_set['module']]['path'].'/images/'.$configs['attachwimage'];
		else $water_image_file = $configs['attachwimage'];
		if(is_file($water_image_file)){
			$make_image_water = true;
			include_once(JIEQI_ROOT_PATH.'/lib/image/imagewater.php');
		}
	}
	$attachdir = jieqi_uploadpath($configs['attachdir'], $post_set['module']);
	if (!file_exists($attachdir)) jieqi_createdir($attachdir);
	$attachdir .= '/'.date('Ymd',$post_set['posttime']);
	if (!file_exists($attachdir)) jieqi_createdir($attachdir);
	foreach($attachary as $k=>$v){
		$attach_save_path = $attachdir.'/'.$post_set['postid'].'_'.$attachary[$k]['attachid'].'.'.$attachary[$k]['postfix'];
		$tmp_attachfile = dirname($_FILES['attachfile']['tmp_name'][$v['order']]).'/'.basename($attach_save_path);
		@move_uploaded_file($_FILES['attachfile']['tmp_name'][$v['order']], $tmp_attachfile);
		//图片加水印
		if($make_image_water && eregi("\.(gif|jpg|jpeg|png)$",$tmp_attachfile)){
			$img = new ImageWater();
			$img->save_image_file = $tmp_attachfile;
			$img->codepage = JIEQI_SYSTEM_CHARSET;
			$img->wm_image_pos = $configs['attachwater'];
			$img->wm_image_name = $water_image_file;
			$img->wm_image_transition  = $configs['attachwtrans'];
			$img->jpeg_quality = $configs['attachwquality'];
			$img->create($tmp_attachfile);
			unset($img);
		}
		jieqi_copyfile($tmp_attachfile, $attach_save_path, 0777, true);
	}
}


/**
 * 编辑帖子时处理老的附件是否需要删除
 * 
 * @param      array       $post_set 发帖信息设置
 * @param      array       $configs 配置参数
 * @param      object      $attachs_handler 附件查询句柄
 * @access     public
 * @return     void
 */
function jieqi_post_attachold(&$post_set, $configs, &$attachs_handler){
	//处理旧附件
	$tmpattachs=$post_set['attachment'];
	$attacholds=array();
	if(!empty($tmpattachs)){
		$tmpattachary=unserialize($tmpattachs);
		if(!is_array($tmpattachary)) $tmpattachary=array();
		if(!is_array($post_set['oldattach'])){
			if(is_string($post_set['oldattach'])) $post_set['oldattach']=array($post_set['oldattach']);
			else $post_set['oldattach']=array();
		}
		foreach($tmpattachary as $val){
			if(in_array($val['attachid'], $post_set['oldattach'])){
				$attacholds[]=$val;
			}else{
				//删除旧附件
				$attachs_handler->delete($val['attachid']);
				$afname = jieqi_uploadpath($configs['attachdir'], JIEQI_MODULE_NAME).'/'.date('Ymd', $post_set['posttime']).'/'.$post_set['postid'].'_'.$val['attachid'].'.'.$val['postfix'];
				if(file_exists($afname)) jieqi_delfile($afname);
			}
		}
	}
	return $attacholds;
}

/**
 * 增加帖子
 * 
 * @param      array       $post_set 发帖信息设置
 * @param      object      $newPost 帖子实例
 * @access     public
 * @return     void
 */
function jieqi_post_newset(&$post_set, &$newPost){
	if(empty($_SESSION['jieqiUserId'])){
		$tmpuid=0;
		$tmpuname='';
	}else{
		$tmpuid=$_SESSION['jieqiUserId'];
		$tmpuname=$_SESSION['jieqiUserName'];
	}
	$newPost->setVar('siteid', JIEQI_SITE_ID);
	$newPost->setVar('topicid', $post_set['topicid']);
	$istopic = isset($post_set['istopic']) ? $post_set['istopic'] : 0;
	$newPost->setVar('istopic', $istopic);
	$newPost->setVar('replypid', 0);
	$newPost->setVar('ownerid', $post_set['ownerid']);
	$newPost->setVar('posterid', $tmpuid);
	$newPost->setVar('poster', $tmpuname);
	$newPost->setVar('posttime', JIEQI_NOW_TIME);
	$newPost->setVar('posterip', jieqi_userip());
	$newPost->setVar('editorid', 0);
	$newPost->setVar('editor', '');
	$newPost->setVar('edittime', JIEQI_NOW_TIME);
	$newPost->setVar('editorip', '');
	$newPost->setVar('editnote', '');
	$newPost->setVar('iconid', 0);
	$newPost->setVar('attachment', $post_set['attachment']);
	$newPost->setVar('subject', $post_set['topictitle']);
	$newPost->setVar('posttext', $post_set['posttext']);
	$newPost->setVar('size', strlen($post_set['posttext']));
}



/**
 * 帖子编辑后更新帖子表
 * 
 * @param      array       $post_set 发帖信息设置
 * @param      string      $table 表名
 * @access     public
 * @return     bool
 */
function jieqi_post_upedit(&$post_set, $table){
	global $query;
	if(!is_a($query, 'JieqiQueryHandler')){
		jieqi_includedb();
		$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	}
	$sql="UPDATE ".$table." SET editorid=".intval($_SESSION['jieqiUserId']).", editor='".jieqi_dbslashes(strval($_SESSION['jieqiUserName']))."', edittime=".intval(JIEQI_NOW_TIME).", subject='".jieqi_dbslashes($post_set['topictitle'])."', posttext='".jieqi_dbslashes($post_set['posttext'])."', attachment='".jieqi_dbslashes($post_set['attachment'])."' WHERE postid=".intval($post_set['postid']);
	return $query->execute($sql);
}

/**
 * 帖子编辑后更新主题表
 * 
 * @param      array       $post_set 发帖信息设置
 * @param      string      $table 表名
 * @access     public
 * @return     bool
 */
function jieqi_topic_upedit(&$post_set, $table){
	global $query;
	if(!is_a($query, 'JieqiQueryHandler')){
		jieqi_includedb();
		$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	}
	$sql="UPDATE ".$table." SET title='".jieqi_dbslashes($post_set['topictitle'])."' WHERE topicid=".intval($post_set['topicid']);
	return $query->execute($sql);
}

?>