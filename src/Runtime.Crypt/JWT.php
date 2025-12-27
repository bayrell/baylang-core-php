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

use Runtime\rs;
use Runtime\BaseObject;
use Runtime\Crypt\HASH;
use Runtime\Crypt\RSA;


class JWT extends \Runtime\BaseObject
{
	var $data;
	var $correct;
	var $token;
	
	
	/**
	 * Returns true if JWT is correct
	 */
	function isCorrect(){ return $this->correct; }
	
	
	/**
	 * Returns method
	 */
	static function getMethod($algo)
	{
		if ($algo == "HS256") return "hash";
		if ($algo == "HS384") return "hash";
		if ($algo == "HS512") return "hash";
		if ($algo == "RS256") return "rsa";
		if ($algo == "RS384") return "rsa";
		if ($algo == "RS512") return "rsa";
		return "";
	}
	
	
	/**
	 * Returns method
	 */
	static function getAlgo($algo)
	{
		if ($algo == "HS256") return "SHA256";
		if ($algo == "HS384") return "SHA384";
		if ($algo == "HS512") return "SHA512";
		if ($algo == "RS256") return "SHA256";
		if ($algo == "RS384") return "SHA384";
		if ($algo == "RS512") return "SHA512";
		return "";
	}
	
	
	/**
	 * Create jwt sign
	 */
	static function createSign($head_b64, $data_b64, $key, $algo)
	{
		$m = static::getMethod($algo);
		$a = static::getAlgo($algo);
		$text = $head_b64 . "." . $data_b64;
		$sign = "";
		if ($m == "rsa") $sign = \Runtime\Crypt\RSA::sign($text, $key, $a);
		else if ($m == "hash") $sign = \Runtime\Crypt\HASH::hash($text, $key, $a);
		$sign = \Runtime\rs::base64_encode_url($sign);
		return $sign;
	}
	
	
	/**
	 * Validate jwt sign
	 */
	static function validateSign($head_b64, $data_b64, $sign, $key, $algo)
	{
		$m = static::getMethod($algo);
		$a = static::getAlgo($algo);
		$text = $head_b64 . "." . $data_b64;
		$flag = false;
		$sign = \Runtime\rs::base64_decode_url($sign);
		if ($m == "rsa") $flag = \Runtime\Crypt\RSA::verify($text, $sign, $key, $a);
		else if ($m == "hash") $flag = \Runtime\Crypt\HASH::verify($text, $sign, $key, $a);
		return $flag;
	}
	
	
	/**
	 * Create jwt
	 */
	static function encode($data, $key, $algo)
	{
		$data_json = \Runtime\rtl::json_encode($data);
		$data_b64 = \Runtime\rs::base64_encode_url($data_json);
		$head_b64 = \Runtime\rs::base64_encode_url(\Runtime\rtl::json_encode(new \Runtime\Map(["alg" => $algo, "typ" => "JWT"])));
		$sign = static::createSign($head_b64, $data_b64, $key, $algo);
		return new \Runtime\Crypt\JWT(new \Runtime\Map([
			"correct" => true,
			"data" => $data,
			"token" => $head_b64 . "." . $data_b64 . "." . $sign,
		]));
	}
	
	
	/**
	 * Decode jwt
	 */
	static function decode($token_str, $key, $algo = "")
	{
		if (!$token_str) return;
		$arr = \Runtime\rs::split(".", $token_str);
		$head_b64 = $arr->get(0, "");
		$data_b64 = $arr->get(1, "");
		$sign = $arr->get(2, "");
		/* Decode head */
		$head_json = \Runtime\rs::base64_decode($head_b64);
		$head_data = \Runtime\rtl::json_decode($head_json);
		if ($head_data == null) return null;
		/* Decode data */
		$data_json = \Runtime\rs::base64_decode($data_b64);
		$data = \Runtime\rtl::json_decode($data_json);
		if ($data == null) return null;
		/* Check algo */
		$token_algo = $head_data->get("alg", "");
		if ($token_algo == "") return null;
		if ($algo != "")
		{
			if ($token_algo != $algo)
			{
				return new \Runtime\Crypt\JWT(new \Runtime\Map([
					"correct" => false,
					"data" => $data,
					"token" => $token_str,
				]));
			}
		}
		/* Validate sign */
		$flag = static::validateSign($head_b64, $data_b64, $sign, $key, $token_algo);
		if (!$flag) return new \Runtime\Crypt\JWT(new \Runtime\Map([
			"correct" => false,
			"data" => $data,
			"token" => $token_str,
		]));
		return new \Runtime\Crypt\JWT(new \Runtime\Map([
			"correct" => true,
			"data" => $data,
			"token" => $token_str,
		]));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->data = null;
		$this->correct = false;
		$this->token = "";
	}
	static function getClassName(){ return "Runtime.Crypt.JWT"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}