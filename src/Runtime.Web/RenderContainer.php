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
use Runtime\Callback;
use Runtime\Component;
use Runtime\RenderContainer as BaseRenderContainer;
use Runtime\Serializer;
use Runtime\VirtualDom;
use Runtime\Entity\Factory;
use Runtime\Providers\RenderContent;
use Runtime\Web\ApiResult;
use Runtime\Web\BaseLayoutModel;
use Runtime\Web\BaseMiddleware;
use Runtime\Web\BaseModel;
use Runtime\Web\BasePageModel;
use Runtime\Web\BaseRoute;
use Runtime\Web\Cookie;
use Runtime\Web\Layout;
use Runtime\Web\RenderResponse;
use Runtime\Web\Request;
use Runtime\Web\Response;
use Runtime\Web\RouteInfo;
use Runtime\Web\RouteProvider;
use Runtime\Web\Hooks\AppHook;


class RenderContainer extends \Runtime\RenderContainer
{
	var $request;
	var $response;
	var $route;
	var $layout;
	var $cookies;
	var $http_code;
	
	
	/**
	 * Resolve container
	 */
	function resolve()
	{
		/* Find route */
		$this->findRoute();
		/* Resolve route */
		$this->resolveRoute();
		/* Create response */
		$this->createResponse();
	}
	
	
	/**
	 * Find route
	 */
	function findRoute()
	{
		/* Call hook find route */
		\Runtime\rtl::getContext()->hook(\Runtime\Web\Hooks\AppHook::FIND_ROUTE_BEFORE, new \Runtime\Map([
			"container" => $this,
		]));
		/* Exit if route find */
		if ($this->route != null) return;
		if ($this->response != null) return;
		/* Find route */
		$routes = \Runtime\rtl::getContext()->provider("Runtime.Web.RouteProvider");
		$this->route = $routes->findRoute($this->request);
		/* Call hook found route */
		\Runtime\rtl::getContext()->hook(\Runtime\Web\Hooks\AppHook::FIND_ROUTE_AFTER, new \Runtime\Map([
			"container" => $this,
		]));
	}
	
	
	/**
	 * Resolve route
	 */
	function resolveRoute()
	{
		if (!$this->route) return;
		if ($this->response) return;
		/* Create layout */
		$layout_name = $this->route->getLayoutName();
		$this->createLayout($layout_name);
		/* Call route before */
		\Runtime\rtl::getContext()->hook(\Runtime\Web\Hooks\AppHook::ROUTE_BEFORE, new \Runtime\Map([
			"container" => $this,
		]));
		/* Call middleware */
		$this->callRouteMiddleware($this);
		/* Load layout data */
		$this->layout->loadData($this);
		/* Render route */
		if ($this->route != null && $this->response == null)
		{
			$this->route->render($this);
		}
		/* Call route after */
		\Runtime\rtl::getContext()->hook(\Runtime\Web\Hooks\AppHook::ROUTE_AFTER, new \Runtime\Map([
			"container" => $this,
		]));
	}
	
	
	/**
	 * Call route middleware
	 */
	function callRouteMiddleware()
	{
		if ($this->route)
		{
			$this->route->callMiddleware($this);
		}
		/* Call hook middleware */
		\Runtime\rtl::getContext()->hook(\Runtime\Web\Hooks\AppHook::ROUTE_MIDDLEWARE, new \Runtime\Map([
			"container" => $this,
		]));
	}
	
	
	/**
	 * Create response
	 */
	function createResponse()
	{
		if ($this->response) return;
		if (!$this->layout) return;
		/* Create response */
		$this->response = new \Runtime\Web\Response();
		$this->response->http_code = $this->http_code;
		$this->response->content = $this->renderApp();
	}
	
	
	/**
	 * Set response
	 */
	function setResponse($response)
	{
		$this->response = $response;
	}
	
	
	/**
	 * Add cookie
	 */
	function addCookie($cookie)
	{
		$this->cookies->set($cookie->name, $cookie);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->request = null;
		$this->response = null;
		$this->route = null;
		$this->layout = null;
		$this->cookies = new \Runtime\Map();
		$this->http_code = 200;
	}
	static function getClassName(){ return "Runtime.Web.RenderContainer"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}