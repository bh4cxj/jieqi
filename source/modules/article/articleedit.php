<?php 
/**
 * 文章编辑
 *
 * 编辑一篇文章信息
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: articleedit.php 286 2008-12-23 03:04:17Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('article', JIEQI_MODULE_NAME);
include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($_REQUEST['id']);
if(!$article) jieqi_printfail($jieqiLang['article']['article_not_exists']);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//管理别人文章权限
$canedit=jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改文章
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($article->getVar('authorid')==$tmpvar || $article->getVar('posterid')==$tmpvar || $article->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}
if(!$canedit) jieqi_printfail($jieqiLang['article']['noper_edit_article']);
$allowmodify=jieqi_checkpower($jieqiPower['article']['articlemodify'], $jieqiUsersStatus, $jieqiUsersGroup, true);

if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'edit';
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(!is_numeric($jieqiConfigs['article']['eachlinknum'])) $jieqiConfigs['article']['eachlinknum']=0;
else $jieqiConfigs['article']['eachlinknum']=intval($jieqiConfigs['article']['eachlinknum']);
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
switch ($_REQUEST['action']) {
	case 'update':
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
		//检查封面图片
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
			//检查文章是否已经发表
			if($article->getVar('articlename', 'n') != $_POST['articlename'] && $jieqiConfigs['article']['samearticlename'] != 1){
				if($article_handler ->getCount(new Criteria('articlename', $_POST['articlename'], '=')) > 0) jieqi_printfail(sprintf($jieqiLang['article']['articletitle_has_exists'], jieqi_htmlstr($_POST['articlename'])));
			}
			$article->setVar('articlename', $_POST['articlename']);
			$article->setVar('keywords', trim($_POST['keywords']));
			$article->setVar('initial', jieqi_getinitial($_POST['articlename']));
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$agentobj=false;
			if(!empty($_POST['agent'])) $agentobj=$users_handler->getByname($_POST['agent'],3);
			if(is_object($agentobj)){
				$article->setVar('agentid', $agentobj->getVar('uid'));
				$article->setVar('agent', $agentobj->getVar('uname', 'n'));
			}else{
				$article->setVar('agentid', 0);
				$article->setVar('agent', '');
			}

			if(jieqi_checkpower($jieqiPower['article']['transarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
				//允许转载的情况
				if(empty($_POST['author'])){
					if(!empty($_SESSION['jieqiUserId'])){
						$article->setVar('authorid', $_SESSION['jieqiUserId']);
						$article->setVar('author', $_SESSION['jieqiUserName']);
					}else{
						$article->setVar('authorid', 0);
						$article->setVar('author', '');
					}
				}else{
					//转载作品
					$article->setVar('author', $_POST['author']);
					if($_POST['authorflag']){
						include_once(JIEQI_ROOT_PATH.'/class/users.php');
						$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
						$authorobj=$users_handler->getByname($_POST['author'],3);
						if(is_object($authorobj)) $article->setVar('authorid', $authorobj->getVar('uid'));
					}else{
						$article->setVar('authorid', 0);
					}
				}
				$article->setVar('permission', $_POST['permission']);
			}else{
				//不允许转载的情况
				if(!empty($_SESSION['jieqiUserId'])){
					$article->setVar('authorid', $_SESSION['jieqiUserId']);
					$article->setVar('author', $_SESSION['jieqiUserName']);
				}else{
					$article->setVar('authorid', 0);
					$article->setVar('author', '');
				}
			}

			if(!empty($_SESSION['jieqiUserId'])){
				$article->setVar('posterid', $_SESSION['jieqiUserId']);
				$article->setVar('poster', $_SESSION['jieqiUserName']);
			}else{
				$article->setVar('posterid', 0);
				$article->setVar('poster', '');
			}

			$article->setVar('fullflag', $_POST['fullflag']);
			$article->setVar('firstflag', $_POST['firstflag']);
			$article->setVar('sortid', intval($_POST['sortid']));
			$article->setVar('typeid', intval($_POST['typeid']));
			$article->setVar('intro', $_POST['intro']);
			$article->setVar('notice', $_POST['notice']);
			//封面图片标志
			$old_imgflag=$article->getVar('imgflag');
			$imgflag=$old_imgflag;

			$imgtary=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
			if (!empty($_FILES['articlespic']['name'])){
				$imgflag = $imgflag | 1;
				$tmpvar = intval(array_search($simage_postfix, $imgtary));
				if($tmpvar > 0) $imgflag = $imgflag & 227 | ($tmpvar * 4);
			}
			if (!empty($_FILES['articlelpic']['name'])){
				$imgflag =$imgflag | 2;
				$tmpvar = intval(array_search($limage_postfix, $imgtary));
				if($tmpvar > 0) $imgflag = $imgflag & 31 | ($tmpvar * 32);
			}
			$article->setVar('imgflag', $imgflag);

			//互换链接
			if($jieqiConfigs['article']['eachlinknum']>0){
				$_POST['eachlinkids']=trim($_POST['eachlinkids']);
				$setting=unserialize($article->getVar('setting', 'n'));
				if(!empty($setting['eachlink']['ids'])) $linkvalue=implode(' ', $setting['eachlink']['ids']);
				else $linkvalue='';
				if($linkvalue != $_POST['eachlinkids']){
					$tmpary=array_unique(explode(' ',$_POST['eachlinkids']));
					foreach($tmpary as $k=>$v){
						if(!is_numeric($v)) unset($tmpary[$k]);
						else $tmpary[$k]=intval($tmpary[$k]);
					}
					$linkids=array();
					$linknames=array();
					if(count($tmpary>0)){
						$sql="SELECT articleid, articlename FROM ".jieqi_dbprefix('article_article')." WHERE articleid IN (".implode(',',$tmpary).")";
						$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
						$query->execute($sql);
						$linknum=0;
						while(($arow = $query->getRow()) && ($linknum < $jieqiConfigs['article']['eachlinknum'])){
							if($arow['articleid'] != $article->getVar('articleid', 'n')){
								$linkids[$linknum]=$arow['articleid'];
								$linknames[$linknum]=$arow['articlename'];
								$linknum++;
							}
						}
					}
					$setting['eachlink']['ids']=$linkids;
					$setting['eachlink']['names']=$linknames;
					$article->setVar('setting', serialize($setting));
				}
			}

			//允许修改统计的情况
			if($allowmodify){
				$statary = array('dayvisit', 'weekvisit', 'monthvisit', 'allvisit', 'dayvote', 'weekvote', 'monthvote', 'allvote');
				foreach($statary as $v){
					if(isset($_POST[$v])) $article->setVar($v, intval($_POST[$v]));
				}
			}

			if (!$article_handler->insert($article)) jieqi_printfail($jieqiLang['article']['article_edit_failure']);
			else {
				$id=$article->getVar('articleid');
				include_once($jieqiModules['article']['path'].'/class/package.php');
				$package=new JieqiPackage($id);
				$package->editPackage(array('id'=>$article->getVar('articleid','n'), 'title'=>$article->getVar('articlename', 'n'), 'creatorid'=>$article->getVar('authorid','n'), 'creator'=>$article->getVar('author','n'), 'subject'=>$article->getVar('keywords','n'), 'description'=>$article->getVar('intro', 'n'), 'publisher'=>JIEQI_SITE_NAME, 'contributorid'=>$article->getVar('posterid', 'n'), 'contributor'=>$article->getVar('poster', 'n'), 'sortid'=>$article->getVar('sortid', 'n'), 'typeid'=>$article->getVar('typeid', 'n'), 'articletype'=>$article->getVar('articletype', 'n'), 'permission'=>$article->getVar('permission', 'n'), 'firstflag'=>$article->getVar('firstflag', 'n'), 'fullflag'=>$article->getVar('fullflag', 'n'), 'imgflag'=>$article->getVar('imgflag', 'n'), 'power'=>$article->getVar('power', 'n'), 'display'=>$article->getVar('display', 'n')));

				//删除老封面
				if($old_imgflag != $imgflag){
					$tmpvar = ($old_imgflag >> 2) & 7;
					if(isset($imgtary[$tmpvar])){
						if(is_file($package->getDir('imagedir').'/'.$id.'s'.$imgtary[$tmpvar])) jieqi_delfile($package->getDir('imagedir').'/'.$id.'s'.$imgtary[$tmpvar]);
					}
					$tmpvar = $old_imgflag >> 5;
					if(isset($imgtary[$tmpvar])){
						if(is_file($package->getDir('imagedir').'/'.$id.'l'.$imgtary[$tmpvar])) jieqi_delfile($package->getDir('imagedir').'/'.$id.'l'.$imgtary[$tmpvar]);
					}
				}

				//保存小图
				if (!empty($_FILES['articlespic']['name'])){
					jieqi_copyfile($_FILES['articlespic']['tmp_name'], $package->getDir('imagedir').'/'.$id.'s'.$simage_postfix, 0777, true);
				}

				//保存大图
				if (!empty($_FILES['articlelpic']['name'])){
					jieqi_copyfile($_FILES['articlelpic']['tmp_name'], $package->getDir('imagedir').'/'.$id.'l'.$limage_postfix, 0777, true);
				}
				jieqi_jumppage($article_static_url.'/articlemanage.php?id='.$id, LANG_DO_SUCCESS, $jieqiLang['article']['article_edit_success']);
			}
		}else{
			jieqi_printfail($errtext);
		}
		break;
	case 'edit':
	default:
		//包含区块参数(定制区块)
		jieqi_getconfigs('article', 'authorblocks', 'jieqiBlocks');
		include_once(JIEQI_ROOT_PATH.'/header.php');
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
		$jieqiTpl->assign('url_articleedit',$article_static_url.'/articleedit.php?do=submit');
		//分类配置
		jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort', 'jieqiSort');
		$jieqiTpl->assign_by_ref('sortrows', $jieqiSort['article']);
		//可选项配置
		jieqi_getconfigs(JIEQI_MODULE_NAME, 'option', 'jieqiOption');
		foreach($jieqiOption['article'] as $k=>$v){
			$jieqiTpl->assign_by_ref($k, $jieqiOption['article'][$k]);
		}
		//文章编辑信息
		$articlevals  = $article->getVars('e');
		//是否授权给作者
		if($article->getVar('authorid')>0) $articlevals['authorflag'] = 1;
		else $articlevals['authorflag'] = 0;
		//互换链接信息
		$jieqiTpl->assign('eachlinknum', $jieqiConfigs['article']['eachlinknum']);
		if($jieqiConfigs['article']['eachlinknum']>0){
			$setting=unserialize($article->getVar('setting', 'n'));
			if(!empty($setting['eachlink']['ids'])) $articlevals['eachlinkids']=implode(' ', $setting['eachlink']['ids']);
			else $articlevals['eachlinkids']='';
		}
		$jieqiTpl->assign_by_ref('articlevals', $articlevals);
		//是否允许转载
		if(jieqi_checkpower($jieqiPower['article']['transarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true)) $jieqiTpl->assign('allowtrans', 1);
		else $jieqiTpl->assign('allowtrans', 0);
		//封面图片格式
		$jieqiTpl->assign('imagetype', $jieqiConfigs['article']['imagetype']);
		//是否允许修改点击统计
		$jieqiTpl->assign('allowmodify', $allowmodify);
		//是否作家栏目
		$jieqiTpl->assign('authorarea', 1);
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/articleedit.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}


?>