<?php
/**
 * 生成ZIP文件类
 *
 * 采用分块读取并逐个打包生成文件的方式，比较节约内存，需要zlib库。
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: zip.php 197 2008-11-25 03:00:05Z juny $
 */

/*
用法
$zip=new JieqiZip(); //建立对象
$zip->addFile('/file/or/pathname'); //添加文件或者路径，添加多个请重复调用本函数
$zip->addData('filename', 'filedata'); //直接添加内容，参数分别是文件名和文件内容
$zip->setComment('comment'); //设置备注
$zip->makezip('zipname.zip'); //压缩并输出ZIP文件

还有一种情况是逐个加入数据压缩的
$zip=new JieqiZip(); //建立对象
$zip->zipstart('zipname.zip'); //建立ZIP文件，准备输出内容
$zip->zipadd('filename', 'filedata'); //添加内容并直接输出到文件，参数分别是文件名和文件内容
$zip->setComment('comment'); //设置备注
$zip->zipend(); //输出zip文件完毕
*/

class JieqiZip
{
	var $zip_fd; //目标文件指针
	var $max_block_file = 1048576; //文件大于多少开始分块读取
	var $read_block_size = 2048; //读文件时候每次几个字节
	var $filelist = array(); //需要打包的文件列表
	var $filedata = array(); //需要打包的文件数据
	var $headerlist = array(); //头信息
	var $comment = ''; //备注

	//增加一个文件或者目录
	function addFile($file){
		if(is_dir($file)){
			if ($file != "." && $file != "..") $this->filelist[]=$file;
			if ($file != ".") $dir = $file.'/';
			else $dir = '';
			$dh = opendir($file);
			while(false !== ($fname = readdir($dh))){
				if ($fname != "." && $fname != ".."){
					if(is_dir($dir.$fname)){
						$this->addfile($dir.$fname);
					}else{
						$this->filelist[]=$dir.$fname;
					}
				}
			}
			closedir($dh);
		}elseif(is_file($file)){
			$this->filelist[]=$file;
		}
	}

	//直接增加文件数据
	function addData($fname, $fdata){
		$this->filedata[$fname]=$fdata;
	}

	//设置备注
	function setComment($comment=''){
		$this->comment=$comment;
	}

	//压缩并输出
	function makezip($zipfile){
		//检查gz压缩函数是否存在
		if(!function_exists('gzopen')) return false;
		//检查文件列表和数据是否为空
		if(empty($this->filelist) && empty($this->filedata)) return false;

		//检查文件是否可写
		$this->zip_fd = @fopen($zipfile, 'wb');
		if(!$this->zip_fd) return false;

		//锁定文件
		@flock($this->zip_fd, LOCK_EX);

		//头部信息
		$v_header_list = array();
		$p_result_list = array();
		$v_header = array();
		$v_nb = count($v_header_list);

		$filenum=count($this->filelist);
		//zip里面保存的路径
		$stored_files=array();
		$root_path='';
		$prevary=array();
		$curary=array();
		$prenum=0;
		$curnum=0;

		for ($i=0; $i<$filenum; $i++) {
			//路径里面“\”改成“/”
			$this->filelist[$i] = $this->translatewinpath($this->filelist[$i], false);
			//路径里面去掉“./”，“../”
			$stored_files[$i] = $this->pathreduction($this->filelist[$i]);
			//相同的根路径过滤掉
			if($i == 0){
				if(is_file($this->filelist[$i])) $root_path=dirname($stored_files[$i]);
				elseif(is_dir($this->filelist[$i])) $root_path=$stored_files[$i];
			}elseif($i > 0 && $root_path != ''){

				$prevary=explode('/',$root_path);
				$prenum=count($prevary);
				$curary=explode('/',$stored_files[$i]);
				$curnum=count($curary);
				$j=0;
				$root_path='';
				while($j<$curnum && $j<$prenum && $curary[$j]==$prevary[$j]){
					if($root_path != '') $root_path.='/';
					$root_path.=$curary[$j];
					$j++;
				}
			}
		}
		$rootlen=strlen($root_path);
		//循环压缩文件
		for ($i=0; $i<$filenum; $i++) {
			//跳过空文件名
			if ($this->filelist[$i] == '') continue;
			//文件不存在也跳过
			if (!file_exists($this->filelist[$i])) continue;
			//zip里面的路径
			if($rootlen > 0 && substr($stored_files[$i],0,$rootlen)==$root_path){
				$stored_files[$i]=substr($stored_files[$i],$rootlen);
				if(substr($stored_files[$i],0,1)=='/') $stored_files[$i]=substr($stored_files[$i],1);
			}
			if($stored_files[$i] == '') continue;
			//增加一个文件
			$this->addfile2zip($this->filelist[$i], $stored_files[$i], $v_header);
			$v_header_list[$v_nb++] = $v_header;
		}

		//循环加入数据
		foreach($this->filedata as $fname=>$fdata){
			//路径里面“\”改成“/”
			$fname = $this->translatewinpath($fname, false);
			//路径里面去掉“./”，“../”
			$fname = $this->pathreduction($fname);
			if($fname == '') continue;
			$this->adddata2zip($fname,$fdata, $v_header);
			$v_header_list[$v_nb++] = $v_header;
		}

		//获取当前文件指针
		$v_offset = @ftell($this->zip_fd);
		//加入文件头信息
		$filenum=count($v_header_list);
		$v_count=0;
		for ($i=0; $i<$filenum; $i++){
			//写头信息
			if ($v_header_list[$i]['status'] == 'ok') {
				$this->writecentralfileheader($v_header_list[$i]);
				$v_count++;
			}
			//头部信息改成文件信息
			$this->header2fileinfo($v_header_list[$i], $p_result_list[$i]);
		}

		//注释
		$v_comment = $this->comment;
		//计算头部信息长度
		$v_size = @ftell($this->zip_fd)-$v_offset;
		//写总的头信息
		$this->writecentralheader($v_count, $v_size, $v_offset, $v_comment);

		//文件解锁关闭返回
		@flock($this->zip_fd, LOCK_UN);
		@fclose($this->zip_fd);
		$this->zip_fd = 0;
		return true;
	}


