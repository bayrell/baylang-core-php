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

use Runtime\Core\Message;
use Runtime\Web\Events\User\ChangeEvent;
use Runtime\Widget\Helper;


class SelectText extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element span */
		$__v0 = $__v->element("span", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("select_text", $componentHash))])));
		
		$item = $this->options ? $this->options->findItem(function ($item){ return $item->get("key") == $this->value; }) : null;
		$__v0->push($item != null ? $item->get("value") : $this->value);
		
		return $__v;
	}
	var $value;
	var $options;
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->value = "";
		$this->options = new \Runtime\Vector();
	}
	static function getComponentStyle(){ return ""; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.SelectText"; }
}