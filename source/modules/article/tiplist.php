<?php
define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
jieqi_checklogin();
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($_REQUEST['id']);
if(!$article) jieqi_printfail($jieqiLang['article']['article_not_exists']);
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//管理别人文章权限
$canedit=jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改文章
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($article->getVar('authorid')==$tmpvar || $article->getVar('posterid')==$tmpvar || $article->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}
if(!$canedit) jieqi_printfail($jieqiLang['article']['noper_manage_article']);

jieqi_getconfigs('article', 'authorblocks', 'jieqiBlocks');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$jieqi_pagetitle=$article->getVar('articlename').'-'.$article->getVar('author').'-'.JIEQI_SITE_NAME;
include_once(JIEQI_ROOT_PATH.'/header.php');
$perpage = 20;
$curpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$begin = ($curpage-1)*$perpage;
$sql = "SELECT u.`name`,t.uid,t.dateline,t.nums from jieqi_article_tip as t
INNER JOIN jieqi_system_users as u
ON t.uid = u.`uid`
WHERE t.articleid = '$_REQUEST[id]'
order by dateline desc
LIMIT $begin,$perpage";
$result = mysql_query($sql);
while($list = mysql_fetch_array($result,1)){
            $listdd[] = $list;
        }
$num = count($listdd);

$jieqiTpl->assign( "page", simplepage($num, $perpage, $curpage, '/modules/article/tiplist.php?id='.$_REQUEST['id']) );
$jieqiTpl->assign( "tiprows", $listdd );
$jieqiTpl->assign( "articleid", $article->getVar('articleid') );
$jieqiTpl->assign( "articlename", $article->getVar('articlename') );

$countsql = "SELECT SUM(nums) as nums FROM jieqi_article_tip WHERE articleid='$_REQUEST[id]'";
$results = mysql_query($countsql);
while($list = mysql_fetch_array($results,1)){
            $count = $list;
        }

$jieqiTpl->assign( "count", $count['nums'] );
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/tiplist.html';

include_once(JIEQI_ROOT_PATH.'/footer.php');

function simplepage($num, $perpage, $curpage, $mpurl) {
		$return = '';
		$lang['next'] = '下一页';
		$lang['prev'] = '上一页';
		$next = $num == $perpage ? '<a href="'.$mpurl.'&page='.($curpage+1).'" class="nxt">'.$lang['next'].'</a>' : '';
		$prev = $curpage > 1 ? '<span class="pgb"><a href="'.$mpurl.'&page='.($curpage-1).'">'.$lang['prev'].'</a></span>' : '';
		if($next || $prev) {
				$return = '<tr><td></td><td></td><td></td><td>'.$prev.'&nbsp;&nbsp;&nbsp;&nbsp;'.$next.'</td></tr>';
		}
		return $return;
}