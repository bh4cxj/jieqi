<?php 
/**
 * ���µ��ͳ��
 *
 * ���µ��ͳ�ƣ�֧�ֻ���
 * 
 * ����ģ�壺��
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: articlevisit.php 337 2009-03-07 00:51:05Z juny $
 */


//ÿ�ε��Ӽ�������
$addnum=1;
if(isset($jieqiConfigs['article']['visitstatnum']) && is_numeric($jieqiConfigs['article']['visitstatnum']) && intval($jieqiConfigs['article']['visitstatnum'])>=0) $addnum=intval($jieqiConfigs['article']['visitstatnum']);
if($addnum > 0){
	if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');
	if(!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
		//����ͳ�ƴ��?��
		include_once(JIEQI_ROOT_PATH.'/include/funstat.php');
		//����Ƿ���Ч�ĵ��
		if(jieqi_visit_valid($_REQUEST['id'], 'article_articleviews')){
			if(!is_object($article)){
				include_once($jieqiModules['article']['path'].'/class/article.php');
				$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
				$article=$article_handler->get($_REQUEST['id']);
			}
			if(is_object($article)){
				$lastvisit = $article->getVar('lastvisit', 'n');
				//����id�����߷���id����
				if($ids = jieqi_visit_ids($_REQUEST['id'], 'article_articleviews', $lastvisit)){
					$nowdate=date('Y-m-d',  JIEQI_NOW_TIME);
					$nowweek=date('w', JIEQI_NOW_TIME);
					foreach($ids as $k=>$v){
						$lastdate=date('Y-m-d', $v['lastvisit']);
						$lastweek=date('w', $v['lastvisit']);
						if($lastweek==0) $lastweek=7;
						$v['visitnum'] = intval($v['visitnum'] * $addnum);
						
						$allstr='allvisit=allvisit+'.$v['visitnum'];
						if($nowdate==$lastdate || JIEQI_NOW_TIME < $v['lastvisit']){
							$daystr='dayvisit=dayvisit+'.$v['visitnum'];
							$weekstr='weekvisit=weekvisit+'.$v['visitnum'];
							$monthstr='monthvisit=monthvisit+'.$v['visitnum'];
						}else{
							$daystr='dayvisit='.$v['visitnum'];
							if($nowweek <= $lastweek || JIEQI_NOW_TIME - $v['lastvisit'] > 604800){
								$weekstr='weekvisit='.$v['visitnum'];
							}else{
								$weekstr='weekvisit=weekvisit+'.$v['visitnum'];
							}
							if(substr($nowdate,0,7) == substr($lastdate,0,7)){
								$monthstr='monthvisit=monthvisit+'.$v['visitnum'];
							}else{
								$monthstr='monthvisit='.$v['visitnum'];
							}
						}
						$sql = 'UPDATE '.jieqi_dbprefix('article_article').' SET lastvisit='.$lastvisit.', '.$daystr.', '.$weekstr.', '.$monthstr.', '.$allstr.' WHERE articleid='.intval($k);
						$article_handler->db->query($sql);
					}
				}
			}
		}
	}
}
?>