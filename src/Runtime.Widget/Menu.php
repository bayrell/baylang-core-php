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


class Menu extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("menu", $this->class, $componentHash))])));
		
		for ($i = 0; $i < $this->items->count(); $i++)
		{
			$item = $this->items->get($i);
			
			/* Element div */
			$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("menu__item", $this->menuActive($item->get("name")) ? "active" : "", $componentHash))])));
			
			/* Element a */
			$__v2 = $__v1->element("a", (new \Runtime\Map(["href" => $item->get("href"), "class" => \Runtime\rs::className(new \Runtime\Vector("nolink", $componentHash))])));
			$__v2->push($item->get("label"));
		}
		
		return $__v;
	}
	var $items;
	/**
	 * Returns true if menu is active
	 */
	function menuActive($route_name)
	{
		if ($this->layout->route == null) return false;
		if ($this->layout->route->name != $route_name) return false;
		return true;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->items = new \Runtime\Vector();
	}
	static function getComponentStyle(){ return ".menu__item.h-35e a{display: block;padding: var(--space);border-bottom: 1px solid var(--color-border)}.menu__item.h-35e a:hover{background-color: var(--color-hover)}.menu__item.active.h-35e a, .menu__item.active.h-35e a.nolink, .menu__item.active.h-35e a.nolink:visited{background-color: var(--color-selected);border-color: var(--color-selected);color: var(--color-selected-text)}.menu__item.h-35e:last-child a{border-bottom-width: 0px}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Menu"; }
}