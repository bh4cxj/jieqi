<?php
//***************************************************************************
//基本配置
//***************************************************************************
$jieqiCollect['pagecharset'] = 'utf-8'; //默认编码
$jieqiCollect['referer']=1; //是否发送referer
$jieqiCollect['proxy_host']=''; //代理服务器设置
$jieqiCollect['proxy_port']='';
$jieqiCollect['proxy_user']='';
$jieqiCollect['proxy_pass']='';
$jieqiCollect['loginexpire']=3600; //超时时间
$jieqiCollect['cookiefile']='finaleden_com'; //是否使用cookie,是的话设置cookie文件标准, 如果设置成 abc 那实际cookie文件是 cookie_abc.php
$jieqiCollect['urlsite']='http://www.finaleden.com'; //网址
$jieqiCollect['sitename']='你的我的在线漫画';  //站名

//***************************************************************************
//漫画信息采集
//***************************************************************************
$jieqiCollect['urlcartoon']='http://www.finaleden.com/Type.aspx?id=<{cartoonid}>'; //漫画信息页面
//***************************************************************************
//漫画标题
$jieqiCollect['cartoontitle']=array('left'=>'<table width="100%"><tr><td align="center" width="$"><b>', 'right'=>'</b><br /><div id="pic">', 'middle'=>'!!!!'); 
//作者
$jieqiCollect['author']=array('left'=>'</div><span class="Whitblack"> 作者：', 'right'=>' | 状态：<span class="time">', 'middle'=>'!!!!');
//关键字
$jieqiCollect['keyword']='';
//简介
$jieqiCollect['intro']=array('left'=>'<td align="left" width="100%">&nbsp;&nbsp;【内容简介】：</span>', 'right'=>'</td></tr><tr><td>', 'middle'=>'****');
//类型
$jieqiCollect['sort']=array('left'=>'<div id="pic"><img src="', 'right'=>'" class="bk" border="0" alt="~" />', 'middle'=>'!!!!');
//封面图片
$jieqiCollect['cartoonimage']=array('left'=>'<div id="pic"><img src="', 'right'=>'" class="bk" border="0" alt="~" />', 'middle'=>'!!!!');
$jieqiCollect['cartoonimageinurl']=1; //采集漫画封面是否需要先访问封面所在页才能访问（采集）
//过滤的封面
$jieqiCollect['filterimage']='';
//全文标记
$jieqiCollect['fullcartoon']='';
//漫画类型对应id
$jieqiCollect['sortid']=array('武侠格斗'=>1, '浪漫爱情'=>2, '耽美/BL'=>3, '侦探推理'=>4, '经典喜剧'=>5, '魔幻冒险'=>6, '另类恐怖'=>7, '体育竞技'=>8, '战争灾难'=>9,  'default'=>10);

//***************************************************************************
//漫画目录采集
//***************************************************************************
$jieqiCollect['urlindex']='http://www.finaleden.com/Type.aspx?id=<{cartoonid}>'; //漫画目录页面
//$jieqiCollect['indexcharset']='utf8';
//***************************************************************************
//<a href="http://www.finaleden.com/ShowDialog.aspx?id=37674" target="_blank">恶魔圣典_VOL10</a><br><span class="time">2007-8-30 12:40:00</span><br></td>
//<a href="http://www.finaleden.com/ShowDialog.aspx?id=37676" target="_blank">龙狼传_CH161</a></td><td align="center" >
//分卷名称
$jieqiCollect['volume']=array('left'=>'<a href="~ShowDialog.aspx?id=$" target="_blank">', 'right'=>'</a></td>', 'middle'=>'~~~~');
//分卷序号
$jieqiCollect['volumeid']=array('left'=>'<a href="~ShowDialog.aspx?id=', 'right'=>'" target="_blank">!</a></td>', 'middle'=>'$$$$');
$jieqiCollect['reverse'] = 1; //对方网站卷是从大到小排列的，我们采集的时候需要从小到大采集
//***************************************************************************
//取得分卷图片数
//***************************************************************************

