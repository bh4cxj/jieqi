<?php

/*

$jieqiBlocks[]=array('bid'=>1, 'blockname'=>'用户登录', 'module'=>'system', 'filename'=>'block_login', 'classname'=>'BlockSystemLogin', 'side'=>0, 'title'=>'用户登录', 'vars'=>'', 'template'=>'', 'contenttype'=>0, 'custom'=>0, 'publish'=>1, 'hasvars'=>0);

$jieqiBlocks[]=array('bid'=>2, 'blockname'=>'用户状态', 'module'=>'system', 'filename'=>'block_userstatus', 'classname'=>'BlockSystemUserstatus', 'side'=>0, 'title'=>'用户状态', 'vars'=>'', 'template'=>'', 'contenttype'=>4, 'custom'=>0, 'publish'=>2, 'hasvars'=>0);
*/

  $jieqiBlocks[]=array(
	'bid'=>5,
	'blockname'=>'group',
        'module'=>'group',
	'filename'=>'block_groupnav',
	'classname'=>'BlockGroupNav',
	'side'=>JIEQI_SIDEBLOCK_LEFT,
	'title'=>'圈子导航',
	'contenttype'=>JIEQI_CONTENT_TXT, 
	'showtype'=>15,
	'custom'=>0,
	'publish'=>3
);
 $jieqiBlocks[]=array(
	'bid'=>3,
	'blockname'=>'group',
    'module'=>'group',
	'filename'=>'block_groupinfo',
	'classname'=>'BlockGroupInfo',
	'side'=>JIEQI_SIDEBLOCK_LEFT,
	'title'=>'',
	'contenttype'=>JIEQI_CONTENT_TXT, 
	'showtype'=>15,
	'custom'=>0,
	'publish'=>3
);


  $jieqiBlocks[]=array(
	'bid'=>4,
	'blockname'=>'group',
        'module'=>'group',
	'filename'=>'block_groupman',
	'classname'=>'BlockGroupMan',
	'title'=>'圈子设置',
	'side'=>JIEQI_SIDEBLOCK_LEFT,
	'contenttype'=>JIEQI_CONTENT_TXT, 
	'publish'=>3
);







?>
