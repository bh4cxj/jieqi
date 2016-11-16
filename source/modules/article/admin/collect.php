<?php
/**
 * 文章采集界面显示
 *
 * 文章采集界面显示
 * 
 * 调用模板：/modules/article/templates/admin/chapterlist.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: collect.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//发表文章权限
jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, false);
jieqi_loadlang('collect', JIEQI_MODULE_NAME);
jieqi_loadlang('article', JIEQI_MODULE_NAME);
@set_time_limit(0);
@session_write_close();

$self_filename='collect.php';
if(!empty($_SERVER['SCRIPT_NAME']) && substr($_SERVER['SCRIPT_NAME'],-4)=='.php'){
	$tmpary=explode('/',$_SERVER['SCRIPT_NAME']);
	if(!empty($tmpary[count($tmpary)-1])) $self_filename=$tmpary[count($tmpary)-1];
}
jieqi_getconfigs(JIEQI_MODULE_NAME, 'collectsite');

include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'show';
switch($_REQUEST['action']){
	case 'newarticle':
		$_REQUEST['articlename'] = trim($_REQUEST['articlename']);
		$_REQUEST['author'] = trim($_REQUEST['author']);
		$_REQUEST['agent'] = trim($_REQUEST['agent']);
		$errtext='';
		//检查标题
		if (strlen($_REQUEST['articlename'])==0) $errtext .= $jieqiLang['article']['need_article_title'].'<br />';
		elseif (!jieqi_safestring($_REQUEST['articlename'])) $errtext .= $jieqiLang['article']['limit_article_title'].'<br />';

		if(empty($errtext)) {
			include_once($jieqiModules['article']['path'].'/class/article.php');
			$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');

			//检查文章是否已存在
			$criteria=new CriteriaCompo(new Criteria('articlename', $_REQUEST['articlename'], '='));
			$res=$article_handler->queryObjects($criteria);
			$articleobj=$article_handler->getObject($res);
			if(is_object($articleobj)){
				jieqi_msgwin($jieqiLang['article']['article_already_exists'],sprintf($jieqiLang['article']['collect_exists_note'], jieqi_htmlstr($_REQUEST['articlename']), $article_static_url.'/admin/updatecollect.php?siteid='.urlencode($_REQUEST['siteid']).'&fromid='.urlencode($_REQUEST['fromid']).'&toid='.urlencode($articleobj->getVar('articleid', 'n')), jieqi_geturl('article', 'article', $articleobj->getVar('articleid', 'n'), 'info')));
				exit;
			}

			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$newArticle = $article_handler->create();
			$newArticle->setVar('siteid', JIEQI_SITE_ID);
			$newArticle->setVar('postdate', JIEQI_NOW_TIME);
			$newArticle->setVar('lastupdate', JIEQI_NOW_TIME);
			$newArticle->setVar('articlename', $_REQUEST['articlename']);
			$newArticle->setVar('keywords', trim($_REQUEST['keywords']));
			$newArticle->setVar('initial', jieqi_getinitial($_REQUEST['articlename']));
			$agentobj=false;
			if(!empty($_REQUEST['agent'])) $agentobj=$users_handler->getByname($_REQUEST['agent']);
			if(is_object($agentobj)){
				$newArticle->setVar('agentid', $agentobj->getVar('uid'));
				$newArticle->setVar('agent', $agentobj->getVar('uname', 'n'));
			}else{
				$newArticle->setVar('agentid', 0);
				$newArticle->setVar('agent', '');
			}

			if(jieqi_checkpower($jieqiPower['article']['transarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
				//允许转载的情况
				if(empty($_REQUEST['author']) || (!empty($_SESSION['jieqiUserId']) && $_REQUEST['author']==$_SESSION['jieqiUserName'])){
					if(!empty($_SESSION['jieqiUserId'])){
						$newArticle->setVar('authorid', $_SESSION['jieqiUserId']);
						$newArticle->setVar('author', $_SESSION['jieqiUserName']);
					}else{
						$newArticle->setVar('authorid', 0);
						$newArticle->setVar('author', '');
					}
				}else{
					//转载作品
					$newArticle->setVar('author', $_REQUEST['author']);
					if($_REQUEST['authorflag']){
						$authorobj=$users_handler->getByname($_REQUEST['author']);
						if(is_object($authorobj)) $newArticle->setVar('authorid', $authorobj->getVar('uid'));
					}else{
						$newArticle->setVar('authorid', 0);
					}
				}
			}else{
				//不允许转载的情况
				if(!empty($_SESSION['jieqiUserId'])){
					$newArticle->setVar('authorid', $_SESSION['jieqiUserId']);
					$newArticle->setVar('author', $_SESSION['jieqiUserName']);
				}else{
					$newArticle->setVar('authorid', 0);
					$newArticle->setVar('author', '');
				}
			}

			if(!empty($_SESSION['jieqiUserId'])){
				$newArticle->setVar('posterid', $_SESSION['jieqiUserId']);
				$newArticle->setVar('poster', $_SESSION['jieqiUserName']);
			}else{
				$newArticle->setVar('posterid', 0);
				$newArticle->setVar('poster', '');
			}

			$newArticle->setVar('lastchapterid', 0);
			$newArticle->setVar('lastchapter', '');
			$newArticle->setVar('lastvolumeid', 0);
			$newArticle->setVar('lastvolume', '');
			$newArticle->setVar('chapters', 0);
			$newArticle->setVar('size', 0);
			$newArticle->setVar('fullflag', $_REQUEST['fullflag']);
			$newArticle->setVar('sortid', $_REQUEST['sortid']);
			$newArticle->setVar('intro', $_REQUEST['intro']);
			$newArticle->setVar('notice', '');
			$newArticle->setVar('setting', '');
			$newArticle->setVar('lastvisit', 0);
			$newArticle->setVar('dayvisit', 0);
			$newArticle->setVar('weekvisit', 0);
			$newArticle->setVar('monthvisit', 0);
			$newArticle->setVar('allvisit', 0);
			$newArticle->setVar('lastvote', 0);
			$newArticle->setVar('dayvote', 0);
			$newArticle->setVar('weekvote', 0);
			$newArticle->setVar('monthvote', 0);
			$newArticle->setVar('allvote', 0);
			$newArticle->setVar('goodnum', 0);
			$newArticle->setVar('badnum', 0);
			$newArticle->setVar('toptime', 0);
			$newArticle->setVar('saleprice', 0);
			$newArticle->setVar('salenum', 0);
			$newArticle->setVar('totalcost', 0);
			$newArticle->setVar('power', 0);
			$newArticle->setVar('articletype', 0);
			$newArticle->setVar('permission', $_REQUEST['permission']);
			$newArticle->setVar('firstflag', $_REQUEST['firstflag']);
			$imgflag=0;
			$_REQUEST['articleimage']=trim($_REQUEST['articleimage']);
			if(!empty($_REQUEST['articleimage'])){
				$imagetype=strtolower(strrchr($_REQUEST['articleimage'], '.'));
				if(!empty($imagetype)){
					if($imagetype==$jieqiConfigs['article']['imagetype']) $imgflag=1;
					else{
						switch($imagetype){
							case '.gif':
								$tmpint=1;
								break;
							case '.jpg':
								$tmpint=2;
								break;
							case '.jpeg':
								$tmpint=3;
								break;
							case '.png':
								$tmpint=4;
								break;
							case '.bmp':
								$tmpint=5;
								break;
							default:
								$tmpint=0;
						}
						if($tmpint>0){
							$imgflag=$tmpint * 4 +1;
						}
					}
				}
			}
			$newArticle->setVar('imgflag', $imgflag);
			if(jieqi_checkpower($jieqiPower['article']['needcheck'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
				$newArticle->setVar('display', 0);
			}else{
				$newArticle->setVar('display', 1);  //待审文章
			}
			if (!$article_handler->insert($newArticle)) jieqi_printfail($jieqiLang['article']['collect_newarticle_failure']);
			else {
				$id=$newArticle->getVar('articleid');
				include_once($jieqiModules['article']['path'].'/class/package.php');
				$package=new JieqiPackage($id);
				$package->initPackage(array('id'=>$newArticle->getVar('articleid','n'), 'title'=>$newArticle->getVar('articlename', 'n'), 'creatorid'=>$newArticle->getVar('authorid','n'), 'creator'=>$newArticle->getVar('author','n'), 'subject'=>$newArticle->getVar('keywords','n'), 'description'=>$newArticle->getVar('intro', 'n'), 'publisher'=>JIEQI_SITE_NAME, 'contributorid'=>$newArticle->getVar('posterid', 'n'), 'contributor'=>$newArticle->getVar('poster', 'n'), 'sortid'=>$newArticle->getVar('sortid', 'n'), 'typeid'=>$newArticle->getVar('typeid', 'n'), 'articletype'=>$newArticle->getVar('articletype', 'n'), 'permission'=>$newArticle->getVar('permission', 'n'), 'firstflag'=>$newArticle->getVar('firstflag', 'n'), 'fullflag'=>$newArticle->getVar('fullflag', 'n'), 'imgflag'=>$newArticle->getVar('imgflag', 'n'), 'power'=>$newArticle->getVar('power', 'n'), 'display'=>$newArticle->getVar('display', 'n')));
				//增加发文积分
				/*
				if(!empty($jieqiConfigs['article']['scorearticle'])){
				$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scorearticle'], true);
				}
				*/

				//采集图片
				if(!empty($_REQUEST['articleimage']) && $imgflag>0){
					include_once(JIEQI_ROOT_PATH.'/configs/article/site_'.$jieqiCollectsite[$_REQUEST['siteid']]['config'].'.php');
					$colary=array('repeat'=>2, 'referer'=>$jieqiCollect['referer'], 'proxy_host'=>$jieqiCollect['proxy_host'], 'proxy_port'=>$jieqiCollect['proxy_port'], 'proxy_user'=>$jieqiCollect['proxy_user'], 'proxy_pass'=>$jieqiCollect['proxy_pass']);
					if(!empty($colary['referer']) && !empty($_REQUEST['collecturl'])) $colary['referer']=$_REQUEST['collecturl'];
					$tmpstr=jieqi_urlcontents($_REQUEST['articleimage'],$colary);
					if(!empty($tmpstr)){
						$imagefile=$package->getDir('imagedir').'/'.$id.'s'.$imagetype;
						@jieqi_writefile($imagefile, $tmpstr);
						@chmod($imagefile, 0777);
					}
				}
				include_once(JIEQI_ROOT_PATH.'/admin/header.php');
				$jieqiTpl->assign('article_static_url',$article_static_url);
				$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
				$jieqiTpl->assign('jieqi_contents', '<br />'.jieqi_msgbox(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['collect_newarticle_success'], $article_static_url.'/admin/updatecollect.php?siteid='.urlencode($_REQUEST['siteid']).'&fromid='.urlencode($_REQUEST['fromid']).'&toid='.urlencode($id))).'<br />');
				include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
				exit;
			}
		}else{
			jieqi_printfail($errtext);
		}
		break;
	case 'collect':
		$errtext='';
		if(empty($_REQUEST['siteid'])) $errtext .= $jieqiLang['article']['need_source_site'].'<br />';
		if(empty($_REQUEST['fromid'])) $errtext .= $jieqiLang['article']['need_source_articleid'].'<br />';
		if(empty($errtext)) {
			if(!empty($_REQUEST['toid'])){
				header('Location: '.$article_static_url.'/admin/updatecollect.php?siteid='.urlencode($_REQUEST['siteid']).'&fromid='.urlencode($_REQUEST['fromid']).'&toid='.urlencode($_REQUEST['toid']));
				exit;
			}
			if(array_key_exists($_REQUEST['siteid'], $jieqiCollectsite) && $jieqiCollectsite[$_REQUEST['siteid']]['enable']=='1'){
				include_once($jieqiModules['article']['path'].'/include/collectfunction.php');
				include_once(JIEQI_ROOT_PATH.'/configs/article/site_'.$jieqiCollectsite[$_REQUEST['siteid']]['config'].'.php');
				if(empty($jieqiCollect['articletitle'])) jieqi_printfail($jieqiLang['article']['collect_rule_notfull']);
				$url=str_replace('<{articleid}>', $_REQUEST['fromid'], $jieqiCollect['urlarticle']);
				if(!empty($jieqiCollect['subarticleid'])){
					$subarticleid=0;
					$articleid=$_REQUEST['fromid'];
					$tmpstr='$subarticleid = '.$jieqiCollect['subarticleid'].';';
					eval($tmpstr);
					$url=str_replace('<{subarticleid}>',$subarticleid, $url);
				}
				$colary=array('repeat'=>2, 'referer'=>$jieqiCollect['referer'], 'proxy_host'=>$jieqiCollect['proxy_host'], 'proxy_port'=>$jieqiCollect['proxy_port'], 'proxy_user'=>$jieqiCollect['proxy_user'], 'proxy_pass'=>$jieqiCollect['proxy_pass']);
				if(!empty($jieqiCollect['pagecharset'])) $colary['charset']=$jieqiCollect['pagecharset'];
				$source=jieqi_urlcontents($url,$colary);
				if(empty($source)) jieqi_printfail(sprintf($jieqiLang['article']['collect_url_failure'], $url, jieqi_htmlstr($url)));
				//标题
				$pregstr=jieqi_collectstoe($jieqiCollect['articletitle']);
				$matchvar=jieqi_cmatchone($pregstr, $source);
				if(empty($matchvar)) jieqi_printfail(sprintf($jieqiLang['article']['parse_articletitle_failure'], jieqi_htmlstr($url), jieqi_htmlstr($source)));
				$articletitle=jieqi_sbcstr(trim(jieqi_textstr($matchvar)));
				//作者
				$author='';
				$pregstr=jieqi_collectstoe($jieqiCollect['author']);
				if(!empty($pregstr)){
					$matchvar=jieqi_cmatchone($pregstr, $source);
					if(!empty($matchvar)) $author=trim(jieqi_textstr($matchvar));
				}
				//类型
				$sort='';
				$pregstr=jieqi_collectstoe($jieqiCollect['sort']);
				if(!empty($pregstr)){
					$matchvar=jieqi_cmatchone($pregstr, $source);
					if(!empty($matchvar)) $sort=trim(jieqi_textstr($matchvar));
				}
				//关键字
				$keyword='';
				$pregstr=jieqi_collectstoe($jieqiCollect['keyword']);
				if(!empty($pregstr)){
					$matchvar=jieqi_cmatchone($pregstr, $source);
					if(!empty($matchvar)) $keyword=trim(jieqi_textstr($matchvar));
				}
				//简介
				$intro='';
				$pregstr=jieqi_collectstoe($jieqiCollect['intro']);
				if(!empty($pregstr)){
					$matchvar=jieqi_cmatchone($pregstr, $source);
					if(!empty($matchvar)) $intro='    '.trim(jieqi_textstr($matchvar));
				}
				//封面
				$articleimage='';
				$pregstr=jieqi_collectstoe($jieqiCollect['articleimage']);
				if(substr($pregstr,0,4)=='http'){
					$articleimage=str_replace('<{articleid}>', $_REQUEST['fromid'], $pregstr);
					$pregstr='';
				}
				if(!empty($pregstr)){
					$matchvar=jieqi_cmatchone($pregstr, $source);
					if(!empty($matchvar)) $articleimage=trim(jieqi_textstr($matchvar));
				}
				//是不是需要过滤封面
				if(!empty($articleimage) && !empty($jieqiCollect['filterimage'])){
					if(strpos($articleimage,$jieqiCollect['filterimage'])!==false) $articleimage='';
				}
				if(!empty($articleimage) && !in_array(strrchr(strtolower($articleimage), '.'),array('.gif', '.jpg', '.jpeg', '.bmp', '.png'))) $articleimage='';
				//图片如果相对地址，改成绝对的
				if(!empty($articleimage) && strpos($articleimage,'http') !== 0){
					if(substr($articleimage,0,1)=='/'){
						$matches=array();
						preg_match('/https?:\/\/[^\/]+/is',$url,$matches);
						if(!empty($matches[0])) $articleimage=$matches[0].$articleimage;
						else $articleimage=$jieqiCollect['siteurl'].$articleimage;
					}else{
						$tmpdir=dirname($url);
						while(strpos($articleimage, '../')===0){
							$tmpdir=dirname($tmpdir);
							$articleimage=substr($articleimage, 3);
						}
						$articleimage=$tmpdir.'/'.$articleimage;
					}
				}
				//全文标记
				$pregstr=jieqi_collectstoe($jieqiCollect['fullarticle']);
				if(!empty($pregstr)){
					$matchvar=jieqi_cmatchone($pregstr, $source);
					if(!empty($matchvar)) $fullarticle=1;
					else $fullarticle=0;
				}else{
					if(!empty($jieqiCollect['defaultfull'])) $fullarticle=1;
					else $fullarticle=0;
				}
				//检查文章是否已存在
				include_once($jieqiModules['article']['path'].'/class/article.php');
				$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
				$criteria=new CriteriaCompo(new Criteria('articlename', $articletitle, '='));
				$res=$article_handler->queryObjects($criteria);
				$articleobj=$article_handler->getObject($res);
				if(is_object($articleobj)){
					jieqi_msgwin($jieqiLang['article']['article_already_exists'], sprintf($jieqiLang['article']['collect_exists_note'], jieqi_htmlstr($articletitle), $article_static_url.'/admin/updatecollect.php?siteid='.urlencode($_REQUEST['siteid']).'&fromid='.urlencode($_REQUEST['fromid']).'&toid='.urlencode($articleobj->getVar('articleid', 'n')), jieqi_geturl('article', 'article', $articleobj->getVar('articleid', 'n'), 'info')));
					exit;
				}
				//增加文章
				include_once(JIEQI_ROOT_PATH.'/admin/header.php');
				$jieqiTpl->assign('article_static_url',$article_static_url);
				$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
				jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
				include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
				$article_form = new JieqiThemeForm($jieqiLang['article']['collect_add_new'], 'newarticle', $article_static_url.'/admin/'.$self_filename);
				if(!empty($sort) && isset($jieqiCollect['sortid'][$sort])) $sortid=$jieqiCollect['sortid'][$sort];
				elseif(isset($jieqiCollect['sortid']['default'])) $sortid=$jieqiCollect['sortid']['default'];
				else $sortid=0;
				$sort_select = new JieqiFormSelect($jieqiLang['article']['table_article_sortid'], 'sortid', $sortid);
				foreach($jieqiSort['article'] as $key => $val){
					$tmpstr = '';
					if ($val['layer']>0){
						for($i=0; $i<$val['layer']; $i++) $tmpstr .= '&nbsp;&nbsp;';
						$tmpstr .= '├';
					}
					$tmpstr .= $val['caption'];
					$sort_select->addOption($key, $tmpstr);
				}
				$sort_select->setDescription($jieqiLang['article']['collect_sort_guide'].jieqi_htmlstr($sort));
				$article_form->addElement($sort_select, true);
				$article_form->addElement(new JieqiFormText($jieqiLang['article']['table_article_articlename'], 'articlename', 30, 50, $articletitle), true);
				$keywords=new JieqiFormText($jieqiLang['article']['table_article_keywords'], 'keywords', 30, 50, $keyword);
				$keywords->setDescription($jieqiLang['article']['note_keywords']);
				$article_form->addElement($keywords);
				if(jieqi_checkpower($jieqiPower['article']['transarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
					$authorname=new JieqiFormText($jieqiLang['article']['table_article_author'], 'author', 30, 30, $author);
					$article_form->addElement($authorname);
					$tmpvar='0';
					$authorflag=new JieqiFormRadio($jieqiLang['article']['article_author_flag'], 'authorflag', $tmpvar);
					$authorflag->addOption('1', $jieqiLang['article']['auth_to_author']);
					$authorflag->addOption('0', $jieqiLang['article']['not_auth_author']);
					$article_form->addElement($authorflag);
				}
				$tmpvar='';
				if(!empty($_SESSION['jieqiUserId'])) $tmpvar=jieqi_htmlstr($_SESSION['jieqiUserName'],ENT_QUOTES);;
				$agent=new JieqiFormText($jieqiLang['article']['table_article_agent'], 'agent', 30, 30, $tmpvar);
				$agent->setDescription($jieqiLang['article']['note_agent']);
				$article_form->addElement($agent);

				$permission=new JieqiFormRadio($jieqiLang['article']['table_article_permission'], 'permission', '1');
				$permission->addOption('3', $jieqiLang['article']['article_permission_special']);
				$permission->addOption('2', $jieqiLang['article']['article_permission_insite']);
				$permission->addOption('1', $jieqiLang['article']['article_permission_yes']);
				$permission->addOption('0', $jieqiLang['article']['article_permission_no']);
				$article_form->addElement($permission);
				$firstflag=new JieqiFormRadio($jieqiLang['article']['table_article_firstflag'], 'firstflag', '0');
				$firstflag->addOption('1', $jieqiLang['article']['article_site_publish']);
				$firstflag->addOption('0', $jieqiLang['article']['article_other_publish']);
				$article_form->addElement($firstflag);
				$fullflag=new JieqiFormRadio($jieqiLang['article']['table_article_fullflag'], 'fullflag', $fullarticle);
				$fullflag->addOption('0', $jieqiLang['article']['article_not_full']);
				$fullflag->addOption('1', $jieqiLang['article']['article_is_full']);
				$article_form->addElement($fullflag);
				$article_form->addElement(new JieqiFormText($jieqiLang['article']['article_image_url'], 'articleimage', 60, 250, $articleimage));
				$article_form->addElement(new JieqiFormTextArea($jieqiLang['article']['table_article_intro'], 'intro', $intro, 6, 60));
				$article_form->addElement(new JieqiFormHidden('action', 'newarticle'));
				$article_form->addElement(new JieqiFormHidden('siteid', $_REQUEST['siteid']));
				$article_form->addElement(new JieqiFormHidden('fromid', $_REQUEST['fromid']));
				$article_form->addElement(new JieqiFormHidden('collecturl', $url));
				$article_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['collect_next_button'], 'submit'));

				$jieqiTpl->assign('jieqi_contents', '<br />'.$article_form->render(JIEQI_FORM_MIDDLE).'<br />');
				include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
			}else{
				jieqi_printfail($jieqiLang['article']['not_support_collectsite']);
			}
		}else{
			jieqi_printfail($errtext);
		}
		break;
	case 'show':
	default:
		include_once(JIEQI_ROOT_PATH.'/admin/header.php');
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$collect_form = new JieqiThemeForm($jieqiLang['article']['article_collect_title'], 'frmcollect', $article_static_url.'/admin/'.$self_filename);

		if(empty($_REQUEST['siteid'])) $_REQUEST['siteid']=1;
		$siteobj=new JieqiFormSelect($jieqiLang['article']['collect_source_site'], 'siteid', $_REQUEST['siteid']);
		foreach($jieqiCollectsite as $k=>$v){
			$siteobj->addOption($k, $v['name']);
		}
		$collect_form->addElement($siteobj);

		if(empty($_REQUEST['fromid'])) $_REQUEST['fromid']='';
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['source_article_id'], 'fromid', 30, 100, $_REQUEST['fromid']), true);
		if(empty($_REQUEST['toid'])) $_REQUEST['toid']='';
		$toidobj=new JieqiFormText($jieqiLang['article']['target_article_id'], 'toid', 30, 11, $_REQUEST['toid']);
		$toidobj->setDescription($jieqiLang['article']['target_article_note']);
		$collect_form->addElement($toidobj);

		$collect_form->addElement(new JieqiFormHidden('action', 'collect'));
		$collect_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['collect_next_button'], 'submit'));

		$jieqiTpl->assign('jieqi_contents', '<br />'.$collect_form->render(JIEQI_FORM_MIDDLE).'<br />');
		include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
		break;
}

?>