<?php
@set_time_limit(3600);
header('Content-type:text/html;charset=gb2312');

if($_GET['confirm'] != 1){
	echo '<font color="red">注意：</font><br>本程序用于更新模板和系统配置文件，适合从1.4x版本升级到1.50版。<br>升级前请备份好以下几个目录，并确认这些目录及子目录下内容可写。<br>/configs<br>/themes<br>/templates<br>/modules/article/templates<br>/modules/forum/templates<br><br><a href="'.basename($_SERVER['PHP_SELF']).'?confirm=1">点击这里正在更新模板和配置</a><br><br>';
	exit;
}

include_once '../../configs/define.php';

echo '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ';

echo '<hr>正在更新模板...<br>';
ob_flush();
flush();

//检查系统有哪些区块
$modary=array();
$dirname='../../modules';
if(is_dir($dirname)){
	$handle = @opendir($dirname);
	while ($file = @readdir($handle)) {
		if($file[0] != '.'){
			$modary[]=$file;
		}
	}
	@closedir($handle);
}
$modstr=implode('|',$modary);

//开始更新
//prep_dirhtml('.', 'update_custom');
//更新通用模板
prep_dirhtml('../../templates', 'update_custom');
prep_dirhtml('../../themes', 'update_custom');
if(is_dir('../../modules')){
	$handle = @opendir('../../modules');
	while ($file = @readdir($handle)) {
		if($file[0] != '.' && is_dir('../../modules/'.$file.'/templates')) prep_dirhtml('../../modules/'.$file.'/templates', 'update_custom');
	}
}
//更新theme风格模板
prep_dirhtml('../../themes', 'update_theme');
prep_dirhtml('../../themes', 'update_css', array('.css'));
//对单独的模板分别更新
update_templates();
//更新配置文件
update_configs();


echo '<br><hr><br><font color="blue">程序执行完成，您可以关闭本窗口！</font>';


//遍历目录和文件，执行更新函数
function prep_dirhtml($dirname, $funname, $ftypes=array('.html')){
	if(is_dir($dirname) && function_exists($funname)){
		$handle = @opendir($dirname);
		while ($file = @readdir($handle)) {
			if($file[0] != '.'){
				if (is_dir($dirname.'/'.$file)){
					prep_dirhtml($dirname.'/'.$file, $funname, $ftypes);
				}else{
					$postfix = strrchr(trim(strtolower($file)),".");
					if(in_array($postfix, $ftypes)){
						$fname=$dirname.'/'.$file;
						$funname($fname);
					}
				}
			}
		}
		@closedir($handle);
	}
}

//通用的模板更新函数
function update_custom($fname){
	global $modstr;
	if(is_writable($fname)){
		$data=jieqi_readfile($fname);
		$old_data=$data;
		$repfrom=array(
		'/<table[^<>]*(width="[^"]*")[^<>]*(class="grid")[^<>]*>/isU',
		'/<div class="gridtop">([^<>]*)<\/div>([\r\n\s]*)<table([^<>]*)>/isU',
		'/<table([^<>]*)>([\r\n\s]*)<tr[^<>]*>[\r\n\s]*<td[^<>]*class="title"[^<>]*>([^<>]*)<\/td>[\r\n\s]*<\/tr>/isU',
		'/<td([^<>]*) class="head"([^<>]*)>([^<>]*)<\/td>/isU',
		'/<th([^<>]*)>([^<>]*)<\/td>/isU',
		'/\{\?\$jieqi_usergroup\?\}/isU',
		'/\{\?\$jieqi_url\?\}\/modules\/('.$modstr.')/isU',
		'/\{\?\$('.$modstr.')_dynamic_url\?\}\/modules\/('.$modstr.')/isU',
		'/\{\?\$('.$modstr.')_static_url\?\}\/modules\/('.$modstr.')/isU',
		'/\{\?\$dynamic_url\?\}\/modules\/('.$modstr.')/isU',
		'/\{\?\$static_url\?\}\/modules\/('.$modstr.')/isU',
		'/\{\?\$[^\?\{\}]+\?\}\/userinfo.php/isU',
		'/\{\?\$i\?\}/isU'
		);
		$repto=array(
		'<table \\2 \\1 align="center">',
		'<table\\3>\\2<caption>\\1</caption>',
		'<table\\1>\\2<caption>\\3</caption>',
		'<th\\1\\2>\\3</th>',
		'<th\\1>\\2</th>',
		'{?$jieqi_groupname?}',
		'{?$jieqi_modules[\'\\1\'][\'url\']?}',
		'{?$\\1_dynamic_url?}',
		'{?$\\1_static_url?}',
		'{?$dynamic_url?}',
		'{?$static_url?}',
		'{?$jieqi_user_url?}/userinfo.php',
		'{?$i[\'key\']?}'
		);
		$data=preg_replace($repfrom,$repto,$data);

		//echo jieqi_htmlstr($data);
		if($old_data != $data){
			jieqi_writefile($fname, $data);
			echo '<br>模板 <a href="'.$fname.'">'.substr($fname,2).'</a>  <font color="blue">更新完成！</font><br>';
		}else{
			echo '. ';
		}
	}else{
		echo '<br>模板 <a href="'.$fname.'">'.substr($fname,2).'</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
	}
	ob_flush();
	flush();

}

