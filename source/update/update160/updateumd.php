<?php
@set_time_limit(0);
header('Content-type:text/html;charset=gb2312');

if($_GET['confirm'] != 1){
	echo '<font color="red">注意：</font><br>1.6版本里面UMD电子书增加了分卷生成功能，并且保存的目录结构有所变化，本程序用户更新UMD保存目录。<br>需要注意的是本程序仅仅更新之前生成的整本小说UMD，如果您希望新版里面使用分卷生成UMD，则建议直接删除之前的UMD目录，然后到网站后台批量重新生成<br><br><a href="'.basename($_SERVER['PHP_SELF']).'?confirm=1">点击这里开更新UMD目录</a><br><br>';
	exit;
}

echo '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ';
echo '开始更新UMD目录，请耐心等待...<br>';
ob_flush();
flush();
require_once('../../global.php');
jieqi_getconfigs('article', 'configs');
if(empty($jieqiConfigs['article']['umddir'])) $jieqiConfigs['article']['umddir']='umd';
$dirname = jieqi_uploadpath($jieqiConfigs['article']['umddir'], 'article');
$handle = opendir($dirname);
while (($file = @readdir($handle)) !== false) {
	if($file != '.' && $file != '..' && $file != '.svn' && is_dir($dirname . '/' . $file)){
		echo '<br>'.str_replace(JIEQI_ROOT_PATH, '', $dirname . '/' . $file).'<br>';
		ob_flush();
		flush();
		update_umd($dirname . '/' . $file);
	}
}
echo '<br><font color="red">恭喜您，全部数据转换完成！</font>';
function  update_umd($dirname){
	$handle = opendir($dirname);
	while (($file = @readdir($handle)) !== false) {
		if($file != '.' && $file != '..' && $file != '.svn'){
			if(preg_match('/^\d+\.(umd)$/is', $file)){
				$id = intval($file);
				jieqi_checkdir($dirname.'/'.$id, true);
				rename($dirname.'/'.$file, $dirname.'/'.$id.'/'.$file);
				echo '. ';
				ob_flush();
				flush();
			}
		}
	}
	@closedir($handle);
	return true;
}
?>