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
namespace Runtime\Web\Hooks;

use Runtime\lib;
use Runtime\Web\Hooks\AppHook;
use Runtime\Web\RouteInfo;
use Runtime\Web\RouteList;


class ApiPrefix extends \Runtime\Web\Hooks\AppHook
{
	var $prefix;
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		if ($params == null) return;
		if ($params->has("prefix")) $this->prefix = $params->get("prefix");
	}
	
	
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		$this->register(static::ROUTES_INIT);
		$this->register(static::CALL_API_BEFORE);
	}
	
	
	/**
	 * Routes init
	 */
	function routes_init($params)
	{
		$routes = $params->get("routes");
		$pos = $routes->routes_list->find(\Runtime\lib::equalAttr("name", "runtime:web:api"));
		if ($pos >= 0)
		{
			$route = $routes->routes_list->get($pos);
			$route->uri = $this->prefix . "/api/{service}/{api_name}/{method_name}/";
			$routes->routes_list->set($pos, $route);
		}
	}
	
	
	/**
	 * Call api before
	 */
	function call_api_before($params)
	{
		$params->set("api_url", $this->prefix . $params->get("api_url"));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->prefix = "";
	}
	static function getClassName(){ return "Runtime.Web.Hooks.ApiPrefix"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}