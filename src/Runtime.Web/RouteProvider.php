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
namespace Runtime\Web;

use Runtime\re;
use Runtime\BaseLayout;
use Runtime\BaseProvider;
use Runtime\Method;
use Runtime\Entity\Factory;
use Runtime\Exceptions\ItemNotFound;
use Runtime\Hooks\RuntimeHook;
use Runtime\Providers\HookProvider;
use Runtime\Web\BaseRoute;
use Runtime\Web\RenderContainer;
use Runtime\Web\Request;
use Runtime\Web\RouteAction;
use Runtime\Web\RouteInfo;
use Runtime\Web\RouteList;
use Runtime\Web\RouteModel;
use Runtime\Web\RoutePage;
use Runtime\Web\Annotations\Route;
use Runtime\Web\Hooks\AppHook;


class RouteProvider extends \Runtime\BaseProvider
{
	var $routes;
	var $routes_list;
	
	
	/**
	 * Init provider
	 */
	function init()
	{
		parent::init();
		$this->initRoutes();
		/* Register hooks */
		$this->register_hooks();
	}
	
	
	/**
	 * Init routes
	 */
	function initRoutes()
	{
		$this->routes = new \Runtime\Map();
		$routes = \Runtime\rtl::getContext()->getEntities("Runtime.Web.Annotations.Route");
		for ($i = 0; $i < $routes->count(); $i++)
		{
			$info = $routes[$i];
			if (!$info->name) continue;
			/* Get method getRoutes */
			$getRoutes = new \Runtime\Method($info->name, "getRoutes");
			if (!$getRoutes->exists())
			{
				throw new \Runtime\Exceptions\ItemNotFound($info->name, "Route");
			}
			/* Get routes */
			$routes_list = $getRoutes->apply();
			if (!$routes_list) continue;
			/* Register routes */
			for ($j = 0; $j < $routes_list->count(); $j++)
			{
				$route = $routes_list->get($j);
				$route->route_class = $info->name;
				$route->compile();
				$this->addRoute($route);
			}
		}
		\Runtime\rtl::getContext()->hook(\Runtime\Web\Hooks\AppHook::ROUTES_INIT, new \Runtime\Map([
			"routes" => $this,
		]));
		/* Sort routes */
		$this->sortRoutes();
	}
	
	
	/**
	 * Add route
	 */
	function addRoute($route)
	{
		if ($route->uri_match === null) $route->compile();
		$this->routes->set($route->name, new \Runtime\Map([
			"uri" => $route->uri,
			"domain" => $route->domain,
		]));
		$this->routes_list->push($route);
	}
	
	
	/**
	 * Returns true if route is exists
	 */
	function hasRoute($route_name){ return $this->routes->has($route_name); }
	
	
	/**
	 * Sort routes
	 */
	function sortRoutes()
	{
		$routes_list = $this->routes_list->map(function ($value, $index){ return new \Runtime\Vector($value, $index); });
		$routes_list->sort(function ($a, $b)
		{
			$pos_a = $a->get(0)->pos;
			$pos_b = $b->get(0)->pos;
			if ($pos_a == $pos_b)
			{
				return $a->get(1) - $b->get(1);
			}
			return $pos_a - $pos_b;
		});
		$this->routes_list = $routes_list->map(function ($item){ return $item->get(0); });
	}
	
	
	/**
	 * Start provider
	 */
	function start()
	{
		parent::start();
	}
	
	
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		$hook = \Runtime\rtl::getContext()->provider("hook");
		$hook->register(\Runtime\Web\Hooks\AppHook::ROUTE_BEFORE, new \Runtime\Method($this, "routeBefore"), 50);
	}
	
	
	/**
	 * Route before
	 */
	function routeBefore($params)
	{
		$container = $params->get("container");
		$layout = $container->layout;
		/* Create route */
		$router = $layout->storage->createWidget("Runtime.Web.RouteList");
		$router->routes = $this->routes->copy();
		$layout->storage->set("router", $router);
	}
	
	
	/**
	 * Find route
	 */
	function findRoute($request)
	{
		if ($request->uri === null) return null;
		for ($i = 0; $i < $this->routes_list->count(); $i++)
		{
			$route = $this->routes_list->get($i);
			$matches = $this->matchRoute($request, $route);
			if ($matches == null) continue;
			$route = $route->copy();
			$route->addMatches($matches);
			return $route;
		}
		return null;
	}
	
	
	/**
	 * Match route
	 */
	function matchRoute($request, $route)
	{
		if ($route == null) return null;
		if ($route->domain && $route->domain != $request->host) return null;
		/* Get matches */
		$matches = \Runtime\re::matchAll($route->uri_match, $request->uri);
		if ($matches)
		{
			$matches = $matches->get(0, null);
			$matches->remove(0);
		}
		/* Call hook */
		$d = \Runtime\rtl::getContext()->hook(\Runtime\Web\Hooks\AppHook::MATCH_ROUTE, new \Runtime\Map([
			"route" => $route,
			"request" => $request,
			"matches" => $matches,
		]));
		$matches = $d->get("matches");
		if ($matches == null) return null;
		return $matches;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->routes = new \Runtime\Map();
		$this->routes_list = new \Runtime\Vector();
	}
	static function getClassName(){ return "Runtime.Web.RouteProvider"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}