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

use Runtime\re;
use Runtime\lib;
use Runtime\BaseLayout;
use Runtime\Entity\Hook;
use Runtime\Hooks\RuntimeHook;
use Runtime\Web\RedirectResponse;
use Runtime\Web\RenderContainer;
use Runtime\Web\Request;
use Runtime\Web\RouteInfo;
use Runtime\Web\RouteList;
use Runtime\Web\RouteProvider;
use Runtime\Web\Hooks\AppHook;


class DetectLanguage extends \Runtime\Hooks\RuntimeHook
{
	var $default_language;
	var $route_match_name;
	var $allowed_languages;
	
	
	/**
	 * Create hook
	 */
	static function hook($params){ return new \Runtime\Entity\Hook("Runtime.Web.Hooks.DetectLanguage", $params); }
	
	
	/**
	 * Init params
	 */
	function initParam($params)
	{
		parent::initParam($params);
		if ($params->has("allowed_languages"))
		{
			$this->allowed_languages = $params->get("allowed_languages");
		}
		if ($params->has("default_language"))
		{
			$this->default_language = $params->get("default_language");
		}
		if ($params->has("route_match_name"))
		{
			$this->route_match_name = $params->get("route_match_name");
		}
	}
	
	
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		$this->register(\Runtime\Web\Hooks\AppHook::ROUTE_BEFORE, "setupLanguage");
		$this->register(\Runtime\Web\Hooks\AppHook::ROUTE_BEFORE, "setupRouteParams");
	}
	/**
	 * Setup language
	 */
	function setupLanguage($params)
	{
		$container = $params->get("container");
		if ($container->layout == null) return;
		if ($container->route == null) return;
		if ($container->route->matches == null) return;
		if (!$container->route->matches->has($this->route_match_name)) return;
		/* Detect lang */
		$container->layout->lang = $container->route->matches->get($this->route_match_name);
	}
	
	
	/**
	 * Setup route params
	 */
	function setupRouteParams($params)
	{
		$container = $params->get("container");
		$router = $container->layout->get("router");
		if (!$router) return;
		/* Add route params */
		$router->route_params->set($this->route_match_name, $container->layout->lang);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->default_language = "";
		$this->route_match_name = "lang";
		$this->allowed_languages = new \Runtime\Vector();
	}
	static function getClassName(){ return "Runtime.Web.Hooks.DetectLanguage"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}