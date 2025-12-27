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
use Runtime\BaseModel;
use Runtime\Component;
use Runtime\Providers\RenderContent;


class VirtualDom extends \Runtime\BaseObject
{
	var $component;
	var $attrs;
	var $slots;
	var $items;
	var $is_raw;
	var $is_render;
	var $is_component;
	var $name;
	
	
	/**
	 * Constructor
	 */
	function __construct($component = null)
	{
		parent::__construct();
		$this->component = $component;
	}
	
	
	/**
	 * Returns true if tag_name is component
	 */
	static function isComponent($tag_name)
	{
		if ($tag_name == "") return false;
		$first = \Runtime\rs::substr($tag_name, 0, 1);
		return \Runtime\rs::upper($first) == $first;
	}
	
	
	/**
	 * Set name
	 */
	function setName($name)
	{
		$this->name = $name;
		$this->is_component = static::isComponent($name);
	}
	
	
	/**
	 * Set attrs
	 */
	function setAttrs($attrs)
	{
		if ($attrs) $this->attrs = $attrs;
	}
	
	
	/**
	 * Add element
	 */
	function element($name, $attrs = null)
	{
		$item = static::newInstance(new \Runtime\Vector($this->component));
		$item->setName($name);
		$item->setAttrs($attrs);
		if ($name == "script" || $name == "style") $item->is_raw = true;
		$this->push($item);
		return $item;
	}
	
	
	/**
	 * Push content
	 */
	function push($content)
	{
		if (\Runtime\rtl::isString($content) && $content == "") return;
		if (!($content instanceof \Runtime\VirtualDom) && !\Runtime\rtl::isString($content))
		{
			$content = \Runtime\rtl::toStr($content);
		}
		if ($this->items->count() > 0 && \Runtime\rtl::isString($content))
		{
			$item = $this->items->last();
			if (\Runtime\rtl::isString($item))
			{
				$this->items->set($this->items->count() - 1, $item . $content);
				return;
			}
		}
		$this->items->push($content);
	}
	
	
	/**
	 * Add slot
	 */
	function slot($slot_name, $content)
	{
		$this->slots->set($slot_name, $content);
	}
	
	
	/**
	 * Render vdom to string
	 */
	function render()
	{
		$content = new \Runtime\Vector();
		$provider = new \Runtime\Providers\RenderContent();
		$provider->components = \Runtime\rtl::getContext()->provider("render")->components;
		$provider->render($this, $content);
		return \Runtime\rs::join("", $content);
	}
	
	
	/**
	 * Raw string
	 */
	static function raw($content)
	{
		$vdom = new \Runtime\VirtualDom();
		$vdom->is_raw = true;
		$vdom->push($content);
		return $vdom;
	}
	
	
	/**
	 * Render model
	 */
	static function renderModel($model)
	{
		$vdom = new \Runtime\VirtualDom();
		$vdom->setName($model->component);
		$vdom->attrs = new \Runtime\Map(["model" => $model, "layout" => $model->layout]);
		return $vdom;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = null;
		$this->attrs = new \Runtime\Map();
		$this->slots = new \Runtime\Map();
		$this->items = new \Runtime\Vector();
		$this->is_raw = false;
		$this->is_render = false;
		$this->is_component = false;
		$this->name = "";
	}
	static function getClassName(){ return "Runtime.VirtualDom"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}