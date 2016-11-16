<?php
/**
 * RSA Sign & Verify according PKCS #1 version 1.5 of RSA Encryption Standard
 * This Class is interoperable with 
 *       Java Class: sun.security.rsa.RSASignature$MD5withRSA
 *       .NET: System.Security.Cryptography.RSACryptoServiceProvider.SignHash using MD5
 * 
 *    By JinsongQiu, Zhejiang University
 *
 * @category   jieqicms
 * @package    pay
 */
require_once("BigInteger.php"); 

class Sign {
        var $keySize;

        // private Key, type: Math_BigInteger
	var $modulus;
	var $d;
	
	// if private key using CRT-Mode, include following paramters to private key (all type is Math_BigInteger)
	var $p;
	var $q;
	var $dp;
	var $dq;
	var $inverseQ;
	var $prvKeyMode;     // 0-default, 1-CRT Mode (using (p, q, dp, dq, inverseQ))
	
	// public Key, type: Math_BigInteger
	var $publicExp;
	
	function setPrivateKey($d, $modulus)
	{
		$this->d = $d;
		$this->modulus = $modulus;
		$this->prvKeyMode = 0;
		$this->keySize = strlen(bin2hex($this->modulus->toBytes()))/2*8;
	}
	
	// using CRT mode will compute more quickly than standard mode
	function setCRTPrivateKey($modulus, $p, $q, $dp, $dq, $inverseQ)
	{
		$this->modulus = $modulus;
		$this->p = $p;
		$this->q = $q;
		$this->dp = $dp;
		$this->dq = $dq;
		$this->inverseQ = $inverseQ;

		$this->prvKeyMode = 1;
		$this->keySize = strlen(bin2hex($this->modulus->toBytes()))/2*8;
	}

	// set private key from XML String (base64 encoded)
	function setPrivateKeyFromXML($xml_d, $xml_modulus)
	{
		$binKey = base64_decode($xml_d);
		$this->d = new Math_BigInteger($binKey, 256);

		$binKey = base64_decode($xml_modulus);
		$this->modulus = new Math_BigInteger($binKey, 256);

		$this->prvKeyMode = 0;
		$this->keySize = strlen(bin2hex($this->modulus->toBytes()))/2*8;
	}

	function setCRTPrivateKeyFromXML($xml_modulus, $xml_p, $xml_q, $xml_dp, $xml_dq, $xml_inverseQ)
	{
		$binKey = base64_decode($xml_modulus);
		$this->modulus = new Math_BigInteger($binKey, 256);

		$binKey = base64_decode($xml_p);
		$this->p = new Math_BigInteger($binKey, 256);

		$binKey = base64_decode($xml_q);
		$this->q = new Math_BigInteger($binKey, 256);

		$binKey = base64_decode($xml_dp);
		$this->dp = new Math_BigInteger($binKey, 256);

		$binKey = base64_decode($xml_dq);
		$this->dq = new Math_BigInteger($binKey, 256);

		$binKey = base64_decode($xml_inverseQ);
		$this->inverseQ = new Math_BigInteger($binKey, 256);

		$this->prvKeyMode = 1;
		$this->keySize = strlen(bin2hex($this->modulus->toBytes()))/2*8;
	}

	function setPublicKey($publicExp, $modulus)
	{
		$this->modulus = $modulus;
		$this->publicExp = $publicExp;

		$this->keySize = strlen(bin2hex($this->modulus->toBytes()))/2*8;
	}

	// set public key from XML String (base64 encoded)
	function setPublicKeyFromXML($xml_exp, $xml_modulus)
	{
		$binKey = base64_decode($xml_exp);
		$this->publicExp = new Math_BigInteger($binKey, 256);

		$binKey = base64_decode($xml_modulus);
		$this->modulus = new Math_BigInteger($binKey, 256);

		$this->keySize = strlen(bin2hex($this->modulus->toBytes()))/2*8;
	}

        // return and input all is Math_BigInteger
        function rsaCrypt($data, $exp, $modulus)
        {
        	return $data->modPow($exp, $modulus);
        }
        
