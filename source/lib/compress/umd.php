<?php
/**
 * 生成UMD文件类
 *
 * 生成UMD文件类，需要zlib库和iconv库
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: umd.php 197 2008-11-25 03:00:05Z juny $
 */

/*
用法-需要gzcompress和iconv
$umd=new JieqiUmd(); //建立对象
$umd->setinfo(array()); //设置文章信息
$umd->addchapter('标题','内容'); //增加章节
$umd->makeumd('my.umd'); //输出umd文件
*/

class JieqiUmd
{

	var $bookinfo=array('id'=>0, 'title'=>'umd book', 'author'=>'author', 'year'=>'0', 'month'=>'0', 'date'=>'0', 'sort'=>'default', 'publisher'=>'', 'seller'=>'DIY_GENERATED', 'corver'=>'');
	var $chapters = array();
	var $charset = 'GBK';
	var $umd_fd; //目标文件指针
	var $chaptercount=0; //章节数
	var $articlelen=0; //章节内容总长度
	var $chaptitlelen=0; //章节标题总长度
	
	function JieqiUmd(){
		$this->bookinfo['year']=date('Y');
		$this->bookinfo['month']=date('n');
		$this->bookinfo['date']=date('j');
	}

	//设置内容编码
	function setcharset($charset){
		$this->charset = $charset;
	}

	//设置文章信息
	function setinfo($infoary = array()){
		foreach($this->bookinfo as $k=>$v){
			if(isset($infoary[$k])) $this->bookinfo[$k] = $infoary[$k];
			if($k != 'id' && $this->charset != 'UCS') $this->bookinfo[$k]=iconv($this->charset, 'UCS-2LE//IGNORE', $this->bookinfo[$k]);
		}
	}

	//增加章节
	function addchapter($title, $content){
		if($this->charset != 'UCS'){
			$title=iconv($this->charset, 'UCS-2LE//IGNORE', $title);
			$content=iconv($this->charset, 'UCS-2LE//IGNORE', str_replace("\r", "", $content));
		}
		$this->chapters[$this->chaptercount] = array('title'=>$title, 'content'=>$content);
		$this->chaptercount++;
		$this->chaptitlelen+=strlen($title);
		$this->articlelen+=strlen($content);
	}

