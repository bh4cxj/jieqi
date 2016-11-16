<?php 
/**
 * 动态文章阅读
 *
 * 显示章节目录或者一个章节内容
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: reader.php 228 2008-11-27 06:44:31Z juny $
 */
define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['aid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
include_once($jieqiModules['article']['path'].'/class/package.php');
include_once(JIEQI_ROOT_PATH.'/header.php');
$package=new JieqiPackage($_REQUEST['aid']);
$conn=mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) or die('链接失败');  mysql_select_db(JIEQI_DB_NAME, $conn); 
@mysql_query("SET names gbk");
if($package->loadOPF()){
	if(!empty($_REQUEST['cid'])){
		$sql3 = mysql_query("SELECT * FROM `jieqi_article_chapter` WHERE `articleid` = $_REQUEST[aid] and chaptertype!=1 order by `chapterid` asc");
        $str = '';
		while($list = mysql_fetch_array($sql3,1)){
			if($list['chapterid'] == $_GET['cid']){
				$color = 'red';
			}else{
				$color = '#fff';
			}
            $str .= '<li><a href="/modules/article/reader.php?aid='.$_REQUEST[aid].'&cid='.$list['chapterid'].'" title="字数：'.$list['size'].'"><font color='.$color.'>'.$list['chaptername'].'</font></a></li>';
        }

        $jieqiTpl->assign( "str", $str );
		$package->showChapter($_REQUEST['cid']);
	}else{
		$index = true;
		$package->showIndex();
	}
}else{
	jieqi_loadlang('article', JIEQI_MODULE_NAME);
	jieqi_printfail($jieqiLang['article']['article_not_exists']);
}
?>