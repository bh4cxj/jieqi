<?php 
/**
 * 重新生成文章阅读格式函数
 *
 * 重新生成文章阅读格式函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: repack.php 330 2009-02-09 16:07:35Z juny $
 */

include_once($GLOBALS['jieqiModules']['article']['path'].'/class/article.php');
include_once($GLOBALS['jieqiModules']['article']['path'].'/class/chapter.php');
include_once($GLOBALS['jieqiModules']['article']['path'].'/class/package.php');

//重新生成opf($syn 0 异步, 1 同步)
function article_repack($id, $params=array(), $syn=0)
{
	global $jieqiConfigs;
	global $jieqiModules;
	global $jieqi_file_postfix;
	if(!$syn){
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$url=$article_static_url.'/makepack.php?key='.urlencode(md5(JIEQI_DB_USER.JIEQI_DB_PASS.JIEQI_DB_NAME)).'&id='.intval($id);
		$url=trim($url);
		if(strtolower(substr($url,0,7)) != 'http://') $url='http://'.$_SERVER['HTTP_HOST'].$url;
		foreach($params as $k=>$v) if($v) $url.='&packflag[]='.urlencode($k);
		return jieqi_socket_url($url);
	}else{
		$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
		$article=$article_handler->get($id);
		if(!is_object($article)){
			return false;
		}else{
			$package=new JieqiPackage($id);
			$package->initPackage(array('id'=>$article->getVar('articleid','n'), 'title'=>$article->getVar('articlename', 'n'), 'creatorid'=>$article->getVar('authorid','n'), 'creator'=>$article->getVar('author','n'), 'subject'=>$article->getVar('keywords','n'), 'description'=>$article->getVar('intro', 'n'), 'publisher'=>JIEQI_SITE_NAME, 'contributorid'=>$article->getVar('posterid', 'n'), 'contributor'=>$article->getVar('poster', 'n'), 'sortid'=>$article->getVar('sortid', 'n'), 'typeid'=>$article->getVar('typeid', 'n'), 'articletype'=>$article->getVar('articletype', 'n'), 'permission'=>$article->getVar('permission', 'n'), 'firstflag'=>$article->getVar('firstflag', 'n'), 'fullflag'=>$article->getVar('fullflag', 'n'), 'imgflag'=>$article->getVar('imgflag', 'n'), 'power'=>$article->getVar('power', 'n'), 'display'=>$article->getVar('display', 'n')), false);

			$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
			$criteria=new CriteriaCompo(new Criteria('articleid', $id, '='));
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
			if($params['makeopf']) $package->createOPF();

			if($params['makehtml']){
				$chaptercount=count($package->chapters);
				for($i=$chaptercount-1; $i<=$chaptercount; $i++){
					if($package->chapters[$i-1]['content-type']=='chapter') $package->makeHtml($i,false,false,true);
				}
				//生成html目录
				$package->makeIndex();
			}

			//生成zip
			if($params['makezip']) $package->makezip();
			//生成全文阅读
			if($params['makefull']) $package->makefulltext();
			//生成umd
			if($params['makeumd']) $package->makeumd();
			//生成txt全文
			if($params['maketxtfull']) $package->maketxtfull();
			//生成jar
			if($params['makejar']) $package->makejar();
			return true;
		}
	}
}


?>