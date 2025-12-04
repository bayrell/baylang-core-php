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
use Runtime\Exceptions\RuntimeException;


class Method extends \Runtime\BaseObject
{
	var $obj;
	var $name;
	var $tag;
	
	
	/**
	 * Constructor
	 */
	function __construct($obj, $name, $tag = null)
	{
		parent::__construct();
		/* Init object */
		$this->_init();
		/* Set variables */
		$this->obj = $obj;
		$this->name = $name;
		$this->tag = $tag;
	}
	
	
	/**
	 * Check if method exists
	 */
	function exists()
	{
		if (!\Runtime\rtl::methodExists($this->obj, $this->name))
		{
			return false;
		}
		return true;
	}
	
	
	/**
	 * Check method
	 */
	function check()
	{
		if (!$this->exists())
		{
			throw new \Runtime\Exceptions\RuntimeException("Method '" . $this->name . "' not found in " . \Runtime\rtl::className($this->obj));
		}
	}
	
	
	/**
	 * Apply
	 */
	function apply($args = null)
	{
		$this->check();
		if ($args == null) $args = [];
		else if ($args instanceof \Runtime\Vector) $args = $args->toArray();
		
		$obj = $this->obj;
		if (gettype($obj) == "string") $obj = rtl::findClass($obj);
		return call_user_func_array([$obj, $this->name], $args);
	}
	
	
	function __invoke()
	{
		return $this->apply(func_get_args());
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->tag = null;
	}
	static function getClassName(){ return "Runtime.Method"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}