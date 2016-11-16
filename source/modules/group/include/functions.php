<?php
//根据字节数取得文件的大小	
function sizeCount($byte){
	if($byte >= 1073741824)
	{
		$temp = round($byte / 1073741824, 2) . " GB";
	}
	elseif($byte >= 1048576)
	{
		$temp = round($byte / 1048576, 2) . " MB";
	}
	elseif($byte >= 1024)
	{
		$temp = round($byte / 1024, 2) . " KB";
	}
	else
	{
		$byte = $byte?$byte:0;
		$temp = $byte . " bytes";
	}
	return $temp;
}


//计算文件夹或者文件的大小
function getSize($path){
	if(!is_dir($path)) return @filesize($path);
	if ($handle = @opendir($path)) {
		$size = 0;
		while (false !== ($file = @readdir($handle))) {
			if($file!='.' && $file!='..'){
				$size += @filesize($path.'/'.$file);
				$size += $this->getSize($path.'/'.$file);
			}
		}
		@closedir($handle);
		return $size;
	}
}
	

//检查上传文件是不是图片
function checkType($pictype)
{
	$typeList =  "image/pjpeg,image/jpg,image/jpeg,image/gif,image/x-png,image/png";
	$mime = explode(",", $typeList);
	$is_vaild = 0;
	foreach ($mime as $type)
	{
		if($pictype == $type){$is_vaild = 1;}
	}
	return $is_vaild;
}

function checkPic($pic,$prosize=10000000){
	if(!checkType( $pic['type'] ) ){
		$error .= $pic['name'].' is not a image<br /> ';
	}
	
	if($pic['size'] > 2097152){
		$error .= $pic['name'].' is too big <br />';
	}

	if(!is_uploaded_file($pic['tmp_name']) ){
		$error .= $pic['name'].' is not valid <br />';
	}
	if($error){
		return $error;
	}else{
		return 'ok';
	}
}
	
