<?php
/*
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
 *
*/
namespace Runtime;

use Runtime\BaseModel;
use Runtime\Message;


class Component extends \Runtime\BaseObject
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		return $__v;
	}
	var $model;
	var $class;
	var $parent_component;
	var $layout;
	var $_slots;
	/**
	 * Returns layout
	 */
	function layout()
	{
	}
	/**
	 * Render slot
	 */
	function renderSlot($slot_name)
	{
		$f = $this->_slots->get($slot_name);
	return $f ? $f() : null;
	}
	/**
	 * Returns parent
	 */
	function getParent()
	{
		return $this->parent_component;
	}
	/**
	 * Returns ref
	 */
	function getRef($name)
	{
	}
	/**
	 * Emit message
	 */
	function emit($message)
	{
	}
	function __get($name)
	{
		if (method_exists($this, $name)) return $this->$name();
		return null;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->model = null;
		$this->class = "";
		$this->parent_component = null;
		$this->layout = null;
		$this->_slots = new \Runtime\Map();
	}
	static function getComponentStyle(){ return ""; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Component"; }
}