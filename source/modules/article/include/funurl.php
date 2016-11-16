<?php
/**
 * 允许模板使用的函数
 *
 * 允许模板使用的函数，函数名必须 jieqi_tpl_ 开头
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $aid: repack.php 230 2008-11-27 08:46:07Z juny $
 */

//需要载入参数设置
if(!isset($jieqiConfigs['article'])) jieqi_getconfigs('article', 'configs');
if(!isset($article_static_url)) $article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
if(!isset($article_dynamic_url)) $article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

/**
 * 根据文章ID获得信息页面url
 * 
 * @param      int        $aid 文章id
 * @param      string     $type 显示类型
 * @access     public
 * @return     string
 */
function jieqi_url_article_article($aid, $type=''){
	global $jieqiConfigs;
	global $article_dynamic_url;
	global $article_static_url;

	switch($type){
		case 'index':
			if($jieqiConfigs['article']['makehtml']==0 || JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET){
				return $article_static_url.'/reader.php?aid='.$aid;
			}else{
				return jieqi_uploadurl($jieqiConfigs['article']['htmldir'], $jieqiConfigs['article']['htmlurl'], 'article', $article_static_url).jieqi_getsubdir($aid).'/'.$aid.'/index'.$jieqiConfigs['article']['htmlfile'];
			}
			break;
		case 'info':
		default:
			if(!empty($jieqiConfigs['article']['fakeinfo'])){
				if(is_numeric($jieqiConfigs['article']['fakeinfo'])){
					if(!empty($jieqiConfigs['article']['fakeprefix'])) $jieqiConfigs['article']['fakeinfo']='/'.$jieqiConfigs['article']['fakeprefix'].'info<{$id|subdirectory}>/<{$id}>'.$jieqiConfigs['article']['fakefile'];
					else $jieqiConfigs['article']['fakeinfo']='/files/article/info<{$id|subdirectory}>/<{$id}>'.$jieqiConfigs['article']['fakefile'];
				}
				$repfrom = array('<{$jieqi_url}>', '<{$id|subdirectory}>', '<{$id}>');
				$repto = array(JIEQI_URL, jieqi_getsubdir($aid), $aid);
				$ret = trim(str_replace($repfrom, $repto, $jieqiConfigs['article']['fakeinfo']));
				if(substr($ret, 0, 4) != 'http') $ret = JIEQI_URL.$ret;
				return $ret;
			}else{
				return $article_dynamic_url.'/articleinfo.php?id='.$aid;
			}
			break;
	}
}

/**
 * 根据文章ID获得文章封面图片url
 * 
 * @param      int        $aid 文章id
 * @param      string     $type 显示类型 s - 小图， l - 大图
 * @param      int        $flag 图片类型标志 -1 则自动判断
 * @access     public
 * @return     string
 */
function jieqi_url_article_cover($aid, $type='s', $flag=-1){
	global $jieqiConfigs;
	global $article_dynamic_url;
	global $article_static_url;
	if($flag < 0){
		global $article;
		if(!is_a($article, 'JieqiArticle')){
			include_once($GLOBALS['jieqiModules']['article']['path'].'/class/article.php');
			$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
			$article=$article_handler->get($aid);
			if(is_object($article)) $flag = $article->getVar('imgflag','n');
		}
	}
	$flag = intval($flag);
	if($flag <= 0) return '';
	
	$imageinfo = array('stype'=>'', 'ltype'=>'');
	if(($flag & 1)>0) $imageinfo['stype']=$jieqiConfigs['article']['imagetype'];
	if(($flag & 2)>0) $imageinfo['ltype']=$jieqiConfigs['article']['imagetype'];
	$imgtype=$flag >> 2;
	if($imgtype > 0){
		$imgtary=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
		$tmpvar = round($imgtype & 7);
		if(isset($imgtary[$tmpvar])) $imageinfo['stype']=$imgtary[$tmpvar];
		$tmpvar = round($imgtype >> 3);
		if(isset($imgtary[$tmpvar])) $imageinfo['ltype']=$imgtary[$tmpvar];
	}
	
	switch($type){
		case 'l':
			if(!empty($imageinfo['ltype'])){
				return jieqi_uploadurl($jieqiConfigs['article']['imagedir'], $jieqiConfigs['article']['imageurl'], 'article', $article_static_url).jieqi_getsubdir($aid).'/'.$aid.'/'.$aid.'l'.$imageinfo['ltype'];
			}elseif(!empty($imageinfo['stype'])){
				return jieqi_uploadurl($jieqiConfigs['article']['imagedir'], $jieqiConfigs['article']['imageurl'], 'article', $article_static_url).jieqi_getsubdir($aid).'/'.$aid.'/'.$aid.'s'.$imageinfo['stype'];
			}else{
				return '';
			}
			break;
		case 's':
		default:
			if(!empty($imageinfo['stype'])){
				return jieqi_uploadurl($jieqiConfigs['article']['imagedir'], $jieqiConfigs['article']['imageurl'], 'article', $article_static_url).jieqi_getsubdir($aid).'/'.$aid.'/'.$aid.'s'.$imageinfo['stype'];
			}else{
				return '';
			}
			break;
	}
}

/**
 * 根据章节和文章ID获得章节阅读页面
 * 
 * @param      int        $cid 章节id
 * @param      int        $aid 文章id
 * @access     public
 * @return     string
 */
function jieqi_url_article_chapter($cid, $aid){
	global $jieqiConfigs;
	global $article_dynamic_url;
	global $article_static_url;

	if($jieqiConfigs['article']['makehtml']==0 || JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET){
		return $article_static_url.'/reader.php?aid='.$aid.'&cid='.$cid;
	}else{
		return jieqi_uploadurl($jieqiConfigs['article']['htmldir'], $jieqiConfigs['article']['htmlurl'], 'article', $article_static_url).jieqi_getsubdir($aid).'/'.$aid.'/'.$cid.$jieqiConfigs['article']['htmlfile'];
	}
}

