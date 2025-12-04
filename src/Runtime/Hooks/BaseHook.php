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
namespace Runtime\Hooks;

use Runtime\BaseObject;
use Runtime\Chain;
use Runtime\Method;
use Runtime\Entity\Hook;
use Runtime\Providers\HookProvider;


class BaseHook extends \Runtime\BaseObject
{
	var $hook;
	var $provider;
	
	
	/**
	 * Create hook
	 */
	function __construct($params = null)
	{
		parent::__construct();
		/* Init params */
		$this->initParams($params);
	}
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		if ($params == null) return;
		$this->hook = $params->get("hook");
		$this->provider = $params->get("provider");
	}
	
	
	/**
	 * Register hook
	 */
	function register($hook_name, $method_name = "", $priority = 100)
	{
		$chain = $this->provider->getChain($hook_name);
		if (!$chain) return;
		$chain->add(new \Runtime\Method($this, $method_name), $priority);
	}
	
	
	/**
	 * Register hooks
	 */
	function register_hooks(){}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->hook = null;
		$this->provider = null;
	}
	static function getClassName(){ return "Runtime.Hooks.BaseHook"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}