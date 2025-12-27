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


class HASH
{
	/**
	 * Create md5 string
	 */
	static function md5($s)
	{
		return md5($s);
		return "";
	}
	
	
	/**
	 * Create hash string
	 */
	static function hash($text, $key, $algo)
	{
		return hash_hmac($algo, $text, $key, true);
		return "";
	}
	
	
	/**
	 * Verify hash string
	 */
	static function verify($text, $sign, $key, $algo)
	{
		$hash = hash_hmac($algo, $text, $key, true);
		return hash_equals($sign, $hash);
		return "";
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.Crypt.HASH"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}