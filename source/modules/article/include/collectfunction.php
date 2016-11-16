<?php 
/**
 * 文章采集相关函数定义
 *
 * 文章采集相关函数定义
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: collectfunction.php 230 2008-11-27 08:46:07Z juny $
 */

if(!defined('JIEQI_ROOT_PATH')) exit;

//提交的变量转成保存的变量
function jieqi_collectptos($str){
	$str=trim($str);
	$middleary=array('****', '!!!!', '~~~~', '^^^^', '$$$$');
	while(list($k, $v) = each($middleary)){
		if(strpos($str, $v)!==false){
			$tmpary=explode($v, $str);
			return array('left'=>strval($tmpary[0]), 'right'=>strval($tmpary[1]), 'middle'=>$v);
		}
	}
	return $str;
}

//保存的变量转成显示的变量
function jieqi_collectstop($str){
	if(is_array($str))return $str['left'].$str['middle'].$str['right'];
	else return $str;
}

//将内容标记转换成preg标记
function jieqi_collectmtop($str){
	switch($str){
		case '!!!!':
			return '([^\>\<]*)';
			break;
		case '~~~~':
			return '([^\<\>\'"]*)';
			break;
		case '^^^^':
			return '([^\<\>\d]*)';
			break;
		case '$$$$':
			return '([\d]*)';
			break;
		case '****':
		default:
			return '(.*)';
			break;
	}
}

//将定义的采集规则转换成执行的
function jieqi_collectstoe($str){
	if(is_array($str)){
		$pregstr='/'.jieqi_pregconvert($str['left']).jieqi_collectmtop($str['middle']).jieqi_pregconvert($str['right']).'/is';
	}else{
		$pregstr=trim($str);
		if(strlen($pregstr) > 0 && substr($pregstr,0,1) != '/') $pregstr='/'.str_replace(array(' ', '/'), array('\s', '\/'), preg_quote($pregstr)).'/is';
	}
	return $pregstr;
}

//匹配一个结果
function jieqi_cmatchone($pregstr, $source){
	$matches=array();
	preg_match($pregstr, $source, $matches);
	if(!is_array($matches) || count($matches)==0){
		return false;
	}else{
		return $matches[count($matches)-1];
	}
}

// 匹配多个结果
function jieqi_cmatchall($pregstr, $source, $flags=0){
	$matches=array();
	if($flags == PREG_OFFSET_CAPTURE) preg_match_all($pregstr, $source, $matches, PREG_OFFSET_CAPTURE + PREG_SET_ORDER);
	else preg_match_all($pregstr, $source, $matches, PREG_SET_ORDER);
	if(!is_array($matches) || count($matches)==0){
		return false;
	}else{
		$ret=array();
		foreach($matches as $v){
			if(is_array($v)) $ret[]=$v[count($v)-1];
			else $ret[]=$v;
		}
		return $ret;
	}
}

//比较两个章节是否相同
function jieqi_equichapter($chapter1, $chapter2){
	$retfrom=array(' ', '　', '<', '>', '【', '】', '[', ']', '［', '］', '（', '）', '(', ')', 'T', '图');
	if($chapter1 == $chapter2){
		return true;
	}elseif(str_replace($retfrom, '', $chapter1)==str_replace($retfrom, '', $chapter2)){
		return true;
	}else{
		$tmpary1=jieqi_splitchapter($chapter1);
		$tmpary2=jieqi_splitchapter($chapter2);
		if($tmpary1['pnum']==$tmpary2['pnum'] && $tmpary1['cname']==$tmpary2['cname']) return true;
		else return false;
	}
}