//$jieqiCollect['urlvolumeimages']=array('http://www.iieye.com/xml/comicVol/<{cartoonid}>.xml', 'http://www.iieye.com/comic/<{volumeid}>/1/index.html'); //分卷图片数
$jieqiCollect['urlvolumeimages']=array('http://www.finaleden.com/ShowDialog.aspx?id=<{volumeid}>','http://www.finaleden.com/display.aspx?id=<{volumeid}>'); //分卷图片数
//$jieqiCollect['urlvolumeimages']=array('http://www.iieye.com/comic/<{volumeid}>/<{pageid}>/index.html', 'http://www.iieye.com/ct/xmlgetViewTxt.aspx?pi=1'); //分卷图片数

//http://ct.iieye.com/UserCt/comic/15818/104075/eye0001-15544.JPG|http://ct.iieye.com/UserCt/comic/15818/104075/eye0002-20541.JPG|194|NANA[日文版] — Vol_15|开放漫画
$jieqiCollect['volumeimagenum']=array('left'=>'array_img[$] = \'', 'right'=>'\';', 'middle'=>'~~~~'); //解析图片数

$jieqiCollect['vimagematchall']='1'; //匹配多个，然后统计图片数

//***************************************************************************
//取得分卷图片地址
//***************************************************************************

//$jieqiCollect['urlpageimage']=array('http://www.finaleden.com/ShowDialog.aspx?id=<{volumeid}>','http://www.finaleden.com/display.aspx?id=<{volumeid}>'); //某页图片地址
$jieqiCollect['urlvimagelist']='http://www.finaleden.com/ShowDialog.aspx?id=<{volumeid}>'; //某页图片地址
$jieqiCollect['urlvimagelist2']='http://www.finaleden.com/display.aspx?id=<{volumeid}>'; //某页图片地址

//$jieqiCollect['pageimageurl']=array('left'=>'array_img[$] = \'', 'right'=>'\';', 'middle'=>'~~~~'); //解析图片数(如果不用采集就留空)
$jieqiCollect['colvimagelist']=array('left'=>'array_img[$] = \'', 'right'=>'\';', 'middle'=>'~~~~'); //解析图片数(如果不用采集就留空)
//$jieqiCollect['pageimagename']=array('left'=>'<input name="hdCuImgName" id="hdCuImgName" type="hidden" value="', 'right'=>'" />', 'middle'=>'~~~~'); //解析图片数(如果不用采集就留空)

//***************************************************************************
//漫画简介及评论
//***************************************************************************
//$jieqiCollect['urlcartoonxml']='http://www.iieye.com/xml/comicMemo/<{cartoonid}>.xml'; //漫画简介评论页面
//$jieqiCollect['introcharset']='utf8';
//***************************************************************************
//简介
//$jieqiCollect['intro']=array('left'=>'<cTxt><![CDATA[', 'right'=>']]></cTxt>', 'middle'=>'****');



//解码 功能类似Javascript的unescape函数unescape
function jieqi_utf8RawUrlDecode($source) { 
    $decodedStr = ""; 
    $pos = 0; 
    $len = strlen ($source); 
    while ($pos < $len) { 
        $charAt = substr ($source, $pos, 1); 
        if ($charAt == '%') { 
            $pos++; 
            $charAt = substr ($source, $pos, 1); 
            if ($charAt == 'u') { 
                // we got a unicode character 
                $pos++; 
                $unicodeHexVal = substr ($source, $pos, 4); 
                $unicode = hexdec ($unicodeHexVal); 
                $entity = "&#". $unicode . ';'; 
                $decodedStr .= utf8_encode ($entity); 
                $pos += 4; 
            } 
            else { 
                // we have an escaped ascii character 
                $hexVal = substr ($source, $pos, 2); 
                $decodedStr .= chr (hexdec ($hexVal)); 
                $pos += 2; 
            } 
        } else { 
            $decodedStr .= $charAt; 
            $pos++; 
        } 
    } 
    return $decodedStr; 
}

/*
function jieqi_unescape($str) { 
         $str = rawurldecode($str); 
         preg_match_all("/%u.{4}|&#x.{4};|&#d+;|.+/U",$str,$r); 
         $ar = $r[0]; 
         foreach($ar as $k=>$v) { 
                  if(substr($v,0,2) == "%u") 
                           $ar[$k] = iconv("UCS-2","GBK",pack("H4",substr($v,-4))); 
                  elseif(substr($v,0,3) == "&#x") 
                           $ar[$k] = iconv("UCS-2","GBK",pack("H4",substr($v,3,-1))); 
                  elseif(substr($v,0,2) == "&#") { 
                           $ar[$k] = iconv("UCS-2","GBK",pack("n",substr($v,2,-1))); 
                  } 
         } 
         return join("",$ar); 
}
*/