        // return and input all is Math_BigInteger
        function crtCrypt($data)
        {
		// m1 = c ^ dP mod p
		$m1 = $data->modPow($this->dp, $this->p);
		
		// m2 = c ^ dQ mod q
		$m2 = $data->modPow($this->dq, $this->q);
	    
	    	// h = (m1 - m2) * qInv mod p
		$mtmp = $m1->subtract($m2);
		$zero = new Math_BigInteger("0");
		
		if ($mtmp->compare($zero) < 0) {
		    $mtmp = $mtmp->add($this->p);
		}
		
		$h = $mtmp->multiply($this->inverseQ);
		
		$h->value = bcmod($h->toString(), $this->p->toString());
		
		// m = m2 + q * h
		$m = $h->multiply($this->q);
		$m = $m->add($m2);
		
        	return $m;
        }

        // input is HexString
        // return is Math_BigInteger
        function rsaWithPrivateKey($data)
        {
        	$dataInteger = new Math_BigInteger($data, 16);
        	
        	if ($this->prvKeyMode == 0)
        	{
        		return $this->rsaCrypt($dataInteger, $this->d, $this->modulus);
        	}
        	else {
        		return $this->crtCrypt($dataInteger);
        	}
        }

        // input is binString
        // return is Math_BigInteger
        function rsaWithPublicKey($data)
        {
        	$dataInteger = new Math_BigInteger($data, 256);
        	
        	return $this->rsaCrypt($dataInteger, $this->publicExp, $this->modulus);
        }
        
        // encode and padding the digest to PKCS#1 v1.5 format
        //    0x00 0x01  PadStr  0x00  DER(ALG_ID) DER(MD5_Hash)
        // input is HexString
        // return encoded as HexString (not Binary)
	function encodeSignData($digest)
	{
		// MD5WithRSA Alg_OID using ASN.1 DER Encode Rule
		$algid = "3020300c06082a864886f70d02050500";

		$digdata = "0410" . $digest;
		
		// PadStr is ffff...ffff, let total length = keysize;
		$pad_length = $this->keySize/8 - 3 - strlen($algid)/2 - strlen($digdata)/2;
		$padStr = str_repeat("FF", $pad_length);
		
		return "00" . "01" . $padStr . "00" . $algid . $digdata;
		
	}
	
	// extract digest from PKCS#1 v1.5 format
        //    0x00 0x01  PadStr  0x00  DER(ALG_ID) DER(MD5_Hash)
        // input is HexString
	// return digest as HexString (not Binary), or false if invalid format
	function decodeSignData($encoded)
	{
		if (strlen($encoded)/2 > $this->keySize/8) {
			//echo "encoded length > keySize <BR>";
		        return false;
		}
		    
		// set input length = keySize, with leading zero
		if (strlen($encoded)/2 < $this->keySize/8)
		    $encoded = str_repeat("00", $this->keySize/8 - strlen($encoded)/2) . $encoded;
		    
		// check the block type
		if (substr_compare($encoded, "01", 2, 2) != 0) {
			//echo "block type invalid <BR>";
		        return false;
		}
		    
		$i = 2;
		while (substr_compare($encoded, "00", $i*2, 2) != 0) {
			if ($i >= $this->keySize/8) return false;
			else $i ++;
		}
		
		// check if the alg OID = MD5RSA_OID
		$i ++;
		$algid = "3020300c06082a864886f70d02050500";
		if (substr_compare($encoded, $algid, $i*2, strlen($algid)) != 0) return false;
		
		$i += strlen($algid)/2;
		
		// check the HASH length
		if (substr_compare($encoded, "0410", $i*2, 4) != 0) return false;
		
		$i += 2;
		return substr($encoded, $i*2, 16*2);
	}
	
	function getSign($param) 
	{
		$digest = md5($param);
		
		$encoded = $this->encodeSignData($digest);
		
		$signData = $this->rsaWithPrivateKey($encoded);
		
		$signStr = base64_encode($signData->toBytes());
		$signStr = str_replace("+", "%2B", $signStr);
		
		return $signStr;
	}
	
	function verifySign($param, $signStr)
	{
		$signStr = str_replace("%2B", "+", $signStr);
		$signData = base64_decode($signStr);
		
		$encoded = $this->rsaWithPublicKey($signData);
		
		$digest = $this->decodeSignData(bin2hex($encoded->toBytes()));
		if ($digest == false) {
			//echo "digest invalid <BR>";
			return false;
		}
		
		$digest2 = md5($param);
		
		if ($digest == $digest2) return true;
		else return false;
	}
}
?>