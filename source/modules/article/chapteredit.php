<?php 
/**
 * 编辑章节
 *
 * 编辑和保存一个章节
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chapteredit.php 330 2009-02-09 16:07:35Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
jieqi_loadlang('article', JIEQI_MODULE_NAME);
if($_REQUEST['chaptertype']==1) $typename=$jieqiLang['article']['volume_name'];
else $typename=$jieqiLang['article']['chapter_name'];
if(empty($_REQUEST['id'])) jieqi_printfail(sprintf($jieqiLang['article']['chapter_volume_notexists'], $typename));
include_once($jieqiModules['article']['path'].'/class/chapter.php');
$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
$chapter=$chapter_handler->get($_REQUEST['id']);
if(!$chapter) jieqi_printfail(sprintf($jieqiLang['article']['chapter_volume_notexists'], $typename));
if($chapter->getVar('chaptertype')==1) {
	$_REQUEST['chaptertype']=1;
	$typename=$jieqiLang['article']['volume_name'];
}else{
	$typename=$jieqiLang['article']['chapter_name'];
	$_REQUEST['chaptertype']=0;
}

include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($chapter->getVar('articleid'));
if(!$article) jieqi_printfail($jieqiLang['article']['article_not_exists']);
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
$canedit=jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改文章
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($article->getVar('authorid')==$tmpvar || $chapter->getVar('posterid')==$tmpvar || $article->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}
if(!$canedit) jieqi_printfail(sprintf($jieqiLang['article']['noper_delete_chapter'], $typename));
//检查上传权限
$canupload = jieqi_checkpower($jieqiPower['article']['articleupattach'], $jieqiUsersStatus, $jieqiUsersGroup, true);

jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'edit';

switch($_REQUEST['action']){
	case 'update':
		$_POST['chaptername'] = trim($_POST['chaptername']);
		$errtext='';
		//检查标题
		if (strlen($_POST['chaptername'])==0) $errtext .= $typename.$jieqiLang['article']['need_chapter_title'].'<br />';

		//检查附件
		$attachary=array();
		$infoary=array();
		$attachnum=0;
		$attachinfo='';
		//检查上传文件
		if($canupload && is_numeric($jieqiConfigs['article']['maxattachnum']) && $jieqiConfigs['article']['maxattachnum']>0){
			$maxfilenum=intval($jieqiConfigs['article']['maxattachnum']);
			$typeary=explode(' ',trim($jieqiConfigs['article']['attachtype']));
			foreach($_FILES['attachfile']['name'] as $k=>$v){
				if(!empty($v)){
					$tmpary=explode('.', $v);
					$tmpint=count($tmpary)-1;
					$tmpary[$tmpint]=strtolower(trim($tmpary[$tmpint]));
					$denyary=array('htm', 'html', 'shtml', 'php', 'asp', 'aspx', 'jsp', 'pl', 'cgi');
					if(empty($tmpary[$tmpint]) || !in_array($tmpary[$tmpint], $typeary)){
						$errtext .= sprintf($jieqiLang['article']['upload_filetype_error'], $v).'<br />';
					}elseif(in_array($tmpary[$tmpint], $denyary)){
						$errtext .= sprintf($jieqiLang['article']['upload_filetype_limit'], $tmpary[$tmpint]).'<br />';
					}
					if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$v)){
						$fclass='image';
						if($_FILES['attachfile']['size'][$k] > (intval($jieqiConfigs['article']['maximagesize']) * 1024)) $errtext .= sprintf($jieqiLang['article']['upload_filesize_toolarge'], $v, intval($jieqiConfigs['article']['maximagesize'])).'<br />';
					}else{
						$fclass='file';
						if($_FILES['attachfile']['size'][$k] > (intval($jieqiConfigs['article']['maxfilesize']) * 1024)) $errtext .= sprintf($jieqiLang['article']['upload_filesize_toolarge'], $v, intval($jieqiConfigs['article']['maxfilesize'])).'<br />';
					}
					if(!empty($errtext)){
						jieqi_delfile($_FILES['attachfile']['tmp_name'][$k]);
					}else{
						$attachary[$attachnum]=$k;
						$infoary[$attachnum]=array('name'=>$v, 'class'=>$fclass, 'postfix'=>$tmpary[$tmpint], 'size'=>$_FILES['attachfile']['size'][$k]);
						$attachnum++;
					}
				}
			}
		}

		//有附件的话允许章节没内容，否则必须有
		if (count($_POST['oldattach']) == 0 && $attachnum == 0 && $_REQUEST['chaptertype'] == 0 && strlen($_POST['chaptercontent'])==0) $errtext .= $jieqiLang['article']['need_chapter_content'].'<br />';


		if(empty($errtext)) {
			$tmptime=JIEQI_NOW_TIME;
			//处理旧附件
			$tmpvar=$chapter->getVar('attachment','n');
			if(!empty($tmpvar)){
				$tmpattachary=unserialize($tmpvar);
				if(!is_array($tmpattachary)) $tmpattachary=array();
				if(!is_array($_POST['oldattach'])){
					if(is_string($_POST['oldattach'])) $_POST['oldattach']=array($_POST['oldattach']);
					else $_POST['oldattach']=array();
				}
				$oldattachary=array();
				foreach($tmpattachary as $val){
					if(in_array($val['attachid'], $_POST['oldattach'])){
						$oldattachary[]=$val;
					}else{
						//删除旧附件
						include_once($jieqiModules['article']['path'].'/class/articleattachs.php');
						$attachs_handler =& JieqiArticleattachsHandler::getInstance('JieqiArticleattachsHandler');
						$attachs_handler->delete($val['attachid']);
						$afname = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article').jieqi_getsubdir($chapter->getVar('articleid','n')).'/'.$chapter->getVar('articleid','n').'/'.$chapter->getVar('chapterid','n').'/'.$val['attachid'].'.'.$val['postfix'];
						jieqi_delfile($afname);
					}
				}
			}else{
				$oldattachary=array();
			}
			//新附件入库
			if($attachnum>0){
				include_once($jieqiModules['article']['path'].'/class/articleattachs.php');
				if(!is_object($attachs_handler)) $attachs_handler =& JieqiArticleattachsHandler::getInstance('JieqiArticleattachsHandler');

				$attachdir = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article');
				if (!file_exists($attachdir)) jieqi_createdir($attachdir);
				$attachdir .= jieqi_getsubdir($chapter->getVar('articleid'));
				if (!file_exists($attachdir)) jieqi_createdir($attachdir);
				$attachdir .= '/'.$chapter->getVar('articleid');
				if (!file_exists($attachdir)) jieqi_createdir($attachdir);
				$attachdir .= '/'.$chapter->getVar('chapterid');
				if (!file_exists($attachdir)) jieqi_createdir($attachdir);

				//判断是否加水印
				$make_image_water = false;
				if(function_exists('gd_info') && $jieqiConfigs['article']['attachwater'] > 0 && JIEQI_MODULE_VTYPE != '' && JIEQI_MODULE_VTYPE != 'Free'){
					if(strpos($jieqiConfigs['article']['attachwimage'], '/')===false && strpos($jieqiConfigs['article']['attachwimage'], '\\')===false) $water_image_file = $jieqiModules['article']['path'].'/images/'.$jieqiConfigs['article']['attachwimage'];
					else $water_image_file = $jieqiConfigs['article']['attachwimage'];
					if(is_file($water_image_file)){
						$make_image_water = true;
						include_once(JIEQI_ROOT_PATH.'/lib/image/imagewater.php');
					}
				}

				foreach($attachary as $k=>$v){
					$newAttach = $attachs_handler->create();
					$newAttach->setVar('articleid', $chapter->getVar('articleid','n'));
					$newAttach->setVar('chapterid', $chapter->getVar('chapterid','n'));
					$newAttach->setVar('name', $infoary[$k]['name']);
					$newAttach->setVar('class', $infoary[$k]['class']);
					$newAttach->setVar('postfix', $infoary[$k]['postfix']);
					$newAttach->setVar('size', $infoary[$k]['size']);
					$newAttach->setVar('hits', 0);
					$newAttach->setVar('needexp', 0);
					$newAttach->setVar('uptime', $chapter->getVar('postdate','n'));
					if($attachs_handler->insert($newAttach)){
						$attachid=$newAttach->getVar('attachid');
						$infoary[$k]['attachid']=$attachid;
					}else{
						$infoary[$k]['attachid']=0;
					}
					$attach_save_path = $attachdir.'/'.$infoary[$k]['attachid'].'.'.$infoary[$k]['postfix'];
					$tmp_attachfile = dirname($_FILES['attachfile']['tmp_name'][$v]).'/'.basename($attach_save_path);
					@move_uploaded_file($_FILES['attachfile']['tmp_name'][$v], $tmp_attachfile);
					//图片加水印
					if($make_image_water && eregi("\.(gif|jpg|jpeg|png)$",$tmp_attachfile)){
						$img = new ImageWater();
						$img->save_image_file = $tmp_attachfile;
						$img->codepage = JIEQI_SYSTEM_CHARSET;
						$img->wm_image_pos = $jieqiConfigs['article']['attachwater'];
						$img->wm_image_name = $water_image_file;
						$img->wm_image_transition  = $jieqiConfigs['article']['attachwtrans'];
						$img->jpeg_quality = $jieqiConfigs['article']['attachwquality'];
						$img->create($tmp_attachfile);
						unset($img);
					}
					jieqi_copyfile($tmp_attachfile, $attach_save_path, 0777, true);
				}
			}

			foreach($infoary as $val){
				$oldattachary[]=$val;
			}
			if(count($oldattachary)>0) $attachinfo=serialize($oldattachary);

			$chapter->setVar('attachment', $attachinfo);
			$chapter->setVar('articleid', $article->getVar('articleid'));
			if(!empty($_SESSION['jieqiUserId'])){
				$chapter->setVar('posterid', $_SESSION['jieqiUserId']);
				$chapter->setVar('poster', $_SESSION['jieqiUserName']);
			}else{
				$chapter->setVar('posterid', 0);
				$chapter->setVar('poster', '');
			}
			$chapter->setVar('lastupdate', JIEQI_NOW_TIME);
			$chapter->setVar('chaptername', $_POST['chaptername']);
			if($_REQUEST['chaptertype']==0){
				//文字过滤
				if(!empty($jieqiConfigs['article']['hidearticlewords'])){
					$articlewordssplit = (strlen($jieqiConfigs['article']['articlewordssplit'])==0) ? ' ' : $jieqiConfigs['article']['articlewordssplit'];
					$filterary=explode($articlewordssplit, $jieqiConfigs['article']['hidearticlewords']);
					$_POST['chaptercontent']=str_replace($filterary, '', $_POST['chaptercontent']);
				}

				//内容排版
				if($jieqiConfigs['article']['authtypeset']==2 || ($jieqiConfigs['article']['authtypeset']==1 && $_POST['typeset']==1)){
					include_once(JIEQI_ROOT_PATH.'/lib/text/texttypeset.php');
					$texttypeset=new TextTypeset();
					$_POST['chaptercontent']=$texttypeset->doTypeset($_POST['chaptercontent']);
				}
				$chaptersize=strlen($_POST['chaptercontent']);
				$beforesize=$chapter->getVar('size');
				$chapter->setVar('size', $chaptersize);
			}else{
				$_POST['chaptercontent']='';
			}
			if (!$chapter_handler->insert($chapter)) jieqi_printfail($jieqiLang['article']['chapter_edit_failure']);
			else {
				if($_REQUEST['chaptertype']==0) {
					$article->setVar('size', $article->getVar('size')+$chaptersize-$beforesize);
					if($chapter->getVar('chapterid')==$article->getVar('lastchapterid')) $article->setVar('lastchapter', $_POST['chaptername']);
				}else{
					if($chapter->getVar('chapterid')==$article->getVar('lastvolumeid')) $article->setVar('lastvolume', $_POST['chaptername']);
				}
				$article_handler->insert($article);
				@clearstatcache(); //清除文件状态缓存，免得附件删除后还认为存在
				include_once($jieqiModules['article']['path'].'/class/package.php');
				$package=new JieqiPackage($article->getVar('articleid'));
				$package->editChapter($_POST['chaptername'], $_POST['chaptercontent'], $_REQUEST['chaptertype'], $chapter->getVar('chapterorder'), $chapter->getVar('chapterid'));
				jieqi_jumppage($article_static_url.'/articlemanage.php?id='.$article->getVar('articleid'), LANG_DO_SUCCESS, $jieqiLang['article']['chapter_edit_success']);
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
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

		$chapter_form = new JieqiThemeForm(sprintf($jieqiLang['article']['chapter_edit'], $typename), 'chapteredit', $article_static_url.'/chapteredit.php');
		$chapter_form->setExtra('enctype="multipart/form-data"');
		$chapter_form->addElement(new JieqiFormLabel($jieqiLang['article']['table_article_articlename'], $article->getVar('articlename')));
		$chapter_form->addElement(new JieqiFormText($typename.$jieqiLang['article']['table_chapter_chaptername'], 'chaptername', 50, 50, $chapter->getVar('chaptername', 'e')), true);
		if($chapter->getVar('chaptertype')==1) $chaptertype='1';
		else{
			if($jieqiConfigs['article']['authtypeset']==1){
				$typeset=new JieqiFormRadio($jieqiLang['article']['chapter_typeset'], 'typeset', $jieqiConfigs['article']['autotypeset']);
				$typeset->addOption('1', $jieqiLang['article']['auto_typeset']);
				$typeset->addOption('0', $jieqiLang['article']['no_typeset']);
				$chapter_form->addElement($typeset);
			}
			$chaptertype='0';
			include_once($jieqiModules['article']['path'].'/class/package.php');
			$package=new JieqiPackage($article->getVar('articleid'));
			$chapter_form->addElement(new JieqiFormTextArea($jieqiLang['article']['table_chapter_chaptercontent'], 'chaptercontent', htmlspecialchars($package->getContent($chapter->getVar('chapterid')), ENT_QUOTES), 15, 60));
		}

		//上传附件
		$tmpvar=$chapter->getVar('attachment','n');
		$attachnum=0;
		if(!empty($tmpvar)){
			$attachary=unserialize($tmpvar);
			if(!is_array($attachary)) $attachary=array();
			$attachnum=count($attachary);
			if($attachnum>0){
				foreach($attachary as $val) $selectattach[]=$val['attachid'];
				$attachelement=new JieqiFormCheckBox($jieqiLang['article']['now_attachment'], 'oldattach', $selectattach);
				$attachelement->setIntro($jieqiLang['article']['note_edit_attachment']);
				foreach($attachary as $key=>$val)
				$attachelement->addOption($val['attachid'], jieqi_htmlstr($val['name']).'&nbsp;&nbsp;');
				$chapter_form->addElement($attachelement, false);
			}
		}

		if($canupload && is_numeric($jieqiConfigs['article']['maxattachnum']) && $jieqiConfigs['article']['maxattachnum']>0){
			$chapter_form->addElement(new JieqiFormLabel($jieqiLang['article']['attachment_limit'], $jieqiLang['article']['limit_attachment_type'].$jieqiConfigs['article']['attachtype'].', '.$jieqiLang['article']['limit_attachment_imagesize'].$jieqiConfigs['article']['maximagesize'].'K, '.$jieqiLang['article']['limit_attachment_filesize'].$jieqiConfigs['article']['maxfilesize'].'K'));
			$maxfilenum=intval($jieqiConfigs['article']['maxattachnum']);
			for($i=1; $i<=$maxfilenum; $i++){
				$chapter_form->addElement(new JieqiFormFile($jieqiLang['article']['attachment_name'].$i, 'attachfile[]', 60));
			}
		}

		$chapter_form->addElement(new JieqiFormHidden('action', 'update'));
		$chapter_form->addElement(new JieqiFormHidden('id', $_REQUEST['id']));

		$chapter_form->addElement(new JieqiFormHidden('chaptertype', $chaptertype));
		$chapter_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));

		$jieqiTpl->assign('authorarea', 1);
		$jieqiTpl->assign('jieqi_contents', '<br />'.$chapter_form->render(JIEQI_FORM_MIDDLE).'<br />');
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}


?>