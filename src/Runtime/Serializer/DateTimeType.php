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
use Runtime\DateTime;
use Runtime\Serializer\BaseType;


class DateTimeType extends \Runtime\BaseObject implements \Runtime\Serializer\BaseType
{
	/**
	 * Filter value
	 */
	function filter($value, $errors)
	{
		if ($value === null) return null;
		if ($value instanceof \Runtime\DateTime) return $value;
		if (!($value instanceof \Runtime\Map))
		{
			$errors->push("Must be Map");
			return null;
		}
		return new \Runtime\DateTime($value);
	}
	
	
	/**
	 * Returns data
	 */
	function encode($value)
	{
		if ($value === null) return null;
		return $value->toMap();
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Serializer.DateTimeType"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}