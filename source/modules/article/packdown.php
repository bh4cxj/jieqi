<?php 
/**
 * 下载打包的文章
 *
 * 下载txt、zip、umd、jar之类格式文章
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: packdown.php 332 2009-02-23 09:15:08Z juny $
 */
define('JIEQI_MODULE_NAME', 'article');
define('JIEQI_USE_GZIP','0');
define('JIEQI_NOCONVERT_CHAR','1');
@ini_set('memory_limit', '32M');
require_once('../../global.php');
//jieqi_checklogin();
if(JIEQI_MODULE_VTYPE == '' || JIEQI_MODULE_VTYPE == 'Free') exit; //普及版不支持
if((empty($_REQUEST['id']) && empty($_REQUEST['name'])) || empty($_REQUEST['type'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('down', JIEQI_MODULE_NAME);
if(empty($_REQUEST['id']) && !empty($_REQUEST['name'])){
	include_once($jieqiModules['article']['path'].'/class/article.php');
	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
	$criteria = new CriteriaCompo(new Criteria('articlename', $_REQUEST['name'], '='));
	$article_handler->queryObjects($criteria);
	$article=$article_handler->getObject();
	if(is_object($article)) $_REQUEST['id'] = intval($article->getVar('articleid','n'));
	else jieqi_printfail($jieqiLang['article']['article_not_exists']);
}
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['fname']=trim($_REQUEST['fname']);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

$opf_file = jieqi_uploadpath($jieqiConfigs['article']['opfdir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/index'.$jieqi_file_postfix['opf'];
if(!is_file($opf_file)) jieqi_printfail($jieqiLang['article']['article_not_exists']);

//文章最后更新时间
$lastupdate = filemtime($opf_file);
//打包大小类型标志
$vsflags = array('0'=>1, '64'=>2, '128'=>4, '256'=>8, '512'=>16, '1024'=>32);

switch($_REQUEST['type']){
	case 'txt':
		if(empty($_REQUEST['cid'])){
			if(empty($jieqiConfigs['article']['maketxtfull'])) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
			$path=jieqi_uploadpath($jieqiConfigs['article']['txtfulldir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].$jieqi_file_postfix['txt'];
			//文件不存在或者过期自动生成
			if(!is_file($path) || filemtime($path) + 600 < $lastupdate){
				include_once(JIEQI_ROOT_PATH.'/modules/article/include/repack.php');
				article_repack($_REQUEST['id'], array('maketxtfull'=>1), 1);
			}
			$ret = pack_down_do($path, $jieqiConfigs['article']['scoretxtfulldown'], true, $_REQUEST['fname']);
		}else{
			if(empty($jieqiConfigs['article']['maketxt'])) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
			$path=jieqi_uploadpath($jieqiConfigs['article']['txtdir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$_REQUEST['cid'].$jieqi_file_postfix['txt'];
			$ret = pack_down_do($path, 0, false, $_REQUEST['fname']);
		}
		break;
	case 'zip':
		if(empty($jieqiConfigs['article']['makezip'])) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		$path = jieqi_uploadpath($jieqiConfigs['article']['zipdir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].$jieqi_file_postfix['zip'];
		//文件不存在或者过期自动生成
		if(!is_file($path) || filemtime($path) + 600 < $lastupdate){
			include_once(JIEQI_ROOT_PATH.'/modules/article/include/repack.php');
			article_repack($_REQUEST['id'], array('makezip'=>1), 1);
		}
		$ret = pack_down_do($path, $jieqiConfigs['article']['scorezipdown'], true, $_REQUEST['fname']);
		break;
	case 'umd':
		if(empty($jieqiConfigs['article']['makeumd'])) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		if(isset($_REQUEST['vsize'])) $_REQUEST['vsize'] = intval($_REQUEST['vsize']);
		else $_REQUEST['vsize'] = 0;
		if($_REQUEST['vsize'] == 1) $_REQUEST['vsize'] = 0;
		if(!isset($vsflags[$_REQUEST['vsize']]) || ($jieqiConfigs['article']['makeumd'] & $vsflags[$_REQUEST['vsize']]) == 0) jieqi_printfail($jieqiLang['article']['down_file_notopen']);

		if(empty($_REQUEST['vsize'])){
			$path = jieqi_uploadpath($jieqiConfigs['article']['umddir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$_REQUEST['id'].$jieqi_file_postfix['umd'];
			$checkfile = $path;
		}else{
			$path = jieqi_uploadpath($jieqiConfigs['article']['umddir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$_REQUEST['id'].'_'.intval($_REQUEST['vsize']).'_'.intval($_REQUEST['vid']).$jieqi_file_postfix['umd'];
			$checkfile = dirname($path).'/'.$_REQUEST['id'].'_'.intval($_REQUEST['vsize']).'.xml';
		}
		//文件不存在或者过期自动生成
		if(!is_file($checkfile) || filemtime($checkfile) + 600 < $lastupdate){
			include_once(JIEQI_ROOT_PATH.'/modules/article/include/repack.php');
			article_repack($_REQUEST['id'], array('makeumd'=>1), 1);
		}
		$ret = pack_down_do($path, $jieqiConfigs['article']['scoreumddown'], true, $_REQUEST['fname']);
		break;
	case 'jar':
		if(empty($jieqiConfigs['article']['makejar'])) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		if(isset($_REQUEST['vsize'])) $_REQUEST['vsize'] = intval($_REQUEST['vsize']);
		else $_REQUEST['vsize'] = 0;
		if($_REQUEST['vsize'] == 1) $_REQUEST['vsize'] = 0;
		if(!isset($vsflags[$_REQUEST['vsize']]) || ($jieqiConfigs['article']['makejar'] & $vsflags[$_REQUEST['vsize']]) == 0) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		
		if(empty($_REQUEST['vsize'])){
			$path = jieqi_uploadpath($jieqiConfigs['article']['jardir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$_REQUEST['id'].$jieqi_file_postfix['jar'];
			$checkfile = $path;
		}else{
			$path = jieqi_uploadpath($jieqiConfigs['article']['jardir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$_REQUEST['id'].'_'.intval($_REQUEST['vsize']).'_'.intval($_REQUEST['vid']).$jieqi_file_postfix['jar'];
			$checkfile = dirname($path).'/'.$_REQUEST['id'].'_'.intval($_REQUEST['vsize']).'.xml';
		}
		//文件不存在或者过期自动生成
		if(!is_file($checkfile) || filemtime($checkfile) + 600 < $lastupdate){
			include_once(JIEQI_ROOT_PATH.'/modules/article/include/repack.php');
			article_repack($_REQUEST['id'], array('makejar'=>1), 1);
		}
		$ret = pack_down_do($path, $jieqiConfigs['article']['scorejardown'], true, $_REQUEST['fname']);
		break;
	case 'jad':
		if(empty($jieqiConfigs['article']['makejar'])) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		if(isset($_REQUEST['vsize'])) $_REQUEST['vsize'] = intval($_REQUEST['vsize']);
		else $_REQUEST['vsize'] = 0;
		if($_REQUEST['vsize'] == 1) $_REQUEST['vsize'] = 0;
		if(!isset($vsflags[$_REQUEST['vsize']]) || ($jieqiConfigs['article']['makejar'] & $vsflags[$_REQUEST['vsize']]) == 0) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		
		if(empty($_REQUEST['vsize'])){
			$path = jieqi_uploadpath($jieqiConfigs['article']['jardir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$_REQUEST['id'].$jieqi_file_postfix['jad'];
			$checkfile = $path;
		}else{
			$path = jieqi_uploadpath($jieqiConfigs['article']['jardir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$_REQUEST['id'].'_'.intval($_REQUEST['vsize']).'_'.intval($_REQUEST['vid']).$jieqi_file_postfix['jad'];
			$checkfile = dirname($path).'/'.$_REQUEST['id'].'_'.intval($_REQUEST['vsize']).'.xml';
		}
		//文件不存在或者过期自动生成(jad暂不检测，只检测jar)
		/*
		if(!is_file($checkfile) || filemtime($checkfile) + 600 < $lastupdate){
			include_once(JIEQI_ROOT_PATH.'/modules/article/include/repack.php');
			article_repack($_REQUEST['id'], array('makejar'=>1), 1);
		}
		*/
		$ret = pack_down_do($path, $jieqiConfigs['article']['scorejardown'], false, $_REQUEST['fname']);
		break;
	default:
		jieqi_printfail(LANG_ERROR_PARAMETER);
		break;
}

if(!$ret){
	jieqi_printfail($jieqiLang['article']['down_file_nocreate']);
}

function pack_down_do($path, $score, $changescore=true, $fname=''){
	global $jieqiLang;
	$score=intval($score);
	$ftype = strrchr(trim(strtolower($path)),".");

	if($score>0){
		jieqi_checklogin();
		jieqi_loadlang('down', JIEQI_MODULE_NAME);
		if($_SESSION['jieqiUserScore']<$score){
			jieqi_printfail(sprintf($jieqiLang['article']['low_down_score'], $score));
		}else{
			if(!is_file($path)) return false;
			$filename = empty($fname) ? basename($path) : jieqi_htmlstr($fname).$ftype;
			pack_down_file($path, $filename);
			if($changescore){
				include_once(JIEQI_ROOT_PATH.'/class/users.php');
				$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
				$users_handler->changeScore($_SESSION['jieqiUserId'], $score, false, false);
			}
		}
	}else{
		if(!is_file($path)) return false;
		$filename = empty($fname) ? basename($path) : jieqi_htmlstr($fname).$ftype;
		pack_down_file($path, $filename);
	}
	return true;
}

function pack_down_file($path, $filename){

	header("Content-type: application/x-octet-stream");
	header("Accept-Ranges: bytes");
	header("Content-Disposition: attachment; filename=".$filename);
	//jad如果是中文的话单独替换下内容
	if(substr($filename,-4) == $jieqi_file_postfix['jad'] && filesize($path) < 2048){
		$tmpvar = substr($filename, 0, -4);
		if(!is_numeric($tmpvar)){
			$data = file_get_contents($path);
			if(!is_numeric($tmpvar) && preg_match('/MIDlet-Name:\s*([^\s\r\n]+)/is', $data, $matches)){
				echo preg_replace('/[0-9]+\.jar/isU', trim($matches[1]).'.jar', $data);
				return true;
			}
		}
	}
	$file_size = filesize($path);
	header("Content-Length: ".$file_size);
	//nginx 利用 X-Accel-Redirect 直接定位成静态下载
	if(!empty($_SERVER['SERVER_SOFTWARE']) && preg_match('/nginx/is', $_SERVER['SERVER_SOFTWARE'])){
		header("X-Accel-Redirect: ".str_replace(JIEQI_ROOT_PATH, '', $path));
		return true;
	}
	$fp = fopen($path,"rb");
	$buffer_size = 1024;
	$cur_pos = 0;
	while(!feof($fp) && $file_size - $cur_pos > $buffer_size){
		echo fread($fp,$buffer_size);
		ob_flush();
		flush();
		$cur_pos += $buffer_size;
	}
	echo fread($fp,$file_size-$cur_pos);
	ob_flush();
	flush();
	fclose($fp);
	return true;
}
?>