/**
 * 显示排行榜url
 * 
 * @param      int        $page 页码
 * @param      string     $sort 排行类型
 * @access     public
 * @return     string
 */
function jieqi_url_article_toplist($page=1, $sort=''){
	global $jieqiConfigs;
	global $article_dynamic_url;
	global $article_static_url;
	$sorts = array('allvisit', 'monthvisit', 'weekvisit', 'dayvisit', 'allauthorvisit', 'monthauthorvisit', 'weekauthorvisit', 'dayauthorvisit', 'allvote', 'monthvote', 'weekvote', 'dayvote', 'postdate', 'lastupdate', 'authorupdate', 'masterupdate', 'goodnum', 'size', 'weekrate', 'monthrate', 'toptime');
	if(!in_array($sort, $sorts)) $sort = 'allvisit';
	if(!empty($page)){
		$page = intval($page);
		if($page < 1) $page = 1;
	}
	if(!empty($jieqiConfigs['article']['faketoplist'])){
		if(is_numeric($jieqiConfigs['article']['faketoplist'])){
			if(!empty($jieqiConfigs['article']['fakeprefix'])) $jieqiConfigs['article']['faketoplist']='/'.$jieqiConfigs['article']['fakeprefix'].'top<{$sort}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
			else $jieqiConfigs['article']['faketoplist']='/files/article/top<{$sort}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
		}
				
		$repfrom = array('<{$jieqi_url}>', '<{$sort}>');
		$repto = array(JIEQI_URL, $sort);
		if(!empty($page)){
			$repfrom[] = '<{$page|subdirectory}>';
			$repfrom[] = '<{$page}>';
			$repto[] = jieqi_getsubdir($page);
			$repto[] = $page;
		}
		$ret = trim(str_replace($repfrom, $repto, $jieqiConfigs['article']['faketoplist']));
		if(substr($ret, 0, 4) != 'http') $ret = JIEQI_URL.$ret;
		return $ret;
	}else{
		return $article_dynamic_url.'/toplist.php?sort='.$sort;
	}
}

/**
 * 显示分类列表url
 *
 * @param      int        $page 页码
 * @param      string     $class 类型id
 * @access     public
 * @return     string
 */
function jieqi_url_article_articlelist($page=1, $class=0){
	global $jieqiConfigs;
	global $article_dynamic_url;
	global $article_static_url;
	global $jieqiSort;

	$class = intval($class);
	if(!empty($page)){
		$page = intval($page);
		if($page < 1) $page = 1;
	}
	jieqi_getconfigs('article', 'sort');
	if(!isset($jieqiSort['article'][$class])) $class = '';

	if(!empty($jieqiConfigs['article']['fakesort'])){
		
		if(is_numeric($jieqiConfigs['article']['fakesort'])){
			if(!empty($jieqiConfigs['article']['fakeprefix'])) $jieqiConfigs['article']['fakesort']='/'.$jieqiConfigs['article']['fakeprefix'].'sort<{$class}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
			else $jieqiConfigs['article']['fakesort']='/files/article/sort<{$class}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
		}
				
		$repfrom = array('<{$jieqi_url}>', '<{$class}>');
		$repto = array(JIEQI_URL, $class);
		if(!empty($page)){
			$repfrom[] = '<{$page|subdirectory}>';
			$repfrom[] = '<{$page}>';
			$repto[] = jieqi_getsubdir($page);
			$repto[] = $page;
		}
		$ret = trim(str_replace($repfrom, $repto, $jieqiConfigs['article']['fakesort']));
		if(substr($ret, 0, 4) != 'http') $ret = JIEQI_URL.$ret;
		return $ret;
	}else{
		return $article_dynamic_url.'/articlelist.php?class='.$class;
	}
}

/**
 * 显示首字母分类列表url
 * 
 * @param      int        $page 页码
 * @param      string     $initial 首字母
 * @access     public
 * @return     string
 */
function jieqi_url_article_initial($page=1, $initial='0'){
	global $jieqiConfigs;
	global $article_dynamic_url;
	global $article_static_url;
	if(!empty($page)){
		$page = intval($page);
		if($page < 1) $page = 1;
	}
	$initial = strtoupper($initial);
	if($initial == '~') $initial = '0';
	$initials = array('1', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0');
	if(!in_array($initial, $initials)) $initial = 'A';

	if(!empty($jieqiConfigs['article']['fakeinitial'])){
		if(is_numeric($jieqiConfigs['article']['fakeinitial'])){
			if(!empty($jieqiConfigs['article']['fakeprefix'])) $jieqiConfigs['article']['fakeinitial']='/'.$jieqiConfigs['article']['fakeprefix'].'initial<{$initial}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
			else $jieqiConfigs['article']['fakeinitial']='/files/article/initial<{$initial}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
		}
		
		$repfrom = array('<{$jieqi_url}>', '<{$initial}>');
		$repto = array(JIEQI_URL, $initial);
		if(!empty($page)){
			$repfrom[] = '<{$page|subdirectory}>';
			$repfrom[] = '<{$page}>';
			$repto[] = jieqi_getsubdir($page);
			$repto[] = $page;
		}
		$ret = trim(str_replace($repfrom, $repto, $jieqiConfigs['article']['fakeinitial']));
		if(substr($ret, 0, 4) != 'http') $ret = JIEQI_URL.$ret;
		return $ret;
	}else{
		return $article_dynamic_url.'/articlelist.php?initial='.$initial;
	}
}
?>