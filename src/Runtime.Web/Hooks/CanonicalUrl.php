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

use Runtime\Web\BaseLayoutModel;
use Runtime\Web\RenderContainer;
use Runtime\Web\Hooks\SeoModel;
use Runtime\Web\Hooks\AppHook as BaseAppHook;


class CanonicalUrl extends \Runtime\Web\Hooks\AppHook
{
	var $query;
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("query")) $this->query = $params->get("query");
	}
	
	
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		$this->register(static::ROUTE_BEFORE);
	}
	
	
	/**
	 * Route before
	 */
	function route_before($params)
	{
		$container = $params->get("container");
		$layout = $container->layout;
		$seo = $layout->getWidget("seo");
		if (!$seo) return;
		if (!$layout->route) return;
		if ($layout->route->uri == null) return;
		/* Build canonical url */
		$canonical_url = $layout->url($layout->route->name, $layout->route->matches, new \Runtime\Map(["domain" => false]));
		/* Add get parameters */
		$keys = $layout->request_query->keys()->sort();
		for ($i = 0; $i < $keys->count(); $i++)
		{
			$key = $keys->get($i);
			if (!$this->query->has($key)) continue;
			if ($this->query->get($key)->indexOf($layout->route->name) == -1) continue;
			$value = $layout->request_query->get($key);
			$canonical_url = \Runtime\rs::url_get_add($canonical_url, $key, $value);
		}
		/* Set canonical url */
		$seo->setCanonicalUrl($canonical_url);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->query = null;
	}
	static function getClassName(){ return "Runtime.Web.Hooks.CanonicalUrl"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}