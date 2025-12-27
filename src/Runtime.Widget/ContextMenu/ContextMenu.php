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
namespace Runtime\Widget\ContextMenu;


class ContextMenu extends \Runtime\Component
{
	function renderItem($item)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("context_menu__item", $item->get("hidden") == true ? "hidden" : "", $componentHash))])));
		$__v0->push($item->get("label"));
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		$props = $this->getProps();
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("context_menu", $this->model->is_open ? "context_menu--open" : "context_menu--hide", $componentHash))]))->concat($props));
		
		for ($i = 0; $i < $this->model->items->count(); $i++)
		{
			$item = $this->model->items->get($i);
			$__v0->push($this->renderItem($item));
		}
		
		return $__v;
	}
	/**
	 * Returns props
	 */
	function getProps()
	{
		$styles = new \Runtime\Vector();
		if ($this->model->width != "")
		{
			$styles->push("max-width: " . $this->model->width);
		}
		$styles->push("left: " . $this->model->x . "px;");
		$styles->push("top: " . $this->model->y . "px;");
		return new \Runtime\Map([
			"style" => \Runtime\rs::join(";", $styles),
		]);
	}
	/**
	 * Click item
	 */
	function onClickItem($item)
	{
		$this->model->onClickItem($item);
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".context_menu.h-eb02{display: none;position: absolute;z-index: 99;background-color: var(--color-background);border: var(--border-width) var(--color-border) solid;border-bottom-width: 0}.context_menu--open.h-eb02{display: block}.context_menu__item.h-eb02{padding: calc(var(--space) * 0.75) var(--space);border-bottom: var(--border-width) var(--color-border) solid;cursor: pointer;user-select: none}.context_menu__item.h-eb02:hover{background-color: var(--color-hover)}.context_menu__item.hidden.h-eb02{display: none}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.ContextMenu.ContextMenu"; }
}