//2007.09.04 Yingxiangjun修改版
function jieqi_unescape($str) { 
         $str = rawurldecode($str); 
         preg_match_all("/%u.{4}|&#x.{4};|&#d+;|.+/U",$str,$r); 
         $ar = $r[0]; 
         foreach($ar as $k=>$v) { 
                  if(substr($v,0,2) == "%u") 
                           $ar[$k] = iconv("UCS-2BE","GBK//IGNORE",pack("H4",substr($v,-4))); 
                  elseif(substr($v,0,3) == "&#x") 
                           $ar[$k] = iconv("UCS-2BE","GBK//IGNORE",pack("H4",substr($v,3,-1))); 
                  elseif(substr($v,0,2) == "&#") { 
                           $ar[$k] = iconv("UCS-2BE","GBK//IGNORE",pack("n",substr($v,2,-1))); 
                  } 
         } 
         return join("",$ar); 
}

function jieqi_colimageurl_custom($params=array()){
	global $colary;
	global $jieqiCollect;
	global $col_image_path;
	global $col_volume_images;

	//先访问总的网页，用于记录cookie
/*	$url=str_replace(array('<{cartoonid}>', '<{volumeid}>', '<{pageid}>'), array($params['fromcid'], $params['fromvid'], $params['frompid']), $jieqiCollect['colvimagelist']);
	$res=jieqi_httpcontents($url, $colary);
	//如果根路径不存在，获取路径
	if(!isset($col_image_path[$params['fromcid']])){
		$url=str_replace(array('<{cartoonid}>', '<{volumeid}>', '<{pageid}>'), array($params['fromcid'], $params['fromvid'], $params['frompid']), $jieqiCollect['urimagepath']);
		$res=jieqi_httpcontents($url, $colary);

		$col_image_path[$params['fromcid']]='';
		$pregstr=jieqi_collectstoe($jieqiCollect['colimagepath']);
		if(!empty($pregstr)){
			$matchvar=jieqi_cmatchone($pregstr, $res);
			if(!empty($matchvar)) $col_image_path[$params['fromcid']]=unescape($matchvar);
		}
	} */

//如果图片列表不存在，获取图片列表
if(!isset($col_volume_images[$params['fromvid']])){
	$colary['charset']='utf-8'; 
	$url=str_replace(array('<{cartoonid}>', '<{volumeid}>'), array($params['fromcid'], $params['fromvid']), $jieqiCollect['urlvimagelist']);
	$res=jieqi_httpcontents($url, $colary);
	$url=str_replace(array('<{cartoonid}>', '<{volumeid}>'), array($params['fromcid'], $params['fromvid']), $jieqiCollect['urlvimagelist2']);
	$res=jieqi_httpcontents($url, $colary);
	$col_volume_images[$params['fromvid']]=array();
	$pregstr=jieqi_collectstoe($jieqiCollect['colvimagelist']);
	if(!empty($pregstr)){
		$matchvar=jieqi_cmatchall($pregstr, $res);
		if(!empty($matchvar)) $col_volume_images[$params['fromvid']]=$matchvar;
	}
}

//返回网址
return jieqi_unescape($col_volume_images[$params['fromvid']][$params['frompid']-1]);
}

//最近更新
$jieqiCollect['listcollect'][0]['title']='最近更新'; //采集规则名称
$jieqiCollect['listcollect'][0]['urlpage']='http://www.finaleden.com/NewComic.aspx?page=<{pageid}>'; //采集地址
$jieqiCollect['listcollect'][0]['cartoonid']=array('left'=>'" /></a></div><a href="Type.aspx?id=', 'right'=>'">~', 'middle'=>'$$$$');  //获取文章id规则
$jieqiCollect['listcollect'][0]['cartoonname']=array('left'=>'" /></a></div><a href="Type.aspx?id=$">', 'right'=>'', 'middle'=>'~~~~');  //获取文章名称规则
$jieqiCollect['listcollect'][0]['nextpageid']=array('left'=>'<a href="NewComic.aspx?page=">', 'right'=>'">', 'middle'=>'!!!!'); //获取下一页变量
$jieqiCollect['listcollect'][0]['startpageid']='';  //第一页变量
$jieqiCollect['listcollect'][0]['maxpagenum']=5;  //最多采集几页


?>