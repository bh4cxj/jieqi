<?php
//***************************************************************************
//基本配置
//***************************************************************************
$jieqiCollect['pagecharset'] = ''; //默认编码
$jieqiCollect['referer']=1; //是否发送referer
$jieqiCollect['proxy_host']=''; //代理服务器设置
$jieqiCollect['proxy_port']='';
$jieqiCollect['proxy_user']='';
$jieqiCollect['proxy_pass']='';
$jieqiCollect['loginexpire']=3600; //超时时间
$jieqiCollect['cookiefile']='zxmh_net'; //是否使用cookie,是的话设置cookie文件标准, 如果设置成 abc 那实际cookie文件是 cookie_abc.php
$jieqiCollect['urlsite']='http://www.zxmh.net'; //网址
$jieqiCollect['sitename']='在线漫画';  //站名

//***************************************************************************
//漫画信息采集
//***************************************************************************
$jieqiCollect['urlcartoon']='http://www.zxmh.net/html/book<{cartoonid}>/'; //漫画信息页面
//***************************************************************************
//漫画标题
$jieqiCollect['cartoontitle']=array('left'=>'<div id="pageBody">!<div id="bodyMainWrapper">!<div id="position">您的位置：<a href="~">首页</a> -><a href="!">!</a>->', 'right'=>'</div>', 'middle'=>'!!!!'); 

//作者
$jieqiCollect['author']=array('left'=>'<br />【漫画作者】 ', 'right'=>'<br />', 'middle'=>'!!!!');
//关键字
$jieqiCollect['keyword']='';
//简介
$jieqiCollect['intro']=array('left'=>'<br />【内容简介】 <br />', 'right'=>'<hr>', 'middle'=>'!!!!');
//类型
$jieqiCollect['sort']='';
//封面图片
$jieqiCollect['cartoonimage']=array('left'=>'<div id="table_top"></div><a href="/html/book$/"!><img alt="~"  src="', 'right'=>'"!></a>', 'middle'=>'~~~~');

//过滤的封面
$jieqiCollect['filterimage']='';
//全文标记
$jieqiCollect['fullcartoon']='已完结</h1>';
//漫画类型对应id
$jieqiCollect['sortid']=array('default'=>10);

//***************************************************************************
//漫画目录采集
//***************************************************************************
$jieqiCollect['urlindex']='http://www.zxmh.net/html/book<{cartoonid}>/'; //漫画目录页面
//***************************************************************************
//分卷名称

$jieqiCollect['volume']=array('left'=>'<li><a href="volume$.htm" >', 'right'=>'</a>', 'middle'=>'!!!!');
//分卷序号
$jieqiCollect['volumeid']=array('left'=>'<li><a href="volume', 'right'=>'.htm" >', 'middle'=>'$$$$');

//***************************************************************************
//取得分卷图片数
//***************************************************************************

$jieqiCollect['urlvolumeimages']=array('http://www.zxmh.net/html/book<{cartoonid}>/volume<{volumeid}>.htm', 'http://www.zxmh.net/jspage/<{cartoonid}>/<{volumeid}>.js'); //分卷图片数

//var datas = new Array();
//datas[1] = 'L2VuZC1hL2Fsc3h5L1ZvbF8wMS8=MDAxODYwMjJGLmpwZw==';
//datas[2] = 'L2VuZC1hL2Fsc3h5L1ZvbF8wMS8=MDAyODQwNDJBLmpwZw==';
//datas[3] = 'L2VuZC1hL2Fsc3h5L1ZvbF8wMS8=MDAzODIwNjI1LmpwZw==';
$jieqiCollect['volumeimagenum']=array('left'=>'datas[$] = \'', 'right'=>'\';', 'middle'=>'~~~~'); //解析图片数
$jieqiCollect['vimagematchall']='1'; //匹配多个，然后统计图片数

//***************************************************************************
//取得分卷图片地址
//***************************************************************************

//$jieqiCollect['urlpageimage']=array('http://www.zxmh.net/html/book<{cartoonid}>/volume<{volumeid}>.htm?p=<{pageid}>'); //某页图片地址

