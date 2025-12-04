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

use Runtime\Callback;
use Runtime\Entity\Hook;
use Runtime\Web\BaseModel;
use Runtime\Web\RenderContainer;
use Runtime\Web\Hooks\AppHook as BaseAppHook;


class WidgetModelFactory extends \Runtime\Web\Hooks\AppHook
{
	var $model_name;
	
	
	/**
	 * Create hook
	 */
	static function hook($model_name)
	{
		return new \Runtime\Entity\Hook(static::getClassName(), new \Runtime\Map(["model_name" => $model_name]));
	}
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("model_name")) $this->model_name = $params->get("model_name");
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
		if ($container->response != null) return;
		/* Add widget */
		$container->layout->addWidget($this->model_name);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->model_name = "";
	}
	static function getClassName(){ return "Runtime.Web.Hooks.WidgetModelFactory"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}