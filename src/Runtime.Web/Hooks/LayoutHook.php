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
use Runtime\Web\EmailLayout;
use Runtime\Web\RedirectResponse;
use Runtime\Web\RenderContainer;
use Runtime\Web\RouteInfo;
use Runtime\Web\RouteList;
use Runtime\Web\Hooks\AppHook as BaseAppHook;


class LayoutHook extends \Runtime\Web\Hooks\AppHook
{
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		$this->register(static::CREATE_LAYOUT);
		$this->register(static::LAYOUT_MODEL_NAME, 0);
	}
	
	
	/**
	 * Layout model name
	 */
	function layout_model_name($params)
	{
		$layout_name = $params->get("layout_name");
		if ($layout_name == "email")
		{
			$params->set("component_name", "Runtime.Web.EmailLayout");
		}
	}
	
	
	/**
	 * Create layout
	 */
	function create_layout($params)
	{
		$container = $params->get("container");
		/* Setup client ip */
		$this->setupClientIP($container);
		/* Setup layout */
		$this->setupLayoutRequest($container);
		/* Setup urls */
		$this->setupLayoutRoutes($container);
	}
	
	
	/**
	 * Setup client ip
	 */
	function setupClientIP($container)
	{
		if (!$container->request) return;
		$client_ip = $container->request->getClientIp();
		$container->layout->storage->backend->set("client_ip", $client_ip);
	}
	
	
	/**
	 * Setup layout request
	 */
	function setupLayoutRequest($container)
	{
		if (!$container->request) return;
		if (!$container->route) return;
		$container->layout->route = $container->route;
		$container->layout->request_https = $container->request->is_https;
		$container->layout->request_host = $container->request->host;
		$container->layout->request_uri = $container->request->uri;
		$container->layout->request_full_uri = $container->request->full_uri;
		$container->layout->request_query = $container->request->query;
	}
	
	
	/**
	 * Setup layout routes
	 */
	function setupLayoutRoutes($container)
	{
		$container->layout->routes = new \Runtime\Map();
		$routes = \Runtime\rtl::getContext()->provider("Runtime.Web.RouteList");
		$routes_list = $routes->routes_list;
		for ($i = 0; $i < $routes_list->count(); $i++)
		{
			$route = $routes_list->get($i);
			if ($route->is_backend) continue;
			$container->layout->routes->set($route->name, new \Runtime\Map([
				"uri" => $route->uri,
				"domain" => $route->domain,
			]));
		}
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Web.Hooks.LayoutHook"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}