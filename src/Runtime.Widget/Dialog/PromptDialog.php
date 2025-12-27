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
namespace Runtime\Widget\Dialog;

use Runtime\Widget\Button;
use Runtime\Widget\Input;


class PromptDialog extends \Runtime\Widget\Dialog\Dialog
{
	function renderTitle()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->slot("title"))
		{
			$__v->push($this->renderSlot("title"));
		}
		else
		{
			$__v->push($this->model->title);
		}
		
		return $__v;
	}
	function renderContent()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog__content", $componentHash))])));
		
		if ($this->slot("content"))
		{
			$__v0->push($this->renderSlot("content"));
		}
		else
		{
			$__v0->push($this->model->content);
		}
		
		/* Element Runtime.Widget.Input */
		$__v0->element("Runtime.Widget.Input", (new \Runtime\Map(["name" => "value", "value" => $this->model->value])));
		$__v0->push($this->renderWidget($this->model->result, new \Runtime\Map([
			"class" => "result--center",
		])));
		
		return $__v;
	}
	function renderFooter()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog__footer", $componentHash))])));
		
		/* Element Runtime.Widget.Button */
		$__v1 = $__v0->element("Runtime.Widget.Button");
		
		/* Content */
		$__v1->slot("default", function ()
		{
			$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
			$__v = new \Runtime\VirtualDom($this);
			$__v->push("Close");
			return $__v;
		});
		
		/* Element Runtime.Widget.Button */
		$__v2 = $__v0->element("Runtime.Widget.Button", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("button--primary", $componentHash)), "style" => $this->model->title_button_styles])));
		
		/* Content */
		$__v2->slot("default", function ()
		{
			$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
			$__v = new \Runtime\VirtualDom($this);
			
			$__v->push($this->model->title_button ? $this->model->title_button : "Confirm");
			
			return $__v;
		});
		
		return $__v;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".dialog__content.h-688b .result{margin-top: calc(var(--space) * 0.5)}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Button", "Runtime.Widget.Input"); }
	static function getClassName(){ return "Runtime.Widget.Dialog.PromptDialog"; }
}