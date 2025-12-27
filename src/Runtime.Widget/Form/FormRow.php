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
namespace Runtime\Widget\Form;

use Runtime\Widget\ResultModel;

class FormRow extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("form_row", $componentHash))])));
		
		/* Element label */
		$__v1 = $__v0->element("label");
		$__v1->push($this->label);
		
		/* Element div */
		$__v2 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("form_row__content", $componentHash))])));
		$__v2->push($this->renderSlot("default"));
		
		if ($this->result)
		{
			$__v0->push($this->renderWidget($this->result, new \Runtime\Map([
				"class" => "result--field",
			])));
		}
		
		return $__v;
	}
	var $label;
	var $result;
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->label = "";
		$this->result = null;
	}
	static function getComponentStyle(){ return ".form_row.h-df7a{margin-bottom: calc(var(--space) * 2)}.form_row.h-df7a:last-child{margin-bottom: calc(var(--space) * 2)}.form_row.h-df7a label{display: block;font-weight: bold;margin-bottom: calc(var(--space) * 0.5)}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Form.FormRow"; }
}