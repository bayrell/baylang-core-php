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
*/
namespace Runtime\Widget;


class Field extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("field", $componentHash))])));
		
		return $__v;
	}
	var $readonly;
	var $id;
	var $name;
	var $value;
	var $default;
	var $placeholder;
	var $type;
	/**
	 * Returns textarea props
	 */
	function getProps()
	{
		$res = new \Runtime\Map();
		if ($this->readonly) $res->set("readonly", true);
		if ($this->id) $res->set("id", $this->id);
		return new \Runtime\Map();
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->readonly = false;
		$this->id = "";
		$this->name = "";
		$this->value = "";
		$this->default = "";
		$this->placeholder = "";
		$this->type = "text";
	}
	static function getComponentStyle(){ return ""; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Field"; }
}