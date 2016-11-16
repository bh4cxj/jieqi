<?php
/**
 * 通用附件类
 *
 * 通用附件类
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: forumattachs.php 326 2009-02-04 00:26:22Z juny $
 */

class JieqiAttachs extends JieqiObjectData
{
    //构建函数
    function JieqiAttachs()
    {       
        $this->JieqiObjectData();
        $this->initVar('attachid', JIEQI_TYPE_INT, 0, '附件序号', false, 11);
        $this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 11);
        $this->initVar('topicid', JIEQI_TYPE_INT, 0, '主题序号', false, 11);
		$this->initVar('postid', JIEQI_TYPE_INT, 0, '帖子序号', false, 11);
		$this->initVar('name', JIEQI_TYPE_TXTBOX, '', '附件名称', true, 100);
		$this->initVar('description', JIEQI_TYPE_TXTBOX, '', '附件描述', true, 100);
        $this->initVar('class', JIEQI_TYPE_TXTBOX, '', '附件类型', true, 30);
		$this->initVar('postfix', JIEQI_TYPE_TXTBOX, '', '附件后缀', true, 30);
		$this->initVar('size', JIEQI_TYPE_INT, 0, '文件大小', false, 10);
		$this->initVar('hits', JIEQI_TYPE_INT, 0, '点击数', false, 8);
		$this->initVar('needperm', JIEQI_TYPE_INT, 0, '需要权限', false, 10);
		$this->initVar('needscore', JIEQI_TYPE_INT, 0, '需要积分', false, 10);
		$this->initVar('needexp', JIEQI_TYPE_INT, 0, '需要经验值', false, 10);
		$this->initVar('needprice', JIEQI_TYPE_INT, 0, '需要价格', false, 10);
		$this->initVar('uptime', JIEQI_TYPE_INT, 0, '上传时间', false, 10);
		$this->initVar('uid', JIEQI_TYPE_INT, 0, '发表用户ID', false, 10);
		$this->initVar('remote', JIEQI_TYPE_INT, 0, '是否远程附件', false, 1);
    }
}