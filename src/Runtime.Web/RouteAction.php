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

use Runtime\Method;
use Runtime\Serializer\ObjectType;
use Runtime\Entity\Factory;
use Runtime\Web\BaseRoute;
use Runtime\Web\RouteInfo;
use Runtime\Web\RenderContainer;


class RouteAction extends \Runtime\Web\RouteInfo
{
	var $action;
	
	
	/**
	 * Process frontend data
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
	}
	
	
	/**
	 * Copy route
	 */
	function copy()
	{
		$route = parent::copy();
		$route->action = $this->action;
		return $route;
	}
	
	
	/**
	 * Compile
	 */
	function compile()
	{
		parent::compile();
		if (\Runtime\rtl::methodExists($this->route_class, $this->action))
		{
			$this->action = new \Runtime\Method($this->route_class, $this->action);
		}
		else
		{
			$this->action = new \Runtime\Entity\Factory($this->route_class, new \Runtime\Map(["action" => $this->action]));
		}
	}
	
	
	/**
	 * Render route
	 */
	function render($container)
	{
		$action = null;
		/* Get action */
		if ($this->action instanceof \Runtime\Method) $action = $this->action;
		else if ($this->action instanceof \Runtime\Entity\Factory)
		{
			$base_route = $this->action->createInstance();
			$action = new \Runtime\Method($base_route, $base_route->action);
		}
		/* Apply action */
		if ($action && $action->exists())
		{
			$action->apply(new \Runtime\Vector($container));
		}
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->action = null;
	}
	static function getClassName(){ return "Runtime.Web.RouteAction"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}