	//生成UMD
	function makeumd($umdfile = ''){
		//检查文件是否可写
		$this->umd_fd = @fopen($umdfile, 'wb');
		if(!$this->umd_fd) return false;
		//锁定文件
		@flock($this->umd_fd, LOCK_EX);
		
		$data='';
		//第1段（头信息）
		$data.=chr(0x89).chr(0x9B).chr(0x9A).chr(0xDE); //起始头信息
		$data.=chr(0x23).chr(0x01).chr(0x00); //表示第1段
		$data.=chr(0x00).chr(0x08); //这个值用是用来定义后面长度的,实际长度为 值-5,一般固定为8
		$data.=chr(0x01);  //这里1为普通书 2为漫画书

		//生成两个字节随机数
		$pgkeed=rand(0x401, 0x7FFF);
		$data.=$this->umddechex($pgkeed, 2);

		//第2段（文章名称）
		$data.=$this->umdmakeinfo($this->bookinfo['title'], 2);

		//第3段（作者名称）
		$data.=$this->umdmakeinfo($this->bookinfo['author'], 3);

		//第4段（年）
		$data.=$this->umdmakeinfo($this->bookinfo['year'], 4);

		//第5段（月）
		$data.=$this->umdmakeinfo($this->bookinfo['month'], 5);

		//第6段（日）
		$data.=$this->umdmakeinfo($this->bookinfo['date'], 6);

		//第7段（书的类别）
		$data.=$this->umdmakeinfo($this->bookinfo['sort'], 7);

		//第8段（出版人）
		$data.=$this->umdmakeinfo($this->bookinfo['publisher'], 8);

		//第9段（出售人）
		$data.=$this->umdmakeinfo($this->bookinfo['seller'], 9);
		
		//写文件
		fputs($this->umd_fd, $data, strlen($data));
		$data='';

		//没第10段，开始11段(记录文章长度)
		$data.=chr(0x23).chr(0x0B).chr(0x00).chr(0x00).chr(0x09);
		//后面四个字节保存文章长度(每个章节多加2个字节)
		$data.=$this->umddechex($this->articlelen + ($this->chaptercount * 2), 4);

		//记录章节数
		$data.=chr(0x23).chr(0x83).chr(0x00).chr(0x01).chr(0x09);
		//做一个0x3000 到 0x3FFF 的随机数
		$tmpnum=rand(0x3000, 0x3FFF);
		$data.=$this->umddechex($tmpnum, 4);
		$data.=chr(0x24);
		$data.=$this->umddechex($tmpnum, 4);


		//4个字节保存章节数（实际值 9 + (章节数 * 4））
		$tmpnum=$this->chaptercount * 4 + 9;
		$data.=$this->umddechex($tmpnum, 4);
		//每个章节的偏移值（每个4字节）
		$spoint=0;
		foreach($this->chapters as $i=>$chapter){
			$data.=$this->umddechex($spoint, 4);
			$spoint+=strlen($chapter['content']) + 2;
		}

		//章节标题
		$data.=chr(0x23).chr(0x84).chr(0x00).chr(0x01).chr(0x09);
		//做一个0x4000 到 0x3FFF 的随机数
		$tmpnum=rand(0x4000, 0x4FFF);
		$data.=$this->umddechex($tmpnum, 4);
		$data.=chr(0x24);
		$data.=$this->umddechex($tmpnum, 4);
		//9+所有章节标题相加的长度(每个章节标题实际长度+1)
		$tmpnum=9+$this->chaptitlelen+$this->chaptercount;
		$data.=$this->umddechex($tmpnum, 4);
		//写入每个 章节标题长度＋标题标题内容（长度1个字节）
		foreach($this->chapters as $i=>$chapter){
			$tmpnum=strlen($chapter['title']);
			$data.=$this->umddechex($tmpnum, 1);
			$data.=$chapter['title'];
		}
		
		//写文件
		fputs($this->umd_fd, $data, strlen($data));
		

		//写入压缩后的章节内容
		//内容长的每段32K压缩一次，两个随机数，加在尾部段落代码
		$point=0;
		$psize=32768;

		$content='';
		foreach($this->chapters as $i=>$chapter){
			$content.=$chapter['content'].chr(0x29).chr(0x20);
		}
		$clen=strlen($content);
		$packnum=ceil($clen / $psize);
		$rnd1=rand(0, $packnum-1);
		$rand2=rand(0,$packnum-1);
		$rndary=array();
		//每个32K分段压缩保存
		for($i=0; $i<$packnum; $i++){
			$data='';
			$data.=chr(0x24);
			//负数随机数
			$rnd_content=rand(0xF0000001, 0xFFFFFFFE);
			$rndary[$i]=$rnd_content;
			$data.=$this->umddechex($rnd_content, 4);
			$tmpdata = substr($content, $point, $psize);
			$point+=$psize;
			$tmpgz=gzcompress($tmpdata);

			//写入每个分段内容（9＋压缩后的分段长度（4个字节））
			$tmpnum=9+strlen($tmpgz);
			$data.=$this->umddechex($tmpnum, 4);
			$data.=$tmpgz;

			if($i==$rnd1){
				$data.=chr(0x23).chr(0xF1).chr(0x00).chr(0x00).chr(0x15).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00);
			}

			if($i==$rnd2){
				$data.=chr(0x23).chr(0x0A).chr(0x00).chr(0x00).chr(0x09);
		$data.=$this->umddechex($this->bookinfo['id']+0x10000000, 4); //CID 标识用(4字节)
			}
			

			//写文件
			fputs($this->umd_fd, $data, strlen($data));

		}
		
		//内容结束部分
		$data='';
		$data.=chr(0x23).chr(0x81).chr(0x00).chr(0x01).chr(0x09);
		$tmpnum=rand(0x2000, 0x2FFF);
		$data.=$this->umddechex($tmpnum, 4);
		$data.=chr(0x24);
		$data.=$this->umddechex($tmpnum, 4);
		//四个字节(几个分段 * 4 + 9)
		$tmpnum = 9 + ($packnum * 4);
		$data.=$this->umddechex($tmpnum, 4);
		//每个分段开始的随机数
		for($i=0; $i<$packnum; $i++){
			$data.=$this->umddechex($rndary[$i], 4);
		}	
		
		//写文件
		fputs($this->umd_fd, $data, strlen($data));
		$data='';
		
		//封面数据
		if(!empty($this->bookinfo['corver']) && is_file($this->bookinfo['corver'])){
			$data.=chr(0x23).chr(0x82).chr(0x00).chr(0x01).chr(0x0A).chr(0x01);
			$tmpnum=rand(0x1000, 0x1FFF);
			$data.=$this->umddechex($tmpnum, 4);
			$data.=chr(0x24);
			$data.=$this->umddechex($tmpnum, 4);
			$corver_content=file_get_contents($this->bookinfo['corver']);
			$tmpnum=strlen($corver_content)+9;
			$data.=$this->umddechex($tmpnum, 4);
			$data.=$corver_content;
			//写文件
			fputs($this->umd_fd, $data, strlen($data));
			$data='';
		}