//$jieqiCollect['pageimageurl']='/(http[^\|]+)\|http[^\|]+\|\d*\|/is'; //解析图片数(如果不用采集就留空)


//阅读页面
$jieqiCollect['urlpageread']='http://www.zxmh.net/html/book<{cartoonid}>/volume<{volumeid}>.htm?p=<{pageid}>'; 
//根目录页面
$jieqiCollect['urimagepath']='http://www.zxmh.net/jsbook/<{cartoonid}>/pic_path.js';  //修改 by wuqiang 2007.07.25
//var pic_path = 'aHR0cDovL3BpYzQuenhtaDIubmV0';
//根目录解析
$jieqiCollect['colimagepath']=array('left'=>'var pic_path = \'', 'right'=>'\';', 'middle'=>'~~~~');

//图片列表地址
$jieqiCollect['urlvimagelist']='http://www.zxmh.net/jspage/<{cartoonid}>/<{volumeid}>.js'; //分卷图片数
//图片列表解析
$jieqiCollect['colvimagelist']=array('left'=>'datas[$] = \'', 'right'=>'\';', 'middle'=>'~~~~'); //解析图片数


//取得一张图片地址
function jieqi_colimageurl_custom($params=array()){
	global $colary;
	global $jieqiCollect;
	global $col_image_path;
	global $col_volume_images;

	//先访问总的网页，用于记录cookie
	$url=str_replace(array('<{cartoonid}>', '<{volumeid}>', '<{pageid}>'), array($params['fromcid'], $params['fromvid'], $params['frompid']), $jieqiCollect['urlpageread']);
	$res=jieqi_httpcontents($url, $colary);
	//如果根路径不存在，获取路径
	if(!isset($col_image_path[$params['fromcid']])){
		$url=str_replace(array('<{cartoonid}>', '<{volumeid}>', '<{pageid}>'), array($params['fromcid'], $params['fromvid'], $params['frompid']), $jieqiCollect['urimagepath']);
		$res=jieqi_httpcontents($url, $colary);

		$col_image_path[$params['fromcid']]='';
		$pregstr=jieqi_collectstoe($jieqiCollect['colimagepath']);
		if(!empty($pregstr)){
			$matchvar=jieqi_cmatchone($pregstr, $res);
			if(!empty($matchvar)) $col_image_path[$params['fromcid']]=jieqi_cartoon_decode64($matchvar);
		}
	} 

	//如果图片列表不存在，获取图片列表
	if(!isset($col_volume_images[$params['fromvid']])){
		$url=str_replace(array('<{cartoonid}>', '<{volumeid}>', '<{pageid}>'), array($params['fromcid'], $params['fromvid'], $params['frompid']), $jieqiCollect['urlvimagelist']);
		$res=jieqi_httpcontents($url, $colary);

		$col_volume_images[$params['fromvid']]=array();
		$pregstr=jieqi_collectstoe($jieqiCollect['colvimagelist']);
		if(!empty($pregstr)){
			$matchvar=jieqi_cmatchall($pregstr, $res);
			if(!empty($matchvar)) $col_volume_images[$params['fromvid']]=$matchvar;
		}
	}

	//返回网址
	return $col_image_path[$params['fromcid']].jieqi_cartoon_decode64($col_volume_images[$params['fromvid']][$params['frompid']-1]);
}

//自定义base64 decode
function jieqi_cartoon_decode64($input){
	$keystr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	$output = '';
	$i = 0;

	$len=strlen($input);
	if($len % 4 != 0) return '';
	$i=0;
	while($i<$len){
		$enc1=strpos($keystr, $input[$i++]);
		$enc2=strpos($keystr, $input[$i++]);
		$enc3=strpos($keystr, $input[$i++]);
		$enc4=strpos($keystr, $input[$i++]);

		$chr1 = ($enc1 << 2) | ($enc2 >> 4);
		$chr2 = (($enc2 & 15) << 4) | ($enc3 >> 2);
		$chr3 = (($enc3 & 3) << 6) | $enc4;

		$output .= chr($chr1);

		if ($enc3 != 64) {
			$output .= chr($chr2);
		}
		if ($enc4 != 64) {
			$output .= chr($chr3);
		}
	}
	return $output;
}
?>