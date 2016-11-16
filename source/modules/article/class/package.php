<?php
/**
 * 文章打包类
 *
 * 生成html、zip、txt、umd、jar等格式
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: package.php 332 2009-02-23 09:15:08Z juny $
 */


//需要更新静态文章信息页面的打包类
include_once(JIEQI_ROOT_PATH.'/lib/xml/xml.php');

jieqi_getconfigs('article', 'configs');
if(!isset($jieqiConfigs['article']['packdbattach'])) $jieqiConfigs['article']['packdbattach']=0;
if(!$jieqiConfigs['article']['packdbattach'] && preg_match('/^(ftps?):\/\/([^:\/]+):([^:\/]*)@([0-9a-z\-\.]+)(:(\d+))?([0-9a-z_\-\/\.]*)/is', $jieqiConfigs['article']['attachdir'])) $jieqiConfigs['article']['packdbattach']=1;

if($jieqiConfigs['article']['packdbattach']){
	jieqi_includedb();
	$package_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
}

if(!empty($jieqiConfigs['article']['dynamicurl'])) define('ARTICLE_DYNAMIC_URL', $jieqiConfigs['article']['dynamicurl']);
else define('ARTICLE_DYNAMIC_URL', $GLOBALS['jieqiModules']['article']['url']);

$article_dynamic_rooturl = ARTICLE_DYNAMIC_URL;
if(strpos($article_dynamic_rooturl,'/modules') > 0) $article_dynamic_rooturl=substr($article_dynamic_rooturl,0,strpos($article_dynamic_rooturl,'/modules'));
define('ARTICLE_DYNAMIC_ROOTURL', $article_dynamic_rooturl);

if(!empty($jieqiConfigs['article']['staticurl'])) define('ARTICLE_STATIC_URL', $jieqiConfigs['article']['staticurl']);
else define('ARTICLE_STATIC_URL', $GLOBALS['jieqiModules']['article']['url']);

//文章打包类
class JieqiPackage extends JieqiObject
{
	var $id=0;
	var $xml=NULL;
	var $metas=array();
	var $chapters=array();
	var $isload=false;
	var $nowid=0;
	var $preid=0;
	var $nextid=0;
	//构建函数
	function JieqiPackage($id=0)
	{
		$this->JieqiObject();
		$this->id=intval($id);
		$this->isload=false;
	}
	//设置序号
	function setId($id=0)
	{
		$this->id=intval($id);
	}
	//取得序号
	function getId()
	{
		return $this->id;
	}

	//取得章节序号
	function getCid($href){
		return intval($href);
		//return substr($href, 0, strlen($href)-strlen($jieqi_file_postfix['txt']));
	}

	//取得文件保存目录
	function getDir($dirtype='txtdir', $idasdir=true, $automake=true)
	{
		global $jieqiConfigs;
		$retdir=jieqi_uploadpath($jieqiConfigs['article'][$dirtype], 'article');
		if ($automake && !file_exists($retdir)) jieqi_createdir($retdir);
		$retdir .= jieqi_getsubdir($this->id);
		if ($automake && !file_exists($retdir)) jieqi_createdir($retdir);
		if($idasdir){
			$retdir .= '/'.$this->id;
			if ($automake && !file_exists($retdir)) jieqi_createdir($retdir);
		}
		return $retdir;
	}


	//初始化opf
	function initPackage($infoary=array(), $save=true)
	{
		foreach($infoary as $k=>$v){
			$this->metas['dc:'.ucfirst($k)]=$v;
		}
		$this->metas['dc:Date']=date(JIEQI_DATE_FORMAT);
		$this->metas['dc:Type']='Text';
		$this->metas['dc:Format']='text';
		$this->metas['dc:Language']='ZH';

		$this->chapters=array();
		if($save) $this->createOPF($save);
	}

	//编辑opf
	function editPackage($infoary=array(), $save=true)
	{
		if(!$this->isload) $this->loadOPF();
		$tmpstr=$this->metas['dc:Title'];
		foreach($infoary as $k=>$v){
			$this->metas['dc:'.ucfirst($k)]=$v;
		}
		$this->metas['dc:Date']=date(JIEQI_DATE_FORMAT);
		$this->metas['dc:Type']='Text';
		$this->metas['dc:Format']='text';
		$this->metas['dc:Language']='ZH';

		$this->createOPF($save);
		$this->makeIndex();
		//标题不同，重新生成网页
		if($tmpstr != $infoary['title']){
			for($i=1; $i<=count($this->chapters); $i++){
				$this->makeHtml($i);
			}
		}
	}

	//创建xml格式的OEB package index.opf
	function createOPF($save=true)
	{
		$this->xml=new XML();
		$this->xml->encoding='ISO-8859-1';
		$this->xml->xmlDecl = '<?xml version="1.0" encoding="ISO-8859-1"?>';
		$package=$this->xml->createElement('package');
		$package->attributes['unique-identifier']=ARTICLE_DYNAMIC_URL.'/-'.$this->id;
		$this->xml->appendChild($package);

		//基本信息
		$metadata=$this->xml->createElement('metadata');
		$package->appendChild($metadata);
		$dcmetadata=$this->xml->createElement('dc-metadata');
		$metadata->appendChild($dcmetadata);
		$i=0;
		foreach($this->metas as $key=>$val){
			${'meta'.$i}=$this->xml->createElement($key);
			${'meta'.$i}->appendChild($this->xml->createTextNode($val));
			$dcmetadata->appendChild(${'meta'.$i});
			$i++;
		}
		//章节列表
		$manifest=$this->xml->createElement('manifest');
		$package->appendChild($manifest);

		//章节排序
		$spine=$this->xml->createElement('spine');
		$package->appendChild($spine);

		$i=0;
		foreach($this->chapters as $val){
			${'item'.$i}=$this->xml->createElement('item');
			${'item'.$i}->attributes=$val;
			$manifest->appendChild(${'item'.$i});
			${'itemref'.$i}=$this->xml->createElement('itemref');
			${'itemref'.$i}->attributes['idref']=$val['id'];
			$spine->appendChild(${'itemref'.$i});
			$i++;
		}

		if($save) $this->saveOPF();

	}
	//保存opf文件
	function saveOPF()
	{
		global $jieqiConfigs;
		global $jieqi_file_postfix;
		$opfdir=$this->getDir('opfdir');
		jieqi_writefile($opfdir.'/index'.$jieqi_file_postfix['opf'], $this->xml->toString());
	}
	//载入opf文件
	function loadOPF()
	{
		global $jieqiConfigs;
		global $jieqi_file_postfix;
		$opfdir=$this->getDir('opfdir', true, false);
		if(!file_exists($opfdir.'/index'.$jieqi_file_postfix['opf'])) return false;
		else{
			if (!is_object($this->xml)){
				$this->xml=new XML();
			}
			$this->xml->load($opfdir.'/index'.$jieqi_file_postfix['opf']);
			$this->metas=array();
			$tmpary=explode('-', $this->xml->firstChild->attributes['unique-identifier']);
			$tmpvar=count($tmpary);
			if($tmpvar >= 3 && is_numeric($tmpary[$tmpvar-1]) && is_numeric($tmpary[$tmpvar-2])) $this->metas['dc:Sortid']=$tmpary[$tmpvar-1];
			$meta=$this->xml->firstChild->firstChild->firstChild->firstChild;
			while ($meta) {
				$this->metas[$meta->nodeName]=$meta->firstChild->nodeValue;
				$meta = $meta->nextSibling;
			}
			unset($meta);

			$chapter=$this->xml->firstChild->firstChild->nextSibling->firstChild;
			$this->chapters=array();
			$i=0;
			while ($chapter) {
				$this->chapters[$i]=$chapter->attributes;
				$chapter = $chapter->nextSibling;
				$i++;
			}
			unset($chapter);
			$this->isload=true;
			return true;
		}
	}

	//显示一个章节
	function showChapter($cid)
	{
		global $jieqiConfigs;
		global $jieqi_file_postfix;
		$i=0;
		$num=count($this->chapters);
		while($i<$num){
			$tmpvar=$this->getCid($this->chapters[$i]['href']);
			if($tmpvar==$cid){
				$this->makeHtml($i+1, true, true);
				return true;
			}
			$i++;
		}
		return false;
	}