//更新风格文件
function update_theme($fname){
	global $modstr;
	if(is_writable($fname)){
		$data=jieqi_readfile($fname);
		$old_data=$data;
		$repfrom=array(
		'/\{\?\$jieqi_url\?\}\/modules\/('.$modstr.')\/index\.php\?class/isU',
		'/\{\?\$jieqi_modules\[\'('.$modstr.')\'\]\[\'url\'\]\?\}\/index\.php\?class/isU'
		);
		$repto=array(
		'{?$jieqi_modules[\'\\1\'][\'url\']?}/\\1list.php?class',
		'{?$jieqi_modules[\'\\1\'][\'url\']?}/\\1list.php?class'
		);
		if(strpos($data, 'scripts/common.js') === false){
			$repfrom[]='/<\/head>/isU';
			$repto[]='<script language="javascript" type="text/javascript" src="{?$jieqi_url?}/scripts/common.js"></script>
</head>';
		}
		$data=preg_replace($repfrom,$repto,$data);

		//echo jieqi_htmlstr($data);
		if($old_data != $data){
			jieqi_writefile($fname, $data);
			echo '<br>模板 <a href="'.$fname.'">'.substr($fname,2).'</a>  <font color="blue">更新完成！</font><br>';
		}else{
			echo '. ';
		}
	}else{
		echo '<br>模板 <a href="'.$fname.'">'.substr($fname,2).'</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
	}

	ob_flush();
	flush();
}

