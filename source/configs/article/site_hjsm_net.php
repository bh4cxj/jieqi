<?php
$jieqiCollect['siteurl']='http://hjsm.tom.com'; //网址
$jieqiCollect['sitename']='幻剑书盟';  //站名
$jieqiCollect['subarticleid']='';  //文章子序号
$jieqiCollect['subchapterid']='';  //章节子序号
$jieqiCollect['autoclear']=0; //章节无法对应是是否自动清空重新采集
$jieqiCollect['defaultfull']=0; //默认是否全本
$jieqiCollect['referer']=0; //是否发送referer
$jieqiCollect['proxy_host']=''; //代理服务器设置
$jieqiCollect['proxy_port']='';
$jieqiCollect['proxy_user']='';
$jieqiCollect['proxy_pass']='';
//****************************************************************************************
$jieqiCollect['urlarticle']='http://hjsm.tom.com/book.php?book_id=<{articleid}>'; //文章信息页面
//文章标题
$jieqiCollect['articletitle']=array('left'=>'<li id=sm><strong>', 'right'=>'</strong></li>', 'middle'=>'!!!!'); 
//作者
$jieqiCollect['author']=array('left'=>'<li id=zzm><strong>', 'right'=>'</strong></li>', 'middle'=>'!!!!');

//类型
$jieqiCollect['sort']=array('left'=>'<li>分　　类 ', 'right'=>'</li>', 'middle'=>'!!!!');;
//关键字
$jieqiCollect['keyword']='';
//简介
$jieqiCollect['intro']=array('left'=>'<div id=wz>', 'right'=>'</div>!</div>!<div class=an>', 'middle'=>'****');
//封面图片
$jieqiCollect['articleimage']=array('left'=>'<img src="', 'right'=>'" alt="~" width=100 height=150 border=1 align=left>', 'middle'=>'~~~~');
//过滤的封面
$jieqiCollect['filterimage']='http://pic.hjsm.tom.com/cover/cover.jpg';
//文章类型对应id
$jieqiCollect['sortid']=array('奇 幻'=>1, '武 侠'=>2, '都 市'=>3, '灵 异'=>8, '言 情'=>3, '游 戏'=>6, '科 幻'=>7, '历 史'=>4, '军 事'=>4, 'default'=>10);

//****************************************************************************************
$jieqiCollect['urlindex']='http://hjsm.tom.com/volume.php?book_id=<{articleid}>'; //文章目录页面
$jieqiCollect['volume']=array('left'=>'<td><strong>!</strong>&nbsp;<b>', 'right'=>'</b> 【<a href="~">分卷阅读</a>', 'middle'=>'!!!!');
//章节名称
$jieqiCollect['chapter']=array('left'=>'<td><a href=" http://html.hjsm.tom.com/html/book/~"  title="~" >', 'right'=>'</a></td>', 'middle'=>'!!!!');
//章节序号
$jieqiCollect['chapterid']=array('left'=>'<td><a href=" http://html.hjsm.tom.com/html/book/', 'right'=>'.htm "  title="~" >!</a></td>', 'middle'=>'~~~~');


//****************************************************************************************
$jieqiCollect['urlchapter']='http://html.hjsm.tom.com/html/book/<{chapterid}>.htm'; //章节内容页面
//章节内容
$jieqiCollect['content']=array('left'=>'<div id="zw">', 'right'=>'</div>!<div id="ts">', 'middle'=>'****');
$jieqiCollect['contentfilter'] = '<script!></script>
<div!>!</div>
<font!>!</font>
<span!>!</span>';
$jieqiCollect['collectimage']=1; //图片内容是否采集到本地
//页面批量采集
//****************************************************************************************
$jieqiCollect['listcollect'][0]['title']='最近更新'; //采集规则名称
$jieqiCollect['listcollect'][0]['urlpage']='http://hjsm.tom.com/?mod=book&act=moreupdate&type=2&page=<{pageid}>'; //采集地址
$jieqiCollect['listcollect'][0]['articleid']=array('left'=>'<td height="13">&nbsp;<a href="http://hjsm.tom.com/book.php?book_id=', 'right'=>'">', 'middle'=>'$$$$');  //获取文章id规则
$jieqiCollect['listcollect'][0]['articlename']=array('left'=>'<td height="13">&nbsp;<a href="http://hjsm.tom.com/book.php?book_id=$">', 'right'=>'</a></td>', 'middle'=>'!!!!');  //获取文章名称规则
$jieqiCollect['listcollect'][0]['startpageid']='1';  //第一页变量
$jieqiCollect['listcollect'][0]['nextpageid']='++'; //获取下一页变量
$jieqiCollect['listcollect'][0]['maxpagenum']=3;  //最多采集几页
?>