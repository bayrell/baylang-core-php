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

use Runtime\BaseModel;
use Runtime\Entity\Hook;
use Runtime\Hooks\RuntimeHook;
use Runtime\Web\RenderContainer;
use Runtime\Web\RouteInfo;
use Runtime\Web\RouteModel;
use Runtime\Web\Hooks\AppHook;


class PageNotFound extends \Runtime\Hooks\RuntimeHook
{
	var $model;
	var $layout_name;
	
	
	/**
	 * Hook factory
	 */
	static function hook($model, $layout = "default")
	{
		return new \Runtime\Entity\Hook(static::getClassName(), new \Runtime\Map(["model" => $model, "layout" => $layout]));
	}
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params->has("model")) $this->model = $params->get("model");
	}
	
	
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		parent::register_hooks();
		$this->register(\Runtime\Web\Hooks\AppHook::FIND_ROUTE_AFTER, "routeNotFound");
		$this->register(\Runtime\Web\Hooks\AppHook::ROUTE_AFTER, "renderPageNotFound");
	}
	
	
	/**
	 * Route not found
	 */
	function routeNotFound($params)
	{
		$container = $params->get("container");
		if ($container->route) return null;
		/* Set route */
		$container->route = new \Runtime\Web\RouteModel(new \Runtime\Map([
			"model" => $this->model,
			"layout" => $this->layout_name,
		]));
	}
	
	
	/**
	 * Render page not found
	 */
	function renderPageNotFound($params)
	{
		$container = $params->get("container");
		if ($container->response) return;
		/* Create default layout */
		if ($container->layout == null)
		{
			$container->createLayout($this->layout_name);
		}
		/* Get page model */
		$page_model = $container->layout->getPageModel();
		if ($page_model) return;
		$container->renderPageModel($this->model);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->model = "";
		$this->layout_name = "default";
	}
	static function getClassName(){ return "Runtime.Web.Hooks.PageNotFound"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}