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
namespace Runtime\Widget\Tab;

use Runtime\Widget\Tab\TabsModel;

class Tab extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		$model = $this->getParent()->model;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tabs__item", $model->isActive($this->name) ? "tabs__item--active" : "", $componentHash)), "data-tab" => $this->name])));
		
		if ($this->canShow)
		{
			$__v0->push($this->renderSlot("default"));
		}
		
		return $__v;
	}
	var $name;
	var $title;
	var $href;
	/**
	 * Returns true if tab can show
	 */
	function canShow()
	{
		if ($this->href == "") return true;
		$model = $this->getParent()->model;
		if ($model->isActive($this->name)) return true;
		return false;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = "";
		$this->title = "";
		$this->href = "";
	}
	static function getComponentStyle(){ return ".tabs__item.h-8789{position: relative;display: none}.tabs__item--active.h-8789{display: block}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Tab.Tab"; }
}