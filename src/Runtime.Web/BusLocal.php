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

use Runtime\lib;
use Runtime\BaseProvider;
use Runtime\Callback;
use Runtime\Exceptions\ApiError;
use Runtime\Exceptions\ItemNotFound;
use Runtime\Web\ApiResult;
use Runtime\Web\BaseApi;
use Runtime\Web\Bus;
use Runtime\Web\BusInterface;
use Runtime\Web\Annotations\Api;
use Runtime\Web\Annotations\ApiMethod;
use Runtime\Web\Hooks\AppHook;


class BusLocal extends \Runtime\BaseProvider implements \Runtime\Web\BusInterface
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
			$getApiName = new \Runtime\Callback($class_name, "getApiName");
			/* Save api */
			$api_name = \Runtime\rtl::apply($getApiName);
			$this->api_list->set($api_name, $api);
		}
	}
	
	
	/**
	 * Send api to frontend
	 */
	function send($params)
	{
		$api_name = $params->get("api_name");
		$method_name = $params->get("method_name");
		/* Get api name */
		$res = \Runtime\Web\Bus::parseName($api_name);
		$api_name = $res->get("api_name");
		/* Call local api */
		try
		{
			$annotation = $this->findApi($params);
			$result = $this->callAnnotation($annotation, $params);
		}
		catch (\Runtime\Exceptions\ApiError $e)
		{
			$result = new \Runtime\Web\ApiResult();
			$result->fail($e->getPreviousException());
		}
		/* Set api name */
		$result->api_name = $api_name;
		$result->method_name = $method_name;
		return $result;
	}
	
	
	/**
	 * Find local api
	 */
	function findApi($params)
	{
		$api_name = $params->get("api_name");
		$method_name = $params->get("method_name");
		/* Get annotation by api name */
		$annotation = $this->api_list->get($api_name);
		/* Call find api hook */
		$res = \Runtime\rtl::getContext()->callHook(\Runtime\Web\Hooks\AppHook::FIND_API, new \Runtime\Map([
			"api_name" => $api_name,
			"annotation" => $annotation,
		]));
		$annotation = $res->get("annotation");
		/* Annotation not found */
		if ($annotation == null)
		{
			throw new \Runtime\Exceptions\ApiError(new \Runtime\Exceptions\ItemNotFound($api_name, "Api annotation"));
		}
		/* Find method */
		$getMethodInfoByName = new \Runtime\Callback($annotation->name, "getMethodInfoByName");
		$method_info = $getMethodInfoByName->apply(new \Runtime\Vector($method_name));
		/* Method not found */
		if ($method_info == null)
		{
			throw new \Runtime\Exceptions\ApiError(new \Runtime\Exceptions\ItemNotFound($method_name . " in " . $api_name, "Api method"));
		}
		/* Check if method is api */
		$api_method = $method_info->get("annotations")->findItem(\Runtime\lib::isInstance("Runtime.Web.Annotations.ApiMethod"));
		if ($api_method == null)
		{
			throw new \Runtime\Exceptions\ApiError(new \Runtime\Exceptions\ItemNotFound($method_name . " in " . $api_name, "Api method"));
		}
		/* Set props */
		$result = new \Runtime\Map([
			"api" => $annotation,
			"api_method" => $api_method,
		]);
		return $result;
	}
	
	
	/**
	 * Call annotation
	 */
	function callAnnotation($annotation, $params)
	{
		$api = $annotation->get("api");
		$method_name = $params->get("method_name");
		/* Create api instance */
		$api_instance = \Runtime\rtl::newInstance($api->name, new \Runtime\Vector($api->params));
		$api_instance->action = $method_name;
		$api_instance->layout = $params->get("layout");
		$api_instance->post_data = $params->get("data");
		$api_instance->result = new \Runtime\Web\ApiResult();
		$api_instance->init();
		/* Create callback */
		$callback = new \Runtime\Callback($api_instance, $method_name);
		/* Call api */
		try
		{
			$api_instance->onActionBefore();
			$callback->apply();
			$api_instance->onActionAfter();
		}
		catch (\Runtime\Exceptions\ApiError $e)
		{
			$api_instance->result->fail($e->getPreviousException());
		}
		/* Return api result */
		return $api_instance->result;
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