	//开始建立压缩文件
	function zipstart($zipfile){
		//检查gz压缩函数是否存在
		if(!function_exists('gzopen')) return false;
		//检查文件是否可写
		$this->zip_fd = @fopen($zipfile, 'wb');
		if(!$this->zip_fd) return false;
		//锁定文件
		@flock($this->zip_fd, LOCK_EX);
		$this->headerlist = array();
		return true;
	}

	//添加压缩数据
	function zipadd($fname, $fdata){
		//路径里面“\”改成“/”
		$fname = $this->translatewinpath($fname, false);
		//路径里面去掉“./”，“../”
		$fname = $this->pathreduction($fname);
		if($fname == '') return false;
		$this->adddata2zip($fname, $fdata, $v_header);
		$this->headerlist[] = $v_header;
		return true;
	}

	//最终输出压缩文件
	function zipend(){
		//获取当前文件指针
		$v_offset = @ftell($this->zip_fd);
		//加入文件头信息
		$filenum=count($this->headerlist);
		$v_count=0;
		for ($i=0; $i<$filenum; $i++){
			//写头信息
			if ($this->headerlist[$i]['status'] == 'ok') {
				$this->writecentralfileheader($this->headerlist[$i]);
				$v_count++;
			}
			//头部信息改成文件信息
			$this->header2fileinfo($this->headerlist[$i], $p_result_list[$i]);
		}

		//注释
		$v_comment = $this->comment;
		//计算头部信息长度
		$v_size = @ftell($this->zip_fd)-$v_offset;
		//写总的头信息
		$this->writecentralheader($v_count, $v_size, $v_offset, $v_comment);

		//文件解锁关闭返回
		@flock($this->zip_fd, LOCK_UN);
		@fclose($this->zip_fd);
		$this->zip_fd = 0;
		$this->headerlist = array();
		return true;
	}


	//写文件头信息
	function writefileheader(&$p_header){
		$p_header['offset'] = ftell($this->zip_fd);
		//把UNIX格式时间转换成DOS格式时间
		$v_date = getdate($p_header['mtime']);
		$v_mtime = ($v_date['hours']<<11) + ($v_date['minutes']<<5) + $v_date['seconds']/2;
		$v_mdate = (($v_date['year']-1980)<<9) + ($v_date['mon']<<5) + $v_date['mday'];

		//打包信息
		$v_binary_data = pack("VvvvvvVVVvv", 0x04034b50,
		$p_header['version_extracted'], $p_header['flag'],
		$p_header['compression'], $v_mtime, $v_mdate,
		$p_header['crc'], $p_header['compressed_size'],
		$p_header['size'],
		strlen($p_header['stored_filename']),
		$p_header['extra_len']);

		//写头部30个字节
		fputs($this->zip_fd, $v_binary_data, 30);

		//写文件信息
		if (strlen($p_header['stored_filename']) != 0){
			fputs($this->zip_fd, $p_header['stored_filename'], strlen($p_header['stored_filename']));
		}
		if ($p_header['extra_len'] != 0){
			fputs($this->zip_fd, $p_header['extra'], $p_header['extra_len']);
		}
	}

