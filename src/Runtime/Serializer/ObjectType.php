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
use Runtime\Chain;
use Runtime\Serializer\BaseType;
use Runtime\Serializer\MapType;
use Runtime\Serializer\TypeError;
use Runtime\Serializer;
use Runtime\SerializeInterface;


class ObjectType extends \Runtime\Serializer\MapType
{
	var $fn_create;
	var $fn_rules;
	var $fn_serialize;
	var $setup;
	var $class_name;
	var $class_extends;
	var $class_implements;
	var $params;
	
	
	/**
	 * Create object
	 */
	function __construct($params = null)
	{
		parent::__construct();
		$this->params = $params;
		if (!$params) return;
		if ($params->has("autocreate")) $this->autocreate();
		if ($params->has("create")) $this->fn_create = $params->get("create");
		if ($params->has("class_name")) $this->class_name = $params->get("class_name");
		if ($params->has("class_extends")) $this->class_extends = $params->get("extends");
		if ($params->has("class_implements")) $this->class_implements = $params->get("implements");
		if ($params->has("rules")) $this->fn_rules = $params->get("rules");
		if ($params->has("serialize")) $this->fn_serialize = $params->get("serialize");
	}
	
	
	/**
	 * Set class name
	 */
	function setClassName($class_name)
	{
		$this->class_name = $class_name;
	}
	
	
	/**
	 * Copy object
	 */
	function copy()
	{
		$rules = new \Runtime\Serializer\ObjectType();
		$rules->fn_create = $this->fn_create;
		$rules->fn_rules = $this->fn_rules;
		$rules->fn_serialize = $this->fn_serialize;
		$rules->setup = $this->setup;
		$rules->items = $this->items->map(function ($items){ return $items->slice(); });
		$rules->class_name = $this->class_name;
		$rules->class_extends = $this->class_extends;
		$rules->class_implements = $this->class_implements;
		return $rules;
	}
	
	
	/**
	 * Autocreate
	 */
	function autocreate()
	{
		$this->fn_rules = function ($rules, $value)
		{
			$rules->class_name = $value->get("__class_name__");
		};
		$this->fn_serialize = function ($item, $value)
		{
			$value->set("__class_name__", $item::getClassName());
		};
	}
	
	
	/**
	 * Create object
	 */
	function createObject($value, $errors, $prev)
	{
		if ($this->class_name == "") return null;
		if (!\Runtime\rtl::classExists($this->class_name))
		{
			$errors->push("Class '" . $this->class_name . "' does not exists");
			return null;
		}
		if ($this->class_extends != "" && !\Runtime\rtl::isInstanceOf($this->class_name, $this->class_extends))
		{
			$errors->push("Class '" . $this->class_name . "' does not extends '" . $this->class_extends . "'");
			return null;
		}
		if ($this->class_implements != "" && !\Runtime\rtl::isImplements($this->class_name, $this->class_implements))
		{
			$errors->push("Class '" . $this->class_name . "' does not implements '" . $this->class_implements . "'");
			return null;
		}
		if ($this->fn_create)
		{
			$fn_create = $this->fn_create;
			return $fn_create($prev, $this, $value);
		}
		return \Runtime\rtl::newInstance($this->class_name, new \Runtime\Vector($value));
	}
	
	
	/**
	 * Init rules
	 */
	function initRules($value)
	{
		if ($this->fn_rules)
		{
			$rules = $this->fn_rules;
			$rules($this, $value);
		}
		return $this;
	}
	
	
	/**
	 * Filter value
	 */
	function filter($value, $errors, $old_value = null, $prev = null)
	{
		if ($value == null) return null;
		$new_value = $old_value;
		if (!$new_value)
		{
			$rules = $this->copy()->initRules($value);
			$new_value = $rules->createObject($value, $errors, $prev);
		}
		if (!$new_value) return null;
		$rules = new \Runtime\Serializer\ObjectType($this->params);
		$new_value::serialize($rules);
		$rules->setup->apply(new \Runtime\Vector($new_value, $rules));
		$rules->walk($value, $errors, function ($field, $new_item, $item_errors, $key) use (&$value, &$prev, &$new_value)
		{
			if (!$value->has($key)) return;
			$old_item = $new_value ? \Runtime\rtl::attr($new_value, $key) : null;
			$new_item = $field->filter($new_item, $item_errors, $old_item, $new_value);
			if ($key != "__class_name__")
			{
				\Runtime\rtl::setAttr($new_value, $key, $new_item);
			}
		});
		return $new_value;
	}
	
	
	/**
	 * Returns data
	 */
	function encode($value)
	{
		if ($value === null) return null;
		if (!($value instanceof \Runtime\SerializeInterface)) return null;
		$rules = new \Runtime\Serializer\ObjectType($this->params);
		$value::serialize($rules);
		$errors = new \Runtime\Vector();
		$new_value = $rules->walk($value, $errors, function ($field, $new_item, $item_errors)
		{
			return $field->encode($new_item);
		});
		$serialize = $this->fn_serialize;
		if ($serialize) $serialize($value, $new_value);
		return $new_value;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->fn_create = null;
		$this->fn_rules = null;
		$this->fn_serialize = null;
		$this->setup = new \Runtime\Chain();
		$this->class_name = "";
		$this->class_extends = "";
		$this->class_implements = "";
		$this->params = new \Runtime\Map();
	}
	static function getClassName(){ return "Runtime.Serializer.ObjectType"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}