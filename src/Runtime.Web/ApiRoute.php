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

use Runtime\Exceptions\ApiError;
use Runtime\Exceptions\RuntimeException;
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
				"uri" => "/api/{api_name}/{method_name}",
				"name" => "runtime:web:api",
				"action" => "actionIndex",
				"pos" => 1000,
			])),
			new \Runtime\Web\RouteAction(new \Runtime\Map([
				"uri" => "/api/{api_name}/{method_name}/",
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
		$api_name = $container->route->matches->get("api_name");
		$method_name = $container->route->matches->get("method_name");
		$api_result = null;
		$bus = \Runtime\rtl::getContext()->provider("api");
		$payload = $container->request->payload;
		$api_result = $bus->send(new \Runtime\Map([
			"api_name" => $api_name,
			"method_name" => $method_name,
			"data" => $payload,
			"storage" => $container->layout->storage->backend,
		]));
		/* Create response */
		$container->setResponse(new \Runtime\Web\JsonResponse($api_result->getContent()));
		/* Set cookie */
		if ($api_result instanceof \Runtime\Web\ApiResult)
		{
			$api_result->cookies->each(function ($cookie) use (&$container)
			{
				$container->addCookie($cookie);
			});
		}
		/* HTTP error if exception */
		if ($api_result->isException())
		{
			$container->response->http_code = 500;
		}
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