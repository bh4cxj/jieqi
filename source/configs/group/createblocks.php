<?php 
//showtype 位运算，1111分别表示 网站所有页、模块所有页、模块首页、网站首页

$jieqiBlocks[0]=array('bid'=>1, 'blockname'=>'用户登录', 'module'=>'system', 'filename'=>'block_login', 'classname'=>'BlockSystemLogin', 'side'=>0, 'title'=>'用户登录', 'vars'=>'', 'template'=>'', 'contenttype'=>0, 'custom'=>0, 'publish'=>1, 'hasvars'=>0);

$jieqiBlocks[1]=array('bid'=>2, 'blockname'=>'用户状态', 'module'=>'system', 'filename'=>'block_userstatus', 'classname'=>'BlockSystemUserstatus', 'side'=>0, 'title'=>'用户状态', 'vars'=>'', 'template'=>'', 'contenttype'=>4, 'custom'=>0, 'publish'=>2, 'hasvars'=>0);


$jieqiBlocks[4]=array('bid'=>5, 'blockname'=>'我的圈子', 'module'=>'group', 'filename'=>'block_mygroup', 'classname'=>'Blockmygroup', 'side'=>JIEQI_SIDEBLOCK_LEFT, 'title'=>'我的圈子', 'contenttype'=>JIEQI_CONTENT_TXT, 'showtype'=>15, 'custom'=>0, 'publish'=>2);

?>
