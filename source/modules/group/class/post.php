<?php 
// $Id: post.php 2004-02-21 $
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------
jieqi_includedb();
class Jieqipost extends JieqiObjectData
{
    //构建函数
    function Jieqipost()
    {
        $this->JieqiObjectData();
        $this->initVar('postid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('topicid', JIEQI_TYPE_INT, 0, '主题序号', false, 11);
        $this->initVar('istopic', JIEQI_TYPE_INT, 0, '是否主题', false, 1);
        $this->initVar('posterid', JIEQI_TYPE_INT, 0, '发表者序号', false, 11);
        $this->initVar('poster', JIEQI_TYPE_TXTBOX, '', '发表者', false, 30);
        $this->initVar('posttime', JIEQI_TYPE_INT, 0, '发表时间', false, 11);
        $this->initVar('posterip', JIEQI_TYPE_TXTBOX, '', '发表者IP', false, 25);
        $this->initVar('enablebbcode', JIEQI_TYPE_INT, 0, '允许代码', false, 1);
        $this->initVar('enablehtml', JIEQI_TYPE_INT, 0, '允许HTML', false, 1);
        $this->initVar('enablesmilies', JIEQI_TYPE_INT, 0, '允许表情', false, 1);
        $this->initVar('enablesig', JIEQI_TYPE_INT, 0, '允许签名', false, 1);
        $this->initVar('edittime', JIEQI_TYPE_INT, 0, '修改时间', false, 11);
        $this->initVar('editinfo', JIEQI_TYPE_TXTBOX, '', '修改者信息', false, 255);
        $this->initVar('iconid', JIEQI_TYPE_INT, 0, '图标', false, 3);
        $this->initVar('attachsinfo', JIEQI_TYPE_TXTAREA, '', '附件信息', false, null);
        $this->initVar('postsubject', JIEQI_TYPE_TXTBOX, '', '主题', false, 60);
        $this->initVar('posttext', JIEQI_TYPE_TXTAREA, '', '内容', true, null);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqipostHandler extends JieqiObjectHandler
{
	function JieqipostHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='post';
	    $this->autoid='postid';	
	    $this->dbname='group_post';
	}
}

?>