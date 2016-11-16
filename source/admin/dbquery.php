<?php 
/**
 * 数据库升级
 *
 * 直接执行sql代码
 * 
 * 调用模板：/templates/admin/dbquery.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: dbquery.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
jieqi_checklogin();
jieqi_loadlang('database', JIEQI_MODULE_NAME);
if($jieqiUsersStatus != JIEQI_GROUP_ADMIN) jieqi_printfail(LANG_NEED_ADMIN);
@set_time_limit(3600);
@session_write_close();
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
if(isset($_POST['action']) && $_POST['action']=='execute'){
	if(empty($_POST['sqldata'])) jieqi_printfail($jieqiLang['system']['need_sql_data']);
	elseif(preg_match('/(into\s+outfile|load_file\s*\([^\)]*\)|load\s+data)/is', $_POST['sqldata'])) jieqi_printfail($jieqiLang['system']['deny_sql_data']);
	jieqi_includedb();
	$db_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');

	jieqi_splitsqlfile($sqlary, str_replace(' jieqi_', ' '.JIEQI_DB_PREFIX.'_', $_POST['sqldata']));

	$sqlerr=array();
	foreach($sqlary as $v){
		$v=trim($v);
		if(!empty($v) and strlen($v)>5){
			$retflag=$db_query->execute($v);
			if(!$retflag){
				$sqlerr[]=array('sql'=>$v, 'error'=>$db_query->db->error());
				if($_POST['errorstop']){
					jieqi_printfail(sprintf($jieqiLang['system']['print_sql_error'], jieqi_htmlstr($v), jieqi_htmlstr($db_query->db->error())));
					break;
				}
			}
		}
	}
	if(!empty($sqlerr) && $_POST['showerror']){
		$errorinfo='';
		foreach($sqlerr as $v){
			$errorinfo.=sprintf($jieqiLang['system']['show_error_format'], jieqi_htmlstr($v['sql']), jieqi_htmlstr($v['error']));
		}
		jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['system']['sql_some_error'], $errorinfo));
	}else{
		jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['execute_sql_success']);
	}
}else{
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/dbquery.html';
}
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');


function jieqi_splitsqlfile(&$ret, $sql, $release=32270){
    //$sql          = trim($sql);
	$sql          = rtrim($sql, "\n\r");
    $sql_len      = strlen($sql);
    $char         = '';
    $string_start = '';
    $in_string    = FALSE;

    for ($i = 0; $i < $sql_len; ++$i) {
        $char = $sql[$i];

        // We are in a string, check for not escaped end of strings except for
        // backquotes that can't be escaped
        if ($in_string) {
            for (;;) {
                $i         = strpos($sql, $string_start, $i);
                // No end of string found -> add the current substring to the
                // returned array
                if (!$i) {
                    $ret[] = $sql;
                    return TRUE;
                }
                // Backquotes or no backslashes before quotes: it's indeed the
                // end of the string -> exit the loop
                else if ($string_start == '`' || $sql[$i-1] != '\\') {
                    $string_start      = '';
                    $in_string         = FALSE;
                    break;
                }
                // one or more Backslashes before the presumed end of string...
                else {
                    // ... first checks for escaped backslashes
                    $j                     = 2;
                    $escaped_backslash     = FALSE;
                    while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                        $escaped_backslash = !$escaped_backslash;
                        $j++;
                    }
                    // ... if escaped backslashes: it's really the end of the
                    // string -> exit the loop
                    if ($escaped_backslash) {
                        $string_start  = '';
                        $in_string     = FALSE;
                        break;
                    }
                    // ... else loop
                    else {
                        $i++;
                    }
                } // end if...elseif...else
            } // end for
        } // end if (in string)

        // We are not in a string, first check for delimiter...
        else if ($char == ';') {
            // if delimiter found, add the parsed part to the returned array
            $ret[]      = substr($sql, 0, $i);
            $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
            $sql_len    = strlen($sql);
            if ($sql_len) {
                $i      = -1;
            } else {
                // The submited statement(s) end(s) here
                return TRUE;
            }
        } // end else if (is delimiter)

        // ... then check for start of a string,...
        else if (($char == '"') || ($char == '\'') || ($char == '`')) {
            $in_string    = TRUE;
            $string_start = $char;
        } // end else if (is start of string)

        // ... for start of a comment (and remove this comment if found)...
		else if ($char == '#' || ($char == '-' && $i > 0 && $sql[$i-1] == '-')) {
            // starting position of the comment depends on the comment type
            $start_of_comment = (($sql[$i] == '#') ? $i : $i-1);
            // if no "\n" exits in the remaining string, checks for "\r"
            // (Mac eol style)
            $end_of_comment   = (strpos(' ' . $sql, "\012", $i+1))
                              ? strpos(' ' . $sql, "\012", $i+1)
                              : strpos(' ' . $sql, "\015", $i+1);
            if (!$end_of_comment) {
                // no eol found after '#', add the parsed part to the returned
                // array if required and exit
                if ($start_of_comment > 0) {
                    $ret[]    = trim(substr($sql, 0, $start_of_comment));
                }
                return TRUE;
            } else {
                $sql          = substr($sql, 0, $start_of_comment)
                              . ltrim(substr($sql, $end_of_comment));
                $sql_len      = strlen($sql);
                $i--;
            } // end if...else
        } // end else if (is comment)

        // ... and finally disactivate the "/*!...*/" syntax if MySQL < 3.22.07
        else if ($release < 32270
                 && ($char == '!' && $i > 1  && $sql[$i-2] . $sql[$i-1] == '/*')) {
            $sql[$i] = ' ';
        } // end else if
    } // end for

    // add any rest to the returned array
    if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
        $ret[] = $sql;
    }

    return TRUE;
}

?>