<?php
$jieqiConfigs['wap']['wapdeclare'] = '<?xml version="1.0" encoding="utf-8"?><!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">'; //xml顶部内容声明

$jieqiConfigs['wap']['contenttype'] = 'Content-type: text/vnd.wap.wml; charset=utf-8'; //网页头信息

$jieqiConfigs['wap']['waphead'] = '<head><meta http-equiv="Cache-Control" content="max-age=0" /></head>'; //内容头信息声明

$jieqiConfigs['wap']['wapbr'] = '<br/>'; //换行代码

$jieqiConfigs['wap']['denycpvisit'] = '0'; //是否禁止电脑访问 0-不禁止，1-检查header禁止， 2-检查ip禁止， 3-同时检查header和ip禁止
$jieqiConfigs['wap']['denycpinfo'] = '对不起，您需要用手机访问本站！<br/>如你的确是用手机访问，请与本站联系！'; //禁止电脑访问时候提示

?>