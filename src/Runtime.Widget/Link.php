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


class Link extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		$attrs = new \Runtime\Map();
		if ($this->target != "")
		{
			$attrs = $attrs->set("target", $this->target);
		}
		
		/* Element a */
		$__v0 = $__v->element("a", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector($this->class, $componentHash)), "href" => $this->href]))->concat($attrs));
		$__v0->push($this->text);
		
		return $__v;
	}
	var $href;
	var $text;
	var $target;
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->href = "";
		$this->text = "";
		$this->target = "_self";
	}
	static function getComponentStyle(){ return ""; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Link"; }
}