<?php
/*
!
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

use Runtime\lib;
use Runtime\BaseLayout;
use Runtime\Callback;
use Runtime\DefaultLayout;
use Runtime\Serializer;
use Runtime\Web\ApiResult;
use Runtime\Web\Bus;
use Runtime\Web\BusInterface;
use Runtime\Web\RouteInfo;
use Runtime\Web\Hooks\AppHook;


class BaseLayoutModel extends \Runtime\BaseLayout
{
	var $component;
	var $title;
	var $content_type;
	var $layout_name;
	var $locale;
	var $current_page_class;
	var $current_page_model;
	var $site_name;
	var $is_full_title;
	var $current_page_props;
	
	/* Current route */
	var $route;
	var $request_full_uri;
	var $request_host;
	var $request_https;
	var $request_uri;
	var $request_query;
	
	/* Components list */
	var $routes;
	
	/* Increment assets */
	var $f_inc;
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		$this->layout = $this;
		if ($params == null) return;
		if ($params->has("route")) $this->route = $params->get("route");
	}
	
	
	/**
	 * Init widget settings
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
	}
	
	
	/**
	 * Process frontend data
	 */
	function serialize($serializer, $data)
	{
		$serializer->process($this, "content_type", $data);
		$serializer->process($this, "f_inc", $data);
		$serializer->process($this, "is_full_title", $data);
		$serializer->process($this, "locale", $data);
		$serializer->process($this, "layout_name", $data);
		$serializer->process($this, "request_full_uri", $data);
		$serializer->process($this, "request_host", $data);
		$serializer->process($this, "request_https", $data);
		$serializer->process($this, "request_query", $data);
		$serializer->process($this, "request_uri", $data);
		$serializer->process($this, "route", $data);
		$serializer->process($this, "routes", $data);
		$serializer->process($this, "site_name", $data);
		$serializer->process($this, "title", $data);
		parent::serialize($serializer, $data);
	}
	
	
	/**
	 * Returns site name
	 */
	function getSiteName(){ return $this->site_name; }
	
	
	/**
	 * Call Api
	 */
	function callApi($params)
	{
		/* Set layout */
		$params->set("layout", $this->layout);
		/* Returns bus */
		$bus = \Runtime\Web\Bus::getApi($params->get("api_name"));
		$api = $bus->send($params);
		return $api;
	}
	
	
	/**
	 * Returns url
	 */
	function url($route_name, $route_params = null, $url_params = null)
	{
		if (!$this->routes->has($route_name)) return null;
		$route = $this->routes->get($route_name);
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
				else
				{
					$url = \Runtime\rs::url_get_add($url, $key, $value);
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
		$res = \Runtime\rtl::getContext()->callHook(\Runtime\Web\Hooks\AppHook::MAKE_URL, new \Runtime\Map([
			"domain" => $domain,
			"layout" => $this,
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
		$this->component = "Runtime.DefaultLayout";
		$this->title = "";
		$this->content_type = "UTF-8";
		$this->layout_name = "default";
		$this->locale = \Runtime\rtl::getContext()->env("LOCALE");
		$this->current_page_class = "";
		$this->current_page_model = "";
		$this->site_name = "";
		$this->is_full_title = false;
		$this->current_page_props = null;
		$this->route = null;
		$this->request_full_uri = "";
		$this->request_host = "";
		$this->request_https = "";
		$this->request_uri = "";
		$this->request_query = null;
		$this->routes = new \Runtime\Map();
		$this->f_inc = "1";
	}
	static function getClassName(){ return "Runtime.Web.BaseLayoutModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}