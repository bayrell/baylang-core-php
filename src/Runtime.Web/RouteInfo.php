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

use Runtime\re;
use Runtime\BaseObject;
use Runtime\Method;
use Runtime\SerializeInterface;
use Runtime\Serializer;
use Runtime\Entity\Factory;
use Runtime\Exceptions\ItemNotFound;
use Runtime\Exceptions\RuntimeException;
use Runtime\Web\BaseMiddleware;
use Runtime\Web\BasePageModel;
use Runtime\Web\BaseRoute;
use Runtime\Web\RenderContainer;
use Runtime\Web\RouteList;


class RouteInfo extends \Runtime\BaseObject implements \Runtime\SerializeInterface
{
	var $name;
	var $uri;
	var $uri_match;
	var $domain;
	var $label;
	var $layout;
	var $route_class;
	var $data;
	var $middleware;
	var $params;
	var $matches;
	var $is_backend;
	var $pos;
	
	
	/**
	 * Constructor
	 */
	function __construct($params = null)
	{
		parent::__construct();
		$this->_assign_values($params);
	}
	
	
	/**
	 * Process frontend data
	 */
	function serialize($serializer, $data)
	{
		$serializer->process($this, "data", $data);
		$serializer->process($this, "domain", $data);
		$serializer->process($this, "label", $data);
		$serializer->process($this, "layout", $data);
		$serializer->process($this, "matches", $data);
		$serializer->process($this, "middleware", $data);
		$serializer->process($this, "name", $data);
		$serializer->process($this, "params", $data);
		$serializer->process($this, "pos", $data);
		$serializer->process($this, "route_class", $data);
		$serializer->process($this, "uri", $data);
		$serializer->process($this, "uri_match", $data);
	}
	
	
	/**
	 * Copy route
	 */
	function copy()
	{
		return \Runtime\Serializer::copy($this);
	}
	
	
	/**
	 * Returns layout name
	 */
	function getLayoutName()
	{
		if ($this->layout != "") return $this->layout;
		if ($this->route_class != "")
		{
			$f = new \Runtime\Method($this->route_class, "getLayoutName");
			return $f->apply();
		}
		return "";
	}
	
	
	/**
	 * Compile route
	 */
	function compile()
	{
		if ($this->uri_match == "")
		{
			$this->uri_match = "^" . \Runtime\re::replace("\\/", "\\/", $this->uri) . "\$";
		}
		$matches = \Runtime\re::matchAll("{(.*?)}", $this->uri);
		if ($matches)
		{
			$params = new \Runtime\Vector();
			for ($i = 0; $i < $matches->count(); $i++)
			{
				$arr = $matches[$i];
				$name = $arr[1];
				$this->uri_match = \Runtime\re::replace("{" . $name . "}", "([^\\/]*?)", $this->uri_match);
				$this->params->push($name);
			}
		}
		else
		{
			$this->params = new \Runtime\Vector();
		}
	}
	
	
	/**
	 * Add matches
	 */
	function addMatches($matches)
	{
		for ($i = 0; $i < $this->params->count(); $i++)
		{
			$param_name = $this->params->get($i);
			$match_value = $matches->get($i);
			$this->matches->set($param_name, $match_value);
		}
	}
	
	
	/**
	 * Call middleware
	 */
	function callMiddleware($container)
	{
		if ($this->middleware)
		{
			for ($i = 0; $i < $this->middleware->count(); $i++)
			{
				$middleware = null;
				$item = $this->middleware->get($i);
				/* Create middleware */
				if (\Runtime\rtl::isString($item))
				{
					$middleware = \Runtime\rtl::newInstance($item);
				}
				else if ($item instanceof \Runtime\Entity\Factory)
				{
					$middleware = $item->factory();
				}
				else if ($item instanceof \Runtime\Web\BaseMiddleware)
				{
					$middleware = $item;
				}
				/* Apply middleware */
				$middleware->apply($container);
			}
		}
		/* Call route class middleware */
		if ($this->route_class)
		{
			$class_name = $this->route_class;
			$getMiddleware = new \Runtime\Method($class_name, "getMiddleware");
			if ($getMiddleware->exists())
			{
				$items = $getMiddleware->apply();
				for ($i = 0; $i < $items->count(); $i++)
				{
					$middleware = $items->get($i);
					$middleware->apply($container);
				}
			}
		}
	}
	
	
	/**
	 * Render route
	 */
	function render($container)
	{
		throw new \Runtime\Exceptions\RuntimeException("RouteInfo is abstract class");
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = null;
		$this->uri = null;
		$this->uri_match = null;
		$this->domain = null;
		$this->label = null;
		$this->layout = null;
		$this->route_class = null;
		$this->data = null;
		$this->middleware = null;
		$this->params = new \Runtime\Vector();
		$this->matches = new \Runtime\Map();
		$this->is_backend = false;
		$this->pos = 100;
	}
	static function getClassName(){ return "Runtime.Web.RouteInfo"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}