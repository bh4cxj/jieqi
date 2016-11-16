<?php
/**
 * 点击、推荐、投票类统计函数
 *
 * 点击、推荐、投票类统计函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: funstat.php 286 2008-12-23 03:04:17Z juny $
 */

/**
 * 检查本次点击时候有效的点击
 * 
 * @param      int         $id 点击对象ID
 * @param      string      $vname 点击标志变量名
 * @param      bool        $save 是否记录本次点击（默认是）
 * @access     public
 * @return     bool
 */
function jieqi_visit_valid($id, $vname, $save=true){
	if(!is_numeric($id) || intval($id) <= 0) return false;
	$sname = '';
	if(isset($_SESSION[$vname])) $arysession=unserialize($_SESSION[$vname]);
	else $arysession=array();
	if(!is_array($arysession)) $arysession=array();

	$tmpary=array();
	$arycookie=array();
	if(isset($_COOKIE['jieqiVisitId'])){
		$tmpary = jieqi_strtosary($_COOKIE['jieqiVisitId'], '=', ',');
		if(isset($tmpary[$vname])) $arycookie = explode('|', $tmpary[$vname]);
	}
	if(!is_array($arycookie)) $arycookie=array();
	if(in_array($id,$arysession) || in_array($id,$arycookie)) return false;

	if($save){
		if(!in_array($id,$arysession) && isset($_SESSION)){
			$arysession[]=$id;
			$_SESSION[$vname]=serialize($arysession);
		}
		if(!in_array($id,$arycookie)){
			$arycookie[]=$id;
			$tmpary[$vname] = implode('|', $arycookie);
			setcookie('jieqiVisitId', jieqi_sarytostr($tmpary, '=', ','), JIEQI_NOW_TIME+3600, '/',  JIEQI_COOKIE_DOMAIN, 0);
		}
	}
	return true;
}

/**
 * 返回点击统计数组，支持点击缓存
 * 
 * @param      int         $id 点击对象ID
 * @param      string      $vname 点击标志变量名
 * @param      int         $lastvisit 上一次更新访问统计时间（需要分周月总统计时候用到）
 * @access     public
 * @return     mixed       如果缓存本点击，返回false，否则返回数组，类似array('1'=>array('visitnum'=>2, 'lastvisit'=>'12345678'), '5'=>array('visitnum'=>1, 'lastvisit'=>'12345678')),这里的键值表示记录ID，visitnum表示被访问了几次，lastvisit是最后更新访问统计的时间
 */
function jieqi_visit_ids($id, $vname, $lastvisit=-1){
	if(!is_numeric($id) || intval($id) <= 0) return false;
	if(!preg_match('/^\w+$/is', $vname)) return false;
	$vname = strtolower($vname);
	$ret=array();
	if (JIEQI_ENABLE_CACHE){
		$logfile = JIEQI_CACHE_PATH.'/cachevars/cachevisit/'.$vname.'.php';
		jieqi_checkdir(dirname($logfile), true);
		//启用缓存，百分之一机会更新缓存
		if(rand(1, 100) == 1) {
			$visitary = @file($logfile);
			if($fp = @fopen($logfile, 'w')) @fclose($fp);
			$visitary[] = $lastvisit >= 0 ? $id.'|'.$lastvisit : $id;
			foreach($visitary as $v){
				$v=trim($v);
				$tmpary=explode('|', $v);
				$tmpary[0]=intval($tmpary[0]);
				if(!empty($tmpary[0])){
					if(key_exists($tmpary[0], $ret)) $ret[$tmpary[0]]['visitnum']++;
					else $ret[$tmpary[0]]['visitnum']=1;
					if(isset($tmpary[1])) $ret[$tmpary[0]]['lastvisit']=intval($tmpary[1]);
					else $ret[$tmpary[0]]['lastvisit']=-1;
				}
			}
		}else{
			if($fp = @fopen($logfile, 'a')) {
				@flock($filenum, LOCK_EX);
				if($lastvisit >= 0)	@fwrite($fp, $id.'|'.$lastvisit."\r\n");
				else @fwrite($fp, $id."\r\n");
				@flock($filenum, LOCK_UN);
				@fclose($fp);
				@chmod($logfile, 0777);
			}
		}
	}else{
		$ret[$id]=array('visitnum'=>1, 'lastvisit'=>$lastvisit);
	}
	return empty($ret) ? false : $ret;
}


/**
 * 通用的点击统计处理
 * 
 * 先判断是否重复的点击、然后检查是否缓存点击，然后更新点击数
 * 
 * @param      int         $id 点击对象ID
 * @param      string      $table 点击对象表名
 * @param      string      $fieldstat 点击数的字段名
 * @param      string      $fieldid ID的字段名
 * @param      object      $query 查询对象，不存在会自动创建
 * @param      int         $addnum 每次点击加几个点击数，默认1
 * @access     public
 * @return     bool
 */
function jieqi_visit_stat($id, $table, $fieldstat, $fieldid, $query=NULL, $addnum=1){
	if(jieqi_visit_valid($id, $table)){
		if($ids = jieqi_visit_ids($id, $table)){
			global $query;
			if(!is_a($query, 'JieqiQueryHandler')){
				jieqi_includedb();
				$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
			}
			foreach($ids as $k=>$v){
				$v['visitnum'] = intval($v['visitnum'] * $addnum);
				$sql='UPDATE '.$table.' SET '.$fieldstat.'='.$fieldstat.'+'.$v['visitnum'].' WHERE '.$fieldid.'='.intval($k);
				$query->execute($sql);
			}
		}
		return true;
	}else{
		return false;
	}
}

?>