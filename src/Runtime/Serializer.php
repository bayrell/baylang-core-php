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

use Runtime\BaseObject;
use Runtime\Callback;
use Runtime\SerializeInterface;


class Serializer extends \Runtime\BaseObject
{
	const ASSIGN_DATA = 1;
	const ENCODE_DATA = 2;
	var $kind;
	
	
	function isAssign(){ return $this->kind == static::ASSIGN_DATA; }
	function isEncode(){ return $this->kind == static::ENCODE_DATA; }
	
	
	/**
	 * Assign data to object
	 */
	function assign($obj, $data)
	{
		if (!($obj instanceof \Runtime\SerializeInterface)) return;
		$this->kind = static::ASSIGN_DATA;
		$obj->serialize($this, $data);
	}
	
	
	/**
	 * Convert value to object
	 */
	function decode($value, $create = null)
	{
		$this->kind = static::ASSIGN_DATA;
		$new_value = $value;
		if ($value instanceof \Runtime\Map && $value->has("__class_name__"))
		{
			$class_name = $value->get("__class_name__");
			$new_value = $create ? $create->apply(new \Runtime\Vector($this, $value)) : \Runtime\rtl::newInstance($class_name);
			$this->assign($new_value, $value);
		}
		else if ($value instanceof \Runtime\Vector || $value instanceof \Runtime\Map)
		{
			$new_value = $value->map(function ($item) use (&$create)
			{
				$new_item = $this->decode($item, $create);
				return $new_item;
			});
		}
		return $new_value;
	}
	
	
	/**
	 * Convert object to Map
	 */
	function encode($obj)
	{
		$this->kind = static::ENCODE_DATA;
		$data = $obj;
		if (\Runtime\rtl::isObject($obj) && $obj instanceof \Runtime\SerializeInterface)
		{
			$data = new \Runtime\Map([
				"__class_name__" => \Runtime\rtl::className($obj),
			]);
			$obj->serialize($this, $data);
		}
		return $data;
	}
	
	
	/**
	 * Serialize item
	 */
	function process($obj, $field_name, $data, $create = null)
	{
		if ($this->kind == static::ASSIGN_DATA)
		{
			$value = $data->get($field_name);
			$new_value = $this->decode($value, $create);
			\Runtime\rtl::setAttr($obj, $field_name, $new_value);
		}
		else
		{
			$value = \Runtime\rtl::attr($obj, $field_name);
			$new_value = $value;
			if ($value instanceof \Runtime\Vector || $value instanceof \Runtime\Map)
			{
				$new_value = $value->map(function ($item){ return $this->encode($item); });
			}
			else
			{
				$new_value = $this->encode($value);
			}
			$data->set($field_name, $new_value);
		}
	}
	
	
	/**
	 * Copy object
	 */
	static function copy($obj)
	{
		$serializer = new \Runtime\Serializer();
		$data = $serializer->encode($obj);
		$s = \Runtime\rtl::jsonEncode($data);
		$data = \Runtime\rtl::jsonDecode($s);
		return $serializer->decode($obj);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->kind = 0;
	}
	static function getClassName(){ return "Runtime.Serializer"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}