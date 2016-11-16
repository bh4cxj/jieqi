<?php
echo '<div id="top" >
<div class="top_inner">
<div class="left orangea"><div style="float:left; height:30px; line-height:30px;"><img src="/themes/chaoliu/images/time.gif"
    align="absmiddle" /> <script type="text/javascript"  src="/themes/chaoliu/js/time.js"></script>&nbsp;  </div>
    <div style="float:right; width:600px;">
		<div class="m_top">
&nbsp;';
if($this->_tpl_vars['jieqi_userid'] == 0){
echo $this->_tpl_vars['jieqi_sitename'].'欢迎您，您可以选择[<a href="'.$this->_tpl_vars['jieqi_user_url'].'/register.php">登录</a>]或者[<a href="'.$this->_tpl_vars['jieqi_user_url'].'/register.php">注册新用户</a>]！';
}else{
echo $this->_tpl_vars['jieqi_sitename'].'欢迎您，'.$this->_tpl_vars['jieqi_username'].'&nbsp; <span style="color:#dbdbdb;">|</span><a href="'.$this->_tpl_vars['jieqi_user_url'].'/userdetail.php" style="margin:0 8px;">个人中心</a><span style="color:#dbdbdb;">|</span><a href="'.$this->_tpl_vars['jieqi_url'].'/modules/article/bookcase.php" style="margin:0 8px;">我的书架</a><span style="color:#dbdbdb;">|</span><a href="'.$this->_tpl_vars['jieqi_url'].'/modules/article/myarticle.php" style="margin:0 8px;">作者专区</a><span style="color:#dbdbdb;">|</span><a href="'.$this->_tpl_vars['jieqi_url'].'/message.php?box=inbox" style="margin:0 8px;">短消息</a><span style="color:#dbdbdb;">|</span><a target="_blank" href="'.$this->_tpl_vars['jieqi_user_url'].'/logout.php" style="margin:0 8px;">退出登录</a>';
}
echo '
</div>
    </div><div class="clear"></div></div>
</div>
</div>
<div id="header">
<div class="header_l">
<a href="/" class="logo pngFix"><img src="/themes/chaoliu/images/logo_06.png" alt="潮流原创"/></a>
</div>
<div class="header_m">
<form id="searchform" name="articlesearch" action="/modules/article/search.php" method="post"  target="_blank">
<input class="search-l" name="searchkey" type="text" value="" /><input name=\'searchtype\' value=\'articlename\' type=\'hidden\'><input  class="search-r" type="image"    src="/themes/chaoliu/images/search_r_10.gif" />
</form>
<p class="remensousuo">热门搜索： '.jieqi_get_block(array('bid'=>'89', 'module'=>'article', 'filename'=>'block_articlelist', 'classname'=>'BlockArticleArticlelist', 'side'=>'0', 'title'=>'', 'vars'=>'allvisit,4,0,0,0,0', 'template'=>'hot_search.html', 'contenttype'=>'4', 'custom'=>'0', 'publish'=>'3', 'hasvars'=>'3'), 1).'</p>
</div>
<div class="header_r">
<a href="/fuli.html" target="_blank" class="pngFix" >
<img src="/themes/chaoliu/images/fuli_03.png" alt=\'作家福利\' /></a>
</div>
<div class="clear"></div>
</div>
<div id="nav">
<ul class="nav_ul">
    <li class="shouye"><a href="/">首页</a></li>
    <li class="np"><a href="/men.php">男频</a></li>
    <li class="nvp"><a href="/women.php">女频</a></li>
    <li class="jingdian"><a href="/jingdian.php">经典</a></li>
    <li class="chaoliuzhi"><a href="/modules/article/articlelist.php?class=23">潮流志</a></li>
    <li class="xiaoshuoph"><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/toplist.php?sort=allvisit">小说排行</a></li>
    <li class="quanbensk"><a href="/modules/article/articlelist.php?fullflag=1">全本书库</a></li>
    <li class="chaoliult"><a href="/bbs">潮流论坛</a></li>
    <li class="chongzhizx"><a href="'.$this->_tpl_vars['jieqi_modules']['pay']['url'].'/buyegold.php">充值中心</a></li>
    <li class="zuozhefuli"><a href="/fuli.html" target="_blank">作者福利</a></li>
    <li class="bangzhu"><a href="/help.html" target="_blank">帮助中心</a></li>
    <div class="clear"></div>
</ul>
</div>
<div class="sub_nav">
    <div class="sub_nav_inner">
        <p style=" float:left;padding-right:43px">男频 >
			<a href="/modules/article/articlelist.php?class=1">玄幻</a>
			<a href="/modules/article/articlelist.php?class=2">奇幻</a>
			<a href="/modules/article/articlelist.php?class=3">仙侠</a>
			<a href="/modules/article/articlelist.php?class=4">武侠</a>
			<a href="/modules/article/articlelist.php?class=5">都市</a>
			<a href="/modules/article/articlelist.php?class=6">异能</a>
			<a href="/modules/article/articlelist.php?class=7">官场</a>
			<a href="/modules/article/articlelist.php?class=8">商战</a>
		</p>
        <p style=" float:left;padding-right:43px">女频 >
			<a href="/modules/article/articlelist.php?class=9" >言情</a>
			<a href="/modules/article/articlelist.php?class=10">校园</a>
			<a href="/modules/article/articlelist.php?class=11">总裁</a>
			<a href="/modules/article/articlelist.php?class=12">同人</a>
			<a href="/modules/article/articlelist.php?class=13">穿越</a>
			<a href="/modules/article/articlelist.php?class=14">宫斗</a>
			<a href="/modules/article/articlelist.php?class=15">女尊</a>
			<a href="/modules/article/articlelist.php?class=16">耽美</a>
		</p>
        <p style=" float:left">经典 >
			<a href="/modules/article/articlelist.php?class=17">历史</a>
			<a href="/modules/article/articlelist.php?class=18">军事</a>
			<a href="/modules/article/articlelist.php?class=19">推理</a>
			<a href="/modules/article/articlelist.php?class=20">科幻</a>
			<a href="/modules/article/articlelist.php?class=21">网游</a>
			<a href="/modules/article/articlelist.php?class=22">惊悚</a>
		</p>
        <div class="clear"></div>
    </div>
</div>';
?>