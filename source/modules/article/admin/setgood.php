<?php 
/**
 * 设置文章的本站推荐标志
 *
 * 设置文章的本站推荐标志
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: setgood.php 228 2008-11-27 06:44:31Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
if(empty($_REQUEST['id'])) jieqi_printfail($jieqiLang['article']['article_not_exists']);
include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($_REQUEST['id']);
if(!is_object($article)) jieqi_printfail($jieqiLang['article']['article_not_exists']);
if($_REQUEST['action']=='no') $article->setVar('toptime', 0);
else $article->setVar('toptime', JIEQI_NOW_TIME);
$article_handler->insert($article);

if($_REQUEST['action']=='no') jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['article_notgood_success']);
else jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['article_setgood_success']);
?>