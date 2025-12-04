<?php
/*!
 *  BayLang Technology
 *
 *  (c) Copyright 2016-2025 "Ildar Bikmamatov" <support@bayrell.org>
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
namespace Runtime;

use Runtime\StringInterface;

class RawString implements \Runtime\StringInterface
{
	var $s;
	
	/**
	 * Constructor
	 */
	function __construct($s)
	{
		$this->s = "";
		if (\Runtime\rtl::isString($s))
		{
			$this->s = $s;
		}
	}
	
	
	/**
	 * To string
	 */
	function toString()
	{
		return $this->s;
	}
	
	
	function __toString(){ return $this->toString(); }
	
	
	/**
	 * Normalize array
	 */
	static function normalize($item)
	{
		if (\Runtime\rtl::isString($item))
		{
			return $item;
		}
		else if ($item instanceof \Runtime\RawString)
		{
			return $item->s;
		}
		else if ($item instanceof \Runtime\Collection)
		{
			$item = $item->map(function ($item){ return static::normalize($item); });
			return \Runtime\rs::join("", $item);
		}
		return "";
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.RawString"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}