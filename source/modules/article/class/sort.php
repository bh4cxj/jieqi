<?php 
/**
 * 数据表类(jieqi_article_sort - 文章分类)
 *
 * 数据表类(jieqi_article_sort - 文章分类)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: sort.php 313 2008-12-31 09:03:22Z juny $
 */

jieqi_includedb();
//用户类
class JieqiArticlesort extends JieqiObjectData
{
    //构建函数
    function JieqiArticlesort()
    {
        $this->JieqiObjectData();
        $this->initVar('sortid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('layer', JIEQI_TYPE_INT, 0, '层次深度', false, 3);
        $this->initVar('weight', JIEQI_TYPE_INT, 0, '序号', false, 6);
        $this->initVar('caption', JIEQI_TYPE_TXTBOX, '', '分类名称', true, 50);
        $this->initVar('shortname', JIEQI_TYPE_TXTBOX, '', '分类简称', false, 20);
        $this->initVar('description', JIEQI_TYPE_TXTAREA, '', '分类描述', false, NULL);
        $this->initVar('imgurl', JIEQI_TYPE_TXTBOX, '', '图片地址', false, 100);
        $this->initVar('authflag', JIEQI_TYPE_INT, 0, '是否检查权限', false, 1);
        $this->initVar('authview', JIEQI_TYPE_TXTBOX, '', '是否可见', false, 255);
        $this->initVar('authread', JIEQI_TYPE_TXTBOX, '', '允许阅读', false, 255);
        $this->initVar('authpost', JIEQI_TYPE_TXTBOX, '', '允许发表', false, 255);
        $this->initVar('authreply', JIEQI_TYPE_TXTBOX, '', '允许回复', false, 255);
        $this->initVar('authupload', JIEQI_TYPE_TXTBOX, '', '允许上传', false, 255);
        $this->initVar('authedit', JIEQI_TYPE_TXTBOX, '', '允许编辑', false, 255);
        $this->initVar('authdelete', JIEQI_TYPE_TXTBOX, '', '允许删除', false, 255);
        $this->initVar('publish', JIEQI_TYPE_INT, 0, '是否发布', false, 1);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiArticlesortHandler extends JieqiObjectHandler
{
	function JieqiArticlesortHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='articlesort';
	    $this->autoid='sortid';	
	    $this->dbname='article_sort';
	}
}

?>