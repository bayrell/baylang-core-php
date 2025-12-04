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

use Runtime\io;
use Runtime\BaseObject;
use Runtime\Curl;
use Runtime\Exceptions\AbstractException;
use Runtime\Exceptions\CurlException;
use Runtime\Web\ApiProvider;
use Runtime\Web\ApiResult;
use Runtime\Web\Bus;
use Runtime\Web\BusInterface;
use Runtime\Web\Hooks\AppHook;


class BusHttp extends \Runtime\BaseObject implements \Runtime\Web\BusInterface
{
	var $kind;
	
	
	/**
	 * Send api to frontend
	 */
	function send($params)
	{
		$service = "";
		$api_name = $params->get("api_name", "");
		$method_name = $params->get("method_name", "");
		$data = $params->get("data", null);
		/* Get api name */
		$name = \Runtime\Web\Bus::parseName($api_name);
		$service = $name->get("service");
		$api_name = $name->get("api_name");
		/* Get route prefix */
		$route_prefix = \Runtime\rtl::getContext()->env("ROUTE_PREFIX");
		$route_prefix = \Runtime\rs::removeFirstSlash($route_prefix);
		/* Get api url */
		$api_url_arr = new \Runtime\Vector(
			$route_prefix,
			$this->kind,
			$service,
			$api_name,
			$method_name,
		);
		$api_url_arr = $api_url_arr->filter(function ($s){ return $s != ""; });
		$api_url = "/" . $api_url_arr->join("/");
		$res = new \Runtime\Web\ApiResult();
		try
		{
			$post_data = new \Runtime\Map([
				"service" => $service,
				"api_name" => $api_name,
				"method_name" => $method_name,
				"data" => $data,
			]);
			/* Call api before hook */
			$d = \Runtime\rtl::getContext()->callHook(\Runtime\Web\Hooks\AppHook::CALL_API_BEFORE, new \Runtime\Map([
				"api_url" => $api_url,
				"post_data" => $post_data,
				"params" => $params,
			]));
			$api_url = $d->get("api_url");
			$post_data = $d->get("post_data");
			/* Send curl */
			$curl = new \Runtime\Curl($api_url, new \Runtime\Map([
				"post" => $post_data,
			]));
			$response = $curl->send();
			/* Get answer */
			$answer = \Runtime\rtl::json_decode($response, \Runtime\rtl::ALLOW_OBJECTS);
			if ($answer && $answer instanceof \Runtime\Dict)
			{
				$res->importContent($answer);
			}
			else
			{
				$res->exception(new \Runtime\Exceptions\AbstractException("Api response error"));
			}
			/* Print exception */
			if (\Runtime\rtl::getContext()->env("DEBUG") && $res->isException() && $answer)
			{
				$arr = new \Runtime\Vector();
				$arr->push($res->error_name . " " . "in file " . $res->error_file . ":" . $res->error_line);
				$arr->appendItems($res->error_trace->map(function ($value, $pos){ return $pos + 1 . ") " . $value; }));
				\Runtime\io::print_error(\Runtime\rs::join("\n", $arr));
			}
		}
		catch (\Runtime\Exceptions\CurlException $e)
		{
			$res->exception($e);
			$res->ob_content = $e->http_content;
			if (\Runtime\rtl::getContext()->env("DEBUG"))
			{
				\Runtime\io::print_error($e->http_content);
			}
		}
		catch (\Exception $e)
		{
			throw $e;
		}
		return $res;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->kind = "api";
	}
	static function getClassName(){ return "Runtime.Web.BusHttp"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}