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
namespace Runtime\Serializer;

use Runtime\BaseObject;
use Runtime\Serializer\BaseType;
use Runtime\Serializer\TypeError;


class IntegerType extends \Runtime\BaseObject implements \Runtime\Serializer\BaseType
{
	var $convert;
	var $default_value;
	
	
	/**
	 * Create type
	 */
	function __construct($params = null)
	{
		parent::__construct();
		if (!$params) return;
		if ($params->has("convert")) $this->conver = $params->get("convert");
		if ($params->has("default")) $this->default_value = $params->get("default");
	}
	
	
	/**
	 * Filter type
	 */
	function filter($value, $errors)
	{
		if ($value === null) return $this->default_value;
		if ($this->convert)
		{
			if (\Runtime\rtl::isInteger($value) || \Runtime\rtl::isBoolean($value)) $value = \Runtime\rtl::toInt($value);
			else if (\Runtime\rtl::isString($value)) $value = \Runtime\rtl::toInt($value);
		}
		if (!\Runtime\rtl::isInteger($value))
		{
			$errors->push(new \Runtime\Serializer\TypeError("Does not integer"));
			return "";
		}
		return $value;
	}
	
	
	/**
	 * Serialize
	 */
	function encode($value)
	{
		if (\Runtime\rtl::isInteger($value)) return $value;
		if (\Runtime\rtl::isBoolean($value) || \Runtime\rtl::isString($value)) return \Runtime\rtl::toInt($value);
		return 0;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->convert = true;
		$this->default_value = 0;
	}
	static function getClassName(){ return "Runtime.Serializer.IntegerType"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}