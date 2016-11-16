<?php 
/**
* 文章信息页
*
* 显示一篇文章信息，包括最近书评等
* 
* 调用模板：/modules/article/templates/articleinfo.html
* 
* @category   jieqicms
* @package    article
* @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.wonmeng.com)
* @author     $Author: juny $
* @version    $Id: articleinfo.php 332 2009-02-23 09:15:08Z juny $
*/

define('JIEQI_MODULE_NAME', 'article');
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('article', JIEQI_MODULE_NAME);
include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($_REQUEST['id']);
if(!$article) jieqi_printfail($jieqiLang['article']['article_not_exists']);
elseif($article->getVar('display') != 0 && $jieqiUsersStatus != JIEQI_GROUP_ADMIN) jieqi_printfail($jieqiLang['article']['article_not_audit']);
else{
        //包含区块参数(定制)
        jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
        jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
        $jieqi_pagetitle=$article->getVar('articlename').'-'.$article->getVar('author').'-'.JIEQI_SITE_NAME;
        include_once(JIEQI_ROOT_PATH.'/header.php');

        $article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
        $article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
        $jieqiTpl->assign('article_static_url',$article_static_url);
        $jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
        $jieqiTpl->assign('makezip', $jieqiConfigs['article']['makezip']);
        $jieqiTpl->assign('makejar', $jieqiConfigs['article']['makejar']);
        $jieqiTpl->assign('makeumd', $jieqiConfigs['article']['makeumd']);
        $jieqiTpl->assign('maketxtfull', $jieqiConfigs['article']['maketxtfull']);
        $jieqiTpl->assign('maketxt', $jieqiConfigs['article']['maketxt']);
        

        $jieqiTpl->assign('articlename', $article->getVar('articlename'));
        $jieqiTpl->assign('keywords', $article->getVar('keywords'));
        $jieqiTpl->assign('postdate', date(JIEQI_DATE_FORMAT, $article->getVar('postdate')));
        $jieqiTpl->assign('lastupdate', date(JIEQI_DATE_FORMAT, $article->getVar('lastupdate')));
        $jieqiTpl->assign('authorid', $article->getVar('authorid'));
        $jieqiTpl->assign('author', $article->getVar('author'));
        $jieqiTpl->assign('agentid', $article->getVar('agentid'));
        $jieqiTpl->assign('agent', $article->getVar('agent'));
        $jieqiTpl->assign('sortid', $article->getVar('sortid'));
        $_REQUEST['class'] = $article->getVar('sortid');
        $_REQUEST['sortid'] = $article->getVar('sortid');
        
        $jieqiTpl->assign('sort', $jieqiSort['article'][$article->getVar('sortid')]['caption']);
        $preg_from=array(
        '/((https?|ftp):\/\/|www\.)[a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+(\.gif|\.jpg|\.jpeg|\.png|\.bmp)/isU'
        );
        $preg_to=array(
        '<img src="\\0" border="0">'
        );
        $jieqiTpl->assign('intro',preg_replace($preg_from, $preg_to, $article->getVar('intro')));
        $jieqiTpl->assign('notice', preg_replace($preg_from, $preg_to, $article->getVar('notice')));

        //文章封面图片标志
        $jieqiTpl->assign('imgflag', $article->getVar('imgflag','n'));
        $url_simage = jieqi_geturl('article', 'cover', $article->getVar('articleid'), 's', $article->getVar('imgflag','n'));
        if(!empty($url_simage)) $jieqiTpl->assign('hasimage', 1);
        else $jieqiTpl->assign('hasimage', 0);
        $jieqiTpl->assign('url_simage',$url_simage);
        $jieqiTpl->assign('url_limage',jieqi_geturl('article', 'cover', $article->getVar('articleid'), 'l', $article->getVar('imgflag','n')));
        $lastchapter=$article->getVar('lastchapter');
        if($lastchapter != ''){
                if($article->getVar('lastvolume') != '') $lastchapter=$article->getVar('lastvolume').' '.$lastchapter;
                $jieqiTpl->assign('url_lastchapter', jieqi_geturl('article', 'chapter', $article->getVar('lastchapterid'), $article->getVar('articleid')));
        }else{
                $jieqiTpl->assign('url_lastchapter', '');
        }
        $jieqiTpl->assign('lastchapter', $lastchapter);
        $jieqiTpl->assign('size', $article->getVar('size'));
        $jieqiTpl->assign('size_k', ceil($article->getVar('size')/1024));
        $jieqiTpl->assign('size_c', ceil($article->getVar('size')/2));
        $jieqiTpl->assign('dayvisit', $article->getVar('dayvisit'));
        $jieqiTpl->assign('weekvisit', $article->getVar('weekvisit'));
        $jieqiTpl->assign('monthvisit', $article->getVar('monthvisit'));
        $jieqiTpl->assign('mouthvisit', $article->getVar('monthvisit'));
        $jieqiTpl->assign('allvisit', $article->getVar('allvisit'));
        $jieqiTpl->assign('dayvote', $article->getVar('dayvote'));
        $jieqiTpl->assign('weekvote', $article->getVar('weekvote'));
        $jieqiTpl->assign('monthvote', $article->getVar('monthvote'));
        $jieqiTpl->assign('mouthvote', $article->getVar('monthvote'));
        $jieqiTpl->assign('allvote', $article->getVar('allvote'));
        $jieqiTpl->assign('goodnum', $article->getVar('goodnum'));
        $jieqiTpl->assign('badnum', $article->getVar('badnum'));
        if($article->getVar('fullflag')==0) $jieqiTpl->assign('fullflag', $jieqiLang['article']['article_not_full']);
        else $jieqiTpl->assign('fullflag', $jieqiLang['article']['article_is_full']);
        $tmpvar='';
        switch($article->getVar('permission')){
                case '3':
                        $tmpvar=$jieqiLang['article']['article_permission_special'];
                        break;
                case '2':
                        $tmpvar=$jieqiLang['article']['article_permission_insite'];
                        break;
                case '1':
                        $tmpvar=$jieqiLang['article']['article_permission_yes'];
                        break;
                case '0':
                default:
                        $tmpvar=$jieqiLang['article']['article_permission_no'];
                        break;
        }
        $jieqiTpl->assign('permission', $tmpvar);
        $tmpvar='';
        switch($article->getVar('firstflag')){
                case '1':
                        $tmpvar=$jieqiLang['article']['article_site_publish'];
                        break;
                case '0':
                default:
                        $tmpvar=$jieqiLang['article']['article_other_publish'];
                        break;
        }
        $jieqiTpl->assign('firstflag', $tmpvar);
        //管理
        $jieqiTpl->assign('url_manage', $article_static_url.'/articlemanage.php?id='.$article->getVar('articleid'));
        //举报
        $tmpstr=sprintf($jieqiLang['article']['article_report_reason'], jieqi_geturl('article', 'article', $article->getVar('articleid'), 'info'));
        $jieqiTpl->assign('url_report', JIEQI_URL.'/newmessage.php?tosys=1&title='.urlencode(sprintf($jieqiLang['article']['article_report_title'], $article->getVar('articlename','n'))).'&content='.urlencode($tmpstr));
        //采集
        $setting=unserialize($article->getVar('setting', 'n'));        $url_collect=$article_static_url.'/admin/collect.php?toid='.$article->getVar('articleid', 'n');
        if(is_numeric($setting['fromarticle'])) $url_collect.='&fromid='.$setting['fromarticle'];
        if(is_numeric($setting['fromsite'])) $url_collect.='&siteid='.$setting['fromsite'];
        $jieqiTpl->assign('url_collect', $url_collect);

        //互换链接
        if($jieqiConfigs['article']['eachlinknum']>0){
                $eachlinkrows=array();
                $eachlinkcount=0;
                $setting=unserialize($article->getVar('setting', 'n'));

                if(!empty($setting['eachlink']['ids'])){
                        foreach($setting['eachlink']['ids'] as $k=>$v){
                                $eachlinkrows[$eachlinkcount]['articleid']=$v;
                                $eachlinkrows[$eachlinkcount]['articlename']=jieqi_htmlstr($setting['eachlink']['names'][$k]);
                                $eachlinkrows[$eachlinkcount]['articlesubdir']=jieqi_getsubdir($v);
                                $eachlinkrows[$eachlinkcount]['url_articleinfo']=jieqi_geturl('article', 'article', $v, 'info');
                                //$imageinfo = JieqiArticleHandler::getCoverInfo($v->getVar('imgflag','n'));
                                //$eachlinkrows[$eachlinkcount]['url_image']=jieqi_uploadurl($jieqiConfigs['article']['imagedir'], $jieqiConfigs['article']['imageurl'], 'article', $article_static_url).jieqi_getsubdir($v).'/'.$v.'/'.$v.'s'.$simgtype;
                                $eachlinkcount++;
                        }
                }

                $jieqiTpl->assign('eachlinknum', $jieqiConfigs['article']['eachlinknum']);
                $jieqiTpl->assign('eachlinkcount', $eachlinkcount);
                $jieqiTpl->assign_by_ref('eachlinkrows', $eachlinkrows);
        }else{
                $jieqiTpl->assign('eachlinknum', 0);
                $jieqiTpl->assign('eachlinkcount', 0);
        }

        //文章序号
        $jieqiTpl->assign('articleid', $article->getVar('articleid'));
        $jieqiTpl->assign('lastchapterid', $article->getVar('lastchapterid'));
        //点击阅读,全文阅读
        if($article->getVar('chapters','n')>0){
                $jieqiTpl->assign('url_read', jieqi_geturl('article', 'article', $article->getVar('articleid'), 'index'));
                if($jieqiConfigs['article']['makefull']==0 || JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET){
                        $jieqiTpl->assign('url_fullpage', $article_static_url.'/reader.php?aid='.$article->getVar('articleid'));
                }else{
                        $jieqiTpl->assign('url_fullpage', jieqi_uploadurl($jieqiConfigs['article']['fulldir'], $jieqiConfigs['article']['fullurl'], 'article', $article_static_url).jieqi_getsubdir($article->getVar('articleid')).'/'.$article->getVar('articleid').$jieqiConfigs['article']['htmlfile']);
                }
        }else{
                $jieqiTpl->assign('url_read', '#');
                $jieqiTpl->assign('url_fullpage', '#');
        }


        //放入书架
        $jieqiTpl->assign('url_bookcase', $article_dynamic_url.'/addbookcase.php?bid='.$article->getVar('articleid'));
        //推荐本书
        $jieqiTpl->assign('url_uservote', $article_dynamic_url.'/uservote.php?id='.$article->getVar('articleid'));
        //作家专栏
        if($article->getVar('authorid')>0){
                $jieqiTpl->assign('url_authorpage', $article_dynamic_url.'/authorpage.php?id='.$article->getVar('authorid'));
        }else{
                $jieqiTpl->assign('url_authorpage','#');
        }
        $jieqiTpl->assign('url_authorarticle', $article_dynamic_url.'/authorarticle.php?author='.urlencode($article->getVar('author','n')));
        //打包下载
        if($article->getVar('chapters','n')>0){
                if($jieqiConfigs['article']['makehtml']==0){
                        $jieqiTpl->assign('url_download', '#');
                }else{
                        $jieqiTpl->assign('url_download', jieqi_uploadurl($jieqiConfigs['article']['zipdir'], $jieqiConfigs['article']['zipurl'], 'article', $article_static_url).jieqi_getsubdir($article->getVar('articleid')).'/'.$article->getVar('articleid').$jieqi_file_postfix['zip']);
                }
                $jieqiTpl->assign('url_txtarticle', $article_static_url.'/txtarticle.php?id='.$article->getVar('articleid'));
        }else{
                $jieqiTpl->assign('url_download', '#');
                $jieqiTpl->assign('url_txtarticle', '#');
        }

        //投票部分
        $showvote=0;
        $jieqiConfigs['article']['articlevote']=intval($jieqiConfigs['article']['articlevote']);
        if($jieqiConfigs['article']['articlevote'] > 0 && isset($setting['avoteid']) && $setting['avoteid']>0){
                include_once($jieqiModules['article']['path'].'/class/avote.php');
                $avote_handler =& JieqiAvoteHandler::getInstance('JieqiAvoteHandler');
                $avote=$avote_handler->get($setting['avoteid']);
                if(is_object($avote)){
                        $jieqiTpl->assign('voteid', $avote->getVar('voteid'));
                        $jieqiTpl->assign('votetitle', $avote->getVar('title'));
                        $jieqiTpl->assign('mulselect', $avote->getVar('mulselect'));
                        $useitem=$avote->getVar('useitem','n');
                        $voteitemrows=array();
                        for($i=1;$i<=$useitem;$i++){
                                $voteitemrows[$i-1]['id']=$i;
                                $voteitemrows[$i-1]['item']=$avote->getVar('item'.$i);
                        }
                        $jieqiTpl->assign_by_ref('voteitemrows', $voteitemrows);
                        $showvote=1;
                }
        }
        $jieqiTpl->assign('showvote', $showvote);

        //电子书部分
        $articletype=intval($article->getVar('articletype'));
        if(($articletype & 1)>0) $hasebook=1;
        else $hasebook=0;
        if(($articletype & 2)>0) $hasobook=1;
        else $hasobook=0;
        if(($articletype & 4)>0) $hastbook=1;
        else $hastbook=0;

        if($hasobook==1){
                include_once($jieqiModules['obook']['path'].'/class/obook.php');
                $obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('articleid', $article->getVar('articleid'), '='));
                $obook_handler->queryObjects($criteria);
                $obook=$obook_handler->getObject();
                if(is_object($obook) && $obook->getVar('display')==0 && $obook->getVar('size')>0){
                        $jieqiTpl->assign('obook_obookid', $obook->getVar('obookid'));
                        $jieqiTpl->assign('obook_lastvolume', $obook->getVar('lastvolume'));
                        $jieqiTpl->assign('obook_lastvolumeid', $obook->getVar('lastvolumeid'));
                        $jieqiTpl->assign('obook_lastchapter', $obook->getVar('lastchapter'));
                        $jieqiTpl->assign('obook_lastchapterid', $obook->getVar('lastchapterid'));
                        $jieqiTpl->assign('obook_lastupdate', date(JIEQI_DATE_FORMAT, $obook->getVar('lastupdate')));
                        $jieqiTpl->assign('obook_size', $obook->getVar('size'));
                        $jieqiTpl->assign('obook_publishid', $obook->getVar('publishid'));
                }else{
                        $hasobook=0;
                }
        }
        $jieqiTpl->assign('articletype', $articletype);
        $jieqiTpl->assign('hasebook', $hasebook);
        $jieqiTpl->assign('hasobook', $hasobook);
        $jieqiTpl->assign('hastbook', $hastbook);

        //网友章节
        /*
        if($jieqiConfigs['article']['allowuserchap']){
        $jieqiTpl->assign('allowuserchap', 1);
        $jieqiTpl->assign('url_newuserchap', $jieqiModules['article']['url'].'/newuserchap.php?aid='.$article->getVar('articleid'));
        $jieqiTpl->assign('url_userchaplist', $jieqiModules['article']['url'].'/userchaplist.php?aid='.$article->getVar('articleid'));



        $query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
        $sql="SELECT userchapid, posterid, poster, postdate, volumename, chaptername, size, goodnum, badnum FROM ".jieqi_dbprefix('article_userchap')." WHERE articleid=".jieqi_dbslashes($article->getVar('articleid', 'n'))." ORDER BY userchapid DESC LIMIT 0, ".intval($jieqiConfigs['article']['userchapnew']);
        $ret=$query->execute($sql);
        $userchaprows=array();
        $k=0;
        while($v =$query->getObject()){
        $userchaprows[$k]['userchapid']=$v->getVar('userchapid');
        $userchaprows[$k]['posterid']=$v->getVar('posterid');
        $userchaprows[$k]['poster']=$v->getVar('poster');
        $userchaprows[$k]['postdate']=date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, $v->getVar('postdate'));
        $userchaprows[$k]['volumename']=$v->getVar('volumename');
        $userchaprows[$k]['chaptername']=$v->getVar('chaptername');
        $userchaprows[$k]['size']=$v->getVar('size');
        $userchaprows[$k]['goodnum']=$v->getVar('goodnum');
        $userchaprows[$k]['badnum']=$v->getVar('badnum');
        $k++;
        }

        $jieqiTpl->assign_by_ref('userchaprows', $userchaprows);
        }else{
        $jieqiTpl->assign('allowuserchap', 0);
        }
        */
        //书评部分
        include_once(JIEQI_ROOT_PATH.'/include/funpost.php');
        $jieqiConfigs['article']['reviewtype']=2;
        if(!isset($jieqiConfigs['article']['reviewtype']) || $jieqiConfigs['article']['reviewtype'] == 1){
                include_once($jieqiModules['article']['path'].'/class/review.php');
                include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');

                $review_handler =& JieqiReviewHandler::getInstance('JieqiReviewHandler');
                $criteria=new CriteriaCompo(new Criteria('articleid', $article->getVar('articleid')));
                $criteria->setSort('topflag DESC, topicid');
                $criteria->setOrder('DESC');
                $criteria->setLimit($jieqiConfigs['article']['reviewnew']);
                $criteria->setStart(0);
                $review_handler->queryObjects($criteria);
                $reviewrows=array();
                $k=0;
                while($v = $review_handler->getObject()){
                        $start=3;
                        if($v->getVar('topflag')==1) {
                                $reviewrows[$k]['topflag']=1;
                                $start+=4;
                        }else{
                                $reviewrows[$k]['topflag']=0;
                        }
                        if($v->getVar('goodflag')==1) {
                                $reviewrows[$k]['goodflag']=1;
                                $start+=4;
                        }else{
                                $reviewrows[$k]['goodflag']=0;
                        }
                        if($jieqiConfigs['article']['reviewenter']=='0'){
                                $reviewrows[$k]['content']=jieqi_htmlstr(jieqi_limitwidth(str_replace(array("\r", "\n"), array('', ' '), $v->getVar('reviewtext', 'n')), $jieqiConfigs['article']['reviewwidth'], $start));
                        }else{
                                $reviewrows[$k]['content']=jieqi_htmlstr(jieqi_limitwidth($v->getVar('reviewtext', 'n'), $jieqiConfigs['article']['reviewwidth'], $start));
                        }
                        $reviewrows[$k]['postdate']=date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, $v->getVar('postdate'));
                        $reviewrows[$k]['userid']=$v->getVar('userid');
                        $reviewrows[$k]['username']=$v->getVar('username');
                        $k++;
                }

                $jieqiTpl->assign_by_ref('reviewrows', $reviewrows);
                $jieqiTpl->assign('url_goodreview', $article_dynamic_url.'/reviews.php?aid='.$article->getVar('articleid').'&type=good');
                $jieqiTpl->assign('url_allreview', $article_dynamic_url.'/reviews.php?aid='.$article->getVar('articleid').'&type=all');
                $jieqiTpl->assign('url_review', $article_dynamic_url.'/reviews.php?aid='.$article->getVar('articleid'));
        }elseif($jieqiConfigs['article']['reviewtype'] == 2){
                include_once($jieqiModules['article']['path'].'/class/reviews.php');
                include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');

                $reviews_handler =& JieqiReviewsHandler::getInstance('JieqiReviewsHandler');
                $criteria=new CriteriaCompo(new Criteria('ownerid',  $article->getVar('articleid')));
                $criteria->setSort('istop DESC, topicid');
                $criteria->setOrder('DESC');
                $criteria->setLimit($jieqiConfigs['article']['reviewnew']);
                $criteria->setStart(0);
                $reviews_handler->queryObjects($criteria);
                $reviewrows=array();
                $k=0;
                while($v = $reviews_handler->getObject()){
                        $reviewrows[$k] = jieqi_topic_vars($v);
                        $k++;
                }

                $jieqiTpl->assign_by_ref('reviewrows', $reviewrows);
                $jieqiTpl->assign('url_goodreview', $article_dynamic_url.'/reviews.php?aid='. $article->getVar('articleid').'&type=good');
                $jieqiTpl->assign('url_allreview', $article_dynamic_url.'/reviews.php?aid='. $article->getVar('articleid').'&type=all');
                $jieqiTpl->assign('url_review', $article_dynamic_url.'/reviews.php?aid='. $article->getVar('articleid'));
        }

        if(!empty($_SESSION['jieqiUserId'])) $jieqiTpl->assign('enablepost', 1);
        else $jieqiTpl->assign('enablepost', 0);
        //是否显示验证码
        if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
        $jieqiTpl->assign('postcheckcode', $jieqiConfigs['system']['postcheckcode']);
        $jieqiTpl->setCaching(0);
        $jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/52mb.html';
        //点击统计要设置cookie和访问数据库，所以放footer.php前面
        if(!isset($jieqiConfigs['article']['visitstatnum']) || !empty($jieqiConfigs['article']['visitstatnum'])) include_once($jieqiModules['article']['path'].'/articlevisit.php');
        include_once(JIEQI_ROOT_PATH.'/footer.php');
}
?>