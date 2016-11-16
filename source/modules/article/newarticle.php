<?php 
/**
 * 新建文章
 *
 * 提交一篇新的文章信息
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: newarticle.php 322 2009-01-13 11:28:29Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//发表文章权限
jieqi_checkpower($jieqiPower['article']['newarticle'], $jieqiUsersStatus, $jieqiUsersGroup, false);
jieqi_loadlang('article', JIEQI_MODULE_NAME);
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'article';
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

switch($_REQUEST['action']){
	case 'newarticle':
		$_POST['articlename'] = trim($_POST['articlename']);
		$_POST['author'] = trim($_POST['author']);
		$_POST['agent'] = trim($_POST['agent']);
		//$_POST['author'] = strtolower(trim($_POST['author']));
		//$_POST['agent'] = strtolower(trim($_POST['agent']));
		$errtext='';
		include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
		//检查标题
		if (strlen($_POST['articlename'])==0) $errtext .= $jieqiLang['article']['need_article_title'].'<br />';
		elseif (!jieqi_safestring($_POST['articlename'])) $errtext .= $jieqiLang['article']['limit_article_title'].'<br />';
		//检查标题和简介有没有违禁单词
		if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
		if(!empty($jieqiConfigs['system']['postdenywords'])){
			include_once(JIEQI_ROOT_PATH.'/include/checker.php');
			$checker = new JieqiChecker();
			$matchwords1 = $checker->deny_words($_POST['articlename'], $jieqiConfigs['system']['postdenywords'], true);
			$matchwords2 = $checker->deny_words($_POST['intro'], $jieqiConfigs['system']['postdenywords'], true);
			if(is_array($matchwords1) || is_array($matchwords2)){
				if(!isset($jieqiLang['system']['post'])) jieqi_loadlang('post', 'system');
				$matchwords=array();
				if(is_array($matchwords1)) $matchwords = array_merge($matchwords, $matchwords1);
				if(is_array($matchwords2)) $matchwords = array_merge($matchwords, $matchwords2);
				$errtext .= sprintf($jieqiLang['system']['post_words_deny'], implode(' ', jieqi_funtoarray('htmlspecialchars',$matchwords)));
			}
		}
		
		//检查封面
		$typeary=explode(' ',trim($jieqiConfigs['article']['imagetype']));
		foreach($typeary as $k=>$v){
			if(substr($v,0,1) != '.') $typeary[$k]='.'.$typeary[$k];
		}
		if (!empty($_FILES['articlespic']['name'])){
			$simage_postfix = strrchr(trim(strtolower($_FILES['articlespic']['name'])),".");
			if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$_FILES['articlespic']['name'])){
				if(!in_array($simage_postfix, $typeary)) $errtext .= sprintf($jieqiLang['article']['simage_type_error'], $jieqiConfigs['article']['imagetype']).'<br />';
			}else{
				$errtext .= sprintf($jieqiLang['article']['simage_not_image'], $_FILES['articlespic']['name']).'<br />';
			}
			if(!empty($errtext)) jieqi_delfile($_FILES['articlespic']['tmp_name']);
		}
		if (!empty($_FILES['articlelpic']['name'])){
			$limage_postfix = strrchr(trim(strtolower($_FILES['articlelpic']['name'])),".");
			if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$_FILES['articlelpic']['name'])){
				if(!in_array($limage_postfix, $typeary)) $errtext .= sprintf($jieqiLang['article']['limage_type_error'], $jieqiConfigs['article']['imagetype']).'<br />';
			}else{
				$errtext .= sprintf($jieqiLang['article']['limage_not_image'], $_FILES['articlelpic']['name']).'<br />';
			}
			if(!empty($errtext)) jieqi_delfile($_FILES['articlelpic']['tmp_name']);
		}
		if(empty($errtext)) {
			include_once($jieqiModules['article']['path'].'/class/article.php');
			$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
			//检查文章是否已经发表
			if($jieqiConfigs['article']['samearticlename'] != 1){
				if($article_handler ->getCount(new Criteria('articlename', $_POST['articlename'], '=')) > 0) jieqi_printfail(sprintf($jieqiLang['article']['articletitle_has_exists'], jieqi_htmlstr($_POST['articlename'])));
			}
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$newArticle = $article_handler->create();
			$newArticle->setVar('siteid', JIEQI_SITE_ID);
			$newArticle->setVar('postdate', JIEQI_NOW_TIME);
			$newArticle->setVar('lastupdate', JIEQI_NOW_TIME);
			$newArticle->setVar('articlename', $_POST['articlename']);
			$newArticle->setVar('keywords', trim($_POST['keywords']));
			$newArticle->setVar('initial', jieqi_getinitial($_POST['articlename']));
			$agentobj=false;
			if(!empty($_POST['agent'])) $agentobj=$users_handler->getByname($_POST['agent'],3);
			if(is_object($agentobj)){
				$newArticle->setVar('agentid', $agentobj->getVar('uid'));
				$newArticle->setVar('agent', $agentobj->getVar('uname', 'n'));
			}else{
				$newArticle->setVar('agentid', 0);
				$newArticle->setVar('agent', '');
			}

			if(jieqi_checkpower($jieqiPower['article']['transarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
				//允许转载的情况
				if(empty($_POST['author']) || (!empty($_SESSION['jieqiUserId']) && $_POST['author']==$_SESSION['jieqiUserName'])){
					if(!empty($_SESSION['jieqiUserId'])){
						$newArticle->setVar('authorid', $_SESSION['jieqiUserId']);
						$newArticle->setVar('author', $_SESSION['jieqiUserName']);
					}else{
						$newArticle->setVar('authorid', 0);
						$newArticle->setVar('author', '');
					}
				}else{
					//转载作品
					$newArticle->setVar('author', $_POST['author']);
					if($_POST['authorflag']){
						$authorobj=$users_handler->getByname($_POST['author'],3);
						if(is_object($authorobj)) $newArticle->setVar('authorid', $authorobj->getVar('uid'));
						else $newArticle->setVar('authorid', 0);
					}else{
						$newArticle->setVar('authorid', 0);
					}
				}
				$newArticle->setVar('permission', $_POST['permission']);
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
			$newArticle->setVar('fullflag', 0);
			$newArticle->setVar('sortid', intval($_POST['sortid']));
			$newArticle->setVar('typeid', intval($_POST['typeid']));
			$newArticle->setVar('intro', $_POST['intro']);
			$newArticle->setVar('notice', $_POST['notice']);
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
			$newArticle->setVar('firstflag', $_POST['firstflag']);
			$imgflag=0;
			$imgtary=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
			if (!empty($_FILES['articlespic']['name'])){
				$imgflag = $imgflag | 1;
				$tmpvar = intval(array_search($simage_postfix, $imgtary));
				if($tmpvar > 0) $imgflag = $imgflag | ($tmpvar * 4);
			}
			if (!empty($_FILES['articlelpic']['name'])){
				$imgflag =$imgflag | 2;
				$tmpvar = intval(array_search($limage_postfix, $imgtary));
				if($tmpvar > 0) $imgflag = $imgflag | ($tmpvar * 32);
			}
			$newArticle->setVar('imgflag', $imgflag);
			if(jieqi_checkpower($jieqiPower['article']['needcheck'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
				$newArticle->setVar('display', 0);
			}else{
				$newArticle->setVar('display', 1);  //待审文章
			}
			if (!$article_handler->insert($newArticle)) jieqi_printfail($jieqiLang['article']['article_add_failure']);
			else {
				$id=$newArticle->getVar('articleid');
				include_once($jieqiModules['article']['path'].'/class/package.php');
				$package=new JieqiPackage($id);

				$package->initPackage(array('id'=>$newArticle->getVar('articleid','n'), 'title'=>$newArticle->getVar('articlename', 'n'), 'creatorid'=>$newArticle->getVar('authorid','n'), 'creator'=>$newArticle->getVar('author','n'), 'subject'=>$newArticle->getVar('keywords','n'), 'description'=>$newArticle->getVar('intro', 'n'), 'publisher'=>JIEQI_SITE_NAME, 'contributorid'=>$newArticle->getVar('posterid', 'n'), 'contributor'=>$newArticle->getVar('poster', 'n'), 'sortid'=>$newArticle->getVar('sortid', 'n'), 'typeid'=>$newArticle->getVar('typeid', 'n'), 'articletype'=>$newArticle->getVar('articletype', 'n'), 'permission'=>$newArticle->getVar('permission', 'n'), 'firstflag'=>$newArticle->getVar('firstflag', 'n'), 'fullflag'=>$newArticle->getVar('fullflag', 'n'), 'imgflag'=>$newArticle->getVar('imgflag', 'n'), 'power'=>$newArticle->getVar('power', 'n'), 'display'=>$newArticle->getVar('display', 'n')));
				//保存小图
				if (!empty($_FILES['articlespic']['name'])){
					jieqi_copyfile($_FILES['articlespic']['tmp_name'], $package->getDir('imagedir').'/'.$id.'s'.$simage_postfix, 0777, true);
				}
				//保存大图
				if (!empty($_FILES['articlelpic']['name'])){
					jieqi_copyfile($_FILES['articlelpic']['tmp_name'], $package->getDir('imagedir').'/'.$id.'l'.$limage_postfix, 0777, true);
				}
				//增加发文积分
				if(!empty($jieqiConfigs['article']['scorearticle'])){
					$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scorearticle'], true);
				}
				//更新最新入库
				if($newArticle->getVar('display')==0){
					jieqi_getcachevars('article', 'articleuplog');
					if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
					$jieqiArticleuplog['articleuptime']=JIEQI_NOW_TIME;
					jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');
				}
				jieqi_jumppage($article_static_url.'/articlemanage.php?id='.$id, LANG_DO_SUCCESS, $jieqiLang['article']['article_add_success']);
			}
		}else{
			jieqi_printfail($errtext);
		}
		break;
	case 'article':
	default:
		//包含区块参数(定制区块)
		jieqi_getconfigs('article', 'authorblocks', 'jieqiBlocks');
		include_once(JIEQI_ROOT_PATH.'/header.php');
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
		$jieqiTpl->assign('url_newarticle',$article_static_url.'/newarticle.php?do=submit');
		jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort', 'jieqiSort');
		$jieqiTpl->assign_by_ref('sortrows', $jieqiSort['article']);
		jieqi_getconfigs(JIEQI_MODULE_NAME, 'option', 'jieqiOption');
		foreach($jieqiOption['article'] as $k=>$v){
			$jieqiTpl->assign_by_ref($k, $jieqiOption['article'][$k]);
		}
		if(jieqi_checkpower($jieqiPower['article']['transarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true)) $jieqiTpl->assign('allowtrans', 1);
		else $jieqiTpl->assign('allowtrans', 0);
		$jieqiTpl->assign('authorarea', 1);
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/newarticle.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}

?>