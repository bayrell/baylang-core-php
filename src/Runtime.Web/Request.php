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
use Runtime\SerializeInterface;
use Runtime\Serializer\MapType;
use Runtime\Serializer\ObjectType;
use Runtime\Serializer\StringType;
use Runtime\Web\Cookie;
use Runtime\Web\Hooks\AppHook;


class Request extends \Runtime\BaseObject implements \Runtime\SerializeInterface
{
	const METHOD_GET = "GET";
	const METHOD_HEAD = "HEAD";
	const METHOD_POST = "POST";
	const METHOD_PUT = "PUT";
	const METHOD_DELETE = "DELETE";
	const METHOD_CONNECT = "CONNECT";
	const METHOD_OPTIONS = "OPTIONS";
	const METHOD_TRACE = "TRACE";
	const METHOD_PATCH = "PATCH";
	
	var $uri;
	var $full_uri;
	var $host;
	var $method;
	var $protocol;
	var $is_https;
	var $query;
	var $payload;
	var $cookies;
	var $headers;
	var $start_time;
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("uri", new \Runtime\Serializer\StringType());
		$rules->addType("full_uri", new \Runtime\Serializer\StringType());
		$rules->addType("host", new \Runtime\Serializer\StringType());
		$rules->addType("method", new \Runtime\Serializer\StringType());
		$rules->addType("protocol", new \Runtime\Serializer\StringType());
		$rules->addType("is_https", new \Runtime\Serializer\StringType());
		$rules->addType("query", new \Runtime\Serializer\MapType(new \Runtime\Serializer\StringType()));
	}
	
	
	/**
	 * Assign rules
	 */
	function assignRules($rules){}
	
	
	/**
	 * Returns client ip
	 */
	function getClientIP()
	{
		$params = \Runtime\rtl::getContext()->hook(\Runtime\Web\Hooks\AppHook::CLIENT_IP, new \Runtime\Map([
			"headers" => $this->headers,
			"client_ip" => $this->headers->get("REMOTE_ADDR"),
		]));
		return $params->get("client_ip");
	}
	
	
	/**
	 * Init request
	 */
	function initUri($full_uri)
	{
		$res = \Runtime\rs::parse_url($full_uri);
		$uri = $res->get("uri");
		$query = $res->get("query_arr");
		$this->full_uri = $full_uri;
		$this->uri = $uri;
		$this->query = $query;
		if ($this->uri == "") $this->uri = "/";
		/* Route prefix */
		$route_prefix = \Runtime\rtl::getContext()->env("ROUTE_PREFIX");
		if ($route_prefix == null) $route_prefix = "";
		$route_prefix_sz = \Runtime\rs::strlen($route_prefix);
		if ($route_prefix_sz != 0 && \Runtime\rs::substr($this->uri, 0, $route_prefix_sz) == $route_prefix)
		{
			$this->uri = \Runtime\rs::substr($this->uri, $route_prefix_sz);
			$this->full_uri = \Runtime\rs::substr($this->full_uri, $route_prefix_sz);
		}
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->uri = "";
		$this->full_uri = "";
		$this->host = "";
		$this->method = "GET";
		$this->protocol = "";
		$this->is_https = false;
		$this->query = null;
		$this->payload = null;
		$this->cookies = null;
		$this->headers = null;
		$this->start_time = 0;
	}
	static function getClassName(){ return "Runtime.Web.Request"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}