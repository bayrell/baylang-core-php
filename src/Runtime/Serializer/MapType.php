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


class MapType extends \Runtime\BaseObject implements \Runtime\Serializer\BaseType
{
	var $fields;
	var $items;
	
	
	/**
	 * Fields
	 */
	function __construct($params = null)
	{
		parent::__construct();
		if ($params instanceof \Runtime\Serializer\BaseType) $this->fields = new \Runtime\Vector($params);
		else if ($params instanceof \Runtime\Vector) $this->fields = $params;
		else if ($params instanceof \Runtime\Map) $this->items = $params;
	}
	
	
	/**
	 * Add type
	 */
	function addType($key, $rule)
	{
		if (!$this->items->has($key)) $this->items->set($key, new \Runtime\Vector());
		$items = $this->items->get($key);
		$items->push($rule);
	}
	
	
	/**
	 * Returns rules
	 */
	function getRules($field_name)
	{
		if ($this->items && $this->items->has($field_name))
		{
			$fields = $this->items->get($field_name);
			if (!($fields instanceof \Runtime\Vector)) $fields = new \Runtime\Vector($fields);
			return $fields;
		}
		if ($this->fields) return $this->fields;
		return null;
	}
	
	
	/**
	 * Returns keys
	 */
	function keys($value = null){ return \Runtime\rtl::list(($this->fields && $value) ? $value->keys() : $this->items->keys()); }
	
	
	
	/**
	 * Walk fields
	 */
	function walk($value, $errors, $f)
	{
		$new_value = new \Runtime\Map();
		$keys = $this->keys($value);
		for ($i = 0; $i < $keys->count(); $i++)
		{
			$key = $keys->get($i);
			$item_value = null;
			if ($value instanceof \Runtime\SerializeInterface) $item_value = \Runtime\rtl::attr($value, $key);
			else if ($value instanceof \Runtime\Map) $item_value = $value->get($key);
			$fields = $this->getRules($key);
			$item_errors = new \Runtime\Vector();
			if ($fields)
			{
				for ($j = 0; $j < $fields->count(); $j++)
				{
					$item = $fields->get($j);
					if ($item instanceof \Runtime\Serializer\BaseType)
					{
						$item_value = $f($item, $item_value, $item_errors, $key);
					}
				}
			}
			$new_value->set($key, $item_value);
			$errors->appendItems($item_errors);
			\Runtime\Serializer\TypeError::addFieldErrors($item_errors, $key);
		}
		return $new_value;
	}
	
	
	/**
	 * Filter type
	 */
	function filter($value, $errors, $old_value = null, $prev = null)
	{
		if (!($value instanceof \Runtime\Map)) return null;
		$new_value = $this->walk($value, $errors, function ($rule, $item_value, $item_errors, $key) use (&$old_value, &$prev)
		{
			return $rule->filter($item_value, $item_errors, $old_value ? $old_value->get($key) : null, $prev);
		});
		return $new_value;
	}
	
	
	/**
	 * Serialize
	 */
	function encode($value)
	{
		if (!($value instanceof \Runtime\Map)) return null;
		$errors = new \Runtime\Vector();
		$new_value = $this->walk($value, $errors, function ($rule, $item_value, $item_errors)
		{
			return $rule->encode($item_value);
		});
		return $new_value;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->fields = null;
		$this->items = new \Runtime\Map();
	}
	static function getClassName(){ return "Runtime.Serializer.MapType"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}