	//生成一个章节的html
	function makeHtml($nowid, $dynamic=false, $show=false, $filter=false)
	{
		global $jieqiConfigs;
		global $jieqiSort;
		global $jieqiTpl;
		global $jieqi_file_postfix;
		if(!isset($jieqiSort['article'])) jieqi_getconfigs('article', 'sort');
		if($nowid<=0) return false;
		$chaptercount=count($this->chapters);
		if($nowid>$chaptercount) return false;

		if(!in_array($jieqiConfigs['article']['htmlfile'], array('.html', '.htm', '.shtml'))) $jieqiConfigs['article']['htmlfile'] = '.html';

		$chapter=jieqi_htmlstr($this->chapters[$nowid-1]['id']);
		$void=$nowid-2;
		$volume='';
		while($void>=0 && $this->chapters[$void]['content-type']!='volume') $void--;
		if($void>=0) $volume=jieqi_htmlstr($this->chapters[$void]['id']);
		$preid=$nowid-2;
		while($preid>=0 && $this->chapters[$preid]['content-type']=='volume') $preid--;
		$preid++;
		$nextid=$nowid;
		while($nextid<$chaptercount && $this->chapters[$nextid]['content-type']=='volume') $nextid++;
		if($nextid>=$chaptercount) $nextid=0;
		else $nextid++;
		if(!is_object($jieqiTpl)){
			include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
			$jieqiTpl =& JieqiTpl::getInstance();
		}
		//文件头变量赋值
		$jieqiTpl->assign('dynamic_url', ARTICLE_DYNAMIC_URL);
		$jieqiTpl->assign('static_url', ARTICLE_STATIC_URL);
		$jieqiTpl->assign('article_title', jieqi_htmlstr($this->metas['dc:Title']));
		$jieqiTpl->assign('jieqi_title',$volume.' '.$chapter);
		$jieqiTpl->assign('jieqi_volume',$volume);
		$jieqiTpl->assign('jieqi_chapter',$chapter);

		//其他序号及名称
		$jieqiTpl->assign('sortid', intval($this->metas['dc:Sortid']));
		if(!empty($jieqiSort['article'][$this->metas['dc:Sortid']]['caption'])) $jieqiTpl->assign('sortname', $jieqiSort['article'][$this->metas['dc:Sortid']]['caption']);
		$jieqiTpl->assign('authorid',intval($this->metas['dc:Creatorid']));
		$jieqiTpl->assign('author', jieqi_htmlstr($this->metas['dc:Creator']));
		$jieqiTpl->assign('fullflag', intval($this->metas['dc:Fullflag']));
		$articletype=intval($this->metas['dc:Articletype']);
		$jieqiTpl->assign('articletype', $articletype);
		if(($articletype & 1)>0) $jieqiTpl->assign('hasebook', 1);
		else $jieqiTpl->assign('hasebook', 0);
		if(($articletype & 2)>0) $jieqiTpl->assign('hasobook', 1);
		else $jieqiTpl->assign('hasobook', 0);
		if(($articletype & 4)>0) $jieqiTpl->assign('hastbook', 1);
		else $jieqiTpl->assign('hastbook', 0);

		$jieqiTpl->assign('articleid', $this->id);
		$chapterid=$this->getCid($this->chapters[$nowid-1]['href']);
		$jieqiTpl->assign('chapterid', $chapterid);
		$jieqiTpl->assign('new_url', JIEQI_LOCAL_URL);
		//全文阅读
		$jieqiTpl->assign('url_fullpage', jieqi_uploadurl($jieqiConfigs['article']['fulldir'], $jieqiConfigs['article']['fullurl'], 'article', ARTICLE_STATIC_URL).jieqi_getsubdir($this->id).'/'.$this->id.$jieqiConfigs['article']['htmlfile']);
		//打包下载
		$jieqiTpl->assign('url_download', jieqi_uploadurl($jieqiConfigs['article']['zipdir'], $jieqiConfigs['article']['zipurl'], 'article', ARTICLE_STATIC_URL).jieqi_getsubdir($this->id).'/'.$this->id.$jieqi_file_postfix['zip']);
		//自己页面
		if($show) $jieqiTpl->assign('url_thispage', ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id.'&cid='.$chapterid);
		else $jieqiTpl->assign('url_thispage', $this->getDir('htmldir').'/'.$chapterid.$jieqiConfigs['article']['htmlfile']);

		$txtdir=$this->getDir('txtdir', true, false);
		//内容赋值
		include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
		$ts=TextConvert::getInstance('TextConvert');

		$tmpvar=jieqi_readfile($txtdir.'/'.$this->chapters[$nowid-1]['href']);
		//文字过滤
		if($filter && !empty($jieqiConfigs['article']['hidearticlewords'])){
			$articlewordssplit = (strlen($jieqiConfigs['article']['articlewordssplit'])==0) ? ' ' : $jieqiConfigs['article']['articlewordssplit'];
			$filterary=explode($articlewordssplit, $jieqiConfigs['article']['hidearticlewords']);
			$tmpvar=str_replace($filterary, '', $tmpvar);
		}
		//网址改成可以点击的
		$tmpvar=$ts->makeClickable(jieqi_htmlstr($tmpvar));
		//加入文字水印
		if(!empty($jieqiConfigs['article']['textwatermark']) && JIEQI_MODULE_VTYPE != '' && JIEQI_MODULE_VTYPE != 'Free'){
			$contentary=explode('<br />
<br />', $tmpvar);
			$tmpvar='';
			foreach($contentary as $v){
				if(empty($tmpvar)) $tmpvar.=$v;
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
					$textwatermark=str_replace('<{$randtext}>', $randtext, $jieqiConfigs['article']['textwatermark']);
					$tmpvar.='<br />
'.$textwatermark.'<br />'.$v;
				}
			}
		}
		$attachurl = jieqi_uploadurl($jieqiConfigs['article']['attachdir'], $jieqiConfigs['article']['attachurl'], 'article').jieqi_getsubdir($this->id).'/'.$this->id.'/'.$chapterid;
		if(!$jieqiConfigs['article']['packdbattach']){
			//检查附件(检查文件是否存在)
			$attachdir = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article').jieqi_getsubdir($this->id).'/'.$this->id.'/'.$chapterid;

			if(is_dir($attachdir)){
				$attachimage='';
				$attachfile='';
				$files=array();
				$dirhandle = @opendir($attachdir);
				while ($file = @readdir($dirhandle)) {
					if($file != '.' && $file != '..'){
						$files[] = $file;
					}
				}
				@closedir($dirhandle);
				sort($files);
				$image_code=$jieqiConfigs['article']['pageimagecode'];

				if(empty($image_code) || !preg_match('/\<img/is', $image_code))	$image_code='<div class="divimage"><img src="<{$imageurl}>" border="0" class="imagecontent"></div>';
				foreach($files as $file){
					if (is_file($attachdir.'/'.$file)){
						$url=$attachurl.'/'.$file;
						if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$file)){
							$attachimage.=str_replace('<{$imageurl}>', $url, $image_code);
						}else{
							$attachfile.='<strong>file:</strong><a href="'.$url.'">'.$url.'</a>('.ceil(filesize($attachdir.'/'.$file)/1024).'K)<br /><br />';
						}
					}
				}
				if(!empty($attachimage) || !empty($attachfile)){
					if(!empty($tmpvar)) $tmpvar.='<br /><br />';
					$tmpvar.=$attachimage.$attachfile;
				}
			}
		}else{
			//检查附件-从数据库判断是不是有附件
			global $package_query;
			$sql="SELECT attachment FROM ".jieqi_dbprefix('article_chapter')." WHERE chapterid=".intval($chapterid);
			$res=$package_query->execute($sql);
			$row=$package_query->db->fetchArray($res);
			$attachary=array();
			if(!empty($row['attachment'])){
				$attachary=unserialize($row['attachment']);
			}
			if(is_array($attachary) && count($attachary)>0){
				$attachimage='';
				$attachfile='';
				$image_code=$jieqiConfigs['article']['pageimagecode'];
				if(empty($image_code) || !preg_match('/\<img/is', $image_code))	$image_code='<div class="divimage"><img src="<{$imageurl}>" border="0" class="imagecontent"></div>';
				foreach($attachary as $attachvar){
					$url=$attachurl.'/'.$attachvar['attachid'].'.'.$attachvar['postfix'];
					if($attachvar['class']=='image'){
						$attachimage.=str_replace('<{$imageurl}>', $url, $image_code);
					}else{
						$attachfile.='<strong>file:</strong><a href="'.$url.'">'.$url.'</a>('.ceil($attachvar['size']/1024).'K)<br /><br />';
					}
				}
				if(!empty($attachimage) || !empty($attachfile)){
					if(!empty($tmpvar)) $tmpvar.='<br /><br />';
					$tmpvar.=$attachimage.$attachfile;
				}
			}
		}

		$jieqiTpl->assign('jieqi_content',$tmpvar);

		if($preid>0){
			$tmpvar=$this->getCid($this->chapters[$preid-1]['href']);
			if($dynamic){
				$tmpurl=ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id.'&cid='.$tmpvar;
			}else{
				$tmpurl=$tmpvar.$jieqiConfigs['article']['htmlfile'];
			}
			$jieqiTpl->assign('preview_link','<a href="'.$tmpurl.'">上一页</a>');
			$preview_page=$tmpurl;
			$jieqiTpl->assign('preview_page',$tmpurl);
			$jieqiTpl->assign('first_page',0);
		}else{
			$jieqiTpl->assign('preview_link','上一页');
			if($dynamic){
				$tmpurl=ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id;
			}else{
				$tmpurl='index'.$jieqiConfigs['article']['htmlfile'];
			}
			$preview_page=$tmpurl;
			$jieqiTpl->assign('preview_page',$tmpurl);
			$jieqiTpl->assign('first_page',1);
		}

		if($dynamic){
			$tmpurl=ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id;
		}else{
			$tmpurl='index'.$jieqiConfigs['article']['htmlfile'];
		}

		$jieqiTpl->assign('index_link','<a href="'.$tmpurl.'">返回目录</a>');
		$index_page=$tmpurl;
		$jieqiTpl->assign('index_page',$tmpurl);
		if($nextid>0){
			$tmpvar=$this->getCid($this->chapters[$nextid-1]['href']);
			if($dynamic){
				$tmpurl=ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id.'&cid='.$tmpvar;
			}else{
				$tmpurl=$tmpvar.$jieqiConfigs['article']['htmlfile'];
			}
			$jieqiTpl->assign('next_link','<a href="'.$tmpurl.'">下一页</a>');
			$next_page=$tmpurl;
			$jieqiTpl->assign('next_page',$tmpurl);
			$jieqiTpl->assign('last_page',0);
		}else{
			$jieqiTpl->assign('next_link','下一页');
			/*
			if($dynamic){
			$tmpurl=ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id;
			}else{
			$tmpurl='index'.$jieqiConfigs['article']['htmlfile'];
			}
			*/
			$tmpurl=ARTICLE_STATIC_URL.'/lastchapter.php?aid='.$this->id.'&dynamic='.intval($dynamic);
			$next_page=$tmpurl;
			$jieqiTpl->assign('next_page',$tmpurl);
			$jieqiTpl->assign('last_page',1);
		}

		$jieqiTpl->setCaching(0);
		//页面跳转
		$jieqiTpl->assign('preview_page', $preview_page);
		$jieqiTpl->assign('next_page', $next_page);
		$jieqiTpl->assign('index_page', $index_page);
		$jieqiTpl->assign('article_id', $this->id);
		$jieqiTpl->assign('chapter_id', $chapterid);
		$jieqiTpl->assign('articlesubdir', jieqi_getsubdir($this->id));
		$jieqiTpl->assign('url_articleinfo', jieqi_geturl('article', 'article', $this->id, 'info'));
		$jieqiTpl->assign('url_bookroom', ARTICLE_DYNAMIC_URL.'/');

		if($show){
			$jieqiTpl->display($GLOBALS['jieqiModules']['article']['path'].'/templates/style.html');
		}else{
			$htmldir=$this->getDir('htmldir');
			$jieqiTpl->assign('jieqi_charset', JIEQI_SYSTEM_CHARSET);
			jieqi_writefile($htmldir.'/'.$chapterid.$jieqiConfigs['article']['htmlfile'], $jieqiTpl->fetch($GLOBALS['jieqiModules']['article']['path'].'/templates/style.html'));
		}
	}

	//显示html目录
	function showIndex()
	{
		$this->makeIndex(true,true);
	}

	//生成html目录
	function makeIndex($dynamic=false, $show=false)
	{
		global $jieqiConfigs;
		global $jieqiSort;
		global $jieqiTpl;
		global $jieqi_file_postfix;
		if(!isset($jieqiSort['article'])) jieqi_getconfigs('article', 'sort');
		if(!in_array($jieqiConfigs['article']['htmlfile'], array('.html', '.htm', '.shtml'))) $jieqiConfigs['article']['htmlfile'] = '.html';
		if(!is_object($jieqiTpl)){
			include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
			$jieqiTpl =& JieqiTpl::getInstance();
		}
		//生成index.html
		$articlename=jieqi_htmlstr($this->metas['dc:Title']);
		//文章序号及名称
		$jieqiTpl->assign('dynamic_url', ARTICLE_DYNAMIC_URL);
		$jieqiTpl->assign('static_url', ARTICLE_STATIC_URL);
		$jieqiTpl->assign('article_title',$articlename);
		$jieqiTpl->assign('sortid', intval($this->metas['dc:Sortid']));
		if(!empty($jieqiSort['article'][$this->metas['dc:Sortid']]['caption'])) $jieqiTpl->assign('sortname', $jieqiSort['article'][$this->metas['dc:Sortid']]['caption']);
		$jieqiTpl->assign('articleid', $this->id);
		$jieqiTpl->assign('chapterid', 0);
		$jieqiTpl->assign('authorid', intval($this->metas['dc:Creatorid']));
		$jieqiTpl->assign('author',jieqi_htmlstr($this->metas['dc:Creator']));
		$jieqiTpl->assign('fullflag', intval($this->metas['dc:Fullflag']));
		$jieqiTpl->assign('keywords', jieqi_htmlstr($this->metas['dc:Subject']));
		$jieqiTpl->assign('intro', jieqi_htmlstr($this->metas['dc:Description']));
		$jieqiTpl->assign('posterid', intval($this->metas['dc:Contributorid']));
		$jieqiTpl->assign('poster', jieqi_htmlstr($this->metas['dc:Contributor']));
		$jieqiTpl->assign('typeid', intval($this->metas['dc:Typeid']));
		$jieqiTpl->assign('permission', intval($this->metas['dc:Permission']));
		$jieqiTpl->assign('firstflag', intval($this->metas['dc:Firstflag']));
		$jieqiTpl->assign('imgflag', intval($this->metas['dc:Imgflag']));
		$jieqiTpl->assign('power', intval($this->metas['dc:Power']));
		$articletype=intval($this->metas['dc:Articletype']);
		$jieqiTpl->assign('articletype', $articletype);
		if(($articletype & 1)>0) $jieqiTpl->assign('hasebook', 1);
		else $jieqiTpl->assign('hasebook', 0);
		if(($articletype & 2)>0) $jieqiTpl->assign('hasobook', 1);
		else $jieqiTpl->assign('hasobook', 0);
		if(($articletype & 4)>0) $jieqiTpl->assign('hastbook', 1);
		else $jieqiTpl->assign('hastbook', 0);

		$jieqiTpl->assign('copy_info',JIEQI_META_COPYRIGHT);

		$indexrows=array();
		$i=0;
		$idx=0;
		if(isset($jieqiConfigs['article']['indexcols']) && $jieqiConfigs['article']['indexcols']>0) $cols=intval($jieqiConfigs['article']['indexcols']);
		else $cols=4;
		$this->preid=0; //前一章
		$this->nextid=0; //下一章
		$preview_page='';
		$next_page='';
		$lastvolume='';
		$lastchapter='';
		$lastchapterid=0;
		$txtdir=$this->getDir('txtdir', true, false);
		foreach($this->chapters as $k => $chapter){
			//分卷
			if($chapter['content-type']=='volume'){
				if($i>0) $idx++;
				$i=0;
				$indexrows[$idx]['ctype']='volume';
				$indexrows[$idx]['vurl']='';
				$indexrows[$idx]['vname']=$chapter['id'];
				$indexrows[$idx]['vid']=$this->getCid($chapter['href']);
				$lastvolume=$chapter['id'];
				$idx++;
			}else{
				$k=$k+1;
				if($k < $this->nowid) $this->preid=$k;
				elseif($k > $this->nowid && $this->nextid==0) $this->nextid=$k;
				$tmpvar=$this->getCid($chapter['href']);
				$i++;
				$indexrows[$idx]['ctype']='chapter';
				$indexrows[$idx]['cname'.$i]=$chapter['id'];
				$indexrows[$idx]['cid'.$i]=$tmpvar;

				if(true){
					//检查文本文件获得章节字数，更新时间和简介
					$txtfile=$txtdir.'/'.$chapter['href'];
					if(is_file($txtfile)){
						$indexrows[$idx]['time'.$i] = filemtime($txtfile);
						$indexrows[$idx]['size'.$i] = filesize($txtfile);
						$indexrows[$idx]['size_c'.$i] = ceil($indexrows[$idx]['size'.$i]/2);
						$indexrows[$idx]['intro'.$i] = htmlspecialchars(str_replace(array("\r","\n"),' ',jieqi_substr(jieqi_readfile($txtfile), 0, 200, '...')), ENT_QUOTES);
					}else{
						echo $txtfile;exit;
						$indexrows[$idx]['time'.$i] = 0;
						$indexrows[$idx]['size'.$i] = 0;
						$indexrows[$idx]['size_c'.$i] = 0;
						$indexrows[$idx]['intro'.$i] = 'no';
					}
				}

				$lastchapter=$chapter['id'];
				$lastchapterid=$tmpvar;
				if($dynamic){
					$indexrows[$idx]['curl'.$i]=ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id.'&cid='.$tmpvar;

					if(empty($next_page)) $next_page=ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id.'&cid='.$tmpvar;
					$preview_page=ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id.'&cid='.$tmpvar;
				}else{
					$indexrows[$idx]['curl'.$i]=$tmpvar.$jieqiConfigs['article']['htmlfile'];

					if(empty($next_page)) $next_page=$tmpvar.$jieqiConfigs['article']['htmlfile'];
					$preview_page=$tmpvar.$jieqiConfigs['article']['htmlfile'];
				}
				if($i==$cols){
					$idx++;
					$i=0;
				}
			}
		}
		$lastvolume=jieqi_htmlstr($lastvolume);
		$lastchapter=jieqi_htmlstr($lastchapter);
		$lastchapterid=intval($lastchapterid);

		if(!empty($lastvolume)) $lastchapter = $lastvolume.' '.$lastchapter;
		$jieqiTpl->assign('lastchapter', $lastchapter);
		$jieqiTpl->assign('lastchapterid', $lastchapterid);
		if($dynamic){
			$jieqiTpl->assign('url_lastchapter', ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id.'&cid='.$lastchapterid);
		}else{
			$jieqiTpl->assign('url_lastchapter', $lastchapterid.$jieqiConfigs['article']['htmlfile']);
		}

		//如果有关联电子书的处理
		if(file_exists(JIEQI_ROOT_PATH.'/files/obook/articlelink') && $jieqiConfigs['article']['obookindex']==1){
			$linkfile=JIEQI_ROOT_PATH.'/files/obook/articlelink'.jieqi_getsubdir($this->id).'/'.$this->id.'.php';
			if(file_exists($linkfile)){
				global $jieqiObookdata;
				include_once($linkfile);
				jieqi_getconfigs('obook', 'configs');
				$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['obook']['staticurl'];
				$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['obook']['dynamicurl'];
				if($i>0) $idx++;
				$i=0;
				$indexrows[$idx]['ctype']='volume';
				$indexrows[$idx]['vurl']='';
				$indexrows[$idx]['vname']='<span class="hottext">[VIP章节目录 | <a href="'.$GLOBALS['jieqiModules']['obook']['url'].'/obookinfo.php?aid='.$this->id.'" target="_blank">查看本书信息</a> | <a href="'.$GLOBALS['jieqiModules']['pay']['url'].'/buyegold.php" target="_blank">我的帐户充值</a>]</span>';
				$idx++;
				foreach($jieqiObookdata['ochapter'] as $chapter){
					if($chapter['display']==0){
						$i++;
						$indexrows[$idx]['ctype']='chapter';
						$indexrows[$idx]['cname'.$i]=jieqi_htmlstr($chapter['chaptername']);
						$indexrows[$idx]['curl'.$i]=$obook_static_url.'/reader.php?aid='.intval($jieqiObookdata['obook']['obookid']).'&cid='.intval($chapter['ochapterid']);
						if($i==$cols){
							$idx++;
							$i=0;
						}
					}
				}
			}
		}

		$jieqiTpl->assign_by_ref('indexrows', $indexrows);

		//页面跳转
		if($dynamic){
			$index_page=ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id;
		}else{
			$index_page='index'.$jieqiConfigs['article']['htmlfile'];
		}

		$jieqiTpl->assign('preview_page', $preview_page);
		$jieqiTpl->assign('next_page', $next_page);
		$jieqiTpl->assign('index_page', $index_page);
		$jieqiTpl->assign('article_id', $this->id);
		$jieqiTpl->assign('chapter_id', '0');
		$jieqiTpl->assign('new_url', JIEQI_LOCAL_URL);
		//全文阅读
		$jieqiTpl->assign('url_fullpage', jieqi_uploadurl($jieqiConfigs['article']['fulldir'], $jieqiConfigs['article']['fullurl'], 'article', ARTICLE_STATIC_URL).jieqi_getsubdir($this->id).'/'.$this->id.$jieqiConfigs['article']['htmlfile']);
		//打包下载
		$jieqiTpl->assign('url_download', jieqi_uploadurl($jieqiConfigs['article']['zipdir'], $jieqiConfigs['article']['zipurl'], 'article', ARTICLE_STATIC_URL).jieqi_getsubdir($this->id).'/'.$this->id.$jieqi_file_postfix['zip']);
		$jieqiTpl->assign('articlesubdir', jieqi_getsubdir($this->id));
		$jieqiTpl->assign('url_articleinfo', jieqi_geturl('article', 'article', $this->id, 'info'));
		$jieqiTpl->assign('url_bookroom', ARTICLE_DYNAMIC_URL.'/');

		//自己页面
		if($show) $jieqiTpl->assign('url_thispage', ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id);
		else $jieqiTpl->assign('url_thispage', $this->getDir('htmldir').'/index'.$jieqiConfigs['article']['htmlfile']);
		//<!--jieqi insert license check-->

		$jieqiTpl->setCaching(0);
		if($show){
			$jieqiTpl->display($GLOBALS['jieqiModules']['article']['path'].'/templates/index.html');
		}else{
			$htmldir=$this->getDir('htmldir');
			$jieqiTpl->assign('jieqi_charset', JIEQI_SYSTEM_CHARSET);
			jieqi_writefile($htmldir.'/index'.$jieqiConfigs['article']['htmlfile'], $jieqiTpl->fetch($GLOBALS['jieqiModules']['article']['path'].'/templates/index.html'));
		}

		//生成静态文章信息页 $jieqiConfigs['article']['staticinfo']
		//if(is_file($GLOBALS['jieqiModules']['article']['path'].'/include/staticmakeinfo.php') && is_file($GLOBALS['jieqiModules']['article']['path'].'/templates/staticinfo.html')){
		if($jieqiConfigs['article']['fakeinfo']==2){
			include_once($GLOBALS['jieqiModules']['article']['path'].'/include/staticmakeinfo.php');

			makestaticinfo($this->id);
			if(!empty($jieqiConfigs['article']['fakeprefix']))	$dirname=JIEQI_ROOT_PATH.'/'.$jieqiConfigs['article']['fakeprefix'].'info';
			else $dirname=JIEQI_ROOT_PATH.'/files/article/info';
			$dirname=$dirname.jieqi_getsubdir($this->id);
			$dirname.='/'.$this->id.'r.js';
			if(!file_exists($dirname)){
				include_once($GLOBALS['jieqiModules']['article']['path'].'/include/staticmakereview.php');
				makestaticreview($this->id);
			}
		}
	}

	//生成压缩文件
	function makezip(){
		if(JIEQI_MODULE_VTYPE == '' || JIEQI_MODULE_VTYPE == 'Free') return true;
		global $jieqiConfigs;
		global $jieqi_file_postfix;
		if (@function_exists('gzcompress')){
			$dir=$this->getDir('htmldir', true, false);

			//逐个加文件，因为里面有内容需要替换
			$filelist=array();
			if (file_exists($dir)){
				$dh = opendir($dir);
				while(false !== ($files = readdir($dh))){
					if (($files!=".") && ($files!="..") && (!is_dir($dir.'/'.$files))) $filelist[]=$dir.'/'.$files;
				}
				closedir($dh);
			}

			if (count($filelist)>0){
				include_once(JIEQI_ROOT_PATH.'/lib/compress/zip.php');
				$zip=new JieqiZip();
				$zipfilename=$this->getDir('zipdir', false).'/'.$this->id.$jieqi_file_postfix['zip'];
				if(!$zip->zipstart($zipfilename)) return false;
				foreach($filelist as $filename){
					if (is_file($filename)){
						$content = jieqi_readfile($filename);
						//把css和js替换成本地的
						$content = preg_replace ("/href=(\"|')([^'\"]*)page.css(\"|')/i", 'href="page.css"', $content, 1);
						$zip->zipadd(basename($filename), $content);
					}
				}
				//加入css和js
				$content = jieqi_readfile(JIEQI_ROOT_PATH.'/configs/article/page.css');
				$zip->zipadd('page.css', $content);
				$zip->setComment("Powered by JIEQI CMS\r\nhttp://www.jieqi.com");
				if($zip->zipend()) @chmod($zipfilename, 0777);;
			}
			return true;
		}else{
			return false;
		}
	}

	function showVolume($vid){
		$this->makefulltext(false, true, $vid);
	}

	//生成全文阅读
	function makefulltext($dynamic=false, $show=false, $vid=0){
		if(JIEQI_MODULE_VTYPE == '' || JIEQI_MODULE_VTYPE == 'Free') return true;
		global $jieqiConfigs;
		global $jieqiSort;
		global $jieqiTpl;
		global $jieqi_file_postfix;
		if(!isset($jieqiSort['article'])) jieqi_getconfigs('article', 'sort');
		if(!in_array($jieqiConfigs['article']['htmlfile'], array('.html', '.htm', '.shtml'))) $jieqiConfigs['article']['htmlfile'] = '.html';
		if(!is_object($jieqiTpl)){
			include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
			$jieqiTpl =& JieqiTpl::getInstance();
		}
		if(!$this->isload) $this->loadOPF();
		//生成index.html
		$articlename=jieqi_htmlstr($this->metas['dc:Title']);
		$jieqiTpl->assign('dynamic_url', ARTICLE_DYNAMIC_URL);
		$jieqiTpl->assign('static_url', ARTICLE_STATIC_URL);
		$jieqiTpl->assign('article_title',$articlename);
		$jieqiTpl->assign('book_title','<a name="articletitle">'.$articlename.'</a>');
		$jieqiTpl->assign('copy_info',JIEQI_META_COPYRIGHT);

		$jieqiTpl->assign('sortid', intval($this->metas['dc:Sortid']));
		if(!empty($jieqiSort['article'][$this->metas['dc:Sortid']]['caption'])) $jieqiTpl->assign('sortname', $jieqiSort['article'][$this->metas['dc:Sortid']]['caption']);
		$jieqiTpl->assign('articleid', $this->id);
		$jieqiTpl->assign('chapterid', 0);

		$jieqiTpl->assign('authorid', intval($this->metas['dc:Creatorid']));
		$jieqiTpl->assign('author',jieqi_htmlstr($this->metas['dc:Creator']));
		$jieqiTpl->assign('fullflag', intval($this->metas['dc:Fullflag']));
		$jieqiTpl->assign('keywords', jieqi_htmlstr($this->metas['dc:Subject']));
		$jieqiTpl->assign('intro', jieqi_htmlstr($this->metas['dc:Description']));
		$jieqiTpl->assign('posterid', intval($this->metas['dc:Contributorid']));
		$jieqiTpl->assign('poster', jieqi_htmlstr($this->metas['dc:Contributor']));
		$jieqiTpl->assign('typeid', intval($this->metas['dc:Typeid']));
		$jieqiTpl->assign('permission', intval($this->metas['dc:Permission']));
		$jieqiTpl->assign('firstflag', intval($this->metas['dc:Firstflag']));
		$jieqiTpl->assign('imgflag', intval($this->metas['dc:Imgflag']));
		$jieqiTpl->assign('power', intval($this->metas['dc:Power']));
		$articletype=intval($this->metas['dc:Articletype']);
		$jieqiTpl->assign('articletype', $articletype);
		if(($articletype & 1)>0) $jieqiTpl->assign('hasebook', 1);
		else $jieqiTpl->assign('hasebook', 0);
		if(($articletype & 2)>0) $jieqiTpl->assign('hasobook', 1);
		else $jieqiTpl->assign('hasobook', 0);
		if(($articletype & 4)>0) $jieqiTpl->assign('hastbook', 1);
		else $jieqiTpl->assign('hastbook', 0);

		$jieqiTpl->assign('new_url', JIEQI_LOCAL_URL);
		//目录阅读
		$jieqiTpl->assign('url_indexpage', jieqi_uploadurl($jieqiConfigs['article']['htmldir'], $jieqiConfigs['article']['htmlurl'], 'article', ARTICLE_STATIC_URL).jieqi_getsubdir($this->id).'/'.$this->id.'/index'.$jieqiConfigs['article']['htmlfile']);
		//全文阅读
		$jieqiTpl->assign('url_fullpage', jieqi_uploadurl($jieqiConfigs['article']['fulldir'], $jieqiConfigs['article']['fullurl'], 'article', ARTICLE_STATIC_URL).jieqi_getsubdir($this->id).'/'.$this->id.$jieqiConfigs['article']['htmlfile']);
		//打包下载
		$jieqiTpl->assign('url_download', jieqi_uploadurl($jieqiConfigs['article']['zipdir'], $jieqiConfigs['article']['zipurl'], 'article', ARTICLE_STATIC_URL).jieqi_getsubdir($this->id).'/'.$this->id.$jieqi_file_postfix['zip']);
		//自己页面
		if($show) $jieqiTpl->assign('url_thispage', ARTICLE_STATIC_URL.'/reader.php?aid='.$this->id);
		else $jieqiTpl->assign('url_thispage', $this->getDir('fulldir', false).'/'.$this->id.$jieqiConfigs['article']['htmlfile']);

		$indexrows=array();
		$idx=0;
		$i=0;
		if(isset($jieqiConfigs['article']['indexcols']) && $jieqiConfigs['article']['indexcols']>0) $cols=$jieqiConfigs['article']['indexcols'];
		else $cols=4;
		$chapters=array();
		$n=0;
		$txtdir=$this->getDir('txtdir', true, false);

		$vname='';
		if($vid > 0) $cstart=false;
		else $cstart=true;
		foreach($this->chapters as $k => $chapter){
			//分卷
			$chapterid=$this->getCid($this->chapters[$k]['href']);

			if($vid > 0){
				if($chapterid == $vid) $cstart=true;
				elseif($cstart == true && $chapter['content-type']=='volume') $cstart=false;
				if(!$cstart) continue;
			}

			if($chapter['content-type']=='volume'){
				if($i>0) $idx++;
				$i=0;
				$indexrows[$idx]['ctype']='volume';
				$indexrows[$idx]['vurl']='';
				$indexrows[$idx]['vname']=$chapter['id'];
				$idx++;
				if($chapter['id'] != $vname) $vname=$chapter['id'];
			}else{
				$i++;
				$indexrows[$idx]['ctype']='chapter';
				$indexrows[$idx]['cname'.$i]=$chapter['id'];
				$indexrows[$idx]['curl'.$i]='#'.$chapterid;
				if($i==$cols){
					$idx++;
					$i=0;
				}

				if(!empty($vname)) $tmpvar=$vname.' ';
				else $tmpvar='';
				$chapters[$n]['title']='<a name="'.$chapterid.'">'.$tmpvar.$chapter['id'].'</a>';

				if(file_exists($txtdir.'/'.$chapterid.$jieqi_file_postfix['txt'])){
					$chapters[$n]['content']=jieqi_htmlstr(jieqi_readfile($txtdir.'/'.$chapterid.$jieqi_file_postfix['txt']));
					//使链接可点击
					$patterns = array("/([^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+)/i", "/([^]_a-z0-9-=\"'\/])www\.([a-z0-9\-]+)\.([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+)/i", "/([^]_a-z0-9-=\"'\/])ftp\.([a-z0-9\-]+)\.([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+)/i", "/([^]_a-z0-9-=\"'\/:\.])([a-z0-9\-_\.]+?)@([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+)/i");
					$replacements = array("\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", "\\1<a href=\"http://www.\\2.\\3\" target=\"_blank\">www.\\2.\\3</a>", "\\1<a href=\"ftp://ftp.\\2.\\3\" target=\"_blank\">ftp.\\2.\\3</a>", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>");
					$chapters[$n]['content']=preg_replace($patterns, $replacements, $chapters[$n]['content']);
				}else{
					$chapters[$n]['content']='';
				}
				$attachurl = jieqi_uploadurl($jieqiConfigs['article']['attachdir'], $jieqiConfigs['article']['attachurl'], 'article').jieqi_getsubdir($this->id).'/'.$this->id.'/'.$chapterid;
				if(!$jieqiConfigs['article']['packdbattach']){
					//检查附件(从文件)
					$attachdir = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article').jieqi_getsubdir($this->id).'/'.$this->id.'/'.$chapterid;

					if(is_dir($attachdir)){
						$attachimage='';
						$attachfile='';
						$files=array();
						$dirhandle = @opendir($attachdir);
						while ($file = @readdir($dirhandle)) {
							if($file != '.' && $file != '..'){
								$files[]=$file;
							}
						}
						@closedir($dirhandle);
						sort($files);
						foreach($files as $file){
							if (is_file($attachdir.'/'.$file)){
								$url=$attachurl.'/'.$file;
								if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$file)){
									$attachimage.='<div class="divimage" id="'.$file.'" title="'.$url.'"><a style="cursor: pointer;" onclick="imgclickshow(\''.$file.'\', \''.$url.'\')">'.$url.'</a>('.ceil(filesize($attachdir.'/'.$file)/1024).'K)</div>';
								}else{
									$attachfile.='<strong>file:</strong><a href="'.$url.'">'.$url.'</a>('.ceil(filesize($attachdir.'/'.$file)/1024).'K)<br /><br />';
								}
							}
						}
						if(!empty($attachimage) || !empty($attachfile)){
							if(!empty($chapters[$n]['content'])) $chapters[$n]['content'].='<br /><br />';
							$chapters[$n]['content'].=$attachimage.$attachfile;
						}
					}
				}else{
					//检查附件，从数据库
					global $package_query;
					$sql="SELECT attachment FROM ".jieqi_dbprefix('article_chapter')." WHERE chapterid=".intval($chapterid);
					$res=$package_query->execute($sql);
					$row=$package_query->db->fetchArray($res);
					$attachary=array();
					if(!empty($row['attachment'])){
						$attachary=unserialize($row['attachment']);
					}

					if(is_array($attachary) && count($attachary)>0){
						$attachimage='';
						$attachfile='';

						foreach($attachary as $attachvar){
							$url=$attachurl.'/'.$attachvar['attachid'].'.'.$attachvar['postfix'];
							if($attachvar['class']=='image'){
								$attachimage.='<strong>image:</strong><a href="'.$url.'" target="_blank">'.$url.'</a>('.ceil($attachvar['size']/1024).'K)<br /><br />';
							}else{
								$attachfile.='<strong>file:</strong><a href="'.$url.'">'.$url.'</a>('.ceil($attachvar['size']/1024).'K)<br /><br />';
							}
						}

						if(!empty($attachimage) || !empty($attachfile)){
							if(!empty($chapters[$n]['content'])) $chapters[$n]['content'].='<br /><br />';
							$chapters[$n]['content'].=$attachimage.$attachfile;
						}
					}
				}

				$n++;
			}
		}
		$jieqiTpl->assign_by_ref('indexrows', $indexrows);
		$jieqiTpl->assign_by_ref('chapters', $chapters);
		$jieqiTpl->assign('articlesubdir', jieqi_getsubdir($this->id));
		$jieqiTpl->assign('url_articleinfo', jieqi_geturl('article', 'article', $this->id, 'info'));
		$jieqiTpl->assign('url_bookroom', ARTICLE_DYNAMIC_URL.'/');
		$jieqiTpl->setCaching(0);
		if($show){
			$jieqiTpl->display($GLOBALS['jieqiModules']['article']['path'].'/templates/fulltext.html');
		}else{
			$htmldir=$this->getDir('fulldir', false);
			$jieqiTpl->assign('jieqi_charset', JIEQI_SYSTEM_CHARSET);
			jieqi_writefile($htmldir.'/'.$this->id.$jieqiConfigs['article']['htmlfile'], $jieqiTpl->fetch($GLOBALS['jieqiModules']['article']['path'].'/templates/fulltext.html'));
		}
	}

	//生成txt全文
	function maketxtfull(){
		global $jieqiConfigs;
		global $jieqi_file_postfix;
		if(JIEQI_MODULE_VTYPE == '' || JIEQI_MODULE_VTYPE == 'Free') return true;
		$txtfulldir=$this->getDir('txtfulldir', false);
		$txtdir=$this->getDir('txtdir', true, false);
		$br="\r\n";
		$data = '';
		if(!empty($jieqiConfigs['article']['txtarticlehead'])) $data .= $jieqiConfigs['article']['txtarticlehead'].$br.$br;
		$data .= '<'.$this->metas['dc:Title'].'>'.$br;
		$volume='';
		foreach($this->chapters as $k => $chapter){
			if($chapter['content-type']=='volume'){
				$volume=$chapter['id'];
			}else{
				$data .= $br.$br.$volume.' '.$chapter['id'].$br.$br;
				$data .= jieqi_readfile($txtdir.'/'.$chapter['href']);
			}
		}
		if(!empty($jieqiConfigs['article']['txtarticlefoot'])) $data .= $br.$jieqiConfigs['article']['txtarticlefoot'];
		jieqi_writefile($txtfulldir.'/'.$this->id.$jieqi_file_postfix['txt'], $data);
	}


	//分卷生成umd
	function makeumd_volume($vk = 0){
		if(JIEQI_MODULE_VTYPE == '' || JIEQI_MODULE_VTYPE == 'Free') return true;
		if (!function_exists('gzcompress') || !function_exists('iconv')) return false;

		global $jieqiConfigs;
		global $jieqi_file_postfix;
		if(!isset($jieqiSort['article'])) jieqi_getconfigs('article', 'sort');
		include_once(JIEQI_ROOT_PATH.'/lib/compress/umd.php');
		$umddir=$this->getDir('umddir', true);
		$txtdir=$this->getDir('txtdir', true, false);

		$vk = intval($vk);
		//$vk = 128; //每卷几K
		$vd = 1; //每卷程序占用几K
		$vc = 0.58; //压缩比例
		$vinfo = array();
		if(empty($vk) || $vk < $vd){
			$umd=new JieqiUmd();
			$umd->setcharset(strtoupper(JIEQI_SYSTEM_CHARSET));

			if(!empty($jieqiSort['article'][$this->metas['dc:Sortid']]['caption'])) $sort=$jieqiSort['article'][$this->metas['dc:Sortid']]['caption'];
			else $sort='';

			$umd->setinfo(array('id'=>$this->id, 'title'=>$this->metas['dc:Title'], 'author'=>$this->metas['dc:Creator'], 'sort'=>$sort, 'publisher'=>$this->metas['dc:Publisher'], 'corver'=>'')); //设置文章信息

			$volume='';
			$fromvolume = '';
			$fromchapter = '';
			$fromchapterid = 0;
			$tovolume = '';
			$tochapter = '';
			$tochapterid = 0;
			$chapters = 0;
			$volumes = 0;
			$firstflag = true;

			foreach($this->chapters as $k => $chapter){
				if($chapter['content-type']=='volume'){
					$volume=$chapter['id'];
					if($firstflag) $fromvolume = $volume;
					$tovolume = $volume;
					$volumes++;
				}else{
					$umd->addchapter($volume.' '.$chapter['id'],'<'.$volume.' '.$chapter['id'].'>'."\n".jieqi_readfile($txtdir.'/'.$chapter['href']));
					if($fromchapter == '') $fromchapter = $chapter['id'];
					$tochapter = $chapter['id'];
					$tmpint = strpos($chapter['href'], '.');
					if($tmpint > 0) $tmpcid = intval(trim(substr($chapter['href'], 0, $tmpint)));
					else $tmpcid = 0;
					if($fromchapterid == 0) $fromchapterid = $tmpcid;
					$tochapterid = $tmpcid;
					$chapters++;
				}
				$firstflag = false;
			}
			$umd->makeumd($umddir.'/'.$this->id.$jieqi_file_postfix['umd']);
			unset($umd);

			$vinfo['chapters'] = $chapters;
			$vinfo['volumes'] = $volumes;
			$vinfo['fromvolume'] = $fromvolume;
			$vinfo['fromchapter'] = $fromchapter;
			$vinfo['fromchapterid'] = $fromchapterid;
			$vinfo['tovolume'] = $tovolume;
			$vinfo['tochapter'] = $tochapter;
			$vinfo['tochapterid'] = $tochapterid;
			$vinfo['maketime'] = JIEQI_NOW_TIME;
			$vinfo['filesize'] = filesize($umddir.'/'.$this->id.$jieqi_file_postfix['umd']);
			include_once(JIEQI_ROOT_PATH.'/lib/xml/xmlarray.php');
			$xmlarray = new XMLArray();
			$xmldata = $xmlarray->array2xml($vinfo);
			jieqi_writefile($umddir.'/'.$this->id.'.xml', $xmldata);

		}elseif($vk > $vd){
			$vid = 1; //第几卷
			$vnew = true; //是否需要新增卷
			$vsize = 0;
			$volume='';
			foreach($this->chapters as $k => $chapter){
				if($chapter['content-type']=='volume'){
					$volume=$chapter['id'];
					$vinfo[$vid]['volumes']++;
				}else{
					$filedata = jieqi_readfile($txtdir.'/'.$chapter['href']);
					$vcdata = '<'.$volume.' '.$chapter['id'].'>'."\n";
					$filelen = strlen($filedata) + strlen($vcdata);
					if($vsize > 0 && (($vsize + $filelen) / 1024 * $vc) > ($vk - $vd)){
						$umd->makeumd($umddir.'/'.$this->id.'_'.$vk.'_'.$vid.$jieqi_file_postfix['umd']);
						unset($umd);
						$vinfo[$vid]['maketime'] = JIEQI_NOW_TIME;
						$vinfo[$vid]['filesize'] = filesize($umddir.'/'.$this->id.'_'.$vk.'_'.$vid.$jieqi_file_postfix['umd']);
						$vid++;
						$vsize = 0;
						$vnew = true;
					}
					if ($vnew) {
						$umd=new JieqiUmd();
						$umd->setcharset(strtoupper(JIEQI_SYSTEM_CHARSET));
						if(!empty($jieqiSort['article'][$this->metas['dc:Sortid']]['caption'])) $sort=$jieqiSort['article'][$this->metas['dc:Sortid']]['caption'];
						else $sort='';
						$umd->setinfo(array('id'=>$this->id, 'title'=>$this->metas['dc:Title'].'_'.$vk.'_'.$vid, 'author'=>$this->metas['dc:Creator'], 'sort'=>$sort, 'publisher'=>$this->metas['dc:Publisher'], 'corver'=>'')); //设置文章信息

						$vnew = false;
						$vinfo[$vid]['chapters'] = 0;
						$vinfo[$vid]['volumes'] = 0;
						$vinfo[$vid]['fromvolume'] = $volume;
						$vinfo[$vid]['fromchapter'] = $chapter['id'];
						$tmpint = strpos($chapter['href'], '.');
						if($tmpint > 0) $vinfo[$vid]['fromchapterid'] = intval(trim(substr($chapter['href'], 0, $tmpint)));
						else $vinfo[$vid]['fromchapterid'] = 0;
					}
					$umd->addchapter($volume.' '.$chapter['id'], $vcdata.$filedata);
					$vsize = $vsize + $filelen;
					$vinfo[$vid]['chapters']++;
					$vinfo[$vid]['tovolume'] = $volume;
					$vinfo[$vid]['tochapter'] = $chapter['id'];
					$tmpint = strpos($chapter['href'], '.');
					if($tmpint > 0) $vinfo[$vid]['tochapterid'] = intval(trim(substr($chapter['href'], 0, $tmpint)));
					else $vinfo[$vid]['tochapterid'] = 0;
				}
			}
			if(!$vnew){
				$umd->makeumd($umddir.'/'.$this->id.'_'.$vk.'_'.$vid.$jieqi_file_postfix['umd']);
				$vinfo[$vid]['tovolume'] = $volume;
				$vinfo[$vid]['tochapter'] = $chapter['id'];
				$tmpint = strpos($chapter['href'], '.');
				if($tmpint > 0) $vinfo[$vid]['tochapterid'] = intval(trim(substr($chapter['href'], 0, $tmpint)));
				else $vinfo[$vid]['tochapterid'] = 0;
				$vinfo[$vid]['maketime'] = JIEQI_NOW_TIME;
				$vinfo[$vid]['filesize'] = filesize($umddir.'/'.$this->id.'_'.$vk.'_'.$vid.$jieqi_file_postfix['umd']);
				unset($umd);
			}
			include_once(JIEQI_ROOT_PATH.'/lib/xml/xmlarray.php');
			$xmlarray = new XMLArray();
			$xmldata = $xmlarray->array2xml($vinfo);
			jieqi_writefile($umddir.'/'.$this->id.'_'.$vk.'.xml', $xmldata);
		}else{
			return false;
		}
	}

	//生成umd
	function makeumd(){
		global $jieqiConfigs;
		if(JIEQI_MODULE_VTYPE == '' || JIEQI_MODULE_VTYPE == 'Free') return true;
		if (!function_exists('gzcompress') || !function_exists('iconv')) return false;

		$jieqiConfigs['article']['makeumd'] = intval($jieqiConfigs['article']['makeumd']);
		if(empty($jieqiConfigs['article']['makeumd'])) $jieqiConfigs['article']['makeumd'] = 1;
		//全本umd
		if(($jieqiConfigs['article']['makeumd'] & 1) > 0) $this->makeumd_volume();
		//64K umd
		if(($jieqiConfigs['article']['makeumd'] & 2) > 0) $this->makeumd_volume(64);
		//128K umd
		if(($jieqiConfigs['article']['makeumd'] & 4) > 0) $this->makeumd_volume(128);
		//256K umd
		if(($jieqiConfigs['article']['makeumd'] & 8) > 0) $this->makeumd_volume(256);
		//512K umd
		if(($jieqiConfigs['article']['makeumd'] & 16) > 0) $this->makeumd_volume(512);
		//1024K umd
		if(($jieqiConfigs['article']['makeumd'] & 32) > 0) $this->makeumd_volume(1024);
	}

	//分卷生成jar($vk 每卷几K)
	function makejar_volume($vk = 0){
		if(JIEQI_MODULE_VTYPE == '' || JIEQI_MODULE_VTYPE == 'Free') return true;
		if (!function_exists('gzcompress') || !function_exists('iconv')) return false;
		global $jieqiConfigs;
		global $jieqi_file_postfix;
		include_once(JIEQI_ROOT_PATH.'/lib/compress/jar.php');
		$jardir=$this->getDir('jardir', true, true);
		$txtdir=$this->getDir('txtdir', true, false);

		$vk = intval($vk);
		$vd = intval(JIEQI_JAR_DEFAULT_SIZE); //每卷程序占用几K
		$vc = floatval(JIEQI_JAR_COMPRESS_RATE); //压缩比例
		$vinfo = array();
		if(empty($vk) || $vk < $vd){
			$jar=new JieqiJar();
			$jar->setcharset(strtoupper(JIEQI_SYSTEM_CHARSET));
			$jar->setinfo(array('id'=>$this->id, 'title'=>$this->metas['dc:Title'], 'author'=>$this->metas['dc:Creator'], 'publisher'=>$this->metas['dc:Publisher'], 'corver'=>'')); //设置文章信息

			$volume = '';
			$fromvolume = '';
			$fromchapter = '';
			$fromchapterid = 0;
			$tovolume = '';
			$tochapter = '';
			$tochapterid = 0;
			$chapters = 0;
			$volumes = 0;
			$firstflag = true;

			foreach($this->chapters as $k => $chapter){
				if($chapter['content-type']=='volume'){
					$volume = $chapter['id'];
					if($firstflag) $fromvolume = $volume;
					$tovolume = $volume;
					$volumes++;
				}else{
					$jar->addchapter($volume.' '.$chapter['id'],'<'.$volume.' '.$chapter['id'].'>'."\r\n".jieqi_readfile($txtdir.'/'.$chapter['href']));
					if($fromchapter == '') $fromchapter = $chapter['id'];
					$tochapter = $chapter['id'];
					$tmpint = strpos($chapter['href'], '.');
					if($tmpint > 0) $tmpcid = intval(trim(substr($chapter['href'], 0, $tmpint)));
					else $tmpcid = 0;
					if($fromchapterid == 0) $fromchapterid = $tmpcid;
					$tochapterid = $tmpcid;
					$chapters++;
				}
				$firstflag = false;
			}
			$jar->makejar($jardir.'/'.$this->id.$jieqi_file_postfix['jar']);
			unset($jar);

			$vinfo['chapters'] = $chapters;
			$vinfo['volumes'] = $volumes;
			$vinfo['fromvolume'] = $fromvolume;
			$vinfo['fromchapter'] = $fromchapter;
			$vinfo['fromchapterid'] = $fromchapterid;
			$vinfo['tovolume'] = $tovolume;
			$vinfo['tochapter'] = $tochapter;
			$vinfo['tochapterid'] = $tochapterid;
			$vinfo['maketime'] = JIEQI_NOW_TIME;
			$vinfo['filesize'] = filesize($jardir.'/'.$this->id.$jieqi_file_postfix['jar']);
			$vinfo['jadsize'] = filesize($jardir.'/'.$this->id.$jieqi_file_postfix['jad']);
			include_once(JIEQI_ROOT_PATH.'/lib/xml/xmlarray.php');
			$xmlarray = new XMLArray();
			$xmldata = $xmlarray->array2xml($vinfo);
			jieqi_writefile($jardir.'/'.$this->id.'.xml', $xmldata);

		}elseif($vk > $vd){
			$vid = 1; //第几卷
			$vnew = true; //是否需要新增卷
			$vsize = 0;
			$volume='';
			foreach($this->chapters as $k => $chapter){
				if($chapter['content-type']=='volume'){
					$volume=$chapter['id'];
					$vinfo[$vid]['volumes']++;
				}else{
					$filedata = jieqi_readfile($txtdir.'/'.$chapter['href']);
					$vcdata = '<'.$volume.' '.$chapter['id'].'>'."\r\n";
					$filelen = strlen($filedata) + strlen($vcdata);
					if($vsize > 0 && (($vsize + $filelen) / 1024 * $vc) > ($vk - $vd)){
						$jar->makejar($jardir.'/'.$this->id.'_'.$vk.'_'.$vid.$jieqi_file_postfix['jar']);
						unset($jar);
						$vinfo[$vid]['maketime'] = JIEQI_NOW_TIME;
						$vinfo[$vid]['filesize'] = filesize($jardir.'/'.$this->id.'_'.$vk.'_'.$vid.$jieqi_file_postfix['jar']);
						$vinfo[$vid]['jadsize'] = filesize($jardir.'/'.$this->id.'_'.$vk.'_'.$vid.$jieqi_file_postfix['jad']);
						$vid++;
						$vsize = 0;
						$vnew = true;
					}
					if ($vnew) {
						$jar=new JieqiJar();
						$jar->setcharset(strtoupper(JIEQI_SYSTEM_CHARSET));
						$jar->setinfo(array('id'=>$this->id, 'title'=>$this->metas['dc:Title'].'_'.$vk.'_'.$vid, 'author'=>$this->metas['dc:Creator'], 'publisher'=>$this->metas['dc:Publisher'], 'corver'=>'')); //设置文章信息
						$vnew = false;
						$vinfo[$vid]['chapters'] = 0;
						$vinfo[$vid]['volumes'] = 0;
						$vinfo[$vid]['fromvolume'] = $volume;
						$vinfo[$vid]['fromchapter'] = $chapter['id'];
						$tmpint = strpos($chapter['href'], '.');
						if($tmpint > 0) $vinfo[$vid]['fromchapterid'] = intval(trim(substr($chapter['href'], 0, $tmpint)));
						else $vinfo[$vid]['fromchapterid'] = 0;
					}
					$jar->addchapter($volume.' '.$chapter['id'], $vcdata.$filedata);
					$vsize = $vsize + $filelen;
					$vinfo[$vid]['chapters']++;
					$vinfo[$vid]['tovolume'] = $volume;
					$vinfo[$vid]['tochapter'] = $chapter['id'];
					$tmpint = strpos($chapter['href'], '.');
					if($tmpint > 0) $vinfo[$vid]['tochapterid'] = intval(trim(substr($chapter['href'], 0, $tmpint)));
					else $vinfo[$vid]['tochapterid'] = 0;
				}
			}
			if(!$vnew){
				$jar->makejar($jardir.'/'.$this->id.'_'.$vk.'_'.$vid.$jieqi_file_postfix['jar']);
				$vinfo[$vid]['tovolume'] = $volume;
				$vinfo[$vid]['tochapter'] = $chapter['id'];
				$tmpint = strpos($chapter['href'], '.');
				if($tmpint > 0) $vinfo[$vid]['tochapterid'] = intval(trim(substr($chapter['href'], 0, $tmpint)));
				else $vinfo[$vid]['tochapterid'] = 0;
				$vinfo[$vid]['maketime'] = JIEQI_NOW_TIME;
				$vinfo[$vid]['filesize'] = filesize($jardir.'/'.$this->id.'_'.$vk.'_'.$vid.$jieqi_file_postfix['jar']);
				$vinfo[$vid]['jadsize'] = filesize($jardir.'/'.$this->id.'_'.$vk.'_'.$vid.$jieqi_file_postfix['jad']);
				unset($jar);
			}
			include_once(JIEQI_ROOT_PATH.'/lib/xml/xmlarray.php');
			$xmlarray = new XMLArray();
			$xmldata = $xmlarray->array2xml($vinfo);
			jieqi_writefile($jardir.'/'.$this->id.'_'.$vk.'.xml', $xmldata);
		}else{
			return false;
		}
	}

	//生成jar
	function makejar(){
		global $jieqiConfigs;
		if(JIEQI_MODULE_VTYPE == '' || JIEQI_MODULE_VTYPE == 'Free') return true;
		if (!function_exists('gzcompress') || !function_exists('iconv')) return false;
		$jieqiConfigs['article']['makejar'] = intval($jieqiConfigs['article']['makejar']);
		if(empty($jieqiConfigs['article']['makejar'])) $jieqiConfigs['article']['makejar'] = 1;
		//全本jar
		if(($jieqiConfigs['article']['makejar'] & 1) > 0) $this->makejar_volume();
		//64K jar
		if(($jieqiConfigs['article']['makejar'] & 2) > 0) $this->makejar_volume(64);
		//128K jar
		if(($jieqiConfigs['article']['makejar'] & 4) > 0) $this->makejar_volume(128);
		//256K jar
		if(($jieqiConfigs['article']['makejar'] & 8) > 0) $this->makejar_volume(256);
		//512K jar
		if(($jieqiConfigs['article']['makejar'] & 16) > 0) $this->makejar_volume(512);
		//1024K jar
		if(($jieqiConfigs['article']['makejar'] & 32) > 0) $this->makejar_volume(1024);

	}

	//生成打包文件（异步）
	function makepack(){
		if(JIEQI_MODULE_VTYPE == '' || JIEQI_MODULE_VTYPE == 'Free') return true;
		global $jieqiConfigs;
		global $jieqiModules;
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$url=$article_static_url.'/makepack.php?key='.urlencode(md5(JIEQI_DB_USER.JIEQI_DB_PASS.JIEQI_DB_NAME)).'&id='.intval($this->id);
		$url=trim($url);
		if(strtolower(substr($url,0,7)) != 'http://') $url='http://'.$_SERVER['HTTP_HOST'].$url;
		$tmpurl = $url;

		//生成zip文件
		if($jieqiConfigs['article']['makezip']){
			$url.='&packflag[]=makezip';
		}
		//生成全文阅读
		if($jieqiConfigs['article']['makefull']){
			$url.='&packflag[]=makefull';
		}
		//生成txt全文
		if($jieqiConfigs['article']['maketxtfull']){
			$url.='&packflag[]=maketxtfull';
		}
		//生成umd
		if($jieqiConfigs['article']['makeumd']){
			$url.='&packflag[]=makeumd';
		}
		//生成jar
		if($jieqiConfigs['article']['makejar']){
			$url.='&packflag[]=makejar';
		}
		if($url == $tmpurl) return true;
		else return jieqi_socket_url($url);
	}

	//生成打包文件(同步)
	function makepack_dist(){
		global $jieqiConfigs;
		//生成zip文件
		if($jieqiConfigs['article']['makezip']){
			$this->makezip();
		}
		//生成全文阅读
		if($jieqiConfigs['article']['makefull']){
			$this->makefulltext();
		}
		//生成txt全文
		if($jieqiConfigs['article']['maketxtfull']){
			$this->maketxtfull();
		}
		//生成umd
		if($jieqiConfigs['article']['makeumd']){
			$this->makeumd();
		}
		//生成jar
		if($jieqiConfigs['article']['makejar']){
			$this->makejar();
		}
	}


	//增加章节
	function addChapter($chapterid, $name, &$content, $type, $volumeid)
	{
		global $jieqiConfigs;
		global $jieqi_file_postfix;
		$txtdir=$this->getDir('txtdir');
		jieqi_writefile($txtdir.'/'.$chapterid.$jieqi_file_postfix['txt'], $content);
		if(!$this->isload) $this->loadOPF();
		$articlename=jieqi_htmlstr($this->metas['dc:Title']);
		if($type) $contenttype='volume';
		else $contenttype='chapter';
		$chaptercount=count($this->chapters);
		//处理新章节的位置
		if($volumeid>0){
			if($volumeid>$chaptercount) $volumeid=$chaptercount+1;
			else{
				while($volumeid<=$chaptercount && $this->chapters[$volumeid-1]['content-type'] != 'volume') $volumeid++;
			}
		}else{
			$volumeid=$chaptercount+1;
		}

		if($volumeid>$chaptercount){
			//追加章节
			$this->chapters[]=array('id'=>$name, 'href'=>$chapterid.$jieqi_file_postfix['txt'], 'media-type'=>'text/html', 'content-type'=>$contenttype);
		}else{
			//插入章节
			for($i=$chaptercount; $i>=$volumeid; $i--){
				$this->chapters[$i]=$this->chapters[$i-1];
			}
			$this->chapters[$volumeid-1]=array('id'=>$name, 'href'=>$chapterid.$jieqi_file_postfix['txt'], 'media-type'=>'text/html', 'content-type'=>$contenttype);
		}
		$this->createOPF();
		//生成html
		if($jieqiConfigs['article']['makehtml']){
			//生成html目录
			$this->nowid=$volumeid;
			$this->makeIndex();
			//如果是章节而不是分卷则生成相应章节的html
			if(!$type){
				if($this->preid>0) $this->makeHtml($this->preid);
				if($this->nextid>0) $this->makeHtml($this->nextid);
				$this->makeHtml($this->nowid);
			}
		}
		if(!$type) $this->makepack();
	}

	//编辑章节
	function editChapter($name,&$content,$type,$chapterorder, $chapterid)
	{
		global $jieqiConfigs;
		global $jieqi_file_postfix;
		$txtdir=$this->getDir('txtdir');
		jieqi_writefile($txtdir.'/'.$chapterid.$jieqi_file_postfix['txt'], $content);
		$this->loadOPF();
		$articlename=jieqi_htmlstr($this->metas['dc:Title']);
		$contenttype=$this->chapters[$chapterorder-1]['content-type'];
		$this->chapters[$chapterorder-1]=array('id'=>$name, 'href'=>$chapterid.$jieqi_file_postfix['txt'], 'media-type'=>'text/html', 'content-type'=>$contenttype);
		$this->createOPF();
		//生成html
		if($jieqiConfigs['article']['makehtml']){
			//生成html目录
			$this->nowid=$chapterorder;
			$this->makeIndex();
			//如果是章节而不是分卷则生成相应章节的html
			if($contenttype=='chapter'){
				//章节
				$this->makeHtml($this->nowid);
			}
		}
		$this->makepack();
	}

	//删除章节
	function delChapter($chapterorder, $chapterid)
	{
		global $jieqiConfigs;
		global $jieqi_file_postfix;
		$txtdir=$this->getDir('txtdir', true, false);
		//删除文件
		if(file_exists($txtdir.'/'.$chapterid.$jieqi_file_postfix['txt'])) jieqi_delfile($txtdir.'/'.$chapterid.$jieqi_file_postfix['txt']);
		//删除附件
		$attachdir = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article').jieqi_getsubdir($this->id).'/'.$this->id.'/'.$chapterid;
		if(is_dir($attachdir)) jieqi_delfolder($attachdir);
		$this->loadOPF();
		$chaptercount=count($this->chapters);
		for($i=$chapterorder; $i<$chaptercount; $i++){
			$this->chapters[$i-1]=$this->chapters[$i];
		}
		array_pop($this->chapters);
		$this->createOPF();
		//生成html
		if($jieqiConfigs['article']['makehtml']){
			//生成html目录
			if($chapterorder>=$chaptercount) $chapterorder=$chaptercount-1;
			$this->nowid=$chapterorder;
			$this->makeIndex();
			$htmldir=$this->getDir('htmldir', true, false);
			if(file_exists($htmldir.'/'.$chapterid.$jieqiConfigs['article']['htmlfile'])) jieqi_delfile($htmldir.'/'.$chapterid.$jieqiConfigs['article']['htmlfile']);
			if($this->preid>0) $this->makeHtml($this->preid);
			if($this->chapters[$chapterorder-1]['content-type'] != 'volume') $this->makeHtml($chapterorder);
			else{
				if($this->nextid>0) $this->makeHtml($this->nextid);
			}
		}
		$this->makepack();
	}

	//章节排序
	function sortChapter($fromid, $toid)
	{
		global $jieqiConfigs;
		$this->loadOPF();
		$chaptercount=count($this->chapters);
		if($fromid<1 || $fromid>$chaptercount || $toid<0 || $toid>$chaptercount) return false;
		if($fromid==$toid || $fromid==$toid+1) return true;
		if($this->chapters[$fromid-1]['content-type']=='volume') $type=0;
		else $type=1;
		if($fromid<$toid){
			$tmpvar=$this->chapters[$fromid-1];
			for($i=$fromid; $i<$toid; $i++){
				$this->chapters[$i-1]=$this->chapters[$i];
			}
			$this->chapters[$toid-1]=$tmpvar;
		}else{
			$tmpvar=$this->chapters[$fromid-1];
			for($i=$fromid-1; $i>$toid; $i--){
				$this->chapters[$i]=$this->chapters[$i-1];
			}
			$this->chapters[$toid]=$tmpvar;
		}
		$this->createOPF();
		//生成html
		if($jieqiConfigs['article']['makehtml']){
			//生成html目录
			$this->makeIndex();
			//章节调换顺序需要重新生成html
			if($type){
				if($fromid>$toid) $toid++;
				$chgarray=array();
				if($this->chapters[$fromid-1]['content-type'] != 'volume'){
					$this->makeHtml($fromid);
					$chgarray[]=$fromid;
				}
				if($this->chapters[$toid-1]['content-type'] != 'volume'){
					$this->makeHtml($toid);
					$chgarray[]=$toid;
				}
				$preid=0;
				$nextid=0;
				for($i=1; $i<=$chaptercount; $i++){
					if($this->chapters[$i-1]['content-type'] != 'volume'){
						if($i < $fromid) $preid=$i;
						elseif($i > $fromid && $nextid==0) {
							$nextid=$i;
							$i=$chaptercount+1;
						}
					}
				}
				if($preid>0){
					if(!in_array($preid, $chgarray)){
						$this->makeHtml($preid);
						$chgarray[]=$preid;
					}
				}
				if($nextid>0){
					if(!in_array($nextid, $chgarray)){
						$this->makeHtml($nextid);
						$chgarray[]=$nextid;
					}
				}
				$preid=0;
				$nextid=0;
				for($i=1; $i<=$chaptercount; $i++){
					if($this->chapters[$i-1]['content-type'] != 'volume'){
						if($i < $toid) $preid=$i;
						elseif($i > $toid && $nextid==0) {
							$nextid=$i;
							$i=$chaptercount+1;
						}
					}
				}
				if($preid>0){
					if(!in_array($preid, $chgarray)){
						$this->makeHtml($preid);
						$chgarray[]=$preid;
					}
				}
				if($nextid>0){
					if(!in_array($nextid, $chgarray)){
						$this->makeHtml($nextid);
						$chgarray[]=$nextid;
					}
				}
			}
			$this->makepack();
		}
	}

	//删除
	function delete()
	{
		global $jieqiConfigs;
		global $jieqi_file_postfix;
		jieqi_delfolder($this->getDir('txtdir', true, false));
		if($jieqiConfigs['article']['makehtml']) jieqi_delfolder($this->getDir('htmldir', true, false));
		if($jieqiConfigs['article']['makezip']) jieqi_delfile($this->getDir('zipdir', false, false).'/'.$this->id.$jieqi_file_postfix['zip']);
		if($jieqiConfigs['article']['makefull']) jieqi_delfile($this->getDir('fulldir', false, false).'/'.$this->id.$jieqiConfigs['article']['htmlfile']);
		if($jieqiConfigs['article']['maketxtfull']) jieqi_delfile($this->getDir('txtfulldir', false, false).'/'.$this->id.$jieqi_file_postfix['txt']);
		//if($jieqiConfigs['article']['makeumd']) jieqi_delfile($this->getDir('umddir', false, false).'/'.$this->id.$jieqi_file_postfix['umd']);
		if($jieqiConfigs['article']['makeumd']) jieqi_delfolder($this->getDir('umddir', true, false));
		if($jieqiConfigs['article']['makejar']){
			jieqi_delfolder($this->getDir('jardir', true, false));
			jieqi_delfolder($this->getDir('jardir', true, false));
		}

		//删除附件
		$attachdir = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article').jieqi_getsubdir($this->id).'/'.$this->id;
		if(is_dir($attachdir)) jieqi_delfolder($attachdir);
	}

	//获得一个章节的内容
	function getContent($id){
		global $jieqiConfigs;
		global $jieqi_file_postfix;
		return jieqi_readfile($this->getDir('txtdir', true, false).'/'.$id.$jieqi_file_postfix['txt']);
	}

	//重新打包
	function repack(){
		if(!$this->isload) $this->loadOPF();
		$this->createOPF();
	}

}

function jieqi_socket_url($url){
	if(!function_exists('fsockopen')) return false;
	$method = "GET";
	$url_array = parse_url($url);
	$port = isset($url_array['port'])? $url_array['port'] : 80;
	$fp = fsockopen($url_array['host'], $port, $errno, $errstr, 30);
	if(!$fp) return false;
	$getPath = $url_array['path'];
	if(!empty($url_array['query'])) $getPath .= "?". $url_array['query'];
	$header = $method . " " . $getPath;
	$header .= " HTTP/1.1\r\n";
	$header .= "Host: ". $url_array['host'] . "\r\n"; //HTTP 1.1 Host域不能省略
	/*
	//以下头信息域可以省略
	$header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13 \r\n";
	$header .= "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,q=0.5 \r\n";
	$header .= "Accept-Language: en-us,en;q=0.5 ";
	$header .= "Accept-Encoding: gzip,deflate\r\n";
	*/
	$header .= "Connection:Close\r\n\r\n";
	fwrite($fp, $header);
	if(!feof($fp)) fgets($fp, 8);
	//while(!feof($fp)) echo fgets($fp, 128);
	fclose($fp);
	return true;
}

?>