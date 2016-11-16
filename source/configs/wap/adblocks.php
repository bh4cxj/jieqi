<?php
/*
/blocks/block_fileget.php 用于获取程序程序输出的内容作为区块，vars里面有三个参数用英文逗号分开
第一个参数是调用文件名，如果是本地程序，用网站的相对路径，如：ad/ad.php（前面不要加 / ），如果是远程url，则用完整的url地址，如：http://www.domain.com/ad/ad.php
第二个参数是指本地缓存时间，单位是秒，-1表示不缓存； 0表示用系统默认缓存； >0则自定义缓存时间，单位是秒
第三个参数是指获取的内容编码，可以是 gbk/big5/utf-8，留空表示和系统编码相同
*/
$jieqiBlocks[]=array('bid'=>0, 'blockname'=>'google广告', 'module'=>'system', 'filename'=>'block_fileget', 'classname'=>'BlockSystemFileget', 'side'=>0, 'title'=>'google广告', 'vars'=>'wap/ad/google.php,3600,utf-8', 'template'=>'', 'contenttype'=>4, 'custom'=>0, 'publish'=>3, 'hasvars'=>1);

$jieqiBlocks[]=array('bid'=>0, 'blockname'=>'yeahwap广告', 'module'=>'system', 'filename'=>'block_fileget', 'classname'=>'BlockSystemFileget', 'side'=>0, 'title'=>'yeahwap广告', 'vars'=>'wap/ad/yeahwap.php,3600,utf-8', 'template'=>'', 'contenttype'=>4, 'custom'=>0, 'publish'=>3, 'hasvars'=>1);

$jieqiBlocks[]=array('bid'=>0, 'blockname'=>'随机广告', 'module'=>'system', 'filename'=>'block_fileget', 'classname'=>'BlockSystemFileget', 'side'=>0, 'title'=>'随机广告', 'vars'=>'wap/ad/randad.php,0,gbk', 'template'=>'', 'contenttype'=>4, 'custom'=>0, 'publish'=>3, 'hasvars'=>1);
?>