//生成缩略图
function image_resize($srcFile,$toFile,$toW="100",$toH="100")
{	
	$res = '';	
	$info = '';
	$data = GetImageSize($srcFile,$info);

	//判断服务器是否支持
	switch ($data[2])
	{
		case 1:
			if(!function_exists("imagecreatefromgif")){
				return '你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式!';
			}
			$im = ImageCreateFromGIF($srcFile);
			break;
		case 2:
			if(!function_exists("imagecreatefromjpeg")){
				return '你的GD库不能使用jpeg格式的图片，请使用其它格式的图片!';
			}
			$im = ImageCreateFromJpeg($srcFile);
			break;
		case 3:
			$im = ImageCreateFromPNG($srcFile);
			break;
	}

	//计算缩略图长和宽
	$srcW=ImageSX($im);
	$srcH=ImageSY($im);
	$toWH=$toW/$toH;
	$srcWH=$srcW/$srcH;
	if($toWH<=$srcWH){
		$ftoW=$toW;
		$ftoH=$ftoW*($srcH/$srcW);
	}
	else{
		$ftoH=$toH;
		$ftoW=$ftoH*($srcW/$srcH);
	}


	//生成缩略图
	if($srcW>$toW||$srcH>$toH)
	{
		if(function_exists("imagecreatetruecolor")){
			@$ni = ImageCreateTrueColor($ftoW,$ftoH);
			if($ni) ImageCopyResampled($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
			else{
				$ni=ImageCreate($ftoW,$ftoH);
				ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
			}
		}else{
			$ni=ImageCreate($ftoW,$ftoH);
			ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
		}

	}

	//判断是否缩略,以及正确引用
	if($ni){
		$outputFile = &$ni;
	}else{
		$outputFile = &$im;
	}
	//保存缩略图
	if(function_exists('imagejpeg')) {
		ImageJpeg($outputFile,$toFile);
	}else {
		ImagePNG($outputFile,$toFile);
	}
	//销毁图片
	if($ni){ImageDestroy($ni);}
	ImageDestroy($im);
	return 1 ;
}
	
	
function copyDir($sourceDir,$targetDir){
		@mkdir($targetDir, 0777);
		$dirHandle = @opendir($sourceDir);
		while (false !== ($file = @readdir($dirHandle))) {
			if($file != ".." and $file != "." and $file != ""){
				if(is_dir($sourceDir.'/'.$file)){
					@mkdir($targetDir.'/'.$file, 0777);
					copyDir($sourceDir.'/'.$file, $targetDir.'/'.$file);
				}else{
					if(!@copy($sourceDir.'/'.$file ,$targetDir.'/'.$file) or !@chmod($targetDir.'/'.$file, 0777)){
						return false;
					}
				}
			}

		}
		closedir($dirHandle);
		return true;
}



function updateginfo($gid){
	global $group_handler;
	//设置工会附件存储目录
	$attachdir = jieqi_uploadpath('userdir', JIEQI_MODULE_NAME);
	if (!file_exists($attachdir)) jieqi_createdir($attachdir, 0777, true);
	$attachdir .= jieqi_getsubdir($gid).'/';
	if (!file_exists($attachdir)) jieqi_createdir($attachdir, 0777, true);			
	$attachdir .= $gid.'/';
	if (!file_exists($attachdir)) jieqi_createdir($attachdir, 0777, true);	
	if (!file_exists($attachdir.'album/')) jieqi_createdir($attachdir.'album/', 0777, true);	
	if (!file_exists($attachdir.'attach/')) jieqi_createdir($attachdir.'attach/', 0777, true);
	if (!file_exists($attachdir.'pic/')) jieqi_createdir($attachdir.'pic/', 0777, true);
	
	if(!$group_handler){
		include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/group.php');
		$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');
	}
	$criteria = new Criteria('gid',$gid );
	$group_handler->queryObjects($criteria);
	$tmp = $group_handler->getRow();
	$string = "<?php \n";
	$string .= '//Don\'t modify me! '.date("Y-m-d H:i:s")."\n";
	foreach ($tmp as $key=>$value) {
		$string .= "\$$key='$value'; \n";
	}
	$string .= '?>';
	jieqi_writefile($attachdir."info.php",$string);
}



function setpower($gid){
	if($uid = $_SESSION['jieqiUserId']){
		if( !isset($_SESSION[$uid][$gid] ) ){
			if(!isset($member_handler) ) {
				include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/member.php');
				$member_handler = JieqimemberHandler::getInstance('JieqimemberHandler');
			}	
			$criteria = new CriteriaCompo(new Criteria('uid',$uid) );
			$criteria->add(new Criteria('gid',$gid ) );
			$member_handler->queryObjects($criteria);
			$tmp = $member_handler->getRow();
			if ($tmp['mswitch'] == 0 && $_SESSION['jieqiUserGroup'] != 2 ) {
				return 0;
			} 	
		        $_SESSION[$uid][$gid] = $tmp['membergid']; 
		}
		$fileid = $_SESSION[$uid][$gid];

		if($_SESSION['jieqiUserGroup'] == 2 ) {
		  	$fileid = 1;
		}
		@include_once(JIEQI_ROOT_PATH.'/configs/'.JIEQI_MODULE_NAME.'/membergroup_'.$fileid.'.php' );
		@include_once(JIEQI_ROOT_PATH.'/configs/'.JIEQI_MODULE_NAME.'/admingroup_'.$fileid.'.php');
	}  
	return $fileid;
}


function update_ginfo($fields,$gid){
	require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/group.php');
	$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');
	$criteria = new Criteria('gid',$gid);
	$group_handler->updatefields($fields,$criteria);
	updateginfo($gid);
}



function user_group($uid,$gid,$act){
    global $groupUserfile;
	if(empty($uid) || empty($gid) ){
		jieqi_printfail('function:user_group $uid or $gid is empty');
	}
	global $jieqiModules;
	include_once($groupUserfile['info']);
	if(empty($user) ){
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		$user=$users_handler->get($uid);
	}	
	$allgroup = unserialize($user->getVar('allgroup','n') );
	switch($act){
		case 'add':
			if(!$_SESSION['group_maingid']){
				$user->setVar('maingid',$gid);
				$user->setVar('maingname',$gname);
				$_SESSION['group_maingid'] = $gid;
				$_SESSION['group_maingname'] = $gname;
			}
			$allgroup[$gid] = $gname;
			$tmpstr = serialize($allgroup);
			$user->setVar('allgroup',$tmpstr);
			break;
		case 'delete':
			unset($allgroup[$gid]);
			$tmpstr = serialize($allgroup);
			if( $user->getVar('maingid')==$gid ){
				$user->setVar('maingid','');
				$user->setVar('maingname','');
			} 
			$user->setVar('allgroup',$tmpstr);
			break;
		case 'setdefault':
			$user->setVar('maingid',$gid);
			$user->setVar('maingname',$gname);
		default:
		    break;
	}
	if($users_handler->insert($user) ){
		return true;
	}else{
		return false;
	}
}


?>