//******************************************************************
/*
分解卷名和章节
Array
(
    [vid] => 1 分卷序号
    [vname] =>xxxx 分卷名
    [fcid] => 0 开始章节序号
    [fcname] =>xxxx  开始章节
    [cid] => 17 结束章节序号
    [cname] =>xxxx 结束章节
    [sid] => 1 章节子序号
    [sname] => 章节子名称
    [pnum] => 1001701 合并序号
)
*/
function jieqi_splitchapter($str){
	$ret=array('vid'=>0, 'vname'=>'', 'fcid'=>0, 'fcname'=>'', 'cid'=>0, 'cname'=>'', 'sid'=>0, 'sname'=>'', 'pnum'=>0);
	$numary=array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '零', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '百', '千', '万', '上', '中', '下');
	$vary=array('卷','部','集','篇');
	$cary=array('章', '节', '回');
	$sary=array(')', '）', '＞');
	$aary=array('第', '：', '(', '（');
	$splitary=array_merge($vary,$cary,$sary,$aary);
	$str=trim($str);
	$str=str_replace(array('<', '>'),array('＜', '＞'), $str);
	$str=jieqi_textstr($str);
	$slen=strlen($str);

	//寻找分卷
	$i=0;

	$nstart=0;
	while($i<$slen){
		$tmpstr=$str[$i];
		if (ord($str[$i]) > 0x80 && $i < $slen -1) {
			$tmpstr .= $str[$i+1];
			$cl=2;
		}else{
			$cl=1;
		}
		$i+=$cl;
		if(in_array($tmpstr, $vary)){
			//找到分卷标记
			if($i-$cl == 0){
				//卷标记在前
				$k=$i;
				$numstr='';
				while($k<$slen){
					$tmpstr=$str[$k];
					if(ord($str[$k]) > 0x80 && $k < $slen -1){
						$tmpstr.=$str[$k+1];
						$k++;
					}
					$k++;
					if(in_array($tmpstr, $numary)) $numstr.=$tmpstr;
					elseif($tmpstr==' ');
					else break;
				}
				$ret['vid']=jieqi_numcntoab($numstr);
				$nstart=$k;
			}else{
				//卷标记在后
				$k=$i-$cl;
				$numstr='';
				while($k>0){
					if($k>=2 && ord($str[$k-1]) > 0x80){
						$tmpstr=$str[$k-2].$str[$k-1];
						$k-=2;
					}else{
						$tmpstr=$str[$k-1];
						$k--;
					}
					if(in_array($tmpstr, $numary)) $numstr=$tmpstr.$numstr;
					elseif($tmpstr==' ');
					else break;
				}
				$ret['vid']=jieqi_numcntoab($numstr);
				$nstart=$i;
			}
			break;
		}

	}
	if($i>=$slen) $i=0;
	//*********************************************************
	//检查卷会不会在章后面
	if($i>0){
		$j=0;
		while($j<$i){
			$tmpstr=$str[$j];
			if(ord($str[$j]) > 0x80 && $j < $slen -1) {
				$tmpstr .= $str[$j+1];
				$j++;
			}
			$j++;
			if(in_array($tmpstr, $cary)){
				$i=0;
				$nstart=0;
				$ret['vid']=0;
				break;
			}
		}
	}
	//寻找章节
	while($i<$slen){
		$tmpstr=$str[$i];
		if (ord($str[$i]) > 0x80 && $i < $slen -1) {
			$tmpstr .= $str[$i+1];
			$cl=2;
		}else{
			$cl=1;
		}
		$i+=$cl;

		if(in_array($tmpstr, $cary)){
			//找到章节标记
			$k=$i-$cl;
			$numstr='';
			while($k>$nstart){
				if($k>=2 && ord($str[$k-1]) > 0x80){
					$tmpstr=$str[$k-2].$str[$k-1];
					$j=2;
				}else{
					$tmpstr=$str[$k-1];
					$j=1;
				}
				if(in_array($tmpstr, $numary)) $numstr=$tmpstr.$numstr;
				elseif($tmpstr==' ');
				else break;
				$k-=$j;
			}
			$ret['cid']=jieqi_numcntoab($numstr);


			//查找有没起始章节
			if($tmpstr != '第' && $tmpstr != ' '){
				$k-=$j;
				$numstr='';
				while($k>$nstart){
					if($k>=2 && ord($str[$k-1]) > 0x80){
						$tmpstr=$str[$k-2].$str[$k-1];
						$j=2;
					}else{
						$tmpstr=$str[$k-1];
						$j=1;
					}
					if(in_array($tmpstr, $numary)) $numstr=$tmpstr.$numstr;
					elseif($tmpstr==' ');
					else break;
					$k-=$j;
				}
				if(!empty($numstr)) $ret['fcid']=jieqi_numcntoab($numstr);
			}

			//取分卷名称
			if($k>$nstart) $ret['vname']=jieqi_usefultitle(substr($str,$nstart,$k-$nstart));
			$nstart=$i;
			break;
		}

	}
	if($i>=$slen) $i=0;
	//*********************************************************
	//继续寻找终止章节
	$baki=$i;
	while($i<$slen){
		$tmpstr=$str[$i];
		if (ord($str[$i]) > 0x80 && $i < $slen -1) {
			$tmpstr .= $str[$i+1];
			$cl=2;
		}else{
			$cl=1;
		}
		$i+=$cl;

		if(in_array($tmpstr, $cary)){
			//找到章节标记
			$k=$i-$cl;
			$numstr='';
			while($k>$nstart){
				if($k>=2 && ord($str[$k-1]) > 0x80){
					$tmpstr=$str[$k-2].$str[$k-1];
					$j=2;
				}else{
					$tmpstr=$str[$k-1];
					$j=1;
				}
				if(in_array($tmpstr, $numary)) $numstr=$tmpstr.$numstr;
				elseif($tmpstr==' ');
				else break;
				$k-=$j;
			}
			if(!empty($numstr)){
				$ret['fcid']=$ret['cid'];
				$ret['cid']=jieqi_numcntoab($numstr);
				//取起始章节名称
				if($k>$nstart) $ret['fcname']=jieqi_usefultitle(substr($str,$nstart,$k-$nstart));
			}
			$nstart=$i;
			break;
		}

	}
	if($i>=$slen) $i=$baki;
	//*********************************************************
	//寻找分段
	$k=$slen;
	$tmpstr='';
	while($k>=2 && $k>$nstart){
		if(ord($str[$k-1]) > 0x80){
			$tmpstr=$str[$k-2].$str[$k-1];
			$cl=2;
		}else{
			$tmpstr=$str[$k-1];
			$cl=1;
		}
		$k-=$cl;
		if(in_array($tmpstr, $sary)){
			$numstr='';
			while($k>$i){
				if($k>=2 && ord($str[$k-1]) > 0x80){
					$tmpstr=$str[$k-2].$str[$k-1];
					$k-=2;
				}else{
					$tmpstr=$str[$k-1];
					$k--;
				}
				if(in_array($tmpstr, $numary)) $numstr=$tmpstr.$numstr;
				elseif($tmpstr==' ');
				else break;
			}
			
			if(!empty($numstr)) $ret['sid']=jieqi_numcntoab($numstr);
			else $k=$slen;
			break;
		}
	}
	if($k<=$nstart) $k=$slen;


	//取章节名称
	while($k>$nstart){
		if($k>=2 && ord($str[$k-1]) > 0x80){
			$tmpstr=$str[$k-2].$str[$k-1];
			$j=2;
		}else{
			$tmpstr=$str[$k-1];
			$j=1;
		}
		if(!in_array($tmpstr, $aary)) break;
		$k-=$j;
	}
	if($k>$nstart) $ret['cname']=jieqi_usefultitle(substr($str,$nstart,$k-$nstart));
	
	//分卷序号不能大于100, 正文表示第一卷
	if($ret['vid']>=100) $ret['vid']=0;
	elseif(substr($str,0,5)=='正文 ') $ret['vid']=1;
	//标题是第几章第几节情况
	if($ret['vid']==0 && $ret['cid']>0 && strpos($str,'章')>0 && strpos($str, '节')>0){
		$numstr1=jieqi_getsnumbyid($str, '章');
		$numstr2=jieqi_getsnumbyid($str, '节');
		if(!empty($numstr1) && !empty($numstr2)){
			$ret['vid']=jieqi_numcntoab($numstr1);
			$ret['cid']=jieqi_numcntoab($numstr2);
		}
	}
	//如果章节没有，有分段，把分段作为章节
	if($ret['cid']==0 && $ret['sid']>0){
		$ret['cid']=$ret['sid'];
		$ret['sid']=0;
	}
	//根据其他标志找章节
	if($ret['cid']==0){
		$numstr=jieqi_getsnumbyid($str, array('、', '：', '.', ':', ' '));
		if(!empty($numstr)){
			$ret['cid']=jieqi_numcntoab($numstr);
		}else{
			if(!empty($ret['vid'])){
				$numstr=jieqi_getsnumbyid($str, array('集', '卷'));
				if(!empty($numstr)) $ret['cid']=jieqi_numcntoab($numstr);
			}
		}
	}
	//整个章节名称就是数字的情况
	if($ret['vid']==0 && $ret['cid']==0 && $ret['sid']==0){
		$ret['cid']=jieqi_numcntoab($str);
	}
	//章节序号不能大于10000
	if($ret['cid']>=10000) $ret['cid']=$ret['cid'] % 10000;
	//分段序号不能大于100
	if($ret['sid']>=100) $ret['sid']=$ret['sid'] % 100;
	//求权重
	$ret['pnum']=($ret['vid'] * 1000000) + ($ret['cid'] * 100) + $ret['sid'];
	return $ret;
}

