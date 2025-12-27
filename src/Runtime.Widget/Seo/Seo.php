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
namespace Runtime\Widget\Seo;

use Runtime\BaseModel;
use Runtime\VirtualDom;
use Runtime\Entity\Hook;
use Runtime\Hooks\BaseHook;
use Runtime\Hooks\RuntimeHook;
use Runtime\Web\RenderContainer;
use Runtime\Web\Hooks\AppHook;
use Runtime\Widget\Seo\SeoModel;


class Seo extends \Runtime\Web\Hooks\AppHook
{
	var $class_name;
	
	
	/**
	 * Create hook
	 */
	static function hook($params = null){ return new \Runtime\Entity\Hook("Runtime.Widget.Seo.Seo", $params); }
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if (!$params) return;
		if ($params->has("class_name")) $this->class_name = $params->get("class_name");
	}
	
	
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		$this->register(\Runtime\Hooks\RuntimeHook::CREATE_LAYOUT, "createLayout");
		$this->register(\Runtime\Hooks\RuntimeHook::LAYOUT_HEADER, "renderHeader");
		$this->register(\Runtime\Web\Hooks\AppHook::ROUTE_BEFORE, "routeBefore");
	}
	
	
	/**
	 * Create layout
	 */
	function createLayout($params)
	{
		$container = $params->get("container");
		$container->layout->storage->set("seo", $container->layout->createWidget("Runtime.Widget.Seo.SeoModel"));
	}
	
	
	/**
	 * Render head
	 */
	function renderHeader($params)
	{
		$layout = $params->get("layout");
		$seo = $layout->get("seo");
		if ($seo) $params->get("components")->push(\Runtime\VirtualDom::renderModel($seo));
	}
	
	
	/**
	 * Route before
	 */
	function routeBefore($params)
	{
		$container = $params->get("container");
		/* If page model exists */
		if ($container->route == null) return;
		if ($container->route->data == null) return;
		/* Setup route data */
		$seo = $container->layout->get("seo");
		$route_data = $container->route->data;
		if ($route_data->has("title")) $container->layout->setPageTitle($route_data->get("title"));
		if ($route_data->has("description")) $seo->description = $route_data->get("description");
		if ($route_data->has("robots")) $seo->robots = $route_data->get("robots");
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->class_name = "Runtime.Widget.Seo.SeoModel";
	}
	static function getClassName(){ return "Runtime.Widget.Seo.Seo"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}