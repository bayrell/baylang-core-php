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

use Runtime\BaseObject;
use Runtime\SerializeInterface;
use Runtime\Serializer\MapType;
use Runtime\Serializer\ObjectType;
use Runtime\Serializer\StringType;
use Runtime\Web\Hooks\AppHook;


class RouteList extends \Runtime\BaseObject implements \Runtime\SerializeInterface
{
	var $routes;
	var $route_params;
	
	
	/**
	 * Serialize
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("routes", new \Runtime\Serializer\MapType(new \Runtime\Serializer\MapType(new \Runtime\Serializer\StringType())));
		$rules->addType("route_params", new \Runtime\Serializer\MapType(new \Runtime\Serializer\StringType()));
	}
	
	
	/**
	 * Assign rules
	 */
	function assignRules($rule){}
	
	
	/**
	 * Returns url
	 */
	function url($route_name, $route_params = null, $url_params = null)
	{
		if (!$this->routes->has($route_name)) return "";
		if ($route_params == null) $route_params = new \Runtime\Map();
		if ($url_params == null) $url_params = new \Runtime\Map();
		/* Merge route params */
		$route_params = $this->route_params->concat($route_params);
		$route = $this->routes->get($route_name);
		$res = \Runtime\rtl::getContext()->hook(\Runtime\Web\Hooks\AppHook::MAKE_URL_PARAMS, new \Runtime\Map([
			"route" => $route,
			"route_name" => $route_name,
			"route_params" => $route_params,
			"url_params" => $url_params,
		]));
		$domain = $route->get("domain");
		$url = $route->get("uri");
		if ($route_params != null && $url != null)
		{
			$route_params->each(function ($value, $key) use (&$url)
			{
				$pos = \Runtime\rs::indexOf($url, "{" . $key . "}");
				if ($pos >= 0)
				{
					$url = \Runtime\rs::replace("{" . $key . "}", $value, $url);
				}
			});
		}
		/* Set url */
		if ($url == null) $url = "";
		/* Add domain */
		$url_with_domain = $url;
		if ($domain)
		{
			$url_with_domain = "//" . $domain . $url;
		}
		/* Make url */
		$res = \Runtime\rtl::getContext()->hook(\Runtime\Web\Hooks\AppHook::MAKE_URL, new \Runtime\Map([
			"domain" => $domain,
			"route" => $route,
			"route_name" => $route_name,
			"route_params" => $route_params,
			"url" => $url,
			"url_with_domain" => $url_with_domain,
			"url_params" => $url_params ? $url_params : new \Runtime\Map(),
		]));
		$is_domain = $url_params ? $url_params->get("domain", true) : true;
		return $is_domain ? $res->get("url_with_domain") : $res->get("url");
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->routes = new \Runtime\Map();
		$this->route_params = new \Runtime\Map();
	}
	static function getClassName(){ return "Runtime.Web.RouteList"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}