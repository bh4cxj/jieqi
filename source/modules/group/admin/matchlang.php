<?php
include_once('../../../global.php');
$file = $_GET['file'];
$filehandle = fopen($file,"r");	
$contents = fread($filehandle, filesize ($file) );
preg_match_all( "([\$]jieqiLang\[[^\[\]]*\]\[[^\[\]]*\])",$contents,$matchs);
if(!empty($matchs[0]) ){
	$string = "<?php \n";	
	foreach($matchs[0] as $value){
		$string .= "$value='';\n";	
	}
	$string .= '?>';
	if(!file_exists('../lang/admin/lang_'.$file) ){
		jieqi_writefile('../lang/admin/lang_'.$file,$string);
		echo  '../lang/admin/lang_'.$file." success";
	}else {
		echo 'this lang exist';	
	}
}

?> 
