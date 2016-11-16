<?php 
/**
 * 采集文章，内部处理过程
 *
 * 采集文章，内部处理过程
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: collectarticle.php 330 2009-02-09 16:07:35Z juny $
 */

if(!defined('JIEQI_ROOT_PATH')) exit;

if(empty($_REQUEST['siteid']) || empty($_REQUEST['fromid']) || empty($_REQUEST['toid'])) exit;
$_REQUEST['fromid']=trim($_REQUEST['fromid']);
$_REQUEST['toid']=intval($_REQUEST['toid']);
include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
include_once($GLOBALS['jieqiModules']['article']['path'].'/include/collectfunction.php');

$retflag=0; //返回标志 
//0 初始状态 1 采集完成 2 不需要采集 3 采集失败 4 需要采集但是对应不上
$retchapinfo=array(); //返回对应不上的章节
$retlogs=array(); //返回日志
$jieqi_collect_time=time(); //采集时间
jieqi_getconfigs(JIEQI_MODULE_NAME, 'collectsite'); //载入采集点配置

if(array_key_exists($_REQUEST['siteid'], $jieqiCollectsite) && $jieqiCollectsite[$_REQUEST['siteid']]['enable']=='1'){
	//载入采集规则
	include_once(JIEQI_ROOT_PATH.'/configs/article/site_'.$jieqiCollectsite[$_REQUEST['siteid']]['config'].'.php');
	//取远程文章信息
	if(empty($jieqiCollect['articletitle'])) jieqi_printfail($jieqiLang['article']['collect_rule_notfull']);
	$colary=array('repeat'=>2, 'referer'=>$jieqiCollect['referer'], 'proxy_host'=>$jieqiCollect['proxy_host'], 'proxy_port'=>$jieqiCollect['proxy_port'], 'proxy_user'=>$jieqiCollect['proxy_user'], 'proxy_pass'=>$jieqiCollect['proxy_pass']);
	if(!empty($jieqiCollect['pagecharset'])) $colary['charset']=$jieqiCollect['pagecharset'];
	//需要从信息页面解析目录页面链接
	$indexlink='';
	if(strpos($jieqiCollect['urlindex'],'<{indexlink}>') !== false && !empty($jieqiCollect['indexlink'])){
		$url=str_replace('<{articleid}>', $_REQUEST['fromid'], $jieqiCollect['urlarticle']);
		if(!empty($jieqiCollect['subarticleid'])){
			$subarticleid=0;
			$articleid=$_REQUEST['fromid'];
			$tmpstr='$subarticleid = '.$jieqiCollect['subarticleid'].';';
			eval($tmpstr);
			$url=str_replace('<{subarticleid}>',$subarticleid, $url);
		}
		$source=jieqi_urlcontents($url,$colary);
		if(empty($source)) jieqi_printfail(sprintf($jieqiLang['article']['collect_articleinfo_failure'], $url, $url));
		//目录链接
		$pregstr=jieqi_collectstoe($jieqiCollect['indexlink']);
		if(!empty($pregstr)){
			$matchvar=jieqi_cmatchone($pregstr, $source);
			if(!empty($matchvar)) $indexlink=trim(jieqi_textstr($matchvar));
		}
	}
	//解析目录页地址
	if(!empty($indexlink)) $tmpstr=str_replace('<{indexlink}>', $indexlink, $jieqiCollect['urlindex']);
	else $tmpstr=$jieqiCollect['urlindex'];
	$url=str_replace('<{articleid}>', $_REQUEST['fromid'], $tmpstr);
	if(!empty($jieqiCollect['subarticleid'])){
		$subarticleid=0;
		$articleid=$_REQUEST['fromid'];
		$tmpstr='$subarticleid = '.$jieqiCollect['subarticleid'].';';
		eval($tmpstr);
		$url=str_replace('<{subarticleid}>',$subarticleid, $url);
	}
	//取得目录页内容
	$source=jieqi_urlcontents($url, $colary);
	if(empty($source)){
		if($error_continue==true){
			echo sprintf($jieqiLang['article']['collect_index_failure'], $url, $url);
			ob_flush();
			flush();
			$retflag=3; 
		}else{
			jieqi_printfail(sprintf($jieqiLang['article']['collect_index_failure'], $url, $url));
		}
	}else{
		//开始解析章节
		$newCollect=array();
		$newCollect['chapter']=$jieqiCollect['chapter'];
		$newCollect['volume']=$jieqiCollect['volume'];
		$newCollect['chapterid']=$jieqiCollect['chapterid'];
		$newCollect['content']=$jieqiCollect['content'];
		//匹配章节名
		$pregstr=jieqi_collectstoe($jieqiCollect['chapter']);
		$matchvar=jieqi_cmatchall($pregstr, $source, PREG_OFFSET_CAPTURE);
		if(empty($matchvar)){
			//没匹配上
			if($error_continue==true){
				echo sprintf($jieqiLang['article']['parse_chapter_failure'], $url, $url);
				ob_flush();
				flush();
				$retflag=3; //采集解析失败
			}else{
				jieqi_printfail(sprintf($jieqiLang['article']['parse_chapter_failure'], $url, $url));
			}
		}else{
			if(is_array($matchvar)) $chapterary=$matchvar;
			else $chapterary=array();

			//匹配章节序号
			$pregstr=jieqi_collectstoe($jieqiCollect['chapterid']);
			$matchvar=jieqi_cmatchall($pregstr, $source, PREG_OFFSET_CAPTURE);
			if(is_array($matchvar)) $chapteridary=$matchvar;
			else $chapteridary=array();

			//匹配卷名
			$volumeary=array();
			$pregstr=jieqi_collectstoe($jieqiCollect['volume']);
			if(!empty($pregstr)){
				$matchvar=jieqi_cmatchall($pregstr, $source, PREG_OFFSET_CAPTURE);
				if(is_array($matchvar)) $volumeary=$matchvar;
				else $volumeary=array();
			}

			//组合章节和分卷名称
			$fromrows=array();
			$i=0;
			$j=0;
			$k=0;
			$chapternum=count($chapterary);
			$volumenum=count($volumeary);
			$volumename='';
			while($j<$chapternum || $k<$volumenum){
				if($j<$chapternum) $a=$chapterary[$j][1];
				else $a=99999999;
				if($k<$volumenum) $b=$volumeary[$k][1];
				else $b=99999999;
				if($a<$b){
					$tmpvar=trim(jieqi_textstr($chapterary[$j][0]));
					if($tmpvar != ''){
						$fromrows[$i]['title']=$tmpvar;
						$fromrows[$i]['type']=0;
						$fromrows[$i]['id']=$chapteridary[$j][0];
						$fromrows[$i]['vname']=$volumename;
						$i++;
					}
					$j++;
				}else{
					$tmpvar=trim(jieqi_textstr($volumeary[$k][0]));
					if($tmpvar != ''){
						$fromrows[$i]['title']=$tmpvar;
						$fromrows[$i]['type']=1;
						$fromrows[$i]['id']=0;
						$fromrows[$i]['vname']=$tmpvar;
						$volumename=$tmpvar;
						$i++;
					}
					$k++;
				}
			}

			//取本地文章信息
			include_once($GLOBALS['jieqiModules']['article']['path'].'/class/article.php');
			$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
			$article =& $article_handler->get($_REQUEST['toid']);
			if(!is_object($article)) jieqi_printfail($jieqiLang['article']['article_not_exists']);
			$myarticlename = $article->getVar('articlename', 'n');
			//是否全本
			if($article->getVar('fullflag') == 1) $fromisfull=true;
			else $fromisfull=false;
			
			include_once($GLOBALS['jieqiModules']['article']['path'].'/class/chapter.php');
			$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
			$criteria=new CriteriaCompo(new Criteria('articleid', $article->getVar('articleid'), '='));
			$criteria->setSort('chapterorder');
			$criteria->setOrder('ASC');
			$chapter_handler->queryObjects($criteria, true);
			$torows=array();
			$i=0;
			$volumename='';
			while($row=$chapter_handler->getRow()){
				$torows[$i]['title']=trim(jieqi_textstr($row['chaptername']));
				$torows[$i]['type']=$row['chaptertype'];
				if($row['chaptertype']==0){
					$torows[$i]['vname']=$volumename;
				}else{
					$torows[$i]['vname']=$torows[$i]['title'];
					$volumename=$torows[$i]['title'];
				}
				$i++;
			}
			$checkvolume=false; //对比章节时候要不要匹配卷名（默认不要）
			$vorder=0;
			$corder=0;
			//去掉分卷中的文章名
			foreach($fromrows as $key=>$value){
				$fromrows[$key]['vname'] = trim(str_replace($myarticlename, '', $fromrows[$key]['vname']));
				if($value['type']>0){
					$fromrows[$key]['title'] = $fromrows[$key]['vname'];
					$vorder++;
					$corder=0;
				}else{
					$corder++;
					if($vorder > 1 && $corder == 1){
						$tempary = jieqi_splitchapter($fromrows[$key]['vname'].' '.$fromrows[$key]['title']);
						//分卷大于一，章节从一开始的情况
						if($tempary['vid']>1 && $tempary['cid']==1) $checkvolume=true;
					}
				}
			}
			foreach($torows as $key=>$value){
				$torows[$key]['vname'] = trim(str_replace($myarticlename, '', $torows[$key]['vname']));
				if($value['type']>0) $torows[$key]['title'] = $torows[$key]['vname'];
			}
			

			//比较更新内容 $fp=frompoint， $tp=topoint
			$fromnum=count($fromrows);
			$tonum=count($torows);
			$maxchapterorder=$tonum;
			if($tonum==0){
				$fp=0;  //重新开始采集
				$tp=0;
			}
			/*
			elseif($tonum - 20 > $fromnum || $tonum * 0.7 > $fromnum){
				$fp=$tonum;  //不采集
				$tp=$tonum;
			}
			*/
			else{
				//检查是否有新章节（可能是中间插入章节的情况）
				$fp=0;
				$tp=0;
				//从开头章节开始判断，章节是否完全对应(错一个章节，如果后面章节对应，仍旧可以算对应)
				while($fp<$fromnum && $tp<$tonum){
					if((jieqi_equichapter($fromrows[$fp]['title'], $torows[$tp]['title']) && $fromrows[$fp]['type'] == $torows[$tp]['type'])){
						$fp++;
						$tp++;
					}elseif($fp<$fromnum-1 && $tp<$tonum-1 && jieqi_equichapter($fromrows[$fp+1]['title'], $torows[$tp+1]['title']) && $fromrows[$fp+1]['type'] == $torows[$tp+1]['type']){
						$retchapinfo[]=array('fchapter'=>($fromrows[$fp]['type']==0) ? $fromrows[$fp]['vname'].' '.$fromrows[$fp]['title'] : $fromrows[$fp]['vname'], 'tchapter'=>($torows[$tp]['type']==0) ? $torows[$tp]['vname'].' '.$torows[$tp]['title'] : $torows[$tp]['vname']);
						$fp+=2;
						$tp+=2;
					}else{
						$retchapinfo[]=array('fchapter'=>($fromrows[$fp]['type']==0) ? $fromrows[$fp]['vname'].' '.$fromrows[$fp]['title'] : $fromrows[$fp]['vname'], 'tchapter'=>($torows[$tp]['type']==0) ? $torows[$tp]['vname'].' '.$torows[$tp]['title'] : $torows[$tp]['vname']);
						break;
					}
				}
				if($tp<$tonum){
					//中间插入章节的情况，检查后面的章节是不是可以完全同步
					$j=$tp;
					$k=$tp;
					//检查后面的章节能不能对应
					while($j<$tonum){
						while($k<$fromnum){
							//本章节不对应
							if(!jieqi_equichapter($fromrows[$k]['title'],$torows[$j]['title']) || $fromrows[$k]['type'] != $torows[$j]['type']){
								//下章节对应也允许通过
								if($k<$fromnum-1 && $j<$tonum-1 && jieqi_equichapter($fromrows[$k+1]['title'],$torows[$j+1]['title']) && $fromrows[$k+1]['type'] == $torows[$j+1]['type']){
									$k++;
									$j++;
									break;
								}else{
									$k++;
								}
							}else{
								break;
							}
						}
						if($k<$fromnum) $j++;
						else break;
					}
					//后面章节不能完全对应，就看本站最后章节后面是否需要更新(倒过来检查)
					if($k>=$fromnum){
						$j=$tp;
						$mn=$fromnum-$j;
						$j=$fromnum;
						$m=1;
						while($m<=$mn){
							if(jieqi_equichapter($fromrows[$fromnum-$m]['title'], $torows[$tonum-1]['title']) && $fromrows[$fromnum-$m]['type'] == $torows[$tonum-1]['type'] &&  ($checkvolume == false || $fromrows[$fromnum-$m]['vname'] == $torows[$tonum-1]['vname'])){
								$j=$fromnum-$m;
								break;
							}
							$m++;
						}
						//本站最后章节能对上
						if($j < $fromnum){
							$fp=$j+1;
							$tp=$tonum;
						}else{
							//无法对应，允许的话清空重新采集

							//后面的章节不能完全对应上，如果上次是采集同一个网站的文章，并且允许清空，则清空后重新采集，否则就不采集
							//$setting=unserialize($article->getVar('setting', 'n'));
							//if(!is_array($setting)) $setting=array();
							//if($setting['fromsite']==$_REQUEST['siteid'] && $setting['fromarticle']==$_REQUEST['fromid'] && $jieqiCollect['autoclear']==1){
							//允许清空，并且不是标记完成的书
							if($jieqiCollect['autoclear']==1 && $fromisfull==false){
								echo sprintf($jieqiLang['article']['article_collect_clean'], jieqi_htmlstr($article->getVar('articlename')));
								//清除文章统计
								$oldchapters=$article->getVar('chapters');
								$article->setVar('lastchapter', '');
								$article->setVar('lastchapterid', 0);
								$article->setVar('lastvolume', '');
								$article->setVar('lastvolumeid', 0);
								$article->setVar('chapters', 0);
								$article->setVar('size', 0);
								$article_handler->insert($article);
								//删除文本、html及zip
								include_once($GLOBALS['jieqiModules']['article']['path'].'/class/package.php');
								$package=new JieqiPackage($_REQUEST['toid']);
								$package->delete();
								$package->initPackage(array('id'=>$article->getVar('articleid','n'), 'title'=>$article->getVar('articlename', 'n'), 'creatorid'=>$article->getVar('authorid','n'), 'creator'=>$article->getVar('author','n'), 'subject'=>$article->getVar('keywords','n'), 'description'=>$article->getVar('intro', 'n'), 'publisher'=>JIEQI_SITE_NAME, 'contributorid'=>$article->getVar('posterid', 'n'), 'contributor'=>$article->getVar('poster', 'n'), 'sortid'=>$article->getVar('sortid', 'n'), 'typeid'=>$article->getVar('typeid', 'n'), 'articletype'=>$article->getVar('articletype', 'n'), 'permission'=>$article->getVar('permission', 'n'), 'firstflag'=>$article->getVar('firstflag', 'n'), 'fullflag'=>$article->getVar('fullflag', 'n'), 'imgflag'=>$article->getVar('imgflag', 'n'), 'power'=>$article->getVar('power', 'n'), 'display'=>$article->getVar('display', 'n')));
								unset($package);

								//删除章节
								$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['toid'], '='));
								$chapter_handler->delete($criteria);
								unset($criteria);
								//减少文章和章节积分
								/*
								include_once(JIEQI_ROOT_PATH.'/class/users.php');
								$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
								jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
								if(!empty($jieqiConfigs['article']['scorechapter'])){
								if($article->getVar('posterid')==$_SESSION['jieqiUserId']){
								$users_handler->changeScore($_SESSION['jieqiUserId'], $oldchapters * $jieqiConfigs['article']['scorechapter'], false);
								}else{
								$users_handler->changeScore($article->getVar('posterid'), $oldchapters * $jieqiConfigs['article']['scorechapter'], false);
								}
								}
								*/
								$fp=0;
								$tp=0;
								$torows=array();
								$tonum=0;
								$maxchapterorder=0;
							}else{
								$fp=$fromnum;
								$tp=$fromnum;
								if($error_continue==true){
									$errchapter='';
									foreach ($retchapinfo as $v){
										$errchapter.=$v['fchapter'].' => '.$v['tchapter'].'<br />';
									}
									echo sprintf($jieqiLang['article']['collect_cant_update'], $errchapter, $article_static_url.'/articlemanage.php?id='.$_REQUEST['toid'], $article_static_url.'/articleclean.php?id='.$_REQUEST['toid'].'&collecturl='.urlencode($article_static_url.'/admin/updatecollect.php?siteid='.$_REQUEST['siteid'].'&fromid='.$_REQUEST['fromid'].'&toid='.$_REQUEST['toid']), $article_static_url.'/admin/collect.php');
									ob_flush();
									flush();
								}
								$retflag=4; //有需要采集的，但是没法对应上
							}
						} //连本站最后一章也对应不上
					} //后面章节不能完全对应，就看本站最后章节后面是否需要更新
				} //中间插入章节的情况，检查后面的章节是不是可以完全同步
			} ////检查是否有新章节（可能是中间插入章节的情况）

			//准备更新章节
			if($fp<$fromnum && $tp<=$tonum){
				//需要从目录页面解析章节页面链接
				$chapterlink='';
				if(strpos($jieqiCollect['urlchapter'],'<{chapterlink}>') !== false && !empty($jieqiCollect['chapterlink'])){
					//章节链接
					$pregstr=jieqi_collectstoe($jieqiCollect['chapterlink']);
					if(!empty($pregstr)){
						$matchvar=jieqi_cmatchone($pregstr, $source);
						if(!empty($matchvar)) $chapterlink=trim(jieqi_textstr($matchvar));
					}
				}

				if(!isset($jieqiConfigs['article'])) jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
				include_once(JIEQI_ROOT_PATH.'/lib/text/texttypeset.php');
				$texttypeset=new TextTypeset();
				//增加章节
				//不直接进行生成页面和压缩包
				$old_makehtml=$jieqiConfigs['article']['makehtml'];
				$jieqiConfigs['article']['makehtml']=0;

				include_once($GLOBALS['jieqiModules']['article']['path'].'/class/chapter.php');
				$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
				$size=$article->getVar('size');
				$lastchapter=$article->getVar('lastchapter','n');
				$lastchapterid=$article->getVar('lastchapterid','n');
				$lastvolume=$article->getVar('lastvolume','n');
				$lastvolumeid=$article->getVar('lastvolumeid','n');
				$lastchapterorder=$tp+1;

				if(!empty($jieqiConfigs['article']['hidearticlewords'])){
					$articlewordssplit = (strlen($jieqiConfigs['article']['articlewordssplit'])==0) ? ' ' : $jieqiConfigs['article']['articlewordssplit'];
					$filterary=explode($articlewordssplit, $jieqiConfigs['article']['hidearticlewords']);
					$filter=true;
				}else{
					$filter=false;
				}

				echo '                                                                                                                                                                                                                                                                ';
				if($tp == $tonum) $tmpvar=$fromnum-$fp;
				else $tmpvar=$fromnum-$tonum;
				echo sprintf($jieqiLang['article']['collect_chapter_doing'], jieqi_htmlstr($article->getVar('articlename')), $tmpvar);

				ob_flush();
				flush();
				$c=1;
				$k=$fp;
				$q=$tp;
				//$k用于$fromrows， $q用于$torows
				while($k<$fromnum){
					//如果章节对应上就不用采集了
					if($q<$tonum && jieqi_equichapter($fromrows[$k]['title'],$torows[$q]['title']) && $fromrows[$k]['type'] == $torows[$q]['type']){
						$k++;
						$q++;
						continue;
					}elseif($k<$fromnum-1 && $q<$tonum-1 && jieqi_equichapter($fromrows[$k+1]['title'],$torows[$q+1]['title']) && $fromrows[$k+1]['type'] == $torows[$q+1]['type']){
						$k+=2;
						$q+=2;
						continue;
					}
					//取章节内容
					if(isset($chaptercontent)) unset($chaptercontent);
					if(isset($url)) unset($url);
					if($fromrows[$k]['type']==0){
						if(!empty($indexlink)) $tmpstr=str_replace('<{indexlink}>', $indexlink, $jieqiCollect['urlchapter']);
						else $tmpstr=$jieqiCollect['urlchapter'];
						if(!empty($chapterlink)) $tmpstr=str_replace('<{chapterlink}>', $chapterlink, $tmpstr);
						$url=str_replace('<{articleid}>', $_REQUEST['fromid'], $tmpstr);
						$url=str_replace('<{chapterid}>', $fromrows[$k]['id'], $url);
						if(!empty($jieqiCollect['subarticleid'])){
							$subarticleid=0;
							$articleid=$_REQUEST['fromid'];
							$chapterid=$fromrows[$k]['id'];
							$tmpstr='$subarticleid = '.$jieqiCollect['subarticleid'].';';
							eval($tmpstr);
							$url=str_replace('<{subarticleid}>',$subarticleid, $url);
						}
						if(!empty($jieqiCollect['subchapterid'])){
							$subchapterid=0;
							$articleid=$_REQUEST['fromid'];
							$chapterid=$fromrows[$k]['id'];
							$tmpstr='$subchapterid = '.$jieqiCollect['subchapterid'].';';
							eval($tmpstr);

							$url=str_replace('<{subchapterid}>',$subchapterid, $url);
						}
						$chaptercontent=jieqi_urlcontents($url, $colary);
						if(!empty($colary['referer'])) $colary['referer']=$url;
						if(!$chaptercontent) $chaptercontent='';
					}else{
						$chaptercontent='';
					}
					//分离章节内容
					$pregstr=jieqi_collectstoe($jieqiCollect['content']);
					//echo strlen($chaptercontent);
					$matchvar=jieqi_cmatchone($pregstr, $chaptercontent);
					if(!empty($matchvar)){
						$chaptercontent=$matchvar;
						
						//如果是文本地址或者js地址，则采集内容
						if(strlen($chaptercontent)>3 && strlen($chaptercontent)<200){
							$urlcontent=trim($chaptercontent);
							//看看是不是 script src="" 的代码
							$matches=array();
							preg_match('/\<script[^\<\>]*src=(\'|")([^\<\>\'"]*)(\'|")[^\<\>]*\>/is', $urlcontent, $matches);
							if(!empty($matches[2])) $urlcontent=$matches[2];
							$tmpstr=strtolower(strrchr($urlcontent, '.'));
							if($tmpstr=='.txt' || $tmpstr=='.js'){
								//内容如果相对地址，改成绝对的
								if(strpos($urlcontent,'http') !== 0){
									if(substr($urlcontent,0,1)=='/'){
										$urlmatches=array();
										preg_match('/https?:\/\/[^\/]+/is',$url,$urlmatches);
										if(!empty($urlmatches[0])) $urlcontent=$urlmatches[0].$urlcontent;
										else $urlcontent=$jieqiCollect['siteurl'].$urlcontent;
									}else{
										$tmpdir=dirname($url);
										while(strpos($urlcontent, '../')===0){
											$tmpdir=dirname($tmpdir);
											$urlcontent=substr($urlcontent, 3);
										}
										$urlcontent=$tmpdir.'/'.$urlcontent;
									}
								}
								$newcontent=jieqi_urlcontents($urlcontent, $colary);
								if(!empty($newcontent)){
									$matches=array();
									preg_match('/document.write\((\'|")(.*)(\'|")\);/is', $newcontent, $matches);
									if(!empty($matches[2])) $chaptercontent=$matches[2];
								}
							}
						}
						//过滤章节内容
						if(!empty($jieqiCollect['contentfilter'])){
							$filterary=explode("\n", $jieqiCollect['contentfilter']);
							$repfrom=array();
							foreach($filterary as $filterstr){
								$filterstr=trim($filterstr);
								if(!empty($filterstr)){
									if(preg_match('/^\/[^\/\\\\]*(?:\\\\.[^\/\\\\]*)*\/[imsu]*$/is', $filterstr)) $repfrom[]=$filterstr;
									else $repfrom[]='/'.jieqi_pregconvert($filterstr).'/is';
								}
							}
							$repto='';
							if(!empty($jieqiCollect['contentreplace'])){
								$repto=explode("\n", str_replace("\r\n","\n",$jieqiCollect['contentreplace']));
							}
							if(count($repfrom) > 0) $chaptercontent=preg_replace($repfrom, $repto, $chaptercontent);
						}
					}else{
						$chaptercontent='';
					}

					if($fromrows[$k]['type']==0 && strlen(trim($chaptercontent))==0){
						echo sprintf($jieqiLang['article']['chapter_collect_failure'], $c, jieqi_htmlstr($fromrows[$k]['title']), $url, $url);
						ob_flush();
						flush();
					}else{
						//过滤用户设置的内容
						if($filter) $chaptercontent=str_replace($filterary, '', $chaptercontent);
						$imagecontentary=array();
						$infoary=array();
						$attachnum=0;
						$attachinfo='';
						//检查是否有图片地址，有的话采集成自己的附件
						if($jieqiCollect['collectimage']==1){
							$matches=array();
							preg_match_all('/\<img[^\<\>]+src=[\'"]?((https?:\/\/|www\.)?[a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+(\.gif|\.jpg|\.jpeg|\.png|\.bmp))[^\<\>]*\>/is', $chaptercontent, $matches);
							//有图片
							if(!empty($matches[1])){
								$imageurls=array();
								//采集图片
								foreach($matches[1] as $s=>$v){
									$imageurls[]=$v;
									$imageurl=$v;
									//内容如果相对地址，改成绝对的
									if(strpos($imageurl,'http') !== 0){
										if(substr($imageurl,0,1)=='/'){
											$urlmatches=array();
											preg_match('/https?:\/\/[^\/]+/is',$url,$urlmatches);
											if(!empty($urlmatches[0])) $imageurl=$urlmatches[0].$imageurl;
											else $imageurl=$jieqiCollect['siteurl'].$imageurl;
										}else{
											$tmpdir=dirname($url);
											while(strpos($imageurl, '../')===0){
												$tmpdir=dirname($tmpdir);
												$imageurl=substr($imageurl, 3);
											}
											$imageurl=$tmpdir.'/'.$imageurl;
										}
									}
									$img_colary=$colary;
									$img_colary['charset']='image';
									$imagecontentary[$attachnum]=jieqi_urlcontents($imageurl,$img_colary);
									if($s==0 && empty($imagecontentary[$attachnum])) break;
									$infoary[$attachnum]=array('name'=>basename($imageurl), 'class'=>'image', 'postfix'=>substr(strrchr($imageurl,'.'),1), 'size'=>strlen($imagecontentary[$attachnum]));
																
									//图片入库
									include_once($GLOBALS['jieqiModules']['article']['path'].'/class/articleattachs.php');
									$attachs_handler =& JieqiArticleattachsHandler::getInstance('JieqiArticleattachsHandler');
									$newAttach = $attachs_handler->create();
									$newAttach->setVar('articleid', $_REQUEST['toid']);
									$newAttach->setVar('chapterid', 0);
									$newAttach->setVar('name', $infoary[$attachnum]['name']);
									$newAttach->setVar('class', $infoary[$attachnum]['class']);
									$newAttach->setVar('postfix', $infoary[$attachnum]['postfix']);
									$newAttach->setVar('size', $infoary[$attachnum]['size']);
									$newAttach->setVar('hits', 0);
									$newAttach->setVar('needexp', 0);
									$newAttach->setVar('uptime', $jieqi_collect_time);
									if($attachs_handler->insert($newAttach)){
										$attachid=$newAttach->getVar('attachid');
										$infoary[$attachnum]['attachid']=$attachid;
									}else{
										$infoary[$attachnum]['attachid']=0;
									}
									$attachnum++;
								}
								if($attachnum>0){
									$chaptercontent=str_replace($imageurls,'',$chaptercontent);
									$attachinfo=serialize($infoary);
								}
							}
						}else{
							$matches=array();
							preg_match_all('/\<img[^\<\>]+src=[\'"]?((https?:\/\/|www\.)?[a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+(\.gif|\.jpg|\.jpeg|\.png|\.bmp))[^\<\>]*\>/is', $chaptercontent, $matches);
							//有图片
							if(!empty($matches[1])){
								$imageurls=array();
								//采集图片
								foreach($matches[1] as $s=>$v){
									$imageurl=$v;
									//内容如果相对地址，改成绝对的
									if(strpos($imageurl,'http') !== 0){
										if(substr($imageurl,0,1)=='/'){
											$urlmatches=array();
											preg_match('/https?:\/\/[^\/]+/is',$url,$urlmatches);
											if(!empty($urlmatches[0])) $imageurl=$urlmatches[0].$imageurl;
											else $imageurl=$jieqiCollect['siteurl'].$imageurl;
										}else{
											$tmpdir=dirname($url);
											while(strpos($imageurl, '../')===0){
												$tmpdir=dirname($tmpdir);
												$imageurl=substr($imageurl, 3);
											}
											$imageurl=$tmpdir.'/'.$imageurl;
										}
										$chaptercontent=str_replace($v,$imageurl,$chaptercontent);
									}
								}
							}
						}
						//转换成文本
						$chaptercontent=jieqi_textstr($chaptercontent, true);

						//内容排版
						$chaptercontent=$texttypeset->doTypeset($chaptercontent);
						//如果是插入章节，则原来章节的序号加一位
						if($q<$tonum){
							$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['toid']));
							$criteria->add(new Criteria('chapterorder', $q, '>'));
							$chapter_handler->updatefields('chapterorder=chapterorder+1', $criteria);
							unset($criteria);
						}

						$newChapter = $chapter_handler->create();
						$newChapter->setVar('siteid', JIEQI_SITE_ID);
						$chaptersize=strlen($chaptercontent);
						$newChapter->setVar('articleid', $_REQUEST['toid']);
						$newChapter->setVar('articlename', $article->getVar('articlename', 'n'));
						$newChapter->setVar('volumeid', 0);
						if(!empty($_SESSION['jieqiUserId'])){
							$newChapter->setVar('posterid', $_SESSION['jieqiUserId']);
							$newChapter->setVar('poster', $_SESSION['jieqiUserName']);
						}else{
							$newChapter->setVar('posterid', 0);
							$newChapter->setVar('poster', '');
						}
						$newChapter->setVar('postdate', $jieqi_collect_time);
						$newChapter->setVar('lastupdate', $jieqi_collect_time);
						$newChapter->setVar('chaptername', $fromrows[$k]['title']);
						$newChapter->setVar('chapterorder', $q+1);
						$newChapter->setVar('size', $chaptersize);
						$newChapter->setVar('chaptertype', $fromrows[$k]['type']);
						$newChapter->setVar('saleprice', 0);
						$newChapter->setVar('salenum', 0);
						$newChapter->setVar('totalcost', 0);
						$newChapter->setVar('attachment', $attachinfo);
						$newChapter->setVar('isvip', 0);
						$newChapter->setVar('power', 0);
						$newChapter->setVar('display', 0);
						if (!$chapter_handler->insert($newChapter)) jieqi_printfail($jieqiLang['article']['add_chapter_failure']);
						else {
							$newid=$newChapter->getVar('chapterid');
							$txtdir=jieqi_uploadpath($jieqiConfigs['article']['txtdir'], 'article');
							if (!file_exists($txtdir)) jieqi_createdir($txtdir);
							$txtdir = $txtdir.jieqi_getsubdir($_REQUEST['toid']);
							if (!file_exists($txtdir)) jieqi_createdir($txtdir);
							$txtdir = $txtdir.'/'.$_REQUEST['toid'];
							if (!file_exists($txtdir)) jieqi_createdir($txtdir);
							if($fromrows[$k]['type']==1){
								jieqi_writefile($txtdir.'/'.$newid.$jieqi_file_postfix['txt'], $chaptercontent);
								$lastvolume=$fromrows[$k]['title'];
								$lastvolumeid=$newid;
							}else{
								jieqi_writefile($txtdir.'/'.$newid.$jieqi_file_postfix['txt'], $chaptercontent);
								$lastchapter=$fromrows[$k]['title'];
								$lastchapterid=$newid;
								$size+=$chaptersize;
							}
							//处理图片附件
							if($attachnum>0 && is_object($attachs_handler)){
								$attachs_handler->db->query("UPDATE ".jieqi_dbprefix('article_attachs')." SET chapterid=".$newChapter->getVar('chapterid')." WHERE articleid=".$_REQUEST['toid']." AND chapterid=0");
								$attachdir = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article');
								if (!file_exists($attachdir)) jieqi_createdir($attachdir);
								$attachdir .= jieqi_getsubdir($newChapter->getVar('articleid'));
								if (!file_exists($attachdir)) jieqi_createdir($attachdir);
								$attachdir .= '/'.$newChapter->getVar('articleid');
								if (!file_exists($attachdir)) jieqi_createdir($attachdir);
								$attachdir .= '/'.$newChapter->getVar('chapterid');
								if (!file_exists($attachdir)) jieqi_createdir($attachdir);
								//是否允许图片处理
								if($jieqiCollect['imagetranslate'] && function_exists("gd_info") && JIEQI_MODULE_VTYPE != '' && JIEQI_MODULE_VTYPE != 'Free') $canimagetrans=true;
								else  $canimagetrans=false;
								//是否加水印
								$make_image_water=false;
								if($jieqiCollect['addimagewater']==1){
									if(strpos($jieqiConfigs['article']['attachwimage'], '/')===false && strpos($jieqiConfigs['article']['attachwimage'], '\\')===false) $water_image_file = $GLOBALS['jieqiModules']['article']['path'].'/images/'.$jieqiConfigs['article']['attachwimage'];
									else $water_image_file = $jieqiConfigs['article']['attachwimage'];
									if(is_file($water_image_file)){
										$make_image_water = true;
										include_once(JIEQI_ROOT_PATH.'/lib/image/imagewater.php');
									}
								}

								foreach($infoary as $s=>$v){
									$imgattach_save_path=$attachdir.'/'.$infoary[$s]['attachid'].'.'.$infoary[$s]['postfix'];
									@jieqi_writefile($imgattach_save_path, $imagecontentary[$s]);

									$imagetype='';
									if (preg_match("/\.(jpg|jpeg|gif|png)$/i", $imgattach_save_path, $itmatches)) $imagetype = strtolower($itmatches[1]);
									if($imagetype == 'jpg') $imagetype='jpeg';


									//图片处理
									if($canimagetrans && !empty($imagetype)){
										$funname='imagecreatefrom'.$imagetype;
										$imageres=$funname($imgattach_save_path);
										$imagewidth=imagesx($imageres);  //图片宽度
										$imageheight=imagesy($imageres);  //图片高度
										if(!preg_match("/^#[a-f0-9]{6}$/i", $jieqiCollect['imagebgcolor'], $tmpmatches)){
											//自动判断背景色
											$tmpary=array();
											$tmpvar=imagecolorat($imageres, 1, 1);
											$tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar]+1 : 1;

											$tmpvar=imagecolorat($imageres, 1, $imageheight-1);
											$tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar]+1 : 1;

											$tmpvar=imagecolorat($imageres, $imagewidth-1, 1);
											$tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar]+1 : 1;

											$tmpvar=imagecolorat($imageres, $imagewidth-1, $imageheight-1);
											$tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar]+1 : 1;

											$tmpvar=imagecolorat($imageres, 1, floor($imageheight / 2));
											$tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar]+1 : 1;

											$tmpvar=imagecolorat($imageres, $imagewidth-1, floor($imageheight / 2));
											$tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar]+1 : 1;

											arsort($tmpary);
											reset($tmpary);
											$imagebgcolor=key($tmpary);
										}else{
											$imagebgcolor=imagecolorclosest($imageres, hexdec(substr($jieqiCollect['imagebgcolor'], 1, 2)), hexdec(substr($jieqiCollect['imagebgcolor'], 3, 2)), hexdec(substr($jieqiCollect['imagebgcolor'], 1, 5)));
										}
										

										$filterwater=false;

										//去文字水印
										if(!empty($jieqiCollect['imageareaclean'])){
											$imageareaary=explode('|', $jieqiCollect['imageareaclean']);
											foreach($imageareaary as $area){
												$xyary=explode(',', $area);
												if(count($xyary)>=4){
													$x1=intval(trim($xyary[0]));
													if($x1<0) $x1=$imagewidth+$x1;
													$y1=intval(trim($xyary[1]));
													if($y1<0) $y1=$imageheight+$y1;
													$x2=intval(trim($xyary[2]));
													if($x2<=0) $x2=$imagewidth+$x2;
													$y2=intval(trim($xyary[3]));
													if($y2<=0) $y2=$imageheight+$y2;
													imagefilledrectangle($imageres, $x1, $y1, $x2, $y2, $imagebgcolor);
													$filterwater=true;
												}
											}
										}

										//去图片颜色水印
										if(!empty($jieqiCollect['imagecolorclean'])){
											$imagecolorary=explode('|', $jieqiCollect['imagecolorclean']);
											foreach($imagecolorary as $fcolor){
												$fcolor=trim($fcolor);
												if(preg_match("/^#[a-f0-9]{6}$/i", $fcolor, $tmpmatches)){
													$filtercolor = imagecolorexact($imageres, hexdec(substr($fcolor, 1, 2)), hexdec(substr($fcolor, 3, 2)), hexdec(substr($fcolor, 5, 2)));
													if($filtercolor>=0){
														$cindexary=imagecolorsforindex($imageres, $imagebgcolor);
														imagecolorset($imageres, $filtercolor, $cindexary['red'], $cindexary['green'], $cindexary['blue']);
														$filterwater=true;
													}
												}
											}
										}

										//保存去水印后图片
										if($filterwater){
											$funname='image'.$imagetype;
											$funname($imageres, $imgattach_save_path);
										}

										//图片加水印
										if($make_image_water && eregi("\.(gif|jpg|jpeg|png)$",$imgattach_save_path)){
											$img = new ImageWater();
											$img->save_image_file = $imgattach_save_path;
											$img->codepage = JIEQI_SYSTEM_CHARSET;
											$img->wm_image_pos = $jieqiConfigs['article']['attachwater'];
											$img->wm_image_name = $water_image_file;
											$img->wm_image_transition  = $jieqiConfigs['article']['attachwtrans'];
											$img->jpeg_quality = $jieqiConfigs['article']['attachwquality'];
											$img->create($imgattach_save_path);
											unset($img);
										}
									}
									@chmod($imgattach_save_path, 0777);
								}
							}
						}
						unset($newChapter);

						//更新文章信息(每采集一个章节就更新主要是为了怕采集中断时对应不上)
						$article->setVar('chapters', $maxchapterorder+1);
						$article->setVar('size', $size);
						$article->setVar('lastupdate', $jieqi_collect_time);
						$article->setVar('lastchapter', $lastchapter);
						$article->setVar('lastchapterid', $lastchapterid);
						$article->setVar('lastvolume', $lastvolume);
						$article->setVar('lastvolumeid', $lastvolumeid);
						$article_handler->insert($article);
						$lastchapterorder=$q+1;
						$maxchapterorder++;
						//本站章节数组加一项
						for($n=$tonum; $n>$q; $n--) $torows[$n]=$torows[$n-1];
						$torows[$q]['title']=$fromrows[$k]['title'];
						$torows[$q]['type']=$fromrows[$k]['type'];
						$tonum++;
						$q++;
						echo $c.'.'.jieqi_htmlstr($fromrows[$k]['title']).' ';
						ob_flush();
						flush();
					}
					$k++;
					$c++;
				}
				//全部章节采集完之后
				//根据最新章节重新搜索最新卷
				$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['toid']));
				$criteria->add(new Criteria('chapterorder', $lastchapterorder, '<'));
				$criteria->add(new Criteria('chaptertype', 1, '='));
				$criteria->setSort('chapterorder');
				$criteria->setOrder('DESC');
				$criteria->setLimit(1);
				$chapter_handler->queryObjects($criteria, true);
				$tmpchapter=$chapter_handler->getObject();
				if(is_object($tmpchapter)){
					$article->setVar('lastvolume', $tmpchapter->getVar('chaptername', 'n'));
					$article->setVar('lastvolumeid', $tmpchapter->getVar('chapterid', 'n'));
				}else{
					$article->setVar('lastvolume', '');
					$article->setVar('lastvolumeid', 0);
				}
				unset($tmpchapter);
				unset($criteria);
				//更新参数
				$setting=unserialize($article->getVar('setting', 'n'));
				if(!is_array($setting)) $setting=array();
				$setting['fromsite']=$_REQUEST['siteid'];
				$setting['fromarticle']=$_REQUEST['fromid'];
				$article->setVar('setting', serialize($setting));
				$article_handler->insert($article);

				$k = $c - 1;
				//增加章节积分
				/*
				if(!empty($jieqiConfigs['article']['scorechapter'])){
				include_once(JIEQI_ROOT_PATH.'/class/users.php');
				$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
				$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scorechapter']*$k, true);
				}
				*/
				echo $jieqiLang['article']['chapter_collect_success'];
				ob_flush();
				flush();

				//生成html和zip和全文阅读
				$jieqiConfigs['article']['makehtml']=$old_makehtml;
				if($old_makehtml==1){
					echo $jieqiLang['article']['collect_create_readfile'];
					ob_flush();
					flush();
					include_once($GLOBALS['jieqiModules']['article']['path'].'/include/repack.php');
					article_repack($_REQUEST['toid'], array('makeopf'=>1, 'makehtml'=>$old_makehtml, 'makezip'=>$jieqiConfigs['article']['makezip'], 'makefull'=>$jieqiConfigs['article']['makefull'], 'maketxtfull'=>$jieqiConfigs['article']['maketxtfull'], 'makeumd'=>$jieqiConfigs['article']['makeumd'], 'makejar'=>$jieqiConfigs['article']['makejar']), 1);
				}else{
					include_once($GLOBALS['jieqiModules']['article']['path'].'/include/repack.php');
					article_repack($_REQUEST['toid'], array('makeopf'=>1, 'makehtml'=>0, 'makezip'=>0, 'makefull'=>0, 'maketxtfull'=>0, 'makeumd'=>0, 'makejar'=>0), 1);
				}
				//采集完成
				$retflag=1;
			}else{
				//没有需要更新的
				if($retflag==0) $retflag=2;
			}
		}
	}
}else{
	$retflag=0; //无法采集
	jieqi_printfail($jieqiLang['article']['not_support_collectsite']);
}

?>