		//结尾部分，模仿写
		$tmpnum1=$this->articlelen + ($this->chaptercount * 2);
		$tmpnum2=rand(0x7000, 0x7FFF);

		$data.=chr(0x23).chr(0x87).chr(0x00).chr(0x01).chr(0x0B).chr(0x10).chr(0xD0);
		$data.=$this->umddechex($tmpnum2, 4);
		$data.=chr(0x24);
		$data.=$this->umddechex($tmpnum2, 4);
		$tmpnum=0x11;
		$data.=$this->umddechex($tmpnum, 4);
		$tmpnum=0x00;
		$data.=$this->umddechex($tmpnum, 4);
		$data.=$this->umddechex($tmpnum1, 4);

		$data.=chr(0x23).chr(0x87).chr(0x00).chr(0x01).chr(0x0B).chr(0x10).chr(0xB0);
		$data.=$this->umddechex($tmpnum2, 4);
		$data.=chr(0x24);
		$data.=$this->umddechex($tmpnum2, 4);
		$tmpnum=0x11;
		$data.=$this->umddechex($tmpnum, 4);
		$tmpnum=0x00;
		$data.=$this->umddechex($tmpnum, 4);
		$data.=$this->umddechex($tmpnum1, 4);

		$data.=chr(0x23).chr(0x87).chr(0x00).chr(0x01).chr(0x0B).chr(0x0C).chr(0xD0);
		$data.=$this->umddechex($tmpnum2, 4);
		$data.=chr(0x24);
		$data.=$this->umddechex($tmpnum2, 4);
		$tmpnum=0x11;
		$data.=$this->umddechex($tmpnum, 4);
		$tmpnum=0x00;
		$data.=$this->umddechex($tmpnum, 4);
		$data.=$this->umddechex($tmpnum1, 4);

		$data.=chr(0x23).chr(0x87).chr(0x00).chr(0x01).chr(0x0B).chr(0x0C).chr(0xB0);
		$data.=$this->umddechex($tmpnum2, 4);
		$data.=chr(0x24);
		$data.=$this->umddechex($tmpnum2, 4);
		$tmpnum=0x11;
		$data.=$this->umddechex($tmpnum, 4);
		$tmpnum=0x00;
		$data.=$this->umddechex($tmpnum, 4);
		$data.=$this->umddechex($tmpnum1, 4);

		$data.=chr(0x23).chr(0x87).chr(0x00).chr(0x05).chr(0x0B).chr(0x0A).chr(0xA6);
		$data.=$this->umddechex($tmpnum2, 4);
		$data.=chr(0x24);
		$data.=$this->umddechex($tmpnum2, 4);
		$tmpnum=0x11;
		$data.=$this->umddechex($tmpnum, 4);
		$tmpnum=0x00;
		$data.=$this->umddechex($tmpnum, 4);
		$data.=$this->umddechex(floor($tmpnum1/2), 4);


		//结尾(四个字节文件总长度)
		$data.=chr(0x23).chr(0x0C).chr(0x00).chr(0x01).chr(0x09);
		$tmpnum=4+strlen($data)+ftell($this->umd_fd);
		$data.=$this->umddechex($tmpnum, 4);
		
		//写文件
		fputs($this->umd_fd, $data, strlen($data));
		$data='';
		flock($this->umd_fd, LOCK_UN);
		fclose($this->umd_fd);
		chmod($umdfile, 0777);
	}

	//生成文章信息的
	function umdmakeinfo($instr, $order){
		//间隔符号“#”，以及表示第几段
		$retstr=chr(0x23).chr($order).chr(0x00).chr(0x00);
		//1个字节保存长度(实际值是长度+5)
		$retstr.=$this->umddechex(strlen($instr)+5, 1);
		//先记录长度，然后记录内容
		$retstr.=$instr;
		return $retstr;
	}
	//数字转换成16进制赋值
	function umddechex($num, $bytes){
		$retstr = '';
		$bytes = $bytes * 2;
		$tmpvar=substr(sprintf('%0'.$bytes.'s', dechex($num)), 0 - $bytes);
		for($i=0; $i<$bytes; $i+=2){
			$retstr=chr(hexdec(substr($tmpvar,$i,2))).$retstr;
		}
		return $retstr;
	}
}

?>