//从字符串中根据标记去左边或者右边的数字部分
function jieqi_getsnumbyid($str, $id, $left=false, $start=0){
	if(is_array($id)) $idary=$id;
	else $idary[]=$id;
	$numstr='';
	$ret='';
	$numary=array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '零', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '百', '千', '万', '上', '中', '下');
	
	$slen=strlen($str);
	$i=$start;
	while($i<$slen){
		$tmpstr=$str[$i];
		if (ord($str[$i]) > 0x80 && $i < $slen -1) {
			$tmpstr .= $str[$i+1];
			$cl=2;
		}else{
			$cl=1;
		}
		$i+=$cl;
		//找到分割标记
		if(in_array($tmpstr, $idary)){
			if($left){
				//标记在前
				$k=$i;
				while($k<$slen){
					$tmpstr=$str[$k];
					if(ord($str[$k]) > 0x80 && $k < $slen -1){
						$tmpstr.=$str[$k+1];
						$k++;
					}
					$k++;
					if(in_array($tmpstr, $numary)) $numstr.=$tmpstr;
					elseif($tmpstr==' ');
					else break;
				}
			}else{
				//标记在后
				$k=$i-$cl;
				$numstr='';
				while($k>0){
					if($k>=2 && ord($str[$k-1]) > 0x80){
						$tmpstr=$str[$k-2].$str[$k-1];
						$k-=2;
					}else{
						$tmpstr=$str[$k-1];
						$k--;
					}
					if(in_array($tmpstr, $numary)) $numstr=$tmpstr.$numstr;
					elseif($tmpstr==' ');
					else break;
				}
			}
			if(!empty($numstr)) break;
		}
	}
	return $numstr;
}

