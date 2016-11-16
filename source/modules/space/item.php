<?php
/**
 * 显示博文详细信息
 *
 * 显示博文详细信息
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/item.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
define('JIEQI_MODULE_NAME','space');
require_once('../../global.php');
include_once($jieqiModules['space']['path'].'/spaceheader.php');
jieqi_loadlang('item',JIEQI_MODULE_NAME);
jieqi_getconfigs('space', 'itemblocks','jieqiBlocks');
space_get_blog_cat();
jieqi_getconfigs(JIEQI_MODULE_NAME,'configs');

//get blog
$id = intval($_REQUEST['id']);
include_once($jieqiModules['space']['path'].'/class/blog.php');
$blog_handler =& JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');
$blog=$blog_handler->get($id);
if(!$blog){
	jieqi_printfail($jieqiLang['space']['no_blog']);
}

switch($_REQUEST['act']){
	case 'add_review':
		if($blog->getVar('allow_com') && $_REQUEST['content'] ){
			include_once($jieqiModules['space']['path'].'/class/blogreview.php');
			$blog_review_handler = & JieqiSpaceBlogReviewHandler::getInstance('JieqiSpaceBlogReviewHandler');
			$review = $blog_review_handler->create();
			$review->setVar('uid',$uid);
			$review->setVar('blog_id',$id);
			$review->setVar('title',$_REQUEST['title']);
			$review->setVar('content',$_REQUEST['content']);
			if(empty($_SESSION['jieqiUserId']) ){
				$poster_id = 0;
			}else{
				$poster_id = $_SESSION['jieqiUserId'];
			}
			if(empty($_SESSION['jieqiUserName']) ){
				if( empty($_REQUEST['poster_name']) ){
					$_REQUEST['poster_name'] = 'guest';
				}
				$poster_name = $_REQUEST['poster_name'];
			}else{
				$poster_name = $_SESSION['jieqiUserName'];
			}
			$review->setVar('poster_id',$poster_id);
			$review->setVar('poster_name',$poster_name);
			$review->setVar('up_time',JIEQI_NOW_TIME);
			$blog_review_handler->insert($review);
			$criteria = new CriteriaCompo(new Criteria('id',$id) );
			$criteria->add(new Criteria('uid',$uid) );
			$blog_handler->updatefields('`review_num`=`review_num`+1',$criteria);
			jieqi_jumppage($jieqiModules['space']['url'].'/item.php?uid='.$uid.'&id='.$id.'&page=last',$jieqiLang['review_finished'],$jieqiLang['space']['review_have_reviewed']);
		}
		break;
	case 'del_review':
		if($space_hoster == 1){
			include_once($jieqiModules['space']['path'].'/class/blogreview.php');
			$blog_review_handler = & JieqiSpaceBlogReviewHandler::getInstance('JieqiSpaceBlogReviewHandler');
			$criteria = new CriteriaCompo(new Criteria('uid',$uid) );
			$criteria->add(new Criteria('id',intval($_REQUEST['rid']) ));
			$criteria->add(new Criteria('blog_id',$id) );
			if( $blog_review_handler->delete($criteria) ){
				$criteria = new CriteriaCompo( new Criteria('uid',$uid) );
				$criteria->add(new Criteria('id',$id) );
				$blog_handler->updatefields('`review_num`=`review_num`-1',$criteria);
				jieqi_jumppage($jieqiModules['space']['url'].'/item.php?uid='.$uid.'&id='.$id,$jieqiLang['space']['del_finish'],$jieqiLang['space']['reivew_have_del']);
			}else{
				jieqi_printfail('delete fail');
			}
		}
		break;
	default:
		include_once($jieqiModules['space']['path'].'/class/blogreview.php');
		include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
		$ts=TextConvert::getInstance('TextConvert');
		//about blog
		$jieqiTpl->assign('id',$id);
		$jieqiTpl->assign('title',$blog->getVar('title') );
		$jieqiTpl->assign('content',$ts->displayTarea($blog->getVar('content','n') ) );
		$jieqiTpl->assign('time',date('Y-m-d H:i:s',$blog->getVar('up_time')) );
		$jieqiTpl->assign('hit_num',$blog->getVar('hit_num') );
		$jieqiTpl->assign('review_num',$blog->getVar('review_num') );
		$jieqiTpl->assign('cat_url',$jieqiModules['space']['url'].'/blog.php?uid='.$uid.'&cat_id='.$blog->getVar('cat_id')  );
		$jieqiTpl->assign('cat_name',$blog_cats[$blog->getVar('cat_id')]['name']);
		$jieqiTpl->assign('allow_com',$blog->getVar('allow_com') );
		$jieqiTpl->assign('poster_sess',$_SESSION['jieqiUserId']);
		if(!$blog->getVar('ar_open') ){
			if($space_hoster == 1){
				$jieqiTpl->assign('private',1);
			}else{
				jieqi_printfail('no this blog');
			}
		}
		blog_add_hit($id);

		//about review
		$blog_review_handler = &JieqiSpaceBlogReviewHandler::getInstance('JieqiSpaceBlogReviewHandler');
		$criteria = new CriteriaCompo(new Criteria('uid',$uid) );
		$criteria->add(new Criteria('blog_id',$id) );
		if($_REQUEST['page']=='last'){
		    $_REQUEST['page'] = ceil($blog_review_handler->getCount($criteria)/$jieqiConfigs['space']['review_num']);
		}else{
			if(!is_numeric($_REQUEST['page'])){
				$_REQUEST['page'] = 1;
			}		    
		}
		$criteria->setSort('id');
		$criteria->setOrder('ASC');
		$criteria->setStart( ($_REQUEST['page']-1)*$jieqiConfigs['space']['review_num'] );
		$criteria->setLimit($jieqiConfigs['space']['review_num'] );
		$blog_review_handler->queryObjects($criteria);
		$k = 0;
		include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
		while($v=$blog_review_handler->getObject() ){
			$reviews[$k]['poster_id'] = $v->getVar('poster_id');
			$reviews[$k]['id'] = $v->getVar('id');
			$reviews[$k]['poster_name'] = $v->getVar('poster_name');
			$reviews[$k]['time'] = date('Y-m-d H:i:s',$v->getVar('up_time') );
			$reviews[$k]['title'] = $v->getVar('poster_id','n');
			$reviews[$k]['content'] =$ts->displayTarea( jieqi_limitwidth( $v->getVar('content','n') ), 0, 1, 1, 1, 1, 'screen.width*0.75');
			$k++;
		}
		$jieqiTpl->assign('reviews',$reviews);
		//处理页面跳转
		include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
		$jumppage = new JieqiPage($blog_review_handler->getCount($criteria),10,$_REQUEST['page']);
		$jumppage->setlink('', true, true);
		$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
		break;
}

$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/item.html';
include_once($jieqiModules['space']['path'].'/spacefooter.php');
/**
　　* 函数blog_add_hit,处理文章的流量统计信息
　　*
　　* 根据文章编号处理文章的相关信息，函数接受二个参数$id,$add_num
　　* 
　　* @param integer $id 文章编号
　　* @param integer $add_num 增加流量数 
	* @return bool
*/
function blog_add_hit($id,$add_num=1){
	global $blog_handler;
	global $blog;
	//judge if visited or not
	if(isset($_COOKIE['blog_visited']) ){
		$visited_cookie_ar = unserialize($_COOKIE['blog_visited']);
	}
	if(isset($_SESSION['blog_visited']) ){
		$visited_session_ar = unserialize($_SESSION['blog_visited']);
	}
	if(@in_array($id,$visited_cookie_ar) || @in_array($id,$visited_session_ar) || $add_num < 1 ){
		return false;
	}else{
		$visited_cookie_ar[] = $id;
		@setcookie('blog_visited',serialize($visited_cookie_ar),JIEQI_NOW_TIME+3600,'/',JIEQI_COOKIE_DOMAIN,0);
		$visited_session_ar[] = $id;
		$_SESSION['blog_visited'] = serialize($visited_session_ar);
	}

	if(!is_object($blog) ){
		include_once($jieqiModules['space']['path'].'/class/blog.php');
		$blog_handler =& JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');
		$blog=$blog_handler->get($id);
	}

	//add hit num
	if(JIEQI_ENABLE_CACHE == 'second'){
	}else{
		$hit_num = $blog->getVar('hit_num') +$add_num;
		$blog->setVar('`hit_num`',$hit_num);
		if($blog_handler->insert($blog) ){
			return true;
		}else{
			jieqi_printfail('add reivews error');
		}
	}

}

?>