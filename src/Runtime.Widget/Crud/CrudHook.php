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
namespace Runtime\Widget\Crud;

use Runtime\Web\Hooks\AppHook as BaseAppHook;
use Runtime\Widget\Crud\CrudFactory;
use Runtime\Widget\Crud\SaveApi;
use Runtime\Widget\Crud\SearchApi;
use Runtime\Web\Annotations\Api;
use Runtime\Web\RouteList;


class CrudHook extends \Runtime\Web\Hooks\AppHook
{
	var $crud;
	
	
	/**
	 * Setup hook params
	 */
	function setup($params)
	{
		parent::setup($params);
		if ($params == null) return;
		if ($params->has("crud")) $this->crud = $params->get("crud");
	}
	
	
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		$this->register(static::FIND_API);
		$this->register(static::ROUTE_BEFORE);
		$this->register(static::ROUTES_INIT);
	}
	/**
	 * Find api
	 */
	function find_api($params)
	{
		$api_name = $params->get("api_name");
		$annotation = $params->get("annotation");
		if ($annotation) return;
		/* Setup save api */
		if ($api_name == $this->crud->getApiName("save"))
		{
			$annotation = new \Runtime\Web\Annotations\Api("Runtime.Widget.Crud.SaveApi", new \Runtime\Map([
				"crud" => $this->crud,
			]));
			$params->set("annotation", $annotation);
		}
		/* Setup search api */
		if ($api_name == $this->crud->getApiName("search"))
		{
			$annotation = new \Runtime\Web\Annotations\Api("Runtime.Widget.Crud.SearchApi", new \Runtime\Map([
				"crud" => $this->crud,
			]));
			$params->set("annotation", $annotation);
		}
	}
	
	
	/**
	 * Routes init
	 */
	function routes_init($params)
	{
		$routes = $params->get("routes");
		/* Add route index */
		$route_index_name = $this->crud->getParam("route.index.name");
		if ($route_index_name && !$routes->hasRoute($route_index_name))
		{
			$routes->addRoute($this->crud->createRoute("index"));
		}
	}
	
	
	/**
	 * Route before
	 */
	function route_before($params){}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->crud = null;
	}
	static function getClassName(){ return "Runtime.Widget.Crud.CrudHook"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}