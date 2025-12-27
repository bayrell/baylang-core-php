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

use Runtime\BaseObject;

class AssertHelper
{
	/**
	 * Check equals of types
	 */
	static function equalValueType($value1, $value2)
	{
		$message = "Type mismatch \"" . $value1 . "\" and \"" . $value2 . "\"";
		$type1 = \Runtime\rtl::getType($value1);
		$type2 = \Runtime\rtl::getType($value2);
		\Runtime\rtl::assert($type1 == $type2, $message);
	}
	
	
	/**
	 * Check equals of types
	 */
	static function equalType($value1, $type1)
	{
		$type2 = \Runtime\rtl::getType($value1);
		$message = "Type mismatch. Needs \"" . $type1 . "\". Exists \"" . $type2 . "\"";
		\Runtime\rtl::assert($type1 == $type2, $message);
	}
	
	
	/**
	 * Check class name
	 */
	static function equalClass($value, $class_name)
	{
		$message = "Class \"" . $class_name . "\" not found";
		$is_object = $value instanceof \Runtime\BaseObject || $value instanceof \Runtime\Map || $value instanceof \Runtime\Vector;
		\Runtime\rtl::assert($is_object, $message);
		\Runtime\rtl::assert($value::getClassName() == $class_name, $message);
	}
	
	
	/**
	 * Check equals of values
	 */
	static function equalValue($value1, $value2, $message = "")
	{
		static::equalValueType($value1, $value2);
		if ($value1 instanceof \Runtime\Vector)
		{
			static::equalVector($value1, $value2);
			return;
		}
		if ($value1 instanceof \Runtime\Map)
		{
			static::equalMap($value1, $value2);
			return;
		}
		if ($message == "") $message = "\"" . $value1 . "\" != \"" . $value2 . "\"";
		\Runtime\rtl::assert($value1 === $value2, $message);
	}
	
	
	/**
	 * Check equals of two vectors
	 */
	static function equalVector($c1, $c2)
	{
		if ($c1->count() != $c2->count())
		{
			$message = "Vectors has different counts";
			\Runtime\rtl::assert(false, $message);
		}
		for ($i = 0; $i < $c1->count(); $i++)
		{
			$value1 = $c1->get($i);
			$value2 = $c2->get($i);
			static::equalValue($value1, $value2);
		}
	}
	
	
	/**
	 * Check equals of two maps
	 */
	static function equalMap($d1, $d2)
	{
		$d1_keys = \Runtime\rtl::list($d1->keys());
		$d2_keys = \Runtime\rtl::list($d2->keys());
		for ($i = 0; $i < $d1_keys->count(); $i++)
		{
			$key1 = $d1_keys->get($i);
			if (!$d2->has($key1))
			{
				$message = "Map does not has key \"" . $key1 . "\"";
				\Runtime\rtl::assert(false, $message);
			}
			$value1 = $d1->get($key1);
			$value2 = $d2->get($key1);
			static::equalValue($value1, $value2);
		}
		for ($i = 0; $i < $d2_keys->count(); $i++)
		{
			$key2 = $d2_keys->get($i);
			if (!$d1->has($key2))
			{
				$message = "Map does not has key \"" . $key2 . "\"";
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