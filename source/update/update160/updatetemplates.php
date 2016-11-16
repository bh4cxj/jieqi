<?php
@set_time_limit(3600);
header('Content-type:text/html;charset=gb2312');

if($_GET['confirm'] != 1){
	echo '<font color="red">注意：</font><br>本程序用于更新模板和系统配置文件，适合从1.5x版本升级到1.60版。<br>升级前请备份好以下几个目录，并确认这些目录及子目录下内容可写。<br>/configs<br>/themes<br>/templates<br>/modules/article/templates<br>/modules/forum/templates<br><br><a href="'.basename($_SERVER['PHP_SELF']).'?confirm=1">点击这里开始更新模板和配置</a><br><br>';
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

//更新通用模板

//更新theme风格模板
prep_dirhtml('../../themes', 'update_css', array('.css'));
//更新配置文件
update_configs();
//清空缓存
jieqi_delfolder('../../blockcache', false);
jieqi_delfolder('../../cache', false);
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

//更新css文件
function update_css($fname){
	global $modstr;
	//更新css
	if(strtolower(basename($fname)) == 'style.css'){
		if(is_writable($fname)){
			$data=jieqi_readfile($fname);
			$old_data=$data;

			if(strpos($data, '.ajaxtip') === false) $data .= "\r\n".'.ajaxtip{
	position:absolute;
	border: 1px solid #a3bee8;
	background: #f0f7ff;
	color: #ff0000;
	font-size: 12px;
	line-height:120%;
	padding: 3px;
	z-index:1000;
}';
			if(strpos($data, '#tips') === false) $data .= "\r\n".'#tips {
	border: 1px solid #a3bee8;
	padding: 3px;
	display: none;
	background: #f0f7ff;
	position: absolute;
	z-index: 2000;
}';
			if(strpos($data, '#dialog') === false) $data .= "\r\n".'#dialog{
	position:absolute;
	top:0px;
	left:0px;
	border: 5px solid #8bcee4;
	background: #f1f5fa;
	font-size: 12px;
	line-height:120%;
	padding: 20px 10px 10px 10px;
	visibility: hidden;
}';
			if(strpos($data, '#mask') === false) $data .= "\r\n".'#mask{
	position:absolute;
	top:0px;
	left:0px;
	background: #777777;
	filter: Alpha(opacity=30);
	opacity: 0.3;
}';
			if(strpos($data, '.avatar') === false) $data .= "\r\n".'img.avatar{
	border: 0px;
}';
			if(strpos($data, 'avatars') === false) $data .= "\r\n".'img.avatars{
	border: 1px solid #dddddd;
}';
			
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

//针对配置文件替换
function update_configs(){
	//系统模块
	$i=0;
	$tmpchange[$i]['tmpfile']=array('configs/adminmenu.php', 'configs/article/adminmenu.php', 'configs/forum/adminmenu.php', 'configs/obook/adminmenu.php');
	$tmpchange[$i]['repfrom']=array('\'layer\' => \'0\'', ', \'power\' => JIEQI_GROUP_GUEST', 'JIEQI_TARGET_SELF', '\'publish\' => \'1\'');
	$tmpchange[$i]['repto']=array('\'layer\' => 0', '', '0', '\'publish\' => 1');

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
		$repstr= '$jieqiAdminmenu[\'system\'][] = array(\'layer\' => 0, \'caption\' => \'用户报告\', \'command\'=>JIEQI_URL.\'/admin/reportlist.php\', \'target\' => 0, \'publish\' => 1);'."\r\n";
		if(is_writable($fname)){
			$data=str_replace('?>', $repstr.'?>', $data);
			jieqi_writefile($fname, $data);
			echo '配置文件 <a href="'.$fname.'">'.substr($fname, 5).'</a>  <font color="blue">更新完成！</font><br>';
		}else{
			echo '配置文件 <a href="'.$fname.'">'.substr($fname, 5).'</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
		}
		ob_flush();
		flush();
	}
	
	//**********************************
	$fname='../../configs/adminmenu.php';
	$data=jieqi_readfile($fname);
	if(strpos($data, 'dbmanage.php')==false){
		$repstr= '$jieqiAdminmenu[\'database\'][] = array(\'layer\' => 0, \'caption\' => \'数据库备份\', \'command\'=>JIEQI_URL.\'/admin/dbmanage.php?option=export\', \'target\' => 0, \'publish\' => 1);

$jieqiAdminmenu[\'database\'][] = array(\'layer\' => 0, \'caption\' => \'数据库恢复\', \'command\'=>JIEQI_URL.\'/admin/dbmanage.php?option=import\', \'target\' => 0, \'publish\' => 1);'."\r\n";
		if(is_writable($fname)){
			$data=str_replace('?>', $repstr.'?>', $data);
			jieqi_writefile($fname, $data);
			echo '配置文件 <a href="'.$fname.'">'.substr($fname, 5).'</a>  <font color="blue">更新完成！</font><br>';
		}else{
			echo '配置文件 <a href="'.$fname.'">'.substr($fname, 5).'</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
		}
		ob_flush();
		flush();
	}
	
	//**********************************
	$fname='../../configs/article/adminmenu.php';
	$data=jieqi_readfile($fname);
	if(strpos($data, 'batchclean.php')==false){
		$repstr= '$jieqiAdminmenu[\'article\'][] = array(\'layer\' => 0, \'caption\' => \'文章批量清理\', \'command\'=>$GLOBALS[\'jieqiModules\'][\'article\'][\'url\'].\'/admin/batchclean.php\', \'target\' => 0, \'publish\' => 1);'."\r\n";
		if(is_writable($fname)){
			$data=str_replace('?>', $repstr.'?>', $data);
			jieqi_writefile($fname, $data);
			echo '配置文件 <a href="'.$fname.'">'.substr($fname, 5).'</a>  <font color="blue">更新完成！</font><br>';
		}else{
			echo '配置文件 <a href="'.$fname.'">'.substr($fname, 5).'</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
		}
		ob_flush();
		flush();
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

function jieqi_delfolder($dirname, $flag = true){
	$dirname = trim($dirname);
	$matches = array();
		$handle = @opendir($dirname);
		while (($file = @readdir($handle)) !== false) {
			if($file != '.' && $file != '..'){
				if (is_dir($dirname . DIRECTORY_SEPARATOR . $file)){
					jieqi_delfolder($dirname . DIRECTORY_SEPARATOR . $file, true);
				}else{
					@unlink($dirname . DIRECTORY_SEPARATOR . $file);
				}
			}
		}
		@closedir($handle);
		if ($flag) @rmdir($dirname);
		return true;

}
?>