<?php 
/**
 * 文章重新生成
 *
 * 重新生成html、opf、zip、umd、jar
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: makepack.php 330 2009-02-09 16:07:35Z juny $
 */


//执行生成文章打包文件
define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');

//检查密钥
if(empty($_REQUEST['key'])) exit('no key');
elseif(defined('JIEQI_SITE_KEY') && $_REQUEST['key'] != JIEQI_SITE_KEY) exit('error key');
elseif($_REQUEST['key'] != md5(JIEQI_DB_USER.JIEQI_DB_PASS.JIEQI_DB_NAME)) exit();
//检查打包参数
if(!is_numeric($_REQUEST['id'])) exit;
if(!is_array($_REQUEST['packflag']) || count($_REQUEST['packflag'])<1) exit;
$_REQUEST['id']=intval($_REQUEST['id']);

@ignore_user_abort(true);
@set_time_limit(3600);
@session_write_close();
@ini_set('memory_limit', '64M');
echo '                                                                                                                                                                                                                                                                                                            ';
ob_flush();
flush();


jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
include_once($GLOBALS['jieqiModules']['article']['path'].'/class/article.php');
include_once($GLOBALS['jieqiModules']['article']['path'].'/class/chapter.php');
include_once($GLOBALS['jieqiModules']['article']['path'].'/class/package.php');

$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($_REQUEST['id']);
if(!is_object($article)){
	exit;
}else{
	$package=new JieqiPackage($_REQUEST['id']);
	$package->initPackage(array('id'=>$article->getVar('articleid','n'), 'title'=>$article->getVar('articlename', 'n'), 'creatorid'=>$article->getVar('authorid','n'), 'creator'=>$article->getVar('author','n'), 'subject'=>$article->getVar('keywords','n'), 'description'=>$article->getVar('intro', 'n'), 'publisher'=>JIEQI_SITE_NAME, 'contributorid'=>$article->getVar('posterid', 'n'), 'contributor'=>$article->getVar('poster', 'n'), 'sortid'=>$article->getVar('sortid', 'n'), 'typeid'=>$article->getVar('typeid', 'n'), 'articletype'=>$article->getVar('articletype', 'n'), 'permission'=>$article->getVar('permission', 'n'), 'firstflag'=>$article->getVar('firstflag', 'n'), 'fullflag'=>$article->getVar('fullflag', 'n'), 'imgflag'=>$article->getVar('imgflag', 'n'), 'power'=>$article->getVar('power', 'n'), 'display'=>$article->getVar('display', 'n')), false);

	$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
	$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['id'], '='));
	$criteria->setSort('chapterorder ASC, chapterid');
	$criteria->setOrder('ASC');
	$res=$chapter_handler->queryObjects($criteria);
	$i=0;
	$articlesize=0;
	while($chapter = $chapter_handler->getObject($res)){
		if($chapter->getVar('chaptertype', 'n')==1) $contenttype='volume';
		else $contenttype='chapter';
		$package->chapters[$i]=array('id'=>$chapter->getVar('chaptername','n'), 'href'=>$chapter->getVar('chapterid','n').$jieqi_file_postfix['txt'], 'media-type'=>'text/html', 'content-type'=>$contenttype);
		$i++;
		if($chapter->getVar('chaptertype', 'n') == 0) $articlesize = $articlesize + intval($chapter->getVar('size', 'n'));
		if($chapter->getVar('chapterorder', 'n') != $i){
			$chapter->setVar('chapterorder', $i);
			$chapter_handler->insert($chapter);
		}
	}
	//检查文章信息和统计的是否对应
	$changeflag=false;
	if($article->getVar('chapters','n') != $i){
		$article->setVar('chapters', $i);
		$changeflag=true;
	}
	if($article->getVar('size','n') != $articlesize){
		$article->setVar('size', $articlesize);
		$changeflag=true;
	}
	if($changeflag) $article_handler->insert($article);
	
	//开始生成
	$package->isload=true;
	//生成opf
	if(in_array('makeopf', $_REQUEST['packflag'])) $package->createOPF();
	
	if(in_array('makehtml', $_REQUEST['packflag'])){
		$chaptercount=count($package->chapters);
		for($i=1; $i<=$chaptercount; $i++){
			if($package->chapters[$i-1]['content-type']=='chapter') $package->makeHtml($i,false,false,true);
		}
		//生成html目录
		$package->makeIndex();
	}
	
	//生成txt全文
	if(in_array('maketxtfull', $_REQUEST['packflag'])) $package->maketxtfull();
	//生成全文阅读
	if(in_array('makefull', $_REQUEST['packflag'])) $package->makefulltext();
	//生成zip
	if(in_array('makezip', $_REQUEST['packflag'])) $package->makezip();
	//生成umd
	if(in_array('makeumd', $_REQUEST['packflag'])) $package->makeumd();
	//生成jar
	if(in_array('makejar', $_REQUEST['packflag'])) $package->makejar();
	
	return true;
}



?>