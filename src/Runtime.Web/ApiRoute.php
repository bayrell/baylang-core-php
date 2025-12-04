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

use Runtime\Exceptions\AbstractException;
use Runtime\Web\ApiProvider;
use Runtime\Web\ApiResult;
use Runtime\Web\BaseRoute;
use Runtime\Web\Bus;
use Runtime\Web\BusInterface;
use Runtime\Web\BusLocal;
use Runtime\Web\Cookie;
use Runtime\Web\JsonResponse;
use Runtime\Web\RenderContainer;
use Runtime\Web\RouteAction;
use Runtime\Web\RouteInfo;


class ApiRoute extends \Runtime\Web\BaseRoute
{
	/**
	 * Returns routes
	 */
	static function getRoutes()
	{
		return new \Runtime\Vector(
			new \Runtime\Web\RouteAction(new \Runtime\Map([
				"uri" => "/api/{service}/{api_name}/{method_name}",
				"name" => "runtime:web:api",
				"action" => "actionIndex",
				"pos" => 1000,
			])),
			new \Runtime\Web\RouteAction(new \Runtime\Map([
				"uri" => "/api/{service}/{api_name}/{method_name}/",
				"name" => "runtime:web:api:index",
				"action" => "actionIndex",
				"pos" => 1000,
			])),
		);
	}
	
	
	/**
	 * Action index
	 */
	static function actionIndex($container)
	{
		/* Call api */
		$api_name = $container->route->matches["api_name"];
		$method_name = $container->route->matches["method_name"];
		$api_result = null;
		$bus = \Runtime\Web\Bus::getApi($api_name);
		try
		{
			$api_result = $bus->send(new \Runtime\Map([
				"api_name" => $api_name,
				"method_name" => $method_name,
				"layout" => $container->layout,
				"data" => $container->request->payload->get("data"),
				"container" => $container,
			]));
		}
		catch (\Runtime\Exceptions\AbstractException $e)
		{
			$api_result = new \Runtime\Web\ApiResult();
			$api_result->exception($e);
		}
		/* Create response */
		$container->setResponse(new \Runtime\Web\JsonResponse($api_result->getContent()));
		/* Set cookie */
		$api_result->cookies->each(function ($cookie) use (&$container)
		{
			$container->addCookie($cookie);
		});
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Web.ApiRoute"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}