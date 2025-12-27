<?php
/*!
 *  BayLang Technology
 *
 *  (c) Copyright 2016-2024 "Ildar Bikmamatov" <support@bayrell.org>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
namespace Runtime\Crypt;


class RSA
{
	static function getAlgo($algo)
	{
		if ($algo == "SHA1") return OPENSSL_ALGO_SHA1;
		if ($algo == "SHA256") return OPENSSL_ALGO_SHA256;
		if ($algo == "SHA384") return OPENSSL_ALGO_SHA384;
		if ($algo == "SHA512") return OPENSSL_ALGO_SHA512;
		if ($algo == "MD5") return OPENSSL_ALGO_MD5;
		return 0;
	}
	
	
	/**
	 * Sign message by private key
	 */
	static function sign($text, $private_key, $algo)
	{
		$pk = @openssl_get_privatekey($private_key);
		$out = ''; @openssl_sign($text, $out, $pk, static::getAlgo($algo));
		return $out;
		return "";
	}
	
	
	/**
	 * Sign message by private key
	 */
	static function verify($text, $sign, $public_key, $algo)
	{
		$pk = @openssl_get_publickey($public_key);
		return @openssl_verify($text, $sign, $pk, static::getAlgo($algo));
		return "";
	}
	
	
	/**
	 * Verify password
	 */
	static function encode($str, $private_key)
	{
		$pk = @openssl_get_privatekey($private_key);
		$r = str_split($str, 32);
		$r = array_map(function ($s) use ($pk){$out='';@openssl_private_encrypt($s, $out, $pk);return $out;},$r);
		$s = implode("", $r);
		return base64_encode($s);
	}
	
	
	/**
	 * Create password hash
	 */
	static function decode($str, $public_key)
	{
		$pk = @openssl_get_publickey($public_key);
		$str = @base64_decode($str);
		$r = str_split($str, 64);
		$r = array_map(function ($s) use ($pk){$out='';@openssl_public_decrypt($s, $out, $pk);return $out;},$r);
		$s = implode("", $r);
		return $s;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		static function getAlgo($algo)
	{
		if ($algo == "SHA1") return OPENSSL_ALGO_SHA1;
		if ($algo == "SHA256") return OPENSSL_ALGO_SHA256;
		if ($algo == "SHA384") return OPENSSL_ALGO_SHA384;
		if ($algo == "SHA512") return OPENSSL_ALGO_SHA512;
		if ($algo == "MD5") return OPENSSL_ALGO_MD5;
		return 0;
	}
	}
	static function getClassName(){ return "Runtime.Crypt.RSA"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}