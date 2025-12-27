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
use Runtime\BaseProvider;
use Runtime\BusInterface;
use Runtime\Method;
use Runtime\Exceptions\ApiError;
use Runtime\Exceptions\ItemNotFound;
use Runtime\Exceptions\RuntimeException;
use Runtime\Web\ApiResult;
use Runtime\Web\ApiRequest;
use Runtime\Web\BaseApi;
use Runtime\Web\Bus;
use Runtime\Web\Annotations\Api;
use Runtime\Web\Annotations\ApiMethod;
use Runtime\Web\Hooks\AppHook;


class BusLocal extends \Runtime\BaseProvider implements \Runtime\BusInterface
{
	var $api_list;
	
	
	/**
	 * Init providers
	 */
	function init()
	{
		parent::init();
		$this->api_list = new \Runtime\Map();
		$api_list = \Runtime\rtl::getContext()->getEntities("Runtime.Web.Annotations.Api");
		for ($i = 0; $i < $api_list->count(); $i++)
		{
			$api = $api_list->get($i);
			$class_name = $api->name;
			$getApiName = new \Runtime\Method($class_name, "getApiName");
			/* Save api */
			$api_name = $getApiName->apply();
			if (!$this->api_list->has($api_name)) $this->api_list->set($api_name, new \Runtime\Vector());
			$items = $this->api_list->get($api_name);
			$items->push($api);
		}
	}
	
	
	/**
	 * Find api
	 */
	function findApi($api_name, $method_name)
	{
		$result = new \Runtime\Map();
		/* Check params */
		if ($api_name == "" || $method_name == "")
		{
			throw new \Runtime\Exceptions\ApiError(new \Runtime\Exceptions\ItemNotFound($api_name . "::" . $method_name, "Api"));
		}
		/* Find api */
		$items = $this->api_list->get($api_name);
		if (!$items)
		{
			throw new \Runtime\Exceptions\ApiError(new \Runtime\Exceptions\ItemNotFound($api_name . "::" . $method_name, "Api"));
		}
		/* Find method */
		$api_method = null;
		$items->each(function ($api) use (&$api_method, &$method_name)
		{
			if ($api_method) return;
			$getMethodsList = new \Runtime\Method($api->name, "getMethodsList");
			$getMethodInfoByName = new \Runtime\Method($api->name, "getMethodInfoByName");
			$methods = $getMethodsList->apply();
			for ($i = 0; $i < $methods->count(); $i++)
			{
				$name = $methods->get($i);
				$annotations = $getMethodInfoByName->apply(new \Runtime\Vector($name));
				$api_method_item = $annotations->find(function ($obj){ return $obj instanceof \Runtime\Web\Annotations\ApiMethod; });
				if ($api_method_item && $api_method_item->name == $method_name)
				{
					$api_item = \Runtime\rtl::newInstance($api->name);
					$api_method = $api_method_item;
					$api_method->item = new \Runtime\Method($api_item, $name);
					return;
				}
			}
		});
		return $api_method;
	}
	
	
	/**
	 * Send api to frontend
	 */
	function send($params)
	{
		$api_name = $params->get("api_name");
		$method_name = $params->get("method_name");
		$result = null;
		try
		{
			$api_method = $this->findApi($api_name, $method_name);
			if ($api_method == null || $api_method->item == null)
			{
				throw new \Runtime\Exceptions\ApiError(new \Runtime\Exceptions\ItemNotFound($api_name . "::" . $method_name, "Api"));
			}
			/* Call method */
			$request = new \Runtime\Web\ApiRequest(new \Runtime\Map([
				"data" => $params->get("data"),
				"storage" => $params->get("storage"),
			]));
			$api_method->item->obj->setRequest($request);
			$api_method->apply($request);
			$result = $api_method->item->obj->result;
		}
		catch (\Runtime\Exceptions\RuntimeException $e)
		{
			$result = new \Runtime\Web\ApiResult();
			if ($e instanceof \Runtime\Exceptions\ApiError)
			{
				if ($e->result instanceof \Runtime\Web\ApiResult) $result = $e->result;
				else $result->fail($e->prev);
			}
			else $result->exception($e);
		}
		/* If result does no exists */
		if (!$result)
		{
			$result = new \Runtime\Web\ApiResult();
			$result->exception(new \Runtime\Exceptions\ItemNotFound("ApiResult"));
		}
		/* Set api name */
		$result->api_name = $api_name;
		$result->method_name = $method_name;
		/* Return result */
		return $result;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->api_list = new \Runtime\Map();
	}
	static function getClassName(){ return "Runtime.Web.BusLocal"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}