	//头部信息改成文件信息
	function header2fileinfo($p_header, &$p_info){
		$p_info['filename'] = $p_header['filename'];
		$p_info['stored_filename'] = $p_header['stored_filename'];
		$p_info['size'] = $p_header['size'];
		$p_info['compressed_size'] = $p_header['compressed_size'];
		$p_info['mtime'] = $p_header['mtime'];
		$p_info['comment'] = $p_header['comment'];
		$p_info['folder'] = (($p_header['external']&0x00000010)==0x00000010);
		$p_info['index'] = $p_header['index'];
		$p_info['status'] = $p_header['status'];
	}

	//写总的头信息
	function writecentralheader($p_nb_entries, $p_size, $p_offset, $p_comment){
		$v_binary_data = pack("VvvvvVVv", 0x06054b50, 0, 0, $p_nb_entries,
		$p_nb_entries, $p_size,
		$p_offset, strlen($p_comment));
		//写22个字节头信息
		fputs($this->zip_fd, $v_binary_data, 22);
		//写备注
		if (strlen($p_comment) != 0){
			fputs($this->zip_fd, $p_comment, strlen($p_comment));
		}
	}

	//写文件头信息
	function writecentralfileheader(&$p_header){
		//把UNIX格式时间转换成DOS格式时间
		$v_date = getdate($p_header['mtime']);
		$v_mtime = ($v_date['hours']<<11) + ($v_date['minutes']<<5) + $v_date['seconds']/2;
		$v_mdate = (($v_date['year']-1980)<<9) + ($v_date['mon']<<5) + $v_date['mday'];

		//打包数据
		$v_binary_data = pack("VvvvvvvVVVvvvvvVV", 0x02014b50,
		$p_header['version'], $p_header['version_extracted'],
		$p_header['flag'], $p_header['compression'],
		$v_mtime, $v_mdate, $p_header['crc'],
		$p_header['compressed_size'], $p_header['size'],
		strlen($p_header['stored_filename']),
		$p_header['extra_len'], $p_header['comment_len'],
		$p_header['disk'], $p_header['internal'],
		$p_header['external'], $p_header['offset']);

		//写42字节头信息
		fputs($this->zip_fd, $v_binary_data, 46);

		//写变量
		if (strlen($p_header['stored_filename']) != 0){
			fputs($this->zip_fd, $p_header['stored_filename'], strlen($p_header['stored_filename']));
		}
		if ($p_header['extra_len'] != 0){
			fputs($this->zip_fd, $p_header['extra'], $p_header['extra_len']);
		}
		if ($p_header['comment_len'] != 0){
			fputs($this->zip_fd, $p_header['comment'], $p_header['comment_len']);
		}
	}

	//改变windows格式路径
	function translatewinpath($p_path, $p_remove_disk_letter=true){
		if (stristr(php_uname(), 'win')) {
			//去掉windows路径里面盘符
			if (($p_remove_disk_letter) && (($v_position = strpos($p_path, ':')) != false)) {
				$p_path = substr($p_path, $v_position+1);
			}
			//改路径里面的斜杆
			if ((strpos($p_path, '\\') > 0) || (substr($p_path, 0,1) == '\\')) {
				$p_path = strtr($p_path, '\\', '/');
			}
		}
		return $p_path;
	}

