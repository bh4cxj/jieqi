<?php
/**
 * 更新一篇文章处理流程
 *
 * 更新一篇文章处理流程
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: updateone.php 332 2009-02-23 09:15:08Z juny $
 */

if(!defined('JIEQI_ROOT_PATH')) exit;
include_once($GLOBALS['jieqiModules']['article']['path'].'/include/collectfunction.php');
include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
$aid=jieqi_textstr($aid);
$url=str_replace('<{articleid}>', $aid, $jieqiCollect['urlarticle']);
if(!empty($jieqiCollect['subarticleid'])){
	$subarticleid=0;
	$articleid=$aid;
	$tmpstr='$subarticleid = '.$jieqiCollect['subarticleid'].';';
	eval($tmpstr);
	$url=str_replace('<{subarticleid}>',$subarticleid, $url);
}
$colary=array('repeat'=>2, 'referer'=>$jieqiCollect['referer'], 'proxy_host'=>$jieqiCollect['proxy_host'], 'proxy_port'=>$jieqiCollect['proxy_port'], 'proxy_user'=>$jieqiCollect['proxy_user'], 'proxy_pass'=>$jieqiCollect['proxy_pass']);
if(!empty($jieqiCollect['pagecharset'])) $colary['charset']=$jieqiCollect['pagecharset'];
$source=jieqi_urlcontents($url, $colary);
if(empty($source)){
	echo sprintf($jieqiLang['article']['collect_articleinfo_failure'], $url, $url);
	ob_flush();
	flush();
}else{
	//标题
	$pregstr=jieqi_collectstoe($jieqiCollect['articletitle']);
	$matchvar=jieqi_cmatchone($pregstr, $source);
	if(!empty($matchvar)){
		//获得文章基本信息
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
		if(!empty($articleimage) && !in_array(strrchr($articleimage, '.'),array('.gif', '.jpg', '.jpeg', '.bmp', '.png'))) $articleimage='';
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

		//检查文章是否存在，没有就新建文章
		$errtext='';
		//检查标题
		if (strlen($articletitle)==0) $errtext .= $jieqiLang['article']['collect_title_empty'].'<br />';
		elseif (!jieqi_safestring($articletitle)) $errtext .= $jieqiLang['article']['collect_title_formaterr'].'<br />';
		if(!empty($errtext)){
			echo $errtext;
			ob_flush();
			flush();
		}else{
			$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
			$criteria=new CriteriaCompo(new Criteria('articlename', $articletitle, '='));
			$res=$article_handler->queryObjects($criteria);
			$article=$article_handler->getObject($res);
			$toid=0;
			if(!empty($article)){
				if($article->getVar('display') != 0){
					echo sprintf($jieqiLang['article']['collect_article_notaudit'], $articletitle);
					ob_flush();
					flush();
				}else{
					$toid=$article->getVar('articleid');
				}
			}else{
				if($_REQUEST['notaddnew']==1){
					echo sprintf($jieqiLang['article']['collect_article_notexists'], $articletitle);
					ob_flush();
					flush();
				}else{
					//增加文章
					$newArticle = $article_handler->create();
					$newArticle->setVar('siteid', JIEQI_SITE_ID);
					$newArticle->setVar('postdate', JIEQI_NOW_TIME);
					$newArticle->setVar('lastupdate', JIEQI_NOW_TIME);
					$newArticle->setVar('articlename', $articletitle);
					$newArticle->setVar('keywords', trim($keywords));
					$newArticle->setVar('initial', jieqi_getinitial($articletitle));
					$newArticle->setVar('agentid', 0);
					$newArticle->setVar('agent', '');
					$newArticle->setVar('authorid', 0);
					$newArticle->setVar('author', $author);
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
					$newArticle->setVar('fullflag', $fullarticle);

					if(!empty($sort) && isset($jieqiCollect['sortid'][$sort])) $sortid=$jieqiCollect['sortid'][$sort];
					elseif(isset($jieqiCollect['sortid']['default'])) $sortid=$jieqiCollect['sortid']['default'];
					else $sortid=0;
					$newArticle->setVar('sortid', $sortid);
					$newArticle->setVar('intro', $intro);
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
					$newArticle->setVar('permission', 0);
					$newArticle->setVar('firstflag', 0);
					$imgflag=0;
					$articleimage=trim($articleimage);
					if(!empty($articleimage)){
						$imagetype=strrchr($articleimage, '.');
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
					if (!$article_handler->insert($newArticle)){
						echo sprintf($jieqiLang['article']['collect_addarticle_failure'], $articletitle);
						ob_flush();
						flush();
					}else {
						$id=$newArticle->getVar('articleid');
						$package=new JieqiPackage($id);
						$package->initPackage(array('id'=>$newArticle->getVar('articleid','n'), 'title'=>$newArticle->getVar('articlename', 'n'), 'creatorid'=>$newArticle->getVar('authorid','n'), 'creator'=>$newArticle->getVar('author','n'), 'subject'=>$newArticle->getVar('keywords','n'), 'description'=>$newArticle->getVar('intro', 'n'), 'publisher'=>JIEQI_SITE_NAME, 'contributorid'=>$newArticle->getVar('posterid', 'n'), 'contributor'=>$newArticle->getVar('poster', 'n'), 'sortid'=>$newArticle->getVar('sortid', 'n'), 'typeid'=>$newArticle->getVar('typeid', 'n'), 'articletype'=>$newArticle->getVar('articletype', 'n'), 'permission'=>$newArticle->getVar('permission', 'n'), 'firstflag'=>$newArticle->getVar('firstflag', 'n'), 'fullflag'=>$newArticle->getVar('fullflag', 'n'), 'imgflag'=>$newArticle->getVar('imgflag', 'n'), 'power'=>$newArticle->getVar('power', 'n'), 'display'=>$newArticle->getVar('display', 'n')));
						$toid=$id;
						//采集图片
						if(!empty($articleimage) && $imgflag>0){
							$colary=array('repeat'=>2, 'referer'=>$jieqiCollect['referer'], 'proxy_host'=>$jieqiCollect['proxy_host'], 'proxy_port'=>$jieqiCollect['proxy_port'], 'proxy_user'=>$jieqiCollect['proxy_user'], 'proxy_pass'=>$jieqiCollect['proxy_pass']);
							if(!empty($colary['referer'])) $colary['referer']=$url;
							$tmpstr=jieqi_urlcontents($articleimage,$colary);
							if(!empty($tmpstr)){
								$imagefile=$package->getDir('imagedir').'/'.$id.'s'.$imagetype;
								@jieqi_writefile($imagefile, $tmpstr);
								@chmod($imagefile, 0777);
							}
						}
					}
				}
			}
			
			if(!empty($toid)){
				$fromid=$aid;
				$_REQUEST['fromid']=$fromid;
				$_REQUEST['toid']=$toid;
				$error_continue=true;
				include($GLOBALS['jieqiModules']['article']['path'].'/include/collectarticle.php');
			}
		}
	}else{
		echo sprintf($jieqiLang['article']['parse_articleinfo_failure'], $url, $url);
		ob_flush();
		flush();
	}
}

echo '<hr />';


?>