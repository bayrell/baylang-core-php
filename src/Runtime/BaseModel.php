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

use Runtime\BaseObject;
use Runtime\RenderContainer;
use Runtime\Listener;
use Runtime\Method;
use Runtime\Serializer;
use Runtime\SerializeInterface;


class BaseModel extends \Runtime\BaseObject implements \Runtime\SerializeInterface
{
	var $layout;
	var $parent_widget;
	var $listener;
	var $component;
	
	
	/**
	 * Create model
	 */
	function __construct($params = null)
	{
		parent::__construct();
		/* Setup widget params */
		$this->initParams($params);
		/* Init widget settings */
		$this->initWidget($params);
		/* Add component */
		if ($this->layout != null && $this->component != "")
		{
			$this->layout->addComponent($this->component);
		}
	}
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		if (!$params) return;
		$this->parent_widget = $params->get("parent_widget");
		$this->layout = $this->parent_widget ? $this->parent_widget->layout : null;
		/* Setup params */
		$this->component = $params->has("component") ? $params->get("component") : $this->component;
	}
	
	
	/**
	 * Init widget settings
	 */
	function initWidget($params){}
	
	
	/**
	 * Serialize object
	 */
	function serialize($serializer, $data)
	{
		$serializer->process($this, "component", $data);
	}
	
	
	/**
	 * Load widget data
	 */
	function loadData($container){}
	
	
	/**
	 * Build page title
	 */
	function buildTitle($container){}
	
	
	/**
	 * Create widget
	 */
	function createWidget($class_name, $params = null)
	{
		if ($params == null) $params = new \Runtime\Map();
		if (!$params->has("parent_widget")) $params->set("parent_widget", $this);
		$widget = \Runtime\rtl::newInstance($class_name, new \Runtime\Vector($params));
		return $widget;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->layout = null;
		$this->parent_widget = null;
		$this->listener = new \Runtime\Listener($this);
		$this->component = "";
	}
	static function getClassName(){ return "Runtime.BaseModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}