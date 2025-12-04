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

use Runtime\Hooks\BaseHook;
use Runtime\Hooks\RuntimeHook;
use Runtime\Web\BaseLayoutModel;
use Runtime\Web\RenderContainer;
use Runtime\Web\RouteInfo;
use Runtime\Web\RouteProvider;


class AppHook extends \Runtime\Hooks\BaseHook
{
	const ASSETS = "runtime.web.app::assets";
	const CALL_API_BEFORE = "runtime.web.app::call_api_before";
	const FIND_API = "runtime.web.app::find_api";
	const FIND_ROUTE_BEFORE = "runtime.web.app::find_route_before";
	const FIND_ROUTE_AFTER = "runtime.web.app::find_route_after";
	const MAKE_URL = "runtime.web.app::make_url";
	const MAKE_URL_PARAMS = "runtime.web.app::make_url_params";
	const MATCH_ROUTE = "runtime.web.app::match_route";
	const RESPONSE = "runtime.web.app::response";
	const ROUTES_INIT = "runtime.web.app::routes_init";
	const ROUTE_AFTER = "runtime.web.app::route_after";
	const ROUTE_MIDDLEWARE = "runtime.web.app::route_middleware";
	const ROUTE_BEFORE = "runtime.web.app::route_before";
	
	
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		parent::register_hooks();
		/* Async hooks */
		$this->provider->setAsync(new \Runtime\Vector(
			static::FIND_ROUTE_AFTER,
			static::FIND_ROUTE_BEFORE,
			static::ROUTES_INIT,
			static::ROUTE_AFTER,
			static::ROUTE_MIDDLEWARE,
			static::ROUTE_BEFORE,
		));
		/* Hooks */
		$this->register(static::ROUTE_BEFORE, "setupRoute");
	}
	/**
	 * Route after
	 */
	function setupRoute($params)
	{
		$container = $params->get("container");
		$layout = $container->layout;
		if (!$layout) return;
		$layout->storage->set("request", $container->request);
		$layout->storage->set("route", $container->route);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Web.Hooks.AppHook"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}