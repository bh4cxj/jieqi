<?php 
//showtype 位运算，1111分别表示 网站所有页、模块所有页、模块首页、网站首页
$jieqiBlocks[]=array('bid'=>31, 'blockname'=>'新闻搜索', 'module'=>'news', 'filename'=>'block_search', 'classname'=>'BlockNewsSearch', 'side'=>0, 'title'=>'新闻搜索', 'vars'=>'', 'template'=>'', 'contenttype'=>0, 'custom'=>0, 'publish'=>3, 'hasvars'=>0);

$jieqiBlocks[]=array('bid'=>32, 'blockname'=>'新闻类别列表', 'module'=>'news', 'filename'=>'block_newsclass', 'classname'=>'BlockNewsClass', 'side'=>0, 'title'=>'新闻类别', 'vars'=>'16,5', 'template'=>'block_newsclass.html', 'contenttype'=>1, 'custom'=>0, 'publish'=>3, 'hasvars'=>1);

$jieqiBlocks[]=array('bid'=>33, 'blockname'=>'最新新闻列表', 'module'=>'news', 'filename'=>'block_newsupdatelist', 'classname'=>'BlockNewsUpdateList', 'side'=>0, 'title'=>'最新新闻', 'vars'=>'0,0,10,36', 'template'=>'block_newsupdatelist.html', 'contenttype'=>1, 'custom'=>0, 'publish'=>3, 'hasvars'=>1);
?>