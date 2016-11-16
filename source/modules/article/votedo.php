<?php 
/**
 * 记录文章投票调查
 *
 * 记录文章投票调查
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: votedo.php 228 2008-11-27 06:44:31Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
jieqi_checklogin();

if(empty($_REQUEST['vid']) || empty($_REQUEST['aid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('avote', JIEQI_MODULE_NAME);
$_REQUEST['vid']=intval($_REQUEST['vid']);
$_REQUEST['aid']=intval($_REQUEST['aid']);
//载入统计处理函数
include_once(JIEQI_ROOT_PATH.'/include/funstat.php');
//检查是否已投票文章
if(!jieqi_visit_valid($_REQUEST['aid'], 'article_articlevotes')) jieqi_printfail($jieqiLang['article']['avote_already_do']);
else{
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
	$jieqiConfigs['article']['articlevote']=intval($jieqiConfigs['article']['articlevote']);
	if($jieqiConfigs['article']['articlevote'] <= 0) jieqi_printfail($jieqiLang['article']['article_vote_close']);

	if(!empty($_REQUEST['voteitem'])){
		$upstr='';
		$votenum=0;
		$addnum=1;
		if(is_array($_REQUEST['voteitem'])){
			foreach($_REQUEST['voteitem'] as $v){
				$v=intval($v);
				if($v>=1 && $v<=$jieqiConfigs['article']['articlevote']){
					$upstr.=", stat".$v."=stat".$v."+".$addnum;
					$votenum++;
				}
			}
		}else{
			$v=intval($_REQUEST['voteitem']);
			if($v>=1 && $v<=$jieqiConfigs['article']['articlevote']){
				$upstr.=", stat".$v."=stat".$v."+".$addnum;
				$votenum++;
			}
		}
		if($votenum>0){
			jieqi_includedb();
			$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
			include_once($jieqiModules['article']['path'].'/class/avote.php');
			$sql="UPDATE ".jieqi_dbprefix('article_avstat')." SET statall=statall+".($votenum * $addnum).$upstr." WHERE voteid=".$_REQUEST['vid'];
			$query->execute($sql);
		}
	}

	header('Location: '.$jieqiModules['article']['url'].'/voteresult.php?id='.$_REQUEST['vid'].'&aid='.$_REQUEST['aid']);
}

?>