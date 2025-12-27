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
namespace Runtime;

use Runtime\ApiResult;
use Runtime\BaseProvider;
use Runtime\BusInterface;
use Runtime\Curl;
use Runtime\Exceptions\ApiError;
use Runtime\Exceptions\CurlException;
use Runtime\Exceptions\RuntimeException;
use Runtime\Hooks\RuntimeHook;


class BusHttp extends \Runtime\BaseProvider implements \Runtime\BusInterface
{
	/**
	 * Send api to frontend
	 */
	function send($params)
	{
		$service = $params->get("service", "");
		$api_name = $params->get("api_name", "");
		$method_name = $params->get("method_name", "");
		$data = $params->get("data", null);
		/* Get route prefix */
		$api_kind = "api";
		$route_prefix = \Runtime\rtl::getContext()->env("ROUTE_PREFIX");
		$route_prefix = \Runtime\rs::removeFirstSlash($route_prefix);
		$route_prefix = \Runtime\rs::removeLastSlash($route_prefix);
		/* Get api url */
		$api_url_arr = new \Runtime\Vector(
			$route_prefix,
			$api_kind,
			$api_name,
			$method_name,
		);
		$api_url_arr = $api_url_arr->filter(function ($s){ return $s != ""; });
		$api_url = "/" . $api_url_arr->join("/");
		$res = new \Runtime\ApiResult();
		/* Call api before hook */
		$d = \Runtime\rtl::getContext()->hook(\Runtime\Hooks\RuntimeHook::SEND_API_BEFORE, new \Runtime\Map([
			"api_url" => $api_url,
			"service" => $service,
			"api_name" => $api_name,
			"method_name" => $method_name,
			"params" => $params,
			"data" => $data,
		]));
		$api_url = $d->get("api_url");
		/* Create curl */
		$curl = new \Runtime\Curl($api_url, new \Runtime\Map([
			"post" => $d->get("data"),
		]));
		/* Send curl */
		try
		{
			$curl->send();
		}
		catch (\Runtime\Exceptions\CurlException $e)
		{
			$res->exception($e);
		}
		/* Get answer */
		$answer = \Runtime\rtl::jsonDecode($curl->response);
		if ($answer && $answer instanceof \Runtime\Map)
		{
			$res->importContent($answer);
		}
		else
		{
			$res->ob_content = $curl->response;
		}
		/* Print content */
		if (\Runtime\rtl::getContext()->env("DEBUG") && $res->ob_content)
		{
			\Runtime\rtl::error($res->ob_content);
		}
		/* Print exception */
		if (\Runtime\rtl::getContext()->env("DEBUG") && $res->isException() && $res->error_trace)
		{
			$arr = new \Runtime\Vector(
				"Error message: " . $res->message,
				"in file " . $res->error_file . ":" . $res->error_line,
			);
			$arr->appendItems($res->error_trace->map(function ($value, $pos){ return $pos + 1 . ") " . $value; }));
			\Runtime\rtl::error(\Runtime\rs::join("\n", $arr));
		}
		return $res;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.BusHttp"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}