//中文数字转换成阿拉伯数字
function jieqi_numcntoab($str){
	$ret=0;
	$str=trim($str);
	if(is_numeric($str)) $ret=intval($str);
	else{
		$numary=array('0'=>'0', '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '零'=>'0', '一'=>'1', '二'=>'2', '三'=>'3', '四'=>'4', '五'=>'5', '六'=>'6', '七'=>'7', '八'=>'8', '九'=>'9', '上'=>'1', '中'=>'2', '下'=>'3');
		$splitary=array('十'=>1, '百'=>2, '千'=>3, '万'=>4);
		$slen=strlen($str);
		$numstr='';
		$i=$slen-1;
		$minlen=0;
		while($i>=0){
			if($i>0 && ord($str[$i]) > 0x80){
				$tmpstr=$str[$i-1].$str[$i];
				$i--;
			}else{
				$tmpstr=$str[$i];
			}
			$i--;
			if(isset($numary[$tmpstr])){
				$numstr=$numary[$tmpstr].$numstr;
			}elseif(isset($splitary[$tmpstr])){
				if(strlen($numstr) > $splitary[$tmpstr]){
					$numstr=substr($numstr,0,$splitary[$tmpstr]);
				}elseif(strlen($numstr) < $splitary[$tmpstr]){
					$start=strlen($numstr);
					for($j=$start; $j<$splitary[$tmpstr]; $j++) $numstr='0'.$numstr;
				}
				$minlen=$splitary[$tmpstr]+1;
			}else{
				$numstr='0';
				break;
			}
		}
		if(empty($numstr)) $numstr='0';
		if(strlen($numstr) < $minlen){
			$start=strlen($numstr);
			for($j=$start; $j<$minlen-1; $j++) $numstr='0'.$numstr;
			$numstr='1'.$numstr;
		}
		$ret=intval($numstr);
	}
	return $ret;
}

//取章节或者卷的有效部分
function jieqi_usefultitle($str){
	$str=trim($str);
	$sary=array(' ', '第', '：', ':', '~', '～', '-','－');
	$slen=strlen($str);
	$s=0;
	$e=$slen;
	while($s<$slen){
		$tmpstr=$str[$s];
		if(ord($str[$s]) > 0x80 && $s < $slen-1){
			$tmpstr.=$str[$s+1];
			$j=2;
		}else{
			$j=1;
		}
		if(!in_array($tmpstr, $sary)) break;
		$s+=$j;
	}

	while($e>0){
		$tmpstr=$str[$e-1];
		if(ord($str[$e-1]) > 0x80 && $e > 1){
			$tmpstr=$str[$e-2].$tmpstr;
			$j=2;
		}else{
			$j=1;
		}
		if(!in_array($tmpstr, $sary)) break;
		$e-=$j;
	}

	if($e>$s) $ret=substr($str,$s,$e-$s);
	else $ret='';
	return $ret;
}
?>