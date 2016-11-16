<?php 
/**
 * 批量删除文章
 *
 * 批量删除文章
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: batchdel.php 228 2008-11-27 06:44:31Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['article']['delallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
if(empty($_REQUEST['checkid'])) jieqi_printfail($jieqiLang['article']['need_delete_ids']);
@set_time_limit(0);
@session_write_close();
echo '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              ';
include_once($jieqiModules['article']['path'].'/include/operatefunction.php');
foreach($_REQUEST['checkid'] as $deleteid){
	$ret = jieqi_article_delete($deleteid, false);
	if(is_object($ret)){
		echo sprintf($jieqiLang['article']['start_delete_article'], $ret->getVar('articlename'));
		ob_flush();
		flush();
	}
}
//更新最新文章
jieqi_getcachevars('article', 'articleuplog');
if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
$jieqiArticleuplog['articleuptime']=time();
jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');

echo $jieqiLang['article']['batch_delete_success'];
ob_flush();
flush();

if(empty($_REQUEST['url_jump'])) $_REQUEST['url_jump']=$jieqiModules['article']['url'].'/admin/article.php';
jieqi_jumppage($_REQUEST['url_jump'], LANG_DO_SUCCESS, $jieqiLang['article']['batch_delete_complete']);
?>