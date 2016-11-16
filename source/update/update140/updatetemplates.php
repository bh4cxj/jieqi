<?php
@set_time_limit(3600);
header('Content-type:text/html;charset=gb2312');
if($_GET['confirm'] != 1){
	echo '<font color="red">注意：</font><br>本程序用于更新模板和系统配置文件，适合从JIEQI CMS 1.3X 升级到 1.40 版。<br>升级前请备份好以下几个目录，并确认这些目录及子目录下内容可写。<br>/configs<br>/themes<br>/templates<br>/modules/article/templates<br>/modules/forum/templates<br><br><a href="'.basename($_SERVER['PHP_SELF']).'?confirm=1">点击这里正在更新模板和配置</a><br><br>';
	exit;
}

include_once '../../configs/define.php';

echo '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ';
ob_flush();
flush();

echo '<hr>正在更新网页模板...<br>';
ob_flush();
flush();

//系统模板
$i=0;
$tmpchange[$i]['tmpfile']=array('templates/loginframe.html', 'templates/loginframe1.html', 'templates/blocks/block_login.html', 'templates/blocks/block_userset.html', 'templates/blocks/block_userstatus.html');
$tmpchange[$i]['repfrom']=array('{?$jieqi_url?}/login.php', '{?$jieqi_url?}/logout.php', '{?$jieqi_url?}/register.php', '{?$jieqi_url?}/loginframe.php');
$tmpchange[$i]['repto']=array('{?$jieqi_user_url?}/login.php', '{?$jieqi_user_url?}/logout.php', '{?$jieqi_user_url?}/register.php', '{?$jieqi_user_url?}/loginframe.php');


$i++;
$tmpchange[$i]['tmpfile']='templates/userdetail.html';
$tmpchange[$i]['repfrom']=array('<table width="100%" align="center" cellpadding="0" cellspacing="1" class="grid">
  <tr align="left">
    <td width="30%" class="odd">用户名：</td>
    <td width="70%" class="even">{?$uname?}</td>
  </tr>
  <tr align="left">
    <td class="odd">等级：</td>', '<tr align="left">
    <td class="odd">现有积分：</td>
    <td class="even">{?$score?}</td>
  </tr>
  <tr>
    <td colspan="2" class="foot">VIP信息</td>
  </tr>');
$tmpchange[$i]['repto']=array('<table width="100%" align="center" cellpadding="0" cellspacing="1" class="grid">
  <tr align="left">
    <td class="odd">用户ID：</td>
    <td class="even">{?$uid?}</td>
  </tr>
  <tr align="left">
    <td width="30%" class="odd">用户名：</td>
    <td width="70%" class="even">{?$uname?}</td>
  </tr>
  <tr align="left">
    <td class="odd">昵称：</td>
    <td class="even">{?$name?}</td>
  </tr>
  <tr align="left">
    <td class="odd">等级：</td>', '<tr align="left">
    <td class="odd">现有积分：</td>
    <td class="even">{?$score?}</td>
  </tr>
  <tr align="left">
    <td class="odd">最多好友数：</td>
    <td class="even">{?$system_maxfriends?}</td>
  </tr>
  <tr align="left">
    <td class="odd">信箱最多消息数：</td>
    <td class="even">{?$system_maxmessages?}</td>
  </tr>
  <tr align="left">
    <td class="odd">书架最大收藏量：</td>
    <td class="even">{?$article_maxbookmarks?}</td>
  </tr>
  <tr align="left">
    <td class="odd">每天允许推荐次数：</td>
    <td class="even">{?$article_dayvotes?}</td>
  </tr>
  <tr>
    <td colspan="2" class="foot">VIP信息</td>
  </tr>');


 $i++;
$tmpchange[$i]['tmpfile']='templates/userinfo.html';
$tmpchange[$i]['repfrom']=array('<tr align="left">
    <td width="200" class="odd">用户名：</td>
    <td width="388" class="even">{?$uname?}</td>
  </tr>
  <tr align="left">
    <td class="odd">等级：</td>');
$tmpchange[$i]['repto']=array('<tr align="left">
    <td width="200" class="odd">用户名：</td>
    <td width="388" class="even">{?$uname?}</td>
  </tr>
  <tr align="left">
    <td class="odd">用户ID：</td>
    <td class="even">{?$uid?}</td>
  </tr>
  <tr align="left">
    <td class="odd">昵称：</td>
    <td class="even">{?$name?}</td>
  </tr>
  <tr align="left">
    <td class="odd">等级：</td>');

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
					echo '模板<a href="'.$filename.'">'.$f.'</a> <font color="blue">更新完成！</font><br>';
				}else{
					echo '模板<a href="'.$filename.'">'.$f.'</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
				}
				ob_flush();
				flush();
			}
		}
	}
}

