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
namespace Runtime\Widget\Table;

use Runtime\Widget\Button;
use Runtime\Widget\WidgetResult;


class RefreshButton extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("refresh_button", $componentHash))])));
		
		$props = $this->model->getProps($this->data);
		
		/* Element Runtime.Widget.Button */
		$__v1 = $__v0->element("Runtime.Widget.Button", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector($this->class, $componentHash)), "render_list" => $this->render_list]))->concat($props));
		
		/* Content */
		$__v1->slot("default", function ()
		{
			$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
			$__v = new \Runtime\VirtualDom($this);
			$__v->push("Refresh");
			return $__v;
		});
		
		/* Element Runtime.Widget.WidgetResult */
		$__v0->element("Runtime.Widget.WidgetResult");
		
		return $__v;
	}
	/**
	 * Refresh item
	 */
	function onClick($e)
	{
		$this->model->onClick($this->data);
		$e->stopPropagation();
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".refresh_button.h-6726{display: flex;align-items: center;gap: 5px}.refresh_button.h-6726 %(WidgetResult)widget_result{margin-top: 0}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Button", "Runtime.Widget.WidgetResult"); }
	static function getClassName(){ return "Runtime.Widget.Table.RefreshButton"; }
}