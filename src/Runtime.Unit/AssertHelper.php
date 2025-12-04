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
namespace Runtime\Unit;

use Runtime\Entity;

class AssertHelper
{
	/**
	 * Check equals of types
	 */
	static function equalValueType($value1, $value2, $message)
	{
		$type1 = \Runtime\rtl::getType($value1);
		$type2 = \Runtime\rtl::getType($value2);
		\Runtime\rtl::assert($type1 == $type2, $message);
	}
	
	
	/**
	 * Check equals of values
	 */
	static function equalValue($value1, $value2, $message)
	{
		static::equalValueType($value1, $value2, $message);
		$value_type1 = \Runtime\rtl::getType($value1);
		$value_type2 = \Runtime\rtl::getType($value2);
		\Runtime\rtl::assert($value_type1 == $value_type2, $message);
		if (\Runtime\rtl::isScalarValue($value1))
		{
			\Runtime\rtl::assert($value1 === $value2, $message);
			return;
		}
		if ($value_type1 == "collection")
		{
			static::equalCollection($value1, $value2, $message);
			return;
		}
		if ($value_type1 == "dict")
		{
			static::equalDict($value1, $value2, $message);
			return;
		}
		\Runtime\rtl::assert(false, $message);
	}
	
	
	/**
	 * Check equals of two collections
	 */
	static function equalCollection($c1, $c2, $message)
	{
		if ($c1->count() != $c2->count())
		{
			\Runtime\rtl::assert(false, $message);
		}
		for ($i = 0; $i < $c1->count(); $i++)
		{
			$value1 = $c1->get($i);
			$value2 = $c2->get($i);
			static::equalValue($value1, $value2, $message);
		}
	}
	
	
	/**
	 * Check equals of two dicts
	 */
	static function equalDict($d1, $d2, $message)
	{
		$d1_keys = $d1->keys();
		$d2_keys = $d2->keys();
		for ($i = 0; $i < $d1_keys->count(); $i++)
		{
			$key1 = $d1_keys->get($i);
			if (!$d2->has($key1))
			{
				\Runtime\rtl::assert(false, $message);
			}
			$value1 = $d1->get($key1);
			$value2 = $d2->get($key1);
			static::equalValue($value1, $value2, $message);
		}
		for ($i = 0; $i < $d2_keys->count(); $i++)
		{
			$key2 = $d2_keys->get($i);
			if (!$d1->has($key2))
			{
				\Runtime\rtl::assert(false, $message);
			}
		}
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.Unit.AssertHelper"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}