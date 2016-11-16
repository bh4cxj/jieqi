<?php
/**
 * SOCKET类
 *
 * SOCKET类
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: socket.php 301 2008-12-26 04:36:17Z juny $
 */

class JieqiSocket extends JieqiObject {
    var $fp = NULL;  //socket 读写指针
    var $blocking = true; //socket (搁置/非搁置模式)
    var $persistent = false; //是否保持连接
    var $addr = '';  //IP 地址
    var $port = 0;  //端口
    var $timeout = false;  //连接超时时间（秒）
    var $lineLength = 2048; //读一行最大长度

    function JieqiSocket()
    {
        $this->JieqiObject();
    }

    //连接socket，如果已经连接，会断开重新连接
    function connect($addr, $port, $persistent = NULL, $timeout = NULL, $options = NULL)
    {
        if (is_resource($this->fp)) {
            @fclose($this->fp);
            $this->fp = NULL;
        }
        if (strspn($addr, '.0123456789') == strlen($addr)) {
            $this->addr = $addr;
        } else {
            $this->addr = gethostbyname($addr);
        }
        $this->port = $port % 65536;
        if ($persistent !== NULL) {
            $this->persistent = $persistent;
        }
        if ($timeout !== NULL) {
            $this->timeout = $timeout;
        }
        $openfunc = $this->persistent ? 'pfsockopen' : 'fsockopen';
        $errno = 0;
        $errstr = '';
        if ($options && function_exists('stream_context_create')) {
            if ($this->timeout) {
                $timeout = $this->timeout;
            } else {
                $timeout = 0;
            }
            $context = stream_context_create($options);
            $fp = $openfunc($this->addr, $this->port, $errno, $errstr, $timeout, $context);
        } else {
            if ($this->timeout) {
                $fp = @$openfunc($this->addr, $this->port, $errno, $errstr, $this->timeout);
            } else {
                $fp = @$openfunc($this->addr, $this->port, $errno, $errstr);
            }
        }

        if (!$fp) {
            $this->raiseError($errno.':'.$errstr, JIEQI_ERROR_RETURN);
            return false;
        }

        $this->fp = $fp;
        return $this->setBlocking($this->blocking);
    }

    //断开连接
    function disconnect()
    {
        if (is_resource($this->fp)) {
            fclose($this->fp);
            $this->fp = NULL;
        }
        return true;
    }

    //是否阻塞模式
	//阻塞模式下尝试从一个socket读取数据是，如果没有数据可用，程序将一直等待，直到有数据可用。
	//费阻塞模式下，如果数据不可用，函数不等待就返回，所以，终端不会阻塞。
    function isBlocking()
    {
        return $this->blocking;
    }

    function setBlocking($mode)
    {
        if (is_resource($this->fp)) {
            $this->blocking = $mode;
            socket_set_blocking($this->fp, $this->blocking);
            return true;
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }
  
    //超时时间
    function setTimeout($seconds, $microseconds)
    {
        if (is_resource($this->fp)) {
            socket_set_timeout($this->fp, $seconds, $microseconds);
            return true;
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //取得链接状态
    function getStatus()
    {
        if (is_resource($this->fp)) {
            return socket_get_status($this->fp);
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //获取一行，最长不超过 $size-1
    function gets($size)
    {
        if (is_resource($this->fp)) {
            return fgets($this->fp, $size);
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //获取一定长度数据
    function read($size)
    {
        if (is_resource($this->fp)) {
            return fread($this->fp, $size);
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //写入数据
    function write($data)
    {
        if (is_resource($this->fp)) {
            return fwrite($this->fp, $data);
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //写一行
    function writeLine ($data)
    {
        if (is_resource($this->fp)) {
            return $this->write($data . "\r\n");
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //是否结束
    function eof()
    {
        return (is_resource($this->fp) && feof($this->fp));
    }

    //读入一个字节
    function readByte()
    {
        if (is_resource($this->fp)) {
            return ord($this->read(1));
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //读入双字节
    function readWord()
    {
        if (is_resource($this->fp)) {
            $buf = $this->read(2);
            return (ord($buf[0]) + (ord($buf[1]) << 8));
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //读一个四个字节的整数
    function readInt()
    {
        if (is_resource($this->fp)) {
            $buf = $this->read(4);
            return (ord($buf[0]) + (ord($buf[1]) << 8) +
                    (ord($buf[2]) << 16) + (ord($buf[3]) << 24));
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //读入字符串 
    function readString()
    {
        if (is_resource($this->fp)) {
            $string = '';
            while (($char = $this->read(1)) != "\x00")  {
                $string .= $char;
            }
            return $string;
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //读入IP地址
    function readIPAddress()
    {
        if (is_resource($this->fp)) {
            $buf = $this->read(4);
            return sprintf("%s.%s.%s.%s", ord($buf[0]), ord($buf[1]),
                           ord($buf[2]), ord($buf[3]));
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //读一行
    function readLine()
    {
        if (is_resource($this->fp)) {
            $line = '';
            $timeout = JIEQI_NOW_TIME + $this->timeout;
            while (!$this->eof() && (!$this->timeout || JIEQI_NOW_TIME < $timeout)) {
                $line .= $this->gets($this->lineLength);
                if (substr($line, -2) == "\r\n" ||
                    substr($line, -1) == "\n") {
                    return rtrim($line, "\r\n");
                }
            }
            return $line;
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

    //读入全部
    function readAll()
    {
        if (is_resource($this->fp)) {
            $data = '';
            while (!$this->eof())
                $data .= $this->read($this->lineLength);
            return $data;
        }
        $this->raiseError('socket is not connected', JIEQI_ERROR_RETURN);
        return false;
    }

}