	//增加一个文件并进行压缩
	function addfile2zip($p_filename, $stored_filename, &$p_header){
		//设置文件属性
		clearstatcache();
		$p_header['version'] = 20;
		$p_header['version_extracted'] = 10;
		$p_header['flag'] = 0;
		$p_header['compression'] = 0;
		$p_header['mtime'] = filemtime($p_filename);
		$p_header['crc'] = 0;
		$p_header['compressed_size'] = 0;
		$p_header['size'] = filesize($p_filename);
		$p_header['filename_len'] = strlen($p_filename);
		$p_header['extra_len'] = 0;
		$p_header['comment_len'] = 0;
		$p_header['disk'] = 0;
		$p_header['internal'] = 0;
		$p_header['external'] = (is_file($p_filename)?0x00000000:0x00000010);
		$p_header['offset'] = 0;
		$p_header['filename'] = $p_filename;
		$p_header['stored_filename'] = $stored_filename;
		$p_header['extra'] = '';
		$p_header['comment'] = '';
		$p_header['status'] = 'ok';
		$p_header['index'] = -1;

		//检查实际文件名是否为空
		if ($p_header['stored_filename'] == "") {
			$p_header['status'] = "filtered";
		}

		//检查路径是不是太长
		if (strlen($p_header['stored_filename']) > 0xFF) {
			$p_header['status'] = 'filename_too_long';
		}

		//检查完成
		if ($p_header['status'] == 'ok') {
			if (is_file($p_filename)){
				//是文件的情况
				$v_size = filesize($p_filename);
				if($v_size < $this->max_block_file){
					if (($v_file = @fopen($p_filename, "rb")) == 0) return false;
					//文件比较小，直接整个读取
					//读文件
					$v_content = @fread($v_file, $p_header['size']);
					//计算CRC校验
					$p_header['crc'] = @crc32($v_content);
					//gz压缩
					$v_content_compressed = @gzdeflate($v_content);
					//设置头部参数
					$p_header['compressed_size'] = strlen($v_content_compressed);
					$p_header['compression'] = 8;

					//写头信息
					$this->writefileheader($p_header);
					//写压缩信息
					@fwrite($this->zip_fd, $v_content_compressed, $p_header['compressed_size']);
					//关闭文件
					@fclose($v_file);
				}else{
					//文件比较大，分块读取生成临时文件再加入到压缩文件
					$tmp_gzfile = tempnam('','');
					if (($v_file_compressed = @gzopen($tmp_gzfile, "wb")) == 0) return false;
					if (($v_file = @fopen($p_filename, "rb")) == 0) return false;
					while ($v_size != 0){
						$v_read_size = ($v_size < $this->read_block_size ? $v_size : $this->read_block_size);
						$v_buffer = fread($v_file, $v_read_size);
						$v_binary_data = pack('a'.$v_read_size, $v_buffer);
						@gzputs($v_file_compressed, $v_binary_data, $v_read_size);
						$v_size -= $v_read_size;
					}
					@fclose($v_file);
					@gzclose($v_file_compressed); //临时的gz文件生成完成
					//gz文件太小
					if (filesize($tmp_gzfile) < 18){
						@unlink($tmp_gzfile);
						return false;
					}

					// 打开临时gz文件
					if (($v_file_compressed = @fopen($tmp_gzfile, "rb")) == 0){
						@unlink($tmp_gzfile);
						return false;
					}

					// 读头部信息
					$v_binary_data = @fread($v_file_compressed, 10);
					$v_data_header = unpack('a1id1/a1id2/a1cm/a1flag/Vmtime/a1xfl/a1os', $v_binary_data);
					$v_data_header['os'] = bin2hex($v_data_header['os']);
					@fseek($v_file_compressed, filesize($tmp_gzfile)-8);
					$v_binary_data = @fread($v_file_compressed, 8);
					$v_data_footer = unpack('Vcrc/Vcompressed_size', $v_binary_data);

					//设置需要的头信息
					$p_header['compression'] = ord($v_data_header['cm']);
					$p_header['crc'] = $v_data_footer['crc'];
					$p_header['compressed_size'] = filesize($tmp_gzfile)-18;
					
					
					//写头信息
					$this->writefileheader($p_header);
					//写压缩信息
					@fwrite($this->zip_fd, $v_content_compressed, $p_header['compressed_size']);
					@rewind($v_file_compressed);

					fseek($v_file_compressed, 10);
					$v_size = $p_header['compressed_size'];
					while ($v_size != 0){
						$v_read_size = ($v_size < $this->read_block_size ? $v_size : $this->read_block_size);
						$v_buffer = fread($v_file_compressed, $v_read_size);
						$v_binary_data = pack('a'.$v_read_size, $v_buffer);
						@fwrite($this->zip_fd, $v_binary_data, $v_read_size);
						$v_size -= $v_read_size;
					}
					@fclose($v_file_compressed);
					@unlink($tmp_gzfile);
				}
			}else{
				//是目录的情况
				//找最后的'/'
				if (@substr($p_header['stored_filename'], -1) != '/') {
					$p_header['stored_filename'] .= '/';
				}

				//设置属性
				$p_header['size'] = 0;
				//$p_header['external'] = 0x41FF0010;   // Value for a folder : to be checked
				$p_header['external'] = 0x00000010;   // Value for a folder : to be checked

				//设置头信息
				$this->writefileheader($p_header);
			}
		}
	}