//文章信息页面 增加投票和推荐
$fname='../../modules/article/templates/articleinfo.html';
$data=jieqi_readfile($fname);
if(strpos($data, 'votedo.php')==false){
	if(is_writable($fname)){	
		$pattern = '/(\<span class="hottext"\>本书公告：\<\/span\>\<br \/\>[^\<\>]*\<\/td\>[^\<\>]*\<\/tr\>[^\<\>]*\<\/table\>\<\/td\>[^\<\>]*\<\/tr\>)[^\<\>]*(\<tr\>[^\<\>]*\<td[^\<\>]*\>\<\/td\>[^\<\>]*\<\/tr\>)/i';

		$replacement = '$1
{?if $showvote > 0?}
  <form name="frmvote" method="post" action="{?$jieqi_url?}/modules/article/votedo.php" target="_blank">
  <tr>
    <td bgcolor="#000000" height="1"></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><span class="hottext">作品投票调查：</span></td>
    <td width="50%" align="right">
	  <input name="aid" type="hidden" value="{?$articleid?}" />
	  <input name="vid" type="hidden" value="{?$voteid?}" />
      <input type="submit" name="votepost" value="提交投票" class="button" />&nbsp;
      <input type="button" name="voteshow" value="查看结果" class="button" onclick="window.open(\'{?$jieqi_url?}/modules/article/voteresult.php?id={?$voteid?}\')" />
      &nbsp;</td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td>主题：<strong>{?$votetitle?}</strong></td>
  </tr>
  <tr>
    <td>
	<ul>
		{?section name=i loop=$voteitemrows?}
		<li style="width:49%;float:left;padding:3px;">
		{?if $mulselect == 1?}
		<input name="voteitem[]" type="checkbox" value="{?$voteitemrows[i].id?}" />
		{?else?}
		<input name="voteitem" type="radio" value="{?$voteitemrows[i].id?}" />
		{?/if?}
		{?$voteitemrows[i].item?}
		</li>
		{?/section?}
    </ul>
	</td>
  </tr>
  </form>
  {?/if?}
  {?if $eachlinknum > 0?}
  <tr>
    <td bgcolor="#000000" height="1"></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td class="odd"><span class="hottext">推荐阅读：</span></td>
        </tr>
        <tr>
          <td>
		  {?section name=i loop=$eachlinkrows?}
		  <li style="width:32%;float:left;padding:3px;">《<a href="{?$eachlinkrows[i].url_articleinfo?}" target="_blank">{?$eachlinkrows[i].articlename?}</a>》</li>
		  {?/section?}</td>
        </tr>
    </table></td>
  </tr>
  {?/if?}
$2';

		$data=preg_replace($pattern, $replacement, $data);

		jieqi_writefile($fname, $data);
		echo '文章信息页面模板 <a href="'.$fname.'">/modules/article/templates/articleinfo.html</a>  <font color="blue">更新完成！</font><br>';

	}else{
		echo '文章信息页面模板 <a href="'.$fname.'">/modules/article/templates/articleinfo.html</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
	}
	ob_flush();
	flush();
}

//****************************************
//文章模块菜单
$fname='../../configs/article/adminmenu.php';
$data=jieqi_readfile($fname);
$printstr='';
if(strpos($data, 'articlelog.php')==false){
	$repstr= '$jieqiAdminmenu[\'article\'][] = array(\'layer\' => \'0\', \'caption\' => \'文章删除日志\', \'command\'=>JIEQI_URL.\'/modules/article/admin/articlelog.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

	if(is_writable($fname)){
		$data=str_replace('?>', $repstr.'?>', $data);
		jieqi_writefile($fname, $data);
		$printstr='小说后台菜单配置文件 <a href="'.$fname.'">/configs/article/adminmenu.php</a>  <font color="blue">更新完成！</font><br>';

	}else{
		$printstr='小说后台菜单配置文件 <a href="'.$fname.'">/configs/article/adminmenu.php</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
	}
}

if(strpos($data, 'applylist.php')==false){
	$repstr= '$jieqiAdminmenu[\'article\'][] = array(\'layer\' => \'0\', \'caption\' => \'作家申请记录\', \'command\'=>JIEQI_URL.\'/modules/article/admin/applylist.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

	if(is_writable($fname)){
		$data=str_replace('?>', $repstr.'?>', $data);
		jieqi_writefile($fname, $data);
		$printstr='小说后台菜单配置文件 <a href="'.$fname.'">/configs/article/adminmenu.php</a>  <font color="blue">更新完成！</font><br>';

	}else{
		$printstr='小说后台菜单配置文件 <a href="'.$fname.'">/configs/article/adminmenu.php</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
	}
}

echo $printstr;
ob_flush();
flush();


echo '<br><hr><br><font color="blue">程序执行完成，您可以关闭本窗口！</font>';



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
?>