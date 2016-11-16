<?php 
jieqi_includedb();
include_once(JIEQI_ROOT_PATH.'/class/posts.php');
//书评
class JieqiReplies extends JieqiPosts
{
    //构建函数
    function JieqiReplies()
    {
        $this->JieqiPosts();
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiRepliesHandler extends JieqiObjectHandler
{
	function JieqiRepliesHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='replies';
	    $this->autoid='postid';	
	    $this->dbname='article_replies';
	}
}

?>