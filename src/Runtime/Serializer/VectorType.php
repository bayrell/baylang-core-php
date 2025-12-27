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
use Runtime\SerializeInterface;
use Runtime\Serializer\BaseType;
use Runtime\Serializer\TypeError;


class VectorType extends \Runtime\BaseObject implements \Runtime\Serializer\BaseType
{
	var $fields;
	
	
	/**
	 * Create object
	 */
	function __construct($fields)
	{
		parent::__construct();
		if ($fields instanceof \Runtime\Serializer\BaseType) $this->fields = new \Runtime\Vector($fields);
		else if ($fields instanceof \Runtime\Vector) $this->fields = $fields;
	}
	
	
	/**
	 * Walk item
	 */
	function walk($value, $errors, $f)
	{
		$new_value = new \Runtime\Vector();
		for ($i = 0; $i < $value->count(); $i++)
		{
			$item_errors = new \Runtime\Vector();
			$new_item = $value->get($i);
			for ($j = 0; $j < $this->fields->count(); $j++)
			{
				$field = $this->fields->get($j);
				if ($field instanceof \Runtime\Serializer\BaseType)
				{
					$new_item = $f($field, $new_item, $item_errors, $i);
				}
			}
			$new_value->push($new_item);
			\Runtime\Serializer\TypeError::addFieldErrors($item_errors, $i);
			$errors->appendItems($item_errors);
		}
		return $new_value;
	}
	
	
	/**
	 * Filter value
	 */
	function filter($value, $errors, $old_value = null, $prev = null)
	{
		if ($value === null) return null;
		if (!($value instanceof \Runtime\Vector)) return null;
		$new_value = $this->walk($value, $errors, function ($field, $new_item, $item_errors, $i) use (&$old_value, &$prev)
		{
			return $field->filter($new_item, $item_errors, $old_value ? $old_value->get($i) : null, $prev);
		});
		return $new_value;
	}
	
	
	/**
	 * Returns data
	 */
	function encode($value)
	{
		if ($value === null) return null;
		if (!($value instanceof \Runtime\Vector)) return null;
		$errors = new \Runtime\Vector();
		$new_value = $this->walk($value, $errors, function ($field, $new_item, $item_errors, $i)
		{
			return $field->encode($new_item);
		});
		return $new_value;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->fields = new \Runtime\Vector();
	}
	static function getClassName(){ return "Runtime.Serializer.VectorType"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}