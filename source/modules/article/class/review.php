<?php 
// $Id: review.php 337 2009-03-07 00:51:05Z juny $
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------
jieqi_includedb();
//用户类
class JieqiReview extends JieqiObjectData
{
    //构建函数
    function JieqiReview()
    {
        $this->JieqiObjectData();
        $this->initVar('reviewid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
        $this->initVar('postdate', JIEQI_TYPE_INT, 0, '发表日期', false, 11);
        $this->initVar('articleid', JIEQI_TYPE_INT, 0, '文章序号', true, 11);
        $this->initVar('articlename', JIEQI_TYPE_TXTBOX, '', '文章名称', false, 250);
        $this->initVar('userid', JIEQI_TYPE_INT, 0, '用户序号', false, 11);
        $this->initVar('username', JIEQI_TYPE_TXTBOX, '', '用户名', false, 30);
        $this->initVar('reviewtitle', JIEQI_TYPE_TXTBOX, '', '评论标题', false, 250);
        $this->initVar('reviewtext', JIEQI_TYPE_TXTAREA, '', '评论内容', true, null);
        $this->initVar('topflag', JIEQI_TYPE_INT, 0, '置顶', false, 1);
        $this->initVar('goodflag', JIEQI_TYPE_INT, 0, '精华', false, 1);
        $this->initVar('display', JIEQI_TYPE_INT, 0, '显示', false, 1);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiReviewHandler extends JieqiObjectHandler
{
	function JieqiReviewHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='review';
	    $this->autoid='topicid';	
	    $this->dbname='article_review';
	}
}

?>