//更新css文件
function update_css($fname){
	global $modstr;
	//更新css
	if(strtolower(basename($fname)) == 'style.css'){
		if(is_writable($fname)){
			$data=jieqi_readfile($fname);
			$old_data=$data;

			if(strpos($data, '.hide') === false) $data .= "\r\n".'table.hide, table.hide th, table.hide td{
	border: 0;
}';
			if(strpos($data, '#tips') === false) $data .= "\r\n".'#tips {
	border: 1px solid #a3bee8;
	padding: 3px;
	display: none;
	background: #f0f7ff;
	position: absolute;
}';
			if(strpos($data, '.pagelink') === false) $data .= "\r\n".'.pages{
	padding: 5px 0px;
}
.pagelink{
	border: 1px solid #a3bee8;
	float: right;
	background: #f0f7ff;
	line-height:24px;
	padding:0;
}
.pagelink a, .pagelink strong, .pagelink em, .pagelink kbd, .pagelink a.first, .pagelink a.last, .pagelink a.prev, .pagelink a.next, .pagelink a.pgroup, .pagelink a.ngroup{
	float: left;
	padding: 0 6px;
}
.pagelink a:hover{background-color: #ffffff; }
.pagelink strong{font-weight: bold; color: #ff6600; background: #e9f1f8;}
.pagelink kbd{height:24px; border-left: 1px solid #a3bee8;}
.pagelink em{height:24px; border-right: 1px solid #a3bee8; font-style:normal;}
.pagelink input{border: 1px solid #a3bee8; color: #054e86; margin-top:1px; height: 18px;}';
			if($old_data != $data){
				jieqi_writefile($fname, $data);
				echo '<br>CSS <a href="'.$fname.'">'.substr($fname,2).'</a>  <font color="blue">更新完成！</font><br>';
			}else{
				echo '. ';
			}
		}else{
			echo '<br>CSS <a href="'.$fname.'">'.substr($fname,2).'</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
		}
	}
	ob_flush();
	flush();
}

//针对模板文件名的替换
function update_templates(){
	//系统模块
	$i=0;
	$tmpchange[$i]['tmpfile']=array('templates/inbox.html', 'templates/outbox.html', 'templates/messagedetail.html', 'templates/myfriends.html', 'templates/userdetail.html', 'modules/obook/templates/buylog.html', 'modules/article/templates/bookcase.html');
	$tmpchange[$i]['insert']='{?set jieqi_blocks_module = "system"?}
{?set jieqi_blocks_config = "userblocks"?}
';

	$i++;
	$tmpchange[$i]['tmpfile']=array('templates/topuser.html');
	$tmpchange[$i]['insert']='{?set jieqi_blocks_module = "system"?}
{?set jieqi_blocks_config = "memberblocks"?}
';

	$i++;
	$tmpchange[$i]['tmpfile']=array('modules/obook/templates/obookinfo.html', 'modules/obook/templates/obookcase.html', 'modules/obook/templates/searchresult.html', 'modules/obook/templates/ochapterlist.html', 'modules/obook/templates/obooklist.html', 'modules/obook/templates/obooklist.html');
	$tmpchange[$i]['insert']='{?set jieqi_blocks_module = "obook"?}
{?set jieqi_blocks_config = "guideblocks"?}
';

	$i++;
	$tmpchange[$i]['tmpfile']=array('modules/obook/templates/masterpage.html', 'modules/obook/templates/chapterstat.html', 'modules/obook/templates/chapterbuylog.html');
	$tmpchange[$i]['insert']='{?set jieqi_blocks_module = "obook"?}
{?set jieqi_blocks_config = "authorblocks"?}
';

	$i++;
	$tmpchange[$i]['tmpfile']=array('modules/article/templates/articleinfo.html', 'modules/article/templates/myarticle.html', 'modules/article/templates/searchresult.html', 'modules/article/templates/toplist.html', 'modules/article/templates/articlelist.html');
	$tmpchange[$i]['insert']='{?set jieqi_blocks_module = "article"?}
{?set jieqi_blocks_config = "guideblocks"?}
';

	$i++;
	$tmpchange[$i]['tmpfile']=array('modules/article/templates/draft.html', 'modules/article/templates/masterpage.html', 'modules/article/templates/votearticle.html', 'modules/article/templates/authorpage.html');
	$tmpchange[$i]['insert']='{?set jieqi_blocks_module = "article"?}
{?set jieqi_blocks_config = "authorblocks"?}
';

	//替换模板
	foreach($tmpchange as $v){
		$tmpfiles=array();
		if(is_array($v['tmpfile'])) $tmpfiles=$v['tmpfile'];
		else $tmpfiles[0]=$v['tmpfile'];
		foreach($tmpfiles as $f){
			$filename='../../'.$f;
			if(is_file($filename)){
				$content=jieqi_readfile($filename);
				$fromlen=strlen($content);
				if(strpos($content, 'jieqi_blocks_config')===false){
					$content=$v['insert'].$content;
				}
				if($fromlen != strlen($content)){
					if(is_writable($filename)){
						jieqi_writefile($filename, $content);
						echo '<br>模板<a href="'.$filename.'">'.$f.'</a> <font color="blue">更新完成！</font><br>';
					}else{
						echo '<br>模板<a href="'.$filename.'">'.$f.'</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
					}
					ob_flush();
					flush();
				}else{
					echo '. ';
					ob_flush();
					flush();
				}
			}
		}
	}
}

//针对配置文件替换
function update_configs(){
	//系统模块
	$i=0;
	$tmpchange[$i]['tmpfile']=array('configs/article/adminmenu.php');
	$tmpchange[$i]['repfrom']=array('admin/review.php');
	$tmpchange[$i]['repto']=array('admin/reviews.php');

	//替换模板
	foreach($tmpchange as $v){
		$tmpfiles=array();
		if(is_array($v['tmpfile'])) $tmpfiles=$v['tmpfile'];
		else $tmpfiles[0]=$v['tmpfile'];
		foreach($tmpfiles as $f){
			$filename='../../'.$f;
			if(is_file($filename)){
				$content=jieqi_readfile($filename);
				$fromlen=strlen($content);
				$content=str_replace($v['repfrom'], $v['repto'], $content);
				if($fromlen != strlen($content)){
					if(is_writable($filename)){
						jieqi_writefile($filename, $content);
						echo '配置文件<a href="'.$filename.'">'.$f.'</a> <font color="blue">更新完成！</font><br>';
					}else{
						echo '配置文件<a href="'.$filename.'">'.$f.'</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
					}
					ob_flush();
					flush();
				}
			}
		}
	}

	//**********************************
	$fname='../../configs/adminmenu.php';
	$data=jieqi_readfile($fname);
	if(strpos($data, 'managemodules.php')==false){
		$repstr= '$jieqiAdminmenu[\'system\'][] = array(\'layer\' => \'0\', \'caption\' => \'模块配置管理\', \'command\'=>JIEQI_URL.\'/admin/managemodules.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

		if(is_writable($fname)){
			$data=str_replace('?>', $repstr.'?>', $data);
			jieqi_writefile($fname, $data);
			echo '配置文件 <a href="'.$fname.'">/configs/adminmenu.php</a>  <font color="blue">更新完成！</font><br>';
		}else{
			echo '配置文件 <a href="'.$fname.'">/configs/adminmenu.php</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
		}
		ob_flush();
		flush();
	}

	//**********************************
	$fname='../../configs/article/fullbottom.js';
	$data=jieqi_readfile($fname);
	if(strpos($data, 'imgclickshow')==false){
		$data.='
		//查找所有图片链接
var divimgs = new Array(); 
function imgsearch(){
	var divs = document.getElementsByTagName(\'div\');
	var j = 0;
	for (i=0; i < divs.length; i++){
		if(divs[i].className == \'divimage\'){
			divimgs[j]=divs[i];
			j++;
		}
	}
}

//点击链接显示图片
function imgclickshow(id, url){
	 if(document.getElementById(id).innerHTML.toLowerCase().indexOf(\'<img\') == -1) document.getElementById(id).innerHTML = \'<img src="\' + url + \'" border="0" class="imagecontent" />\';
}

//自动显示图片
function imgautoshow() {
	var documentTop = document.documentElement.scrollTop|| document.body.scrollTop;
	var docHeight = document.documentElement.clientHeight|| document.body.clientHeight;
	for (i=0; i < divimgs.length; i++){
		if(documentTop > divimgs[i].offsetTop - docHeight - docHeight && documentTop < divimgs[i].offsetTop + divimgs[i].offsetHeight  && divimgs[i].innerHTML.toLowerCase().indexOf(\'<img\') == -1){
			divimgs[i].innerHTML = \'<img src="\' + divimgs[i].title + \'" border="0" class="imagecontent" />\';
		}
	}
	setTimeout("imgautoshow()", 300);
}

//内容图片显示处理
function imgcontentinit(){
	imgsearch();
	imgautoshow();
}

//载入图片显示函数
if (document.all){
	window.attachEvent(\'onload\',imgcontentinit);
}else{
	window.addEventListener(\'load\',imgcontentinit,false);
}';

		if(is_writable($fname)){
			jieqi_writefile($fname, $data);
			echo 'JS文件 <a href="'.$fname.'">/configs/article/fullbottom.js</a>  <font color="blue">更新完成！</font><br>';
		}else{
			echo  'JS文件 <a href="'.$fname.'">/configs/article/fullbottom.js</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
		}
	}
}

// 读文件
function jieqi_readfile($file_name){
	if (function_exists("file_get_contents")) {
		return file_get_contents($file_name);
	}else{
		$filenum = @fopen($file_name, "rb");
		@flock($filenum, LOCK_SH);
		$file_data = @fread($filenum, @filesize($file_name));
		@flock($filenum, LOCK_UN);
		@fclose($filenum);
		return $file_data;
	}
}

//写文件
function jieqi_writefile($file_name, &$data, $method = "wb"){
	$filenum = @fopen($file_name, $method);
	if(!$filenum) return false;
	@flock($filenum, LOCK_EX);
	$ret = @fwrite($filenum, $data);
	@flock($filenum, LOCK_UN);
	@fclose($filenum);
	@chmod($file_name, 0777);
	return $ret;
}

//把字符串转换为htm格式
function jieqi_htmlstr($str, $quote_style=ENT_QUOTES){
	$str = htmlspecialchars($str, $quote_style);
	$str = nl2br($str);
	$str = str_replace("  ", "&nbsp;&nbsp;", $str);
	$str = preg_replace("/&amp;#(\d+);/isU", "&#\\1;", $str);
	return $str;
}
?>