<?php
/**
 * 数据表类(jieqi_article_articlelog - 文章管理日志表)
 *
 * 数据表类(jieqi_article_articlelog - 文章管理日志表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: articlelog.php 312 2008-12-29 05:30:54Z juny $
 */

jieqi_includedb();
//用户管理日志
class JieqiArticlelog extends JieqiObjectData
{ 
    //构建函数
    function JieqiArticlelog()
    {
        $this->JieqiObjectData();
        $this->initVar('logid', JIEQI_TYPE_INT, 0, '日志序号', false, 11);
        $this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
        $this->initVar('logtime', JIEQI_TYPE_INT, 0, '操作时间', false, 11);
        $this->initVar('userid', JIEQI_TYPE_INT, 0, '操作者id', false, 11);
        $this->initVar('username', JIEQI_TYPE_TXTBOX, '', '操作者', false, 30);
        $this->initVar('articleid', JIEQI_TYPE_INT, 0, '文章序号', false, 11);
        $this->initVar('articlename', JIEQI_TYPE_TXTBOX, '', '文章名', false, 255);
        $this->initVar('chapterid', JIEQI_TYPE_INT, 0, '章节序号', false, 11);
        $this->initVar('chaptername', JIEQI_TYPE_TXTBOX, '', '章节名', false, 255);
        $this->initVar('reason', JIEQI_TYPE_TXTAREA, '', '修改原因', false, NULL);
        $this->initVar('chginfo', JIEQI_TYPE_TXTAREA, '', '修改描述', false, NULL);
        $this->initVar('chglog', JIEQI_TYPE_TXTAREA, '', '修改记录', false, NULL);
        $this->initVar('ischapter', JIEQI_TYPE_INT, 0, '是否章节', false, 1);
        $this->initVar('isdel', JIEQI_TYPE_INT, 0, '是否删除', false, 1);
        $this->initVar('databak', JIEQI_TYPE_TXTAREA, '', '信息备份', false, NULL);
    }
}


//------------------------------------------------------------------------
//------------------------------------------------------------------------

//用户句柄
class JieqiArticlelogHandler extends JieqiObjectHandler
{
    function JieqiArticlelogHandler($db='')
    {
        $this->JieqiObjectHandler($db);
        $this->basename='articlelog';
        $this->autoid='logid';
        $this->dbname='article_articlelog';
    }
}
?>