	//增加一个文件并进行压缩
	function adddata2zip($fname, $fdata, &$p_header){
		//设置文件属性
		clearstatcache();
		$p_header['version'] = 20;
		$p_header['version_extracted'] = 10;
		$p_header['flag'] = 0;
		$p_header['compression'] = 0;
		$p_header['mtime'] = time();
		$p_header['crc'] = 0;
		$p_header['compressed_size'] = 0;
		$p_header['size'] = strlen($fdata);
		$p_header['filename_len'] = strlen($fname);
		$p_header['extra_len'] = 0;
		$p_header['comment_len'] = 0;
		$p_header['disk'] = 0;
		$p_header['internal'] = 0;
		$p_header['external'] = (($fdata === false) ? 0x00000010 : 0x00000000);
		$p_header['offset'] = 0;
		$p_header['filename'] = $fname;
		$p_header['stored_filename'] = $fname;
		$p_header['extra'] = '';
		$p_header['comment'] = '';
		$p_header['status'] = 'ok';
		$p_header['index'] = -1;

		//检查实际文件名是否为空
		if ($p_header['stored_filename'] == "") {
			$p_header['status'] = "filtered";
		}

		//检查路径是不是太长
		if (strlen($p_header['stored_filename']) > 0xFF) {
			$p_header['status'] = 'filename_too_long';
		}

		//检查完成
		if ($p_header['status'] == 'ok') {
			if ($fdata !== false){
				//是文件的情况
				//计算CRC校验
				$p_header['crc'] = @crc32($fdata);
				//gz压缩
				$v_content_compressed = @gzdeflate($fdata);
				//设置头部参数
				$p_header['compressed_size'] = strlen($v_content_compressed);
				$p_header['compression'] = 8;
				//写头信息
				$this->writefileheader($p_header);
				//写压缩信息
				@fwrite($this->zip_fd, $v_content_compressed, $p_header['compressed_size']);
			}else{
				//是目录的情况
				//找最后的'/'
				if (@substr($p_header['stored_filename'], -1) != '/') {
					$p_header['stored_filename'] .= '/';
				}
				//设置属性
				$p_header['size'] = 0;
				//$p_header['external'] = 0x41FF0010;   // Value for a folder : to be checked
				$p_header['external'] = 0x00000010;   // Value for a folder : to be checked
				//设置头信息
				$this->writefileheader($p_header);
			}
		}
	}

	//取正式路径 /ab/cd/../ef 改成 /ab/ef
	function pathreduction($p_dir)
	{
		$v_result = "";

		if ($p_dir != "") {
			//分割多级目录
			$v_list = explode("/", $p_dir);
			$v_skip = 0;
			$v_listnum=count($v_list);
			for ($i=$v_listnum-1; $i>=0; $i--) {
				//从最深的目录找起
				if ($v_list[$i] == ".") {
					//忽略
				}else if ($v_list[$i] == "..") {
					$v_skip++;
				}else if ($v_list[$i] == "") {
					//如果是第一个"/"
					if ($i == 0) {
						$v_result = "/".$v_result;
						if ($v_skip > 0) {
							//错误路径，保持不变
							$v_result = $p_dir;
							$v_skip = 0;
						}
					}else if ($i == ($v_listnum-1)){
						//如果是路径里面最后的"/"
						$v_result = $v_list[$i];
					}else {
						//忽略双斜杆"//"
					}
				}else{
					if ($v_skip > 0) {
						$v_skip--;
					}else{
						$v_result = $v_list[$i].($i!=($v_listnum-1)?"/".$v_result:"");
					}
				}
			}
			//查找'../的情况'
			if ($v_skip > 0) {
				while ($v_skip > 0) {
					$v_result = '../'.$v_result;
					$v_skip--;
				}
			}
		}
		return $v_result;
	}
}

?>