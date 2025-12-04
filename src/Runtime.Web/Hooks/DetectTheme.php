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

use Runtime\RenderContainer;
use Runtime\Entity\Hook;
use Runtime\Hooks\RuntimeHook;
use Runtime\Web\Hooks\AppHook;


class DetectTheme extends \Runtime\Hooks\RuntimeHook
{
	var $cookie_name;
	
	
	/**
	 * Create hook
	 */
	static function hook($params = null){ return new \Runtime\Entity\Hook("Runtime.Web.Hooks.DetectTheme", $params); }
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params->has("cookie_name")) $this->cookie_name = $params->get("cookie_name");
	}
	
	
	/**
	 * Register hook
	 */
	function register_hooks()
	{
		$this->register(\Runtime\Web\Hooks\AppHook::ROUTE_AFTER, "routeAfter");
	}
	
	
	/**
	 * Route after
	 */
	function routeAfter($params)
	{
		$container = $params->get("container");
		$container->layout->theme = $container->request->cookies->get($this->cookie_name, "light");
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->cookie_name = "theme";
	}
	static function getClassName(){ return "Runtime.Web.Hooks.DetectTheme"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}