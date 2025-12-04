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
namespace Runtime\Providers;

use Runtime\lib;
use Runtime\BaseHook;
use Runtime\BaseProvider;
use Runtime\Callback;
use Runtime\Chain;
use Runtime\ChainAsync;
use Runtime\Context;
use Runtime\Entity\Hook;


class HookProvider extends \Runtime\BaseProvider
{
	var $base_hooks;
	var $chains;
	var $async_hooks;
	
	
	/**
	 * Init provider
	 */
	function init()
	{
		$hooks = \Runtime\rtl::getContext()->getEntities("Runtime.Entity.Hook");
		$this->base_hooks = new \Runtime\Vector();
		for ($i = 0; $i < $hooks->count(); $i++)
		{
			$hook = $hooks[$i];
			$base_hook = $hook->createInstance(new \Runtime\Map([
				"hook" => $hook,
				"provider" => $this,
			]));
			$base_hook->register_hooks();
			$this->base_hooks->push($base_hook);
		}
	}
	
	
	/**
	 * Start provider
	 */
	function start()
	{
		parent::start();
		$this->sort();
	}
	
	
	/**
	 * Set async
	 */
	function setAsync($arr)
	{
		for ($i = 0; $i < $arr->count(); $i++)
		{
			$hook_name = $arr->get($i);
			$this->async_hooks->set($hook_name, true);
		}
	}
	
	
	/**
	 * Returns if chain is async
	 */
	function isAsync($name)
	{
		if (!$this->async_hooks->has($name)) return false;
		return $this->async_hooks->get($name);
	}
	
	
	/**
	 * Register hook
	 */
	function register($hook_name, $f, $priority = 100)
	{
		$chain = $this->getChain($hook_name);
		if (!$chain) return;
		$chain->add($f, $priority);
	}
	
	
	/**
	 * Sort
	 */
	function sort()
	{
		$this->chains->each(function ($chain)
		{
			$chain->sort();
		});
	}
	
	
	/**
	 * Create chain
	 */
	function createChain($name, $is_async)
	{
		if ($this->chains->has($name)) return;
		if ($is_async) $this->chains->set($name, new \Runtime\ChainAsync());
		else $this->chains->set($name, new \Runtime\Chain());
	}
	
	
	/**
	 * Returns chain
	 */
	function getChain($name)
	{
		if (!$this->chains->has($name)) $this->createChain($name, $this->isAsync($name));
		return $this->chains->get($name);
	}
	
	
	/**
	 * Apply hook
	 */
	function apply($hook_name, $params = null)
	{
		$chain = $this->chains->get($hook_name);
		if (!$chain) return $params;
		if ($chain instanceof \Runtime\ChainAsync)
		{
			$f = function () use (&$chain, &$params)
			{
				$chain->apply(new \Runtime\Vector($params));
				return $params;
			};
			return $f();
		}
		$chain->apply(new \Runtime\Vector($params));
		return $params;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->base_hooks = new \Runtime\Vector();
		$this->chains = new \Runtime\Map();
		$this->async_hooks = new \Runtime\Map();
	}
	static function getClassName(){ return "Runtime.Providers.HookProvider"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}