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


class Label extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element label */
		$__v0 = $__v->element("label", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("label", $componentHash))])));
		
		if (\Runtime\rtl::isString($this->value))
		{
			$__v0->push(!\Runtime\rtl::isEmpty($this->value) ? $this->value : "");
		}
		else if (\Runtime\rtl::is_instanceof($this->value, "Runtime.Vector"))
		{
			for ($i = 0; $i < $this->value->count(); $i++)
			{
				$item = $this->value->get($i);
				
				/* Element span */
				$__v1 = $__v0->element("span");
				$__v1->push(!\Runtime\rtl::isEmpty($item) ? $item : "");
			}
		}
		
		return $__v;
	}
	var $value;
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->value = "";
	}
	static function getComponentStyle(){ return ""; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Label"; }
}