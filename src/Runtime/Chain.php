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

use Runtime\Method;

class Chain extends \Runtime\Method
{
	var $chain;
	
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct(null, null);
	}
	
	
	/**
	 * Check if method exists
	 */
	function exists(){ return true; }
	
	
	/**
	 * Check method
	 */
	function check(){}
	
	
	/**
	 * Returns true if async
	 */
	function getChain(){ return $this->chain->slice(); }
	
	
	/**
	 * Add function to chain
	 */
	function add($f, $priority = 100)
	{
		$this->chain->push(new \Runtime\Map([
			"method" => $f,
			"priority" => $priority,
		]));
		return $this;
	}
	
	
	/**
	 * Sort chain
	 */
	function sort()
	{
		$this->chain->sort(\Runtime\rtl::compare(function ($item){ return $item->get("priority"); }));
	}
	
	
	/**
	 * Apply chain
	 */
	function apply($args = null)
	{
		for ($i = 0; $i < $this->chain->count(); $i++)
		{
			$item = $this->chain->get($i);
			$f = $item->get("method");
			if ($f instanceof \Runtime\Method) $f->apply($args);
			else \Runtime\rtl::apply($f, $args);
		}
	}
	
	
	function __invoke()
	{
		return $this->apply(func_get_args());
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->chain = new \Runtime\Vector();
	}
	static function getClassName(){ return "Runtime.Chain"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}