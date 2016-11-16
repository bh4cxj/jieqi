<?php 
/**
 * 数据表类(jieqi_system_pposts - 会客室帖子内容表)
 *
 * 数据表类(jieqi_system_pposts - 会客室帖子内容表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: pposts.php 329 2009-02-07 01:21:38Z juny $
 */

jieqi_includedb();
include_once(JIEQI_ROOT_PATH.'/class/posts.php');
//会客室回复
class JieqiPposts extends JieqiPosts
{
    //构建函数
    function JieqiPposts()
    {
        $this->JieqiPosts();
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiPpostsHandler extends JieqiObjectHandler
{
	function JieqiPpostsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='pposts';
	    $this->autoid='postid';	
	    $this->dbname='system_pposts';
	}
}

?>